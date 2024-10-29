<?php
/**
 * Plugin Name:     ATAS Auto Tweet Scheduler
 * Description:     ATAS automatically publishes pages, posts and custom posts on Twitter in a nicely formatted way included with the post title, description, backlink, and image. The whole process is completely automated. Using ATAS you can get more Twitter followers and drive more traffic to your blog posts.
 * Version:         1.0.4
 * Author:          Codeweby
 * Author URI:      https://codeweby.com/
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     atas-auto-tweet-and-scheduler
 *
 * @package         ATASWP
 * @author          Codeweby - Attila Abraham
 * @copyright       Copyright (c) Codeweby - Attila Abraham
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'ATASWP' ) ) {
	
 class ATASWP {
	 
	/**
	 * @var         ATASWP $instance 
	 * @since       1.0.0
	 */
	private static $instance;
	
	/**
	 * Get active instance
	 *
	 * @access      public
	 * @since       1.0.0
	 * @return      object self::$instance
	 */
	public static function instance() {
		if( !self::$instance ) {
			self::$instance = new ATASWP();
			self::$instance->constants();
			self::$instance->includes();
			self::$instance->hooks();
			self::$instance->load_textdomain();
		}
		return self::$instance;
	}
	
	/**
	 * Plugin constants
	 *
	 * @access      private
	 * @since       1.0.0
	 * @return      void
	 */
	private function constants() {
		// Plugin path
		define( 'ATASWP_DIR', plugin_dir_path( __FILE__ ) );
		// Plugin URL
		define( 'ATASWP_URL', plugin_dir_url( __FILE__ ) );
		// Plugin FILE
		define( 'ATASWP_PLUGIN_FILE', __FILE__ );
		// CSS
		define( 'ATASWP_CSS_MODE', 'css' ); // css or css-min
		// JS
		define( 'ATASWP_JS_MODE', 'js' ); // js or js-min
	}
	
	/**
	 * Include files
	 *
	 * @access      private
	 * @since       1.0.0
	 * @return      void
	 */
	private function includes() {
		
		#### Libraries
		include_once ATASWP_DIR . 'includes/libraries/twitteroauth/autoload.php';
		
		#### ADMIN
		require_once ATASWP_DIR . 'includes/class-admin-core.php';
		require_once ATASWP_DIR . 'includes/classes/class-helper.php';
		require_once ATASWP_DIR . 'includes/classes/class-multisite.php';
		require_once ATASWP_DIR . 'includes/classes/class-twitter.php';
		require_once ATASWP_DIR . 'includes/classes/class-twitter-account.php';
		require_once ATASWP_DIR . 'includes/classes/class-importer.php';
		require_once ATASWP_DIR . 'includes/classes/class-scheduler.php';
		require_once ATASWP_DIR . 'includes/classes/class-twitter-tweet.php';
		require_once ATASWP_DIR . 'includes/classes/class-scheduler-settings.php';
		require_once ATASWP_DIR . 'includes/classes/class-auto-post.php';
		require_once ATASWP_DIR . 'includes/classes/class-wp-cron.php';
		require_once ATASWP_DIR . 'includes/classes/class-scheduler-table.php';
		require_once ATASWP_DIR . 'includes/classes/class-auto-post-metabox.php';
		require_once ATASWP_DIR . 'includes/classes/class-scheduler-log.php';
		
		#### PUBLIC
		require_once ATASWP_DIR . 'includes/class-public-core.php';
	}
	
	/**
	 * Run action and filter hooks
	 *
	 * @access      private
	 * @since       1.0.0
	 * @return      void
	 */
	private function hooks() {
		#### ADMIN
		$admin_core          = new ATASWP_Admin_Core(); 
		$multisite           = new ATASWP_Multisite();
		$twitter             = new ATASWP_Twitter();
		$twitter_account     = new ATASWP_Twitter_Account();
		$importer            = new ATASWP_Importer();
		$scheduler           = new ATASWP_Scheduler();
		$twitter_tweet       = new ATASWP_Twitter_Tweet();
		$scheduler_settings  = new ATASWP_Scheduler_Settings();
		$auto_post           = new ATASWP_Auto_Post();
		$scheduler_table     = new ATASWP_Scheduler_Table();
		$auto_post_metabox   = new ATASWP_Auto_Post_Metabox();
		$scheduler_log       = new ATASWP_Scheduler_Log();
		
		add_action( 'admin_menu', array($admin_core, 'admin_menu') );
		
		// run cron
		//add_action( 'p2sap_wp_cron_one_minutes_event', array($scheduler, 'wp_cron_test') );
		add_action( 'ataswp_wp_cron_one_minutes_event', array($scheduler, 'run_scheduler') );
		
	    // Multisite
	    add_action( 'wpmu_new_blog', array($multisite, 'multisite_new_site_activation'), 12, 6 );
		
		// twitter app connect account
		add_action( 'wp_ajax_twitter_app_connect_account', array($twitter_account, 'twitter_app_connect_account') );
		add_action( 'wp_ajax_nopriv_twitter_app_connect_account', array($twitter_account, 'twitter_app_connect_account') );
		add_action( 'wp_ajax_twitter_app_disconnect_account', array($twitter_account, 'twitter_app_disconnect_account') );
		add_action( 'wp_ajax_nopriv_twitter_app_disconnect_account', array($twitter_account, 'twitter_app_disconnect_account') );
		add_action( 'init', array($twitter_account, 'twitter_app_callback') ); // virtual page for twitter app

		// importer display posts
		add_action( 'wp_ajax_display_posts', array($importer, 'display_posts') );
		add_action( 'wp_ajax_nopriv_display_posts', array($importer, 'display_posts') );
		
		// run posts importer
		add_action( 'wp_ajax_run_importer', array($importer, 'run_importer') );
		add_action( 'wp_ajax_nopriv_run_importer', array($importer, 'run_importer') );
		
		// scheduler page, remove page
		add_action( 'wp_ajax_remove_page', array($scheduler, 'remove_page') );
		add_action( 'wp_ajax_nopriv_remove_page', array($scheduler, 'remove_page') );
		
		// scheduler settings page
		add_action( 'wp_ajax_scheduler_settings', array($scheduler_settings, 'scheduler_settings') );
		add_action( 'wp_ajax_nopriv_scheduler_settings', array($scheduler_settings, 'scheduler_settings') );
		
		// auto post settings page
		add_action( 'wp_ajax_auto_post_settings', array($auto_post, 'auto_post_settings') );
		add_action( 'wp_ajax_nopriv_auto_post_settings', array($auto_post, 'auto_post_settings') );
		
		// live clock
		add_action( 'wp_ajax_live_clock', array($admin_core, 'live_clock') );
		add_action( 'wp_ajax_nopriv_live_clock', array($admin_core, 'live_clock') );
		
		// scheduler table multiple forms
		add_action( 'wp_ajax_scheduler_update_post', array($scheduler_table, 'scheduler_update_post') );
		add_action( 'wp_ajax_nopriv_scheduler_update_post', array($scheduler_table, 'scheduler_update_post') );
		
		// Log, delete single log
		add_action( 'wp_ajax_process_delete_single_log', array($scheduler_log, 'process_delete_single_log') );
		add_action( 'wp_ajax_nopriv_process_delete_single_log', array($scheduler_log, 'process_delete_single_log') );
		
		// Log, delete all logs
		add_action( 'wp_ajax_process_delete_all_logs', array($scheduler_log, 'process_delete_all_logs') );
		add_action( 'wp_ajax_nopriv_process_delete_all_logs', array($scheduler_log, 'process_delete_all_logs') );
		
		// auto post metabox
		add_action( 'add_meta_boxes', array($auto_post_metabox, 'add_auto_post_metabox') );
		add_action( 'save_post', array($auto_post_metabox, 'save_auto_post_metabox'), 10, 2 );
		
		// process auto post metabox tweet and display admin notice
		add_action( 'ataswp_process_twitter_auto_post', array($auto_post, 'process_auto_post_metabox_tweet'), 10, 1 ); // <- extended
		add_action( 'admin_notices', array($auto_post, 'auto_post_metabox_tweet_notice') );
		
		add_action( 'admin_enqueue_scripts', array($admin_core, 'admin_enqueue_styles') );
		add_action( 'admin_enqueue_scripts', array($admin_core, 'admin_enqueue_scripts') );
		
		
		#### PUBLIC
		$public_core  = new ATASWP_Public_Core(); 
		
		//add_action( 'wp_enqueue_scripts', array($public_core, 'public_enqueue_styles'), 15 ); // ### Important! Load style after theme style (15)
		//add_action( 'wp_enqueue_scripts', array($public_core, 'public_enqueue_scripts') );	
	}
	
	/**
	 * Internationalization
	 *
	 * @access      public
	 * @since       1.0.0
	 * @return      void
	 */
	public function load_textdomain() {
		// language directory
		$lang_dir = ATASWP_DIR . '/languages/';
	}
	
 }
	
} // if class_exists end


