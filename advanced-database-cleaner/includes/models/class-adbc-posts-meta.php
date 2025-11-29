<?php

// Exit if accessed directly or not on the main site.
if ( ! defined( 'ABSPATH' ) || ! is_main_site() ) {
	exit;
}

/**
 * ADBC posts meta class.
 * 
 * This class provides the posts meta functions.
 */
class ADBC_Posts_Meta {

	private const BIG_POSTMETA_THRESHOLD_WARNING = 150 * 1024; // 150 KB. (If you change this value, change it as well in js filter message and slice)
	private const TRUNCATE_LENGTH = 20; // Length to truncate the meta value for display

	/**
	 * Get the posts meta list for the endpoint.
	 *
	 * @param array $filters Output of sanitize_filters().
	 *
	 * @return WP_REST_Response The list of posts meta.
	 */
	public static function get_posts_meta_list( $filters ) {

		// Prepare variables
		$posts_meta_list = [];
		$total_posts_meta = 0;

		$big_posts_meta_count = self::count_big_posts_meta();
		$not_scanned_count = self::count_total_not_scanned_posts_meta();
		$duplicated_posts_meta_count = self::count_duplicated_posts_meta();
		$unused_posts_meta_count = self::count_unused_posts_meta();

		$scan_counter = new ADBC_Scan_Counter();

		$startRecord = ( $filters['current_page'] - 1 ) * $filters['items_per_page'];
		$endRecord = $startRecord + $filters['items_per_page'];
		$currentRecord = 0;

		$limit = ADBC_Settings::instance()->get_setting( 'database_rows_batch' );
		$offset = 0;

		do { // Loop through all posts meta in batches of $limit to avoid memory issues

			$posts_meta = self::get_posts_meta_list_batch( $filters, $limit, $offset );
			$fetched_count = count( $posts_meta );

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				ADBC_Scan_Results::instance()->load_scan_results_to_items_rows( $posts_meta, 'posts_meta' );
			else
				ADBC_Common_Model::load_scan_results_to_items_for_free_version( $posts_meta );

			ADBC_Hardcoded_Items::instance()->load_hardcoded_scan_results_to_items_rows( $posts_meta, 'posts_meta' ); // Load hardcoded items to the posts meta rows

			foreach ( $posts_meta as $index => $post_meta ) {

				$scan_counter->refresh_categorization_count( $post_meta->belongs_to );

				if ( ! ADBC_Common_Model::is_item_satisfies_belongs_to( $filters, $post_meta->belongs_to ) )
					continue;

				$total_posts_meta++; // Count posts meta that satisfy all filters and belongs_to

				// Only process the current batch if it's within the desired page range
				if ( $currentRecord >= $startRecord && $currentRecord < $endRecord ) {

					$posts_meta_list[] = [ 
						// This id is used to identify the post meta in the frontend and take actions on it
						'composite_id' => [ 
							'items_type' => 'posts_meta',
							'site_id' => (int) $post_meta->site_id,
							'id' => (int) $post_meta->meta_id,
							'name' => $post_meta->name,
						],
						'id' => $post_meta->meta_id,
						'name' => $post_meta->name, // Used in the known addons modal & "show value modal". To be generic and work for all items types.
						'meta_key' => $post_meta->name,
						'value' => $post_meta->value,
						'is_truncated' => $post_meta->is_truncated,
						'size' => $post_meta->size,
						'post_id' => $post_meta->post_id,
						'site_id' => $post_meta->site_id,
						'belongs_to' => $post_meta->belongs_to,
						'known_plugins' => $post_meta->known_plugins,
						'known_themes' => $post_meta->known_themes,
					];
				}

				$currentRecord++;
			}

			$offset += $limit;

		} while ( $fetched_count == $limit ); // Continue if the last batch was full

		// Loop over the $posts_meta_list and $scan_counter add the plugins/themes names from the dictionary if they are empty
		// This is because load_scan_results_to_rows() only loads the names of the plugins/themes that are currently installed
		if ( ADBC_VERSION_TYPE === 'PREMIUM' )
			ADBC_Dictionary::add_missing_addons_names_from_dictionary( $posts_meta_list, $scan_counter, 'posts_meta' );

		// Calculate total number of pages to verify that the current page sent by the user is within the range
		$total_real_pages = max( 1, ceil( $total_posts_meta / $filters['items_per_page'] ) );

		return ADBC_Rest::success( "", [ 
			'items' => $posts_meta_list,
			'total_items' => $total_posts_meta,
			'real_current_page' => min( $filters['current_page'], $total_real_pages ),
			'big_posts_meta_count' => $big_posts_meta_count,
			'not_scanned_count' => $not_scanned_count,
			'duplicated_posts_meta_count' => $duplicated_posts_meta_count,
			'unused_posts_meta_count' => $unused_posts_meta_count,
			'categorization_count' => $scan_counter->get_categorization_count(),
			'plugins_count' => $scan_counter->get_plugins_count(),
			'themes_count' => $scan_counter->get_themes_count(),
		] );
	}

