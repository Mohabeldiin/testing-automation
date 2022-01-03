<?php
/**
 * Thim_Builder Elementor Google Map widget
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

if ( ! class_exists( 'Thim_Builder_El_Google_Map' ) ) {
	/**
	 * Class Thim_Builder_El_Google_Map
	 */
	class Thim_Builder_El_Google_Map extends Thim_Builder_El_Widget {

		/**
		 * @var string
		 */
		protected $config_class = 'Thim_Builder_Config_Google_Map';

		/**
		 * Register controls.
		 */
		protected function _register_controls() {
			$this->start_controls_section(
				'el-google-map', [ 'label' => esc_html__( 'Thim: Google Map', 'eduma' ) ]
			);

			$controls = \Thim_Builder_El_Mapping::mapping( $this->options() );

			foreach ( $controls as $key => $control ) {
				if(isset($control['start_section'])){
					$this->end_controls_section();
					$this->start_controls_section(
						$control['start_section'], [ 'label' => $control['section_name']]
					);
				}
				if ( $key == 'location_lat' || $key == 'location_lng' ) {
					$key = str_ireplace( 'location_', '', $key );
				}

				if ( $key == 'settings_height' || $key == 'settings_zoom' || $key == 'settings_scroll_zoom' || $key == 'settings_draggable' ) {
					$key = str_ireplace( 'settings_', '', $key );
				}
				$this->add_control( $key, $control );
			}

			$this->end_controls_section();
		}
		// convert setting
		function thim_convert_setting( $settings ) {
			$settings = array(
				'title'       => $settings['title'],
				'display_by'  => $settings['display_by'],
				'map_options' => $settings['map_options'],
				'location'    => array(
					'lat' => $settings['lat'],
					'lng' => $settings['lng']
				),
				'map_center'  => $settings['map_center'],
				'api_key'     => $settings['api_key'],
				'settings'    => array(
					'height'      => $settings['height'],
					'zoom'        => isset( $settings['zoom']['size'] ) ? $settings['zoom']['size'] : '12',
					'draggable'   => $settings['draggable'],
					'scroll_zoom' => $settings['scroll_zoom']
				),
				'markers'     => array(
					'marker_at_center' => $settings['marker_at_center'],
					'marker_icon'      => $settings['marker_icon'],
				)
			);

			return $settings;
		}
	}
}