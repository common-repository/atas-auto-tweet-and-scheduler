<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// real cron: 00,15,30,45 	* 	* 	* 	* wget -q -O - https://example.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1
class ATASWP_Scheduler
{

    const CRON_RUN_TIME_LOG = '0'; // save cron run time text log file to plugin folder
    const CRON_SCHEDULER_LOG = '0';
	const CRON_SCHEDULER_CHECK_LOG = '0';
	/**
	 * Run the scheduler.
	 *
	 * How to test: set the method to static then load the method manually, shoud return the tweet object
	 *
	 * @since  1.0.0
	 * @return object $tweet
	 */
    public function run_scheduler() 
	{
		global $wpdb;
		
		$cron_run_time_log        = ''; // def
		$cron_scheduler_log       = ''; // def
		$cron_scheduler_check_log = ''; // def
		$global_filters = ATASWP_Admin_Core::global_filters();
		if ( isset($global_filters['general']['cron_run_time_log']) && $global_filters['general']['cron_run_time_log'] == '1' ) {
			$cron_run_time_log = $global_filters['general']['cron_run_time_log'];
		}
		if ( isset($global_filters['general']['cron_scheduler_log']) && $global_filters['general']['cron_scheduler_log'] == '1' ) {
			$cron_scheduler_log = $global_filters['general']['cron_scheduler_log'];
		}
		if ( isset($global_filters['general']['cron_scheduler_check_log']) && $global_filters['general']['cron_scheduler_check_log'] == '1' ) {
			$cron_scheduler_check_log = $global_filters['general']['cron_scheduler_check_log'];
		}
		
		$data = ''; // def
		$check_log = ''; // def
		
		$app_single_account  = get_option('ataswp_twitter_app_single_account');
		$tw_screen_name      = isset( $app_single_account['twitter_user_screen_name'] ) ? sanitize_text_field( $app_single_account['twitter_user_screen_name'] ) : '';
		
		$scheduler_settings    = get_option('ataswp_scheduler_settings');
		$enable_scheduler      = isset( $scheduler_settings['enable_scheduler'] ) ? sanitize_text_field( $scheduler_settings['enable_scheduler'] ) : '';
		$gmt_or_local_timezone = isset( $scheduler_settings['gmt_or_local_timezone'] ) ? sanitize_text_field( $scheduler_settings['gmt_or_local_timezone'] ) : '';
		
		$timezone_date_time = ATASWP_Helper::wp_universal_GMT_date_time();
		
		if ($gmt_or_local_timezone == 'local' ) { 
		   $timezone_date_time = ATASWP_Helper::wp_local_site_date_time();
		} elseif ($gmt_or_local_timezone == 'gmt' ) {
		   $timezone_date_time = ATASWP_Helper::wp_universal_GMT_date_time();
		}
		
		$datetime_str = strtotime($timezone_date_time);
		$time_zone_date_time = date('Y-m-d H:i', $datetime_str);
		$time_zone_date      = date('Y-m-d', $datetime_str);
		$time_zone_time      = date("H:i", $datetime_str);
		$time_zone_hour      = date("H", $datetime_str);
		$time_zone_minutes   = date("i", $datetime_str);
		$time_zone_day       = date('l', $datetime_str); // name of the day e.g. Saturday
		
		// if not enabled return
		if ( $enable_scheduler != '1' )
		return;
		
		$currentdatetime = date("Y-m-d H:i:s");
        
		// check cron
		if ( $cron_run_time_log == '1' ) {
			// test cron
			$data = 'Scheduler Cron Run at: ' . $time_zone_date_time . ' Current Date Time: ' . $currentdatetime  . PHP_EOL;
			$filepath = ATASWP_DIR . '_ataswp-scheduler-cron-run-log.txt';
			$ataswp_scheduler_log = file_put_contents($filepath, $data , FILE_APPEND);
		}
		
		$table = $wpdb->prefix . 'postmeta'; // table, do not forget about tables prefix 
		$sql  = "
				SELECT *
				FROM $table
				WHERE meta_key = '_ataswp_scheduler' AND  meta_value = '1' ORDER BY post_id ASC
				";	
		$get_results = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A
		
		if ( $get_results ) {
			
			foreach($get_results as $result ) {
				// auto publish activated on the following posts, pages etc.
				$post_id = $result['post_id'];
				
				// get meta values by post_id
				$ataswp_scheduler   = get_post_meta($post_id,'_ataswp_scheduler', true);

				// only published
				if ( $ataswp_scheduler == '1' && get_post_status ( $post_id ) == 'publish' ) {
					
					$get_post_type = get_post_type( $post_id );
					
					$scheduler_counter = get_post_meta($post_id,'_ataswp_scheduler_counter', true); 
					$intervals         = get_post_meta($post_id,'_ataswp_scheduler_intervals', true); // json obj
					$times             = get_post_meta($post_id,'_ataswp_scheduler_times', true); // json obj
					$start_date        = get_post_meta($post_id,'_ataswp_scheduler_start_date', true);
					$end_date          = get_post_meta($post_id,'_ataswp_scheduler_end_date', true);
				
					$intervals_arr = json_decode($intervals, true); // conv to array
					$times_arr     = json_decode($times, true); // conv to array
					
					$data = array(
						"counter"		    => $scheduler_counter,
						"intervals"		    => $intervals_arr, // array	
						"times"	            => $times_arr, // array
						"start_date"	    => $start_date,
						"end_date"	        => $end_date
					);
					
					$run_cron_check = '0';
					// check start date
					if ( $start_date <= $time_zone_date ) {
						$run_cron_heck = '1';
					} else {
						// don't start cron
						$check_log = $timezone_date_time . ' Do not start cron: start date is greater than the time zone date.';
						$run_cron_heck = '0';
					}
					
					// check end date, if end date 0000-00-00 means unlimited
					if ( $end_date != '0000-00-00' ) {
						if ( $time_zone_date <= $end_date ) {
							$run_cron_heck = '1';
						} else {
							// don't start cron, end date is lower than time zone date
							$check_log = $timezone_date_time . ' Do not start cron: end date is lower than the time zone date.';
							$run_cron_heck = '0';
						}
					} else {
						$run_cron_heck = '1';
					}
					
					// process
					if ( $run_cron_heck == '1' ) {
						// if day is in array
		               if( in_array( $time_zone_day ,$intervals_arr ) ) {
						   
						   // if time is in array
						   if( in_array( $time_zone_time ,$times_arr ) ) {
							   
							    // Process Tweet
								// update counter
								$ataswp_scheduler_counter = $scheduler_counter + 1;
								update_post_meta($post_id,'_ataswp_scheduler_counter', $ataswp_scheduler_counter);
								
								// process to post
								$tweet = ATASWP_Scheduler::scheduler_process_tweet( $post_id ) ; // core class
								
								$post_title = esc_attr( get_the_title($post_id) ); // get post title
								
								$status  = ''; // def
								$account = $tw_screen_name;
								
								if ( !empty($tweet) ) {
									
									// create log
									$status  = 'success';
									
								} else {
									
									// create log
									$status  = 'missed';
									
								}
								
								// ### get custom post title ###
								$custom_post_title = ATASWP_Scheduler::custom_post_titles( $post_id );
								
								if ( !empty($custom_post_title) ) {
									$custom_post_title = $custom_post_title;
								} else {
									$custom_post_title = '';
								}
								
								// create log
								ATASWP_Scheduler_Log::db_insert_log( $post_id, $custom_post_title, $status, $timezone_date_time, $account );
						   }
						   
					   }
					}
				}	
			}
			
			if ( $cron_scheduler_check_log == '1' ) {
				if ( !empty($check_log) ) {
					$check_log = $check_log . ' ' . PHP_EOL;
					$filepath = ATASWP_DIR . '_ataswp-cron-scheduler-check-log.txt';
					$ataswp_scheduler_log = file_put_contents($filepath, $check_log , FILE_APPEND);
				}
			}
			
			return $data;
		}
			
	}
	