	/**
	 * Get the posts meta list that satisfy the UI filters.
	 *
	 * @param array $filters Output of sanitize_filters().
	 * @param int $limit Limit for the number of rows to return.
	 * @param int $offset Offset for the number of rows to return.
	 *
	 * @return array List of posts meta that satisfy the filters.
	 */
	private static function get_posts_meta_list_batch( $filters, $limit, $offset ) {

		global $wpdb;
		$sites_list = ADBC_Sites::instance()->get_sites_list( $filters['site_id'] );
		$union_queries = [];

		/* ────────────────────────────────────────────────────────────
		 * Build a safe ORDER BY clause
		 * ────────────────────────────────────────────────────────────*/
		$allowed_columns = [ 
			'meta_key' => '`name`',
			'size' => '`size`',
			'post_id' => '`post_id`',
			'site_id' => '`site_id`',
		];

		$sort_col = $filters['sort_by'] ?? '';
		$sort_dir = strtoupper( $filters['sort_order'] ?? 'ASC' );
		$sort_dir = $sort_dir === 'DESC' ? 'DESC' : 'ASC';

		// Add 'order by' clause if the column is allowed.
		$order_by_sql = isset( $allowed_columns[ $sort_col ] )
			? "ORDER BY {$allowed_columns[ $sort_col ]} {$sort_dir}"
			: '';

		/* ────────────────────────────────────────────────────────────
		 * Loop through all sites and prepare the SQL for each site
		 * ────────────────────────────────────────────────────────────*/
		// Offset starts from 0, so we need to add the limit to it to increment the number of rows to fetch in each iteration.
		$total_rows_to_fetch = $offset + $limit;
		foreach ( $sites_list as $site ) {
			$table_name = $site['prefix'] . "postmeta"; // Get the postmeta table name for the current site
			$site_id = $site['id']; // Get the site ID for the current site
			$union_queries[] = self::prepare_posts_meta_list_sql_for_union( $site_id, $table_name, $filters, $order_by_sql, $total_rows_to_fetch );
		}

		$union_sql = implode( "\nUNION ALL\n", $union_queries );

		$sql = $wpdb->prepare(
			"SELECT *
				FROM ( {$union_sql} ) AS rows_merged
				{$order_by_sql}
				LIMIT %d OFFSET %d
			",
			$limit,
			$offset
		);

		return $wpdb->get_results( $sql, OBJECT );
	}

