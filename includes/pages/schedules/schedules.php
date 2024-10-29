
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<section>

<!-- section box -->
<div id="ataswp-scheduler-content" class="section-box"> 

    <div class="ataswp-pages-header-bg">
    
        <div class="col-6">
        <span class="ataswp-page-title"><?php _e("Schedules", 'ataswp_lang');  ?></span>
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
<div id="ataswp-scheduler-form" class="cw-admin-forms padding-bottom-25">


<div class="col-12">

<div class="padding-left-right-15">

    <div class="col-12 padding-top-bottom-15">
    
    <?php
    // get admin url: https://example.com/wp-admin/
    $baseurl = get_admin_url() . 'admin.php?page=ataswp&main=schedules&maintab='; 
    
    $cron_intervals = ATASWP_Helper::wp_cron_intervals();
    
    // Interval
    foreach( $cron_intervals as $key => $value )
    {
        
    echo '<a href="' . esc_url( $baseurl . $key ) . '" title="' . esc_attr( $key ) . '" class="btn btn-intervals btn-interval" > <i style="color:#ed9d13;" class="glyphicon glyphicon-refresh"></i> &nbsp;' . esc_html( ucfirst($value) ) . '</a> ';
    }
    
    ?>
    </div>

<?php 
$maintab = isset( $_GET['maintab'] ) ? sanitize_text_field( $_GET['maintab'] ) : 'Monday'; // def is Monday

global $wpdb, $post, $wp_query;

$per_page = 20;
$page = isset( $_GET['cpage'] ) ? (int) $_GET['cpage'] : 1;
	
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;	

$meta_query_args = array(
    'post_type' =>  'any', // for fetching from all post types set (any)
	'post_status'      => 'publish',
	'relation' => 'AND', // Optional, OR defaults to "AND"
    'order'     => 'ASC',
	'posts_per_page' => $per_page, // -1
	'paged'          => $page,
    'meta_key' => '_ataswp_scheduler_start_date', 
    'orderby'   => 'meta_value', //or 'meta_value_num' convert timestamp
    'meta_query' => array(
                    array('key' => '_ataswp_scheduler', // where key = value
                          'value' => '1',
						  'compare' => '='
					),
					array('key' => '_ataswp_scheduler_intervals', // where key = value
						  'value' => $maintab,
						  'compare' => 'LIKE'
					)
					
));

$get_results = new WP_Query( $meta_query_args );
$posts_count = $get_results->found_posts; // get count

