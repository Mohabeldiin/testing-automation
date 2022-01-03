<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


/**
 * Class Description_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Description_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ! isset( $property_data['description'] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		if ( $this->field['required'] && empty( $property_data['description'] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$args    = array(
			'post_content' => $property_data['description'],
			'ID'           => $property_id
		);
		$post_id = wp_update_post( $args );

		if ( is_wp_error( $post_id ) ) {
			throw new \Exception( esc_html__( "Something went wrong", 'myhome-core' ) );
		}
	}

}