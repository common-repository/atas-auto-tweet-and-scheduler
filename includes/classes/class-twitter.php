<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// #### Required #### Library that handles Twitter API
require_once( ATASWP_DIR . 'includes/libraries/twitteroauth/autoload.php' );
use Abraham\TwitterOAuth\TwitterOAuth;

// Source: https://twitteroauth.com/

class ATASWP_Twitter
{

	/**
	 * Twitter connection.
	 *
	 * @since  1.0.0
	 * @return void $connection
	 */
    public static function connect() {
		$connection = ''; // def
		$error = ''; // def
		
		$oauth_config = ATASWP_Twitter_Account::get_oauth_config(); 
		
		if ( !empty($oauth_config['consumer_key']) && !empty($oauth_config['consumer_secret']) && !empty($oauth_config['oauth_token']) && !empty($oauth_config['oauth_token_secret']) ) {
		
			$connection = new TwitterOAuth($oauth_config['consumer_key'], 
										   $oauth_config['consumer_secret'], 
										   $oauth_config['oauth_token'],
										   $oauth_config['oauth_token_secret']);
			
		} else {
			// missing required API keys
			$error = __('Missing required Twitter API keys.', 'ataswp_lang');
			
			$data = $error . ' ' . PHP_EOL;
			$filepath = ATASWP_DIR . '_ataswp-error-log.txt';
			$auto_publish_log = file_put_contents($filepath, $data , FILE_APPEND);
			
		}
		
		return $connection;
	}
	
	/**
	 * Twitter Tweet.
	 *
	 * @since  1.0.0
	 * @param object $tweet
	 * @return void  $response
	 */
    public static function tweet( $tweet ) {
		
		if ( empty( $tweet ) )
		return;
		
		global $wpdb;
		
		$response = ''; // def
		
		$tweet   = json_decode($tweet, true); // convert to array
		//$tweet_obj = json_decode($tweet); // convert to object
		
		$tweet_data = array(
			"title" 		=> $tweet['title'],
			"description"	=> $tweet['description'],
			"url"		    => $tweet['url'],
			"author"		=> $tweet['author']
		);

		$connection = ATASWP_Twitter::connect();
		
		if ( $connection ) {
			
			// process tweet
			#### Twitter Cards work by pulling metadata information straight from a posted link.
			#### You don't need <a /> tag to post link to Twitter.
			
			$twitter_tweet = $tweet['title'] . ' ' . $tweet['url'];
			
			$response = $connection->post('statuses/update', array('status' => $twitter_tweet));
			
		}
		
		return $response;
		
	}
	
	/**
	 * Twitter Tweet.
	 *
	 * @since  1.0.0
	 * @param string $tweet
	 * @return void  $response
	 */
    public static function text_tweet( $tweet ) {
		
		if ( empty( $tweet ) )
		return;
		
		global $wpdb;
		
		$response = ''; // def

		$connection = ATASWP_Twitter::connect();
		
		if ( $connection ) {
			
			// process tweet
			#### Twitter Cards work by pulling metadata information straight from a posted link.
			#### You don't need <a /> tag to post link to Twitter.
			
			$response = $connection->post('statuses/update', array('status' => $tweet));
			
		}
		
		return $response;
		
	}
	
	/**
	 * Twitter verify credentials.
	 *
	 * @since  1.0.0
	 * @return object $user
	 */
	public static function verify_credentials() {	
		
		global $wpdb;
		
		$connection = ATASWP_Twitter::connect();
		
		$user = ''; // def
		if ( $connection ) {
			
			// you can now call all the methods on the twitteroauth/connection object
			$user = $connection->get('account/verify_credentials');
		
		}
		return $user;
	   
	}
	
	/**
	 * Twitter get configuration.
	 *
	 * @since  1.0.0
	 * @return object $configuration
	 */
	public static function get_configuration() {	
		
		$connection = ATASWP_Twitter::connect();
		
		$configuration = ''; // def
		if ( $connection ) {
			
			// GET https://api.twitter.com/1.1/help/configuration.json
			$configuration = $connection->get("help/configuration", []);
		
		}
		return $configuration;
	   
	}
	
	
}

?>