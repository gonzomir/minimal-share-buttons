<?php

/**
 * Returns the list of available social networks.
 */
function msb_get_socials(){

  $socials = array(
    'facebook' => array(
      'field_label' => __( 'Facebook', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Facebook', 'minimal-share-buttons' ),
      'share_url' => 'http://facebook.com/sharer.php?u=%1$s&t=%2$s'
    ),
    'twitter' => array(
      'field_label' => __( 'Twitter', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Twitter', 'minimal-share-buttons' ),
      'share_url' => 'https://twitter.com/intent/tweet?url=%1$s&text=%2$s'
    ),
    'google-plus' => array(
      'field_label' => __( 'Google Plus', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on Google Plus', 'minimal-share-buttons' ),
      'share_url' => 'https://plus.google.com/share?url=%1$s&title=%2$s'
    ),
    'linkedin' => array(
      'field_label' => __( 'LinkedIn', 'minimal-share-buttons' ),
      'button_label' => __( 'Share on LinkedIn', 'minimal-share-buttons' ),
      'share_url' => 'https://www.linkedin.com/shareArticle?mini=true&amp;url=%1$s&title=%2$s'
    ),
  );

  return apply_filters( 'msb_socials', $socials );

}
