<?php

namespace MyHomeCore\Frontend_Panel\Save_Field;


class Featured_Field extends Field {

	/**
	 * @param int   $property_id
	 * @param array $property_data
	 */
	public function save( $property_id, $property_data ) {
		$payment_status = get_post_meta( $property_id, 'myhome_state', true );
		if ( $payment_status == 'payed' ) {
			return;
		}

		$is_featured = filter_var( $property_data['is_featured'], FILTER_VALIDATE_BOOLEAN );
		update_post_meta( $property_id, 'estate_featured', $is_featured );
	}

}