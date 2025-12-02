<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC tables class.
 * 
 * This class provides tables functions.
 */
class ADBC_Tables {

	private const TIME_TO_REFRESH_TO_REPAIR_TRANSIENT = 3600; // 1 hour in seconds

	/**
	 * Get the tables list for the endpoint.
	 *
	 * @param array $filters Output of sanitize_filters().
	 *
	 * @return WP_REST_Response The list of tables.
	 */
	public static function get_tables_list( $filters ) {

		$show_tables_with_invalid_prefix = ADBC_Settings::instance()->get_setting( 'show_tables_with_invalid_prefix' );
		$tables_to_repair_data = self::get_tables_to_repair(); // Get the tables to repair data and refresh the count if needed

		// Prepare variables
		$tables_list = [];
		$total_tables = 0;
		$total_tables_to_optimize = 0;
		$total_tables_to_repair = $tables_to_repair_data[0];
		$total_tables_with_invalid_prefix = 0;
		$total_not_scanned = 0;

		$scan_counter = new ADBC_Scan_Counter();

		$startRecord = ( $filters['current_page'] - 1 ) * $filters['items_per_page'];
		$endRecord = $startRecord + $filters['items_per_page'];
		$currentRecord = 0;

		$limit = ADBC_Settings::instance()->get_setting( 'database_rows_batch' );
		$offset = 0;

		do { // Loop through all tables in batches of $limit to avoid memory issues

			$tables = self::get_tables_list_batch( $filters['sort_by'], $filters['sort_order'], $limit, $offset );
			$fetched_count = count( $tables );

			// If the user want to not show tables with invalid prefix, remove them from the list
			if ( $show_tables_with_invalid_prefix === "0" )
				self::remove_tables_with_invalid_prefix_from_rows( $tables );

			self::add_tables_data_to_rows( $tables ); // Add site id, prefix and table name without prefix

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				ADBC_Scan_Results::instance()->load_scan_results_to_tables_rows( $tables ); // Load scan results to the tables rows
			else
				ADBC_Common_Model::load_scan_results_to_items_for_free_version( $tables ); // Load scan results to the tables rows for free version

			ADBC_Hardcoded_Items::instance()->load_hardcoded_scan_results_to_tables_rows( $tables ); // Load hardcoded items to the tables rows

			foreach ( $tables as $table_name => $table_data ) {

				/* ──────────────────────────────────────────────────────────────
				 * Prepare counts for health check filters
				 * ─────────────────────────────────────────────────────────────*/

				// Count overhead for tables that are not InnoDB
				if ( $table_data->type !== 'InnoDB' && $table_data->overhead > 0 )
					$total_tables_to_optimize++;

				// count tables with invalid prefix
				if ( $show_tables_with_invalid_prefix === "1" && ! self::is_table_having_valid_prefix( $table_name ) )
					$total_tables_with_invalid_prefix++;

				// count not scanned tables
				if ( $table_data->belongs_to['type'] == 'u' )
					$total_not_scanned++;

				/* ──────────────────────────────────────────────────────────────────────────────────
				 * Ignore tables that don't satisfy the filters and belongs_to, then process the rest
				 * ─────────────────────────────────────────────────────────────────────────────────*/

				if ( ! self::is_table_satisfies_filters( $table_name, $table_data, $filters ) )
					continue;


				$scan_counter->refresh_categorization_count( $table_data->belongs_to );

				if ( ! ADBC_Common_Model::is_item_satisfies_belongs_to( $filters, $table_data->belongs_to ) )
					continue;

				$total_tables++; // Count tables that satisfy all filters and belongs_to

				// Only process the current batch if it's within the desired page range
				if ( $currentRecord >= $startRecord && $currentRecord < $endRecord ) {

					$tables_list[] = [ 
						// This id is used to identify the table in the frontend and take actions on it
						'composite_id' => [ 
							'items_type' => 'tables',
							'name' => $table_name,
						],
						'table_name' => $table_name,
						'name' => $table_data->table_name_without_prefix, // Used in the known addons modal
						'prefix' => $table_data->prefix,
						'name_without_prefix' => $table_data->table_name_without_prefix,
						'size' => $table_data->size,
						'rows' => $table_data->rows,
						'overhead' => ADBC_Common_Utils::format_bytes( $table_data->overhead ),
						'raw_overhead' => $table_data->overhead,
						'type' => $table_data->type,
						'site_id' => $table_data->site_id,
						'belongs_to' => $table_data->belongs_to,
						'known_plugins' => $table_data->known_plugins,
						'known_themes' => $table_data->known_themes
					];
				}

				$currentRecord++;
			}

			$offset += $limit;

		} while ( $fetched_count == $limit ); // Continue if the last batch was full

		// Loop over the $tables_list and $scan_counter add the plugins/themes names from the dictionary if they are empty
		// This is because load_scan_results_to_tables_rows() only loads the names of the plugins/themes that are currently installed
		if ( ADBC_VERSION_TYPE === 'PREMIUM' )
			ADBC_Dictionary::add_missing_addons_names_from_dictionary( $tables_list, $scan_counter, 'tables' );

		// Calculate total number of pages to verify that the current page sent by the user is within the range
		$total_real_pages = max( 1, ceil( $total_tables / $filters['items_per_page'] ) );

		return ADBC_Rest::success( "", [ 
			'items' => $tables_list,
			'total_items' => $total_tables,
			'real_current_page' => min( $filters['current_page'], $total_real_pages ),
			'to_optimize_count' => $total_tables_to_optimize,
			'to_repair_count' => $total_tables_to_repair,
			'invalid_prefix_count' => $total_tables_with_invalid_prefix,
			'not_scanned_count' => $total_not_scanned,
			'categorization_count' => $scan_counter->get_categorization_count(),
			'plugins_count' => $scan_counter->get_plugins_count(),
			'themes_count' => $scan_counter->get_themes_count()
		] );
	}

