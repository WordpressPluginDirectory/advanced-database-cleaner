<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ADBC Hardcoded Items.
 * 
 * This class provides methods for the hardcoded items scan process.
 */
class ADBC_Hardcoded_Items extends ADBC_Singleton {

	/**
	 * WordPress options.
	 * 
	 * @var array
	 */
	private $wp_options = [ 
		'siteurl' => '',
		'home' => '',
		'blogname' => '',
		'blogdescription' => '',
		'users_can_register' => '',
		'admin_email' => '',
		'start_of_week' => '',
		'use_balanceTags' => '',
		'use_smilies' => '',
		'require_name_email' => '',
		'comments_notify' => '',
		'posts_per_rss' => '',
		'rss_use_excerpt' => '',
		'mailserver_url' => '',
		'mailserver_login' => '',
		'mailserver_pass' => '',
		'mailserver_port' => '',
		'default_category' => '',
		'default_comment_status' => '',
		'default_ping_status' => '',
		'default_pingback_flag' => '',
		'posts_per_page' => '',
		'date_format' => '',
		'time_format' => '',
		'links_updated_date_format' => '',
		'comment_moderation' => '',
		'moderation_notify' => '',
		'permalink_structure' => '',
		'gzipcompression' => '',
		'hack_file' => '',
		'blog_charset' => '',
		'moderation_keys' => '',
		'active_plugins' => '',
		'category_base' => '',
		'ping_sites' => '',
		'advanced_edit' => '',
		'comment_max_links' => '',
		'gmt_offset' => '',
		// 1.5
		'default_email_category' => '',
		'recently_edited' => '',
		'template' => '',
		'stylesheet' => '',
		'comment_whitelist' => '',
		'blacklist_keys' => '',
		'comment_registration' => '',
		'html_type' => '',
		// 1.5.1
		'use_trackback' => '',
		// 2.0
		'default_role' => '',
		'db_version' => '',
		// 2.0.1
		'uploads_use_yearmonth_folders' => '',
		'upload_path' => '',
		// 2.1
		'blog_public' => '',
		'default_link_category' => '',
		'show_on_front' => '',
		// 2.2
		'tag_base' => '',
		// 2.5
		'show_avatars' => '',
		'avatar_rating' => '',
		'upload_url_path' => '',
		'thumbnail_size_w' => '',
		'thumbnail_size_h' => '',
		'thumbnail_crop' => '',
		'medium_size_w' => '',
		'medium_size_h' => '',
		// 2.6
		'avatar_default' => '',
		// 2.7
		'large_size_w' => '',
		'large_size_h' => '',
		'image_default_link_type' => '',
		'image_default_size' => '',
		'image_default_align' => '',
		'close_comments_for_old_posts' => '',
		'close_comments_days_old' => '',
		'thread_comments' => '',
		'thread_comments_depth' => '',
		'page_comments' => '',
		'comments_per_page' => '',
		'default_comments_page' => '',
		'comment_order' => '',
		'sticky_posts' => '',
		'widget_categories' => '',
		'widget_text' => '',
		'widget_rss' => '',
		'uninstall_plugins' => '',
		// 2.8
		'timezone_string' => '',
		// 3.0
		'page_for_posts' => '',
		'page_on_front' => '',
		// 3.1
		'default_post_format' => '',
		// 3.5
		'link_manager_enabled' => '',
		// 4.3.0
		'finished_splitting_shared_terms' => '',
		'site_icon' => '',
		// 4.4.0
		'medium_large_size_w' => '',
		'medium_large_size_h' => '',
		// 4.9.6
		'wp_page_for_privacy_policy' => '',
		// 4.9.8
		'show_comments_cookies_opt_in' => '',
		// Deleted from new versions
		'blodotgsping_url' => '', 'bodyterminator' => '', 'emailtestonly' => '', 'phoneemail_separator' => '',
		'subjectprefix' => '', 'use_bbcode' => '', 'use_blodotgsping' => '', 'use_quicktags' => '', 'use_weblogsping' => '',
		'weblogs_cache_file' => '', 'use_preview' => '', 'use_htmltrans' => '', 'smilies_directory' => '', 'fileupload_allowedusers' => '',
		'use_phoneemail' => '', 'default_post_status' => '', 'default_post_category' => '', 'archive_mode' => '', 'time_difference' => '',
		'links_minadminlevel' => '', 'links_use_adminlevels' => '', 'links_rating_type' => '', 'links_rating_char' => '',
		'links_rating_ignore_zero' => '', 'links_rating_single_image' => '', 'links_rating_image0' => '', 'links_rating_image1' => '',
		'links_rating_image2' => '', 'links_rating_image3' => '', 'links_rating_image4' => '', 'links_rating_image5' => '',
		'links_rating_image6' => '', 'links_rating_image7' => '', 'links_rating_image8' => '', 'links_rating_image9' => '',
		'links_recently_updated_time' => '', 'links_recently_updated_prepend' => '', 'links_recently_updated_append' => '',
		'weblogs_cacheminutes' => '', 'comment_allowed_tags' => '', 'search_engine_friendly_urls' => '', 'default_geourl_lat' => '',
		'default_geourl_lon' => '', 'use_default_geourl' => '', 'weblogs_xml_url' => '', 'new_users_can_blog' => '', '_wpnonce' => '',
		'_wp_http_referer' => '', 'Update' => '', 'action' => '', 'rich_editing' => '', 'autosave_interval' => '', 'deactivated_plugins' => '',
		'can_compress_scripts' => '', 'page_uris' => '', 'update_core' => '', 'update_plugins' => '', 'update_themes' => '', 'doing_cron' => '',
		'random_seed' => '', 'rss_excerpt_length' => '', 'secret' => '', 'use_linksupdate' => '', 'default_comment_status_page' => '',
		'wporg_popular_tags' => '', 'what_to_show' => '', 'rss_language' => '', 'language' => '', 'enable_xmlrpc' => '', 'enable_app' => '',
		'embed_autourls' => '', 'default_post_edit_rows' => '',
		//Found in wp-admin/includes/upgrade.php
		'widget_search' => '',
		'widget_recent-posts' => '',
		'widget_recent-comments' => '',
		'widget_archives' => '',
		'widget_meta' => '',
		'sidebars_widgets' => '',
		// Found in wp-admin/includes/schema.php but not with the above list
		'initial_db_version' => '',
		'WPLANG' => '',
		// Found in wp-admin/includes/class-wp-plugins-list-table.php
		'recently_activated' => '',
		// Found in wp-admin/network/site-info.php
		'rewrite_rules' => '',
		// Found in wp-admin/network.php
		'auth_key' => '',
		'auth_salt' => '',
		'logged_in_key' => '',
		'logged_in_salt' => '',
		'nonce_key' => '',
		'nonce_salt' => '',
		// Found in wp-includes/theme.php
		'theme_switched' => '',
		// Found in wp-includes/class-wp-customize-manager.php
		'current_theme' => '',
		// Found in wp-includes/cron.php
		'cron' => '',
		'widget_nav_menu' => '',
		'_split_terms' => '',
		// Added in the new adbc 3.2.7
		'_wp_suggested_policy_text_has_changed' => '',
		'active_sitewide_plugins' => '',
		'admin_email_lifespan' => '',
		'adminhash' => '',
		'allowed_themes' => '',
		'allowedthemes' => '',
		'auto_core_update_checked' => '',
		'auto_core_update_failed' => '',
		'auto_core_update_last_checked' => '',
		'auto_core_update_notified' => '',
		'auto_plugin_theme_update_emails' => '',
		'auto_update_core_dev' => '',
		'auto_update_core_major' => '',
		'auto_update_core_minor' => '',
		'auto_update_plugins' => '',
		'auto_update_themes' => '',
		'blocklist_keys' => '',
		'blog_count' => '',
		'blog_upload_space' => '',
		'category_children' => '',
		'comment_previously_approved' => '',
		'core_updater.lock' => '',
		'customize_stashed_theme_mods' => '',
		'dashboard_widget_options' => '',
		'db_upgraded' => '',
		'deactivated_sitewide_plugins' => '',
		'delete_blog_hash' => '',
		'disallowed_keys' => '',
		'dismissed_update_core' => '',
		'dismissed_update_plugins' => '',
		'dismissed_update_themes' => '',
		'embed_size_h' => '',
		'embed_size_w' => '',
		'fileupload_maxk' => '',
		'fileupload_url' => '',
		'finished_updating_comment_type' => '',
		'fresh_site' => '',
		'ftp_credentials' => '',
		'global_terms_enabled' => '',
		'https_detection_errors' => '',
		'https_migration_required' => '',
		'illegal_names' => '',
		'large_image_threshold' => '',
		'layout_columns' => '',
		'links_per_page' => '',
		'ms_files_rewriting' => '',
		'my_array' => '',
		'my_option_name' => '',
		'nav_menu_options' => '',
		'network_admin_hash' => '',
		'new_admin_email' => '',
		'post_count' => '',
		'product_cat_children' => '',
		'recovery_keys' => '',
		'recovery_mode_auth_key' => '',
		'recovery_mode_auth_salt' => '',
		'recovery_mode_email_last_sent' => '',
		'registration' => '',
		'registrationnotification' => '',
		'secret_key' => '',
		'site_admins' => '',
		'site_logo' => '',
		'stylesheet_root' => '',
		'template_root' => '',
		'theme_mods_twentytwentythree' => '',
		'theme_switch_menu_locations' => '',
		'theme_switched_via_customizer' => '',
		'update_core_major' => '',
		'update_services' => '',
		'update_translations' => '',
		'upgrade_500_was_gutenberg_active' => '',
		'use_fileupload' => '',
		'user_count' => '',
		'welcome_user_email' => '',
		'widget_block' => '',
		'widget_calendar' => '',
		'widget_custom_html' => '',
		'widget_media_audio' => '',
		'widget_media_gallery' => '',
		'widget_media_image' => '',
		'widget_media_video' => '',
		'widget_pages' => '',
		'widget_recent_comments' => '',
		'widget_recent_entries' => '',
		'widget_tag_cloud' => '',
		'wp_calendar_block_has_published_posts' => '',
		'wp_force_deactivated_plugins' => '',
		'wpmu_sitewide_plugins' => '',
		'wpmu_upgrade_site' => '',
		'wp_attachment_pages_enabled' => '',
		// 6.9
		'wp_notes_notify' => '',
	];

