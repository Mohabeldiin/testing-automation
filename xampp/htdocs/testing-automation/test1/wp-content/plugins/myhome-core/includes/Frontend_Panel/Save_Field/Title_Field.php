<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


/**
 * Class Title_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Title_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		if ( $this->field['required'] && ! isset( $property_data['title'] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		if ( $this->field['required'] && empty( $property_data['title'] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		if ( empty( $property_data['title'] ) || $property_data['title'] == 'undefined' ) {
			$property_data['title'] = esc_html__( 'Property draft', 'myhome-core' );
		}

		$args    = array(
			'post_title' => $property_data['title'],
			'ID'         => $property_id
		);
		$post_id = wp_update_post( $args );

		if ( is_wp_error( $post_id ) ) {
			throw new \Exception( esc_html__( "Something wen't wrong", 'myhome-core' ) );
		}
	}

}