	/**
	 * Get the tables list with the given order by SQL, limit and offset.
	 *
	 * @param string $order_by_sql The order by SQL.
	 * @param int $limit The limit.
	 * @param int $offset The offset.
	 * @return array The list of tables.
	 */
	public static function get_tables_list_batch( $sort_by, $sort_order, $limit, $offset ) {

		global $wpdb;

		/* ──────────────────────────────────────────────────────────────
		 * Build a safe ORDER BY clause
		 * ─────────────────────────────────────────────────────────────*/
		$allowed_columns = [ 
			'table_name' => '`table_name`',
			'size' => '`size`',
			'rows' => '`rows`',
			'type' => '`type`',
			'overhead' => '`overhead`'
		];
		$sort_col = $sort_by ?? '';
		$sort_dir = strtoupper( $sort_order ?? 'ASC' );
		$sort_dir = $sort_dir === 'DESC' ? 'DESC' : 'ASC';

		// Special handling for sorting by site_id
		if ( $sort_col === 'site_id' ) {
			$prefix_list = ADBC_Sites::instance()->get_all_prefixes(); // [ prefix => site_id ]

			// Ensure longest-prefix-first matching to handle nested/similar prefixes accurately
			uksort( $prefix_list, function ($a, $b) {
				$lenA = strlen( (string) $a );
				$lenB = strlen( (string) $b );
				if ( $lenA === $lenB )
					return 0;
				return ( $lenA > $lenB ) ? -1 : 1; // Desc by length
			} );

			$case_parts = [];
			foreach ( $prefix_list as $prefix => $site_id ) {
				$like = $wpdb->esc_like( $prefix ) . '%';
				$case_parts[] = "WHEN `TABLE_NAME` LIKE '{$like}' THEN " . absint( $site_id );
			}
			if ( ! empty( $case_parts ) ) {
				$case_expr = '(CASE ' . implode( ' ', $case_parts ) . ' ELSE 2147483647 END)';
				$order_by_sql = "ORDER BY {$case_expr} {$sort_dir}";
			}
		} else {
			// Add 'order by' clause if the column is allowed.
			$order_by_sql = isset( $allowed_columns[ $sort_col ] )
				? "ORDER BY {$allowed_columns[ $sort_col ]} {$sort_dir}"
				: '';
		}

		$sql_rows = $wpdb->prepare(
			"SELECT 
				`TABLE_NAME` AS `table_name`, 
				(`DATA_LENGTH` + `INDEX_LENGTH`) AS `size`, 
				`TABLE_ROWS` AS `rows`, 
				`DATA_FREE` as `overhead`, 
				`ENGINE` AS `type`
			FROM 
				`information_schema`.`TABLES`
			WHERE 
				`TABLE_SCHEMA` = %s
			$order_by_sql
			LIMIT %d OFFSET %d",
			DB_NAME,
			absint( $limit ),
			absint( $offset )
		);

		return $wpdb->get_results( $sql_rows, OBJECT_K );
	}

