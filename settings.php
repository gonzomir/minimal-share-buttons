<?php

/**
 * custom option and settings
 */
function msb_settings_init() {

  register_setting( 'minimal-share-buttons', 'msb_socials' );

  add_settings_section(
    'msb_section_networks',
    __( 'Social networks', 'minimal-share-buttons' ),
    'msb_section_networks',
    'minimal-share-buttons'
  );

  $socials = get_option( 'msb_socials', array( 'facebook' => false, 'twitter' => false, 'gplus' => false, 'linkedin' => false ) );

  add_settings_field(
    'msb_socials_facebook',
    __( 'Select social networks', 'minimal-share-buttons' ),
    'msb_socials_fieldset',
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
    'msb_section_display',
    'minimal-share-buttons'
  );

  $msb_content_filter = get_option( 'msb_content_filter', array( 'post' => true, 'page' => false, 'attachment' => true ) );
  add_settings_field(
    'msb_content_filter',
    __( 'Show buttons under content', 'minimal-share-buttons' ),
    'msb_post_types_fieldset',
    'minimal-share-buttons',
    'msb_section_display',
    array(
      'value' => $msb_content_filter
    )
  );

  add_settings_field(
    'msb_content_title',
    __( 'Title of the section under content', 'minimal-share-buttons' ),
    'msb_text_field',
    'minimal-share-buttons',
    'msb_section_display',
    array(
      'label_for' => 'msb_content_title',
      'value' => get_option( 'msb_content_title',  __( 'Share this', 'minimal-share-buttons' ) )
    )
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


function msb_checkbox_field( $args ) {

  ?>
  <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="true" <?php echo ( isset( $args['value'] ) && boolval( $args['value'] ) ) ? 'checked' : ''; ?> />
  <?php

}

function msb_text_field( $args ) {

  $options = get_option( 'msb_options' );

  ?>
  <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $args['value'] ) ? esc_attr( $args['value'] ) : ''; ?>" />
  <?php

}

function msb_socials_fieldset( $args ) {

  $socials = array( 'facebook', 'twitter', 'gplus', 'linkedin' );
  $labels = array(
    'facebook' => __( 'Facebook', 'minimal-share-buttons' ),
    'twitter' => __( 'Twitter', 'minimal-share-buttons' ),
    'gplus' => __( 'Google Plus', 'minimal-share-buttons' ),
    'linkedin' => __( 'LinkedIn', 'minimal-share-buttons' )
    );
  ?>
  <fieldset>
    <?php foreach( $socials as $social ): ?>
    <p>
      <input type="checkbox" id="msb_socials_<?php echo $social; ?>" name="msb_socials[<?php echo $social; ?>]" value="true" <?php echo ( isset( $args['value'][$social] ) && $args['value'][$social] ) ? 'checked' :  '' ; ?> />
      <label for="msb_socials_<?php echo $social; ?>"><?php echo esc_html( $labels[$social] ); ?></label>
    </p>
  <?php endforeach; ?>
  </fieldset>
  <?php

}

function msb_post_types_fieldset( $args ) {

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
      <input type="checkbox" id="msb_content_filter_<?php echo $post_type->name; ?>" name="msb_content_filter[<?php echo $post_type->name; ?>]" value="true" <?php echo ( isset( $args['value'][$post_type->name] ) && $args['value'][$post_type->name] ) ? 'checked' :  '' ; ?> />
      <label for="msb_content_filter_<?php echo $post_type->name; ?>"><?php echo esc_html( $post_type->labels->name ); ?></label>
    </p>
  <?php endforeach; ?>
  </fieldset>
  <?php

}


function msb_options_page() {
  add_submenu_page(
    'options-general.php',
    __( 'Share Options', 'minimal-share-buttons' ),
    __( 'Share Options', 'minimal-share-buttons' ),
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
 * Render settings page
 */
function msb_options_page_html() {

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