if ( $get_results ) {
?>

<!-- table-responsive start -->
<div class="ataswp-table">

<table id="ataswp-posts-list-table">

<thead>
  <tr>
    <th class="uppercase"><?php _e( 'Next Scheduled', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'Post Title', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'Start Date', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'End Date', 'ataswp_lang' ); ?></th>
    <th class="uppercase"><?php _e( 'Count', 'ataswp_lang' ); ?></th>
    <th></th>
    </tr>
</thead>

<tbody>	
<?php
    // to do: use date time from settings
	$date_time = ATASWP_Scheduler::scheduler_date_time();
	//$date_time = ATASWP_Helper::wp_local_site_date_time(); // date time format
	$datetime_str = strtotime($date_time);
	$date         = date('Y-m-d', $datetime_str);
	$time         = date("H:i", $datetime_str);
	$hour         = date("H", $datetime_str);
	$minute       = date("i", $datetime_str);
	
	foreach($get_results->posts as $post ) {
		
		// auto publish activated on the following posts, pages etc.
		$post_id = $post->ID;
		
		// get meta values by post_id
		$ataswp_scheduler   = get_post_meta($post_id,'_ataswp_scheduler', true);
		
		$ataswp_scheduler_counter = get_post_meta($post_id,'_ataswp_scheduler_counter', true); 
		
		if ( empty($ataswp_scheduler_counter) ) {
			$ataswp_scheduler_counter = '0';
		}
		
		// only published
		if ( $ataswp_scheduler == '1' && get_post_status ( $post_id ) == 'publish' ) {
		
			$scheduler_intervals     = get_post_meta($post_id,'_ataswp_scheduler_intervals', true); // json obj
			$scheduler_intervals_arr = json_decode($scheduler_intervals, true); // convert to array
			$cron_intervals          = ATASWP_Helper::wp_cron_intervals();
			
			$scheduler_times     = get_post_meta($post_id,'_ataswp_scheduler_times', true); // json obj
			$scheduler_times_arr = json_decode($scheduler_times, true); // convert to array
			
			// start date
			$start_date = get_post_meta($post_id,'_ataswp_scheduler_start_date', true); 
			$start_date_format   = ATASWP_Helper::formatDate( $start_date );
			
			// end date
			$end_date = get_post_meta($post_id,'_ataswp_scheduler_end_date', true); 
			if ( !empty($end_date) && $end_date != '0000-00-00') {
			    $end_date_format   = ATASWP_Helper::formatDate( $end_date );
			} else {
				$end_date_format = __( 'never', 'ataswp_lang' );
			}
			
			// next scheduled
			$interval = $maintab;
			$next_scheduled_date  = ATASWP_Scheduler::next_scheduled_date( $interval ); 
			
			// trick, if start date is greater then recalculate the next scheduled date
			// start date + next interval
			if ($next_scheduled_date < $start_date) {
				$next_scheduled_date  = date('Y-m-d', strtotime('next ' . $interval, strtotime($start_date))); // create next date
			}
			
			$next_scheduled_date_format  = date('d F Y',strtotime($next_scheduled_date));
			$scheduler_start_time = end($scheduler_times_arr); // get last array value
			$next_day   = '<div class="ap-display-next-scheduled-day">' . $interval;
			$next_time  = ' start at</div> <div class="ap-display-next-scheduled-time">' . date('H:i A',strtotime($scheduler_start_time)) . '</div>';
			
			// check: active, inactive, expired
			$scheduler_status_label = __( 'active', 'ataswp_lang' );	
			$scheduler_status = 'active';
			// 0000-00-00 is for unlimited
			if ( $end_date != '0000-00-00' ) {
				if ( $end_date < $date ) {
					$scheduler_status_label = __( 'inactive', 'ataswp_lang' );
					$scheduler_status = 'inactive';
				}
			}
			
			
			/*
			echo '<pre>';
			print_r( $data );
			echo '</pre>';
			*/	
?>
  <tr id="tr-<?php echo esc_attr( $post_id ); ?>" class="ataswp-posts">
  
    <td data-title="<?php _e( 'Next Scheduled', 'ataswp_lang' ); ?>"> 
    <div class="ap-next-scheduled-date">
    <div class="ap-scheduler-status-<?php echo $scheduler_status; ?>" title="<?php echo esc_attr( $scheduler_status_label ); ?>"> <i class="glyphicon glyphicon-time"></i></div>
	 <?php echo esc_attr( $next_scheduled_date_format ); ?> 
    </div>
    <div class="ap-next-scheduled-day-and-time"><?php echo $next_day . $next_time; ?></div>
    </td> 
    
    <td class="post_title" data-title="<?php _e( 'Post Title', 'ataswp_lang' ); ?>"> 
    <?php 
	$go_to_post = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';
	$view = '<a href="' . esc_url( $go_to_post ) . '"> ' . esc_attr( get_the_title($post_id) ) . '</a>';
	echo $view;
	?>
    </td>
    
    <td data-title="<?php _e( 'Start Date', 'ataswp_lang' ); ?>"> 
    <div class="ap-light-grey"><?php echo esc_attr( $start_date_format ); ?></div>
    </td>
    
    <td data-title="<?php _e( 'End Date', 'ataswp_lang' ); ?>"> 
    <div class="ap-light-grey"><?php echo esc_attr( $end_date_format ); ?></div>
    </td>
        
    <td data-title="<?php _e( 'Count', 'ataswp_lang' ); ?>">
    <div class="ap-light-grey"><?php echo esc_attr( $ataswp_scheduler_counter ); ?></div>
    </td>

    <td> 

    <a id="<?php echo esc_attr( $post_id ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>" class="ataswp-remove-page btn btn-xs btn-ataswp-table" title="delete" href="/" onclick="return false;"><i class="glyphicon glyphicon-trash"></i></a>
      
    <a id="#collapse<?php echo esc_attr( $post_id ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>" class="toggleContent btn btn-xs btn-ataswp-table" title="edit" href="/" onclick="return false;"><i class="glyphicon glyphicon-edit"></i></a>
    
    <a id="<?php echo esc_attr( $post_id ); ?>" class="ataswp-view-page btn btn-xs btn-ataswp-table" title="view" href="<?php echo get_permalink( $post_id ); ?>" target="_blank" ><i class="glyphicon glyphicon-eye-open"></i></a>
    
    </td>
    
  </tr>	
  
  <tr id="remove-hidden-tr-<?php echo esc_attr( $post_id ); ?>">
    <td colspan="7" class="show-hiden-row" id="collapse<?php echo esc_attr( $post_id ); ?>" style="display:none;">	
    
    <form action="" method="post" id="ataswp-scheduler-form-<?php echo esc_attr( $post_id ); ?>">
    <input type="hidden" name="post_id_<?php echo esc_attr( $post_id ); ?>" value="<?php echo esc_attr( $post_id ); ?>"/> 
    <input type="hidden" name="ataswp-scheduler-form-nonce" value="<?php echo wp_create_nonce('ataswp_scheduler_form_nonce'); ?>"/> 
    
    <div class="row  padding-bottom-15">
    
        <!-- jquery -->
        <div class="response-data-<?php echo esc_attr( $post_id ); ?>"></div>
       
            <!-- content -->
            <div class="col-3"> 
                <div class="padding-left-right-10" >
                    
                <div class="checkbox margin-top-10 margin-bottom-5">
                <label for="ataswp_interval_label"><?php _e( 'Intervals', 'ataswp_lang' ); ?>
                <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Select the intervals you want to auto publish the post.", 'ataswp_lang') ; ?>"></span>
                </label>
                </div>
                
                <div class="margin-bottom-15">
                <?php 
                  $interval = ATASWP_Helper::wp_cron_intervals();
                  // Interval
                  $checked = '';
                  foreach( $interval as $key => $value )
                  {
                      // if in array key
                      if( in_array( $key ,$scheduler_intervals_arr ) ) {
                          $checked = '1';
                      } else {
                         $checked = ''; 
                      }
                    ?> 
                    <div class="display-block" style="line-height:2em;">
                        <input class="ataswp-get-intervals" type="checkbox" <?php echo ($checked  == '1') ? 'checked' : ''; ?> value="<?php echo esc_attr( $key ); ?>" name="intervals_<?php echo esc_attr( $post_id ); ?>[]" />
                        <span style="line-height:1.6em;"><?php echo esc_attr( $value ); ?> </span>
                    </div>
                   <?php  
                  }
                ?> 
                </div>
                    
                </div>
            </div><!--/ col --> 
        
            <!-- content -->
            <div class="col-3"> 
                <div class="padding-left-right-10" >
                    
                <div class="checkbox margin-top-10 margin-bottom-5">
                <label for="start_date_label"><?php _e( 'Start Date', 'ataswp_lang' ); ?>
                <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The date when the scheduler start.", 'ataswp_lang') ; ?>"></span>
                </label>
                </div>
                <div style="display:block;">
                <input class="datepicker inputfield margin-bottom-15" id="start_date_<?php echo esc_attr( $post_id ); ?>" name="start_date_<?php echo esc_attr( $post_id ); ?>" type="text" value="<?php echo esc_attr__( $start_date ); ?>"> 
                </div>
            
                <div class="checkbox margin-top-10 margin-bottom-5">
                <label for="end_date_label"><?php _e( 'End Date', 'ataswp_lang' ); ?>
                <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The date when the scheduled end. Set '0000-00-00' for unlimited schedules.", 'ataswp_lang') ; ?>"></span>
                </label>
                </div>
                <div style="display:block;">
                <input class="datepicker inputfield margin-bottom-15" id="end_date_<?php echo esc_attr( $post_id ); ?>" name="end_date_<?php echo esc_attr( $post_id ); ?>" type="text" value="<?php echo esc_attr__( $end_date ); ?>"> 
                </div>
                
                    <?php 
                    if ( empty($ataswp_scheduler_counter) ) {
                        $ataswp_scheduler_counter = '0';
                    }
                    ?>
                
                    <div class="checkbox margin-top-10 margin-bottom-5">
                    <label for="ataswp_scheduler_counter_label"><?php _e( 'Tweet Count', 'ataswp_lang' ); ?>
                    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The time the post was tweeted. ", 'ataswp_lang'); ?>"></span>
                    </label>
                    </div>
                    <div style="display:block;">
                    <input style="width:100px;" class="inputfield" id="scheduler_counter_<?php echo esc_attr( $post_id ); ?>" name="scheduler_counter_<?php echo esc_attr( $post_id ); ?>" type="number" value="<?php echo esc_attr__( $ataswp_scheduler_counter ); ?>">
                    </div>
                    
                </div>
            </div><!--/ col --> 
       
            <!-- content -->
            <div class="col-3">
                <div class="padding-left-right-10" >
                
                <div class="checkbox margin-top-10 margin-bottom-5">
                <label for="time_label"><?php _e( 'Time', 'ataswp_lang' ); ?>
                <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The time on the scheduler run. For time based cron it is recommended to use the real cronjob instead of wp cron.", 'ataswp_lang') ; ?>"></span>
                </label>
                </div>
            
                
                <div class="display-cron-time-fields" id="display-cron-time-fields-<?php echo esc_attr( $post_id ); ?>" >
                
                    <a class="ataswp-scheduler-add-time btn btn-sm btn-wp-blue margin-top-5 margin-bottom-5" id="<?php echo esc_attr( $post_id ); ?>">
                    <i class="glyphicon glyphicon-plus-sign"></i>&nbsp; 
                    <?php _e( 'Add', 'ataswp_lang' ); ?>
                    </a>
                  
                        <div id="ataswp-scheduler-cron-time-fields-<?php echo esc_attr( $post_id ); ?>"></div><!-- jQuery insert time after this --> 
                        
                        <?php 
                        foreach( $scheduler_times_arr as $time ) {
                            
                            $time = explode(':',$time);
                            
                            $hours   = $time[0];
                            $minutes = $time[1];
                        ?>
                            <div class="scheduler-hour-and-minute display-block margin-top-5 margin-bottom-5">
                            <select name="hours_<?php echo esc_attr( $post_id ); ?>[]" class="ataswp_scheduler_hours_class">
                            <?php 
                                echo '<option selected="selected" value="' . esc_attr( $hours ) . '">' . esc_attr( $hours ) . '</option>'; 
                                ATASWP_Helper::wp_cron_option_hours();    
                            ?> 
                            </select>
                             :         
                            <select name="minutes_<?php echo esc_attr( $post_id ); ?>[]" class="ataswp_scheduler_minutes_class">
                            <?php 
                                echo '<option selected="selected" value="' . esc_attr( $minutes ) . '">' . esc_attr( $minutes ) . '</option>'; 
                                ATASWP_Helper::wp_cron_option_minutes();
                            ?> 
                            </select>
                            <a class="ataswp-scheduler-remove-time padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
                            </div>
                        <?php 
                        }
                        ?>
                    
                    </div>
                
                </div>
            </div><!--/ col --> 
        
            <!-- content -->
            <div class="col-3">
                <div class="padding-left-right-10" >
                
                <div class="formsubmit">
                    <button class="ataswp-scheduler-form-submit btn btn-mdl btn-wp-blue margin-top-25" name="ataswp-scheduler-form-submit" id="<?php echo esc_attr( $post_id ); ?>" type="submit"> 
                    <i class="glyphicon glyphicon-edit"></i>&nbsp; <?php esc_attr_e('Save', 'ataswp_lang'); ?>
                    </button>
                </div>
               
                
                </div>
            </div><!--/ col --> 
    
    </div><!--/ row -->
    
    <div class="row  padding-bottom-25">

        <!-- content -->
        <div class="col-12">
        <div class="padding-left-right-10" >   
        <?php 
           // display custom post titles feature
           ATASWP_Scheduler_Table::custom_post_titles( $post_id );
        
           do_action( 'ataswp_scheduler_table_edit_post', $post_id ); // <- extensible 
        ?>
        </div>
        </div><!--/ col -->     
    
    </div><!--/ row -->

    </form>    
    
    </td>
    
  </tr>		

<?php 
	    }
		
	}
	
?>
 
</tbody>

</table>

</div>
<!-- table-responsive end -->	

<?php 
}
wp_reset_query();
?>


<div class="col-12">
<?php 

//$total = $get_results->max_num_pages;

$total = $posts_count;

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

?>

</div>


</div>    
</div><!--/ col --> 

            <!-- clone for jQuery -->
            <div class="scheduler-hour-and-minute-clone display-block margin-top-5 margin-bottom-5">
            <select name="" class="ataswp_scheduler_hours_class">
            <?php 
                ATASWP_Helper::wp_cron_option_hours();    
            ?> 
            </select>
             :            
            <select name="" class="ataswp_scheduler_minutes_class">
            <?php 
                ATASWP_Helper::wp_cron_option_minutes();
            ?> 
            </select>
            <a class="ataswp-scheduler-remove-time padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
            </div>
            
            <!-- clone for jQuery -->
            <div class="ataswp-custom-post-title-clone display-block margin-top-5 margin-bottom-5">
            <input style="width:90%;" maxlength="140" class="custom_post_title_class inputfield" name="" type="text" value="">
            <a class="ataswp-remove-custom-post-title padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
            </div>


</div>
<!--/ cw-admin-forms -->


</div><!--/ row -->


</div>
<!--/ padding content -->

<div class="ataswp-pages-footer-bg"></div>

</div>
<!--/ section box -->

</section>