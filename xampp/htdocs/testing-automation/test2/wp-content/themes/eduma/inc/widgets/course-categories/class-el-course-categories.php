<?php
/**
 * Thim_Builder Elementor Course Categories widget
 *
 * @version     1.0.0
 * @author      ThimPress
 * @package     Thim_Builder/Classes
 * @category    Classes
 * @author      Thimpress, tuanta
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Thim_Builder_El_Course_Categories' ) ) {
	/**
	 * Class Thim_Builder_El_Course_Categories
	 */
	class Thim_Builder_El_Course_Categories extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Course_Categories';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-course-categories', [ 'label' => esc_html__( 'Thim: Course Categories', 'eduma' ) ]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if ( $key == 'slider_limit' || $key == 'slider_show_pagination' || $key == 'slider_show_navigation' || $key == 'slider_auto_play' || $key == 'slider_item_visible' ) {
					$key = str_replace( "slider_", "", $key );
				}

				if ( $key == 'list_show_counts' || $key == 'list_hierarchical' ) {
					$key = str_replace( "list_", "", $key );;
				}

				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		// convert setting
		function thim_convert_setting( $settings ) {
			$settings = array(
				'title'          => $settings['title'],
				'layout'         => $settings['layout'],
				'image_size'         => $settings['image_size'],
				'slider-options' => array(
					'limit'              => $settings['limit'],
					'show_navigation'    => $settings['show_navigation'],
					'auto_play'          => $settings['auto_play'],
					'show_pagination'    => $settings['show_pagination'],

					'responsive-options' => array(
						'item_visible'               => isset( $settings['item_visible'] ) ? $settings['item_visible']['size'] : 6,
						'item_small_desktop_visible' => isset( $settings['slider_item_small_desktop_visible'] ) ? $settings['slider_item_small_desktop_visible'] : 6,
						'item_tablet_visible'        => isset( $settings['slider_item_tablet_visible'] ) ? $settings['slider_item_tablet_visible'] : 4,
						'item_mobile_visible'        => isset( $settings['slider_item_mobile_visible'] ) ? $settings['slider_item_mobile_visible'] : 2
					)
				),
				'list-options'   => array(
					'show_counts'  => $settings['show_counts'],
					'hierarchical' => $settings['hierarchical']
				),
				'grid-options'   => array(
					'grid_limit'  => $settings['grid_limit'],
					'grid_column' => $settings['grid_column']
				),
				'sub_categories' => $settings['sub_categories'],
			);

			return $settings;
		}

	}
}