<?php
/**
 * Plugin Name:       Advanced Database Cleaner
 * Plugin URI:        https://sigmaplugin.com/downloads/wordpress-advanced-database-cleaner
 * Description:       The most advanced Database Cleaner for WordPress. Clean database by deleting orphaned items such as old "revisions", "old drafts", optimize database, and more.
 * Version:           4.0.3
 * Author:            SigmaPlugin
 * Author URI:        https://sigmaplugin.com
 * Contributors:      symptote
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       advanced-database-cleaner
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

// Return if not in main site
if ( ! is_main_site() )
	return;

if ( ! defined( "ADBC_MAIN_PLUGIN_FILE_PATH" ) )
	define( "ADBC_MAIN_PLUGIN_FILE_PATH", __FILE__ );

if ( ! defined( 'ADBC_PLUGIN_VERSION' ) )
	define( 'ADBC_PLUGIN_VERSION', '4.0.3' );

class ADBC_Advanced_DB_Cleaner {

	public function __construct() {

		// Load the classes files on demand
		spl_autoload_register( [ $this, 'loader' ] );

		// Prevent conflicts with other versions
		if ( ADBC_Admin_Init::has_conflict() )
			return;

		// Load constants early so activation hooks have access to them
		include_once 'constants.php';

		// Register activation and deactivation hooks
		register_activation_hook( __FILE__, [ 'ADBC_Admin_Init', 'activate' ] );
		register_deactivation_hook( __FILE__, [ 'ADBC_Admin_Init', 'deactivate' ] );

		if ( ADBC_VERSION_TYPE === "FREE" ) {
			add_action( 'init', [ 'ADBC_Migration', 'run_free_migration' ] );
		}

		// Menus, scripts and custom styles
		add_action( 'admin_menu', [ 'ADBC_Admin_Init', '_add_admin_menu' ] );
		if ( is_multisite() )
			add_action( 'network_admin_menu', [ 'ADBC_Admin_Init', '_add_network_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ 'ADBC_Admin_Init', '_enqueue_scripts' ] );

		// Register all routes
		add_action( 'rest_api_init', [ 'ADBC_Routes', 'register_all_routes' ] );

		// Init
		add_action( 'init', [ 'ADBC_Admin_Init', 'create_adbc_uploads_folder_with_its_content' ] );
		add_action( 'init', [ 'ADBC_Admin_Init', 'ensure_automation_integrity' ] );
		add_action( 'init', [ 'ADBC_Admin_Init', 'load_general_cleanup_handlers' ] );

		// Show global notifications
		add_action( 'all_admin_notices', [ 'ADBC_Admin_Init', 'maybe_show_global_notifications' ] );

		// Add crons hooks
		add_action( 'adbc_cron_automation', [ 'ADBC_Automation', '_run_task_by_id' ], 10, 1 );

		if ( ADBC_VERSION_TYPE === "PREMIUM" ) {

			// Register premium routes
			add_action( 'rest_api_init', [ 'ADBC_Premium_Routes', 'register_routes' ] );

			// Analytics cron hook scheduler
			add_action( 'init', [ 'ADBC_Analytics', 'check_and_schedule_cron' ] );

			// Analytics cron hook
			add_action( 'adbc_cron_analytics', [ 'ADBC_Analytics', '_run_analytics_cron' ] );

			// Hook into plugin and theme events.
			add_action( 'activated_plugin', [ 'ADBC_Addons_Activity', 'on_plugin_activated' ] );
			add_action( 'deactivated_plugin', [ 'ADBC_Addons_Activity', 'on_plugin_deactivated' ] );
			add_action( 'switch_theme', [ 'ADBC_Addons_Activity', 'on_theme_switched' ], 10, 3 );
			add_action( 'delete_plugin', [ 'ADBC_Addons_Activity', 'on_plugin_uninstalled' ] );
			add_action( 'delete_theme', [ 'ADBC_Addons_Activity', 'on_theme_uninstalled' ] );

			// Initialize the plugin license handler and updater.
			$sdk_handler = __DIR__ . '/vendor/easy-digital-downloads/edd-sl-sdk/edd-sl-sdk.php';
			if ( file_exists( $sdk_handler ) ) {
				require_once $sdk_handler;
			}
			// Initialize the EDD SL SDK license manager
			add_action( 'edd_sl_sdk_registry', [ 'ADBC_License_Manager', 'register_sdk' ] );
			// Remove SDK license manage link from plugin actions
			add_filter(
				'plugin_action_links_' . plugin_basename( ADBC_MAIN_PLUGIN_FILE_PATH ),
				[ 'ADBC_License_Manager', 'filter_remove_sdk_manage_link' ],
				200,
				3
			);

		}

		// filters
		add_filter( 'cron_schedules', [ 'ADBC_Admin_Init', 'add_adbc_schedules_frequencies' ] );
		add_filter( 'load_script_translation_file', [ 'ADBC_Admin_Init', 'change_script_translation_file_name' ], 10, 3 );
		if ( method_exists( 'ADBC_Admin_Init', '_capture_original_plugin_meta_links' ) && method_exists( 'ADBC_Admin_Init', '_restore_plugin_meta_links' ) ) {
			add_filter( 'plugin_row_meta', [ 'ADBC_Admin_Init', '_capture_original_plugin_meta_links' ], 1, 2 );
			add_filter( 'plugin_row_meta', [ 'ADBC_Admin_Init', '_restore_plugin_meta_links' ], 999, 2 );
		}
		/* TO-CHECK: Always prioritize plugin's shipped translations over global ones for the pro version
		 * For the free version we keep it until new version (>=4.0.0) translations are mature in the global repo */
		add_filter( 'load_textdomain_mofile', [ 'ADBC_Admin_Init', 'prioritize_plugin_translations' ], 10, 2 );
		// Declare compliance with consent level API
		$plugin = plugin_basename( __FILE__ );
		add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );

	}

	/**
	 * Load the class file.
	 * 
	 * @param string $class_name The name of the class to load.
	 * @return void
	 */
	public function loader( $class_name ) {

		// skip loading the classes that doesn't belong to our plugin
		if ( strpos( $class_name, 'ADBC_' ) !== 0 ) {
			return;
		}

		$class_file_name = 'class-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( __DIR__ . '/includes', FilesystemIterator::SKIP_DOTS )
		);

		foreach ( $iterator as $file ) {
			if ( $file->getFilename() === $class_file_name ) {
				include_once $file->getRealPath();
				return;
			}
		}

	}

}

// Get instance
new ADBC_Advanced_DB_Cleaner();
