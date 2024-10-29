<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Cataloggi 
 * @subpackage ataswp/includes
 * @author     Attila Abraham
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 
 
class ATASWP_Deactivator {

	/**
	 * Install site settings.
	 *
	 * @since    1.0.0
	 */
	public static function single_site() 
	{
		ATASWP_Deactivator::wp_cron_clean_the_scheduler();
		
		ATASWP_Deactivator::flush_rewrite_rules();
	}
	
	/**
	 * WP Cron clean the scheduler on deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function wp_cron_clean_the_scheduler() {
		
		/*
		//first find the next schedule callback time
		$one_minutes = wp_next_scheduled("ataswp_wp_cron_one_minutes_event");
		//use this function to unschedule it by passing the time and event name
		wp_unschedule_event($one_minutes, "ataswp_wp_cron_one_minutes_event");
		
		//first find the next schedule callback time
		$five_minutes = wp_next_scheduled("ataswp_wp_cron_five_minutes_event");
		//use this function to unschedule it by passing the time and event name
		wp_unschedule_event($five_minutes, "ataswp_wp_cron_five_minutes_event");
		
		//first find the next schedule callback time
		$fifteen_minutes = wp_next_scheduled("ataswp_wp_cron_fifteen_minutes_event");
		//use this function to unschedule it by passing the time and event name
		wp_unschedule_event($fifteen_minutes, "ataswp_wp_cron_fifteen_minutes_event");
        */

		$args = array( false );
		wp_clear_scheduled_hook('ataswp_wp_cron_one_minutes_event', $args);
		wp_clear_scheduled_hook('ataswp_wp_cron_five_minutes_event', $args);
		wp_clear_scheduled_hook('ataswp_wp_cron_fifteen_minutes_event', $args);
		
	}
	
	/**
	 * This is how you would flush rewrite rules when a plugin is activated
	 *
	 * @since    1.0.0
	 */
	public static function flush_rewrite_rules() {
	    flush_rewrite_rules( false ); // soft flush. Default is true (hard), update rewrite rules
	}

}

?>