	/**
	 * Get database tables count.
	 *
	 * @return int Database tables count.
	 */
	public static function get_total_tables_count() {

		global $wpdb;
		$sql = $wpdb->prepare( "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s", DB_NAME );
		$count = $wpdb->get_var( $sql );
		return $count;
	}

	/**
	 * Get the count of tables with invalid prefix.
	 *
	 * @return int The count of tables with invalid prefix.
	 */
	public static function get_total_tables_with_invalid_prefix_count() {

		global $wpdb;

		$sql = $wpdb->prepare( "SELECT `TABLE_NAME` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s", DB_NAME );

		$all_tables = $wpdb->get_col( $sql );

		$count = 0;
		foreach ( $all_tables as $table ) {
			if ( ! self::is_table_having_valid_prefix( $table ) ) {
				$count++;
			}
		}

		return $count;

	}

	/**
	 * Get the tables names with or without prefix for the given limit and offset.
	 *
	 * @param int $limit The limit.
	 * @param int $offset The offset.
	 * @param bool $with_prefix True to include the prefix in the table name, false otherwise.
	 * @param bool $return_invalid_prefix_tables True to return the tables with invalid prefix, false otherwise.
	 * @return array The list of tables names with or without prefix as keys of the associative array.
	 */
	public static function get_tables_names( $limit, $offset, $with_prefix = true, $return_invalid_prefix_tables = true ) {

		global $wpdb;

		$tables_names = [];

		$sql_rows = $wpdb->prepare(
			"SELECT `TABLE_NAME` FROM `information_schema`.`TABLES`
				WHERE `TABLE_SCHEMA` = %s
				LIMIT %d OFFSET %d",
			DB_NAME,
			absint( $limit ),
			absint( $offset )
		);

		$tables_names_with_prefix = $wpdb->get_col( $sql_rows );

		foreach ( $tables_names_with_prefix as $table ) {

			// Don't add the table if it is not having a valid prefix and we don't want to return invalid prefix tables
			if ( $return_invalid_prefix_tables === false && ! self::is_table_having_valid_prefix( $table ) ) {
				continue;
			}

			// If we don't want to return the prefix, remove it from the table name
			if ( $with_prefix === false ) {
				$table = self::remove_prefix_from_table_name( $table );
			}

			$tables_names[ $table ] = true; // Use the table name as the key and true as dummy value

		}

		return $tables_names;

	}

	/**
	 * Get the table prefix, blog id and table name without prefix.
	 *
	 * @param string $table_name The table name.
	 * @return array The table prefix, blog id and table name without prefix.
	 */
	public static function get_table_prefix_and_blog_id( $table_name ) {

		$prefix_list = ADBC_Sites::instance()->get_all_prefixes();
		$found_prefix = '';
		$table_site_id = 'N/A'; // Do not change this default value, it is used elsewhere.

		// Find the longest matching prefix
		foreach ( $prefix_list as $prefix => $site_id ) {
			if ( strpos( $table_name, $prefix ) === 0 && strlen( $prefix ) > strlen( $found_prefix ) ) {
				$found_prefix = $prefix;
				$table_site_id = $site_id;
			}
		}

		// Prepare the table name without prefix
		$table_name_without_prefix = $found_prefix ? substr( $table_name, strlen( $found_prefix ) ) : $table_name;

		return [ 
			'prefix' => $found_prefix,
			'site_id' => $table_site_id,
			'table_name_without_prefix' => $table_name_without_prefix
		];

	}