	/**
	 * WordPress tables.
	 * 
	 * @var array
	 */
	private $wp_tables = [ 
		'terms' => '',
		'term_taxonomy' => '',
		'term_relationships' => '',
		'commentmeta' => '',
		'comments' => '',
		'links' => '',
		'options' => '',
		'postmeta' => '',
		'posts' => '',
		'users' => '',
		'usermeta' => '',
		// Since 3.0 in wp-admin/includes/upgrade.php
		'sitecategories' => '',
		// Since 4.4
		'termmeta' => '',
		'blogs' => '',
		'blog_versions' => '',
		'blogmeta' => '',
		'registration_log' => '',
		'signups' => '',
		'site' => '',
		'sitemeta' => '',
	];

	/**
	 * WordPress cron jobs.
	 * 
	 * @var array
	 */
	private $wp_cron_jobs = [ 
		'delete_expired_transients' => '',
		'do_pings' => '',
		'publish_future_post' => '',
		'recovery_mode_clean_expired_keys' => '',
		'update_network_counts' => '',
		'upgrader_scheduled_cleanup' => '',
		'wp_auto_updates_maybe_update' => '',
		'wp_delete_temp_updater_backups' => '',
		'wp_https_detection' => '',
		'wp_maybe_auto_update' => '',
		'wp_privacy_delete_old_export_files' => '',
		'wp_scheduled_auto_draft_delete' => '',
		'wp_scheduled_delete' => '',
		'wp_site_health_scheduled_check' => '',
		'wp_split_shared_term_batch' => '',
		'wp_update_comment_type_batch' => '',
		'wp_update_plugins' => '',
		'wp_update_themes' => '',
		'wp_update_user_counts' => '',
		'wp_version_check' => '',
		'importer_scheduled_cleanup' => '',
		'wp_schedule_delete' => '',
	];

