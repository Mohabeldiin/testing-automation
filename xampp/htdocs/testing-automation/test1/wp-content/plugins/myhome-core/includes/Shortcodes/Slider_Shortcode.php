<?php

namespace MyHomeCore\Shortcodes {


	/**
	 * Class Slider_Shortcode
	 * @package MyHomeCore\Shortcodes
	 */
	class Slider_Shortcode extends Shortcode {

		/**
		 * @param array $args
		 * @param null $content
		 *
		 * @return string
		 */
		public function display( $args = array(), $content = null ) {
			$content = wpb_js_remove_wpautop( $content, true );

			if ( function_exists( 'vc_map_get_attributes' ) ) {
				$args = array_merge( $args, vc_map_get_attributes( 'mh_slider', $args ) );
			}

			global $myhome_slider;
			$myhome_slider = array(
				'slider'            => $args['slider'],
                'search_style'      => 'mh-rs-search--type-1',
                'search_size'       => 'mh-rs-search--medium',
                'search_background' => 'mh-rs-search--light-mask',
				'content'           => $content
			);
            $class = $args['search_style'] . ' ' . $args['search_size'] . ' ' . $args['search_background'];
            $search_offset = $args['search_offset'];
            $search_offset_mobile = $args['search_offset_mobile'];

            global $myhome_search;
            $myhome_search                  = $args;
            $myhome_search['class']         = $class;
            $myhome_search['search_offset'] = $search_offset;
            $myhome_search['search_offset_mobile'] = $search_offset_mobile;

			return $this->get_template();
		}

		/**
		 * @return array
		 */
		public function get_vc_params() {
			$sliders = array();
			if ( class_exists( \RevSlider::class ) ) {
				$sliders[ esc_html__( 'Select slider', 'myhome-core' ) ] = '';
				foreach ( \RevSlider::get_sliders_short_list() as $slider ) {
					$sliders[ $slider->alias ] = $slider->title;
				}
			}

			return array(
				array(
					'group'       => esc_html__( 'General', 'myhome-core' ),
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Slider', 'myhome-core' ),
					'param_name'  => 'slider',
					'value'       => $sliders,
					'save_always' => true
				),
                // Search Form Style
                array(
                    'group'      => esc_html__( 'Search Form with "Submit" Button Settings', 'myhome-core' ),
                    'type'       => 'dropdown',
                    'heading'    => esc_html__( 'Search Form Style', 'myhome-core' ),
                    'param_name' => 'search_style',
                    'value'      => array(
                        esc_html__( '75% - 25%', 'myhome-core' )                => 'mh-rs-search--type-1',
                        esc_html__( '85% - 15%', 'myhome-core' )                => 'mh-rs-search--type-2',
                        esc_html__( '40% - 20% - 20% - 20%', 'myhome-core' )    => 'mh-rs-search--type-3',
                        esc_html__( '20% - 60% - 20%', 'myhome-core' )          => 'mh-rs-search--type-4',
                        esc_html__( '25% x 4', 'myhome-core' )                  => 'mh-rs-search--type-5',
                        esc_html__( '25% x 8', 'myhome-core' )                  => 'mh-rs-search--type-6',
                        esc_html__( '60% - 20% - 20%', 'myhome-core' )          => 'mh-rs-search--type-7'
                    ),
                ),
                // Search Form Size
                array(
                    'group'      => esc_html__( 'Search Form with "Submit" Button Settings', 'myhome-core' ),
                    'type'       => 'dropdown',
                    'heading'    => esc_html__( 'Search Form Size', 'myhome-core' ),
                    'param_name' => 'search_size',
                    'value'      => array(
                        esc_html__( 'medium', 'myhome-core' ) => 'mh-rs-search--size-medium',
                        esc_html__( 'big', 'myhome-core' )    => 'mh-rs-search--size-big',
                        esc_html__( 'full', 'myhome-core' )   => 'mh-rs-search--size-full',
                    ),
                ),
                // Search Form BG
                array(
                    'group'      => esc_html__( 'Search Form with "Submit" Button Settings', 'myhome-core' ),
                    'type'       => 'dropdown',
                    'heading'    => esc_html__( 'Search Form background', 'myhome-core' ),
                    'param_name' => 'search_background',
                    'value'      => array(
                        esc_html__( 'White mask', 'myhome-core' ) => 'mh-rs-search--bg-light-mask',
                        esc_html__( 'Dark mask ', 'myhome-core' ) => 'mh-rs-search--bg-dark-mask',
                        esc_html__( 'White ', 'myhome-core' )     => 'mh-rs-search--bg-light',
                        esc_html__( 'Dark', 'myhome-core' )       => 'mh-rs-search--bg-dark',
                    ),
                ),
                // Search Form Middle Offset
                array(
                    'group'      => esc_html__( 'Search Form with "Submit" Button Settings', 'myhome-core' ),
                    'type'       => 'textfield',
                    'heading'    => esc_html__( 'Search Form Middle Offset', 'myhome-core' ),
                    'param_name' => 'search_offset',
                    'value'      => '0',
                ),
                // Search Form Middle Offset
                array(
                    'group'      => esc_html__( 'Search Form with "Submit" Button Settings', 'myhome-core' ),
                    'type'       => 'textfield',
                    'heading'    => esc_html__( 'Search Form Middle Offset Mobile (<778px)', 'myhome-core' ),
                    'param_name' => 'search_offset_mobile',
                    'value'      => '0',
                ),
			);
		}

	}
}

namespace {
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_mh_slider extends WPBakeryShortCodesContainer {
		}
	}
}