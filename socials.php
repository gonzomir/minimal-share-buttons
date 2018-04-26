<?php

class MsbSocials {

  public $socials;

  private static $instance;

  private function __construct(){
    add_action('wp_loaded', array($this, 'load'));
  }

  public static function get_instance(){
      if (null === self::$instance) {
          self::$instance = new self();
      }

      return self::$instance;
  }

  public function load(){

    $socials = array(
      'facebook' => array(
          'field_label' => __( 'Facebook', 'minimal-share-buttons' ),
          'button_label' => __(  'Share on Facebook', 'minimal-share-buttons' ),
          'share_url' => 'http://facebook.com/sharer.php?u=%1$s&t=%2$s'
        ),
      'twitter' => array(
          'field_label' => __( 'Twitter', 'minimal-share-buttons' ),
          'button_label' => __(  'Share on Twitter', 'minimal-share-buttons' ),
          'share_url' => 'https://twitter.com/intent/tweet?url=%1$s&text=%2$s'
        ),
      'google-plus' => array(
          'field_label' => __( 'Google Plus', 'minimal-share-buttons' ),
          'button_label' => __(  'Share on Google Plus', 'minimal-share-buttons' ),
          'share_url' => 'https://plus.google.com/share?url=%1$s&title=%2$s'
        ),
      'linkedin' => array(
          'field_label' => __( 'LinkedIn', 'minimal-share-buttons' ),
          'button_label' => __(  'Share on LinkedIn', 'minimal-share-buttons' ),
          'share_url' => 'https://www.linkedin.com/shareArticle?mini=true&amp;url=%1$s&title=%2$s'
        )
    );

    $this->socials = apply_filters( 'msb_socials', $socials );

  }

}

// We need to create our instance early, so that when filters are called, we have it.
MsbSocials::get_instance();

?>
