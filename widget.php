<?php

/**
 * Minimal Share Buttons widget
 */
class minimal_share_buttons extends WP_Widget {

  /**
   * Constructor
   */
  function __construct() {
    parent::__construct(
      'minimal_share_buttons',
      $name = __( 'Share', 'minimal-share-buttons' ),
      array(
        'customize_selective_refresh' => true,
      )
    );
  }

  /**
   * This is the Widget
   */
  function widget( $args, $instance ) {
    global $post;
    extract( $args );

    // Widget options
    if ( array_key_exists( 'title', $instance ) ) {
      $title = apply_filters( 'widget_title', $instance['title'] ); // Title
    }
    else {
      $title = '';
    }

    echo $before_widget;
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }

    $options = get_option(
      'msb_socials',
      array(
        'facebook' => false,
        'twitter' => false,
        'google-plus' => false,
        'linkedin' => false,
        'pinterest' => false,
        'reddit' => false,
        'email' => false,
      )
    );
    ?>
    <p>
      <?php foreach( msb_get_socials() as $social => $attributes ): ?>
        <?php if ( array_key_exists( $social, $options ) && $options[ $social ] ) : ?>
          <a href="<?php echo esc_url( sprintf( $attributes['share_url'], urlencode( get_permalink() ), urlencode( the_title_attribute( 'echo=0' ) ) ) ); ?>" target="_blank" class="minimal-share-button" aria-label="<?php echo esc_html( $attributes['button_label'] ); ?>" rel="noopener"><?php msb_icon($social . '-square', true); ?></a>
        <?php endif; ?>

      <?php endforeach; ?>
    </p>
    <?php

    echo $after_widget;
  }

  /**
   * Widget control update
   */
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['title']  = strip_tags( $new_instance['title'] );

    return $instance;
  }

  /**
   * Widget settings
   */
  function form( $instance ) {
    // instance exist? if not set defaults
    if ( $instance ) {
      $title = $instance['title'];
    } else {
      //These are our defaults
      $title = __( 'Share', 'minimal-share-buttons' );
    }

    // The widget form ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'minimal-share-buttons' ); ?></label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
    </p>
<?php
  }

}
add_action( 'widgets_init', 'minimal_share_buttons_widget' );

/**
 * Register our widget with WordPress.
 */
function minimal_share_buttons_widget() {
  return register_widget( 'minimal_share_buttons' );
}
