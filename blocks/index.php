<?php
/**
 * `msb/share` block.
 *
 * @package minimal-share-buttons
 */

/**
 * Enqueue block main JavaScript file
 */
function msb_enqueue_block_editor_assets() {
  wp_enqueue_script(
    'msb-share-block',
    plugins_url( 'index.js', __FILE__ ),
    array( 'wp-blocks', 'wp-element' )
  );
}
add_action( 'enqueue_block_editor_assets', 'msb_enqueue_block_editor_assets' );

/**
 * Enqueue styles in the block editor too.
 */
add_action( 'enqueue_block_editor_assets', 'msb_styles' );

/**
 * Register our block for server-side rendering.
 */
register_block_type( 'msb/share', array(
  'attributes'      => array(
      'title' => [
        'type' => 'string',
        'default' => __( 'Share', 'minimal-share-buttons' ),
      ],
      'align' => [
        'type' => 'string',
        'defailt' => 'none',
      ],
  ),
  'render_callback' => 'msb_render_block_share',
) );

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

  $align = array_key_exists( 'align', $attributes ) ? $attributes['align'] : 'none';

  $widget_args = array(
    'before_widget' => '<aside class="msb-container align' . $align . '">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  );
  $instance_args = array(
    'title' => $title
  );
  ob_start();
  the_widget( 'minimal_share_buttons', $instance_args, $widget_args );
  $block_content = ob_get_contents();
  ob_end_clean();

  return preg_replace( '/[\n\r]+/i', ' ', $block_content );
}

