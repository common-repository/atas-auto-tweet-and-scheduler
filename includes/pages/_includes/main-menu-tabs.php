
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="row padding-left-right-15  margin-top-25 padding-bottom-15" style="vertical-align:middle;"> 

<div class="col-9">

    <div class="cw-plugin-logo">
    <a href="https://codeweby.com/" target="_blank">
    <img class="responsive" src="<?php echo esc_url( ATASWP_URL . 'includes/assets/images/atas-logo.png' ); ?>" alt="Auto Tweet and Scheduler">
    </a>
    </div>  
    <span class="padding-right-25 font-14"><?php _e("Version: ", 'ataswp_lang'); ?> <?php echo esc_attr( $plugin_version ); ?></span>
<?php 
$main_pages_tabs = ATASWP_Admin_Core::main_pages_tabs();
/*
echo '<pre>';
print_r( $main_pages_tabs );
echo '</pre>';
*/

foreach( $main_pages_tabs as $key => $value ){ 

// get admin url: https://example.com/wp-admin/
$url = get_admin_url() . 'admin.php?page=ataswp&main=' . $key;

// set up button colors and glyphicons
// $key == folder name
if ( $key == 'autopost' ) {
	$btn_color = 'btn-grey-settings';
	$glyphicon = 'class="glyphicon glyphicon-repeat"';
} elseif ( $key == 'accounts' ) {
	$btn_color = 'btn-grey-settings';
	$glyphicon = 'class="glyphicon glyphicon-user"';
} else {
    // default
	$btn_color = 'btn-wp-blue';
	$glyphicon = 'class="glyphicon glyphicon-refresh"';
}

?>    
    <a href="<?php echo esc_url( $url ); ?>" class="btn btn-mdl <?php echo $btn_color; ?>">
    <i <?php echo $glyphicon; ?>></i>&nbsp; <?php _e($value, 'ataswp_lang'); ?>
    </a>
<?php 
}
?>
    
</div>

<div class="col-3">

<a class="twitter-follow-button" href="https://twitter.com/codeweby" data-show-count="false" data-size="large">
<?php _e("Follow", 'ataswp_lang'); ?> @Codeweby
</a>

</div>

</div>
<!--/ row -->