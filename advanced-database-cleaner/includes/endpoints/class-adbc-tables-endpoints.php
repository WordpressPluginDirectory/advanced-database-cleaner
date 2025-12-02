<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC Tables Endpoints.
 * 
 * This class provides the endpoints (controllers) for the tables routes.
 */
class ADBC_Tables_Endpoints {

	/**
	 * Get the tables list.
	 *
	 * @param WP_REST_Request $filters_request The request with the filters.
	 * @return WP_REST_Response The list of tables.
	 */
	public static function get_tables_list( WP_REST_Request $filters_request ) {

		try {

			$filters = ADBC_Common_Validator::sanitize_filters( $filters_request );
			$rest_response = ADBC_Tables::get_tables_list( $filters );
			return $rest_response;

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Get the names of all tables.
	 *
	 * @return WP_REST_Response The response with the tables names.
	 */
	public static function get_tables_names() {

		try {

			$show_tables_with_invalid_prefix = ADBC_Settings::instance()->get_setting( 'show_tables_with_invalid_prefix' ) === '1';
			$tables_names = ADBC_Tables::get_tables_names( PHP_INT_MAX, 0, true, $show_tables_with_invalid_prefix );

			return ADBC_Rest::success( "", $tables_names );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Edit scan results of tables.
	 *
	 * @param WP_REST_Request $request_data The request with the tables to edit.
	 * @return WP_REST_Response The response.
	 */
	public static function edit_scan_results_tables( WP_REST_Request $request_data ) {

		try {

			return ADBC_Scan_Utils::edit_scan_results( $request_data, 'edit_scan_results_tables', 'tables' );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Optimize tables.
	 *
	 * @param WP_REST_Request $request_data The request with the tables to optimize.
	 * @return WP_REST_Response The response.
	 */
	public static function optimize_tables( WP_REST_Request $request_data ) {

		try {

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "optimize_tables", "tables", $request_data, true );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			// Create an array containing only the table names.
			$tables_names = array_column( $validation_answer, 'name' );

			$not_processed = ADBC_Tables::optimize_tables( $tables_names ); // Optimize the tables

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Empty rows of tables.
	 *
	 * @param WP_REST_Request $request_data The request with the tables to empty.
	 * @return WP_REST_Response The response.
	 */
	public static function empty_rows_tables( WP_REST_Request $request_data ) {

		try {

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "empty_rows_tables", "tables", $request_data, true );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			// Exclude hardcoded items from selected items.
			$cleaned_tables = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'tables' );

			// Create an array containing only the table names.
			$tables_names = array_column( $cleaned_tables, 'name' );

			$not_processed = ADBC_Tables::empty_tables( $tables_names ); // Empty the tables

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Delete tables.
	 *
	 * @param WP_REST_Request $request_data The request with the tables to delete.
	 * @return WP_REST_Response The response.
	 */
	public static function delete_tables( WP_REST_Request $request_data ) {

		try {

			// Verify if there is a scan in progress. If there is, return an error to prevent conflicts.
			if ( ADBC_VERSION_TYPE === 'PREMIUM' && ADBC_Scan_Utils::is_scan_exists( 'tables' ) )
				return ADBC_Rest::error( __( 'A scan is in progress. Please wait until it finishes before performing this action.', 'advanced-database-cleaner' ), ADBC_Rest::BAD_REQUEST );

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "delete_tables", "tables", $request_data, true );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			// Exclude hardcoded items from selected items.
			$cleaned_tables = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'tables' );

			// Create an array containing only the table names.
			$tables_names = array_column( $cleaned_tables, 'name' );

			$not_processed = ADBC_Tables::delete_tables( $tables_names ); // Delete the tables

			// Delete the tables from the scan results
			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				ADBC_Scan_Utils::update_scan_results_file_after_deletion( 'tables', $tables_names, $not_processed );

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Repair tables.
	 *
	 * @param WP_REST_Request $request_data The request with the tables to repair.
	 * @return WP_REST_Response The response.
	 */
	public static function repair_tables( WP_REST_Request $request_data ) {

		try {

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "repair_tables", "tables", $request_data, true );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			// Create an array containing only the table names.
			$tables_names = array_column( $validation_answer, 'name' );

			$not_processed = ADBC_Tables::repair_tables( $tables_names ); // Repair the tables

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

}