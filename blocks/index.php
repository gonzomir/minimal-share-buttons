<?php

/**
 * Registers block scripts so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function msb_register_block_editor_scripts() {
	/*
	 * Main block script.
	 */
	wp_register_script(
		'msb-share-block',
		plugins_url( 'index.js', __FILE__ ),
		[
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		],
		filemtime( plugin_dir_path( __FILE__ ) . 'index.js' ),
		true
	);

	/*
	 * Pass already loaded translations to our JavaScript.
	 * This happens _before_ our JavaScript runs, afterwards it's too late.
	 */
	if ( function_exists( 'gutenberg_get_jed_locale_data' ) ) {
		$locale_data = gutenberg_get_jed_locale_data( 'minimal-share-buttons' );
		wp_add_inline_script(
			'msb-share-block',
			'wp.i18n.setLocaleData(' . wp_json_encode( $locale_data ) . ', "minimal-share-buttons");',
			'before'
		);
	} elseif ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'msb-share-block', 'minimal-share-buttons' );
	}

	/*
	 * Register our block for server-side rendering.
	 */
	register_block_type(
		'msb/share',
		[
			'editor_script' => 'msb-share-block',
			'editor_style' => 'msb-style',
			'attributes' => [
				'title' => [
					'type' => 'string',
					'default' => __( 'Share', 'minimal-share-buttons' ),
				],
				'align' => [
					'type' => 'string',
					'defailt' => 'none',
				],
			],
			'render_callback' => 'msb_render_block_share',
		]
	);

}
add_action( 'init', 'msb_register_block_editor_scripts' );

/**
 * Enqueue styles in the block editor too.
 */
add_action( 'enqueue_block_editor_assets', 'msb_styles' );

/**
 * Renders the `msb/share` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string
 */
function msb_render_block_share( $attributes ) {

	$title = __( 'Share', 'minimal-share-buttons' );

	if ( array_key_exists( 'title', $attributes ) ) {
		$title = trim( $attributes['title'] );
	}

	$align = $attributes['align'] ?? 'none';
	$container_classes = [
		'msb-container',
		'align' . $align,
	];

	$args = [
		'before_widget' => sprintf( '<div class="%s">', esc_attr( join( ' ', $container_classes ) ) ),
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
		'title'         => $title,
	];

	return msb_display_buttons( $args );
}
