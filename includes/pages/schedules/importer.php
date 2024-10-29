<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$post_types = ATASWP_Admin_Core::get_registered_post_types();

$registered_taxonomies = ATASWP_Admin_Core::get_registered_taxonomies();

$all_post_types_filter = ''; // def
$global_filters = ATASWP_Admin_Core::global_filters();
if ( isset($global_filters['pro_version']['all_post_types_filter']) && $global_filters['pro_version']['all_post_types_filter'] == '1' ) {
	$all_post_types_filter = $global_filters['pro_version']['all_post_types_filter'];
}

/*
echo '<pre>';
print_r( $registered_taxonomies );
echo '</pre>';
*/
?>


<section>

<!-- section box -->
<div class="section-box">

<div class="ataswp-pages-header-bg">

<div class="col-6">
<span class="ataswp-page-title"><?php _e("Importer", 'ataswp_lang');  ?></span>
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

<p><?php _e("Import your posts.", 'ataswp_lang');  ?></p>

<!-- jquery -->
<div class="run-importer-response"></div>

<div class="row  padding-bottom-25">

<!-- jquery -->
<div class="show-return-data"></div>

<!-- cw-admin-forms -->
<div class="cw-admin-forms padding-bottom-25">

<form action="" method="post" id="ataswp-run-importer-form">

<input type="hidden" name="ataswp-run-importer-form-nonce" value="<?php echo wp_create_nonce('ataswp_run_importer_form_nonce'); ?>"/> 

<div class="row  padding-bottom-25">

<div class="col-4">
<div class="padding-left-right-10">

  <div class="checkbox">
    <label for="share_on_social_metabox"><?php _e("Post Types", 'ataswp_lang'); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Select the post types you want to import.", 'ataswp_lang') ; ?>"></span>
    </label>
  </div>
  
  <div id="" style="overflow-y:scroll; overflow-x:hidden; height:260px; padding:25px; margin-top:0px; margin-bottom:0px; background: #f4f8fb;">
  <div id="ataswp-post-types" class="checkbox margin-bottom-15">
    <?php 
	$checked  = ''; // def
	$disabled = ''; // def
	foreach ($post_types as $key => $value ) {
		
		if ( $key != 'post' && $all_post_types_filter != '1'  ) {
			$disabled = '1';
		}
		
		$value = str_replace("_"," ",$value); // replace underscore
	?>
    <div id="<?php esc_attr_e($key); ?>" class="display-block" style=" line-height:2em;">
        <input id="<?php esc_attr_e($key); ?>" class="ataswp-post-type" type="checkbox" data-ataswp-post-type="<?php esc_attr_e($key); ?>"  value="<?php esc_attr_e($key); ?>" name="importer[post_types][]" <?php echo ($disabled  == '1') ? 'disabled' : ''; ?> />
        <span style="line-height:1.6em;"><?php esc_attr_e(ucwords($value)); ?> </span>
    </div>
    <?php 
	}
	?>
  </div>
  </div>
  
  <?php 
  if ( $all_post_types_filter != '1'  ) {
	 // upgrade to PRO 
  ?>
    <div class="checkbox margin-top-15 margin-bottom-5">
    <span class="ataswp-upgrade-to-pro-text">
	<?php _e( 'Pages and custom posts are available in the ', 'ataswp_lang' ); ?>  
    <a href="https://codeweby.com/products/atas-auto-tweet-and-scheduler-pro/" target="_blank"><strong><?php _e( 'PRO version', 'ataswp_lang' ); ?></strong></a>.
    </span>
    </div>
  <?php 
  }
  ?>
  
</div>
</div><!--/ col --> 

