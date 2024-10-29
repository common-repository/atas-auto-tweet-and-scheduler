<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class ATASWP_Public_Core
{
  
  public function public_method_name() {	
       // do stuff
  }

	
  // Enqueue Styles
  public function public_enqueue_styles() {	
	  //wp_enqueue_style( 'ataswp_public_css', ATASWP_URL . 'public/assets/css/public.css', array(), '', 'all' );
  }
  
  // Enqueue Scripts
  public function public_enqueue_scripts() {	
    //wp_enqueue_script( 'ataswp_public_js', ATASWP_URL . 'public/assets/js/public.js', array( 'jquery' ), '', true ); 
  }
	
}

?>