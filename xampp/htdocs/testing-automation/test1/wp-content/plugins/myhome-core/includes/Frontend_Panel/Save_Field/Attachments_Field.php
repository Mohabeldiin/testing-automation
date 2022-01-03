<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


use MyHomeCore\Estates\Elements\Estate_Element;

/**
 * Class Attachments_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Attachments_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ! isset( $property_data[ Estate_Element::ATTACHMENTS ] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$values = $property_data[ Estate_Element::ATTACHMENTS ];
		if ( $this->field['required'] && ( empty( $values ) || ! is_array( $values ) ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$attachments = array();
		foreach ( $values as $value ) {
			$attachments[] = array(
				'estate_attachment_name' => sanitize_text_field( $value['name'] ),
				'estate_attachment_file' => intval( $value['id'] )
			);
		}

		$attachments_limit = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-attachments_max-number' ) );
		if ( $attachments_limit && $attachments_limit < count( $attachments ) ) {
			throw new \Exception( sprintf( esc_html__( 'To many images. Limit is %d.', 'myhome-core' ) ), $attachments_limit );
		}

		update_field( 'myhome_estate_attachments', $attachments, $property_id );
	}

}