/*
 * Load: ATASWP
 */
 
function ATASWP_Load() {

  // require plugin.php
  require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  
  // only load plugin if Cataloggi active
  // if( class_exists( 'Cataloggi' ) ) {
    return ATASWP::instance();
  // }
}
add_action( 'plugins_loaded', 'ATASWP_Load' );

/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, 'ataswp_activation' ); 
function ataswp_activation( $network_wide ) {
	
	global $wpdb;
	
	
	// allow editor to access the plugin, add roles
    $role = get_role( 'editor' );
    $role->add_cap( 'edit_pages' ); // capability
    
	
    if ( ! current_user_can( 'activate_plugins' ) )
        return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/classes/install/class-activator.php';
	
	// Check if the plugin is being network-activated or not.
	if ( $network_wide ) {
		// Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
		if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
		  $site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
		} else {
		  $site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;" );
		}
		
		// Install the plugin for all these sites.
		foreach ( $site_ids as $site_id ) {
		  switch_to_blog( $site_id );
          ATASWP_Activator::install_single_site();
		  restore_current_blog();
		}
	} else {
        ATASWP_Activator::install_single_site();
	}
	
}

/**
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook( __FILE__, 'ataswp_deactivation' ); 
function ataswp_deactivation( $network_wide ) {
	
	global $wpdb;
    
	
	// allow editor to access the plugin, remove roles
    $role = get_role( 'editor' );
    $role->remove_cap( 'edit_pages' ); // capability
	
	
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
		
	require_once plugin_dir_path( __FILE__ ) . 'includes/classes/install/class-deactivator.php';

	// Check if the plugin is being network-activated or not.
	if ( $network_wide ) {
		// Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
		if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
		  $site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
		} else {
		  $site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;" );
		}
		
		// Install the plugin for all these sites.
		foreach ( $site_ids as $site_id ) {
		  switch_to_blog( $site_id );
		  
			// DEACTIVATION
			ATASWP_Deactivator::single_site();
			
		  restore_current_blog();
		}
	} else {
		// DEACTIVATION
		ATASWP_Activator::flush_rewrite_rules();
	}
	
}

/**
 * The code that runs during plugin uninstallation.
 */