	/**
	 * Check if the table is having valid prefix.
	 *
	 * @param string $table_name The table name.
	 * @return bool True if the table is having valid prefix, false otherwise.
	 */
	public static function is_table_having_valid_prefix( $table_name ) {

		$prefix_list = ADBC_Sites::instance()->get_all_prefixes();

		foreach ( $prefix_list as $prefix => $site_id ) {

			if ( strpos( $table_name, $prefix ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Remove the prefix from the table name.
	 *
	 * @param string $table_name The table name.
	 * @return string The table name without prefix.
	 */
	public static function remove_prefix_from_table_name( $table_name ) {

		$table_info = self::get_table_prefix_and_blog_id( $table_name );
		return $table_info['table_name_without_prefix'];

	}

	/**
	 * Get the count and list of tables to repair.
	 *
	 * @return array The list of tables to repair. The first element is the count of tables, the second element is the list of tables.
	 */
	public static function get_tables_to_repair() {

		global $wpdb;
		$transient = get_transient( 'adbc_plugin_tables_to_repair' );

		// Check if the transient is set and has a value
		if ( $transient !== false && is_array( $transient ) )
			return [ count( $transient ), $transient ];

		// If the transient is not set, does not have a value or is expired, refresh it
		$corrupted_tables = [];
		$limit = ADBC_Settings::instance()->get_setting( 'database_rows_batch' );
		$offset = 0;
		$limit_for_sql = 20; // Number of tables to run CHECK TABLE on at once
		$db_dot = DB_NAME . '.'; // used for stripping later
		$db_dot_length = strlen( $db_dot );
		$quick = is_multisite() ? 'QUICK' : '';  // QUICK = header-only scan, harmless on busy sites

		do { // Loop through all tables in batches

			$tables = self::get_tables_names( $limit, $offset, true, true ); // Get all the tables names with their prefixes
			$fetched_count = count( $tables );
			$tables_names = array_keys( $tables );

			// Execute CHECK TABLE in batches of $limit_for_sql
			for ( $mini_offset = 0; $mini_offset < $fetched_count; $mini_offset += $limit_for_sql ) {

				$batch = array_slice( $tables_names, $mini_offset, $limit_for_sql );
				$quoted = '`' . implode( '`, `', $batch ) . '`';
				$results = $wpdb->get_results( "CHECK TABLE $quoted {$quick}" );

				foreach ( $results as $row ) {

					if ( strtolower( $row->Msg_type ) == 'error' && stripos( $row->Msg_text, 'corrupt' ) !== false ) {

						// strip "dbname." from the table name. Because $row->Table is dbname.table_name
						$table = stripos( $row->Table, $db_dot ) === 0
							? substr( $row->Table, $db_dot_length )
							: $row->Table;

						$corrupted_tables[] = $table;
					}
				}
			}

			$offset += $limit;

		} while ( $fetched_count == $limit ); // Continue if the last batch was full

		$corrupted_tables = array_unique( $corrupted_tables );
		set_transient( 'adbc_plugin_tables_to_repair', $corrupted_tables, self::TIME_TO_REFRESH_TO_REPAIR_TRANSIENT );
		return [ count( $corrupted_tables ), $corrupted_tables ];
	}

	/**
	 * Filter tables by the given filters.
	 * 
	 * @param string $table_name The table name.
	 * @param object $table_data The table data.
	 * @param array $filters The filters.
	 * @return bool True if the table satisfies the filters, false otherwise.
	 */
	public static function is_table_satisfies_filters( $table_name, $table_data, $filters ) {

		// Filter by search
		if ( ! empty( $filters['search_for'] ) && strpos( $table_name, $filters['search_for'] ) === false ) {
			return false;
		}

		// Filter by "to_optimize"
		if ( $filters['table_status'] === 'to_optimize' && ( $table_data->overhead <= 0 || $table_data->type === "InnoDB" ) ) {
			return false;
		}

		// Filter by "to_repair"
		if ( $filters['table_status'] === 'to_repair' ) {

			// Repair tables works only for MyISAM, ARCHIVE and CSV tables
			if ( ! in_array( $table_data->type, [ 'MyISAM', 'ARCHIVE', 'CSV' ], true ) )
				return false;

			// Get the list of corrupted tables from the transient
			$corrupted_tables_transient = get_transient( 'adbc_plugin_tables_to_repair' );
			if ( $corrupted_tables_transient === false || ! is_array( $corrupted_tables_transient ) || empty( $corrupted_tables_transient ) )
				return false;

			// Check if the table is in the list of corrupted tables
			if ( ! in_array( $table_name, $corrupted_tables_transient, true ) )
				return false;
		}

		// Filter by "valid_prefix"
		if ( $filters['prefix_status'] === 'valid_prefix' && ! self::is_table_having_valid_prefix( $table_name ) ) {
			return false;
		}

		// Filter by "invalid_prefix"
		if ( $filters['prefix_status'] === 'invalid_prefix' && self::is_table_having_valid_prefix( $table_name ) ) {
			return false;
		}

		// Filter by size
		$size_filter = ADBC_Common_Utils::convert_size_to_bytes( $filters['size'], $filters['size_unit'] );
		if ( $filters['size'] > 0 && $table_data->size < $size_filter ) {
			return false;
		}

		// Filter by site ID
		if ( $filters['site_id'] != 'all' && $table_data->site_id != $filters['site_id'] ) {
			return false;
		}

		return true;

	}

	/**
	 * Optimize the list of the provided tables.
	 *
	 * @param array $tables_names The list of tables names to optimize.
	 * @return array The list of tables that were not optimized.
	 */
	public static function optimize_tables( $tables_names ) {

		global $wpdb;
		$not_optimized = [];

		// Loop through the list of tables and optimize them
		foreach ( $tables_names as $table_name ) {

			$result = $wpdb->get_results( "OPTIMIZE TABLE `{$table_name}`" );

			// Check if the table is optimized successfully.
			foreach ( $result as $row ) {
				if ( $row->Msg_type == 'status' ) {
					if ( strtolower( $row->Msg_text ) == 'table is already up to date' || strtolower( $row->Msg_text ) == 'ok' ) {
						$wpdb->query( "ANALYZE TABLE `{$table_name}`" ); // Analyze the table to update the table data
					} else {
						$not_optimized[] = $table_name; // If the query failed, add the table name to the not optimized list
					}
				}
			}

		}

		return $not_optimized;
	}

	/**
	 * Delete the list of the provided tables.
	 *
	 * @param array $tables_names The list of tables names to delete.
	 * @return array The list of tables that were not deleted.
	 */
	public static function delete_tables( $tables_names ) {

		global $wpdb;
		$not_deleted = [];

		// Loop through the selected tables and delete them
		foreach ( $tables_names as $table_name ) {

			$deleted = $wpdb->query( "DROP TABLE `{$table_name}`" );

			if ( ! $deleted )
				$not_deleted[] = $table_name; // If the query failed, add the table name to the not deleted list
		}

		// Delete the transient to force refresh the count of the tables to repair
		delete_transient( 'adbc_plugin_tables_to_repair' );

		return $not_deleted;
	}

	/**
	 * Empty the list of the provided tables.
	 *
	 * @param array $tables_names The list of tables names to empty.
	 * @return array The list of tables that were not emptied.
	 */
	public static function empty_tables( $tables_names ) {

		global $wpdb;
		$not_processed = [];

		// Loop through the selected tables and optimize them
		foreach ( $tables_names as $table_name ) {

			$emptied = $wpdb->query( "TRUNCATE TABLE `{$table_name}`" );

			if ( $emptied ) {
				$wpdb->query( "ANALYZE TABLE `{$table_name}`" ); // If the query succeeded, analyze the table to update the table data
			} else {
				$not_processed[] = $table_name; // If the query failed, add the table name to the not processed list
			}
		}

		return $not_processed;
	}

	/**
	 * Repair the list of the provided tables.
	 *
	 * @param array $tables_names The list of tables names to repair.
	 * @return array The list of tables that were not repaired.
	 */
	public static function repair_tables( $tables_names ) {

		global $wpdb;
		$not_repaired = [];

		// Loop through the selected tables and repair them
		foreach ( $tables_names as $table_name ) {

			$result = $wpdb->get_results( "REPAIR TABLE `{$table_name}`" );

			// Check if the table is repaired successfully
			foreach ( $result as $row ) {
				if ( strtolower( $row->Msg_type ) == 'error' && stripos( $row->Msg_text, 'corrupt' ) !== false ) {
					$not_repaired[] = $table_name; // If the query failed, add the table name to the not repaired list
					break; // Break the loop if an error is found
				} else {
					$wpdb->query( "ANALYZE TABLE `{$table_name}`" ); // If the query succeeded, analyze the table to update the table data
				}
			}
		}

		// Delete the transient to force refresh the count of the tables to repair
		delete_transient( 'adbc_plugin_tables_to_repair' );

		return $not_repaired;
	}

	/**
	 * Add tables data to the rows array by reference: site id, prefix and table name without prefix.
	 * Used by the table endpoint class only.
	 * 
	 * @param array $tables_rows The tables rows array to add the tables data to.
	 * @return void
	 */
	public static function add_tables_data_to_rows( &$tables_rows ) {

		foreach ( $tables_rows as $table_name => $table_data ) {

			$table_info = self::get_table_prefix_and_blog_id( $table_name );
			$tables_rows[ $table_name ]->site_id = $table_info['site_id']; // Site id is "N/A" for tables with invalid prefix
			$tables_rows[ $table_name ]->prefix = $table_info['prefix'];
			$tables_rows[ $table_name ]->table_name_without_prefix = $table_info['table_name_without_prefix'];

		}
	}

	/**
	 * Remove tables with invalid prefix from the rows array by reference.
	 * Used by the table endpoint class only.
	 * 
	 * @param array $tables_rows The tables rows array to remove the tables data from.
	 * @return void
	 */
	public static function remove_tables_with_invalid_prefix_from_rows( &$tables_rows ) {

		foreach ( $tables_rows as $table_name => $table_data ) {
			if ( ! self::is_table_having_valid_prefix( $table_name ) ) {
				unset( $tables_rows[ $table_name ] );
			}
		}
	}

	/**
	 * Get all tables names, sizes, total rows and total columns for analytics.
	 *
	 * @return array All tables data, the table name as the key and the table data as the value.
	 */
	public static function get_all_tables_info_for_analytics() {

		global $wpdb;

		$query =
			"SELECT 
				table_name AS table_name,
				(data_length + index_length) AS size, 
				table_rows AS total_rows,
				(SELECT COUNT(*) FROM information_schema.columns 
				 WHERE table_schema = DATABASE() 
				 AND table_name = t.table_name) AS total_columns
			FROM information_schema.tables t
			WHERE table_schema = DATABASE();
			";
		$results = $wpdb->get_results( $query, ARRAY_A );

		// Format the results to be an associative array with the table name as the key
		$formatted_results = [];

		foreach ( $results as $row ) {
			$formatted_results[ $row['table_name'] ] = [ 
				's' => (float) $row['size'],
				'r' => (int) $row['total_rows'],
				'c' => (int) $row['total_columns']
			];
		}

		return $formatted_results;

	}

	/**
	 * Run ANALYZE SQL command on all tables to force MySQL to update the tables statistics.
	 * 
	 * @return void
	 */
	public static function analyze_all_tables() {

		global $wpdb;

		// Get all tables in the database
		$tables = $wpdb->get_col( "SHOW TABLES" );

		foreach ( $tables as $table ) {
			$wpdb->query( "ANALYZE TABLE `$table`" );
		}

	}

	/**
	 * Get all existing WordPress core tables in the database with the prefix.
	 *
	 * @return array The list of all WordPress core tables with the prefix.
	 */
	public static function get_all_wp_core_tables_with_prefix() {

		$wp_core_tables = ADBC_Hardcoded_Items::instance()->get_wordpress_items( 'tables' );
		$all_existing_prefixes = ADBC_Sites::instance()->get_all_prefixes();

		// Prepare the list of tables with prefix
		$wp_core_tables_with_prefix = [];
		foreach ( $wp_core_tables as $table_name => $_ ) {

			foreach ( $all_existing_prefixes as $prefix => $site_id )
				$wp_core_tables_with_prefix[] = $prefix . $table_name;

		}

		return $wp_core_tables_with_prefix;

	}

	/**
	 * Get the list of tables to optimize and their overhead.
	 * 
	 * @return array The list of tables to optimize as objects, with the table name and the overhead as attributes.
	 */
	public static function get_tables_to_optimize() {

		global $wpdb;
		$sql = "SELECT `TABLE_NAME` AS `table_name`,
					   `DATA_FREE` as `overhead`
				FROM   `information_schema`.`TABLES`
				WHERE  `TABLE_SCHEMA` = %s
				AND `DATA_FREE` > 0
				AND `ENGINE` != 'InnoDB'
				";
		$query = $wpdb->prepare( $sql, DB_NAME );

		$results = $wpdb->get_results( $query, OBJECT_K );

		return $results;

	}

	/**
	 * Check if the actionscheduler table exists.
	 * 
	 * @param string $table_type The type of the Actions Scheduler table to check for ('actions', 'logs'...)
	 * @return bool True if the table exists in any site, false otherwise.
	 * */
	public static function is_actionscheduler_table_exists( $table_type = 'actions' ) {

		global $wpdb;

		$exists = false;

		foreach ( ADBC_Sites::instance()->get_sites_list() as $site ) {

			ADBC_Sites::instance()->switch_to_blog_id( $site['id'] );

			$table_name = $wpdb->prefix . 'actionscheduler_' . $table_type;
			$exists = (bool) $wpdb->get_var(
				$wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name )
			);

			if ( $exists ) {
				ADBC_Sites::instance()->restore_blog();
				break;
			}

			ADBC_Sites::instance()->restore_blog();

		}

		return $exists;

	}

	/**
	 * Check if a table exists in the current database.
	 * 
	 * @param string $table_name The name of the table to check for.
	 * @return bool True if the table exists, false otherwise.
	 */
	public static function is_table_exists( $table_name ) {
		global $wpdb;
		$exists = (bool) $wpdb->get_var(
			$wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name )
		);
		return $exists;
	}

}