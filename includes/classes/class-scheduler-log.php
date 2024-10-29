<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class ATASWP_Scheduler_Log
{

	/**
	 * Delete single log Ajax.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function process_delete_single_log() 
	{
		
		// get form data
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		
		$post_id  = isset( $postdata['post_id'] ) ? sanitize_text_field( $postdata['post_id'] ) : '';
		
        if ( empty( $post_id ) )
		return;
	    
		// process delete
        ATASWP_Scheduler_Log::db_delete_log( $post_id );
		
	    #### important! #############
	    exit; // don't forget to exit!
		
	}
	
	/**
	 * Delete single log Ajax.
	 *
	 * @since  1.0.0
	 * @return void
	 */
    public function process_delete_all_logs() 
	{
		
		// get form data
		$formData = $_POST['formData'];
		// parse string
		parse_str($formData, $postdata);
		
		$delete  = isset( $postdata['delete'] ) ? sanitize_text_field( $postdata['delete'] ) : '';
		
        if ( empty( $delete ) )
		return;
	    
		// process delete
        ATASWP_Scheduler_Log::db_delete_all_logs();
		
	    #### important! #############
	    exit; // don't forget to exit!
		
	}

	/**
	 * Select logs.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @return array $get_results
	 */
	public static function db_select_logs() {
		
		// only admin allowed
		if ( ! current_user_can( 'edit_pages' ) )
		return;
		
	    // $post_id is the order_id
	    global $wpdb;
		
		$get_results = ''; // def
		  
	    $ataswp_scheduler_log = $wpdb->prefix . 'ataswp_scheduler_log'; // table, do not forget about tables prefix 
		
		$sql  = "
				SELECT id, post_id, custom_post_title, status, datetime, account
				FROM $ataswp_scheduler_log ORDER BY datetime DESC
				";
		$get_results = $wpdb->get_results( $sql, ARRAY_A ); // returns array: ARRAY_A
		
		return $get_results;
		
	}

	/**
	 * Insert log.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @param  string $status
	 * @param  string $datetime
	 * @param  string $account
	 * @return int last insert ID
	 */
	public static function db_insert_log( $post_id, $custom_post_title, $status, $datetime, $account )
	{
		if ( empty( $post_id ) && empty( $status ) && empty( $datetime ) )
		return;
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'ataswp_scheduler_log';
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'post_id'           => intval( $post_id ), 
				'custom_post_title' => sanitize_text_field( $custom_post_title ), 
				'status'            => sanitize_text_field( $status ), 
				'datetime'          => sanitize_text_field( $datetime ), 
				'account'           => sanitize_text_field( $account ), 
			) 
		);
		
		return $wpdb->insert_id;
		
	}
	
	/**
	 * Delete log.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @return void
	 */
	public static function db_delete_log( $post_id )
	{
		if ( empty( $post_id ) )
		return;
		
		// only admin allowed
		if ( ! current_user_can( 'edit_pages' ) )
		return;
		
		global $wpdb;
		
		$ataswp_scheduler_log = $wpdb->prefix . 'ataswp_scheduler_log'; // table, do not forget about tables prefix
		$wpdb->delete( $ataswp_scheduler_log, array( 'post_id' => $post_id ) );
		
	}
	
	/**
	 * Delete all logs.
	 *
	 * @global $wpdb
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  int $post_id
	 * @return void
	 */
	public static function db_delete_all_logs()
	{
		
		global $wpdb;
		
		$ataswp_scheduler_log = $wpdb->prefix . 'ataswp_scheduler_log'; // table, do not forget about tables prefix
		//$wpdb->query( "TRUNCATE $ataswp_scheduler_log" );
		$wpdb->query("TRUNCATE TABLE $ataswp_scheduler_log");
		
	}
							
}

?>