<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


use MyHomeCore\Estates\Elements\Estate_Element;

/**
 * Class Plans_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Plans_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		$plans = array();

		if ( empty( $this->field['required'] ) && ! isset( $property_data[ Estate_Element::PLANS ] ) ) {
			update_field( 'myhome_estate_plans', $plans, $property_id );

			return;
		}

		if ( ! empty( $this->field['required'] ) && ! isset( $property_data[ Estate_Element::PLANS ] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$values = $property_data[ Estate_Element::PLANS ];
		if ( ! empty( $this->field['required'] ) && empty( $values ) || ! is_array( $values ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		if ( empty( $values ) ) {
			return;
		}

		foreach ( $values as $value ) {
			$plans[] = array(
				'estate_plans_name'  => sanitize_text_field( $value['name'] ),
				'estate_plans_image' => intval( $value['image_id'] )
			);
		}

		$plans_limit = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-images_max-number' ) );
		if ( $plans_limit && $plans_limit < count( $plans ) ) {
			throw new \Exception( sprintf( esc_html__( 'To many images. Limit is %d.', 'myhome-core' ) ), $plans_limit );
		}

		update_field( 'myhome_estate_plans', $plans, $property_id );
	}

}