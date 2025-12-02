<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class ADBC_Cleanup_Unused_Relationships_Handler
 * 
 * This class handles the cleanup of unused term relationships in WordPress.
 */
class ADBC_Cleanup_Unused_Relationships_Handler extends ADBC_Abstract_Cleanup_Handler {

	// Required methods from ADBC_Abstract_Cleanup_Handler
	protected function items_type() {
		return 'unused_relationships';
	}
	protected function table() {
		global $wpdb;
		return $wpdb->term_relationships;
	}
	protected function pk() {
		return 'object_id';
	}
	protected function base_where() {
		global $wpdb;
		return "( main.term_taxonomy_id = 1 AND main.object_id NOT IN ( SELECT ID FROM {$wpdb->posts} ) )";
	}
	protected function name_column() {
		return 'term_taxonomy_id';
	}
	protected function value_column() {
		return 'term_order';
	}
	protected function is_all_sites_sortable() {
		return true;
	}
	protected function sortable_columns() {
		return [ 
			'object_id',
			'term_taxonomy_id',
			'term_order',
			'size',
			'site_id'
		];
	}
	protected function delete_helper() {
		return static fn() => false; // Unused relationships are not deleted by the helper.
	}

	// Optional methods for this handler
	protected function date_column() {
		return null;
	}

	// Overridable methods from ADBC_Abstract_Cleanup_Handler
	protected function add_composite_id( &$rows ) {

		foreach ( $rows as &$row ) {
			$row['composite_id'] = [ 
				'items_type' => $this->items_type(),
				'site_id' => (int) $row['site_id'],
				'id' => (int) $row['object_id'],
				'term_taxonomy_id' => (int) $row['term_taxonomy_id'],
			];
		}

		return $rows;

	}

	// Public methods overridden from ADBC_Abstract_Cleanup_Handler
	public function delete( $items ) {

		global $wpdb;

		if ( empty( $items ) ) {
			return 0;
		}

		$by_site = [];
		foreach ( $items as $row ) {
			$by_site[ $row['site_id'] ][] = [ 
				'object_id' => (int) $row['id'],
				'taxonomy_id' => (int) $row['term_taxonomy_id'],
			];
		}

		$deleted = 0;

		foreach ( $by_site as $site_id => $pairs ) {

			ADBC_Sites::instance()->switch_to_blog_id( $site_id );

			foreach ( $pairs as $pair ) {

				$sql = "
					DELETE main
					FROM   {$this->table()} AS main
					WHERE  main.object_id = %d
					   	   AND main.term_taxonomy_id = %d
				";
				$query = $wpdb->prepare( $sql, $pair['object_id'], $pair['taxonomy_id'] );

				$deleted += $wpdb->query( $query );

			}

			ADBC_Sites::instance()->restore_blog();

		}

		return $deleted;

	}

	public function purge() {

		global $wpdb;

		$total = 0;

		foreach ( ADBC_Sites::instance()->get_sites_list() as $site ) {

			ADBC_Sites::instance()->switch_to_blog_id( $site['id'] );

			$sql = "
                DELETE main                                
                FROM   {$this->table()} AS main
                WHERE  {$this->base_where()}
            ";
			$total += $wpdb->query( $sql );

			ADBC_Sites::instance()->restore_blog();

		}

		return $total;

	}

}

// Register the handler with the cleanup type registry.
ADBC_Cleanup_Type_Registry::register( 'unused_relationships', new ADBC_Cleanup_Unused_Relationships_Handler );