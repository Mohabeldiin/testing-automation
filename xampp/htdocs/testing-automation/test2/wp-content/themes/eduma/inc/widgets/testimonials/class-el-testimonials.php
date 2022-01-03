<?php
/**
 * Thim_Builder Elementor Testimonials widget
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

if ( ! class_exists( 'Thim_Builder_El_Testimonials' ) ) {
	/**
	 * Class Thim_Builder_El_Testimonials
	 */
	class Thim_Builder_El_Testimonials extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Testimonials';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-testimonials', [ 'label' => esc_html__( 'Thim: Testimonials', 'eduma' ) ]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if(isset($control['start_section'])){
					$this->end_controls_section();
					$this->start_controls_section(
						$control['start_section'], [ 'label' => $control['section_name']]
					);
				}
				if($key =='carousel_autoplay'){
					$key = 'autoplay_time';
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}

		// convert setting
		function thim_convert_setting( $settings ) {
			$settings['carousel-options']['auto_play']       = $settings['autoplay_time'];
			$settings['carousel-options']['show_pagination'] = $settings['show_pagination'];
			$settings['carousel-options']['show_navigation'] = $settings['show_navigation'];
			return $settings;
		}
	}
}