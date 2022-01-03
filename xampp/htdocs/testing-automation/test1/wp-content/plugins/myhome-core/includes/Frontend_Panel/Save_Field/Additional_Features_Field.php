<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


use MyHomeCore\Estates\Elements\Estate_Element;

/**
 * Class Additional_Features_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Additional_Features_Field extends Field {

	/**
	 * @param int $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( ! empty( $this->field['required'] ) && ! isset( $property_data[ Estate_Element::ADDITIONAL_FEATURES ] ) ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
			} else {
				return;
			}
		}

		$values = $property_data[ Estate_Element::ADDITIONAL_FEATURES ];
		if ( ! empty( $this->field['required'] ) && ( empty( $values ) || ! is_array( $values ) ) ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
			} else {
				return;
			}
		}

		if ( ! is_array( $values ) ) {
			$values = array();
		}

		$additional_features = array();
		foreach ( $values as $value ) {
			$additional_features[] = array(
				'estate_additional_feature_name'  => sanitize_text_field( $value['name'] ),
				'estate_additional_feature_value' => sanitize_text_field( $value['value'] )
			);
		}

		update_field( 'myhome_estate_additional_features', $additional_features, $property_id );
	}

}