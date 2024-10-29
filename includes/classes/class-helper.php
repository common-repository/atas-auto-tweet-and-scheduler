<?php
 
/**
 * helper class.
 *
 * @package     Post2Social
 * @subpackage  public/
 * @copyright   Copyright (c) 2017, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class ATASWP_Helper {
	
	/**
	 * Shorten text.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param  string $text
	 * @param  int    $limit
	 * @return string $text
	 */
	public static function shorten_text($text, $limit) {
		  if (str_word_count($text, 0) > $limit) {
			  $words = str_word_count($text, 2);
			  $pos = array_keys($words);
			  $text = substr($text, 0, $pos[$limit]) . '...';
		  }
		  return $text;
	}
	
	/**
	 * interval.
	 *
	 * @since 1.0.0
	 * @return array $interval
	 */
	public static function wp_cron_intervals() {
		$interval = array(
			  'Monday'          => __( 'Monday', 'ataswp_lang' ),
			  'Tuesday'         => __( 'Tuesday', 'ataswp_lang' ),
			  'Wednesday'       => __( 'Wednesday', 'ataswp_lang' ),
			  'Thursday'        => __( 'Thursday', 'ataswp_lang' ),
			  'Friday'          => __( 'Friday', 'ataswp_lang' ),
			  'Saturday'        => __( 'Saturday', 'ataswp_lang' ),
			  'Sunday'          => __( 'Sunday', 'ataswp_lang' )
		); 
		return $interval;
	}
	
	/**
	 * Hours.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function wp_cron_option_hours() {
		$hours = '';
		$hours .= '<option value="' . esc_attr( '00' ) . '">' . esc_attr( '00' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '01' ) . '">' . esc_attr( '01' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '02' ) . '">' . esc_attr( '02' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '03' ) . '">' . esc_attr( '03' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '04' ) . '">' . esc_attr( '04' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '05' ) . '">' . esc_attr( '05' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '06' ) . '">' . esc_attr( '06' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '07' ) . '">' . esc_attr( '07' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '08' ) . '">' . esc_attr( '08' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '09' ) . '">' . esc_attr( '09' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '10' ) . '">' . esc_attr( '10' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '11' ) . '">' . esc_attr( '11' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '12' ) . '">' . esc_attr( '12' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '13' ) . '">' . esc_attr( '13' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '14' ) . '">' . esc_attr( '14' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '15' ) . '">' . esc_attr( '15' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '16' ) . '">' . esc_attr( '16' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '17' ) . '">' . esc_attr( '17' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '18' ) . '">' . esc_attr( '18' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '19' ) . '">' . esc_attr( '19' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '20' ) . '">' . esc_attr( '20' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '21' ) . '">' . esc_attr( '21' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '22' ) . '">' . esc_attr( '22' ) . '</option>'; 
		$hours .= '<option value="' . esc_attr( '23' ) . '">' . esc_attr( '23' ) . '</option>'; 
		echo $hours;
	}
	
	/**
	 * Minutes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function wp_cron_option_minutes() {
		$minutes = '';
		$minutes .= '<option value="' . esc_attr( '00' ) . '">' . esc_attr( '00' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '01' ) . '">' . esc_attr( '01' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '02' ) . '">' . esc_attr( '02' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '03' ) . '">' . esc_attr( '03' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '04' ) . '">' . esc_attr( '04' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '05' ) . '">' . esc_attr( '05' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '06' ) . '">' . esc_attr( '06' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '07' ) . '">' . esc_attr( '07' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '08' ) . '">' . esc_attr( '08' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '09' ) . '">' . esc_attr( '09' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '10' ) . '">' . esc_attr( '10' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '11' ) . '">' . esc_attr( '11' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '12' ) . '">' . esc_attr( '12' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '13' ) . '">' . esc_attr( '13' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '14' ) . '">' . esc_attr( '14' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '15' ) . '">' . esc_attr( '15' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '16' ) . '">' . esc_attr( '16' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '17' ) . '">' . esc_attr( '17' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '18' ) . '">' . esc_attr( '18' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '19' ) . '">' . esc_attr( '19' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '20' ) . '">' . esc_attr( '20' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '21' ) . '">' . esc_attr( '21' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '22' ) . '">' . esc_attr( '22' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '23' ) . '">' . esc_attr( '23' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '24' ) . '">' . esc_attr( '24' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '25' ) . '">' . esc_attr( '25' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '26' ) . '">' . esc_attr( '26' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '27' ) . '">' . esc_attr( '27' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '28' ) . '">' . esc_attr( '28' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '29' ) . '">' . esc_attr( '29' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '30' ) . '">' . esc_attr( '30' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '31' ) . '">' . esc_attr( '31' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '32' ) . '">' . esc_attr( '32' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '33' ) . '">' . esc_attr( '33' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '34' ) . '">' . esc_attr( '34' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '35' ) . '">' . esc_attr( '35' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '36' ) . '">' . esc_attr( '36' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '37' ) . '">' . esc_attr( '37' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '38' ) . '">' . esc_attr( '38' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '39' ) . '">' . esc_attr( '39' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '40' ) . '">' . esc_attr( '40' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '41' ) . '">' . esc_attr( '41' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '42' ) . '">' . esc_attr( '42' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '43' ) . '">' . esc_attr( '43' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '44' ) . '">' . esc_attr( '44' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '45' ) . '">' . esc_attr( '45' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '46' ) . '">' . esc_attr( '46' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '47' ) . '">' . esc_attr( '47' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '48' ) . '">' . esc_attr( '48' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '49' ) . '">' . esc_attr( '49' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '50' ) . '">' . esc_attr( '50' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '51' ) . '">' . esc_attr( '51' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '52' ) . '">' . esc_attr( '52' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '53' ) . '">' . esc_attr( '53' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '54' ) . '">' . esc_attr( '54' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '55' ) . '">' . esc_attr( '55' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '56' ) . '">' . esc_attr( '56' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '57' ) . '">' . esc_attr( '57' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '58' ) . '">' . esc_attr( '58' ) . '</option>'; 
		$minutes .= '<option value="' . esc_attr( '59' ) . '">' . esc_attr( '59' ) . '</option>'; 
		echo $minutes;
	}
	
	/**
	 * interval.
	 *
	 * @since 1.0.0
	 * @return array $interval
	 */
	public static function interval() {
		$interval = array(
			  'day'   => __( 'Day', 'ataswp_lang' ),
			  'week'  => __( 'Week', 'ataswp_lang' ),
			  'month' => __( 'Month', 'ataswp_lang' ),
			  'year'  => __( 'Year', 'ataswp_lang' )
		); 
		return $interval;
	}
	
	/**
	 * Blog local site datetime.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @return string $local_site_date_time
	 */
	public static function wp_local_site_date_time() {
        $local_site_date_time  = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		return $local_site_date_time;
	}
	
	/**
	 * Blog universal GMT datetime.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @return string $GMT
	 */
	public static function wp_universal_GMT_date_time() {
        $GMT  = date( 'Y-m-d H:i:s', current_time( 'timestamp', 1 ) );
		return $GMT;
	}
	
	/**
	 * Format date. d F Y - d M Y - M-d, Y
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param string $date
	 * @return string $date
	 */
	public static function formatDate( $date ) {
        $fdate  = date('d M Y',strtotime($date));
		return $fdate;
	}

	/**
	 * Format date time. d F Y - d M Y - M-d, Y   , d M Y  - H:i A
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param string $date
	 * @return string $date
	 */
	public static function formatDateTime( $date ) {
        $fdate  = date('d M Y  - H:i',strtotime($date));
		return $fdate;
	}
	
	/**
	 * Current date time.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @return string $currentdate
	 */
	public static function currentDate() {
        $currentdate = date("Y-m-d H:i:s");
		return $currentdate;
	}
	
	/**
	 * Remove time from current datetime.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param string $date
	 * @return string $date
	 */
	public static function removeTimeFromDate( $date ) {
		
		if ( empty( $date ) )
		return;
		
		$date  = date('Y-m-d',strtotime($date)); 
		return $date;
	}
	
	/**
	 * Add time to current date.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param string $date
	 * @return string $datetime
	 */
	public static function addTimeToDate( $date ) {
		
		if ( empty( $date ) )
		return;
		
		$time = date('H:i:s');
		$format = $date . ' ' . $time;
		
		$datetime  = date('Y-m-d H:i:s',strtotime($format)); 
		return $datetime;
	}
	
	/**
	 * base64_encode.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param string $data
	 * @return string $data
	 */
	public static function base64url_encode($data) {
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	/**
	 * base64_decode.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param string $data
	 * @return string $data
	 */
	public static function base64url_decode($data) {
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	} 
	
	/**
	 * Generate random string.
	 * 
	 * @since 1.0.0
	 * @access public static
	 * @param int $length
	 * @return string $randomString
	 */
	public static function generate_random_string($length)
	{
		// length should be minimum 6 characters long
		if ( empty($length) or $length <= 6 )
		{ 
		   $length = '6';
		}
		
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charsLength = strlen($chars); 
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $chars[rand(0, $charsLength - 1)];
		}
		 return $randomString;
		
	}
	
	/**
	 * AVOID METABOXES DUPLICATES.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param int $post_id
	 * @param string $post_type
	 * @return string $get_post_type
	 */
	public static function check_post_post_type_by_post_id($post_id, $post_type) {
		
		if ( empty( $post_id ) && empty( $post_type ) )
		return false;
		
		$get_post_type = '';
		// get post data by post id
		if ( get_post( $post_id ) ) {
			$get_post = get_post( $post_id );
			$get_post_type = $get_post->post_type;
			// check post type
			if ( $get_post_type == $post_type ) {
				return $get_post_type; 
			} else {
				// post type do not match
				return false;
			}
		} else {
			// no post found
			return false;
		}
	}
	
	/**
	 * Get domain name. e.g. example.com
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return string $domain
	 */
    public static function site_domain_name() 
	{
		// current page url
		//$url = CTLGGI_Helper::ctlggi_get_current_page_url();
		$url = home_url(); // use home_url as it's more safe
		// get domain name
		$domain = Post2Social_Helper::get_url_parse($url, $part='host');
		
		return $domain;
	}

	/**
	 * Get url parse.
	 *
	 * scheme - e.g. http
	 * host
	 * port
	 * user
	 * pass
	 * path
	 * query - after the question mark ?
	 * fragment - after the hashmark #
	 *
	 * @since 1.0.0
	 * @access public static
	 * @param  string $url
	 * @param  string $part
	 * @return string $parse
	 */
	public static function get_url_parse($url, $part='host') {
		$parse = parse_url($url);
		return $parse[$part];	
	}

	/**
	 * Get current page url.
	 *
	 * @since 1.0.0
	 * @access public static
	 * @return string $pageURL
	 */
	public static function get_current_page_url() 
	{
		 $pageURL = 'http';
		 if ( isset ($_SERVER["HTTPS"] ) == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 /*
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 */
		 $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 return $pageURL;
	}

	
}

?>