	/**
	 * WordPress transients.
	 * 
	 * @var array
	 */
	private $wp_transients = [ 
		'_site_transient_available_translations' => '',
		'_site_transient_theme_roots' => '',
		'_site_transient_update_core' => '',
		'_site_transient_update_plugins' => '',
		'_site_transient_update_themes' => '',
		'_site_transient_wporg_theme_feature_list' => '',
		'_site_transient_wp_plugin_dependencies_plugin_data' => '',
		'_transient_dirsize_cache' => '',
		'_transient_doing_cron' => '',
		'_transient_health-check-site-status-result' => '',
		'_transient_is_multi_author' => '',
		'_transient_mailserver_last_checked' => '',
		'_transient_plugin_slugs' => '',
		'_transient_random_seed' => '',
		'_transient_settings_errors' => '',
		'_transient_wp_core_block_css_files' => '',
		'_transient_wporg_theme_feature_list' => '',
		'_transient_featured_content_ids' => '',
		'_transient_rewrite_rules' => '',
		'_transient_twentyfifteen_categories' => '',
		'_transient_twentyfourteen_category_count' => '',
		'_transient_twentyseventeen_categories' => '',
		'_transient_twentysixteen_categories' => '',
		'_transient_global_styles' => '',
		'_transient_update_core' => '',
		'_transient_update_plugins' => '',
		'_transient_update_themes' => '',
		'_transient_wp_styles_for_blocks' => '',
		'_site_transient_popular_importers_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_g_url_details_response_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_wp_font_collection_url_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'string' // url
		],
		'_site_transient_community-events-' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_wp_remote_block_patterns_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_browser_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_php_check_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_poptags_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_wordpress_credits_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'string'
		],
		'_site_transient_wp_theme_files_patterns-' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_transient_wp_generating_att_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'int'
		],
		'_transient_oembed_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_transient_dash_v2_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_transient_rss_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_transient_feed_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_transient_feed_mod_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_feed_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_feed_mod_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_transient_scrape_key_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'md5'
		],
		'_site_transient_wp_plugin_dependencies_plugin_timeout_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'string'
		],
		'_transient_global_styles_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'string'
		],
		'_transient_global_styles_svg_filters_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'string'
		],
		'_transient_plugins_delete_result_' => [ 
			'rule' => 'starts_with',
			'concatenated_with' => 'int'
		],

	];

	/**
	 * WordPress posts meta.
	 * 
	 * @var array
	 */
	private $wp_posts_meta = [ 
		'_cover_hash' => '',
		'_customize_changeset_uuid' => '',
		'_customize_draft_post_name' => '',
		'_customize_restore_dismissed' => '',
		'_edit_last' => '',
		'_edit_lock' => '',
		'_encloseme' => '',
		'_export_data_grouped' => '',
		'_export_data_raw' => '',
		'_export_file_name' => '',
		'_export_file_path' => '',
		'_export_file_url' => '',
		'_menu_item_classes' => '',
		'_menu_item_menu_item_parent' => '',
		'_menu_item_object' => '',
		'_menu_item_object_id' => '',
		'_menu_item_orphaned' => '',
		'_menu_item_target' => '',
		'_menu_item_type' => '',
		'_menu_item_url' => '',
		'_menu_item_xfn' => '',
		'_pingme' => '',
		'_post_restored_from' => '',
		'_source_url' => '',
		'_starter_content_theme' => '',
		'_thumbnail_id' => '',
		'_trackbackme' => '',
		'_wp_admin_notified' => '',
		'_wp_attached_file' => '',
		'_wp_attachment_backup_sizes' => '',
		'_wp_attachment_context' => '',
		'_wp_attachment_image_alt' => '',
		'_wp_attachment_is_custom_background' => '',
		'_wp_attachment_is_custom_header' => '',
		'_wp_attachment_metadata' => '',
		'_wp_desired_post_slug' => '',
		'_wp_old_date' => '',
		'_wp_old_slug' => '',
		'_wp_page_template' => '',
		'_wp_suggested_privacy_policy_content' => '',
		'_wp_trash_meta_comments_status' => '',
		'_wp_trash_meta_status' => '',
		'_wp_trash_meta_time' => '',
		'_wp_user_notified' => '',
		'_wp_user_request_completed_timestamp' => '',
		'_wp_user_request_confirmed_timestamp' => '',
		'enclosure' => '',
		'footnotes' => '',
		'imagedata' => '',
		'is_wp_suggestion' => '',
		'origin' => '',
	];

	/**
	 * WordPress users meta.
	 * 
	 * @var array
	 */
	private $wp_users_meta = [ 
		'_new_email' => '',
		'admin_color' => '',
		'aim' => '',
		'closedpostboxes_post' => '',
		'comment_shortcuts' => '',
		'community-events-location' => '',
		'default_password_nag' => '',
		'description' => '',
		'dismissed_wp_pointers' => '',
		'enable_custom_fields' => '',
		'first_name' => '',
		'icq' => '',
		'last_name' => '',
		'last_update' => '',
		'locale' => '',
		'managenav-menuscolumnshidden' => '',
		'manageuploadcolumnshidden' => '',
		'meta-box-order_post' => '',
		'metaboxhidden_nav-menus' => '',
		'metaboxhidden_post' => '',
		'msn' => '',
		'nav_menu_recently_edited' => '',
		'nickname' => '',
		'primary_blog' => '',
		'rich_editing' => '',
		'session_tokens' => '',
		'show_admin_bar_front' => '',
		'show_welcome_panel' => '',
		'source_domain' => '',
		'syntax_highlighting' => '',
		'upload_per_page' => '',
		'use_ssl' => '',
		'wp_capabilities' => '',
		'wp_dashboard_quick_press_last_post_id' => '',
		'wp_media_library_mode' => '',
		'wp_persisted_preferences' => '',
		'wp_user_level' => '',
		'wp_user-settings' => '',
		'wp_user-settings-time' => '',
		'wporg_favorites' => '',
		'yim' => '',
	];

	// TO-CHECK: Make sure all ADBC hardcoded items are added here

	/**
	 * ADBC plugin options.
	 * 
	 * @var array
	 */
	private $adbc_options = [ 
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

	/**
	 * ADBC plugin cron jobs.
	 * 
	 * @var array
	 */
	private $adbc_cron_jobs = [ 
		'adbc_cron_analytics' => '',
		'adbc_cron_automation' => '',
	];

	/**
	 * ADBC plugin transients.
	 * For this specific case, we save both the final transient name and the original name.
	 * 
	 * @var array
	 */
	private $adbc_transients = [ 
		'_transient_adbc_plugin_tables_to_repair' => 'adbc_plugin_tables_to_repair',
	];

	/**
	 * Most popular posts_meta and users_meta used to decide if a relation is unknown.
	 * @var array
	 */
	private $known_meta_dict = [ 
		// TO-CHECK: inserted all postmeta that have more than 30 relations
		"posts_meta" => [ 
			"_price" => "965",
			"_regular_price" => "832",
			"_sale_price" => "642",
			"_sku" => "488",
			"_stock_status" => "445",
			"_stock" => "386",
			"_elementor_data" => "369",
			"_manage_stock" => "326",
			"_transaction_id" => "318",
			"_product_attributes" => "301",
			"_elementor_edit_mode" => "289",
			"_payment_method" => "278",
			"_product_image_gallery" => "270",
			"_customer_user" => "265",
			"_yoast_wpseo_metadesc" => "255",
			"_visibility" => "230",
			"_virtual" => "220",
			"related-posts" => "206",
			"_sale_price_dates_to" => "201",
			"total_sales" => "194",
			"_billing_email" => "190",
			"description" => "189",
			"_yoast_wpseo_title" => "184",
			"_weight" => "182",
			"discount_type" => "178",
			"_billing_phone" => "171",
			"_billing_first_name" => "171",
			"_sale_price_dates_from" => "170",
			"coupon_amount" => "169",
			"layout" => "169",
			"title" => "168",
			"_purchase_note" => "168",
			"_order_total" => "168",
			"_billing_last_name" => "163",
			"_backorders" => "163",
			"_width" => "156",
			"_length" => "155",
			"_height" => "154",
			"_downloadable" => "152",
			"_featured" => "147",
			"position" => "146",
			"_sold_individually" => "143",
			"usage_limit" => "142",
			"individual_use" => "140",
			"expiry_date" => "139",
			"_payment_method_title" => "138",
			"_elementor_template_type" => "133",
			"keywords" => "133",
			"free_shipping" => "133",
			"_yoast_wpseo_focuskw" => "130",
			"product_ids" => "126",
			"_form" => "125",
			"_order_currency" => "124",
			"rule" => "120",
			"email" => "116",
			"exclude_product_ids" => "115",
			"type" => "114",
			"_wxr_import_menu_item" => "113",
			"_wxr_import_user_slug" => "112",
			"_wxr_import_parent" => "112",
			"hide_on_screen" => "111",
			"_shipping_address_1" => "111",
			"allorany" => "110",
			"_shipping_city" => "110",
			"_wxr_import_has_attachment_refs" => "109",
			"_billing_country" => "108",
			"_shipping_postcode" => "107",
			"_elementor_page_settings" => "106",
			"price" => "106",
			"_shipping_country" => "104",
			"_format_link_url" => "103",
			"_billing_city" => "102",
			"thumbnail" => "101",
			"_format_quote_source_url" => "101",
			"_billing_address_1" => "101",
			"twp_disable_ajax_load_next_post" => "99",
			"site_layout" => "96",
			"website_url" => "95",
			"_shipping_first_name" => "95",
			"_shipping_last_name" => "93",
			"apply_before_tax" => "92",
			"Image" => "92",
			"_shipping_address_2" => "90",
			"item_value" => "88",
			"featured_item" => "88",
			"_elementor_version" => "88",
			"_billing_postcode" => "88",
			"testimonial_by" => "87",
			"remove_title_page" => "87",
			"remove_box_content" => "87",
			"frame_style" => "87",
			"feat_serv_item" => "87",
			"feat_post" => "87",
			"currency_val" => "87",
			"rank_math_description" => "86",
			"blog-cats" => "85",
			"_billing_state" => "85",
			"related-cat" => "83",
			"related-tag" => "82",
			"_shipping_state" => "82",
			"status" => "80",
			"customer_email" => "79",
			"_elementor_css" => "78",
			"_billing_address_2" => "78",
			"_aioseop_description" => "77",
			"_post_type" => "76",
			"_order_key" => "76",
			"_layout" => "76",
			"_taxonomy" => "75",
			"_taxonomy_rewrite" => "74",
			"_rewrite" => "74",
			"_label_singular" => "74",
			"_label_plural" => "74",
			"panels_data" => "70",
			"_tax_status" => "70",
			"_billing_company" => "70",
			"rank_math_focus_keyword" => "69",
			"_tax_class" => "69",
			"_shipping_company" => "69",
			"_customer_ip_address" => "68",
			"rank_math_title" => "67",
			"_order_tax" => "67",
			"_sidebar_primary" => "64",
			"_featured_header_id" => "64",
			"url" => "63",
			"_product_url" => "63",
			"subtitle" => "62",
			"phone" => "61",
			"location" => "61",
			"_aioseop_title" => "61",
			"address" => "60",
			"minimum_amount" => "59",
			"_menu_item_icon" => "59",
			"_sidebar_secondary" => "58",
			"usage_limit_per_user" => "55",
			"_order_shipping" => "55",
			"views" => "54",
			"video_url" => "54",
			"date_expires" => "53",
			"_wpb_shortcodes_custom_css" => "53",
			"featured" => "52",
			"_format_video_embed" => "52",
			"link" => "51",
			"_cart_discount" => "51",
			"_yoast_wpseo_meta-robots-noindex" => "50",
			"_wc_average_rating" => "50",
			"_order_shipping_tax" => "50",
			"_mail" => "49",
			"product_categories" => "48",
			"exclude_sale_items" => "47",
			"_downloadable_files" => "47",
			"rating" => "46",
			"name" => "46",
			"exclude_product_categories" => "45",
			"city" => "45",
			"_format_audio_embed" => "45",
			"_default_attributes" => "45",
			"first_name" => "44",
			"_shipping_phone" => "44",
			"twitter" => "43",
			"last_name" => "43",
			"geo_longitude" => "43",
			"geo_latitude" => "43",
			"_download_limit" => "43",
			"_completed_date" => "43",
			"usage_count" => "42",
			"maximum_amount" => "42",
			"facebook" => "42",
			"_variation_description" => "42",
			"_aioseo_description" => "42",
			"thumb" => "41",
			"sidebar_select" => "40",
			"post_views_count" => "40",
			"menu-item-mm-megamenu-subcat" => "40",
			"menu-item-mm-megamenu-posts" => "40",
			"_prices_include_tax" => "40",
			"_download_expiry" => "40",
			"show_on_page" => "39",
			"field_group_layout" => "39",
			"_paid_date" => "39",
			"_format_gallery_images" => "39",
			"_customer_user_agent" => "39",
			"_created_via" => "39",
			"start_date" => "38",
			"longitude" => "38",
			"wpml_language" => "37",
			"user_id" => "37",
			"portfolio_image" => "37",
			"latitude" => "37",
			"_email" => "37",
			"video" => "36",
			"field_test_field" => "36",
			"country" => "36",
			"author" => "36",
			"_yoast_wpseo_opengraph-description" => "36",
			"_thankyou_action_done" => "36",
			"_menu_item_megamenu" => "36",
			"_aioseop_keywords" => "35",
			"_fl_builder_enabled" => "35",
			"_order_discount" => "35",
			"transaction_id" => "34",
			"limit_usage_to_x_items" => "34",
			"heading" => "34",
			"_upsell_ids" => "34",
			"_crosssell_ids" => "34",
			"redirect" => "33",
			"cyberchimps_slider_size" => "33",
			"cyberchimps_slider_lite_slide_two_url" => "33",
			"cyberchimps_slider_lite_slide_two_image" => "33",
			"cyberchimps_slider_lite_slide_three_url" => "33",
			"cyberchimps_slider_lite_slide_three_image" => "33",
			"cyberchimps_slider_lite_slide_one_url" => "33",
			"cyberchimps_slider_lite_slide_one_image" => "33",
			"cyberchimps_portfolio_title_toggle" => "33",
			"cyberchimps_portfolio_title" => "33",
			"cyberchimps_portfolio_lite_image_two_caption" => "33",
			"cyberchimps_portfolio_lite_image_two" => "33",
			"cyberchimps_portfolio_lite_image_three_caption" => "33",
			"cyberchimps_portfolio_lite_image_three" => "33",
			"cyberchimps_portfolio_lite_image_one_caption" => "33",
			"cyberchimps_portfolio_lite_image_one" => "33",
			"cyberchimps_portfolio_lite_image_four_caption" => "33",
			"cyberchimps_portfolio_lite_image_four" => "33",
			"cyberchimps_portfolio_link_url_two" => "33",
			"cyberchimps_portfolio_link_url_three" => "33",
			"cyberchimps_portfolio_link_url_one" => "33",
			"cyberchimps_portfolio_link_url_four" => "33",
			"cyberchimps_portfolio_link_toggle_two" => "33",
			"cyberchimps_portfolio_link_toggle_three" => "33",
			"cyberchimps_portfolio_link_toggle_one" => "33",
			"cyberchimps_portfolio_link_toggle_four" => "33",
			"cyberchimps_page_sidebar" => "33",
			"cyberchimps_page_section_order" => "33",
			"_tracking_number" => "33",
			"_status" => "33",
			"_post_like_count" => "33",
			"_order_stock_reduced" => "33",
			"_enable_dropship" => "33",
			"_dropship_location" => "33",
			"width" => "32",
			"duration" => "32",
			"currency" => "32",
			"course_id" => "32",
			"_yoast_wpseo_twitter-description" => "32",
			"_yoast_wpseo_opengraph-image" => "32",
			"_user_IP" => "32",
			"_seopress_titles_desc" => "32",
			"_button_text" => "32",
			"end_date" => "31",
			"_wc_review_count" => "31",
			"_order_number" => "31",
			"_et_pb_use_builder" => "31",
			"state" => "30",
			"post-image" => "30",
			"order_id" => "30",
			"hide_title" => "30",
			"height" => "30",
			"gallery" => "30",
			"ed_header_overlay" => "30",
			"_user_liked" => "30",
			"_type" => "30",
			"_my_meta_value_key" => "30",
			"_locale" => "30",
		],
		// TO-CHECK: inserted all usermeta that have more than 20 relations
		"users_meta" => [ 
			"billing_phone" => "421",
			"billing_country" => "275",
			"billing_city" => "255",
			"billing_first_name" => "255",
			"billing_last_name" => "252",
			"billing_address_1" => "248",
			"billing_postcode" => "244",
			"billing_state" => "232",
			"ignore_hints" => "224",
			"billing_email" => "204",
			"billing_address_2" => "189",
			"billing_company" => "174",
			"twitter" => "154",
			"facebook" => "134",
			"shipping_first_name" => "129",
			"shipping_last_name" => "126",
			"shipping_city" => "121",
			"shipping_address_1" => "121",
			"shipping_country" => "120",
			"shipping_postcode" => "120",
			"shipping_state" => "114",
			"shipping_address_2" => "104",
			"phone" => "104",
			"shipping_company" => "85",
			"linkedin" => "83",
			"acf_user_settings" => "81",
			"last_login" => "62",
			"instagram" => "56",
			"shipping_phone" => "53",
			"pinterest" => "48",
			"wp_email_tracking_ignore_notice" => "46",
			"display_name" => "44",
			"country" => "40",
			"dismiss-kirki-recommendation" => "39",
			"phone_number" => "38",
			"address" => "37",
			"youtube" => "37",
			"avatar" => "36",
			"themeisle_sdk_dismissed_notice_black_friday" => "36",
			"wcfmmp_profile_settings" => "34",
			"example_ignore_notice" => "33",
			"wpclever_wpcstore_ignore" => "32",
			"flickr" => "32",
			"dokan_profile_settings" => "32",
			"city" => "30",
			"user_email" => "30",
			"google" => "29",
			"dribbble" => "27",
			"shipping_email" => "27",
			"nag_remove_theme_review_notice_partially" => "26",
			"remove_theme_review_notice" => "26",
			"email" => "25",
			"mobile" => "25",
			"gender" => "25",
			"optionsframework_ignore_notice" => "24",
			"googleplus" => "23",
			"user_phone" => "22",
			"wp_user_avatar" => "22",
			"user_url" => "21",
		]
	];

	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct();
		$this->add_special_wordpress_options();
	}

	/**
	 * Add special WordPress options to the hardcoded options list.
	 * 
	 * @return void
	 */
	private function add_special_wordpress_options() {

		global $wpdb;

		// The 'user_roles' option is added as $prefix.'user_roles'
		if ( is_multisite() ) {
			$sites = ADBC_Sites::instance()->get_sites_list();
			foreach ( $sites as $site ) {
				$this->wp_options[ $site['prefix'] . 'user_roles' ] = '';
			}
		} else {
			$this->wp_options[ $wpdb->prefix . 'user_roles' ] = '';
		}

		// Add also theme_mods option
		$child_theme_slug = get_stylesheet();
		$parent_theme_slug = get_template();
		$this->wp_options[ 'theme_mods_' . $child_theme_slug ] = '';
		if ( $child_theme_slug != $parent_theme_slug ) {
			$this->wp_options[ 'theme_mods_' . $parent_theme_slug ] = '';
		}

	}

	/**
	 * Get the WP hardcoded items based on the items type.
	 * 
	 * @param string $items_type The items type to get the hardcoded items for.
	 * @return array The hardcoded items or an empty array if the items type is not found.
	 */
	public function get_wordpress_items( $items_type ) {

		switch ( $items_type ) {
			case 'tables':
				return $this->wp_tables;
			case 'options':
				return $this->wp_options;
			case 'cron_jobs':
				return $this->wp_cron_jobs;
			case 'transients':
				return $this->wp_transients;
			case 'posts_meta':
				return $this->wp_posts_meta;
			case 'users_meta':
				return $this->wp_users_meta;
			default:
				return [];
		}

	}

	/**
	 * Get the ADBC hardcoded items based on the items type.
	 * 
	 * @param string $items_type The items type to get the hardcoded items for.
	 * @return array The hardcoded items or an empty array if the items type is not found.
	 */
	public function get_adbc_items( $items_type ) {

		switch ( $items_type ) {
			case 'options':
				return $this->adbc_options;
			case 'cron_jobs':
				return $this->adbc_cron_jobs;
			case 'transients':
				return $this->adbc_transients;
			default:
				return [];
		}

	}

	/**
	 * Load hardcoded scan results to tables rows.
	 * This function will override the belongs_to property of the tables rows with the hardcoded scan results.
	 * 
	 * @param array $tables_rows The tables rows to load the hardcoded scan results to.
	 * 
	 * @return void
	 */
	public function load_hardcoded_scan_results_to_tables_rows( &$tables_rows ) {

		$wp_hardcoded_items = $this->get_wordpress_items( 'tables' );
		$adbc_hardcoded_items = $this->get_adbc_items( 'tables' );

		foreach ( $tables_rows as $table_name => $table_data ) {

			// For tables, we should search for the table name without prefix, because hardcoded tables are saved without any prefix.
			$table_name_without_prefix = $tables_rows[ $table_name ]->table_name_without_prefix;

			if ( isset( $wp_hardcoded_items[ $table_name_without_prefix ] ) ) {

				$tables_rows[ $table_name ]->belongs_to = [ 
					'type' => 'w',
					'slug' => 'w',
					'name' => __( 'WordPress core', 'advanced-database-cleaner' ),
					'by' => 'l',
					'percent' => 100,
					'status' => 'active',
				];
				// Set known plugins/themes to empty arrays because we are sure that this item is not related to any plugin/theme.
				$tables_rows[ $table_name ]->known_plugins = [];
				$tables_rows[ $table_name ]->known_themes = [];

			} else if ( isset( $adbc_hardcoded_items[ $table_name_without_prefix ] ) ) {

				$tables_rows[ $table_name ]->belongs_to = [ 
					'type' => 'p',
					'slug' => ADBC_PLUGIN_DIR_NAME,
					'name' => ADBC_Plugins::instance()->get_plugin_name_from_slug( ADBC_PLUGIN_DIR_NAME ),
					'by' => 'l',
					'percent' => 100,
					'status' => 'active',
				];
				// Set known plugins/themes to empty arrays because we are sure that this item is not related to any plugin/theme.
				$tables_rows[ $table_name ]->known_plugins = [];
				$tables_rows[ $table_name ]->known_themes = [];

			}
		}
	}

	/**
	 * Load hardcoded scan results to items rows.
	 * This function will override the belongs_to property of the items rows with the hardcoded scan results.
	 * 
	 * @param array $items_rows The items rows to load the hardcoded scan results to.
	 * @param string $items_type The items type to load the hardcoded scan results for.
	 * @return void
	 */
	public function load_hardcoded_scan_results_to_items_rows( &$items_rows, $items_type ) {

		$adbc_hardcoded_items = $this->get_adbc_items( $items_type );
		$wp_hardcoded_items = $this->get_wordpress_items( $items_type );

		foreach ( $items_rows as $index => $item ) {

			if ( $this->is_item_belongs_to_wp_core( $item->name, $items_type, $wp_hardcoded_items ) ) {

				$items_rows[ $index ]->belongs_to = [ 
					'type' => 'w',
					'slug' => 'w',
					'name' => __( 'WordPress core', 'advanced-database-cleaner' ),
					'by' => 'l',
					'percent' => 100,
					'status' => 'active',
				];
				// Set known plugins/themes to empty arrays because we are sure that this item is not related to any plugin/theme.
				$items_rows[ $index ]->known_plugins = [];
				$items_rows[ $index ]->known_themes = [];

			} else if ( isset( $adbc_hardcoded_items[ $item->name ] ) ) {

				$items_rows[ $index ]->belongs_to = [ 
					'type' => 'p',
					'slug' => ADBC_PLUGIN_DIR_NAME,
					'name' => ADBC_Plugins::instance()->get_plugin_name_from_slug( ADBC_PLUGIN_DIR_NAME ),
					'by' => 'l',
					'percent' => 100,
					'status' => 'active',
				];
				// Set known plugins/themes to empty arrays because we are sure that this item is not related to any plugin/theme.
				$items_rows[ $index ]->known_plugins = [];
				$items_rows[ $index ]->known_themes = [];

			}
		}
	}

	/**
	 * Exclude hardcoded items from selected items.
	 * This function will remove the hardcoded items from the selected items.
	 * 
	 * @param array  $selected_items  The selected items to exclude the hardcoded items from.
	 * @param string $items_type      The items type to exclude the hardcoded items from.
	 * @param string $type_to_exclude The type of hardcoded items to exclude. Can be 'all', 'wp', or 'adbc'.
	 * @return array The cleaned selected items without the hardcoded items.
	 */
	public function exclude_hardcoded_items_from_selected_items( $selected_items, $items_type, $type_to_exclude = 'all' ) {

		$exclude_wp = ( $type_to_exclude === 'all' || $type_to_exclude === 'wp' );
		$exclude_adbc = ( $type_to_exclude === 'all' || $type_to_exclude === 'adbc' );

		$adbc_hardcoded_items = $exclude_adbc ? $this->get_adbc_items( $items_type ) : [];
		$wp_hardcoded_items = $exclude_wp ? $this->get_wordpress_items( $items_type ) : [];
		$cleaned_items = [];

		foreach ( $selected_items as $selected_item ) {

			if ( empty( $selected_item['name'] ) )
				continue; // skip malformed entry

			$name = $selected_item['name'];

			// For tables, we should search for the table name without prefix,
			// because hardcoded tables are saved without any prefix.
			if ( $items_type === 'tables' )
				$name = ADBC_Tables::remove_prefix_from_table_name( $name );

			$is_wp_core = false;
			$is_adbc = false;

			// Check WordPress core hardcoded items (exact + rule-based for transients).
			if ( $exclude_wp && $this->is_item_belongs_to_wp_core( $name, $items_type, $wp_hardcoded_items ) )
				$is_wp_core = true;

			// Check ADBC hardcoded items (exact matches only).
			if ( $exclude_adbc && isset( $adbc_hardcoded_items[ $name ] ) )
				$is_adbc = true;

			// If item is not hardcoded (WP core nor ADBC), keep it.
			if ( ! $is_wp_core && ! $is_adbc )
				$cleaned_items[] = $selected_item;

		}

		return $cleaned_items;

	}


	/**
	 * Check if an item exists in the dictionary of common meta keys.
	 * 
	 * @param string $item_name The item name to check.
	 * @param string $items_type The items type (posts_meta or users_meta).
	 * @return bool True if item exists in common dict, false otherwise.
	 */
	public function is_item_in_known_meta_dict( $item_name, $items_type ) {

		if ( array_key_exists( $item_name, $this->known_meta_dict[ $items_type ] ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Remove the hardcoded items from the list.
	 * 
	 * This function removes items that are known to belong to WordPress core
	 * or to the ADBC plugin (hardcoded items) from the given list.
	 * It supports both exact matches and rule-based entries.
	 * 
	 * @param array  $items_list The list of items to remove the hardcoded items from (passed by reference).
	 * @param string $items_type The items type to remove the hardcoded items from.
	 * @return array The list of items without the hardcoded items.
	 */
	public function remove_hardcoded_items_from_list( &$items_list, $items_type ) {

		$adbc_items = $this->get_adbc_items( $items_type );
		$wp_hardcoded_items = $this->get_wordpress_items( $items_type );

		foreach ( $items_list as $item_name => $data ) {

			if ( $item_name === '' )
				continue;

			// Remove WordPress core hardcoded items (exact + rule-based).
			if ( $this->is_item_belongs_to_wp_core( $item_name, $items_type, $wp_hardcoded_items ) ) {
				unset( $items_list[ $item_name ] );
				continue;
			}

			// Remove ADBC hardcoded items (exact matches for now).
			if ( isset( $adbc_items[ $item_name ] ) ) {
				unset( $items_list[ $item_name ] );
				continue;
			}

		}

		return $items_list;

	}

	/**
	 * Check if an item belongs to WordPress core.
	 * 
	 * This function checks both hardcoded items without rules and items
	 * defined with matching rules (starts_with, ends_with, contains)
	 * for the given items type.
	 * 
	 * @param string $item_name  The item name to check.
	 * @param string $items_type The items type (options, tables, cron_jobs, transients, posts_meta, users_meta).
	 * @param array|null $wp_items Optional preloaded WP core items array to avoid recomputing it.
	 * @return bool True if the item belongs to WordPress core, false otherwise.
	 */
	public function is_item_belongs_to_wp_core( $item_name, $items_type, $wp_items = null ) {

		if ( $wp_items === null )
			$wp_items = $this->get_wordpress_items( $items_type );

		if ( empty( $wp_items ) )
			return false;

		// For all items except transients, only exact match is needed.
		if ( $items_type !== 'transients' ) {

			if ( isset( $wp_items[ $item_name ] ) )
				return true;

			return false;

		}

		// ---- TRANSIENTS: exact and rule-based ---- //

		// First, check exact transients with no rule.
		if ( isset( $wp_items[ $item_name ] ) && ! is_array( $wp_items[ $item_name ] ) )
			return true;

		// Then check rule-based entries.
		foreach ( $wp_items as $pattern => $data ) {

			if ( ! is_array( $data ) || empty( $data['rule'] ) )
				continue;

			$rule = $data['rule']; // always starts_with, ends_with, contains
			$concat_type = isset( $data['concatenated_with'] ) ? $data['concatenated_with'] : 'string';

			// ---- starts_with ---- //
			if ( $rule === 'starts_with' ) {

				if ( strpos( $item_name, $pattern ) !== 0 )
					continue;

				$dynamic_part = substr( $item_name, strlen( $pattern ) );
				if ( $this->is_valid_hardcoded_concatenation( $dynamic_part, $concat_type ) )
					return true;

			}

			// ---- ends_with ---- //
			else if ( $rule === 'ends_with' ) {

				$pattern_len = strlen( $pattern );
				if ( $pattern_len === 0 || substr( $item_name, -$pattern_len ) !== $pattern )
					continue;

				$dynamic_part = substr( $item_name, 0, -$pattern_len );
				if ( $this->is_valid_hardcoded_concatenation( $dynamic_part, $concat_type ) )
					return true;

			}

			// ---- contains ---- //
			else if ( $rule === 'contains' ) {

				if ( strpos( $item_name, $pattern ) !== false )
					return true;

			}

		}

		return false;

	}

	/**
	 * Validate the dynamic part of a hardcoded item name based on its type.
	 * 
	 * Supported types:
	 * - md5    : 32 hex characters.
	 * - int    : numeric string.
	 * - string : any non-empty string.
	 * 
	 * @param string $value The dynamic part to validate.
	 * @param string $type  The expected type (md5, int, string).
	 * @return bool True if the value matches the expected type, false otherwise.
	 */
	private function is_valid_hardcoded_concatenation( $value, $type ) {

		if ( $value === '' )
			return false;

		switch ( $type ) {

			case 'md5':
				return strlen( $value ) === 32 && ctype_xdigit( $value );

			case 'int':
				return ctype_digit( $value );

			case 'string':
			default:
				return true;

		}

	}


}