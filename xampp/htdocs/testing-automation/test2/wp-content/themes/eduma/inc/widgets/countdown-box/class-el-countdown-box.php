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

if ( ! class_exists( 'Thim_Builder_El_Countdown_Box' ) ) {
	/**
	 * Class Thim_Builder_El_Countdown_Box
	 */
	class Thim_Builder_El_Countdown_Box extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Countdown_Box';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-countdown-box', [ 'label' =>  esc_html__( 'Thim: Countdown Box', 'eduma' )]
			);
 			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if ( $key == 'time_year' || $key == 'time_month' || $key == 'time_day' ) {
					continue;
				}
				if ( $key == 'time_hour'  ) {
					$key = 'countdown_due_time';
					$control['label'] = esc_html__( 'Countdown Due Date', 'eduma' );
					$control['description'] = esc_html__( 'Set the due date and time', 'eduma' );
				}
   				$this->add_control( $key, $control );

			}

			$this->end_controls_section();
		}
		// convert setting
		function thim_convert_setting( $settings ) {
			$settings['due-time'] = $settings['countdown_due_time'];

			return $settings;
		}
	}
}