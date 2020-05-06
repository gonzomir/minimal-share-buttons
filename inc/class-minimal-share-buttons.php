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
				'native' => false,
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
			<?php
			if ( empty( $socials ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					printf(
						/* translators: %s is settings page address */
						__( 'To configure sharing options <a href="%s">go to the settings page</a>.', 'minila-share-buttons' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						esc_url( admin_url( 'options-general.php?page=minimal-share-buttons' ) )
					);
				}
			} else {
				$share_title = html_entity_decode ( get_the_title() );

				if ( $options['native'] ) {
					$classes = [
						'minimal-share-button',
						'msb-native-share',
					];
					$classes = apply_filters( 'msb_button_classes', $classes, 'native' );

					printf(
						'<button type="button" data-url="%1$s" data-title="%2$s" class="%3$s" aria-label="%4%s">%5$s</button>',
						esc_attr( get_permalink() ),
						esc_attr( $share_title ),
						esc_attr( join( ' ', $classes ) ),
						esc_attr( 'Share', 'msb' ),
						msb_icon( 'share-square', false )
					);
				}

				foreach ( $socials as $social => $attributes ) {
					$share_link = sprintf(
						$attributes['share_url'],
						rawurlencode( get_permalink() ),
						rawurlencode( $share_title )
					);

					$classes = [
						'minimal-share-button',
						'msb-' . $social,
					];
					$classes = apply_filters( 'msb_button_classes', $classes, $social );

					printf(
						'<a href="%1$s" target="_blank" class="%2$s" aria-label="%3$s" rel="noopener">%4$s</a>',
						esc_url( $share_link ),
						esc_attr( join( ' ', $classes ) ),
						esc_attr( $attributes['button_label'] ),
						msb_icon( $social . '-square', false )
					);
				}
			}
			?>
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
