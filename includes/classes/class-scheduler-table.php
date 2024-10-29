<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class ATASWP_Scheduler_Table
{

	/**
	 * Scheduler table custom post titles.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return 
	 */
    public static function custom_post_titles( $post_id ) 
	{
		if ( empty( $post_id ) )
		return;
		
		$custom_post_titles_filter = ''; // def
		$global_filters = ATASWP_Admin_Core::global_filters();
		if ( isset($global_filters['pro_version']['custom_post_titles_filter']) && $global_filters['pro_version']['custom_post_titles_filter'] == '1' ) {
			$custom_post_titles_filter = $global_filters['pro_version']['custom_post_titles_filter'];
		}
		
		$enable_custom_post_titles = get_post_meta($post_id,'_ataswp_scheduler_enable_custom_post_titles', true); 
		
		$custom_post_titles     = get_post_meta($post_id,'_ataswp_scheduler_custom_post_titles', true); // json obj
		$custom_post_titles_arr = json_decode($custom_post_titles, true); // convert to array
		
		if ( $enable_custom_post_titles != '1' ) {
			$enable_custom_post_titles = ''; // default
			$display_custom_post_titles_fields = '';
		} else {
			$display_custom_post_titles_fields = 'style="display:block;"';
		}
		
		?>
        <div class="checkbox margin-top-10 margin-bottom-5">   
        <input class="ataswp-scheduler-custom-post-titles-checkbox enable-custom-post-titles-<?php echo esc_attr( $post_id ); ?>" type="checkbox" value="<?php echo esc_attr( $enable_custom_post_titles ); ?>" <?php echo ($enable_custom_post_titles == '1') ? 'checked' : ''; ?>  id="<?php echo esc_attr( $post_id ); ?>" name="enable_custom_post_titles_<?php echo esc_attr( $post_id ); ?>" />
        <span style="font-weight:bold;"><?php _e("Custom Post Titles", 'ataswp_lang'); ?> </span>
        <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Add unlimited custom post titles, as well you can add hashtags in the titles. If enabled 'custom post titles' will be used randomly instead of the post title.", 'ataswp_lang'); ?>"></span>
        </div> 
        
        <div class="display-custom-post-titles-fields" id="display-custom-post-titles-fields-<?php echo esc_attr( $post_id ); ?>"  <?php echo $display_custom_post_titles_fields; ?>>
        
            <a class="ataswp-add-custom-post-title btn btn-sm btn-wp-blue margin-top-5 margin-bottom-5" id="<?php echo esc_attr( $post_id ); ?>">
            <i class="glyphicon glyphicon-plus-sign"></i>&nbsp; 
            <?php _e( 'Add', 'ataswp_lang' ); ?>
            </a>
        
			  <?php 
              if ( $custom_post_titles_filter != '1'  ) {
                 // upgrade to PRO 
              ?>
                <div class="checkbox margin-top-15 margin-bottom-5">
                <span class="ataswp-upgrade-to-pro-text">
                <?php _e( 'Custom post titles are available in the ', 'ataswp_lang' ); ?>  
                <a href="https://codeweby.com/products/atas-auto-tweet-and-scheduler-pro/" target="_blank"><strong><?php _e( 'PRO version', 'ataswp_lang' ); ?></strong></a>.
                </span>
                </div>
                
                <div class="display-block margin-top-5 margin-bottom-5">
                <input style="width:90%;" maxlength="140" class="example_custom_post_title_class inputfield" name="example_custom_post_titles" type="text" value="">
                <a class="example-remove-custom-post-title padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
                </div>
                
              <?php 
              }
              ?>
            
            <div id="ataswp-scheduler-custom-post-titles-fields-<?php echo esc_attr( $post_id ); ?>"></div><!-- jQuery insert time after this --> 
            
            <?php 
            if ( !empty($custom_post_titles_arr) ) {
                foreach( $custom_post_titles_arr as $custom_post_title ) {
                    ?>
                    
                        <div class="ataswp-custom-post-title display-block margin-top-5 margin-bottom-5">
                        <input style="width:90%;" maxlength="140" class="custom_post_title_class inputfield" name="custom_post_titles_<?php echo esc_attr( $post_id ); ?>[]" type="text" value="<?php echo esc_attr( $custom_post_title ); ?>">
                        <a class="ataswp-remove-custom-post-title padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
                        </div>
                    
                    <?php 
                }
            }
            ?>
        </div>
        
        
		<?php 
		
	}
	
	/**
	 * Scheduler update post.
	 *
	 * @since  1.0.0
	 * @return 
	 */
    public function scheduler_update_post() 
	{
		
		// store validation results in array
		$validation = array();
		
		// get form data
		$formData = $_POST['formData'];
		$post_id = isset( $_POST['postID'] ) ? sanitize_text_field( $_POST['postID'] ) : '';
		
		if ( empty( $post_id ) && empty( $formData ) )
		return;
		
		// parse string
		parse_str($formData, $postdata);
		
		// Add nonce for security and authentication.
		$nonce_action = 'ataswp_scheduler_form_nonce';
			
		// Check if a nonce is valid.
		if ( wp_verify_nonce( $postdata['ataswp-scheduler-form-nonce'], $nonce_action ) ) {
			
			$intervals          = isset( $postdata['intervals_' . $post_id])?array_map('sanitize_text_field',$postdata['intervals_' . $post_id]):array();
			$start_date         = isset( $postdata['start_date_' . $post_id] ) ? sanitize_text_field( $postdata['start_date_' . $post_id] ) : '';
			$end_date           = isset( $postdata['end_date_' . $post_id] ) ? sanitize_text_field( $postdata['end_date_' . $post_id] ) : '';
			$hours              = isset( $postdata['hours_' . $post_id])?array_map('sanitize_text_field',$postdata['hours_' . $post_id]):array();
			$minutes            = isset( $postdata['minutes_' . $post_id])?array_map('sanitize_text_field',$postdata['minutes_' . $post_id]):array();
			$scheduler_counter  = isset( $postdata['scheduler_counter_' . $post_id] ) ? sanitize_text_field( $postdata['scheduler_counter_' . $post_id] ) : '';
			
			$enable_custom_post_titles = isset( $postdata['enable_custom_post_titles_' . $post_id] ) ? sanitize_text_field( $postdata['enable_custom_post_titles_' . $post_id] ) : '';
			$custom_post_titles = isset( $postdata['custom_post_titles_' . $post_id])?array_map('sanitize_text_field',$postdata['custom_post_titles_' . $post_id]):array();
			
			if ( empty( $intervals ) ) {
				
				// error message
				$validation[] = __('Please select at least one interval.', 'ataswp_lang');
				// validation
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='info');
				echo json_encode(array('success'=>false, 'message'=>$print ));
				
			} elseif ( empty( $hours ) ) {

				// error message
				$validation[] = __('Please add time.', 'ataswp_lang');
				// validation
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='info');
				echo json_encode(array('success'=>false, 'message'=>$print ));

			} else {
			
				$times = ''; // def
				// create times array
				$index = 0; // default
				foreach($hours as $hour) {
					
				  if ( ! empty( $hours[$index] ) && ! empty( $minutes[$index] ) ) {
					  
					  $time = $hours[$index] . ':' . $minutes[$index];
	
					  $times[] = $time;
				  
				  }
				  
				  $index++; // should be at the end of the loop
				}
				
				$data = array(
					"post_id"	                 => $post_id,
					"scheduler_counter"	         => $scheduler_counter,
					"scheduler_intervals"		 => $intervals, // array		  
					"scheduler_start_date"	     => $start_date,
					"scheduler_end_date"	     => $end_date,
					"scheduler_times"	         => $times, // array
					"enable_custom_post_titles"	 => $enable_custom_post_titles,
					"custom_post_titles"	     => $custom_post_titles
				);
				
				/*
				echo '<pre>';
				print_r( $data );
				echo '</pre>';
				exit;
				*/
				
				$intervals = json_encode($data['scheduler_intervals']); // json encode before save
				$times     = json_encode($data['scheduler_times']); // json encode before save
				
				update_post_meta($post_id,'_ataswp_scheduler_counter', $scheduler_counter);
				update_post_meta($post_id,'_ataswp_scheduler_intervals', $intervals);
				update_post_meta($post_id,'_ataswp_scheduler_start_date', $data['scheduler_start_date']);
				update_post_meta($post_id,'_ataswp_scheduler_end_date', $data['scheduler_end_date']);			
				update_post_meta($post_id,'_ataswp_scheduler_times', $times);
	
				$data = json_encode($data); // json encode before send
				// update custom post titles
				do_action( 'ataswp_scheduler_update_post', $data ); // <- extensible 
				
				// success message
				$validation[] = __('Settings has been updated.', 'ataswp_lang');
				// validation
				$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='success');
				echo json_encode(array('success'=>true, 'message'=>$print ));
			}
			
		
		} else {
			// error message
			$validation[] = __('Form Validation failed!', 'ataswp_lang');
			// validation
			$print = ATASWP_Admin_Core::displayAjaxFormsValidationResult($validation, $type='error');
			echo json_encode(array('success'=>false, 'message'=>$print ));
		}

		
        exit; // don't forget to exit!	
		
	}
	
	
						
}

?>