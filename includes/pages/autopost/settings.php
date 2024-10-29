<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$auto_post_settings = get_option('ataswp_auto_post_settings');
$enable_auto_post = isset( $auto_post_settings['enable_auto_post'] ) ? sanitize_text_field( $auto_post_settings['enable_auto_post'] ) : '';
$words  = isset( $auto_post_settings['words_into_hashtags'] ) ? sanitize_text_field( $auto_post_settings['words_into_hashtags'] ) : '';

$enabled_post_types = isset( $auto_post_settings['enabled_post_types'] ) ? sanitize_text_field( $auto_post_settings['enabled_post_types'] ) : '';

if ( ! empty($enabled_post_types) ) {
	$enabled_post_types   = json_decode($enabled_post_types, true); // convert to array
} else {
	$enabled_post_types = ''; // def
}

$post_types = ATASWP_Admin_Core::get_registered_post_types();
		
?>

<section>

<!-- section box -->
<div class="section-box">

<div class="ataswp-pages-header-bg">
<span class="ataswp-page-title"><?php _e("Auto Post Settings", 'ataswp_lang');  ?></span>
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

<form action="" method="post" id="ataswp-auto-post-settings-form">

<input type="hidden" name="ataswp-auto-post-settings-form-nonce" value="<?php echo wp_create_nonce('ataswp_auto_post_settings_form_nonce'); ?>"/>

<div class="col-4">
<div class="padding-left-right-10">

  <div class="ataswp-form-elements-title margin-bottom-10">
  <?php _e('Auto Post Metabox', 'ataswp_lang'); ?> 
  <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "If enabled the Auto Post metabox will be displayed on the above selected posts, pages and custom post pages.", 'ataswp_lang') ; ?>"></span>
  </div> 
    
  <div class="checkbox margin-bottom-5">   
    <input type="checkbox" value="1" <?php echo ($enable_auto_post == '1') ? 'checked' : '' ?> id="enable_auto_post" name="enable_auto_post" />
    <span><?php _e("Enable/Disable", 'ataswp_lang'); ?> </span>
  </div>
  

  
  <div class="ataswp-form-elements-title margin-top-bottom-10">
  <?php _e('Post Types', 'ataswp_lang'); ?> 
  <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "The Auto Post Metabox will be displayed on the selected post type pages.", 'ataswp_lang') ; ?>"></span>
  </div> 
    
  <div class="checkbox">
    <?php 
	$checked = ''; // def
	foreach ($post_types as $key => $value ) {
		if ( ! empty($enabled_post_types) ) {
			if( in_array( $value ,$enabled_post_types ) ) {
				$checked = 'checked';
			} else {
				$checked = '';
			}
		}
		
		$value = str_replace("_"," ",$value); // replace underscore
	?>
    <div class="display-block" style=" line-height:1.6em;">
        <input type="checkbox" value="<?php esc_attr_e($key); ?>" <?php echo $checked; ?> id="enabled_post_types" name="enabled_post_types[]" />
        <span><?php esc_attr_e(ucwords($value)); ?> </span>
    </div>
    <?php 
	}
	?>
  </div>
  
</div>
</div><!--/ col --> 

<div class="col-8">
<div class="padding-left-right-10">

  <div class="radiobox margin-top-5 margin-bottom-5">
    <div class="ataswp-form-elements-title">
	<?php _e('Auto Convert Words into Hashtags', 'ataswp_lang'); ?>
    <span class="ataswp-tooltip tooltip-info-icon" title="<?php _e( "Auto-convert the added words into hashtags and replace the matched words in the blog post title. E.g. wordpress will become to #wordpress", 'ataswp_lang') ; ?>"></span>
    </div>
  </div>
  
  <div class="inputbox">
     <textarea id="words_into_hashtags" name="words_into_hashtags"  rows="6" maxlength="300" ><?php echo esc_textarea( stripslashes( $words ) ); ?></textarea>
     <div class="padding-top-15"><?php _e("Words should be separated by comma ( , ).", 'ataswp_lang'); ?></div>
  </div>
  
    <div class="formsubmit">
        <button class="btn btn-mdl btn-wp-blue float-left margin-top-15" name="ataswp-auto-post-settings-form-submit" type="submit"> 
        <i class="glyphicon glyphicon-edit"></i>&nbsp; <?php esc_attr_e('Save Changes', 'ataswp_lang'); ?>
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