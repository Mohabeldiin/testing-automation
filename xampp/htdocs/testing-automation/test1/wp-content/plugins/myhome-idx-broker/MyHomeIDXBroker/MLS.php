<?php

namespace MyHomeIDXBroker;


/**
 * Class MLS
 * @package MyHomeIDXBroker
 */
class MLS {

	const OPTION_KEY = 'myhome_idx_broker_mls_ids';

	public static function save() {
		if ( ! isset( $_POST['mls_ids'] ) ) {
			return;
		}

		$mls_ids = preg_split( '/(\r?\n)+/', $_POST['mls_ids'] );

		if ( empty( $mls_ids ) || ! is_array( $mls_ids ) ) {
			update_option( MLS::OPTION_KEY, array() );
		}

		foreach ( $mls_ids as $key => $mls_id ) {
			$mls_ids[ $key ] = trim( $mls_id );
		}

		$mls_ids = array_filter( $mls_ids, function ( $mls_id ) {
			return ! empty( $mls_id );
		} );

		update_option( MLS::OPTION_KEY, $mls_ids );
	}

	/**
	 * @return array
	 */
	public static function get() {
		$mls_ids = get_option( MLS::OPTION_KEY );

		if ( empty( $mls_ids ) || ! is_array( $mls_ids ) ) {
			return array();
		}

		return $mls_ids;
	}

}