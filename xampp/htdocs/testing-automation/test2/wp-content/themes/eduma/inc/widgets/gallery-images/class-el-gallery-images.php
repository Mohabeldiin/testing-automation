<?php
/**
 * Thim_Builder Elementor Gallery Images widget
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

if ( ! class_exists( 'Thim_Builder_El_Gallery_Images' ) ) {
	/**
	 * Class Thim_Builder_El_Gallery_Images
	 */
	class Thim_Builder_El_Gallery_Images extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Gallery_Images';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-gallery-images', [ 'label' => esc_html__( 'Thim: Gallery Images', 'eduma' )]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if($key == 'number'){
					$key = 'item';
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		// convert setting
		function thim_convert_setting( $settings ) {
			$settings['number'] = $settings['item'];
			$settings['image']  = array_map(
				function ( $ar ) {
					return $ar['id'];
				}, $settings['image']
			);

			return $settings;
		}
	}
}