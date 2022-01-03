<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


/**
 * Class Gallery_Field
 * @package MyHomeCore\Frontend_Panel\Save_Field
 */
class Gallery_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 *
	 * @throws \Exception
	 */
	public function save( $property_id, $property_data ) {
		$gallery = array();

		if ( empty( $this->field['required'] ) && ! isset( $property_data['images'] ) ) {
			update_post_meta( $property_id, 'estate_gallery', $gallery );

			return;
		}

		if ( ! empty( $this->field['required'] ) && ! isset( $property_data['images'] ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		$images = $property_data['images'];
		if ( ! empty( $this->field['required'] ) && empty( $images ) || ! is_array( $images ) ) {
			throw new \Exception( esc_html__( 'Required data are missing', 'myhome-core' ) );
		}

		if ( empty( $images ) ) {
			return;
		}

		foreach ( $images as $image ) {
			$gallery[] = intval( $image['id'] );
		}

		$gallery_limit = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-images_max-number' ) );
		if ( $gallery_limit && $gallery_limit < count( $gallery ) ) {
			throw new \Exception( sprintf( esc_html__( 'To many images. Limit is %d.', 'myhome-core' ) ), $gallery_limit );
		}

		update_post_meta( $property_id, 'estate_gallery', $gallery );
	}

}