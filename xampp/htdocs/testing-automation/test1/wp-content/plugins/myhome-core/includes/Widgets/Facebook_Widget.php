<?php

namespace MyHomeCore\Widgets;


class Facebook_Widget extends \WP_Widget {

	/**
	 * construct
	 *
	 * Set widget name
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param N /A
	 *
	 * @return N/A
	 *
	 */
	public function __construct() {
		$widget_opts = array(
			'classname'   => 'widget-mh-facebook',
			'description' => esc_html__( 'Display facebook page', 'myhome-core' ),
		);
		parent::__construct( 'myhome-facebook-widget', esc_html__( 'MH Facebook', 'myhome-core' ), $widget_opts );
	}

	/**
	 * widget
	 *
	 * Outputs the content of the widget
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param $args     (array)
	 * @param $instance (array)
	 *
	 * @return N/A
	 *
	 */
	public function widget( $args, $instance ) {
		$widget_data = array_merge(
			array(
				'title'              => '',
				'page_url'           => '',
				'small_header'       => 0,
				'hide_cover_photo'   => 0,
				'show_friends_faces' => 1,
				'show_timeline'      => 1,
				'page_height'        => 500,
				'lang_code'          => 'en_us'
			), $instance
		);

		extract( $args );

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$widget_data['title'] = apply_filters(
				'wpml_translate_single_string',
				$widget_data['title'],
				esc_html__( 'MyHome - Widgets', 'myhome-core' ),
				esc_html__( 'Facebook widget', 'myhome-core' )
			);
		}

		$title = apply_filters( 'widget_title', $widget_data['title'] );

		$options = array();
		// options for facebook page plugin
		$options['page_url']           = $widget_data['page_url'];
		$options['page_height']        = intval( $widget_data['page_height'] );
		$options['small_header']       = $widget_data['small_header'] ? 'true' : 'false';
		$options['hide_cover_photo']   = $widget_data['hide_cover_photo'] ? 'true' : 'false';
		$options['show_friends_faces'] = $widget_data['show_friends_faces'] ? 'true' : 'false';
		$options['show_timeline']      = $widget_data['show_timeline'] ? 'timeline' : '';
		$options['lang_code']          = $widget_data['lang_code'];

		if ( ! empty( $before_widget ) ) {
			echo myhome_filter( $before_widget );
		}

		if ( ! empty( $title ) ) {
			if ( ! empty( $before_title ) ) {
				echo myhome_filter( $before_title );
			}
			echo esc_html( $title );

			if ( ! empty( $after_title ) ) {
				echo myhome_filter( $after_title );
			}
		}
		?>
		<div class="mh-widget-facebook">

			<div class="mh-widget-facebook">
				<div id="fb-root"></div>
				<script>(function (d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s);
						js.id = id;
						js.src = 'https://connect.facebook.net/<?php echo esc_attr( $options['lang_code'] ); ?>/sdk.js#xfbml=1&version=v3.0';
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
				<div
					class="fb-page"
					data-href="<?php echo esc_url( $options['page_url'] ); ?>"
					data-tabs="<?php echo esc_attr( $options['show_timeline'] ); ?>"
					data-small-header="<?php echo esc_attr( $options['small_header'] ); ?>"
					data-adapt-container-width="true"
					data-hide-cover="<?php echo esc_attr( $options['hide_cover_photo'] ); ?>"
					data-show-facepile="<?php echo esc_attr( $options['show_friends_faces'] ); ?>"
					data-height="<?php echo esc_attr( $options['page_height'] ); ?>"
				>
				</div>
			</div>

		</div>
		<?php
		if ( ! empty( $after_widget ) ) {
			echo myhome_filter( $after_widget );
		}
	}

	/**
	 * form
	 *
	 * Outputs the options form on admin
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param $instance (array)
	 *
	 * @return N/A
	 *
	 */
	public function form( $instance ) {
		// prepare options
		$instance = wp_parse_args(
			(array) $instance, array(
				'title'              => '',
				'page_url'           => '',
				'small_header'       => 0,
				'hide_cover_photo'   => 0,
				'show_friends_faces' => 1,
				'show_timeline'      => 1,
				'page_height'        => 500,
				'lang_code'          => 'en_US'
			)
		);

		$title              = $instance['title'];
		$page_url           = $instance['page_url'];
		$page_height        = $instance['page_height'];
		$small_header       = $instance['small_header'];
		$hide_cover_photo   = $instance['hide_cover_photo'];
		$show_friends_faces = $instance['show_friends_faces'];
		$show_timeline      = $instance['show_timeline'];
		$lang_code          = $instance['lang_code'];

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'myhome - core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>"><?php esc_html_e( 'Page( url ):', 'myhome - core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_url' ) ); ?>" type="text" value="<?php echo esc_url( $page_url ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'lang_code' ) ); ?>"><?php esc_html_e( 'Language code', 'myhome - core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'lang_code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lang_code' ) ); ?>" type="text" value="<?php echo esc_attr( $lang_code ); ?>" />
			<?php esc_html_e( 'Read more about it ', 'myhome - core' ); ?>
			<a href="https://developers.facebook.com/docs/internationalization#locales" target="_blank"><?php esc_html_e( 'here', 'myhome - core' ); ?></a>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'page_height' ) ); ?>"><?php esc_html_e( 'Widget height:', 'myhome - core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_height' ) ); ?>" type="text" value="<?php echo esc_attr( $page_height ); ?>" />
		</p>
		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'small_header' ) ); ?>" type="checkbox" value="1" <?php checked( $small_header, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>"><?php esc_html_e( 'Use small header', 'myhome - core' ); ?></label>
		</p>
		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_cover_photo' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_cover_photo' ) ); ?>" type="checkbox" value="1" <?php checked( $hide_cover_photo, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'hide_cover_photo' ) ); ?>"><?php esc_html_e( 'Hide cover photo', 'myhome - core' ); ?></label>
		</p>
		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_friends_faces' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_friends_faces' ) ); ?>" type="checkbox" value="1" <?php checked( $show_friends_faces, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_friends_faces' ) ); ?>"><?php esc_html_e( 'Display faces of friend', 'myhome - core' ); ?></label>
		</p>
		<p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_timeline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_timeline' ) ); ?>" type="checkbox" value="1" <?php checked( $show_timeline, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_timeline' ) ); ?>"><?php esc_html_e( 'Display timeline', 'myhome - core' ); ?></label>
		</p>
		<?php
	}

	/**
	 * update
	 *
	 * Processing widget options on save
	 *
	 * @date   23/06/16
	 * @since  1.0.0
	 *
	 * @param $new_instance (array)
	 * @param $old_instance (array)
	 *
	 * @return $instance (array)
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                       = $old_instance;
		$instance['title']              = strip_tags( $new_instance['title'] );
		$instance['page_url']           = strip_tags( $new_instance['page_url'] );
		$instance['lang_code']          = strip_tags( $new_instance['lang_code'] );
		$instance['page_height']        = intval( $new_instance['page_height'] );
		$instance['small_header']       = intval( $new_instance['small_header'] );
		$instance['hide_cover_photo']   = intval( $new_instance['hide_cover_photo'] );
		$instance['show_friends_faces'] = intval( $new_instance['show_friends_faces'] );
		$instance['show_timeline']      = intval( $new_instance['show_timeline'] );

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			do_action(
				'wpml_register_single_string',
				esc_html__( 'MyHome - Widgets', 'myhome - core' ),
				esc_html__( 'Facebook widget', 'myhome - core' ),
				$instance['title']
			);
		}

		return $instance;
	}

}
