<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


use MyHomeCore\Estates\Elements\Estate_Element;

/**
 * Class Video_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Video_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ! isset( $property_data[ Estate_Element::VIDEO ] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$video = $property_data[ Estate_Element::VIDEO ];
		if ( $this->field['required'] && empty( $video ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		update_field( 'myhome_estate_video', $video, $property_id );
	}

}