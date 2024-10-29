<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// #### Required #### Library that handles Twitter API
require_once( ATASWP_DIR . 'includes/libraries/twitteroauth/autoload.php' );
use Abraham\TwitterOAuth\TwitterOAuth;

// Twitter user authentication 
// Source: https://code.tutsplus.com/tutorials/how-to-authenticate-users-with-twitter-oauth-20--cms-25713
// https://github.com/tutsplus/tuts-twitter-oauth/blob/master/twitter_callback.php

class ATASWP_Twitter_Account
{
	
	/**
	 * Codeweby Twitter config.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_oauth_config() {	
	    
		// get admin url: https://example.com/wp-admin/
		$login = get_admin_url() . 'admin.php?page=ataswp&main=accounts';
		// should call home_url()
	    $url_callback    =  home_url() . '/?ataswp_twitter_app=twitter_app_callback'; // change to codeweby main
		//$call_back_url =  'https://codeweby.com/?ataswp_twitter_app=twitter_app_callback'; // codeweby
		
		$app_single_account   = get_option('ataswp_twitter_app_single_account');
		// user auth
		$screen_name  = isset( $app_single_account['twitter_user_screen_name'] ) ? sanitize_text_field( $app_single_account['twitter_user_screen_name'] ) : '';
		$access_token  = isset( $app_single_account['twitter_access_token'] ) ? sanitize_text_field( $app_single_account['twitter_access_token'] ) : '';
		$access_token_secret  = isset( $app_single_account['twitter_access_token_secret'] ) ? sanitize_text_field( $app_single_account['twitter_access_token_secret'] ) : '';
		// twitter app name: ATAS Auto Tweet Scheduler
		return [
			'consumer_key'       => trim('CNghOk2YlXU64sBbwvggJz6Kw'),
			'consumer_secret'    => trim('9zxLga85zqKXc80zVVB5LGNgLImuzpQu604w6J7rlnFVgLEnEW'),
			'screen_name'        => $screen_name,
			'oauth_token'        => trim($access_token),
			'oauth_token_secret' => trim($access_token_secret),
			'url_login'          => $login,
			'url_callback'       => $url_callback,
		];
	}
	
	/**
	 * User authentication 
	 *
	 * @since  1.0.0
	 * @return object data
	 */
	public function twitter_app_connect_account() {
		
		/* Start session and load lib */
		//session_start();

		// get form data
		//$formData = $_POST['formData']; // array
		$formData  = isset( $_POST['formData'] ) ? $_POST['formData'] : ''; // array
		
		// store validation results in array
		$validation = array();
		
		if ( empty( $formData ) )
		return;
		
		// parse string
		parse_str($formData, $postdata);
		
		// Add nonce for security and authentication.
		$nonce_action = 'ataswp_twitter_connect_account_form_nonce';
       
		// Check if a nonce is set.
		if ( ! isset( $postdata['ataswp-twitter-connect-account-form-nonce'] ) )
			return;
		
		$authorization_url = ''; // def
		
		// Check if a nonce is valid.
		if ( wp_verify_nonce( $postdata['ataswp-twitter-connect-account-form-nonce'], $nonce_action ) ) {
		
			$config  = ATASWP_Twitter_Account::get_oauth_config();
			$url_login    = $config['url_login'];
			$url_callback = $config['url_callback'];
			
			// create TwitterOAuth object
			$twitteroauth = new TwitterOAuth($config['consumer_key'], $config['consumer_secret']);
			 
			// request token of application
			$request_token = $twitteroauth->oauth(
				'oauth/request_token', [
					'oauth_callback' => $url_callback // $config['url_callback'] , 'oob'
				]
			);
	
			// throw exception if something gone wrong
			if($twitteroauth->getLastHttpCode() != 200) {
				//throw new \Exception('There was a problem performing this request');
				$print = 'There was a problem performing this request'; 
				echo json_encode(array('success'=>false, 'message'=>$print, 'authorization_url'=>$authorization_url ));
			}
			 
			// save token of application to session
			$_SESSION['oauth_token'] = $request_token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			 
			// generate the URL to make request to authorize our application
			$authorization_url = $twitteroauth->url(
				'oauth/authorize', [
					'oauth_token' => $request_token['oauth_token']
				]
			);
			
			// return json
			$print = 'ok'; 
			echo json_encode(array('success'=>true, 'message'=>$print, 'authorization_url'=>$authorization_url ));
			//return $authorization_url;
		
		} else {
			// error message
			$validation[] = __('Form Validation failed!', 'ataswp_lang');
			// validation
			$print = Post2Social_Admin::displayAjaxFormsValidationResult($validation, $type='error');
			echo json_encode(array('success'=>false, 'message'=>$print, 'authorization_url'=>$authorization_url ));
		}
		
		//echo json_encode(array('success'=>'', 'message'=>'' )); // return json	
		
        exit; // don't forget to exit!	
		
	}
	