	/**
	 * Prepare a SQL query string to get the posts meta list that satisfy the UI filters. It will be used in a UNION query to get all posts meta in all sites.
	 *
	 * @param int $site_id Site ID to query.
	 * @param string $table_name Postmeta table name to query.
	 * @param array $filters Output of sanitize_filters().
	 * @param string $order_by_sql SQL query clause to order the results.
	 * @param int $total_rows_to_fetch Limit for the number of rows to return.
	 *
	 * @return string SQL query to get the posts meta list.
	 */
	private static function prepare_posts_meta_list_sql_for_union( $site_id, $table_name, $filters, $order_by_sql, $total_rows_to_fetch ) {

		global $wpdb;
		$truncate_length = self::TRUNCATE_LENGTH;
		$params = [ absint( $site_id ) ];  // Place the site_id at the beginning of the params array

		/* ────────────────────────────────────────────────────────────
		 * 2. Assemble the dynamic WHERE parts
		 * ────────────────────────────────────────────────────────────*/
		$where = [];

		/* — Search filter — */
		if ( ! empty( $filters['search_for'] ) && ! empty( $filters['search_in'] ) ) {

			$needle = '%' . $wpdb->esc_like( $filters['search_for'] ) . '%';

			switch ( $filters['search_in'] ) {
				case 'name':
					$where[] = 'main.`meta_key` LIKE %s';
					$params[] = $needle;
					break;

				case 'value':
					$where[] = 'main.`meta_value` LIKE %s';
					$params[] = $needle;
					break;

				case 'all':
					// Search in both columns
					$where[] = '(main.`meta_key` LIKE %s OR main.`meta_value` LIKE %s)';
					$params[] = $needle;   // for meta_key
					$params[] = $needle;   // for meta_value
					break;
			}
		}

		/* — Size ≥ threshold — */
		if ( ! empty( $filters['size'] ) && (int) $filters['size'] > 0 ) {
			$bytes = ADBC_Common_Utils::convert_size_to_bytes(
				$filters['size'],
				$filters['size_unit']
			);
			$where[] = 'OCTET_LENGTH(main.`meta_value`) >= %d';
			$params[] = $bytes;
		}

		/* — Duplicated filter — */
		if ( isset( $filters['duplicated'] ) && in_array( $filters['duplicated'], [ 'yes', 'no' ], true ) ) {
			$exists_sql = "EXISTS (SELECT 1 FROM {$table_name} dup WHERE dup.post_id = main.post_id AND dup.meta_key = main.meta_key AND dup.meta_value = main.meta_value AND dup.meta_id < main.meta_id)";
			if ( $filters['duplicated'] === 'yes' ) {
				$where[] = $exists_sql;
			} else {
				$where[] = "NOT ( {$exists_sql} )";
			}
		}

		/* — Unused filter — */
		if ( isset( $filters['unused'] ) && in_array( $filters['unused'], [ 'yes', 'no' ], true ) ) {
			$posts_table = str_replace( 'postmeta', 'posts', $table_name );
			$unused_sql = "main.post_id NOT IN (SELECT ID FROM {$posts_table})";
			if ( $filters['unused'] === 'yes' ) {
				$where[] = $unused_sql;
			} else {
				$where[] = "NOT ( {$unused_sql} )";
			}
		}

		$where_sql = ! empty( $where ) ? 'WHERE ' . implode( ' AND ', $where ) : '';
		$params[] = absint( $total_rows_to_fetch ); // Add the limit to the params array

		/* ────────────────────────────────────────────────────────────
		 * 3. Final SQL and fetch
		 * ────────────────────────────────────────────────────────────*/
		$sql = $wpdb->prepare(
			"SELECT
				main.`meta_key`                         AS name,
				main.`meta_id`                          AS meta_id,
				main.`post_id`                          AS post_id,
				CASE
					WHEN CHAR_LENGTH(main.`meta_value`) > $truncate_length
					THEN SUBSTRING(main.`meta_value`, 1, $truncate_length)
					ELSE main.`meta_value`
				END                                AS value,
				CASE
					WHEN CHAR_LENGTH(main.`meta_value`) > $truncate_length
					THEN 1
					ELSE 0
				END             			   AS is_truncated,
				OCTET_LENGTH(main.`meta_value`)         AS size,
				%d						   AS site_id
			FROM {$table_name} main
			{$where_sql}
			{$order_by_sql}
			LIMIT %d
			",
			...$params
		);

		return '(' . $sql . ')';
	}

