<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Auto_Post_Metabox
{
	
	/**
	 * Add Metaboxes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_auto_post_metabox() {
		//$post_types = Post2Social_Admin::get_registered_post_types();
		$post_types  = ''; // def
		$auto_post_settings    = get_option('ataswp_auto_post_settings');
		$enable_auto_post = isset( $auto_post_settings['enable_auto_post'] ) ? sanitize_text_field( $auto_post_settings['enable_auto_post'] ) : '';
		// display the metavox on the saved post types only
		if ( isset( $auto_post_settings['enabled_post_types'] ) && !empty($auto_post_settings['enabled_post_types']) ) {
			$post_types  = $auto_post_settings['enabled_post_types'];
			$post_types  = json_decode($post_types, true); // convert to array
		}
		
		if ( $enable_auto_post == '1' ) {
			if ( !empty($post_types) ) {
				foreach ($post_types as $key => $value ) {
					add_meta_box(
						'ataswp_auto_post_metabox',
						__( '<strong>ATAS</strong> - Auto Post', 'ataswp_lang' ), 
						array( $this, 'render_auto_post_metabox' ), 
						$value, // post type
						'side',
						'high'
					);
				}
			}
		}
	}
	
	/**
	 * Render Metaboxes.
	 *
	 * @since 1.0.0
	 * @param object $post
	 * @return void
	 */
	public function render_auto_post_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'ataswp_auto_post_metabox_nonce_action', 'ataswp_auto_post_metabox_nonce' );
		
		// Get the meta for all keys for the current post
		$meta = get_post_meta( $post->ID );
		$post_id = $post->ID;
		?>
          
          <div class="checkbox margin-top-10 margin-bottom-10">   
            <input type="checkbox" value="1" id="ataswp_twitter_auto_post" name="ataswp_twitter_auto_post" />
            <span><?php _e("Twitter Auto Post", 'ataswp_lang'); ?> </span>
            <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Page will be auto posted to Twitter soon as you hit the publish button.", 'ataswp_lang'); ?>"></span>
          </div>
          
        <?php 
		  
			// do avtion, extend share on social metabox
			do_action( 'ataswp_auto_post_metabox', $post_id ); // <- extensible 
		
	}
	
	/**
	 * Save Metabox.
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @param object $post
	 * @return void
	 */
	public function save_auto_post_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_action = 'ataswp_auto_post_metabox_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $_POST['ataswp_auto_post_metabox_nonce'] ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $_POST['ataswp_auto_post_metabox_nonce'], $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// auto post
		if ( isset($_POST['ataswp_twitter_auto_post']) ) {
			$auto_post = '1';
		} else {
			$auto_post = '';
		}
		
		// auto post save
		if ( $auto_post == '1' ) {
			update_post_meta($post_id,'_ataswp_twitter_auto_post', $auto_post);
		} else {
			delete_post_meta($post_id,'_ataswp_twitter_auto_post');
		}
		
		// post on social networks only if auto post enabled
		if ( $auto_post == '1' ) {
			$twitter_data = array(
				'post_id'              => $post_id,
				'auto_post'            => $auto_post // 1 or empty 
			);
			$twitter_data = json_encode($twitter_data); // json encode before send
			// do avtion, process to post
			do_action( 'ataswp_process_twitter_auto_post', $twitter_data ); // <- extensible 
		}
		
		
	}
						
}

?>