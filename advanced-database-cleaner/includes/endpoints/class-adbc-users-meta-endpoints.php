<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC Users Meta Endpoints.
 * 
 * This class provides the endpoints (controllers) for the users meta routes.
 */
class ADBC_Users_Meta_Endpoints {

	/**
	 * Get the users meta list.
	 *
	 * @param WP_REST_Request $filters_request The request with the filters.
	 * @return WP_REST_Response The list of users meta.
	 */
	public static function get_users_meta_list( WP_REST_Request $filters_request ) {

		try {

			$filters = ADBC_Common_Validator::sanitize_filters( $filters_request );
			$rest_response = ADBC_Users_Meta::get_users_meta_list( $filters );
			return $rest_response;

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Edit scan results of users meta.
	 *
	 * @param WP_REST_Request $request_data The request with the users meta to edit.
	 * @return WP_REST_Response The response.
	 */
	public static function edit_scan_results_users_meta( WP_REST_Request $request_data ) {

		try {

			return ADBC_Scan_Utils::edit_scan_results( $request_data, 'edit_scan_results_users_meta', 'users_meta' );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

	/**
	 * Delete users meta.
	 * 
	 * @param WP_REST_Request $request_data The request with the users meta to delete.
	 * @return WP_REST_Response The response.
	 */
	public static function delete_users_meta( WP_REST_Request $request_data ) {

		try {

			// Verify if there is a scan in progress. If there is, return an error to prevent conflicts.
			if ( ADBC_VERSION_TYPE === 'PREMIUM' && ADBC_Scan_Utils::is_scan_exists( 'users_meta' ) )
				return ADBC_Rest::error( __( 'A scan is in progress. Please wait until it finishes before performing this action.', 'advanced-database-cleaner' ), ADBC_Rest::BAD_REQUEST );

			$validation_answer = ADBC_Common_Validator::validate_endpoint_action_data( "delete_users_meta", "users_meta", $request_data );

			// If $validation_answer is not an array, it means that the validation failed and we have an error message.
			if ( ! is_array( $validation_answer ) )
				return ADBC_Rest::error( $validation_answer, ADBC_Rest::BAD_REQUEST );

			$cleaned_users_meta = ADBC_Hardcoded_Items::instance()->exclude_hardcoded_items_from_selected_items( $validation_answer, 'users_meta', "wp" );

			$not_processed = ADBC_Users_Meta::delete_users_meta( $cleaned_users_meta );

			// Delete the users meta from the scan results
			$users_meta_names = array_column( $cleaned_users_meta, 'name' ); // Create an array containing only the users meta names.

			if ( ADBC_VERSION_TYPE === 'PREMIUM' )
				ADBC_Scan_Utils::update_scan_results_file_after_deletion( 'users_meta', $users_meta_names, $not_processed );

			return ADBC_Rest::success( "", count( $not_processed ) );

		} catch (Throwable $e) {

			return ADBC_Rest::error_for_uncaught_exception( __METHOD__, $e );

		}
	}

}