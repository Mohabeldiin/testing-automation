<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Testimonial_Carousel_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Testimonial_Carousel_Shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		wp_enqueue_script( 'myhome-carousel' );

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$args = array_merge( $args, vc_map_get_attributes( 'mh_carousel_testimonials', $args ) );
		}

		$class = $args['owl_visible'] . ' ' . $args['owl_dots'] . ' ' . $args['color'] . ' ' . $args['style'];
		if ( $args['owl_auto_play'] != 'true' ) {
			$class .= ' owl-carousel--no-auto-play';
		}

		$args = array(
			'post_type'           => 'testimonial',
			'posts_per_page'      => intval( $args['limit'] ),
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'suppress_filters'    => false
		);

		global $myhome_testimonial_carousel;
		$myhome_testimonial_carousel          = $args;
		$myhome_testimonial_carousel['class'] = $class;
		global $myhome_testimonials;
		$query = new \WP_Query( $args );
		$myhome_testimonials = $query->posts;

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		return array(
			// Visible
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Visible', 'myhome-core' ),
				'param_name'  => 'owl_visible',
				'value'       => array(
					esc_html__( 'Default - 3', 'myhome-core' ) => 'owl-carousel--visible-3',
					esc_html__( '1 ', 'myhome-core' )          => 'owl-carousel--visible-1',
					esc_html__( '2 ', 'myhome-core' )          => 'owl-carousel--visible-2',
					esc_html__( '3 ', 'myhome-core' )          => 'owl-carousel--visible-3',
				),
				'save_always' => true
			),
			// Limit
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Limit', 'myhome-core' ),
				'param_name' => 'limit',
				'value'      => '5'
			),
			// Style
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'myhome-core' ),
				'param_name'  => 'style',
				'value'       => array(
					esc_html__( 'Standard', 'myhome-core' )    => 'mh-testimonials--standard',
					esc_html__( 'Cloud text', 'myhome-core' )  => 'mh-testimonials--cloud-text',
					esc_html__( 'Transparent', 'myhome-core' ) => 'mh-testimonials--transparent',
					esc_html__( 'Boxed', 'myhome-core' )       => 'mh-testimonials--boxed',
				),
				'save_always' => true
			),
			// Color
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Color Scheme', 'myhome-core' ),
				'param_name'  => 'color',
				'value'       => array(
					esc_html__( 'Dark', 'myhome-core' )  => 'mh-testimonials--dark',
					esc_html__( 'Light', 'myhome-core' ) => 'mh-testimonials--light',
				),
				'save_always' => true
			),
			// Dots
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Dots', 'myhome-core' ),
				'param_name'  => 'owl_dots',
				'value'       => array(
					esc_html__( 'Yes', 'myhome-core' ) => '',
					esc_html__( 'No', 'myhome-core' )  => 'owl-carousel--no-dots',
				),
				'save_always' => true
			),
			// Auto play
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Auto play', 'myhome-core' ),
				'param_name'  => 'owl_auto_play',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true
			),
		);
	}

}