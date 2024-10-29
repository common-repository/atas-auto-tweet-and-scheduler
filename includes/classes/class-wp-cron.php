<?php

/**
 * WP Cron class.
 *
 * @package     Post2Social
 * @subpackage  public/
 * @copyright   Copyright (c) 2017, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class ATASWP_WP_Cron {
	
	const FiveSeconds = 5;
	const OneMinutes  = 60;
	const FiveMinutes = 300;
	const FifteenMinutes  = 900;
	const OneWeek     = 604800;
	const OneMonth    = 2635200;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	
	public function __construct() {

		add_filter( 'cron_schedules', array( $this, 'ataswp_wp_cron_add_new_intervals') ); // wp use: cron_schedules
		add_action( 'wp', array( $this, 'ataswp_wp_cron_add_events' ) );

	}
	

	/**
	 * Register new schedules.
	 *
	 * @since 1.0.0
	 * @param array $schedules
	 * @return array
	 */
	public function ataswp_wp_cron_add_new_intervals($schedules) 
	{
		// The default intervals provided by WordPress are: hourly, twicedaily, daily
		
		// add one_minutes interval
		$schedules['one_minutes'] = array(
			'interval' => self::OneMinutes,
			'display'  => esc_html__('Every One Minutes', 'post2social')
		);
		// add five_minutes interval
		$schedules['five_minutes'] = array(
			'interval' => self::FiveMinutes,
			'display'  => esc_html__('Every Five Minutes', 'post2social')
		);
		// add fifteen_minutes interval
		$schedules['fifteen_minutes'] = array(
			'interval' => self::FifteenMinutes,
			'display'  => esc_html__('Every Fifteen Minutes', 'post2social')
		);
	
		return $schedules;
	}
	
	/**
	 * Add events.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function ataswp_wp_cron_add_events() {
		$this->one_minutes_event();
		$this->five_minutes_event();
		$this->fifteen_minutes_event();
	}
	
	/**
	 * Schedule event Every One Minutes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function one_minutes_event() {
		$args = array( false );
		if ( ! wp_next_scheduled( 'ataswp_wp_cron_one_minutes_event', $args ) ) {
			//  current_time( 'timestamp' ) returns local site timestamp
			wp_schedule_event( time(), 'one_minutes', 'ataswp_wp_cron_one_minutes_event', $args );
		}
	}
	
	/**
	 * Schedule event Every Five Minutes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function five_minutes_event() {
		$args = array( false );
		if ( ! wp_next_scheduled( 'ataswp_wp_cron_five_minutes_event', $args ) ) {
			//  current_time( 'timestamp' ) returns local site timestamp
			wp_schedule_event( time(), 'five_minutes', 'ataswp_wp_cron_five_minutes_event', $args );
		}
	}
	
	/**
	 * Schedule event Every Fifteen Minutes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function fifteen_minutes_event() {
		
		$args = array( false );
		if ( ! wp_next_scheduled( 'ataswp_wp_cron_fifteen_minutes_event', $args ) ) {
			//  current_time( 'timestamp' ) returns local site timestamp
			wp_schedule_event( time(), 'fifteen_minutes', 'ataswp_wp_cron_fifteen_minutes_event', $args );
		}
		
	}
	
	
}

$ataswp_wp_cron = new ATASWP_WP_Cron;

?>