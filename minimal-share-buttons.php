<?php

/*
Plugin Name: Minimal Share Buttons
Plugin URI: https://github.com/gonzomir/minimal-share-buttons
Description: A social share plugin that doesn't spy on users and doesn't slow down you site.
Author: Milen Petrinski - Gonzo
Author URI: https://greatgonzo.net/
Version: 1.5
License: GPL3
License URI: https://www.gnu.org/licenses/gpl.html
Text Domain: minimal-share-buttons
Domain Path: /languages
*/

define( 'MSB_PLUGIN_BASE', plugin_dir_path( __FILE__ ) );
define( 'MSB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require MSB_PLUGIN_BASE . 'inc/socials.php';
require MSB_PLUGIN_BASE . 'inc/settings.php';
require MSB_PLUGIN_BASE . 'inc/class-minimal-share-buttons.php';

if ( function_exists( 'register_block_type' ) ) {
	// Block editor (Gutenberg) is active.
	include MSB_PLUGIN_BASE . 'blocks/index.php';
}

/**
 * Enqueue scripts for the frontend.
 */
function msb_scripts() {

	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		// When SCRIPT_DEBUG is true load unminified version of script.
		wp_register_script(
			'svg4everybody',
			plugins_url( 'assets/js/svg4everybody.legacy.min.js', __FILE__ ),
			[],
			filemtime( MSB_PLUGIN_BASE . 'assets/js/svg4everybody.legacy.min.js' ),
			false
		);

		wp_register_script(
			'msb-script',
			plugins_url( 'assets/js/minimal-share-buttons.js', __FILE__ ),
			[ 'svg4everybody' ],
			filemtime( MSB_PLUGIN_BASE . 'assets/js/minimal-share-buttons.js' ),
			false
		);
		wp_enqueue_script( 'msb-script' );

	} else {
		// We have minified script too.
		wp_register_script(
			'msb-script',
			plugins_url( 'assets/js/msb.min.js', __FILE__ ),
			[],
			filemtime( MSB_PLUGIN_BASE . 'assets/js/minimal-share-buttons.js' ),
			false
		);
		wp_enqueue_script( 'msb-script' );
	}
}
add_action( 'wp_enqueue_scripts', 'msb_scripts' );

/**
 * Enqueue styles for the frontend.
 */
function msb_styles() {

	wp_enqueue_style(
		'msb-style',
		plugins_url( 'assets/css/minimal-share-buttons.css', __FILE__ ),
		[],
		filemtime( MSB_PLUGIN_BASE . 'assets/css/minimal-share-buttons.css' )
	);

}
add_action( 'wp_enqueue_scripts', 'msb_styles' );

/**
 * Add link to settings in plugins list item actions.
 *
 * @param string $links Action links in plugins list table.
 * @return string Modified action links.
 */
function msb_add_settings_link( $links ) {
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'options-general.php?page=minimal-share-buttons' ) ),
			esc_html__( 'Settings', 'minimal-share-buttons' )
		);
		array_unshift( $links, $settings_link );
		return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'msb_add_settings_link' );

/**
 * Register our widget with WordPress.
 */
function minimal_share_buttons_widget() {
	return register_widget( 'minimal_share_buttons' );
}
add_action( 'widgets_init', 'minimal_share_buttons_widget' );

/**
 * Show share widget after post content.
 *
 * @param string $content Post content.
 * @return string
 */
function msb_content_filter( $content ) {
	$content .= msb_display_buttons();
	return $content;
}

/**
 * Add filter to `the_content` to render the share widget.
 */
function msb_content_filter_init() {

	// Bail early, we'll add `the_content` filter only on single pages.
	if ( ! is_singular() ) {
		return;
	}

	$defaults = [
		'post' => true,
		'page' => false,
		'attachment' => true,
	];
	$msb_content_filter = get_option( 'msb_content_filter', $defaults );

	if ( ! is_array( $msb_content_filter ) ) {
		$msb_content_filter = $defaults;
	}

	$post_type = get_post_type();
	if ( ! $post_type ) {
		return;
	}

	$filter = isset( $msb_content_filter[ $post_type ] ) && $msb_content_filter[ $post_type ];

	if ( $filter ) {
		add_filter( 'the_content', 'msb_content_filter', 999 );
	}

}
add_action( 'loop_start', 'msb_content_filter_init' );

/**
 * Plugin init.
 */
function msb_init() {

	// Make plugin available for translation.
	// Translations can be filed in the /languages/ directory.
	load_plugin_textdomain( 'minimal-share-buttons', false, basename( __DIR__ ) . '/languages/' );

}
add_action( 'plugins_loaded', 'msb_init' );

/**
 * Uninstall hook to clear options.
 */
function msb_uninstall() {

	delete_option( 'msb_socials' );
	delete_option( 'msb_content_filter' );
	delete_option( 'msb_content_title' );

}
register_uninstall_hook( __FILE__, 'msb_uninstall' );

/**
 * SVG icon helper.
 *
 * @param string  $icon Icon name.
 * @param boolean $echo Echo the markup if true.
 * @return string SVG icon markup.
 */
function msb_icon( $icon, $echo = true ) {
	$icon = apply_filters( 'msb_icon_name', 'icon-' . $icon );
	$sprite_url = apply_filters( 'msb_sprite_url', MSB_PLUGIN_URL . 'assets/images/icons.svg' );
	$html = sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" class="icon" aria-hidden="true"><use xlink:href="%1$s"></use></svg>',
		esc_url( $sprite_url . '#' . $icon )
	);
	if ( $echo ) {
		echo $html; // WPCS: XSS OK.
	}
	return $html;
}

/**
 * Display the sharing buttons widget.
 *
 * @param array   $args Array of arguments to pass to the widget.
 * @param boolean $echo Echo the buttons markup if true.
 * @return string The sharing widget markup.
 */
function msb_display_buttons( $args = [], $echo = false ) {
	$widget_args = wp_parse_args(
		$args,
		[
			'before_widget' => '<div class="msb-container">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		]
	);

	$instance_args = [];
	if ( ! empty( $args['title'] ) ) {
		$instance_args = [
			'title' => $args['title'],
		];
	}
	$instance_args = wp_parse_args(
		$instance_args,
		[
			'title' => get_option( 'msb_content_title', __( 'Share this', 'minimal-share-buttons' ) ),
		]
	);

	ob_start();
	the_widget( 'minimal_share_buttons', $instance_args, $widget_args );
	$output = ob_get_clean();

	if ( $echo ) {
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	return $output;
}
add_shortcode( 'msb_share', 'msb_display_buttons' );
