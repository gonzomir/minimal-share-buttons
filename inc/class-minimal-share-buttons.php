<?php

/**
 * Minimal Share Buttons widget
 */
class Minimal_Share_Buttons extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'minimal_share_buttons',
			$name = __( 'Share', 'minimal-share-buttons' ),
			[
				'customize_selective_refresh' => true,
			]
		);
	}

	/**
	 * Render the widget.
	 *
	 * @param array $args Args array, passed from dynamic_sidebar().
	 * @param array $instance Instance arguments from widget settings.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		global $post;

		$options = get_option(
			'msb_socials',
			[
				'facebook' => false,
				'twitter' => false,
				'google-plus' => false,
				'linkedin' => false,
				'pinterest' => false,
				'reddit' => false,
				'email' => false,
			]
		);

		$socials = array_filter(
			msb_get_socials(),
			function( $social ) use ( $options ) {
				return ! empty( $options[ $social ] );
			},
			ARRAY_FILTER_USE_KEY
		);

		// Bail if nothing to show.
		if ( empty( $socials ) && ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Widget options.
		if ( array_key_exists( 'title', $instance ) ) {
			$title = apply_filters( 'widget_title', $instance['title'] ); // Title.
		} else {
			$title = '';
		}

		echo $args['before_widget']; // WPCS: XSS OK.
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // WPCS: XSS OK.
		}

		?>
		<p>
			<?php if ( empty( $socials ) ) : ?>
				<?php
				printf(
					/* translators: %s is settings page address */
					__( 'To configure sharing options <a href="%s">go to the settings page</a>.', 'minila-share-buttons' ),
					esc_url( admin_url( 'options-general.php?page=minimal-share-buttons' ) )
				);
				?>
			<?php else : ?>
				<?php foreach ( $socials as $social => $attributes ) : ?>
					<a href="<?php echo esc_url( sprintf( $attributes['share_url'], rawurlencode( get_permalink() ), rawurlencode( the_title_attribute( 'echo=0' ) ) ) ); ?>" target="_blank" class="minimal-share-button" aria-label="<?php echo esc_html( $attributes['button_label'] ); ?>" rel="noopener"><?php msb_icon( $social . '-square', true ); ?></a>
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<?php

		echo $args['after_widget']; // WPCS: XSS OK.
	}

	/**
	 * Widget control update
	 *
	 * @param array $new_instance New widget setttings.
	 * @param array $old_instance Old widget setings.
	 * @return array Widget setings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Render widget settings form.
	 *
	 * @param array $instance Current widget setttings.
	 * @return void
	 */
	public function form( $instance ) {
		// Instance exist? If not set defaults.
		if ( $instance ) {
			$title = $instance['title'];
		} else {
			// These are our defaults.
			$title = __( 'Share', 'minimal-share-buttons' );
		}

		// Render the widget form.
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'minimal-share-buttons' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>
		<?php
	}

}
