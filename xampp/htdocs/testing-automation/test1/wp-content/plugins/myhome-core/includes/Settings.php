<?php

namespace MyHomeCore;


class Settings {

	public $props = array();

	public function __construct() {
		$this->props = get_option( 'myhome_redux' );
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get( $key ) {
		$key = 'mh-' . $key;

		if ( isset( $this->props[ $key ] ) ) {
			return $this->props[ $key ];
		}

		return '';
	}

}