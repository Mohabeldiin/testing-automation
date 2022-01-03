<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Client_Carousel_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Client_Carousel_Shortcode extends Shortcode {

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		wp_enqueue_script( 'myhome-carousel' );

		$default_args = array(
			'owl_visible'   => 'owl-carousel--visible-3',
			'owl_dots'      => '',
			'owl_auto_play' => 'true',
			'image_filter'  => '',
			'limit'         => '5'
		);

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$args = array_merge( $default_args, vc_map_get_attributes( 'mh_carousel_clients', $args ) );
		}

		$class = $args['owl_visible'] . ' ' . $args['owl_dots'] . ' ' . $args['image_filter'];
		if ( $args['owl_auto_play'] != 'true' ) {
			$class .= ' owl-carousel--no-auto-play';
		}

		$args    = array(
			'post_type'           => 'client',
			'posts_per_page'      => intval( $args['limit'] ),
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish'
		);
		$clients = new \WP_Query( $args );
		global $myhome_clients;
		$myhome_clients = $clients;
		global $myhome_client_carousel;
		$myhome_client_carousel          = $args;
		$myhome_client_carousel['class'] = $class;

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		return array(
			// Visible
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Number of visible clients', 'myhome-core' ),
				'param_name' => 'owl_visible',
				'value'      => array(
					esc_html__( 'Default - 3', 'myhome-core' ) => 'owl-carousel--visible-3',
					esc_html__( '1 ', 'myhome-core' )          => 'owl-carousel--visible-1',
					esc_html__( '2 ', 'myhome-core' )          => 'owl-carousel--visible-2',
					esc_html__( '3 ', 'myhome-core' )          => 'owl-carousel--visible-3',
					esc_html__( '4 ', 'myhome-core' )          => 'owl-carousel--visible-4',
					esc_html__( '5 ', 'myhome-core' )          => 'owl-carousel--visible-5',
				),
			),
			// Limit
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Total number of clients to display', 'myhome-core' ),
				'param_name' => 'limit',
				'value'      => '5'
			),
			// Dots
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Display navigation dots', 'myhome-core' ),
				'param_name' => 'owl_dots',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => '',
					esc_html__( 'No', 'myhome-core' )  => 'owl-carousel--no-dots',
				),
			),
			// Image filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Image Hover Effect - Grayscale', 'myhome-core' ),
				'param_name' => 'image_filter',
				'value'      => array(
					esc_html__( 'No', 'myhome-core' )  => '',
					esc_html__( 'Yes', 'myhome-core' ) => 'mh-clients--image-filter',
				),
			),
			// Auto play
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Auto play', 'myhome-core' ),
				'param_name'  => 'owl_auto_play',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true
			)
		);
	}

}