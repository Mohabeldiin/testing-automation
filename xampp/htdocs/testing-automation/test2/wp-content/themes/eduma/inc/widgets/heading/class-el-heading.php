<?php
/**
 * Thim_Builder Elementor Heading widget
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

if ( ! class_exists( 'Thim_Builder_El_Heading' ) ) {
	/**
	 * Class Thim_Builder_El_Heading
	 */
	class Thim_Builder_El_Heading extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Heading';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-heading', [ 'label' => esc_html__( 'Thim: Heading', 'eduma' )]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if(isset($control['start_section'])){
					$this->end_controls_section();
					$this->start_controls_section(
						$control['start_section'], [ 'label' => $control['section_name']]
					);
				}
				if($key == 'title_custom'){
					$key = 'font_heading';
				}
				if($key == 'font_size'|| $key == 'font_weight'|| $key == 'font_style'){
					$key = 'custom_'.$key;
 				}
 				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		// convert variables
		function thim_convert_setting( $settings ) {
			$settings['custom_font_heading']['custom_font_size']   = $settings['custom_font_size'];
			$settings['custom_font_heading']['custom_font_weight'] = $settings['custom_font_weight'];
			$settings['custom_font_heading']['custom_font_style']  = $settings['custom_font_style'];

			return $settings;
		}
	}
}