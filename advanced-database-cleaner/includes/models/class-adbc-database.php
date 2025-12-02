<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC common database class.
 * 
 * This class provides common database functions.
 */
class ADBC_Database {

	/**
	 * Get database size using SQL query.
	 *
	 * @param bool $formatted Whether to format the size or not.
	 * @return string|int Formatted database size or raw database size.
	 */
	public static function get_database_size_sql( $formatted = true ) {

		global $wpdb;

		$sql_query = "SELECT SUM(data_length + index_length) 
						FROM information_schema.tables 
						WHERE table_schema = DATABASE()
					";

		// Get database size.
		$database_size = $wpdb->get_var( $sql_query );

		if ( $formatted === true )
			$database_size = ADBC_Common_Utils::format_bytes( $database_size );

		return $database_size;

	}

	/**
	 * Get number of tables.
	 *
	 * @return int Number of tables.
	 */
	public static function get_number_of_tables() {

		global $wpdb;

		// Get number of tables.
		$tables = $wpdb->get_results( "SHOW TABLES", ARRAY_N );

		return count( $tables );
	}

}