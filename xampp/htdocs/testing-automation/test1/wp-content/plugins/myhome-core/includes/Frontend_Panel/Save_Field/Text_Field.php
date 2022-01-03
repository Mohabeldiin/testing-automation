<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;

use MyHomeCore\Attributes\Attribute_Factory;


/**
 * Class Text_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Text_Field extends Field {

	/**
	 * @param int $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ! isset( $property_data['attributes'][ $this->field['id'] ] ) ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( sprintf( esc_html__( 'Required data are missing (%s)', 'myhome-core' ), $this->field['name'] ) );
			} else {
				return;
			}
		}

		$value = $property_data['attributes'][ $this->field['id'] ];

		if ( $this->field['required'] && ( empty( $value ) || ! is_array( $value ) ) ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( sprintf( esc_html__( 'Required data are missing (%s)', 'myhome-core' ), $this->field['name'] ) );
			} else {
				return;
			}
		}

		$result = wp_set_post_terms( $property_id, $value, $this->field['slug'] );
		if ( is_wp_error( $result ) ) {
			if ( apply_filters( 'myhome_v', true ) ) {
				throw new \Exception( esc_html__( 'Something went wrong', 'myhome-core' ) );
			} else {
				return;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function is_property_type() {
		$property_type = Attribute_Factory::get_property_type();

		return $property_type->get_slug() == $this->field['slug'];
	}

	public function fit_property_types( $property_types ) {
		if ( count( $property_types ) == 0 ) {
			return true;
		}

		$attribute                 = Attribute_Factory::get_by_ID( $this->field['id'] );
		$property_types_dependency = $attribute->get_property_type_dependency();

		foreach ( $property_types as $property_type ) {
			if ( isset( $property_types_dependency[ $property_type ] ) && ! empty( $property_types_dependency[ $property_type ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $property_id
	 */
	public function clear( $property_id ) {
		wp_set_post_terms( $property_id, [], $this->field['slug'] );
	}

}