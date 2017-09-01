<?php
/**
 * Server-side rendering of the `msb/share` block.
 *
 * @package minimal-share-buttons
 */
/**
 * Renders the `msb/share` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns the post content with share widget added.
 */
function msb_render_block_share( $attributes ) {

  error_log( 'DEBUG OUTPUT STARTS HERE' );
  error_log( print_r( $attributes, true ) );
  error_log( 'DEBUG OUTPUT ENDS HERE' );

  $title = __('Share', 'minimal-share-buttons');

  if ( array_key_exists( 'blockTitle', $attributes ) ) {
    $title = trim( $attributes['blockTitle'] );
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
register_block_type( 'msb/share', array(
  'render_callback' => 'msb_render_block_share',
) );
