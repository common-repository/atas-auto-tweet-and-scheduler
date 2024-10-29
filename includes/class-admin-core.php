<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ATASWP_Admin_Core
{

	/**
	 * Live clock display the local or GMT scheduler datetime. l, d M Y  - H:i:s
	 *
	 * @since  1.0.0
     * @return string $datetime
	 */
	public function live_clock() {
		$datetime = ATASWP_Scheduler::scheduler_date_time();
		$datetime  = date('l, d M Y  - H:i',strtotime($datetime));
		echo $datetime;
		exit; // don't forget to exit!
	}
	
    public function admin_menu() {
      add_menu_page ( 
					  $page_title = __( 'ATAS Auto Tweet Scheduler', 'ataswp_lang' ),
					  $menu_title = __( 'ATAS Auto Tweet Scheduler', 'ataswp_lang' ),
					  $capability = 'edit_pages', // admin, editor, user etc.
					  $menu_slug  = 'ataswp',
					  $function   = array( $this, 'main_page'), 
					  $icon_url   = ATASWP_URL . 'includes/assets/images/atas-wp-menu-icon.png',
					  $position   = '61' // after Appearance
					 );
	}
	
	public function main_page() {
		
		$app_single_account  = get_option('ataswp_twitter_app_single_account');
		$atas_is_pro_user    =  ''; // global for pages
        require_once ATASWP_DIR . 'includes/pages/main.php'; // main page 
	  
	}
	
	/**
	 * Global filters for core and any extension.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $gateways
	 */
    public static function global_filters() 
	{
		
		$filters = array(
			'general' => array(
				'debug_mode'                => '0', // values: 0 or 1
				'cron_run_time_log'         => '0', // run scheduler, cron run time text file log will be saved in plugin folder
				'cron_scheduler_check_log'  => '0', // run scheduler, cron check file log will be saved in plugin folder
			),
			
		);
	
		return apply_filters( 'ataswp_global_filters', $filters ); // <- extensible

	}
	
	/**
	 * Main pages tabs.
	 *
	 * @since      1.0.0
     * @return     array   $tabs
	 */
    public static function main_pages_tabs() 
	{
		$tabs = array(
		'schedules'     => __( 'Scheduler', 'ataswp_lang' ), // schedules.php
		'autopost'      => __( 'Auto Post', 'ataswp_lang' ), // autopost.php
		'accounts'      => __( 'Accounts', 'ataswp_lang' ), // accounts.php
		);
		return apply_filters( 'ataswp_main_pages_tabs', $tabs ); // <- extensible
	}
	
	/**
	 * Sub pages tabs.
	 *
	 * @since      1.0.0
     * @return     array   $tabs
	 */
    public static function sub_pages_tabs($main) 
	{
		if ( empty( $main ) )
		return;
		
		if ( $main == 'schedules' ) {
			$tabs = array(
			'schedules'     => __( 'Schedules', 'ataswp_lang' ),
			'scheduler-log' => __( 'Log', 'ataswp_lang' ),
			'importer'      => __( 'Importer', 'ataswp_lang' ),
			'settings'      => __( 'Settings', 'ataswp_lang' ),
			);
			return apply_filters( 'ataswp_scheduler_sub_pages_tabs', $tabs ); // <- extensible
		} elseif ( $main == 'autopost' ) {
			$tabs = array(
            'settings'     => __( 'Settings', 'ataswp_lang' ),
			);
			return apply_filters( 'ataswp_autopost_sub_pages_tabs', $tabs ); // <- extensible
		}  elseif ( $main == 'accounts' ) {
			$tabs = array(
			'accounts'      => __( 'Accounts', 'ataswp_lang' ),
			);
			return apply_filters( 'ataswp_accounts_sub_pages_tabs', $tabs ); // <- extensible
		} else {
			return;
		}
		
	}
	
	/**
	 * Get registered post types.
	 *
	 * @since  1.0.0
	 * @return array $post_types
	 */
	public static function get_registered_post_types() {
		$post_types = get_post_types();
		unset($post_types['revision']);
		unset($post_types['attachment']);
		unset($post_types['nav_menu_item']);
		unset($post_types['post_tag']);
		return $post_types;
	}
	
	/**
	 * Get registered taxonomies.
	 *
	 * @since  1.0.0
	 * @return array $taxonomies
	 */
	public static function get_registered_taxonomies() {
		$taxonomies = get_taxonomies();
		unset($taxonomies['nav_menu']);
		unset($taxonomies['post_format']);
		return $taxonomies;
	}
	
	/**
	 * Output admin Ajax notification messages.
	 *
	 * @since      1.0.0
     * @return     void
	 */
    public static function displayAjaxFormsValidationResult($validation='', $type='success') 
	{
		$output = '';
		
	    if ( $validation != '') {
		
			if ($type == 'success') {
				$type = 'alert-success'; // css
			} else if ($type == 'info') {
				$type = 'alert-info'; // css
			} else if ($type == 'error') {
				$type = 'alert-danger'; // css
			}
			
			// display validation error messages
			if( $validation != '' ) {
				$output .= '<div class="cw-form-msgs">';
				foreach ($validation as $validate ) {
				  $output .= '<div class="form-messages ' . $type . '">';
				  $output .= '&nbsp; ' . $validate; 
				  $output .= '</div>';
				}
				$output .= '</div>';
			}
			return $output;	
		
		} else {
			return false;
		}
	}
	
	// Enqueue Styles
	public function admin_enqueue_styles() {	
	    wp_enqueue_style( 'ataswp_admin_css', ATASWP_URL . 'includes/assets/css/admin.css', array(), '', 'all' );
		wp_enqueue_style( 'ataswp_table_css', ATASWP_URL . 'includes/assets/css/atas-table-responsive.css', array(), '', 'all' );
		wp_enqueue_style( 'ataswp_glyphicon_css', ATASWP_URL . 'includes/assets/css/glyphicon.css', array(), '', 'all' );
		//jQuery UI theme css file for date picker
		wp_enqueue_style('ataswp-admin-ui-css','https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',array(),"1.9.0",false);
	}
	
	// Enqueue Scripts
	public function admin_enqueue_scripts() {	
	    wp_enqueue_media();
		
		$spinner_img = ATASWP_URL . 'includes/assets/images/spinner-white.gif';
		
	    wp_enqueue_script( 'ataswp_admin_js', ATASWP_URL . 'includes/assets/js/admin.js', array( 'jquery' ), '', true ); 
		
		wp_localize_script( 'ataswp_admin_js', 'ataswp_admin', array( 
			'ataswp_spinner_img'       => $spinner_img
		));
		
		//jQuery UI date picker file
		wp_enqueue_script('jquery-ui-datepicker');
		
		#### Twitter ####
		wp_enqueue_script( 'ataswp-twitter-widgets', 'https://platform.twitter.com/widgets.js', array( 'jquery' ), '', true); // twitter
	}
	
	
	
}

?>