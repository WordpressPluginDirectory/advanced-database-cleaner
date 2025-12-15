<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC Cron Jobs Endpoints.
 * 
 * This class provides the endpoints (controllers) for the cron jobs routes.
 */
class ADBC_Cron_Jobs_Endpoints {

	/**
	 * Get the cron jobs list.
	 *
	 * @param WP_REST_Request $filters_request The request with the filters.
	 * @return WP_REST_Response The list of cron jobs.
	 */
	public static function get_cron_jobs_list( WP_REST_Request $filters_request ) {

		try {

			$filters = ADBC_Common_Validator::sanitize_filters( $filters_request );
			$rest_response = ADBC_Cron_Jobs::get_cron_jobs_list( $filters );
			return $rest_response;

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Edit scan results of cron jobs.
	 *
	 * @param WP_REST_Request $request_data The request with the cron jobs to edit.
	 * @return WP_REST_Response The response.
	 */
	public static function edit_scan_results_cron_jobs( WP_REST_Request $request_data ) {

		try {

			return ADBC_Scan_Utils::edit_scan_results( $request_data, 'edit_scan_results_cron_jobs', 'cron_jobs' );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Delete cron jobs.
	 * 
	 * @param WP_REST_Request $request_data The request with the cron jobs to delete.
	 * @return WP_REST_Response The response.
	 */
	public static function delete_cron_jobs( WP_REST_Request $request_data ) {

		try {

			// Verify if there is a scan in progress. If there is, return an error to prevent conflicts.
			if ( ADBC_VERSION_TYPE === 'PREMIUM' && ADBC_Scan_Utils::is_scan_exists( 'cron_jobs' ) )
				return ADBC_Rest::error( __( 'A scan is in progress. Please wait until it finishes before performing this action.', 'advanced-database-cleaner' ), ADBC_Rest::BAD_REQUEST );

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "delete_cron_jobs", "cron_jobs", $request_data );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			$cleaned_cron_jobs = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'cron_jobs', "wp" );

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				$cleaned_cron_jobs = ADBC_Scan_Utils::exclude_r_wp_items_from_selected_items( $cleaned_cron_jobs, 'cron_jobs' );

			if ( empty( $cleaned_cron_jobs ) )
				return ADBC_Rest::error( __( "Selected cron jobs cannot be deleted because they belong to WordPress.", 'advanced-database-cleaner' ), ADBC_Rest::BAD_REQUEST );

			$grouped = ADBC_Selected_Items_Validator::group_selected_items_by_site_id( $cleaned_cron_jobs );

			$not_processed = ADBC_Cron_Jobs::delete_cron_jobs( $grouped );

			// Delete the cron jobs from the scan results
			$cron_jobs_names = array_column( $cleaned_cron_jobs, 'name' ); // Create an array containing only the cron job names.

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				ADBC_Scan_Utils::update_scan_results_file_after_deletion( 'cron_jobs', $cron_jobs_names, $not_processed );

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Count the total number of cron jobs that are not scanned.
	 *
	 * @return WP_REST_Response The response.
	 */
	public static function count_total_not_scanned_cron_jobs() {
		try {
			return ADBC_Rest::success( "", ADBC_Cron_Jobs::count_total_not_scanned_cron_jobs() );
		} catch (Throwable $e) {
			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );
		}
	}

	/**
	 * Count the total number of cron jobs that have no registered action (no callbacks hooked to their hook name).
	 *
	 * @return WP_REST_Response The response.
	 */
	public static function count_total_cron_jobs_with_no_action() {
		try {
			return ADBC_Rest::success( "", ADBC_Cron_Jobs::count_total_cron_jobs_with_no_action() );
		} catch (Throwable $e) {
			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );
		}
	}

}