	/**
	 * Custom post titles.
	 * If Custom post titles enabled select one randomly and use as the tweet title.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param int $post_id
	 * @return object $tweet
	 */
	public static function custom_post_titles( $post_id ) 
	{	
		if ( empty( $post_id ) )
		return;
		
		// ### manage custom post titles ###
		$enable_custom_post_titles = get_post_meta($post_id,'_ataswp_scheduler_enable_custom_post_titles', true); 
		
		if ( isset($enable_custom_post_titles) && $enable_custom_post_titles == '1' ) {
			
			$custom_post_titles     = get_post_meta($post_id,'_ataswp_scheduler_custom_post_titles', true); // json obj
			
			if ( !empty($custom_post_titles) ) {
				
				$custom_post_titles_arr = json_decode($custom_post_titles, true); // convert to array
				
				// randomly select array key
				$arr_key   = array_rand($custom_post_titles_arr);
				// get the array value
				$title = $custom_post_titles_arr[$arr_key];
				return $title;
				
			}
			
		} else {
			return;
		}
		
	}
	
	/**
	 * Process Tweet.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param int $post_id
	 * @return object $tweet
	 */
	public static function scheduler_process_tweet( $post_id ) 
	{	
		if ( empty( $post_id ) )
		return;
		
		$title       = ''; // def
		$description = ''; // def
		$url         = ''; // def
		$author      = ''; // def
		
		// ### get custom post title ###
		$custom_post_title = ATASWP_Scheduler::custom_post_titles( $post_id );
		
		$description = ATASWP_Twitter_Tweet::create_post_description( $post_id );
		$url         = ATASWP_Twitter_Tweet::get_post_url( $post_id );
		$author      = ATASWP_Twitter_Tweet::get_post_author( $post_id );
		
		if ( !empty($custom_post_title) ) {
			
			$title = $custom_post_title;
			
		} else {
			
			$title       = ATASWP_Twitter_Tweet::get_post_title( $post_id );
			
			// replace words with defined hashtags
			$title       = ATASWP_Twitter_Tweet::words_to_hashtags( $title );
			$description = ATASWP_Twitter_Tweet::words_to_hashtags( $description );
		}
		
		$tweet = array(
			"title" 		=> $title,
			"description"	=> $description,
			"url"		    => $url,
			"author"		=> '@'.$author
		);
		
		$tweet = json_encode($tweet); // json encode before send
		
		$tweet = ATASWP_Twitter::tweet( $tweet ); // process tweet
		
		return $tweet;
		
		/*
		echo '<pre>';
		print_r( $tweet );
		echo '</pre>';
		exit;
		*/

	}
	
