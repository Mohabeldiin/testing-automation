<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


/**
 * Class Image_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Image_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ( ! isset( $property_data['image'] ) || empty( $property_data['image'] ) ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$image_id = intval( $property_data['image'] ['image_id'] );
		set_post_thumbnail( $property_id, $image_id );
	}

}