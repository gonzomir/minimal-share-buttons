<?php
/*
Plugin Name: Minimal Share Buttons
Plugin URI: http://greatgonzo.net/
Description: Minimal share buttons that don't spy on users and don't require a ton of JavaScript
Author: Milen Petrinski - Gonzo
Author URI: http://greatgonzo.net/
Version: 0.1
*/

define( 'PLUGIN_BASE', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include( PLUGIN_BASE . 'settings.php' );
include( PLUGIN_BASE . 'widget.php' );


function msb_scripts() {


  wp_register_script( 'svg4everybody', PLUGIN_URL.'/js/svg4everybody.legacy.min.js', array() );
  wp_enqueue_script( 'svg4everybody' );

  wp_register_script( 'domready', PLUGIN_URL.'/js/ready.min.js', array() );
  wp_enqueue_script( 'domready' );

  wp_register_script( 'msb-script', PLUGIN_URL.'/js/minimal-share-buttons.js', array( 'svg4everybody', 'domready' ) );
  wp_enqueue_script( 'msb-script' );

}
add_action( 'wp_enqueue_scripts', 'msb_scripts' );


function msb_styles() {

  wp_enqueue_style( 'msb-style', PLUGIN_URL.'/css/minimal-share-buttons.css' );

}
add_action( 'wp_enqueue_scripts', 'msb_styles' );


/**
 * Show share widget after post content.
 *
 * @uses is_single()
 */
function msb_content_filter( $content ) {

  if ( is_single() ) {
    $widget_args = array(
      'before_widget' => '<aside class="msb-container">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>'
    );
    $instance_args = array(
      'title' => get_option( 'msb_content_title', __( 'Share this', 'minimal-share-buttons' ) )
    );
    ob_start();
    the_widget( 'minimal_share_buttons', $instance_args, $widget_args );
    $output = ob_get_contents();
    ob_end_clean();
    $content .= $output;
  }

  return $content;

}

function msb_init(){

  $filter = get_option( 'msb_content_filter', false );

  if ( $filter ) {
    add_filter( 'the_content', 'msb_content_filter', 20 );
  }

  // Make plugin available for translation
  // Translations can be filed in the /languages/ directory
  load_plugin_textdomain( 'minimal-share-buttons', false, basename( dirname( __FILE__ ) ) . '/languages/' );

}
add_action( 'plugins_loaded', 'msb_init' );

// SVG icon helper
function msb_icon( $icon, $echo = true ) {
  $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon"><use xlink:href="'.PLUGIN_URL.'/images/icons.svg#icon-'.$icon.'"></use></svg>';
  if ( $echo ) {
    echo $html;
  }
  return $html;
}



?>
