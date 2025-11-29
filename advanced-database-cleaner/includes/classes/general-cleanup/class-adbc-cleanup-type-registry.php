<?php

// Exit if accessed directly or not on the main site.
if ( ! defined( 'ABSPATH' ) || ! is_main_site() ) {
	exit;
}

/**
 * Class ADBC_Cleanup_Type_Registry
 * 
 * This class manages the registration and retrieval of cleanup type handlers.
 */
final class ADBC_Cleanup_Type_Registry {

	/**
	 * Contains the registered handlers for each items type.
	 * 
	 * @var array<string,ADBC_Cleanup_Type_Handler>
	 */
	private static $handlers = [];

	private static $all_handlers_types = [ 
		"revisions",
		"auto_drafts",
		"trashed_posts",
		"spam_comments",
		"trashed_comments",
		"unapproved_comments",
		"pingbacks",
		"trackbacks",
		"duplicated_postmeta",
		"duplicated_commentmeta",
		"duplicated_usermeta",
		"duplicated_termmeta",
		"unused_postmeta",
		"unused_commentmeta",
		"unused_usermeta",
		"unused_termmeta",
		"oembed_caches",
		"expired_transients",
		"unused_relationships",
		"tables_to_optimize",
		"tables_to_repair",
		"actionscheduler_completed_actions",
		"actionscheduler_failed_actions",
		"actionscheduler_canceled_actions",
		"actionscheduler_completed_logs",
		"actionscheduler_failed_logs",
		"actionscheduler_canceled_logs",
		"actionscheduler_orphan_logs"
	];

	/**
	 * Registers a handler for a specific items type.
	 *
	 * @param string $items_type The type of items this handler will manage.
	 * @param ADBC_Cleanup_Type_Handler $handler The handler instance to register.
	 */
	public static function register( $items_type, ADBC_Cleanup_Type_Handler $handler ) {
		self::$handlers[ $items_type ] = $handler;
	}

	/**
	 * Retrieves the handler for a specific items type.
	 *
	 * @param string $items_type The type of items to get the handler for.
	 * 
	 * @return ADBC_Cleanup_Type_Handler|null The handler instance for the specified items type, or null if not found.
	 */
	public static function handler( $items_type ) {
		if ( ! isset( self::$handlers[ $items_type ] ) ) {
			return null;
		}
		return self::$handlers[ $items_type ];
	}

	/**
	 * Retrieves all registered handlers.
	 *
	 * @return array<string,ADBC_Cleanup_Type_Handler> An associative array of items types and their handlers.
	 */
	public static function all_handlers() {
		return self::$handlers;
	}

	/**
	 * Checks if a handler is registered for a specific items type.
	 *
	 * @param string $items_type The type of items to check.
	 * 
	 * @return bool True if a handler is registered for the given items type, false otherwise.
	 */
	public static function is_registered_items_type( $items_type ) {
		return isset( self::$handlers[ $items_type ] );
	}

	/**
	 * Validates if the provided items type is valid.
	 * 
	 * @param mixed $items_type
	 * 
	 * @return bool
	 */
	public static function is_valid_items_type( $items_type ) {
		return array_key_exists( $items_type, array_flip( self::$all_handlers_types ) );
	}

	/**
	 * Retrieves all registered items types.
	 *
	 * @return array<string> An array of all registered items types.
	 */
	public static function get_all_items_type() {
		return self::$all_handlers_types;
	}

}