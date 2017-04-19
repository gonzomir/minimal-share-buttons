<?php

function minimal_share_buttons_widget() {
  return register_widget('minimal_share_buttons');
}

class minimal_share_buttons extends WP_Widget {
  /** constructor */
  function __construct() {
    parent::__construct( 'minimal_share_buttons', $name = __('Share widget', 'minimal-share-buttons'), array(
      'customize_selective_refresh' => true,
    ) );
  }

  /**
  * This is the Widget
  **/
  function widget( $args, $instance ) {
    global $post;
    extract($args);

    // Widget options
    if ( array_key_exists( 'title', $instance ) ) {
      $title = apply_filters('widget_title', $instance['title'] ); // Title
    } else {
      $title = '';
    }

    echo $before_widget;
    if ( $title ) echo $before_title . $title . $after_title;

    $options = get_option( 'msb_options' );

    ?>
    <p>
      <?php if ( array_key_exists( 'msb_socials_facebook', $options ) && $options[ 'msb_socials_facebook' ] ) : ?>
        <a href="http://facebook.com/sharer.php?u=<?php echo esc_attr(  get_permalink() ); ?>&amp;t=<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" target="_blank" class="minimal-share-button" aria-label="<?php esc_html_e( 'Share on Facebook', 'minimal-share-buttons' ); ?>"><?php msb_icon('facebook-square', true); ?></a>
      <?php endif; ?>
      <?php if ( array_key_exists( 'msb_socials_twitter', $options ) && $options[ 'msb_socials_twitter' ] ) : ?>
        <a href="https://twitter.com/share?text=<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" target="_blank" class="minimal-share-button" aria-label="<?php esc_html_e( 'Share on Twitter', 'minimal-share-buttons' ); ?>"><?php msb_icon('twitter-square', true); ?></a>
      <?php endif; ?>
      <?php if ( array_key_exists( 'msb_socials_linkedin', $options ) && $options[ 'msb_socials_linkedin' ] ) : ?>
        <a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_attr(  get_permalink() ); ?>&title=<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" target="_blank" class="minimal-share-button" aria-label="<?php esc_html_e( 'Share on LinkedIn', 'minimal-share-buttons' ); ?>"><?php msb_icon('linkedin-square', true); ?></a>
      <?php endif; ?>
      <?php if ( array_key_exists( 'msb_socials_gplus', $options ) && $options[ 'msb_socials_gplus' ] ) : ?>
        <a href="https://plus.google.com/share?url=<?php echo esc_attr(  get_permalink() ); ?>&amp;title=<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" target="_blank" class="minimal-share-button" aria-label="<?php esc_html_e( 'Share on Google Plus', 'minimal-share-buttons' ); ?>"><?php msb_icon('google-plus-square', true); ?></a>
      <?php endif; ?>
    </p>
    <?php

    echo $after_widget;
  }
  /** Widget control update */
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['title']  = strip_tags( $new_instance['title'] );

    return $instance;
  }

  /**
  * Widget settings
  **/
  function form( $instance ) {
      // instance exist? if not set defaults
      if ( $instance ) {
        $title  = $instance['title'];
      } else {
          //These are our defaults
        $title  = __('Share', 'minimal-share-buttons');
      }

      // The widget form ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:' ); ?></label>
        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
      </p>
<?php
  }

}
add_action( 'widgets_init', 'minimal_share_buttons_widget' );