<div class="col-4">
<div class="padding-left-right-10" >

  <div class="checkbox">
    <label for="share_on_social_metabox"><?php _e("Categories", 'ataswp_lang'); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Select the categories you want to import.", 'ataswp_lang') ; ?>"></span>
    </label>
  </div>

  <div id="" style="overflow-y:scroll; overflow-x:hidden; height:260px; padding:25px; margin-top:0px; margin-bottom:0px; background: #f4f8fb;">
  <div id="ataswp-categories" class="checkbox margin-bottom-15"> 
    <?php 
	$checked = ''; // def
	foreach ( $registered_taxonomies as $taxonomy ) {
		
		$taxonomy_obj = get_taxonomy($taxonomy);

		$terms = get_terms($taxonomy, array('hide_empty' => 1)); // hide empty categories
		
		$postTypeArray = $taxonomy_obj->object_type; // get post type
		
		/*
		echo '<pre>';
		print_r( $terms );
		echo '</pre>';
		*/
		
		if (count($terms) > 0) {
			echo '<div style="display:none;" class="ataswp-post-type-categories-' . $postTypeArray[0] . '">';
			
			// exclude tags
			if ( $taxonomy_obj->label != 'Tags' ) {
			?>
			<div class="display-block" style="line-height:2em;">
				<input class="ataswp-get-post-type" type="checkbox" value="<?php echo $postTypeArray[0]; ?>" id="<?php echo $postTypeArray[0]; ?>" name="post_type[]" />
				<span style="font-weight:bold; line-height:1.6em; font-size:13px;"><?php echo esc_attr( $taxonomy_obj->label . ' (' . $postTypeArray[0] . ') ' ); ?> </span>
			</div>
			<?php 
			
				foreach ($terms as $term) {
					 //echo $term->name . '<br>';
					 $categories = 'post_type:' . $postTypeArray[0] . ', taxonomy:' . $term->taxonomy . ', term_id:' . $term->term_id . '';
					?>
					<div class="ataswp-is-category-checked display-block ataswp-check-categories-<?php echo $postTypeArray[0]; ?>" style="line-height:2em;">
						<input class="ataswp-get-categories" type="checkbox" value="<?php echo $categories; ?>" id="<?php echo $postTypeArray[0]; ?>" name="categories[]" />
						<span style="line-height:1.6em;"><?php esc_attr_e( $term->name ); ?> </span>
					</div>
					<?php 
				}
			}
			echo '</div>';
		}
	}
	?>
  </div>
  </div>
  
</div>
</div><!--/ col --> 

<div class="col-4">
<div class="padding-left-right-10">

  <div class="checkbox">
    <label for="share_on_social_metabox"><?php _e("Posts", 'ataswp_lang'); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Select the posts you want to import.", 'ataswp_lang') ; ?>"></span>
    </label>
  </div>

  <div id="" style="overflow-y:scroll; overflow-x:hidden; height:260px; padding:25px; margin-top:0px; margin-bottom:0px; background: #f4f8fb;">
  <span class="ataswp-spinner-img-blog-posts"></span>
  <div id="ataswp-importer-display-pages">
     <?php ATASWP_Importer::display_pages(); ?>
  </div>
  <div id="ataswp-blog-posts" class="checkbox margin-bottom-15"> 
  
  </div>
  </div>
  
</div>
</div><!--/ col --> 

</div><!--/ row -->



<div class="row  padding-bottom-25">

<div class="col-3">
<div class="padding-left-right-10">
    
    <div class="checkbox margin-top-5 margin-bottom-5">
    <label for="ataswp_interval_label"><?php _e( 'Intervals', 'ataswp_lang' ); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Select the intervals you want to auto publish the post.", 'ataswp_lang') ; ?>"></span>
    </label>
    </div>
    
    <div class="margin-bottom-15">
    <?php 
      $interval = ATASWP_Helper::wp_cron_intervals();
      // Interval
      foreach( $interval as $key => $value )
      {
		?> 
		<div class="display-block" style="line-height:2em;">
			<input class="ataswp-get-intervals" type="checkbox" value="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" name="intervals[]" />
			<span style="line-height:1.6em;"><?php echo esc_attr( $value ); ?> </span>
		</div>
       <?php  
      }
    ?> 
    </div>
    
</div> 
</div><!--/ col --> 

