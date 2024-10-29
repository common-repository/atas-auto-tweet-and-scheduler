<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( isset($_GET['page']) && $_GET['page'] == "ataswp"){ 

$tw_user_name  = isset( $app_single_account['twitter_user_name'] ) ? sanitize_text_field( $app_single_account['twitter_user_name'] ) : '';
$tw_screen_name  = isset( $app_single_account['twitter_user_screen_name'] ) ? sanitize_text_field( $app_single_account['twitter_user_screen_name'] ) : '';
$tw_access_token  = isset( $app_single_account['twitter_access_token'] ) ? sanitize_text_field( $app_single_account['twitter_access_token'] ) : '';
$tw_access_token_secret  = isset( $app_single_account['twitter_access_token_secret'] ) ? sanitize_text_field( $app_single_account['twitter_access_token_secret'] ) : '';

$main = ''; // def
$sub  = ''; // def

##### folder name #######
if( isset($_GET['main']) ) {
   $main = $_GET['main'];
} else {
   $main = 'schedules'; // default main page 
}

if( isset($_GET['sub']) ) {
   $sub = $_GET['sub'];
} else {
	
	// default sub pages
   if ( $main == 'autopost' ) {
	  $sub = 'settings'; 
   } elseif ( $main == 'schedules' ) {
	  $sub = 'schedules'; 
   } elseif ( $main == 'accounts' ) {
	  $sub = 'accounts'; 
   }
   
}

$plugin_data    = get_plugin_data( ATASWP_PLUGIN_FILE );
$plugin_version = $plugin_data['Version'];

/*
echo '<pre>';
print_r( $plugin_data );
echo '</pre>';
*/

?>

<div id="ataswp-admin"> 

<!-- main menu tabs -->
<?php 
// main menu tabs
require_once ATASWP_DIR . 'includes/pages/_includes/main-menu-tabs.php'; // schedules etc.
?>
<!--/ main menu tabs -->

<!-- menu tabs -->
<?php 
// sub menu tabs
require_once ATASWP_DIR . 'includes/pages/_includes/sub-menu-tabs.php'; 
?>
<!--/ menu tabs -->

<div class="row"> 

<!----------------------- page content left -->
<article class="col-9">

<?php 
// if not logged in to Twitter redirect to accounts page
if ( empty($tw_access_token) && empty($tw_access_token_secret) ) {
	require ATASWP_DIR . 'includes/pages/' . 'accounts' . '/' . 'accounts' . '.php';
} else {
	// page content
	if ( file_exists( ATASWP_DIR . 'includes/pages/' . $main . '/' . $sub . '.php' ) ) {
	  require ATASWP_DIR . 'includes/pages/' . $main . '/' . $sub . '.php';
	} else {
	  // make it extensible
	  do_action( 'ataswp_admin_add_sub_pages', $main, $sub ); // <- extensible	
	}
}

?>

</article>
<!-----------------------/ page content left -->


<!----------------------- page content right -->
<aside class="col-3">
<?php 
// aside boxes
if ( file_exists( ATASWP_DIR . 'includes/pages/' . $main . '/includes/' . $main . '-aside.php' ) ) {   
  require ATASWP_DIR . 'includes/pages/' . $main . '/includes/' . $main . '-aside.php';
} else {
  // make it extensible
  do_action( 'ataswp_admin_add_sub_pages_aside', $main, $sub ); // <- extensible	
}
?>
</aside>
<!-----------------------/ page content right -->

</div>
<!--/ row -->

<!-- footer -->
<div class="row"> 
<div class="col-12">
<!-- padding content -->
<div class="padding-left-right-25">
<p><?php _e('Developed by:', 'ataswp_lang'); ?> <a href="https://www.codeweby.com/" target="_blank">Codeweby</a></p>
</div>
<!--/ padding content -->
</div>
</div>
<!--/ row -->

</div> <!--/ #codeweby-admin -->

<?php 
}
?>