<?php
/*
Plugin Name: Minimal Share Buttons
Plugin URI: https://github.com/gonzomir/minimal-share-buttons
Description: A social share plugin that doesn't spy on users and doesn't slow down you site.
Author: Milen Petrinski - Gonzo
Author URI: https://greatgonzo.net/
Version: 0.6
License: GPL3
License URI: https://www.gnu.org/licenses/gpl.html
Text Domain: minimal-share-buttons
Domain Path: /languages
*/

define( 'MSB_PLUGIN_BASE', plugin_dir_path( __FILE__ ) );
define( 'MSB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include( MSB_PLUGIN_BASE . 'socials.php' );
include( MSB_PLUGIN_BASE . 'settings.php' );
include( MSB_PLUGIN_BASE . 'widget.php' );


function msb_scripts() {


  wp_register_script( 'svg4everybody', MSB_PLUGIN_URL.'/js/svg4everybody.legacy.min.js', array() );
  wp_enqueue_script( 'svg4everybody' );

  wp_register_script( 'domready', MSB_PLUGIN_URL.'/js/ready.min.js', array() );
  wp_enqueue_script( 'domready' );

  wp_register_script( 'msb-script', MSB_PLUGIN_URL.'/js/minimal-share-buttons.js', array( 'svg4everybody', 'domready' ) );
  wp_enqueue_script( 'msb-script' );

}
add_action( 'wp_enqueue_scripts', 'msb_scripts' );


function msb_styles() {

  wp_enqueue_style( 'msb-style', MSB_PLUGIN_URL.'/css/minimal-share-buttons.css' );

}
add_action( 'wp_enqueue_scripts', 'msb_styles' );


function msb_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=minimal-share-buttons">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ), 'msb_add_settings_link' );

/**
 * Show share widget after post content.
 *
 */
function msb_content_filter( $content ) {

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

  return $content;

}

function msb_content_filter_init(){

  $defaults = array( 'post' => true, 'page' => false, 'attachment' => true );
  $msb_content_filter = get_option( 'msb_content_filter', $defaults );

  if( !is_array( $msb_content_filter ) ) {
    $msb_content_filter = $defaults;
  }

  $post_type = get_post_type();
  if( ! $post_type ) return;

  $filter = array_key_exists( $post_type, $msb_content_filter ) && $msb_content_filter[ $post_type ];

  if ( $filter ) {
    add_filter( 'the_content', 'msb_content_filter', 999 );
  }

}
add_action( 'loop_start', 'msb_content_filter_init' );

function msb_init(){

  // Make plugin available for translation
  // Translations can be filed in the /languages/ directory
  load_plugin_textdomain( 'minimal-share-buttons', false, basename( dirname( __FILE__ ) ) . '/languages/' );

}
add_action( 'plugins_loaded', 'msb_init' );


function msb_uninstall(){

  delete_option( 'msb_socials' );
  delete_option( 'msb_content_filter' );
  delete_option( 'msb_content_title' );

}
register_uninstall_hook( __FILE__, 'msb_uninstall' );

// SVG icon helper
function msb_icon( $icon, $echo = true ) {
  $icon = apply_filters( 'msb_icon_name', 'icon-' . $icon );
  $sprite_url = apply_filters( 'msb_sprite_url', MSB_PLUGIN_URL . 'images/icons.svg' );
  $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" aria-hidden="true"><use xlink:href="'. esc_url( $sprite_url . '#' . $icon ) . '"></use></svg>';
  if ( $echo ) {
    echo $html;
  }
  return $html;
}



?>
