=== Advanced Database Cleaner – Optimize & Clean Database to Speed Up Site Performance ===
Contributors: symptote
Donate Link: https://www.sigmaplugin.com/donation
Tags: clean, database, optimize, performance, postmeta
Requires at least: 5.0.0
Requires PHP: 7.0
Tested up to: 6.9
Stable tag: 4.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Clean database by deleting orphaned data such as 'revisions', 'expired transients', optimize database and more...

== Description ==

Advanced Database Cleaner is a complete WordPress optimization plugin that helps you clean up database clutter and optimize database performance by removing unused data such as old revisions, auto drafts, spam comments, expired transients, unused post meta, duplicated post meta, unused user meta, etc. 

It is designed to help you improve website speed by reducing database bloat and ensuring a lean, efficient WordPress installation. It also provides detailed previews, powerful filters, and automation tools to safely control what gets cleaned. 

With the ✨[**Premium version**](https://sigmaplugin.com/downloads/wordpress-advanced-database-cleaner/?utm_source=wprepo&utm_medium=readme&utm_campaign=wordpress&utm_content=landing_page)✨, you can unlock even more advanced features, such as detecting and cleaning orphaned options, orphaned tables, orphaned post meta, orphaned user meta, orphaned transients, and orphaned cron jobs. It also gives you clear insights into how your database evolves over time through built-in analytics, lets you monitor plugin and theme activity to better understand when new data is created or when leftovers appear, and much more.

### Why use Advanced Database Cleaner❓

👉 **Get a clear overview**: see how many tables, options, transients, cron jobs, metadata... records you have, and identify which are unused or orphaned.

👉 **Save time**: configure what to clean, how far back to keep data, and how often to run automations. The plugin will then handle recurring cleanups for you.

👉 **Save space and improve performance**: removing unnecessary data reduces database size, makes backups faster, and can improve query performance, especially on busy or older sites.

#### ✅ Main Features
* Delete old revisions of posts and pages
* Delete old auto-drafts
* Delete trashed posts
* Delete pending comments
* Delete spam comments
* Delete trashed comments
* Delete pingbacks
* Delete trackbacks
* Delete unused post meta
* Delete unused comment meta
* Delete unused user meta
* Delete unused term meta
* Delete unused relationships
* Delete expired transients
* Delete duplicated post meta
* Delete duplicated user meta
* Delete duplicated comment meta
* Delete duplicated term meta
* Delete oEmbed caches
* Display the database size that will be freed before cleaning for each item type, and the total size to be freed
* Display and preview items to clean before performing a database cleanup to ensure safety
* Sorting capability in cleanup preview tables (by name, date, size, site id, etc.)
* View options value content in original or formatted mode for serialized or JSON structures (and other items types as well).
* Keep last X days of data: clean only data older than the number of days you specify

#### ✅ Automation
* Schedule database cleanup to run automatically
* Create scheduled cleanup tasks and specify which items each task should clean
* Schedule database optimization and/or repair to run automatically
* Execute scheduled tasks based on several frequencies: once, hourly, twice a day, daily, weekly, or monthly
* Specify the "keep last X days" rule for each item type in a scheduled task
* Pause/Resume scheduled tasks whenever needed
* Create as many scheduled cleanup tasks as needed and specify what each task should clean

#### ✅ Tables
* Display the list of database tables with information such as number of rows, table size, engine, etc.
* Sort tables by any column such as table name or table size
* Detect and filter tables with invalid prefixes (tables that do not belong to the current WordPress installation), this can be enabled or disabled from the settings page
* Optimize database tables (the plugin notifies you when tables require optimization)
* Repair corrupted or damaged database tables (the plugin notifies you when tables are corrupted)
* Empty rows of database tables
* Clean and delete database tables

#### ✅ Options
* Display the options list with information such as option name, option value, option size, and autoload status
* Sort options by any column such as option name or option size
* View option value content in original or formatted mode for serialized or JSON structures.
* Notify you if autoloaded options are large and help reduce autoload size for better performance
* Detect large options that may slow down your website
* Set option autoload to yes/no
* Clean and delete options

#### ✅ Cron Jobs
* Display the list of active cron jobs (scheduled tasks) with information such as arguments, action, next run, schedule, etc.
* Sort cron jobs by any column such as action name or next run time
* Detect cron jobs with no valid actions
* Clean and delete scheduled tasks

#### ✅ Post Meta
* Display the post meta list with information such as meta key, value, size, associated post ID, etc.
* Sort post meta by any column such as meta key, meta size, or post ID
* View post meta value content in original or formatted mode for serialized or JSON structures.
* Detect unused post meta (meta not associated with any existing posts)
* Detect duplicated post meta (same meta key/value for the same post ID)
* Clean and delete post meta

#### ✅ User Meta
* Display the user meta list with information such as meta key, value, size, associated user ID, etc.
* Sort user meta by any column such as meta key, meta size, or user ID
* View user meta value content in original or formatted mode for serialized or JSON structures.
* Detect unused user meta (meta not associated with any existing users)
* Detect duplicated user meta (same meta key/value for the same user ID)
* Clean and delete user meta

#### ✅ Transients
* Display the list of transients with information such as name, value, size, and expiration time
* Sort transients by any column such as transient name, size, or expiration time
* View transient value content in original or formatted mode for serialized or JSON structures.
* Clean expired transients
* Detect large transients that may slow down your website
* Clean and delete transients
* Set transient autoload to yes/no

#### ✅ Other Tools
* Display current database size
* Logging system for easy troubleshooting
* Access the WordPress debug log directly from the plugin interface
* Multisite support (network-wide database cleanup and optimization from the main site)
* Modern, responsive interface powered by React for a smooth experience without page reloads
* Show/hide plugin tabs for better usability

#### ⚡ Premium Features ⚡ [**Official website**](https://sigmaplugin.com/downloads/wordpress-advanced-database-cleaner/?utm_source=wprepo&utm_medium=readme&utm_campaign=wordpress&utm_content=landing_page)

Unlock the full power of database cleanup and optimization with Advanced Database Cleaner Premium - packed with smart features that take accuracy, speed, and cleanup control to the next level.

#### ✅ Remote SmartScan
* Local scan + Remote SmartScan technology to accurately detect the true owners of tables, options, post meta, user meta, transients, and cron jobs
* Cloud-enhanced ownership detection using a large and continuously improving remote database
* Improved accuracy for identifying orphaned items left by deleted plugins and themes
* Ability to edit ownership of any item and correct misidentified owners
* Ability to send ownership corrections to improve the global detection database
* Enhanced "Belongs to" ownership column everywhere using cloud data + local data
* Display multiple possible owners for each item when applicable
* Display owner status (active, inactive, not installed) to simplify cleanup decisions
* Check your remote scan credits to monitor usage

#### ✅ Action Scheduler Cleanup
* Clean Action Scheduler Completed actions
* Clean Action Scheduler Failed actions
* Clean Action Scheduler Canceled actions
* Clean Action Scheduler Completed logs
* Clean Action Scheduler Failed logs
* Clean Action Scheduler Canceled logs
* Clean Action Scheduler Orphan logs

#### ✅ General Cleanup Enhancements
* Keep last X items feature in General Cleanup
* Keep last X items per parent (e.g., per post)
* Keep last X items globally (e.g., keep the last 10 pingbacks)
* Combine Keep Last X Days with Keep Last X Items for advanced cleanup safety

#### ✅ Advanced Filters
* Advanced filters in all modules (Tables, Options, Post Meta, User Meta, Transients, Cron Jobs)
* Filter by size, value content, autoload, expiration, metadata type, and more
* Filter by plugin owner, theme owner, WordPress core, orphan, or unknown
* Filter by multisite site ID with full per-site visibility
* Filter by action frequency and interval in cron jobs
* Filter by duplicated, unused, large, not-yet-scanned, or expired items

#### ✅ Advanced Automation
* Unlimited automation tasks (Free version is limited to 5 tasks)
* Create any number of scheduled cleanup tasks with different configurations
* Create scheduled optimization and repair tasks
* Use Keep Last X Items and Keep Last X Days inside scheduled tasks
* Run automation tasks hourly, twice daily, daily, weekly, monthly, or at any supported frequency
* Pause/resume/delete automation tasks without losing settings
* Per-task automation event logging showing executed actions, number of items cleaned, execution timestamps, and detailed logs

#### ✅ Database Analytics
* Daily tracking of total database size and number of tables
* Daily and monthly charts showing database growth trends
* Raw data tab with all recorded measurements
* Table-level analytics showing size growth, rows growth, and daily changes
* Ability to detect abnormal table growth caused by logs, caches, or runaway actions
* Multi-table selection and search for analyzing multiple tables at once

#### ✅ Addons Activity
* Automatically track plugin activations, deactivations, and uninstalls
* Automatically track theme switches and uninstalls
* Display activity in a color-coded timeline for better readability
* All timestamps shown in your local timezone
* Multisite support (activity recorded on the main site)

#### ✅ Full Multisite Support
* Clean any site or all sites
* Filter items by site ID in every module (Tables, Options, Post Meta, User Meta, Transients, Cron Jobs)
* Display which site each item belongs to
* Run automation tasks across the entire network

== Installation ==

This section describes how to install the plugin. In general, there are 3 ways to install this plugin like any other WordPress plugin.

= 1. Via WordPress dashboard =

* Click on "Add New" in the Plugins dashboard.
* Search for "advanced-database-cleaner".
* Click the "Install Now" button.
* Activate the plugin from the same page or from the Plugins dashboard.

= 2. Via uploading the plugin to WordPress dashboard =

* Download the plugin to your computer from: https://wordpress.org/plugins/advanced-database-cleaner/
* Click on "Add New" in the Plugins dashboard.
* Click on the "Upload Plugin" button.
* Select the zip file of the plugin that you downloaded.
* Click "Install Now".
* Activate the plugin from the Plugins dashboard.

= 3. Via FTP =

* Download the plugin to your computer from: https://wordpress.org/plugins/advanced-database-cleaner/
* Unzip the zip file, which will extract the "advanced-database-cleaner" directory.
* Upload the "advanced-database-cleaner" directory (included inside the extracted folder) to the /wp-content/plugins/ directory in your web space.
* Activate the plugin from the Plugins dashboard.

= For Multisite installation =

* Log in to your primary site and go to "My Sites" » "Network Admin" » "Plugins".
* Install the plugin following one of the above ways.
* Network-activate the plugin. (Only the main site can access the full network-wide cleanup tools.)

= Where is the plugin menu? =

* The plugin can be accessed via "Dashboard" » "WP DB Cleaner" or "Dashboard" » "Tools" » "WP DB Cleaner" (depending on your settings).

== Screenshots ==

1. General Cleanup overview (list of database items to clean, total count & size)
2. Preview items before cleaning - Revisions example (filters in Premium)
3. Keep Last rules - Revisions example (keep last X items in Premium)
4. Tables overview (filters & scan in Premium)
5. Options overview (filters & scan in Premium)
6. Post Meta overview (filters & scan in Premium)
7. User Meta overview (filters & scan in Premium)
8. Transients overview (filters & scan in Premium)
9. Cron Jobs overview (filters & scan in Premium)
10. Start Scan modal - Full scan selected (in Premium)
11. Scan running for Options - Exact Match step (in Premium)
12. More info about an Option ownership (in Premium)
13. Edit an Option ownership (in Premium)
14. Automation cleanup tasks overview
15. Create an Automation Revisions cleanup task (keep last 2 revisions per post)
16. Revisions cleanup Automation task events log (in Premium)
17. Database analytics - Last 30 days daily charts (in Premium)
18. Tables analytics - Last 30 days, actionscheduler_logs & wp_options selected (in Premium)
19. Addons Activity - Timeline of activation, deactivation & uninstall (in Premium)
20. Info & Logs - System Info tab selected
21. Settings page

== Changelog ==

= 4.0.3 – 14/12/2025 =
- Fix: Improved compatibility with PHP 7.
- Tweak: Optimized the loading of the Post Meta module for large websites.
- Tweak: Highlighted preset filter section counters are now fetched via separate endpoints for better performance.
- Tweak: Optimized the duplicated meta module to improve performance.
- Tweak: Optimized the General Cleanup module for faster loading.
- Tweak: Overall performance improvements and internal code optimizations.

= 4.0.2 – 05/12/2025 =
- Fix: Conflict with another plugin injecting links into our plugin settings.
- Fix: Syntax error: unexpected '...' (T_ELLIPSIS), expecting ']'.
- Fix: Deletion of transients and expired_transients in multisite within the sitemeta table when the transient's site_id is invalid.
- Fix: Duplicate "squared" transients and expired transients being displayed.
- Tweak: Synchronize Axios timeout (React) with PHP max execution time to avoid early request timeouts.
- Tweak: In trashed comments, count only trashed comments and ignore comments belonging to trashed posts.
- Tweak: Use crc32 hashing to speed up detection of duplicate values.
- Tweak: General code cleanup and optimization.
- Tweak: [Premium] Added new WordPress-related items for improved identification.
- New: [Free] new setting allowing to control the number of items retrieved from the database per request for better performance.
- New: Choose between native WordPress functions or direct SQL queries for deleting items (new setting added).
- New: Items in the General Cleanup page are now loaded individually, so content appears immediately without waiting for all items.
- New: Items can now be deleted one by one in General Cleanup without reloading the entire list after each action.
- Compatibility: Tested with WordPress 6.9.

= 4.0.1 – 01/12/2025 =
- Fix: handling FS_METHOD ftpext in the file system class.
- Fix: sub-sites in Multisites were not loaded correctly.
- Fix: options and other items cannot be deleted in free version.

= 4.0.0 – 28/11/2025 =

Version 4.0.0 marks the biggest upgrade ever released for Advanced Database Cleaner. This major update introduces a completely redesigned interface for a smoother, faster, and more intuitive experience. It also brings powerful new features, an enhanced two-step scan engine for unmatched accuracy, and advanced security improvements that make database maintenance safer than ever. With better performance, more flexibility, and a modern UI, version 4.0.0 sets a new standard for professional WordPress database optimization.

- New: Duplicated post meta cleanup type.
- New: Duplicated user meta cleanup type.
- New: Duplicated comment meta cleanup type.
- New: Duplicated term meta cleanup type.
- New: oEmbed caches cleanup type.
- New: Estimated size to clean displayed for each cleanup type, plus a total freed-space summary before running a cleanup.
- New: Sorting capability added to cleanup preview tables (e.g. by name, date, size, site ID).
- New: Value viewer added to several cleanup types, displaying serialized or JSON data in raw or formatted views.
- New: Dedicated Post Meta Management module to list, sort, inspect, and clean post meta, including detection of unused and duplicated metadata.
- New: Dedicated User Meta Management module to list, sort, inspect, and clean user meta, including detection of unused and duplicated metadata.
- New: Dedicated Transients Management module to inspect, sort, and clean transients, with expiration tracking, detection of large transients, and control over their autoload status.
- New: Tables Management can now detect tables with invalid prefixes that do not belong to the current WordPress installation, with their visibility controlled from the Settings page.
- New: Options Management now includes a formatted value viewer, detection of large options, and warnings for heavy autoloaded options to help reduce autoload size.
- New: Cron Jobs Management now includes detection of cron jobs with no valid action/callback to help you clean them safely.
- New: All six management modules now detect items owned by WordPress core and Advanced Database Cleaner, making it clearer where data comes from.
- New: All six management modules now include an Attention Area that highlights priority issues, warns you about items requiring action, and helps you quickly identify and target them.
- New: Introduced a built-in error and exception logging system, allowing logs to be copied or downloaded for support or user-side investigations.
- New: Added tools to display the current database size, show or hide the plugin’s menu tabs, and access the WordPress debug log directly from the interface.
- New: Modern, fully responsive interface rebuilt with React for a smoother, faster, and more intuitive user experience.
- Enhanced: Cleaning process in the General Cleanup module now uses WordPress native deletion functions for deeper, hook-aware cleanup, with direct SQL deletion kept only as a safe fallback when required.
- Enhanced: Automation is now centralized into a unified module with a clearer creation/edit flow and consistent use of the local timezone for all schedules.
- Enhanced: Options, Tables, and Cron Jobs modules now display richer information with additional columns and more detailed data for each item.
- Enhanced: System Info is now far more detailed and can be copied or downloaded, making it easier to share environment details, diagnose issues, and assist users during support.
- Enhanced: Overall multisite support now provides clearer separation between network and site data and safer network-wide cleanup and optimization.
- Enhanced: Backend architecture migrated to a REST API–driven system for significantly faster interactions and navigation without page reloads.
- Enhanced: Numerous bugs and edge cases were resolved across all modules, resulting in more stable behavior and more reliable, effective cleaning operations.
- Premium: New - Action Scheduler completed actions cleanup type.
- Premium: New - Action Scheduler failed actions cleanup type.
- Premium: New - Action Scheduler canceled actions cleanup type.
- Premium: New - Action Scheduler completed logs cleanup type.
- Premium: New - Action Scheduler failed logs cleanup type.
- Premium: New - Action Scheduler canceled logs cleanup type.
- Premium: New - Action Scheduler orphan logs cleanup type.
- Premium: New - "Keep last X items" rule introduced, either per parent (e.g. keep 5 revisions per post) or globally (e.g. keep the last 10 pingbacks), in addition to the existing "keep last X days" rule.
- Premium: New - Introduced Remote Scan system that combines the local scan with our cloud-based detection engine and continuously curated ownership database to deliver near-perfect accuracy when identifying the true owners of tables, options, post meta, user meta, transients, and cron jobs.
- Premium: New - Added the ability to anonymously send your ownership corrections to improve our global detection database and refine ownership results for all users.
- Premium: New - "Keep last X items" rule now configurable inside scheduled tasks, in addition to the existing "keep last X days", for more advanced and safer automated cleanups.
- Premium: New - Introduced Database Analytics module with daily and monthly charts, raw data views, and per-table analytics (size evolution, rows evolution, daily change breakdown), including multi-table selection for comparative analysis.
- Premium: New - Introduced Addons Activity module that automatically tracks plugin and theme activations, deactivations, uninstalls, and theme switches in a color-coded timeline using your local timezone.
- Premium: New - Added multisite filters to the General Cleanup preview, allowing items to be filtered by site ID or site name so you can focus on a specific site in the network.
- Premium: New - Introduced per-automation event logs showing what was cleaned, when each task ran, and how many items were processed.
- Premium: Enhanced - Scan process fully redesigned for greater robustness and accuracy, combining an improved local scan with Remote Scan results.
- Premium: Enhanced - Scan flow now offers clearer insights, guidance, and error handling throughout each step of the process.
- Premium: Enhanced - "Belongs to" ownership column enriched with cloud-backed data across all management modules for more accurate owner detection.
- Premium: Enhanced - Detailed ownership info modal added, showing all known plugins/themes related to each item.
- Premium: Enhanced - Owner status indicators added (active, inactive, or not installed) to support deeper investigations.
- Premium: Enhanced - Filtering capabilities expanded across all management modules with new filters by size, value content, autoload, expiration, owner type (plugin, theme, WordPress core, orphan, unknown), duplicates, unused, large, not-yet-scanned, and more, including filtering specifically by a chosen plugin or theme.
- Premium: Enhanced - Multisite experience improved with clearer cross-site visibility, safer network-level operations, and tighter integration of ownership and analytics across all sites.
- Premium: Enhanced - Numerous bugs and edge cases were resolved across all premium features, resulting in more stable behavior and more reliable, effective cleaning operations.

= 3.1.6 - 24/03/2025 =
- Fix: names containing HTML were not displayed correctly.
- Fix: certain transients, options, tables, and cron jobs could not be deleted.
- Fix: function _load_textdomain_just_in_time was called incorrectly.
- Fix: after optimizing tables, the plugin now refreshes the data to accurately reflect the database’s real status.
- Fix: enhanced the plugin's security.
- Fix (PRO): sometimes users were unable to deactivate their license.
- Tweak: improved how the plugin edits the autoload value for options.
- Tweak: increased the max_execution_time only after a scan has started, and under specific conditions.
- Tweak: cleaned up and enhanced some PHP, CSS, and JS code parts.
- New: the Options tab now displays the total size of autoloaded options.
- New: in Multisite, users can now choose to display the plugin menu in the Network Admin panel.
- New (PRO): added support for new autoload option values in filters: on, auto, auto-on, auto-off.
- New (PRO): users can now assign items to WordPress using the "manual categorization" feature.

= 3.1.5 - 19/09/2024 =
- Fix: Automatic conversion of false to array is deprecated
- Fix: Cannot modify header information - headers already sent..
- Fix: Object of class stdClass could not be converted to string

= 3.1.4 - 23/01/2024 =
- Security: enhancing the security by avoiding deserialization (thanks to Richard Telleng from Wordfence)
- PRO: fix endless scan reloading
- PRO: fix PHP warning: Implicit conversion from float to int
- PRO: some code cleanup

= 3.1.3 - 12/09/2023 =
- Security: enhancing the security by sanitizing some parameters
- Fix: fixed 'Constant FILTER_SANITIZE_STRING is deprecated in PHP 8'
- Fix: fixed 'Undefined property : stdClass::$data_free'
- Fix: fixed 'PHP Fatal error:  Uncaught TypeError: date(): Argument #2 ($timestamp) must be of type ?int'
- Tweak: better handling of nonces
- Compatibility: tested with the latest version of WordPress 6.3.1

= 3.1.2 - 22/02/2023 =
- Security fix: when saving the settings
- Fix: changing the 'autoload' of an option may sometimes result in it being created twice
- Fix: activating both the free and pro versions together causes compatibility issues
- Tweak: enhancing some blocks of code to use Ajax
- Tweak: better handling the use of the WP_List_Table class
- Tweak (PRO): enhancing the license page + the update process of the plugin
- Compatibility: Tested with the latest version of WordPress 6.1.1

= 3.1.1 - 24/06/2022 =
- Security fix: enhancing the security of the plugin by escaping some URLs before outputting them

= 3.1.0 - 16/06/2022 =
- Fix: fixing the error 'Fatal error: Can't use function return value in write context'
- Fix: fixing the Warning: count(): Parameter must be an array or an object that implements Countable
- Tweak: correcting of some typos and grammar
- Tweak: deleting some useless data from "overview & settings" tab
- Tweak: enhancing the CSS code, the plugin is responsive now and can be used in small screens
- Tweak: enhancing some blocks of PHP code
- New: adding "delete filter" for custom cleanup elements in "general cleanup" tab
- Info: since we have changed a lot of CSS code, please refresh your browser cache or click "Ctrl + F5"
- Info: great feature will be added to the next version

= 3.0.4 - 21/01/2022 =
- Tweak: Enhancing the security of the plugin
- Tweak: Testing the plugin with latest versions of WP

= 3.0.3 - 06/10/2020 =
- Tweak: Cleaning the code by deleting unused blocks of code
- Tweak: Enhancing the security of the plugin

= 3.0.2 - 01/09/2020 =
- Fix: fixing an issue in the general cleanup tab preventing users from deleting orphaned items
- Tweak: we are now using SweetAlert for all popup boxes
- Tweak: enhancing the JavaScript code
- Tweak: enhancing some blocks of code
- Tweak: enhancing the security of the plugin

= 3.0.1 - 26/08/2020 =
- Fix: some calls in the JS file has been corrected
- Fix: the warning "Deprecated: array_key_exists()" is now solved
- Fix: an issue of 'failed to open stream: No such file or directory' is now solved
- Tested with WordPress 5.5
- New features very soon!

= 3.0.0 - 05/12/2019 =
* IMPORTANT NOTICE FOR PRO USERS: After you upgrade to 3.0.0 from an old version, you will notice that WordPress has deactivated the plugin due to an error: 'Plugin file does not exist'. This is because we have renamed the pro plugin folder name from "advanced-db-cleaner" to "advanced-database-cleaner-pro", causing the WordPress to not being able to find the old one and therefore deactivating the plugin. Just activate it again. It doesn’t break anything. Once you activate the plugin again it will continue working normally without any issues. You will also probably lose the pro version after this upgrade (This is due to a conflict between the free and pro versions which is now solved). If it is the case, please follow these steps to restore your pro version with all new features: (https://sigmaplugin.com/blog/restore-pro-version-after-upgrade-to-3-0-0)

* COMPATIBILITY: The plugin is tested with WordPress 5.3
* CHANGE: Some changes to readme.txt file
* REMOVE: Drafts are not cleaned anymore in 3.0.0 since many users have reported that drafts are useful for them
* New: You can now clean up new items: pingbacks, trackbacks, orphaned user meta, orphaned term meta, expired transients
* New: The plugin icon in the left side menu has been changed to white color
* New: Change text-domain to 'advanced-database-cleaner'
* New: Enhancements to the look and feel of the plugin
* New: The sidebar has been changed for the free version and deleted in the pro version
* New: For options, we have added the option size column and two new actions: Set autoload to no / yes
* New: For tables, we have added two actions: Empty tables and repair tables
* New: You can now order and sort all items
* New: You can change the number of items per page
* New: You can keep last x days' data from being cleaned and clean only data older than the number of days you have specified
* New: You can specify elements to cleanup in a scheduled task. You can also create as many scheduled tasks as you need
* New: Add information to each line of unused data in 'General clean-up' tab to let users know more about each item they will clean
* New: Display/view items before cleaning them (in 'General cleanup' tab) is now in the free version
* New: Add a new setting to hide the "premium" tab in the free version
* Fix: Repair some strings with correct text domain
* Fix: Some tasks with arguments can't be cleaned. This is fixed now
* Fix: Some tasks with the same hook name and different arguments were not displayed. This is fixed now
* Fix: In some previous versions, tables were not shown for some users. This has been fixed
* PERFORMANCE: All images used by the plugin are now in SVG format
* PERFORMANCE: Restructuring the whole code for better performance
* SECURITY: add some _wpnonce to forms
* New (PRO): Add "Pro" to the title of the pro version to distinguish between the free and the pro versions
* New (PRO): You can now search and filter all elements: options, tables, tasks, etc. based on several criteria
* New (PRO): Add progress bar when searching for orphan items to show remaining items to process
* New (PRO): Add a category called "uncategorized" to let users see items that have not been categorized yet
* Fix (PRO): The activation issue is now fixed
* Fix (PRO): The scan of orphaned items generated timeout errors for big databases, we use now ajax to solve this
* Fix (PRO): A conflict between the free and the pro versions is now solved
* PERFORMANCE (PRO): We are now using an enhanced new update class provided by EDD plugin
* PERFORMANCE (PRO): Set autoload to no in all options used by the plugin
* PERFORMANCE (PRO): The plugin does not store scan results in DB anymore. We use files instead
* SECURITY (PRO): The license is now hidden after activation for security reasons
* WEBSITE (PRO): You can now view your purchase history, downloads, generate an invoice, upgrade your license, etc. [Read more](https://sigmaplugin.com/blog/how-to-get-access-to-my-account-and-downloads)
* WEBSITE (PRO): Enhancements of the [plugin premium page](https://sigmaplugin.com/downloads/wordpress-advanced-database-cleaner)

= 2.0.0 =
* Some changes to readme.txt file
* Changing the way the plugin can be translated
* Correcting __() to some texts
* Correcting some displaying texts
* Big change in styles
* Restructuring the whole code for better performance
* Creation of the plugin main page: (https://sigmaplugin.com/downloads/wordpress-advanced-database-cleaner)
* Adding language translation support
* Correct the time zone offset for the scheduled tasks
* Skipping InnoDB tables while optimizing
* Change size of lost tables data from 'o' to 'KB'
* Main menu is now under 'Tools' not 'settings'
* Adding separate left menu (can be disabled)
* Adding overview page with some useful information
* Adding settings page
* "Reset database" is now in a separate plugin (please view our plugins page)
* Multisite: now only the main site can clean the network
* New feature: Display/view items before cleaning them (Pro)
* New feature: view and clean options
* New feature: Detect orphan options, plugins options, themes options and WP options (Pro)
* New feature: view and clean cron (scheduled tasks)
* New feature: Detect orphan tasks, plugins tasks, themes tasks and WP tasks (Pro)
* New feature: view and clean database tables
* New feature: Detect orphan tables, plugins tables, themes tables and WP tables (Pro)

= 1.3.7 =
* Adding "clean trash-posts"
* Updating FAQ
* Updating readme file
* Tested up to: 4.4

= 1.3.6 =
* Fixing a problem in donate button
* Using _e() and __() for all texts in the plugin

= 1.3.5 =
* New feature: Adding "Clean Cron". You can now clean unnecessary scheduled tasks.
* Updating FAQ

= 1.3.1 =
* Adding FAQ

= 1.3.0 =
* Some code optimizations
* New feature: Support multisite. You can now clean and optimize your database in multisite installation.

= 1.2.3 =
* Some optimizations and style modifications
* New feature: Adding the scheduler. You can now schedule the clean-up and optimization of your database.

= 1.2.2 =
* Some optimizations and style modifications

= 1.2.1 =
* Some optimizations and style modifications
* "Clean database" tab shows now only elements that should be cleaned instead of listing all elements.
* "Clean database" tab shows now an icon that indicates the state of your database.
* "Optimize database" tab shows now only tables that should be optimized instead of listing all tables.
* "Optimize database" tab shows now an icon that indicates the state of your tables.
* "Reset database" shows now a warning before resetting the database.

= 1.2.0 =
* Some optimizations and style modifications
* New feature: Adding "Reset database"

= 1.1.1 =
* Some optimizations and style modifications
* Adding "Donate link"

= 1.1.0 =
* Some optimizations and style modifications
* New feature: Adding "Optimize Database"

= 1.0.0 =
* First release: Hello world!

== Upgrade Notice ==

= 4.0.0 =
Version 4.0.0 marks the biggest evolution of Advanced Database Cleaner since its creation. Everything has been rebuilt for speed, accuracy, and reliability. Please review the changelog for full details.

= 3.0.0 =
Known issues have been fixed in both free and pro versions (timeout error, activation, scheduled tasks...) New features have been added (new items to cleanup, filter & sort items...) Readme.txt file updated.

= 2.0.0 =
New release.

== Frequently Asked Questions ==

= Why should I "clean my database"? =
As you use WordPress, your database accumulates a large amount of unnecessary data such as revisions, spam comments, trashed comments, and more. This clutter slowly increases the size of your database, which can make your site slower and make backups take longer. Cleaning this data keeps your site lighter, faster, and easier to maintain.

= Is it safe to clean my database? =
Yes, it is safe. The plugin does not run any code that can break your site or delete posts, pages, or approved comments. It only removes items that WordPress considers unnecessary. However, you should always back up your database before performing any cleanup. This is required, not optional! Backups ensure you can always restore your site if something unexpected happens.

= Why should I "optimize my database"? =
Optimizing your database reclaims unused space and reorganizes the way data is stored inside your tables. Over time, tables become fragmented, especially on active websites. Optimization reduces storage usage and improves the speed at which your database responds. This process is safe and can significantly improve performance on large or busy websites.

= Is it safe to clean the cron (scheduled tasks)? =
Cron jobs allow WordPress and plugins to run tasks automatically (like checking for updates or sending emails). When a plugin is removed, some of its cron jobs may remain behind. These leftover tasks serve no purpose and can slow down wp-cron events. Cleaning unnecessary cron jobs is safe as long as you know which ones should be removed. If you are unsure, it is safer not to delete any cron jobs manually.

= What are "revisions"? What SQL code is used to clean them? =
WordPress stores revisions for each saved draft or update so you can review older versions. Over time, these accumulate and take up space.  
SQL used by the plugin to delete revisions:  
`DELETE FROM posts WHERE post_type = 'revision'`

= What are "auto drafts"? What SQL code is used to clean them? =
WordPress automatically creates auto-drafts while you are editing posts/pages. If those drafts are never published, they remain in the database.  
SQL used by the plugin to delete auto-drafts:  
`DELETE FROM posts WHERE post_status = 'auto-draft'`

= What are "pending comments"? What SQL code is used to clean them? =
Pending comments are comments waiting for your approval. If you have many bots submitting comments, this list can grow quickly.  
SQL used by the plugin to delete pending comments:  
`DELETE FROM comments WHERE comment_approved = '0'`

= What are "spam comments"? What SQL code is used to clean them? =
Spam comments are comments flagged as spam by you or by an anti-spam plugin. They can safely be deleted.  
SQL used by the plugin to delete spam comments:  
`DELETE FROM comments WHERE comment_approved = 'spam'`

= What are "trash comments"? What SQL code is used to clean them? =
Trash comments are deleted comments moved to the trash. They are no longer visible and can be permanently removed.  
SQL used by the plugin to delete trash comments:  
`DELETE FROM comments WHERE comment_approved = 'trash'`

= What are "trackbacks"? What SQL code is used to clean them? =
Trackbacks are a legacy system used by WordPress to allow one website to notify another that it has linked to its content. When a site receives a trackback, it appears as a type of comment on the post. Because trackbacks can be sent manually, they became heavily abused by spammers who use them to post unwanted links on websites.
SQL used by the plugin to delete trackbacks:  
`DELETE FROM comments WHERE comment_type = 'trackback'`

= What are "pingbacks"? What SQL code is used to clean them? =
Pingbacks are an automated notification system used by WordPress. When one website publishes a link to another site’s post, WordPress sends a pingback request to the linked site. If accepted, the pingback appears as a type of comment, confirming that another site has referenced your content. Because pingbacks are automated, they are often exploited by bots to generate spam requests. 
SQL used by the plugin to delete pingbacks:  
`DELETE FROM comments WHERE comment_type = 'pingback'`

= What is "unused post meta"? What SQL code is used to clean it? =
Post meta stores additional information for posts. When a post is deleted, some metadata may be left behind. This leftover "unused" data can grow over time.  
SQL used by the plugin to delete unused post meta:  
`DELETE pm FROM postmeta pm LEFT JOIN posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL`

= What is "unused comment meta"? What SQL code is used to clean it? =
Comment meta stores extra information for comments. When a comment is removed, some metadata may remain in the database.  
SQL used by the plugin to delete unused comment meta:  
`DELETE FROM commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM comments)`

= What is "unused user meta"? What SQL code is used to clean it? =
User meta stores additional data for users. If a user is deleted, their metadata may not be removed automatically.  
SQL used by the plugin to delete unused user meta:  
`DELETE FROM usermeta WHERE user_id NOT IN (SELECT ID FROM users)`

= What is "unused term meta"? What SQL code is used to clean it? =
Term meta stores extra information for taxonomy terms (categories, tags, etc.). If a term is removed, its metadata may remain behind.  
SQL used by the plugin to delete unused term meta:  
`DELETE FROM termmeta WHERE term_id NOT IN (SELECT term_id FROM terms)`

= What are "unused relationships"? What SQL code is used to clean them? =
The wp_term_relationships table links posts to categories/tags. When posts are deleted, related entries may remain in this table, taking unnecessary space.  
SQL used by the plugin to delete unused relationships:  
`DELETE FROM term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM posts)`

= What are "expired transients"? =
Transients are temporary cached data stored by plugins or themes. When they expire, they should be removed automatically. However, some expired transients may remain in the database. These can be safely cleaned to free space.

= Is this plugin compatible with multisite? =
Yes, the plugin is compatible with multisite. For safety, only the main site can clean the database for the entire network. Sub-sites cannot perform cleanup operations to avoid accidental damage.

= Is this plugin compatible with SharDB, HyperDB, or Multi-DB? =
Not yet. The plugin is not currently compatible with SharDB, HyperDB, or Multi-DB setups. Support may be added in future versions.

= Does this plugin clean itself after uninstall? =
Yes. The plugin removes all of its data and settings when uninstalled. A cleanup plugin that leaves clutter would not make sense!