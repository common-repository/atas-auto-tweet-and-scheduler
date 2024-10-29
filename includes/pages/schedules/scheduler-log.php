
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<section>

<!-- section box -->
<div id="ataswp-scheduler-log-content" class="section-box"> 

<div class="ataswp-pages-header-bg">

<div class="col-6">
<span class="ataswp-page-title"><?php _e("Log", 'ataswp_lang');  ?></span>
</div><!--/ col --> 

<div class="col-6">
<?php 
		$datetime = ATASWP_Scheduler::scheduler_date_time();
		$datetime  = date('l, d M Y  - H:i',strtotime($datetime));
?>
<div class="timezone float-right"><span class="ataswp-live-clock"><?php echo esc_attr( $datetime ); ?> </span></div>
</div><!--/ col --> 

</div>

<!-- padding content -->
<div class="padding-left-right-15">

<div class="row padding-bottom-25">

<!-- jquery -->
<div class="auto-publish-response"></div>

<!-- cw-admin-forms -->
<div id="ataswp-scheduler-log-form" class="cw-admin-forms padding-bottom-25">


<div class="col-12">

<div class="padding-left-right-15">

<div class="col-12 padding-top-bottom-15">

<?php

// get admin url: https://example.com/wp-admin/
$baseurl = get_admin_url() . 'admin.php?page=ataswp&main=scheduler&maintab='; 

// delete all
?>
<a href="/" onclick="return false;" class="ataswp-scheduler-reset-log btn btn-intervals btn-interval"> 
<i style="color:#ed9d13;" class="glyphicon glyphicon-refresh"></i> &nbsp;
<?php _e( 'Reset', 'ataswp_lang' ); ?>
</a>

</div>

<?php 
$maintab = isset( $_GET['maintab'] ) ? sanitize_text_field( $_GET['maintab'] ) : 'success'; // def is Monday

global $wpdb, $post, $wp_query;

$per_page = 10;
$page = isset( $_GET['cpage'] ) ? (int) $_GET['cpage'] : 1;


if ($page > 1 ) {
    //$offset = $page * $per_page - $per_page;
	$offset = $page * $per_page - $per_page;
} else {
    $offset = 0; // set to zero
}

$total = '10'; // test

$get_results = ATASWP_Scheduler_Log::db_select_logs();

/*
echo '<pre>';
print_r( $get_results );
echo '</pre>';
*/

if ( !empty($get_results) ) {
?>

<!-- table-responsive start -->
<div class="ataswp-table">

<table id="ataswp-logs-list-table">

<thead>
  <tr>
    <th class="uppercase"><?php _e( 'Date', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'Title', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'Status', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'Account', 'ataswp_lang' ); ?></th>
    <th></th>
    </tr>
</thead>

<tbody>	
<?php
	
	foreach($get_results as $log ) {

		$post_id           = isset( $log['post_id'] ) ? sanitize_text_field( $log['post_id'] ) : '';
		$custom_post_title = isset( $log['custom_post_title'] ) ? sanitize_text_field( $log['custom_post_title'] ) : '';
        $status            = isset( $log['status'] ) ? sanitize_text_field( $log['status'] ) : '';
		$datetime          = isset( $log['datetime'] ) ? sanitize_text_field( $log['datetime'] ) : '';
		$account           = isset( $log['account'] ) ? sanitize_text_field( $log['account'] ) : '';
		
		if ( !empty($custom_post_title) ) {
			$custom_post_title = '<span class="ataswp-custom-post-title display-block">' . esc_attr( $custom_post_title ) . '</span>';
		} else {
			$custom_post_title = '';
		}
		
		$datetime_format  = date('d M Y  - H:i A',strtotime($datetime));
		

?>
  <tr id="tr-<?php esc_attr_e($post_id); ?>" class="ataswp-posts">
  
    <td data-title="<?php _e( 'Date', 'ataswp_lang' ); ?>"> 
    <?php esc_attr_e($datetime_format); ?>
    </td>
  
    <td class="post_title" data-title="<?php _e( 'Title', 'ataswp_lang' ); ?>"> 
    <?php 
	
	$go_to_post = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';
	$view = '<a href="' . esc_url( $go_to_post ) . '"> ' . esc_attr( get_the_title($post_id) ) . '</a>';
	$view .= $custom_post_title;
	echo $view;
	?>
    </td> 
    
    <td data-title="<?php _e( 'Status', 'ataswp_lang' ); ?>"> 
    <?php esc_attr_e($status); ?>
    </td>
        
    <td data-title="<?php _e( 'Account', 'ataswp_lang' ); ?>">
    <?php esc_attr_e($account); ?>
    </td>

    <td> 
    <a id="<?php esc_attr_e($post_id); ?>"  class="ataswp-delete-single-log btn btn-xs btn-ataswp-table" title="delete" href="/" onclick="return false;"><i class="glyphicon glyphicon-trash"></i></a>
    </td>
    
  </tr>		

<?php 	
	}
?>
 
</tbody>

</table>

</div>
<!-- table-responsive end -->	

<?php 
}
?>


<div class="col-12">
<?php 
/*
echo '<div class="auto-publisher-nav">';

echo paginate_links(array(
    'base' => add_query_arg('cpage', '%#%'),
    'format' => '',
	'type' => 'list',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total / $per_page),
    'current' => $page
));

echo '</div>';
*/
?>

</div>


</div>    
</div><!--/ col --> 


</div>
<!--/ cw-admin-forms -->


</div><!--/ row -->


</div>
<!--/ padding content -->

<div class="ataswp-pages-footer-bg"></div>

</div>
<!--/ section box -->

</section>