<?php

namespace MyHomeCore\Integrations\ESSB;

/**
 * Class ESSB_Init
 * @package MyHomeCore\ESSB
 */
class ESSB_Init {

	const PROPERTIES = 'mh_properties';
	const PROPERTY = 'mh_property';

	/**
	 * ESSB_Init constructor.
	 */
	public function __construct() {
		add_filter( 'essb4_custom_method_list', array( $this, 'essb_register_custom_position' ) );
		add_filter( 'essb4_custom_positions', array( $this, 'essb_display_register_custom_position' ) );
		add_filter( 'essb4_button_positions', array( $this, 'essb_display_custom_position' ) );
		add_filter( 'essb4_button_positions_mobile', array( $this, 'essb_display_custom_position' ) );
		add_action( 'init', array( $this, 'essb_custom_methods_register' ), 99 );
	}

	public function essb_display_custom_position( $positions ) {

		$positions[ESSB_Init::PROPERTIES] = array( 'image' => 'assets/images/display-positions-09.png', 'label' => esc_html__( 'Properties archive ', 'myhome-core' ) );
		$positions[ESSB_Init::PROPERTY]   = array( 'image' => 'assets/images/display-positions-09.png', 'label' => esc_html__( 'Property page', 'myhome-core' ) );

		return $positions;
	}

	public function essb_custom_methods_register() {
		if ( is_admin() && class_exists( 'ESSBOptionsStructureHelper' ) ) {
			essb_prepare_location_advanced_customization( 'where', 'display-41', ESSB_Init::PROPERTIES );
			essb_prepare_location_advanced_customization( 'where', 'display-42', ESSB_Init::PROPERTY );
		}
	}

	/**
	 * @param array $positions
	 *
	 * @return array
	 */
	public function essb_display_register_custom_position( $positions ) {
		$positions[ESSB_Init::PROPERTIES] = esc_html__( 'Properties archive', 'myhome-core' );
		$positions[ESSB_Init::PROPERTY]   = esc_html__( 'Single property', 'myhome-core' );

		return $positions;
	}

	/**
	 * @param  array $methods
	 *
	 * @return array
	 */
	public function essb_register_custom_position( $methods ) {
		$methods['display-41'] = esc_html__( 'Properties archive', 'myhome-core' );
		$methods['display-42'] = esc_html__( 'Single property', 'myhome-core' );

		return $methods;
	}

	public function display( $position ) {
		if ( function_exists( 'essb_core' ) ) {
			$general_options = essb_core()->get_general_options();

			if ( is_array( $general_options ) ) {
				if ( in_array( $position, $general_options['button_position'] ) ) {
					echo essb_core()->generate_share_buttons( $position );
				}
			}
		}
	}

}