	/**
	 * Scheduler default date time.
	 *
	 * @since  1.0.0
	 * @return datetime $timezone_date_time
	 */
    public static function scheduler_date_time() 
	{
		$scheduler_settings    = get_option('ataswp_scheduler_settings');
		$gmt_or_local_timezone = isset( $scheduler_settings['gmt_or_local_timezone'] ) ? sanitize_text_field( $scheduler_settings['gmt_or_local_timezone'] ) : '';
		
		$timezone_date_time = ATASWP_Helper::wp_universal_GMT_date_time(); // def
		
		if ($gmt_or_local_timezone == 'local' ) { 
		   $timezone_date_time = ATASWP_Helper::wp_local_site_date_time();
		} elseif ($gmt_or_local_timezone == 'gmt' ) {
		   $timezone_date_time = ATASWP_Helper::wp_universal_GMT_date_time();
		}
		
		return $timezone_date_time;
		
	}

	/**
	 * WP Cron test.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function wp_cron_test() 
	{
		$date_time = ATASWP_Helper::wp_local_site_date_time();
		$datetime_str = strtotime($date_time);
		$date         = date('Y-m-d', $datetime_str);
		$time         = date("H:i", $datetime_str);
		$hour         = date("H", $datetime_str);
		$time_zone_minutes       = date("i", $datetime_str);
		
		$currentdatetime = date("Y-m-d H:i:s");
		
		$GMT = ATASWP_Helper::wp_universal_GMT_date_time();
		
		$data = 'Auto Publish Cron Run at: ' . $date_time . ' Current Date Time: ' . $currentdatetime  . ' GMT: ' . $GMT . PHP_EOL;
		$filepath = ATASWP_DIR . '_ataswp-auto-publish-cron-test-log.txt';
		$ataswp_scheduler_log = file_put_contents($filepath, $data , FILE_APPEND);
		
	}
	
	/**
	 * Next Sheduled date based on interval. e.g. Monday
	 *
	 * @since  1.0.0
	 * @param string $interval
	 * @return date $next_date
	 */
    public static function next_scheduled_date( $interval ) 
	{
        if ( empty( $interval ) )
		return;
		
		$datetime = ATASWP_Scheduler::scheduler_date_time(); // time zone date time
		
		$nameOfheDay = date('l', strtotime($datetime)); // name of the day e.g. Saturday

			// remove time from datetime
			$date = date('Y-m-d', strtotime($datetime)); // remove time
			$time = date('H:i:s', strtotime($datetime)); // remove date
			// find next date
			$next_date  = date('Y-m-d', strtotime('next ' . $interval, strtotime($date))); // create next date
			// add time to date
			//$next_date_time  = $next_date . ' ' . $time;
		
		return $next_date;
		
	}
	
	/**
	 * Remove page process Ajax.
	 *
	 * @since  1.0.0
	 * @return string $total
	 */
    public function remove_page() 
	{
		
		// get form data
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		
		$post_id  = isset( $postdata['post_id'] ) ? sanitize_text_field( $postdata['post_id'] ) : '';
		
        if ( empty( $post_id ) )
		return;
	
		delete_post_meta($post_id,'_ataswp_scheduler');
		delete_post_meta($post_id,'_ataswp_scheduler_counter');
		delete_post_meta($post_id,'_ataswp_scheduler_intervals');
		delete_post_meta($post_id,'_ataswp_scheduler_times');
		delete_post_meta($post_id,'_ataswp_scheduler_start_date');
		delete_post_meta($post_id,'_ataswp_scheduler_end_date');
		
		// custom post titles
		delete_post_meta($post_id,'_ataswp_scheduler_enable_custom_post_titles');
		delete_post_meta($post_id,'_ataswp_scheduler_custom_post_titles');
		
	    #### important! #############
	    exit; // don't forget to exit!
		
	}
					
}

?>