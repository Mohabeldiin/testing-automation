<?php

namespace MyHomeCore\Integrations\ESSB;


/**
 * Class ESSB_Display
 * @package MyHomeCore\Integrations\ESSB
 */
class ESSB_Display {

	const PROPERTIES = 'mh_properties';
	const PROPERTY = 'mh_property';
	const AGENT = 'mh_agent';

	public function properties() {

	}

	public function property() {

	}

	public function agent() {

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