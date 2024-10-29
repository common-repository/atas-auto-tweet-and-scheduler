<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cataloggi 
 * @subpackage ataswp/includes
 * @author     Attila Abraham
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 
 
class ATASWP_Activator {

	/**
	 * Install site settings.
	 *
	 * @since    1.0.0
	 */
	public static function install_single_site() 
	{
		ATASWP_Activator::twitter_app_single_account();
		ATASWP_Activator::scheduler_settings();
		ATASWP_Activator::auto_post_settings();
		ATASWP_Activator::scheduler_log_table();
		ATASWP_Activator::flush_rewrite_rules();
	}
	
	/**
	 * Scheduler log table.
	 *
	 * @since    1.0.0
	 */
	public static function scheduler_log_table() {
		
		// sql to create your table
		// NOTICE that:
		// 1. each field MUST be in separate line
		// 2. There must be two spaces between PRIMARY KEY and its name
		//    Like this: PRIMARY KEY[space][space](id)
		// otherwise dbDelta will not work
		
		global $ataswp_scheduler_log_table_db_version;
		$ataswp_scheduler_log_table_db_version = '1.0.0';
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'ataswp_scheduler_log'; // do not forget about tables prefix 
		
		// check if table exist
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		
		    //table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();
			$sql =
			"CREATE TABLE {$table_name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			post_id bigint(20) NOT NULL,
			custom_post_title varchar(260) NULL,
			status varchar(60) NOT NULL,
			datetime datetime NOT NULL,
			account varchar(60) NULL,
			PRIMARY KEY (id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
			
			update_option( 'ataswp_scheduler_log_table_db_version', $ataswp_scheduler_log_table_db_version ); // save version in options
		
		}
	}
	
	/**
	 * Twitter cards options.
	 *
	 * @since    1.0.0
	 */
	public static function twitter_app_single_account() 
	{
		// check if option exist
		if ( get_option('ataswp_twitter_app_single_account') )
			return;

			$arr = apply_filters( 'ataswp_twitter_app_single_account_filter', array( // <- extensible 
				'version'                     => '1.0.0',
				'twitter_user_name'           => '',
				'twitter_user_screen_name'    => '',
				'profile_image_url_https'     => '',
				'twitter_access_token'        => '',
				'twitter_access_token_secret' => ''
			) );
	
			update_option('ataswp_twitter_app_single_account', $arr);
	}
	
	/**
	 * Scheduler settings.
	 *
	 * @since    1.0.0
	 */
	public static function scheduler_settings() 
	{
		// check if option exist
		if ( get_option('ataswp_scheduler_settings') )
			return;

			$arr = apply_filters( 'ataswp_scheduler_settings_filter', array( // <- extensible 
				'version'                  => '1.0.0',
				'enable_scheduler'         => '1',
				'reset_scheduler'          => '0', // this will delete all the data
				'gmt_or_local_timezone'    => 'gmt', // Universal or Local Timezone
			) );
	
			update_option('ataswp_scheduler_settings', $arr);
	}
	
	/**
	 * Auto post settings.
	 *
	 * @since    1.0.0
	 */
	public static function auto_post_settings() 
	{
		// check if option exist
		if ( get_option('ataswp_auto_post_settings') )
			return;

			$arr = apply_filters( 'ataswp_auto_post_settings_filter', array( // <- extensible 
				'version'               => '1.0.0',
				'enable_auto_post'      => '1',
				'enabled_post_types'    => '',
				'words_into_hashtags'   => 'word1 ,word2, word3', 
			) );
	
			update_option('ataswp_auto_post_settings', $arr);
	}
	
	/**
	 * This is how you would flush rewrite rules when a plugin is activated
	 *
	 * @since    1.0.0
	 */
	public static function flush_rewrite_rules() {
	    //flush_rewrite_rules( false ); // soft flush. Default is true (hard), update rewrite rules
	}

}

?>
