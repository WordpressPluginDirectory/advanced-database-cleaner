<?php

// Exit if accessed directly or not on the main site.
if ( ! defined( 'ABSPATH' ) || ! is_main_site() ) {
	exit;
}

/**
 * ADBC admin init class.
 *
 * This class adds the menu and submenu pages, and enqueuing the scripts and styles.
 */
class ADBC_Admin_Init extends ADBC_Singleton {

	/**
	 * Holds the menu hook for the main left sidebar admin menu item.
	 */
	private $left_menu;

	/**
	 * Holds the menu hook for the submenu item under Tools.
	 */
	private $tools_submenu;

	/**
	 * Holds the menu hook for the network admin menu item.
	 */
	private $network_menu;

	/**
	 * Holds the svg icon for the menu item.
	 */
	private $icon_svg = "";

	/**
	 * Constructor.
	 */
	protected function __construct() {

		parent::__construct();

		$this->icon_svg = 'data:image/svg+xml;base64,' . base64_encode( '<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="#a0a5aa" d="M896 768q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0 768q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-384q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-1152q208 0 385 34.5t280 93.5 103 128v128q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-128q0-69 103-128t280-93.5 385-34.5z"/></svg>' );
	}

	/**
	 * Add the menu and submenu pages.
	 * 
	 * @return void
	 */
	public function add_admin_menu() {

		$left_menu = ADBC_Settings::instance()->get_setting( 'left_menu' );
		$tools_menu = ADBC_Settings::instance()->get_setting( 'tools_menu' );

		if ( $left_menu === '1' ) {
			$this->left_menu = add_menu_page( 'Advanced DB Cleaner', 'WP DB Cleaner', 'manage_options', 'advanced_db_cleaner', [ $this, 'main_page_callback' ], $this->icon_svg, '80.01123' );
		}

		if ( $tools_menu === '1' ) {
			$this->tools_submenu = add_submenu_page( 'tools.php', 'Advanced DB Cleaner', 'WP DB Cleaner', 'manage_options', 'advanced_db_cleaner', [ $this, 'main_page_callback' ], '80.01123' );
		}
	}

	/**
	 * Add the network admin menu page.
	 * 
	 * @return void
	 */
	public function add_network_admin_menu() {

		$network_menu = ADBC_Settings::instance()->get_setting( 'network_menu' );

		if ( $network_menu === '1' ) {
			$this->network_menu = add_menu_page( 'Advanced DB Cleaner', 'WP DB Cleaner', 'manage_network_options', 'advanced_db_cleaner_network', [ $this, 'main_page_callback' ], $this->icon_svg, '80.01123' );
		}

	}

	/**
	 * Callback for the main page.
	 * 
	 * @return void
	 */
	public function main_page_callback() {

		echo "<div id='adbc-plugin-root'></div>";
	}

