<?php
/**
 * Thim_Builder Elementor List Post widget
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

if ( ! class_exists( 'Thim_Builder_El_List_Post' ) ) {
	/**
	 * Class Thim_Builder_El_List_Post
	 */
	class Thim_Builder_El_List_Post extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_List_Post';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-list-post', [ 'label' => esc_html__( 'Thim: List Posts', 'eduma' ) ]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if ( isset( $control['start_section'] ) ) {
					$this->end_controls_section();
					$this->start_controls_section(
						$control['start_section'], [ 'label' => $control['section_name'] ]
					);
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
	}
}