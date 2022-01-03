<?php
/**
 * Thim_Builder Elementor Image Box widget
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

if ( ! class_exists( 'Thim_Builder_El_Image_Box' ) ) {
	/**
	 * Class Thim_Builder_El_Image_Box
	 */
	class Thim_Builder_El_Image_Box extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Image_Box';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-image-box', [ 'label' => esc_html__( 'Thim: Image Box', 'eduma' )]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );
 			foreach ( $controls as $key => $control ) {
   				if($key == 'layout'){
					$key ='style';
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}

		// convert setting width image-box
		function thim_convert_setting( $settings ) {
			$settings = array(
				'layout'         => $settings['style'],
				'title'          => $settings['title'],
				'description'    => $settings['description'],
				'image'          => $settings['image'],
				'title_bg_color' => $settings['title_bg_color'],
				'link'           => $settings['link'],
			);

			return $settings;
		}
	}
}