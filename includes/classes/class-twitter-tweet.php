<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Twitter_Tweet
{
	
	/**
	 * Get post title.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param int $post_id
	 * @return string $post_title
	 */
    public static function get_post_title( $post_id ) {
		
		if ( empty( $post_id ) )
		return;
		
		$post_title = ''; // def
		// twitter post title max 70 chars
		$post = get_post($post_id); // post data object
		$post_title = $post->post_title;
		$post_title = ATASWP_Helper::shorten_text($text=$post_title, $limit='65'); // limit title
		$post_title = trim($post_title);
		return $post_title;
	}

	/**
	 * Create post description from post content.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param int $post_id
	 * @return string $description
	 */
    public static function create_post_description( $post_id ) {
		
		if ( empty( $post_id ) )
		return;
		
		$description = ''; // def
		// twitter description max 140 chars
		$post = get_post($post_id); // post data object
		$description = $post->post_content;
		$description = ATASWP_Helper::shorten_text($text=$description, $limit='75'); // limit title
		$description = esc_attr( wp_strip_all_tags( stripslashes( trim($description) ), true ) );
		return $description;
	}

	/**
	 * Get post url.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param int $post_id
	 * @return string $post_url
	 */
    public static function get_post_url( $post_id ) {
		
		if ( empty( $post_id ) )
		return;
		
		$post_url = ''; // def
		$post_url = get_permalink( $post_id );
		$post_url = esc_attr( wp_strip_all_tags( stripslashes( trim($post_url) ), true ) );
		return $post_url;
	}


	/**
	 * Get post author.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param int $post_id
	 * @return string $author
	 */
    public static function get_post_author( $post_id ) {
		
		if ( empty( $post_id ) )
		return;
		
		$author = ''; // def
		// twitter post title max 70 chars
		$post = get_post($post_id); // post data object
		$post_author_id = $post->post_author;
		// get user info
		$user_info     = get_userdata($post_author_id);
		$display_name  = $user_info->display_name;
		
		$author = trim($display_name);
		return $author;
	}
	
	/**
	 * Replace words in text.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param string $text
	 * @return string $text
	 */
    public static function words_to_hashtags( $text ) {
		
		if ( empty( $text ) )
		return;
		
		$auto_post_settings = get_option('ataswp_auto_post_settings');
		$words  = isset( $auto_post_settings['words_into_hashtags'] ) ? sanitize_text_field( $auto_post_settings['words_into_hashtags'] ) : '';
		
		$words = str_replace(" ","",$words); // replace white spaces
		$words_array = explode(',', $words); // create array, explode by comma
		foreach($words_array as $word) {
           //echo $word . '<br>';
		   $text = str_replace($word,' #' . $word . '',$text);
		}
		
		return $text;
		
	}	
			
	
}

?>