<div class="col-3">
<div class="padding-left-right-10">

    <?php 
	$datetime = ATASWP_Helper::wp_local_site_date_time();
	$datetime_str = strtotime($datetime);
	$date         = date('Y-m-d', $datetime_str);
	?>
    
    <div class="checkbox margin-top-5 margin-bottom-5">
    <label for="start_date_label"><?php _e( 'Start Date', 'ataswp_lang' ); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The date when the scheduler start.", 'ataswp_lang') ; ?>"></span>
    </label>
    </div>
    <div style="display:block;">
    <input class="datepicker inputfield margin-bottom-15" id="start_date" name="start_date" type="text" value="<?php echo esc_attr__( $date ); ?>"> 
    </div>

    <div class="checkbox margin-top-10 margin-bottom-5">
    <label for="end_date_label"><?php _e( 'End Date', 'ataswp_lang' ); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The date when the scheduled end. Set '0000-00-00' for unlimited schedules.", 'ataswp_lang') ; ?>"></span>
    </label>
    </div>
    <div style="display:block;">
    <input class="datepicker inputfield margin-bottom-15" id="end_date" name="end_date" type="text" value="<?php echo esc_attr__( '0000-00-00' ); ?>"> 
    </div>

</div> 
</div><!--/ col --> 

<div class="col-3">
<div class="padding-left-right-10">
    
    <div class="checkbox margin-top-5 margin-bottom-5">
    <label for="time_label"><?php _e( 'Time', 'ataswp_lang' ); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The time on the scheduler run. For time based cron it is recommended to use the real cronjob instead of wp cron.", 'ataswp_lang') ; ?>"></span>
    </label>
    </div>
    
    <div class="display-cron-time-fields" id="display-cron-time-fields">
    
        <a class="ataswp-importer-add-time btn btn-sm btn-wp-blue margin-top-5 margin-bottom-5">
        <i class="glyphicon glyphicon-plus-sign"></i>&nbsp;
		<?php _e( 'Add', 'ataswp_lang' ); ?>
        </a>
      
        <div id="ataswp-importer-cron-time-fields"></div><!-- jQuery insert time after this --> 
        
            <div class="importer-hour-and-minute display-block margin-top-5 margin-bottom-5">
            <select name="hours[]" class="ataswp_importer_hours_class">
            <?php 
                ATASWP_Helper::wp_cron_option_hours();    
            ?> 
            </select>
             :         
            <select name="minutes[]" class="ataswp_importer_minutes_class">
            <?php 
                ATASWP_Helper::wp_cron_option_minutes();
            ?> 
            </select>
            <a class="ataswp-importer-remove-time padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
            </div>
            
            <!-- clone for jQuery -->
            <div class="importer-hour-and-minute-clone display-block margin-top-5 margin-bottom-5">
            <select name="" class="ataswp_importer_hours_class">
            <?php 
                ATASWP_Helper::wp_cron_option_hours();    
            ?> 
            </select>
             :            
            <select name="" class="ataswp_importer_minutes_class">
            <?php 
                ATASWP_Helper::wp_cron_option_minutes();
            ?> 
            </select>
            <a class="ataswp-importer-remove-time padding-left-right-10" href="/" onclick="return false;"><?php _e( 'X', 'ataswp_lang' ); ?></a>
            </div>
    
    </div>

</div> 
</div><!--/ col --> 

<div class="col-3">
<div class="padding-left-right-10">
    <div class="ataswp-formsubmit">
        <button class="btn btn-mdl btn-wp-blue margin-top-25" id="ataswp-run-importer-form-submit" name="ataswp-run-importer-form-submit" type="submit"> 
        <i class="glyphicon glyphicon-edit"></i>&nbsp; <?php esc_attr_e('Run Importer', 'ataswp_lang'); ?>
        <span class="ataswp-spinner-img"></span>
        </button>
    </div>
</div> 
</div><!--/ col --> 

</div><!--/ row -->

</form>

</div>
<!--/ cw-admin-forms -->


</div><!--/ row -->


</div>
<!--/ padding content -->

<div class="ataswp-pages-footer-bg"></div>

</div>
<!--/ section box -->

</section>
