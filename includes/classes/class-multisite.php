<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Multisite
{

	/**
	 * Checks to see if a plugin is "Network Active" on a multi-site installation of WordPress.
	 *
	 * @since        1.0.0
     * @return       int   $network_active
	 */
	public function is_plugin_network_active()
	{   
	    $network_active = '0'; // default
		// Makes sure the plugin is defined before trying to use it
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		$plugin_base_name = plugin_basename(ATASWP_PLUGIN_FILE); // e.g. plugin-directory/plugin-file.php
		
		if ( is_plugin_active_for_network( $plugin_base_name ) ) {
			// Plugin is activated
			$network_active = '1';
		}
		
		return $network_active;
	}
	
	/**
	 * Action triggered whenever a new blog is created within a multisite network. 
	 *
	 * @global       $wpdb
	 *
	 * @since        1.0.0
	 * @param int    $blog_id Blog ID.
	 * @param int    $user_id User ID.
	 * @param string $domain  Site domain.
	 * @param string $path    Site path.
	 * @param int    $site_id Site ID. Only relevant on multi-network installs.
	 * @param array  $meta    Meta data. Used to set initial site options.
     * @return       void
	 */
	public function multisite_new_site_activation( $blog_id, $user_id, $domain, $path, $site_id, $meta )
	{ 
		global $wpdb;
		
		if ( empty( $blog_id ) )
			return;
	
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
		
		// check if plugin network active
		if ( $this->is_plugin_network_active() != '1' )
			return;
		
		require_once ATASWP_DIR . 'includes/class-activator.php';
		
		switch_to_blog( $blog_id );
		
		// do something
		ATASWP_Activator::install_single_site();
		
		restore_current_blog();
	}
			
}

?>