<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function msb_settings_init() {
  // register a new setting for "minimal-share-buttons" page
  register_setting( 'minimal-share-buttons', 'msb_options' );

  // register a new section in the "minimal-share-buttons" page
  add_settings_section(
    'msb_section_networks',
    __( 'Social networks', 'minimal-share-buttons' ),
    'msb_section_networks',
    'minimal-share-buttons'
  );

  // register a new field in the "msb_section_developers" section, inside the "minimal-share-buttons" page
  add_settings_field(
    'msb_socials_facebook', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Facebook', 'minimal-share-buttons' ),
    'msb_socials_field',
    'minimal-share-buttons',
    'msb_section_networks',
    [
      'label_for' => 'msb_socials_facebook',
      'class' => ''
    ]
  );

  add_settings_field(
    'msb_socials_twitter', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Twitter', 'minimal-share-buttons' ),
    'msb_socials_field',
    'minimal-share-buttons',
    'msb_section_networks',
    [
      'label_for' => 'msb_socials_twitter',
      'class' => ''
    ]
  );

  add_settings_field(
    'msb_socials_gplus', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Google Plus', 'minimal-share-buttons' ),
    'msb_socials_field',
    'minimal-share-buttons',
    'msb_section_networks',
    [
      'label_for' => 'msb_socials_gplus',
      'class' => ''
    ]
  );

  add_settings_field(
    'msb_socials_linkedin', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'LinkedIn', 'minimal-share-buttons' ),
    'msb_socials_field',
    'minimal-share-buttons',
    'msb_section_networks',
    [
      'label_for' => 'msb_socials_linkedin',
      'class' => ''
    ]
  );


  add_settings_section(
    'msb_section_display',
    __( 'Display settings', 'minimal-share-buttons' ),
    'msb_section_display',
    'minimal-share-buttons'
  );

  // register a new field in the "msb_section_developers" section, inside the "minimal-share-buttons" page
  add_settings_field(
    'msb_content_filter', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Show buttons under content', 'minimal-share-buttons' ),
    'msb_socials_field',
    'minimal-share-buttons',
    'msb_section_display',
    [
      'label_for' => 'msb_content_filter',
      'class' => ''
    ]
  );


}

/**
 * register our msb_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'msb_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

function msb_section_networks( $args ) {
  ?>
  <p><?php esc_html_e( 'Select the social networks you wish your content to be shared on.', 'minimal-share-buttons' ); ?></p>
  <?php
}


function msb_section_display( $args ) {
  ?>
  <p><?php esc_html_e( 'Settings that control the appearance of the buttons.', 'minimal-share-buttons' ); ?></p>
  <?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function msb_socials_field( $args ) {
  // get the value of the setting we've registered with register_setting()
  $options = get_option( 'msb_options' );
  // output the field
  ?>
  <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="msb_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $args['label_for'] ); ?>" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], $args['label_for'], false ) ) : ( '' ); ?> />
  <?php
}

/**
 * top level menu
 */
function msb_options_page() {
  // add top level menu page
  add_submenu_page(
    'options-general.php',
    'Share Options',
    'Share Options',
    'manage_options',
    'minimal-share-buttons',
    'msb_options_page_html'
  );
}

/**
 * register our msb_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'msb_options_page' );

/**
 * top level menu:
 * callback functions
 */
function msb_options_page_html() {
  // check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  // add error/update messages

  // check if the user have submitted the settings
  // wordpress will add the "settings-updated" $_GET parameter to the url
  if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    //add_settings_error( 'msb_messages', 'msb_message', __( 'Settings Saved', 'minimal-share-buttons' ), 'updated' );
  }

  // show error/update messages
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
      submit_button( 'Save Settings' );
      ?>
    </form>
  </div>
  <?php
}
