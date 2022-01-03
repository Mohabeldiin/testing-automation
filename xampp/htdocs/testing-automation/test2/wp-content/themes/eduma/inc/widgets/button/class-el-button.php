<?php
/**
 * Thim_Builder Elementor Button widget
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

if ( ! class_exists( 'Thim_Builder_El_Button' ) ) {
	/**
	 * Class Thim_Builder_El_Button
	 */
	class Thim_Builder_El_Button extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Button';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-button', [ 'label' => esc_html__( 'Thim: Button', 'eduma' )]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		function thim_convert_setting( $settings ) {
			$settings = array(
				'title'         => $settings['title'],
				'url'           => $settings['url'],
				'new_window'    => $settings['new_window'],
				'custom_style'  => $settings['custom_style'],
				'style_options' => array(
					'font_size'          => $settings['font_size'],
					'font_weight'        => $settings['font_weight'],
					'border_width'       => $settings['border_width'],
					'color'              => $settings['color'],
					'border_color'       => $settings['border_color'],
					'bg_color'           => $settings['bg_color'],
					'hover_color'        => $settings['hover_color'],
					'hover_border_color' => $settings['hover_border_color'],
					'hover_bg_color'     => $settings['hover_bg_color'],
				),
				'icon'          => array(
					'icon'          => $settings['icon'],
					'icon_size'     => $settings['icon_size'],
					'icon_position' => $settings['icon_position'],
				),
				'layout'        => array(
					'button_size' => $settings['button_size'],
					'rounding'    => $settings['rounding'],
				),
			);

			return $settings;
		}
	}
}