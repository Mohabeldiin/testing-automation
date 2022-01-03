<?php

namespace MyHomeCore\Payments;


/**
 * Class WC_Product_Property_Package
 * @package MyHomeCore\Payments
 */
class WC_Product_Property_Package extends \WC_Product_Simple {

	/**
	 * @return string
	 */
	public function get_type() {
		return 'myhome_package';
	}

	/**
	 * @return int
	 */
	public function get_featured_number() {
		return intval( $this->get_meta( 'myhome_featured_number' ) );
	}

	/**
	 * @return int
	 */
	public function get_properties_number() {
		return intval( $this->get_meta( 'myhome_properties_number' ) );
	}

	/**
	 * @return bool
	 */
	public function is_one_time() {
		return $this->get_meta( 'myhome_one_time' ) == 'yes';
	}

	/**
	 * @return bool
	 */
	public function can_current_user_buy() {
		if ( ! is_user_logged_in() || ! $this->is_one_time() ) {
			return true;
		}

		$current_user = wp_get_current_user();
		$bought       = get_user_meta( $current_user->ID, 'myhome_package_bought_' . $this->get_id(), true );

		return empty( $bought );
	}

	/**
	 * @param int $user_id
	 */
	public function bought( $user_id ) {
		update_user_meta( $user_id, 'myhome_package_bought_' . $this->get_id(), 1 );
	}

	/**
	 * @return bool
	 */
	public function is_virtual() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function is_downloadable() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function is_purchasable() {
		return true;
	}

}