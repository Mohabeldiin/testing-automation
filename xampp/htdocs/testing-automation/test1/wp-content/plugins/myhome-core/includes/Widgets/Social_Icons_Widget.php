<?php

namespace MyHomeCore\Widgets;


use MyHomeCore\Core;

class Social_Icons_Widget extends \WP_Widget {

	// vars
	private $icons = array(
		'facebook'   => 'facebook',
		'twitter'    => 'twitter',
		'instagram'  => 'instagram',
		'linkedin'   => 'linkedin',
		'youtube'    => 'youtube',
		'pinterest'  => 'pinterest',
		'slideshare' => 'slideshare',
		'vimeo'      => 'vimeo',
		'dropbox'    => 'dropbox',
		'google'     => 'google'
	);

	/**
	 * construct
	 *
	 * Set widget name
	 *
	 * @date  3.07.2016
	 * @since 1.0.0
	 *
	 * @param N /A
	 *
	 * @return N/A
	 *
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-mh-social-icons',
			'description' => esc_html__( 'Display social icons', 'myhome-core' )
		);
		parent::__construct( 'myhome-social-icons-widget', esc_html__( 'MH Social Icons', 'myhome-core' ), $widget_ops );
	}

	/**
	 * widget
	 *
	 * Outputs the content of the widget
	 *
	 * @date   3.07.2016
	 * @since  1.0.0
	 *
	 * @param  (array)
	 * @param  (array)
	 *
	 * @return N/A
	 *
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$instance['title'] = apply_filters(
				'wpml_translate_single_string',
				$instance['title'],
				esc_html__( 'MyHome - Widgets', 'myhome-core' ),
				esc_html__( 'Icons widget', 'myhome-core' )
			);
		}
		$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

		echo myhome_filter( $before_widget );

		if ( ! empty( $title ) ) {
			echo myhome_filter( $before_title . $title . $after_title );
		}

		?>
		<div class="mh-social-icons"><?php

		foreach ( $this->icons as $key => $icon ) :
			$i = isset( $instance[ $key ] ) ? $instance[ $key ] : '';
			if ( ! empty( $i ) ) :
				?><a class="mh-social-icon" target="_blank" href="<?php echo esc_url( $i ); ?>">
				<i class="fab fa-<?php echo esc_attr( $icon ); ?>"></i></a><?php
			endif;
		endforeach;

		?></div><?php

		echo myhome_filter( $after_widget );
	}

	/**
	 * form
	 *
	 * Outputs the options form on admin
	 *
	 * @date   3.07.2016
	 * @since  1.0.0
	 *
	 * @param  (array)
	 *
	 * @return N/A
	 *
	 */
	public function form( $instance ) {
		$defaults = array(
			'title' => ''
		);
		foreach ( $this->icons as $key => $icon ) {
			$defaults[ $key ] = '';
		}
		// prepare options
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php
		foreach ( $this->icons as $key => $icon ) : ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $key ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ $key ] ); ?>" />
			</p>
		<?php
		endforeach;
	}

	/**
	 * update
	 *
	 * Processing widget options on save
	 *
	 * @date   3.07.2016
	 * @since  1.0.0
	 *
	 * @param $new_instance (array)
	 * @param $old_instance (array)
	 *
	 * @return $instance (array)
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			do_action(
				'wpml_register_single_string',
				esc_html__( 'MyHome - Widgets', 'myhome-core' ),
				esc_html__( 'Icons widget', 'myhome-core' ),
				$instance['title']
			);
		}

		foreach ( $this->icons as $key => $icon ) {
			$instance[ $key ] = strip_tags( $new_instance[ $key ] );
		}

		return $instance;
	}

}