	/**
	 * Disconnect account.
	 *
	 * @since  1.0.0
	 * @return object data
	 */
	public function twitter_app_disconnect_account() {

		// get form data
		//$formData = $_POST['formData']; // array
		$formData  = isset( $_POST['formData'] ) ? $_POST['formData'] : ''; // array
		
		// store validation results in array
		$validation = array();
		
		if ( empty( $formData ) )
		return;
		
		// parse string
		parse_str($formData, $postdata);
		
		// Add nonce for security and authentication.
		$nonce_action = 'ataswp_twitter_disconnect_account_form_nonce';
       
		// Check if a nonce is set.
		if ( ! isset( $postdata['ataswp-twitter-disconnect-account-form-nonce'] ) )
			return;
		
		$authorization_url = ''; // def
		
		// Check if a nonce is valid.
		if ( wp_verify_nonce( $postdata['ataswp-twitter-disconnect-account-form-nonce'], $nonce_action ) ) {
		
			$config  = ATASWP_Twitter_Account::get_oauth_config();
			$url_login    = $config['url_login'];
			$url_callback = $config['url_callback'];
			
			$app_single_account   = get_option('ataswp_twitter_app_single_account');
			$version = $app_single_account['version'];
			if( trim($version) == false ) $version = '';
			// save data to database
			$arr = array(
				'version'                     => $version,
				'twitter_user_name'           => '',
				'twitter_user_screen_name'    => '',
				'profile_image_url_https'     => '',
				'twitter_access_token'        => '',
				'twitter_access_token_secret' => ''
			);
	
			update_option('ataswp_twitter_app_single_account', $arr);
			
			// return json
			$print = 'ok'; 
			echo json_encode(array('success'=>true, 'message'=>$print ));
			//return $authorization_url;
		
		} else {
			// error message
			$validation[] = __('Form Validation failed!', 'ataswp_lang');
			// validation
			$print = Post2Social_Admin::displayAjaxFormsValidationResult($validation, $type='error');
			echo json_encode(array('success'=>false, 'message'=>$print ));
		}
		
		//echo json_encode(array('success'=>'', 'message'=>'' )); // return json	
		
        exit; // don't forget to exit!	
		
	}
	
	/**
	 * Twitter user authentication callback (virtual page) use init
	 * https://codeweby.com/?ataswp_twitter_app=twitter_app_callback
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function twitter_app_callback() {
		
		if ( isset( $_REQUEST['ataswp_twitter_app'] ) && $_REQUEST['ataswp_twitter_app'] == "twitter_app_callback") {

			$config  = ATASWP_Twitter_Account::get_oauth_config();
			
			// session_start();
			// source: https://stackoverflow.com/questions/9319546/authenticate-with-twitter-oauth-api
			if(isset($_REQUEST['oauth_verifier'])){

				$oauth_access_token        = $_REQUEST['oauth_token'];
				$oauth_access_token_secret = $_REQUEST['oauth_verifier'];
				$consumer_key              = $config['consumer_key'];
				$consumer_secret           = $config['consumer_secret'];
				$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);
				
				// request user token
				$access_token = $connection->oauth(
					'oauth/access_token', [
						'oauth_verifier' => $_REQUEST['oauth_verifier']
					]
				);
				
				// connect with user token
				$twitter = new TwitterOAuth(
					$config['consumer_key'],
					$config['consumer_secret'],
					$access_token['oauth_token'],
					$access_token['oauth_token_secret']
				);
				
				$user = $twitter->get('account/verify_credentials');
				
				$name = $user->name;
				$screen_name = $user->screen_name;
				$profile_image_url = $user->profile_image_url_https;
				
				$twitter_user_data = array(
					'twitter_user_name'           => $name,
					'twitter_user_screen_name'    => $screen_name,
					'profile_image_url_https'     => $profile_image_url,
					'twitter_access_token'        => $access_token['oauth_token'],
					'twitter_access_token_secret' => $access_token['oauth_token_secret']
				);
				
				$app_single_account   = get_option('ataswp_twitter_app_single_account');
				$version = $app_single_account['version'];
				if( trim($version) == false ) $version = '';
				// save data to database
				$arr = array(
					'version'                     => $version,
					'twitter_user_name'           => $name,
					'twitter_user_screen_name'    => $screen_name,
					'profile_image_url_https'     => $profile_image_url,
					'twitter_access_token'        => $access_token['oauth_token'],
					'twitter_access_token_secret' => $access_token['oauth_token_secret']
				);
		
				update_option('ataswp_twitter_app_single_account', $arr);
				
				/*
				// post a tweet
				$status = $twitter->post(
					"statuses/update", [
						"status" => "Thank you now I know how to authenticate users with Twitter."
					]
				);
				
				echo ('Created new status with #' . $status->id . PHP_EOL);
				//print_r($status);
				
				
				echo '<pre>';
				print_r( $twitter ); // screen_name
				echo '</pre>';
				*/
			
				//$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_access_token_secret)); // response array
				//var_dump($access_token); die("--success here--");// Obtain tokens and save it in database for further use.
				
				/*
				// response is in that array
				Array
				(
					[oauth_token] => XXXXXXXXXXXXXXXX
					[oauth_token_secret] => XXXXXXXXXXXXXXXX
					[user_id] => XXXXXXXXXXXXXXXX
					[screen_name] => screenname
					[x_auth_expires] => 0
				)
				*/
				
				// Reply with an empty 200 response to indicate received correctly.
				//header("HTTP/1.1 200 OK");
				$url_login = $config['url_login'];
				header('Location: ' . $url_login);
				
			} else {
				// Any value other than 200 indicates a failure. (http bad request 404)
				http_response_code(404);
				$message = 'Invalid Access!';
				die($message);
			}
			
			exit;
		}
	}
	
	
}

?>