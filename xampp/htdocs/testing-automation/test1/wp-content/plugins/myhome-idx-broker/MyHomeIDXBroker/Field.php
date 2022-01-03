<?php

namespace MyHomeIDXBroker;


/**
 * Class Field
 * @package MyHomeIDXBroker
 */
class Field {

	private $name;
	private $display_name;
	private $value = 'ignore';

	/**
	 * Field constructor.
	 *
	 * @param array $field
	 */
	public function __construct( $field ) {
		$this->name         = $field['name'];
		$this->display_name = $field['display_name'];

		if ( isset( $field['value'] ) && ! empty( $field['value'] ) ) {
			$this->value = $field['value'];
		}
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_display_name() {
		if ( ! empty( $this->display_name ) ) {
			return $this->display_name;
		}

		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function has_value( $value ) {
		if ( ! is_array( $this->value ) ) {
			return $this->value == $value;
		}

		return in_array( $value, $this->value );
	}

}