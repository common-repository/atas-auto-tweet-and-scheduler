
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="cw-menu-tabs">
   <ul>
<?php 
$sub_pages_tabs = ATASWP_Admin_Core::sub_pages_tabs($main);

foreach( $sub_pages_tabs as $key => $value ){ 

// get admin url: https://example.com/wp-admin/
$url = get_admin_url() . 'admin.php?page=ataswp&main=' . $main . '&sub=' . $key;
?>
   <li><a href="<?php echo esc_url( $url ); ?>"><?php _e($value, 'ataswp_lang'); ?></a></li>
<?php 
}
?>     
   </ul>
</div>