
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<section>

<!-- section box -->
<div class="section-box">

<div class="ataswp-pages-header-bg">
<span class="ataswp-page-title"><?php _e("Accounts", 'ataswp_lang');  ?></span>
</div>

<!-- padding content -->
<div class="padding-left-right-15">

<div class="row padding-bottom-25">

<!-- jquery -->
<div class="show-return-data"></div>

<div class="col-12">

<div class="padding-left-right-10">


  <div class="ataswp-pages-title padding-top-15"><?php _e("Twitter Login", 'ataswp_lang');  ?></div>
  
    <!-- jquery -->
    <div class="twitter-app-return-data"></div>
    
    <?php 
    if ( !empty($tw_access_token) && !empty($tw_access_token_secret) ) {
    ?>
    <p><?php _e("Great! You are successfully connected to Twitter. Now you might want to ", 'ataswp_lang');  ?>
    <a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=ataswp&main=scheduler&sub=importer' ); ?>"><?php _e("import your posts", 'ataswp_lang');  ?>.</a>
    </p>
    <form action="" method="post" id="ataswp-twitter-disconnect-app-form">
        <input type="hidden" name="ataswp-twitter-disconnect-account-form-nonce" value="<?php echo wp_create_nonce('ataswp_twitter_disconnect_account_form_nonce'); ?>"/> 
        <button class="btn btn-mdl btn-orange margin-top-10" name="ataswp-disconnect-app-form-submit" type="submit"> 
        <div class="ataswp-social-media-button-data">
        <i class="glyphicon glyphicon-refresh"></i>
        &nbsp; <?php esc_attr_e('Connected by', 'ataswp_lang') ?>  <?php esc_attr_e( $tw_user_name ); ?> 
        <!-- <span class="ataswp-disconnect-button" >x </span> -->
        </div>
        </button>
        
        <span class="margin-left-15">
        <?php _e("My Twitter: ", 'ataswp_lang'); ?>
        <a href="<?php echo esc_url('https://twitter.com/' . trim($tw_screen_name) ); ?>" target="_blank">
        <?php esc_attr_e('https://twitter.com/' . trim($tw_screen_name)); ?>
        </a>
        </span>
        
    </form> 
	<?php
    } else {
    ?>
    <p><?php _e("Please Login to your Twitter account.", 'ataswp_lang');  ?></p>
    <form action="" method="post" id="ataswp-twitter-connect-app-form">
        <input type="hidden" name="ataswp-twitter-connect-account-form-nonce" value="<?php echo wp_create_nonce('ataswp_twitter_connect_account_form_nonce'); ?>"/> 
        <button class="btn btn-mdl btn-wp-blue margin-top-10" name="ataswp-connect-app-form-submit" type="submit"> 
        <i class="glyphicon glyphicon-refresh"></i>&nbsp; <?php esc_attr_e('Twitter Connect', 'ataswp_lang') ?>
        </button>
    </form> 
	<?php
    }
    ?>


</div>  
  
</div><!--/ col --> 


</div><!--/ row -->


</div>
<!--/ padding content -->

<div class="ataswp-pages-footer-bg"></div>

</div>
<!--/ section box -->

</section>