<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


/**
 * Class Location_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Location_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ( ! isset( $property_data['address'] ) || ! isset( $property_data['location'] ) ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		if ( $this->field['required'] && ( empty( $property_data['address'] ) || empty( $property_data['location'] ) ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$map = array(
			'address' => $property_data['address'],
			'lat'     => $property_data['location']['lat'],
			'lng'     => $property_data['location']['lng'],
			'zoom'    => 15
		);

		update_field( 'myhome_estate_location', $map, $property_id );
	}

}