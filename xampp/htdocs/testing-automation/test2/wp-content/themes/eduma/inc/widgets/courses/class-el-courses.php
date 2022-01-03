<?php
/**
 * Thim_Builder Elementor Courses widget
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

if ( ! class_exists( 'Thim_Builder_El_Courses' ) ) {
	/**
	 * Class Thim_Builder_El_Courses
	 */
	class Thim_Builder_El_Courses extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Courses';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-courses', [ 'label' => esc_html__( 'Thim: Courses', 'eduma' ) ]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if ( isset( $control['start_section'] ) ) {
					$this->end_controls_section();
					$this->start_controls_section(
						$control['start_section'], [ 'label' => $control['section_name'] ]
					);
				}
				if ( $key == 'grid_columns' ) {
					$key = 'columns';
				}
				if ( $key == 'slider_navigation' || $key == 'slider_pagination' ) {
					$key = str_replace( 'slider', 'show', $key );
				}
				if ( $key == 'slider_auto_play' || $key == 'slider_item_visible' ) {
					$key = str_replace( 'slider_', '', $key );
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		
		// convert setting 
		function thim_convert_setting( $settings ) {
			$settings['thumbnail_width'] = isset( $settings['thumbnail_width']['size'] ) ? $settings['thumbnail_width']['size'] : '';
			$settings['thumbnail_height'] = isset( $settings['thumbnail_height']['size'] ) ? $settings['thumbnail_height']['size'] : '';

			$settings['slider-options']['show_pagination'] = $settings['show_pagination'];
			$settings['slider-options']['show_navigation'] = $settings['show_navigation'];
			$settings['slider-options']['item_visible']    = $settings['item_visible'];
			$settings['slider-options']['auto_play']       = $settings['auto_play'];

			$settings['grid-options']['columns']           = $settings['columns'];
			$settings['tabs-options']['limit_tab']         = $settings['limit_tab'];
			$settings['tabs-options']['cat_id_tab'] =   $settings['cat_id_tab'] ;
 			return $settings;
		}
	}
}