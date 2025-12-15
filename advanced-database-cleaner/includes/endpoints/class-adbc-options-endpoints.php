<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC Options Endpoints.
 * 
 * This class provides the endpoints (controllers) for the options routes.
 */
class ADBC_Options_Endpoints {

	/**
	 * Get the options list.
	 *
	 * @param WP_REST_Request $filters_request The request with the filters.
	 * @return WP_REST_Response The list of options.
	 */
	public static function get_options_list( WP_REST_Request $filters_request ) {

		try {

			$filters = ADBC_Common_Validator::sanitize_filters( $filters_request );
			$rest_response = ADBC_Options::get_options_list( $filters );
			return $rest_response;

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Edit scan results of options.
	 *
	 * @param WP_REST_Request $request_data The request with the options to edit.
	 * @return WP_REST_Response The response.
	 */
	public static function edit_scan_results_options( WP_REST_Request $request_data ) {

		try {

			return ADBC_Scan_Utils::edit_scan_results( $request_data, 'edit_scan_results_options', 'options' );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Set autoload to "yes" for options.
	 * 
	 * @param WP_REST_Request $request_data The request with the options to set autoload to "yes".
	 * @return WP_REST_Response The response.
	 */
	public static function set_autoload_to_yes_options( WP_REST_Request $request_data ) {

		try {

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "set_autoload_to_yes_options", "options", $request_data );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			$cleaned_options = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'options', "wp" );
			$grouped = ADBC_Selected_Items_Validator::group_selected_items_by_site_id( $cleaned_options );
			$not_processed = ADBC_Options::set_autoload_to_yes( $grouped );

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Set autoload to "no" for options.
	 * 
	 * @param WP_REST_Request $request_data The request with the options to set autoload to "no".
	 * @return WP_REST_Response The response.
	 */
	public static function set_autoload_to_no_options( WP_REST_Request $request_data ) {

		try {

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "set_autoload_to_no_options", "options", $request_data );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			$cleaned_options = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'options', "wp" );
			$grouped = ADBC_Selected_Items_Validator::group_selected_items_by_site_id( $cleaned_options );
			$not_processed = ADBC_Options::set_autoload_to_no( $grouped );

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Delete options.
	 * 
	 * @param WP_REST_Request $request_data The request with the options to delete.
	 * @return WP_REST_Response The response.
	 */
	public static function delete_options( WP_REST_Request $request_data ) {

		try {

			// Verify if there is a scan in progress. If there is, return an error to prevent conflicts.
			if ( ADBC_VERSION_TYPE === 'PREMIUM' && ADBC_Scan_Utils::is_scan_exists( 'options' ) )
				return ADBC_Rest::error( __( 'A scan is in progress. Please wait until it finishes before performing this action.', 'advanced-database-cleaner' ), ADBC_Rest::BAD_REQUEST );

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "delete_options", "options", $request_data );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			$cleaned_options = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'options', "wp" );

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				$cleaned_options = ADBC_Scan_Utils::exclude_r_wp_items_from_selected_items( $cleaned_options, 'options' );

			if ( empty( $cleaned_options ) )
				return ADBC_Rest::error( __( "Selected options cannot be deleted because they belong to WordPress.", 'advanced-database-cleaner' ), ADBC_Rest::BAD_REQUEST );

			$grouped = ADBC_Selected_Items_Validator::group_selected_items_by_site_id( $cleaned_options );

			$not_processed = ADBC_Options::delete_options( $grouped );

			// Delete the options from the scan results
			$options_names = array_column( $cleaned_options, 'name' ); // Create an array containing only the options names.

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				ADBC_Scan_Utils::update_scan_results_file_after_deletion( 'options', $options_names, $not_processed );

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Count the size of all autoloaded options in the wp_options table.
	 *
	 * @return WP_REST_Response The response.
	 */
	public static function count_big_options() {
		try {
			return ADBC_Rest::success( "", ADBC_Options::count_big_options() );
		} catch (Throwable $e) {
			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );
		}
	}

	/**
	 * Count the total number of options that are not scanned.
	 *
	 * @return WP_REST_Response The response.
	 */
	public static function count_total_not_scanned_options() {

		try {
			return ADBC_Rest::success( "", ADBC_Options::count_total_not_scanned_options() );
		} catch (Throwable $e) {
			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );
		}
	}

	/**
	 * Count the health of the autoloaded options.
	 *
	 * @return WP_REST_Response The response.
	 */
	public static function get_autoload_health() {
		try {
			return ADBC_Rest::success( "", ADBC_Options::count_autoload_size_using_sql() );
		} catch (Throwable $e) {
			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );
		}
	}

}