	/**
	 * Enqueue the scripts and styles.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {

		// Enqueue global scripts for global notifications such as the rating notice.
		$this->enqueue_global_scripts();

		// Load plugins scripts only in the plugin page.
		if ( $hook != $this->left_menu && $hook != $this->tools_submenu && $hook != $this->network_menu )
			return;

		// Enqueue the scripts and styles.
		wp_enqueue_style( 'adbc-app-style', ADBC_PLUGIN_ABSOLUTE_URL . '/assets/css/app.css', [], ADBC_PLUGIN_VERSION );
		wp_enqueue_script( 'adbc-vendors-script', ADBC_PLUGIN_ABSOLUTE_URL . '/assets/js/vendors.js', [], ADBC_PLUGIN_VERSION, true );
		wp_enqueue_script( 'adbc-app-script', ADBC_PLUGIN_ABSOLUTE_URL . '/assets/js/app.js', [ 'adbc-vendors-script', 'wp-i18n' ], ADBC_PLUGIN_VERSION, true );

		// Load the translations for the app script.
		wp_set_script_translations( 'adbc-app-script', 'advanced-database-cleaner', ADBC_PLUGIN_DIR_PATH . 'languages' );

		// Localize right after enqueuing.
		wp_localize_script(
			'adbc-app-script',
			'adbc_app_settings',
			array(
				'rest_url' => esc_url_raw( rest_url( ADBC_REST_API_NAMESPACE ) ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'version' => ADBC_PLUGIN_VERSION,
				'version_type' => ADBC_VERSION_TYPE,
				'settings' => ADBC_Settings::instance()->get_settings( false ), // Send none sensitive settings.
				'license_data' => ADBC_VERSION_TYPE === "PREMIUM" ? ADBC_License_Manager::get_license_data() : [], // Send masked license key.
				'warnings' => ADBC_Notifications::instance()->get_warnings(),
				'notifications' => ADBC_Notifications::instance()->get_local_notifications(),
				'is_multisite' => is_multisite() ? '1' : '0',
				'sites_list' => ADBC_Sites::instance()->get_sites_list(),
				'actionscheduler_actions_exists' => ADBC_Tables::is_actionscheduler_table_exists( 'actions' ) ? '1' : '0',
				'actionscheduler_logs_exists' => ADBC_Tables::is_actionscheduler_table_exists( 'logs' ) ? '1' : '0',
			)
		);

	}

	/**
	 * Enqueue global scripts for ADBC notifications.
	 * This runs on all admin pages, not just the plugin pages.
	 *
	 * @return void
	 */
	private function enqueue_global_scripts() {

		// Only load on admin pages and for users with manage_options capability.
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) )
			return;

		// Check if we have any global notifications to show
		$global_notifications = ADBC_Notifications::instance()->get_global_notifications();

		if ( empty( $global_notifications ) )
			return;

		// Enqueue the rating js code and style
		wp_enqueue_style( 'adbc-global-style', ADBC_PLUGIN_ABSOLUTE_URL . '/assets/css/rating-msg-style.css', [], ADBC_PLUGIN_VERSION );
		wp_enqueue_script( 'adbc-global-script', ADBC_PLUGIN_ABSOLUTE_URL . '/assets/js/rating-msg-js.js', [], ADBC_PLUGIN_VERSION, true );

		// Localize script with necessary data
		wp_localize_script(
			'adbc-global-script',
			'adbc_global_settings',
			array(
				'rest_url' => esc_url_raw( rest_url( ADBC_REST_API_NAMESPACE ) ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	/**
	 * Prioritize plugin's translation files over global ones.
	 * 
	 * This ensures that translations in the plugin's languages folder
	 * take precedence over those in wp-content/languages/plugins/
	 * 
	 * @param string $mofile Path to the MO file.
	 * @param string $domain Text domain.
	 * @return string Modified path to the MO file.
	 */
	public static function prioritize_plugin_translations( $mofile, $domain ) {

		// Only apply to our plugin's text domain
		if ( 'advanced-database-cleaner' !== $domain ) {
			return $mofile;
		}

		// Get the current locale
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );

		// Build the path to our plugin's translation file
		$plugin_mofile = ADBC_PLUGIN_DIR_PATH . 'languages/' . $domain . '-' . $locale . '.mo';

		// If our plugin's translation file exists, use it instead
		if ( file_exists( $plugin_mofile ) ) {
			return $plugin_mofile;
		}

		// Fallback to the original file
		return $mofile;

	}

	/**
	 * Change the script translation file name to load the correct file.
	 * 
	 * This is necessary because WordPress generates the MD5 hash based on the script file name,
	 * and we want to ensure that our translations are loaded from the correct file.
	 * The MD5 of the translation file built by WP will use assets/js/translations.js instead of assets/js/app.js
	 * 
	 * @param string $file The path to the translation file.
	 * @param string $handle The script handle.
	 * @param string $domain The text domain.
	 * @return string The modified path to the translation file.
	 */
	public static function change_script_translation_file_name( $file, $handle, $domain ) {

		if ( $domain !== 'advanced-database-cleaner' ) {
			return $file;
		}

		$file = str_replace( md5( 'assets/js/app.js' ), md5( 'assets/js/translations.js' ), $file );

		return $file;

	}

	/**
	 * What happens upon plugin activation.
	 * 
	 * @return void
	 */
	public static function activate() {
		// Nothing to do here for now
	}

	/**
	 * What happens upon plugin deactivation.
	 * 
	 * @return void
	 */
	public static function deactivate() {

		// unschedule analytics cron
		wp_clear_scheduled_hook( 'adbc_cron_analytics' );

		// Deactivate all automation tasks
		ADBC_Automation::instance()->deactivate_all_tasks();

	}

	/**
	 * Generate ADBC uploads folder if missing.
	 *
	 * @return void
	 */
	public static function create_adbc_uploads_folder_with_its_content() {

		// Check if the WP filesystem is initialized. If it fails, no need to continue.
		if ( ! ADBC_Files::instance()->is_wp_fs_initialized() )
			return;

		// Try to create the ADBC uploads folder. If it fails, no need to continue.
		if ( ! self::create_adbc_uploads_folder() )
			return;

		// Create files and folders inside the ADBC uploads folder.
		ADBC_Files::instance()->create_file( ADBC_Logging::DEBUG_LOG_FILE_PATH );

		// Create the scan folder.
		if ( ADBC_VERSION_TYPE === "PREMIUM" ) {
			ADBC_Files::instance()->create_folder( ADBC_Scan_Paths::SCAN_FOLDER_PATH, true );
			ADBC_Files::instance()->create_folder( ADBC_Analytics::ADBC_ANALYTICS_DIR, true );
			ADBC_Files::instance()->create_folder( ADBC_Analytics::ADBC_ANALYTICS_DATABASE_DIR, true );
			ADBC_Files::instance()->create_folder( ADBC_Analytics::ADBC_ANALYTICS_TABLES_DIR, true );
			ADBC_Files::instance()->create_folder( ADBC_Automation::AUTOMATION_EVENT_DIR, true );
			ADBC_Files::instance()->create_file( ADBC_Addons_Activity::ADDONS_ACTIVITY_LOG_FILE_PATH );
			ADBC_Files::instance()->create_file( ADBC_Addons_Activity::ADDONS_ACTIVITY_DICTIONARY );
		}

	}

	/**
	 * Setup the ADBC uploads folder (with its content).
	 * 
	 * @return boolean True if successful, false otherwise.
	 */
	private static function create_adbc_uploads_folder() {

		// If the ADBC uploads folder doesn't exist, delete all folders starting with the ADBC uploads folder prefix before creating a new one.
		if ( ! ADBC_Files::instance()->is_dir( ADBC_UPLOADS_DIR_PATH ) ) {

			$files = glob( ADBC_WP_UPLOADS_DIR_PATH . '/' . ADBC_UPLOADS_DIR_PREFIX . '*' );
			$adbc_folder_length = strlen( ADBC_UPLOADS_DIR_PREFIX ) + ADBC_SECURITY_CODE_LENGTH;

			foreach ( $files as $file ) {
				$folder_name = basename( $file );
				// Delete if the folder name = $adbc_folder_length and composed only of alphanumeric, underscores and F.
				if ( strlen( $folder_name ) === $adbc_folder_length && preg_match( '/^[a-z0-9_F]+$/', $folder_name ) )
					ADBC_Files::instance()->delete_folder( $file );
			}
		}

		// Create the new ADBC uploads folder if it doesn't exist.
		ADBC_Files::instance()->create_folder( ADBC_UPLOADS_DIR_PATH, true );

		// At this point the ADBC uploads folder should exist. Verify if it's readable and writable.
		if ( ADBC_Files::instance()->is_readable_and_writable( ADBC_UPLOADS_DIR_PATH ) ) {
			ADBC_Notifications::instance()->delete_notification( "uploads_folder" ); // Clear the warning form the DB if it exists.
		} else {
			ADBC_Notifications::instance()->add_notification( 'uploads_folder' );
			return false;
		}

		return true;
	}

	/**
	 * Ensure the integrity of automation tasks.
	 * 
	 * This method checks all automation tasks, validates their structure, and ensures they are scheduled or unscheduled as needed.
	 * It also cleans up any orphaned cron hooks that no longer have a corresponding task with their events files.
	 */
	public static function ensure_automation_integrity() {

		$tasks = ADBC_Automation::instance()->tasks();
		$cron_ids = ADBC_Automation::get_scheduled_automation_crons_ids();

		foreach ( $tasks as $id => $task ) {

			$valid = ADBC_Automation_Validator::validate_task_structure( $task );

			// task invalid → remove unschedule & cleanup
			if ( $valid === false ) {
				ADBC_Automation::instance()->delete( $id );
				continue;
			}

			$is_active = (bool) $task['active'];
			$is_scheduled = in_array( $id, $cron_ids, true );

			// valid & inactive → ensure NOT scheduled
			if ( ! $is_active && $is_scheduled ) {
				ADBC_Automation::instance()->unschedule( $id ); // unschedule the task
				// keep events file (user may re-enable later)
			}

			// valid & active → ensure scheduled
			if ( $is_active && ! $is_scheduled ) {
				ADBC_Automation::instance()->schedule( $task ); // schedule the task
			}

		}

		// orphan cron hooks (hook exists but task record gone)
		foreach ( $cron_ids as $id ) {
			if ( ! isset( $tasks[ $id ] ) ) {
				// The task is not found in the tasks array, so we can unschedule it and delete its events file.
				ADBC_Automation::instance()->unschedule( $id );
				ADBC_Automation::delete_events_file( $id );
			}
		}

	}

	/**
	 * Add custom schedules for ADBC cron jobs.
	 * 
	 * @param array $schedules The existing schedules.
	 * 
	 * @return array The modified schedules with ADBC custom frequencies added.
	 */
	public static function add_adbc_schedules_frequencies( $schedules ) {

		// Add adbc_hourly schedule (1 hour)
		$schedules['adbc_hourly'] = array(
			'interval' => HOUR_IN_SECONDS,
			'display' => __( 'Once hourly', 'advanced-database-cleaner' )
		);

		// Add adbc_twicedaily schedule (12 hours)
		$schedules['adbc_twicedaily'] = array(
			'interval' => 12 * HOUR_IN_SECONDS,
			'display' => __( 'Twice daily', 'advanced-database-cleaner' )
		);

		// Add adbc_daily schedule (1 day)
		$schedules['adbc_daily'] = array(
			'interval' => DAY_IN_SECONDS,
			'display' => __( 'Once daily', 'advanced-database-cleaner' )
		);

		// Add adbc_weekly schedule (1 week)
		$schedules['adbc_weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display' => __( 'Once weekly', 'advanced-database-cleaner' )
		);

		// Add adbc_monthly schedule (approx. 1 month)
		$schedules['adbc_monthly'] = array(
			'interval' => 30 * DAY_IN_SECONDS, // 30 days as a rough monthly interval
			'display' => __( 'Once monthly', 'advanced-database-cleaner' )
		);

		return $schedules;

	}

	/**
	 * Initialize settings with defaults if not set.
	 *
	 * @return void
	 */
	public static function maybe_show_global_notifications() {

		// Only load on admin pages and for users with manage_options capability.
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) )
			return;

		$notifications = ADBC_Notifications::instance()->get_global_notifications();

		foreach ( $notifications as $key => $notification ) {

			// Special handling for rating_notice notification
			if ( $key === 'rating_notice' ) {

				$rating_url = 'https://wordpress.org/support/plugin/advanced-database-cleaner/reviews/?filter=5';

				$text = sprintf(
					/* translators: 1: Plugin name with link */
					__( 'You have been using %s for more than 1 week. Would you mind taking a few seconds to give it a 5-star rating on WordPress? Thank you in advance :)', 'advanced-database-cleaner' ),
					'<a href="admin.php?page=advanced_db_cleaner">Advanced DB Cleaner</a>'
				);

				if ( is_multisite() )
					$text = __( 'You have been using the "Advanced DB Cleaner" for more than 1 week. Would you mind taking a few seconds to give it a 5-star rating on WordPress? Thank you in advance :)', 'advanced-database-cleaner' );

				printf(
					'<div id="adbc-rating-notice" class="adbc-notice">
						<div class="adbc-content">
							<div class="adbc-header">
								<div class="adbc-star">
									<span>⭐</span>
								</div>
								<span class="adbc-title">%s</span>
							</div>
							
							<p class="adbc-text">%s</p>
							
							<div class="adbc-buttons">
								<div class="adbc-buttons-left">
									<a href="%s" target="_blank" class="adbc-btn adbc-btn-primary">
										<span>⭐</span>%s
									</a>
									<a class="adbc-btn adbc-btn-secondary adbc-rating-dismiss">
										%s
									</a>
									<a class="adbc-btn adbc-btn-secondary adbc-rating-dismiss">
										%s
									</a>
								</div>
								
								<a id="adbc-rating-btn-remind-me" class="adbc-btn adbc-btn-secondary">
									<span class="dashicons dashicons-clock"></span>%s
								</a>
							</div>
						</div>
						
						<div>
							<a title="%s" class="adbc-btn-dismiss adbc-rating-dismiss">
								<span class="dashicons dashicons-dismiss"></span>
							</a>
						</div>
					</div>',
					__( 'Awesome!', 'advanced-database-cleaner' ),
					$text,
					$rating_url,
					__( 'Ok, you deserved it', 'advanced-database-cleaner' ),
					__( 'I already did', 'advanced-database-cleaner' ),
					__( 'No, not good enough', 'advanced-database-cleaner' ),
					__( 'Remind me later', 'advanced-database-cleaner' ),
					__( 'Dismiss', 'advanced-database-cleaner' )
				);

			} else {
				// Standard notification rendering. For now, we are not rendering these notifications in the admin area.
				// $type = esc_attr( $notification['type'] );
				// $message = wp_kses_post( $notification['message'] );
				// $dismissible = ! empty( $notification['dismissible'] ) ? ' is-dismissible' : '';
				// printf(
				// 	'<div class="notice notice-%1$s%2$s adbc-notice" data-adbc-notice-key="%3$s"><p>%4$s</p></div>',
				// 	$type,
				// 	$dismissible,
				// 	esc_attr( $key ),
				// 	$message
				// );
			}
		}
	}

	/**
	 * Static proxy for add_admin_menu to be used in the hooks.
	 * 
	 * @return void
	 */
	public static function _add_admin_menu() {
		self::instance()->add_admin_menu();
	}

	/**
	 * Static proxy for add_network_admin_menu to be used in the hooks.
	 * 
	 * @return void
	 */
	public static function _add_network_admin_menu() {
		self::instance()->add_network_admin_menu();
	}

	/**
	 * Static proxy for enqueue_scripts to be used in the hooks.
	 * 
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public static function _enqueue_scripts( $hook ) {
		self::instance()->enqueue_scripts( $hook );
	}

	/**
	 * Load all cleanup type handlers.
	 * 
	 * This method includes all PHP files in the type-handlers directories,
	 * allowing each handler to self-register with the General Cleanup system.
	 * It supports both free and premium handlers based on the plugin version.
	 * 
	 * @return void
	 */
	public static function load_general_cleanup_handlers() {

		$base_dir = ADBC_PLUGIN_DIR_PATH . '/includes/classes/general-cleanup/type-handlers';
		$premium_dir = ADBC_PLUGIN_DIR_PATH . 'includes/premium/classes/general-cleanup/type-handlers';

		$dirs = [ $base_dir ];

		// Only add premium if this is the premium build
		if ( ADBC_VERSION_TYPE === "PREMIUM" ) {
			$dirs[] = $premium_dir;
		}

		foreach ( $dirs as $dir ) {

			if ( ! is_dir( $dir ) ) {
				continue;
			}

			foreach ( glob( $dir . '/*.php' ) as $file ) {
				include_once $file; // triggers the self-registration logic
			}

		}

	}

	/**
	 * Prevent conflict between free, pro and premium versions
	 * 
	 * @return bool True if deactivated a conflicting version, false otherwise
	 */
	public static function has_conflict() {

		if ( ! function_exists( 'deactivate_plugins' ) )
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$premium_active = is_plugin_active( 'advanced-database-cleaner-premium/advanced-db-cleaner.php' );
		$pro_active = is_plugin_active( 'advanced-database-cleaner-pro/advanced-db-cleaner.php' );
		$free_active = is_plugin_active( 'advanced-database-cleaner/advanced-db-cleaner.php' );

		if ( $premium_active && $free_active ) {

			$network_wide = function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'advanced-database-cleaner/advanced-db-cleaner.php' );
			deactivate_plugins( 'advanced-database-cleaner/advanced-db-cleaner.php', true, $network_wide );
			ADBC_Common_Utils::old_plugin_version_deactivation_cleaning();
			add_action( 'all_admin_notices', [ self::class, 'free_conflict_notice' ] );

			return true;

		}

		if ( $premium_active && $pro_active ) {

			$network_wide = function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'advanced-database-cleaner-pro/advanced-db-cleaner.php' );
			deactivate_plugins( 'advanced-database-cleaner-pro/advanced-db-cleaner.php', true, $network_wide );
			ADBC_Common_Utils::old_plugin_version_deactivation_cleaning();
			add_action( 'all_admin_notices', [ self::class, 'pro_conflict_notice' ] );

			return true;

		}

		return false;

	}

	/**
	 * Display a notice if the free version was deactivated due to the premium version activation.
	 * 
	 * @return void
	 */
	public static function free_conflict_notice() {
		echo '<div class="error"><p>';
		_e( 'The free version of Advanced DB Cleaner has been de-activated since the premium version is active.', 'advanced-database-cleaner' );
		echo "</p></div>";
	}

	/**
	 * Display a notice if the pro version was deactivated due to the premium version activation.
	 * 
	 * @return void
	 */
	public static function pro_conflict_notice() {
		echo '<div class="error"><p>';
		_e( 'The pro version of Advanced DB Cleaner has been de-activated since the newest premium version is active.', 'advanced-database-cleaner' );
		echo "</p></div>";
	}

}