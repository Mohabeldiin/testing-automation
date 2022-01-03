<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


use MyHomeCore\Estates\Elements\Estate_Element;

/**
 * Class Video_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Virtual_Tour_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ! isset( $property_data[ Estate_Element::VIRTUAL_TOUR ] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$virtual_tour = $property_data[ Estate_Element::VIRTUAL_TOUR ];
		if ( $this->field['required'] && empty( $virtual_tour ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		update_field( 'myhome_estate_virtual_tour', $virtual_tour, $property_id );
	}

}