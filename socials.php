<?php

/**
 * Returns the list of available social networks.
 */
function msb_get_socials(){

  $socials = array(
    'facebook' => array(
      'field_label' => __( 'Facebook', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Facebook', 'minimal-share-buttons' ),
      'share_url' => 'https://facebook.com/sharer.php?u=%1$s&t=%2$s',
    ),
    'twitter' => array(
      'field_label' => __( 'Twitter', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Twitter', 'minimal-share-buttons' ),
      'share_url' => 'https://twitter.com/intent/tweet?url=%1$s&text=%2$s',
    ),
    'linkedin' => array(
      'field_label' => __( 'LinkedIn', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on LinkedIn', 'minimal-share-buttons' ),
      'share_url' => 'https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s',
    ),
    'pinterest' => array(
      'field_label' => __( 'Pinterest', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Pinterest', 'minimal-share-buttons' ),
      'share_url' => 'https://pinterest.com/pin/create/button/?url=%1&s&description=%2$s',
    ),
    'reddit' => array(
      'field_label' => __( 'Reddit', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Reddit', 'minimal-share-buttons' ),
      'share_url' => 'http://www.reddit.com/submit?url=%1$s&title=%2$s',
    ),
    'email' => array(
      'field_label' => __( 'Email', 'minimal-share-buttons' ),
      'button_label' => __( 'Share by email', 'minimal-share-buttons' ),
      'share_url' => 'mailto:?body=%1$s&subject=%2$s',
    ),
  );

  return apply_filters( 'msb_socials', $socials );

}
