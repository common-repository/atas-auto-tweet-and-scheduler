<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Scheduler_Settings
{
	
	/**
	 * Scheduler settings page Ajax process.
	 *
	 * @since  1.0.0
	 * @return 
	 */
    public function scheduler_settings() 
	{
		// get form data
		$formData = $_POST['formData'];
		
		// store validation results in array
		$validation = array();
		
		if ( empty( $formData ) )
		return;
		
		// parse string
		parse_str($formData, $postdata);
		
		// Add nonce for security and authentication.
		$nonce_action = 'ataswp_scheduler_settings_form_nonce';
       
		// Check if a nonce is set.
		if ( ! isset( $postdata['ataswp-scheduler-settings-form-nonce'] ) )
			return;
			
		// Check if a nonce is valid.
		if ( wp_verify_nonce( $postdata['ataswp-scheduler-settings-form-nonce'], $nonce_action ) ) {
			
			$enable_scheduler   = isset( $postdata['enable_scheduler'] ) ? sanitize_text_field( $postdata['enable_scheduler'] ) : '';
			$reset_scheduler    = isset( $postdata['reset_scheduler'] ) ? sanitize_text_field( $postdata['reset_scheduler'] ) : '';
			$gmt_or_local_timezone = isset( $postdata['gmt_or_local_timezone'] ) ? sanitize_text_field( $postdata['gmt_or_local_timezone'] ) : 'gmt'; // radio
			
			// this will delete all the schedules
			ATASWP_Scheduler_Settings::reset_scheduler( $reset_scheduler );
	
			$scheduler_settings    = get_option('ataswp_scheduler_settings');
			$version = $scheduler_settings['version'];
			if( trim($version) == false ) $version = '';
	
			$arr = array(
				'version'                => $version,
				'enable_scheduler'       => $enable_scheduler,
				'gmt_or_local_timezone'  => $gmt_or_local_timezone // Universal or Local Timezone
			);
	
			update_option('ataswp_scheduler_settings', $arr);
			
			// success message
			$validation[] = __('Settings has been updated. ', 'ataswp_lang');
			// validation
			echo ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='success');
		
		} else {
			// error message
			$validation[] = __('Form Validation failed!', 'ataswp_lang');
			// validation
			echo ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='error');
		}
		
		//echo json_encode(array('success'=>'', 'message'=>'' )); // return json	
		
        exit; // don't forget to exit!	
		
	}
	
	/**
	 * Reset scheduler will delete all the schedules.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public static function reset_scheduler( $reset_scheduler ) 
	{
		if ( empty( $reset_scheduler ) )
		return;
		
		if ( $reset_scheduler == '1' ) {
			// delete all schedules
			global $wpdb, $post, $wp_query;
			
			$meta_query_args = array(
				'post_type' =>  'any', // for fetching from all post types set (any)
				//'post_status'      => 'publish',
				'relation' => 'AND', // Optional, OR defaults to "AND"
				'order'     => 'ASC',
				'posts_per_page' => -1, // -1
				'paged'          => '',
				'meta_query' => array(
								array('key' => '_ataswp_scheduler', // where key = value
									  'value' => '1',
									  'compare' => '='
								)
								
			));
			
			$get_results = new WP_Query( $meta_query_args );
			//$posts_count = $get_results->found_posts; // get count
			
			if ( $get_results ) {
				foreach($get_results->posts as $post ) {
					$post_id = $post->ID;
					
					// delete metas
					delete_post_meta($post_id,'_ataswp_scheduler');
					delete_post_meta($post_id,'_ataswp_scheduler_counter');
					delete_post_meta($post_id,'_ataswp_scheduler_intervals');
					delete_post_meta($post_id,'_ataswp_scheduler_times');
					delete_post_meta($post_id,'_ataswp_scheduler_start_date');
					delete_post_meta($post_id,'_ataswp_scheduler_end_date');
					
					// custom post titles
					delete_post_meta($post_id,'_ataswp_scheduler_enable_custom_post_titles');
					delete_post_meta($post_id,'_ataswp_scheduler_custom_post_titles');
					
				}
			}
			
		}
		
	}
						
}

?>