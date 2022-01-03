<?php
/**
 * Thim_Builder Elementor Login Form widget
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

if ( ! class_exists( 'Thim_Builder_El_Login_Form' ) ) {
	/**
	 * Class Thim_Builder_El_Login_Form
	 */
	class Thim_Builder_El_Login_Form extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Login_Form';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-login-form', [ 'label' => esc_html__( 'Thim: Login Form', 'eduma' )]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if($key == 'is_external' || $key == 'nofollow'){
					continue;
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		// convert setting
		function thim_convert_setting( $settings ) {
			$settings = array(
				'captcha'     => $settings['captcha'],
				'terms'        => $settings['term']['url'],
				'is_external' => isset($settings['term']['target']) ? $settings['term']['target'] :'',
				'nofollow'    => isset($settings['term']['rel']) ? $settings['term']['rel'] : ''
			);
			return $settings;
		}
	}
}