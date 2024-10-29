<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$scheduler_settings    = get_option('ataswp_scheduler_settings');
$enable_scheduler      = isset( $scheduler_settings['enable_scheduler'] ) ? sanitize_text_field( $scheduler_settings['enable_scheduler'] ) : '';
$reset_scheduler       = isset( $scheduler_settings['reset_scheduler'] ) ? sanitize_text_field( $scheduler_settings['reset_scheduler'] ) : '';
$gmt_or_local_timezone = isset( $scheduler_settings['gmt_or_local_timezone'] ) ? sanitize_text_field( $scheduler_settings['gmt_or_local_timezone'] ) : '';
/*
echo '<pre>';
print_r( $scheduler_settings );
echo '</pre>';
*/
?>

<section>

<!-- section box -->
<div class="section-box">

<div class="ataswp-pages-header-bg">
<span class="ataswp-page-title"><?php _e("Scheduler Settings", 'ataswp_lang');  ?></span>
</div>

<!-- padding content -->
<div class="padding-left-right-15">

<div class="row padding-bottom-25">

<!-- jquery -->
<div class="show-return-data"></div>

<div class="col-12">

<div class="padding-left-right-10">

<!-- cw-admin-forms -->
<div class="cw-admin-forms padding-bottom-25 padding-top-20">

<form action="" method="post" id="ataswp-scheduler-settings-form">

<input type="hidden" name="ataswp-scheduler-settings-form-nonce" value="<?php echo wp_create_nonce('ataswp_scheduler_settings_form_nonce'); ?>"/>

<div class="col-4">
<div class="padding-left-right-10">

  <div class="ataswp-form-elements-title margin-bottom-10">
  <?php _e('Enable/Disable Scheduler', 'ataswp_lang'); ?>
  <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The ATAS scheduler will run only if the mode is set to enabled.", 'ataswp_lang') ; ?>"></span>
  </div> 
    
  <div class="checkbox margin-bottom-5">   
    <input type="checkbox" value="1" <?php echo ($enable_scheduler == '1') ? 'checked' : '' ?> id="enable_scheduler" name="enable_scheduler" />
    <span><?php _e("Enable/Disable", 'ataswp_lang'); ?> </span>
  </div>
  
  <div class="ataswp-form-elements-title margin-top-bottom-10">
  <?php _e('Reset Schedules', 'ataswp_lang'); ?>
  <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Warning! If you check this all of the schedules data will be deleted.", 'ataswp_lang') ; ?>"></span>
  </div> 
    
  <div class="checkbox margin-bottom-5">   
    <input type="checkbox" value="1" id="reset_scheduler" name="reset_scheduler" />
    <span><?php _e("Reset", 'ataswp_lang'); ?> </span>
  </div>
  
</div>
</div><!--/ col --> 

<div class="col-4">
<div class="padding-left-right-10">

  <div class="radiobox margin-top-5 margin-bottom-5">
    <div class="ataswp-form-elements-title margin-bottom-10">
	<?php _e('Set Timezone', 'ataswp_lang'); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Choose Universal or Local Timezone. Timezone settings is from WP Admin->Settings->General. ", 'ataswp_lang') ; ?>"></span>
    </div>
  </div>
    
  <div class="radiobox">
    <?php 
	
	$GMT = ATASWP_Helper::wp_universal_GMT_date_time();
	$local = ATASWP_Helper::wp_local_site_date_time();
	
	$checked_gmt   = ''; // def
	$checked_local = ''; // def
	
	if ($gmt_or_local_timezone == 'local' ) { 
	   $checked_local = 'checked';
	} elseif ($gmt_or_local_timezone == 'gmt' ) {
	   $checked_gmt = 'checked';
	} else {
	   $checked_gmt = 'checked'; // default
	}
		
	?>
    <div class="radio display-block" >
        <input type="radio" value="gmt" <?php echo $checked_gmt; ?>  name="gmt_or_local_timezone" />
        <span><?php _e( "Universal Timezone", 'ataswp_lang'); ?> ( <?php echo $GMT; ?> )</span>
    </div>
    
    <div class="radio display-block" >
        <input type="radio" value="local" <?php echo $checked_local; ?>  name="gmt_or_local_timezone" />
        <span><?php _e( "Local Timezone", 'ataswp_lang'); ?> ( <?php echo $local; ?> )</span>
    </div>

  </div>
  
    <div class="ataswp-formsubmit">
        <button class="btn btn-mdl btn-wp-blue float-left margin-top-15" is="ataswp-scheduler-settings-form-submit" name="ataswp-scheduler-settings-form-submit" type="submit"> 
        <i class="glyphicon glyphicon-edit"></i>&nbsp; <?php esc_attr_e('Save Changes', 'ataswp_lang'); ?>
        <span class="ataswp-spinner-img"></span>
        </button>
    </div>

</div>
</div><!--/ col --> 

</form>

</div>
<!--/ cw-admin-forms -->


</div>  
  
</div><!--/ col --> 


</div><!--/ row -->


</div>
<!--/ padding content -->

<div class="ataswp-pages-footer-bg"></div>

</div>
<!--/ section box -->

</section>