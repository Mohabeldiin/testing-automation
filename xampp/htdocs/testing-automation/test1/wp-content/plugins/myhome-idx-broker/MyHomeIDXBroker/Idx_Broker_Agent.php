<?php

namespace MyHomeIDXBroker;


use MyHomeCore\Users\User;

/**
 * Class Idx_Broker_Agent
 * @package MyHomeIDXBroker
 */
class Idx_Broker_Agent extends User {

	/**
	 * @return string
	 */
	public function get_idx_broker_id() {
		$ids = get_user_meta( $this->get_ID(), Agents::FIELD_ID );
		if ( ! is_array( $ids ) ) {
			return '';
		}
		$ids = array_filter( $ids, function ( $id ) {
			return ! empty( $id );
		} );

		return implode( ', ', $ids );
	}

	/**
	 * @return string
	 */
	public function get_idx_broker_listing_id() {
		return get_user_meta( $this->get_ID(), Agents::FIELD_LISTING_ID, true );
	}

	/**
	 * @param $idx_broker_id
	 *
	 * @return bool|Idx_Broker_Agent
	 */
	public static function get_by_idx_broker_id( $idx_broker_id ) {
		$users = get_users( array(
			'meta_key'   => Agents::FIELD_ID,
			'meta_value' => $idx_broker_id
		) );

		if ( count( $users ) ) {
			return new Idx_Broker_Agent( $users[0] );
		}

		return false;
	}

	public function set_idx_id( $id ) {
		$ids = get_user_meta( $this->get_ID(), Agents::FIELD_ID );
		if ( in_array( $id, $ids ) ) {
			return;
		}
		delete_user_meta( $this->get_ID(), Agents::FIELD_ID );
		$ids[] = $id;

		foreach ( $ids as $id ) {
			add_user_meta( $this->get_ID(), Agents::FIELD_ID, $id );
		}
	}

}