<?php

namespace MyHomeCore\Users\Fields;


/**
 * Class Fields
 * @package MyHomeCore\Users\Fields
 */
class Fields implements \Iterator, \Countable {

	/**
	 * @var Field[]
	 */
	private $fields = array();

	/**
	 * Fields constructor.
	 *
	 * @param $user_id
	 */
	public function __construct( $user_id ) {
		$fields = get_option( Settings::USER_FIELDS_OPTION );

		if ( ! empty( $fields ) && is_array( $fields ) ) {
			foreach ( $fields as $field ) {
				$this->fields[] = new Field( $field['name'], $field['slug'], $user_id, false, $field['is_link'] );
			}
		}
	}

	/**
	 * @return Field
	 */
	public function current() {
		return current( $this->fields );
	}

	/**
	 * @return Field
	 */
	public function next() {
		return next( $this->fields );
	}

	/**
	 * @return int|null|string
	 */
	public function key() {
		return key( $this->fields );
	}

	/**
	 * @return bool
	 */
	public function valid() {
		$key = key( $this->fields );

		return ( $key !== null && $key !== false );
	}

	public function rewind() {
		reset( $this->fields );
	}

	/**
	 * @return int
	 */
	public function count() {
		return count( $this->fields );
	}

	public function get_data() {
		$fields = array();

		foreach ( $this->fields as $field ) {
			$fields[] = $field->get_data();
		}

		return $fields;
	}

}