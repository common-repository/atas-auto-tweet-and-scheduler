<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Auto_Post
{
	
	/**
	 * Process auto post metabox tweet
	 * Using The do action: "ataswp_process_twitter_auto_posting" 
	 *
	 * @since 1.0.0
	 * @param object $twitter_data
	 * @return void
	 */
	public function process_auto_post_metabox_tweet( $twitter_data ) 
	{	
		if ( empty( $twitter_data ) )
		return;
		
		$data_arr   = json_decode($twitter_data, true); // convert to array
		//$data_obj = json_decode($twitter_data); // convert to object
		
		$twitterdata = array(
			'post_id'              => $data_arr['post_id'],
			'auto_post'            => $data_arr['auto_post'] // 1 or empty 
		);
		
		$post_id = $data_arr['post_id'];
		
		$title       = ATASWP_Twitter_Tweet::get_post_title( $post_id );
		$description = ATASWP_Twitter_Tweet::create_post_description( $post_id );
		$url         = ATASWP_Twitter_Tweet::get_post_url( $post_id );
		$author      = ATASWP_Twitter_Tweet::get_post_author( $post_id );
		
		// replace words with defined hashtags
		$title       = ATASWP_Twitter_Tweet::words_to_hashtags( $title );
		$description = ATASWP_Twitter_Tweet::words_to_hashtags( $description );
		
		$tweet = array(
			"title" 		=> $title,
			"description"	=> $description,
			"url"		    => $url,
			"author"		=> '@'.$author
		);
		
		$tweet = json_encode($tweet); // json encode before send
		
		$response = ATASWP_Twitter::tweet( $tweet ); // process tweet
		
		if ( !empty($response) ) {
			// success, show admin notice using transient
			set_transient( 'ataswp_auto_post_metabox_tweet_result', true, 180 ); // for 180 seconds
		}
		
		return $response;
		
		/*
		echo '<pre>';
		print_r( $tweet );
		echo '</pre>';
		
		echo '<pre>';
		print_r( $twitterdata );
		echo '</pre>';
		exit;
		*/

	}
	
	/**
	 * Display admin notice upon on tweet.
	 *
	 * @since      1.0.0
     * @return     void $notice
	 */
	public function auto_post_metabox_tweet_notice()
	{ 
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	
	    $notice = ''; // def
		// If transient exist
		if ( get_transient( 'ataswp_auto_post_metabox_tweet_result' ) ) {
			
		  $tweet_button = '<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://codeweby.com" data-text="ATAS #WordPress #Twitter #scheduler can help you to earn more followers and drive more traffic to your website." data-via="codeweby">Tweet</a>';
			
		  $notice = '<div class="updated notice notice-success is-dismissible" id="message"><p>' 
		  . __('Tweet successfully posted.', 'ataswp_lang') . '</p>' 
		  . '<p>' . __('Share your Love by Tweeting to us: &nbsp; &nbsp; ', 'ataswp_lang') . $tweet_button . '</p>'  
		  . '</div>';
			// delete transient
			delete_transient( 'ataswp_auto_post_metabox_tweet_result' );	
			
			?>
            
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            
            <?php
		} 
		echo $notice; // use echo instead of return!!!
		
	}
	
	/**
	 * Auto post settings page Ajax process.
	 *
	 * @since  1.0.0
	 * @return 
	 */
    public function auto_post_settings() 
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
		$nonce_action = 'ataswp_auto_post_settings_form_nonce';
       
		// Check if a nonce is set.
		if ( ! isset( $postdata['ataswp-auto-post-settings-form-nonce'] ) )
			return;
			
		// Check if a nonce is valid.
		if ( wp_verify_nonce( $postdata['ataswp-auto-post-settings-form-nonce'], $nonce_action ) ) {
			
			$enable_auto_post   = isset( $postdata['enable_auto_post'] ) ? sanitize_text_field( $postdata['enable_auto_post'] ) : '';
			$words_into_hashtags = isset( $postdata['words_into_hashtags'] ) ? sanitize_text_field( $postdata['words_into_hashtags'] ) : 'gmt'; // radio
			
			// Checkboxes
			if( isset( $postdata['enabled_post_types'] ) ) {
				$enabled_post_types = $postdata['enabled_post_types']; // post types
				$enabled_post_types = json_encode($enabled_post_types); // encode to json before save
				/*
				echo '<pre>';
				print_r( $enabled_post_types );
				echo '</pre>';
				exit;
				*/
				
			} else {
				$enabled_post_types = '';
			}
	
			$auto_post_settings    = get_option('ataswp_auto_post_settings');
			$version = $auto_post_settings['version'];
			if( trim($version) == false ) $version = '';
	
			$arr = array(
				'version'              => $version,
				'enable_auto_post'     => $enable_auto_post,
				'enabled_post_types'   => $enabled_post_types,
				'words_into_hashtags'  => $words_into_hashtags // Universal or Local Timezone
			);
	
			update_option('ataswp_auto_post_settings', $arr);
			
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
	
}

?>