	/**
	 * Get the count of big posts meta in all sites.
	 * 
	 * @return int Total count of big posts meta.
	 */
	public static function count_big_posts_meta() {

		global $wpdb;
		$total_big_posts_meta = 0;

		$sites_prefixes = array_keys( ADBC_Sites::instance()->get_all_prefixes() );

		foreach ( $sites_prefixes as $site_prefix ) {

			$table = $site_prefix . "postmeta";

			$count = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*)
						FROM `$table`
						WHERE OCTET_LENGTH(meta_value) > %d",
					self::BIG_POSTMETA_THRESHOLD_WARNING
				)
			);

			$total_big_posts_meta += $count;
		}

		return $total_big_posts_meta;
	}

	/**
	 * Count duplicated postmeta across all sites.
	 * A duplicated postmeta is defined as same (meta_key, meta_value, post_id) with a smaller meta_id existing.
	 *
	 * @return int
	 */
	public static function count_duplicated_posts_meta() {

		global $wpdb;
		$total = 0;

		$sites_prefixes = array_keys( ADBC_Sites::instance()->get_all_prefixes() );
		foreach ( $sites_prefixes as $site_prefix ) {
			$table = $site_prefix . 'postmeta';
			$count = (int) $wpdb->get_var( "
				SELECT COUNT(*)
				FROM {$table} main
				WHERE EXISTS (
					SELECT 1 FROM {$table} dup
					WHERE dup.post_id = main.post_id
						AND dup.meta_key = main.meta_key
						AND dup.meta_value = main.meta_value
						AND dup.meta_id < main.meta_id
				)
			" );
			$total += $count;
		}

		return $total;
	}

	/**
	 * Count unused postmeta across all sites.
	 * An unused postmeta is a meta referencing a non-existing post_id.
	 *
	 * @return int
	 */
	public static function count_unused_posts_meta() {

		global $wpdb;
		$total = 0;

		$sites = ADBC_Sites::instance()->get_sites_list();
		foreach ( $sites as $site ) {
			$postmeta = $site['prefix'] . 'postmeta';
			$posts = $site['prefix'] . 'posts';
			$count = (int) $wpdb->get_var( "
				SELECT COUNT(*)
				FROM {$postmeta} main
				LEFT JOIN {$posts} p ON p.ID = main.post_id
				WHERE p.ID IS NULL
			" );
			$total += $count;
		}

		return $total;
	}

	/**
	 * Count the total number of posts meta that are not scanned.
	 * 
	 * @return int Total not scanned posts meta.
	 */
	public static function count_total_not_scanned_posts_meta() {

		$sites_prefixes = array_keys( ADBC_Sites::instance()->get_all_prefixes() );
		$total_not_scanned = 0;
		$limit = ADBC_Settings::instance()->get_setting( 'database_rows_batch' );

		foreach ( $sites_prefixes as $site_prefix ) {

			$offset = 0;

			do { // Loop through all posts meta in batches of $limit to avoid memory issues

				$posts_meta = self::get_posts_meta_names( $site_prefix, $limit, $offset, false );
				$fetched_count = count( $posts_meta );
				$not_scanned_count = 0;

				if ( ADBC_VERSION_TYPE === 'PREMIUM' )
					$not_scanned_count = ADBC_Scan_Utils::count_not_scanned_items_in_list( "posts_meta", $posts_meta );
				else
					$not_scanned_count = ADBC_Common_Model::count_not_scanned_items_in_list_for_free( "posts_meta", $posts_meta );

				$total_not_scanned += $not_scanned_count;

				$offset += $limit;

			} while ( $fetched_count == $limit ); // Continue if the last batch was full

		}

		return $total_not_scanned;

	}

	/**
	 * Get post meta keys for a specific site.
	 * 
	 * @param string $site_prefix Site prefix.
	 * @param int $limit Limit.
	 * @param int $offset Offset.
	 * @param boolean $keyed wether or not to key the array by names
	 * 
	 * @return array Post meta keys.
	 */
	public static function get_posts_meta_names( $site_prefix, $limit, $offset, $keyed = true ) {

		global $wpdb;
		$table = $site_prefix . 'postmeta'; // $site_prefix is safe to use here as it is validated in the calling function.

		$query = $wpdb->prepare(
			"SELECT meta_key FROM `$table` 
			 LIMIT %d OFFSET %d",
			absint( $limit ),
			absint( $offset )
		);

		$posts_meta_names = $wpdb->get_col( $query );

		if ( $keyed )
			return array_fill_keys( $posts_meta_names, true );
		else
			return $posts_meta_names;

	}

	/**
	 * Get post meta keys from their ids in a specific site prefix.
	 * 
	 * @param string $site_prefix Site prefix of the post meta.
	 * @param array $posts_meta_ids Post meta ids to get their keys.
	 * 
	 * @return array Associative post meta keys.
	 */
	public static function get_posts_meta_names_from_ids( $site_prefix, $posts_meta_ids ) {

		global $wpdb;
		$table = $site_prefix . 'postmeta'; // $site_prefix is safe to use here as it is validated in the calling function.

		if ( empty( $posts_meta_ids ) )
			return [];

		$in_placeholders = implode( ',', array_fill( 0, count( $posts_meta_ids ), '%d' ) );

		$query = $wpdb->prepare(
			"SELECT DISTINCT meta_key
			 FROM `$table`
			 WHERE meta_id IN ($in_placeholders)",
			...$posts_meta_ids
		);

		$posts_meta_names = $wpdb->get_col( $query );

		// transform the posts_meta names array to associative array with the option name as key and true as value
		$posts_meta_names = array_fill_keys( $posts_meta_names, true );

		return $posts_meta_names;

	}

	/**
	 * Delete grouped posts meta. Posts meta are grouped by site ID as key.
	 * 
	 * @param array $grouped_selected Grouped selected posts meta to delete.
	 * 
	 * @return array An array of posts meta names that were not processed (not deleted).
	 */
	public static function delete_posts_meta( $grouped_selected ) {

		global $wpdb;

		$not_processed = [];

		foreach ( $grouped_selected as $site_id => $group ) {

			ADBC_Sites::instance()->switch_to_blog_id( $site_id );

			foreach ( $group as $selected ) {

				// try deleting using standard wordpress function
				$success = delete_metadata_by_mid( 'post', $selected['id'] );

				// try deleting using direct sql by meta id to be sure there's no problem in the name
				if ( ! $success )
					$success = $wpdb->delete( $wpdb->postmeta, array( 'meta_id' => $selected['id'] ) );

				if ( ! $success )
					$not_processed[] = $selected['name'];

			}

			ADBC_Sites::instance()->restore_blog();
		}

		return $not_processed;
	}

	/**
	 * Get posts meta names that still exist anywhere across the network from a provided list.
	 *
	 * @param array $posts_meta_names List of meta_key names to check for existence.
	 *
	 * @return array Existing names found across all sites.
	 */
	public static function get_posts_meta_names_that_exists_from_list( $posts_meta_names ) {

		global $wpdb;

		if ( empty( $posts_meta_names ) || ! is_array( $posts_meta_names ) )
			return [];

		$branches = [];
		$sites = ADBC_Sites::instance()->get_sites_list();
		$in_placeholders = implode( ',', array_fill( 0, count( $posts_meta_names ), '%s' ) );

		foreach ( $sites as $site ) {
			$table = $site['prefix'] . 'postmeta';
			$sql = $wpdb->prepare(
				"SELECT DISTINCT meta_key AS name
				 FROM `{$table}`
				 WHERE meta_key IN ( {$in_placeholders} )",
				...$posts_meta_names
			);
			$branches[] = '(' . $sql . ')';
		}

		if ( empty( $branches ) )
			return [];

		$union_sql = implode( "\nUNION ALL\n", $branches );
		$query = "SELECT DISTINCT name FROM ( {$union_sql} ) AS existing_names";

		$existing_names = $wpdb->get_col( $query );

		return array_values( array_unique( array_filter( (array) $existing_names ) ) );
	}

	/**
	 * Get total posts meta count across all sites.
	 * 
	 * @return int Total posts meta count.
	 */
	public static function get_total_posts_meta_count() {

		global $wpdb;
		$total_posts_meta = 0;

		$sites = ADBC_Sites::instance()->get_sites_list();

		foreach ( $sites as $site ) {

			$table = $site['prefix'] . "postmeta";

			$count = (int) $wpdb->get_var( "SELECT COUNT(*)	FROM `$table`" );

			$total_posts_meta += $count;

		}

		return $total_posts_meta;

	}

}