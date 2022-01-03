<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;

use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Price_Attribute;


/**
 * Class Number_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Number_Field extends Field {

	/**
	 * @param int $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( ! empty( $this->field['required'] ) && ! isset( $property_data['attributes'][ $this->field['slug'] ] ) ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( sprintf( esc_html__( 'Required data are missing (%s)', 'myhome-core' ), $this->field['name'] ) );
			} else {
				return;
			}
		}

		$validation = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-frontend-number_field_validation'] ) || ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-frontend-number_field_validation'] );
		$attribute  = Attribute_Factory::get_by_ID( $this->field['id'] );

		$initialValue = trim( $property_data['attributes'][ $this->field['slug'] ] );
		if ( $validation ) {
			if ( $attribute instanceof Price_Attribute ) {
				$value = intval( $property_data['attributes'][ $this->field['slug'] ] );
			} else {
				$value = floatval( $property_data['attributes'][ $this->field['slug'] ] );
			}
		} else {
			if ( $attribute instanceof Price_Attribute ) {
				$value = intval( $property_data['attributes'][ $this->field['slug'] ] );
			} else {
				$value = $property_data['attributes'][ $this->field['slug'] ];
			}
		}
		if ( ! empty( $this->field['required'] ) && $initialValue === '' ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( sprintf( esc_html__( 'Required data are missing (%s)', 'myhome-core' ), $this->field['name'] ) );
			} else {
				return;
			}
		}

		update_post_meta( $property_id, 'estate_attr_' . $this->field['slug'], $value );
	}

	public function fit_property_types( $property_types ) {
		if ( count( $property_types ) == 0 ) {
			return true;
		}

		$attribute                 = Attribute_Factory::get_by_ID( $this->field['id'] );
		$property_types_dependency = $attribute->get_property_type_dependency();

		foreach ( $property_types as $property_type ) {
			if ( isset( $property_types_dependency[ $property_type ] ) && $property_types_dependency[ $property_type ] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $property_id
	 */
	public function clear( $property_id ) {
		update_post_meta( $property_id, 'estate_attr_' . $this->field['slug'], '' );
	}

}