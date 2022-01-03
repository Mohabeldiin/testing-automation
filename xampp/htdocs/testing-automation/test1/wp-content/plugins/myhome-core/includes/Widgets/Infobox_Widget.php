<?php

namespace MyHomeCore\Widgets;

class Infobox_Widget extends \WP_Widget {

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
			'classname'   => 'widget-mh-infobox',
			'description' => esc_html__( 'Display image and text', 'myhome-core' )
		);
		parent::__construct( 'myhome-image-widget', esc_html__( 'MH Infobox', 'myhome-core' ), $widget_ops );
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

		$title            = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$image_url        = isset( $instance['image_url'] ) ? $instance['image_url'] : '';
		$link_url         = isset( $instance['link_url'] ) ? $instance['link_url'] : '#';
		$text             = isset( $instance['text'] ) ? $instance['text'] : '';
		$more_button      = isset( $instance['more_button'] ) ? $instance['more_button'] : 'More';
		$show_more_button = isset( $instance['show_more_button'] ) ? $instance['show_more_button'] : 1;

		echo myhome_filter( $before_widget );

		if ( ! empty( $title ) ) {
			echo myhome_filter( $before_title . $title . $after_title );
		}
		?>
		<div class="widget-infobox"><?php

			if ( ! empty( $link_url ) ) : ?>
			<a href="<?php echo esc_url( $link_url ); ?>" title="<?php echo esc_attr( $title ); ?>" class="widget-infobox__image-wrapper">
				<?php endif; ?>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="widget-infobox__image">
				<?php if ( ! empty( $link_url ) ) : ?>
			</a>
		<?php endif;

		if ( ! empty( $text ) ) : ?>
			<div class="widget-infobox__text"><?php echo wp_kses_post( $text ); ?></div>
		<?php endif;

		if ( $show_more_button ) : ?>
			<div class="widget-infobox__btn">
				<a class="mdl-button mdl-js-button mdl-button--raised" href="<?php echo esc_url( $link_url ); ?>" title="<?php echo esc_attr( $title ); ?>">
					<?php echo esc_html( $more_button ); ?>
				</a>
			</div>
		<?php endif; ?>
		</div>
		<?php
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
		// prepare options
		$instance = wp_parse_args(
			(array) $instance, array(
				'title'            => '',
				'image_title'      => '',
				'image_url'        => '',
				'link_url'         => '',
				'text'             => '',
				'more_button'      => 'More',
				'show_more_button' => 1
			)
		);

		$title            = $instance['title'];
		$image_title      = $instance['image_title'];
		$image_url        = $instance['image_url'];
		$link_url         = $instance['link_url'];
		$text             = $instance['text'];
		$show_more_button = $instance['show_more_button'];
		$more_button      = $instance['more_button'];

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>"><?php esc_html_e( 'Image url ( jpg / png / gif ):', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="text" value="<?php echo esc_url( $image_url ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>"><?php esc_html_e( 'Link ( url: ):', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_url' ) ); ?>" type="text" value="<?php echo esc_url( $link_url ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'myhome-core' ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_html( $text ); ?></textarea>
		</p>
		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_more_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_more_button' ) ); ?>" type="checkbox" value="1" <?php checked( $show_more_button, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_more_button' ) ); ?>"><?php esc_html_e( 'Display button', 'myhome-core' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'more_button' ) ); ?>"><?php esc_html_e( 'Button text:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_button' ) ); ?>" type="text" value="<?php echo esc_attr( $more_button ); ?>" />
		</p>
		<?php
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
		$instance                     = $old_instance;
		$instance['title']            = strip_tags( $new_instance['title'] );
		$instance['image_url']        = strip_tags( $new_instance['image_url'] );
		$instance['link_url']         = strip_tags( $new_instance['link_url'] );
		$instance['text']             = strip_tags( $new_instance['text'] );
		$instance['more_button']      = strip_tags( $new_instance['more_button'] );
		$instance['show_more_button'] = intval( $new_instance['show_more_button'] );

		return $instance;
	}

}
