<?php

/**
 * custom option and settings
 */

class MsbSettings {

  private static $instance;

  public static function get_instance() {
    if ( null === self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public static function init() {
    $self = self::get_instance();
    add_action( 'wp_loaded', array( $self, 'load' ) );
  }

  public function load(){

    /**
     * register our msb_settings_init to the admin_init action hook
     */
    add_action( 'admin_init', array( $this, 'settings' ) );

    /**
     * register our msb_options_page to the admin_menu action hook
     */
    add_action( 'admin_menu', array( $this, 'options_page' ) );

  }

  public function settings() {

    register_setting( 'minimal-share-buttons', 'msb_socials' );

    add_settings_section(
      'msb_section_networks',
      __( 'Social networks', 'minimal-share-buttons' ),
      array( $this, 'section_networks' ),
      'minimal-share-buttons'
    );

    $socials = get_option(
      'msb_socials',
      array(
        'facebook' => false,
        'twitter' => false,
        'linkedin' => false,
        'pinterest' => false,
        'reddit' => false,
        'email' => false,
      )
    );

    add_settings_field(
      'msb_socials_facebook',
      __( 'Select social networks', 'minimal-share-buttons' ),
      array( $this, 'socials_fieldset' ),
      'minimal-share-buttons',
      'msb_section_networks',
      array(
        'value' => $socials
      )
    );

    register_setting( 'minimal-share-buttons', 'msb_content_filter' );
    register_setting( 'minimal-share-buttons', 'msb_content_title', 'sanitize_text_field' );

    add_settings_section(
      'msb_section_display',
      __( 'Display settings', 'minimal-share-buttons' ),
      array( $this, 'section_display' ),
      'minimal-share-buttons'
    );

    $msb_content_filter = get_option( 'msb_content_filter', array( 'post' => true, 'page' => false, 'attachment' => true ) );
    add_settings_field(
      'msb_content_filter',
      __( 'Show buttons under content', 'minimal-share-buttons' ),
      array( $this, 'post_types_fieldset' ),
      'minimal-share-buttons',
      'msb_section_display',
      array(
        'value' => $msb_content_filter
      )
    );

    add_settings_field(
      'msb_content_title',
      __( 'Title of the section under content', 'minimal-share-buttons' ),
      array( $this, 'text_field' ),
      'minimal-share-buttons',
      'msb_section_display',
      array(
        'label_for' => 'msb_content_title',
        'value' => get_option( 'msb_content_title',  __( 'Share this', 'minimal-share-buttons' ) )
      )
    );

  }

  /**
   * Render description text from the social networks settings section.
   */
  public static function section_networks( $args ) {
    ?>
    <p><?php esc_html_e( 'Select the social networks you wish your content to be shared on.', 'minimal-share-buttons' ); ?></p>
    <?php
  }

  /**
   * Render description text from the display setings section.
   */
  public static function section_display( $args ) {
    ?>
    <p><?php esc_html_e( 'Settings that control the appearance of the buttons.', 'minimal-share-buttons' ); ?></p>
    <?php
  }

  /**
   * Render checkbox field.
   */
  public static function checkbox_field( $args ) {

    ?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="true" <?php echo ( isset( $args['value'] ) && boolval( $args['value'] ) ) ? 'checked' : ''; ?> />
    <?php

  }

  /**
   * Render text field.
   */
  public static function text_field( $args ) {

    ?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $args['value'] ) ? esc_attr( $args['value'] ) : ''; ?>" />
    <?php

  }

  /**
   * Render social networks selection fieldset.
   */
  public static function socials_fieldset( $args ) {

    ?>
    <fieldset>
      <?php foreach( msb_get_socials() as $social => $attributes ): ?>
      <p>
        <input type="checkbox" id="msb_socials_<?php echo $social; ?>" name="msb_socials[<?php echo $social; ?>]" value="true" <?php echo ( isset( $args['value'][$social] ) && $args['value'][$social] ) ? 'checked' :  '' ; ?> />
        <label for="msb_socials_<?php echo $social; ?>"><?php echo esc_html( $attributes['field_label'] ); ?></label>
      </p>
      <?php endforeach; ?>
    </fieldset>
    <?php

  }

  /**
   * Render post types selection fieldset.
   */
  public static function post_types_fieldset( $args ) {

    $pt_args = array(
        'public' => true ,
        '_builtin' => true
    );
    $output = 'object';
    $operator = 'and';
    $post_types = get_post_types( $pt_args , $output , $operator );

    ?>
    <fieldset>
      <input type="hidden" name="msb_content_filter[none]" value="true" />
      <?php foreach( $post_types as $post_type ): ?>
      <p>
        <input type="checkbox" id="msb_content_filter_<?php echo esc_attr( $post_type->name ); ?>" name="msb_content_filter[<?php echo esc_attr( $post_type->name ); ?>]" value="true" <?php echo ( isset( $args['value'][$post_type->name] ) && $args['value'][$post_type->name] ) ? 'checked' :  '' ; ?> />
        <label for="msb_content_filter_<?php echo esc_attr( $post_type->name ); ?>"><?php echo esc_html( $post_type->labels->name ); ?></label>
      </p>
    <?php endforeach; ?>
    </fieldset>
    <?php

  }

  /**
   * Add submenu item to the Settings menu in WP admin.
   */
  public static function options_page() {
    add_submenu_page(
      'options-general.php',
      __( 'Share Options', 'minimal-share-buttons' ),
      __( 'Share Options', 'minimal-share-buttons' ),
      'manage_options',
      'minimal-share-buttons',
      array( MsbSettings::get_instance(), 'options_page_html' )
    );
  }

  /**
   * Render settings page
   */
  public static function options_page_html() {

    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
      return;
    }

    // show error/update messages
    // Looks like WordPress sets the messages automatically
    settings_errors( 'msb_messages' );

    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "minimal-share-buttons"
        settings_fields( 'minimal-share-buttons' );
        // output setting sections and their fields
        // (sections are registered for "minimal-share-buttons", each field is registered to a specific section)
        do_settings_sections( 'minimal-share-buttons' );
        // output save settings button
        submit_button( __( 'Save Settings', 'minimal-share-buttons' ) );
        ?>
      </form>
    </div>
    <?php

  }

}

MsbSettings::init();