register_uninstall_hook( __FILE__, 'ataswp_uninstall' ); 
function ataswp_uninstall() {
	
	global $wpdb;
		
	
	// allow editor to access the plugin, remove roles
    $role = get_role( 'editor' );
    $role->remove_cap( 'edit_pages' ); // capability
	
	
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
		
	//this check makes sure that this file is called manually.
	if (!defined("WP_UNINSTALL_PLUGIN"))
		return;
	
	require_once plugin_dir_path( __FILE__ ) . 'includes/classes/install/class-activator.php';
	
	// Check if we are on a Multisite or not.
	if ( is_multisite() ) {
		// Retrieve all site IDs from all networks (WordPress >= 4.6 provides easy to use functions for that).
		if ( function_exists( 'get_sites' ) ) {
		  $site_ids = get_sites( array( 'fields' => 'ids' ) );
		} else {
		  $site_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs;" );
		}
		
		// Uninstall the plugin for all these sites.
		foreach ( $site_ids as $site_id ) {
		  switch_to_blog( $site_id );
		  
		  // UNINSTALLATION
		  ATASWP_Activator::flush_rewrite_rules();
			
		  restore_current_blog();
		}
	} else {
		// UNINSTALLATION
		ATASWP_Activator::flush_rewrite_rules();
	}
	
}


?>