<?php

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

class ADBC_Uninstall {

	// List of all ADBC options to delete on uninstall
	private static $adbc_options = [ 
		'adbc_plugin_settings' => '',
		'adbc_plugin_scan_info_options' => '',
		'adbc_plugin_scan_info_tables' => '',
		'adbc_plugin_scan_info_cron_jobs' => '',
		'adbc_plugin_scan_info_users_meta' => '',
		'adbc_plugin_scan_info_posts_meta' => '',
		'adbc_plugin_scan_info_transients' => '',
		'adbc_plugin_should_stop_scan_options' => '',
		'adbc_plugin_should_stop_scan_tables' => '',
		'adbc_plugin_should_stop_scan_cron_jobs' => '',
		'adbc_plugin_should_stop_scan_users_meta' => '',
		'adbc_plugin_should_stop_scan_posts_meta' => '',
		'adbc_plugin_should_stop_scan_transients' => '',
		'adbc_plugin_automation' => '',
		'adbc_plugin_license_key' => '',
		'adbc_plugin_license_key_license' => '',
	];

	// List of all ADBC transients to delete on uninstall
	private static $adbc_transients = [ 
		'_transient_adbc_plugin_tables_to_repair' => 'adbc_plugin_tables_to_repair',
	];

	// List of all ADBC cron jobs to unschedule on uninstall
	private static $adbc_cron_jobs = [ 
		'adbc_cron_analytics',
		'adbc_cron_automation',
		'edd_sl_sdk_weekly_license_check_advanced-database-cleaner-premium',
	];

	/**
	 * Run the uninstall process.
	 * 
	 * This method checks if the uninstall is being run from the premium or free version,
	 * and deletes plugin data accordingly to avoid data loss when switching between versions.
	 * 
	 * If uninstalling the premium version and a new free version (>= 4.0.0) exists, it keeps
	 * useful data such as settings and automation tasks. Otherwise, it deletes all plugin data.
	 * 
	 * If uninstalling the free version and the premium version does not exist, it deletes all plugin data.
	 * 
	 * @return void
	 */
	public static function run() {

		// check if we are in the premium
		if ( file_exists( __DIR__ . '/includes/premium' ) ) {

			// keep useful data if the the new free version is uninstalled (>= 4.0.0), otherwise delete everything
			if ( self::is_new_free_version_exists() ) {

				// Delete all options except the adbc_plugin_settings and the automation options
				foreach ( self::$adbc_options as $option_name => $_ ) {
					if ( $option_name !== 'adbc_plugin_settings' && $option_name !== 'adbc_plugin_automation' ) {
						delete_option( $option_name );
					}
				}

				// delete the scan and the analytics folders with their contents
				$adbc_upload_folder = self::get_adbc_upload_folder_path();
				$scan_folder = $adbc_upload_folder . '/scan';
				$analytics_folder = $adbc_upload_folder . '/analytics';
				$automation_folder = $adbc_upload_folder . '/automation_events';
				$addons_activity_file = $adbc_upload_folder . '/addons_activity.log';
				$addons_activity_dictionary_file = $adbc_upload_folder . '/addons_activity_dictionary.log';

				self::delete_folder( $scan_folder );
				self::delete_folder( $analytics_folder );
				self::delete_folder( $automation_folder );

				if ( file_exists( $addons_activity_file ) )
					unlink( $addons_activity_file );

				if ( file_exists( $addons_activity_dictionary_file ) )
					unlink( $addons_activity_dictionary_file );

				// Unschedule crons
				wp_unschedule_hook( 'adbc_cron_analytics' );

				// unschedule automation crons only if the new version is deactivated
				if ( ! is_plugin_active( 'advanced-database-cleaner/advanced-db-cleaner.php' ) ) {
					wp_unschedule_hook( 'adbc_cron_automation' );
				}

			} else {

				// delete all plugin data
				self::delete_all_plugin_data();

			}

		} else { // we are in the free

			// if the premium version doesn't exist, delete everything
			if ( ! self::is_premium_version_exists() ) {
				self::delete_all_plugin_data();
			}

		}

	}

	/**
	 * Recursively delete a folder and its contents.
	 * 
	 * @param string $folder The folder path to delete.
	 * 
	 * @return bool True on success, false on failure.
	 */
	private static function delete_folder( $folder ) {

		if ( ! is_dir( $folder ) ) {
			return false;
		}

		$files = array_diff( scandir( $folder ), [ '.', '..' ] ); // get all files/folders

		foreach ( $files as $file ) {
			$path = $folder . DIRECTORY_SEPARATOR . $file;

			if ( is_dir( $path ) ) {
				self::delete_folder( $path ); // recursion for subfolders
			} else {
				unlink( $path ); // delete file
			}
		}

		return rmdir( $folder ); // delete the folder itself

	}

	/**
	 * Get the ADBC upload folder path.
	 * 
	 * @return string The ADBC upload folder path.
	 */
	private static function get_adbc_upload_folder_path() {

		// Get upload folder security code to delete the folder
		$settings = get_option( 'adbc_plugin_settings', [] );
		$security_code = isset( $settings['security_code'] ) ? $settings['security_code'] : '';
		$upload_folder = wp_upload_dir()['basedir'] . '/adbc_uploads_F_' . $security_code;

		return $upload_folder;

	}

	/**
	 * Delete the ADBC upload folder.
	 * 
	 * @return void
	 */
	private static function delete_adbc_upload_folder() {
		$upload_folder = self::get_adbc_upload_folder_path();
		self::delete_folder( $upload_folder );
	}

	/**
	 * Delete all ADBC options.
	 * 
	 * @return void
	 */
	private static function delete_all_adbc_options() {
		foreach ( self::$adbc_options as $option_name => $_ ) {
			delete_option( $option_name );
		}
	}

	/**
	 * Delete all ADBC transients.
	 * 
	 * @return void
	 */
	private static function delete_all_adbc_transients() {
		foreach ( self::$adbc_transients as $full_transient_name => $transient_name ) {
			delete_transient( $transient_name );
		}
	}

	/**
	 * Unschedule all ADBC cron jobs.
	 * 
	 * @return void
	 */
	private static function unschedule_all_cron_jobs() {
		foreach ( self::$adbc_cron_jobs as $cron_job ) {
			wp_unschedule_hook( $cron_job );
		}
	}

	/**
	 * Delete all plugin data: options, transients, upload folder.
	 * 
	 * @return void
	 */
	private static function delete_all_plugin_data() {
		self::delete_adbc_upload_folder();
		self::delete_all_adbc_transients();
		self::delete_all_adbc_options();
		self::unschedule_all_cron_jobs();
	}

	/**
	 * Check if a new free version (>= 4.0.0) exists.
	 * 
	 * @return bool True if a new free version exists, false otherwise.
	 */
	private static function is_new_free_version_exists() {

		// Ensure plugin functions are loaded
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		$free_slug = 'advanced-database-cleaner/advanced-db-cleaner.php';

		if ( isset( $plugins[ $free_slug ] ) ) {
			$free_version = $plugins[ $free_slug ]['Version'];

			// Compare version
			if ( version_compare( $free_version, '4.0.0', '>=' ) ) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Check if the premium version exists.
	 * 
	 * @return bool True if the premium version exists, false otherwise.
	 */
	private static function is_premium_version_exists() {

		// Ensure plugin functions are loaded
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		$premium_slug = 'advanced-database-cleaner-premium/advanced-db-cleaner.php';

		if ( isset( $plugins[ $premium_slug ] ) ) {
			return true;
		}

		return false;

	}

}

// Run the uninstall process
ADBC_Uninstall::run();