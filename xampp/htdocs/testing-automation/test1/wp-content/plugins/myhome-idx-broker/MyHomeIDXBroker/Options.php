<?php

namespace MyHomeIDXBroker;


/**
 * Class Options
 * @package MyHomeIDXBroker
 */
class Options {

	const OPTION_KEY = 'myhome_idx_broker_options';

	/**
	 * @var array
	 */
	private $options;

	/**
	 * Options constructor.
	 */
	public function __construct() {
		$this->options = get_option( Options::OPTION_KEY, array() );
	}

	/**
	 * @param string $option_key
	 * @param string $second_option_key
	 *
	 * @return string
	 */
	public function get( $option_key, $second_option_key = '' ) {
		if ( ! isset( $this->options[ $option_key ] ) ) {
			return '';
		}

		if ( ! empty( $second_option_key ) && isset( $this->options[ $option_key ][ $second_option_key ] ) ) {
			return $this->options[ $option_key ][ $second_option_key ];
		} elseif ( ! empty( $second_option_key ) ) {
			return '';
		}

		return $this->options[ $option_key ];
	}

	/**
	 * @param $option_key
	 *
	 * @return bool
	 */
	public function exists( $option_key ) {
		return isset( $this->options[ $option_key ] );
	}

	public function save( $options ) {
		update_option( Options::OPTION_KEY, $options );
		$this->options = $options;
	}

}