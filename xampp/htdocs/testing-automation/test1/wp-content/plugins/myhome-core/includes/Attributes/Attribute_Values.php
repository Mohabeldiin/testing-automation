<?php

namespace MyHomeCore\Attributes;


/**
 * Class Attribute_Values
 * @package MyHomeCore\Attributes
 */
class Attribute_Values implements \Iterator, \Countable {

	/**
	 * @var Attribute_Value[]
	 */
	private $values;

	/**
	 * Attribute_Values constructor.
	 *
	 * @param Attribute_Value[] $values
	 */
	public function __construct( $values = array() ) {
		$this->values = $values;
	}

	/**
	 * @param Attribute_Value $attribute_value
	 */
	public function add( Attribute_Value $attribute_value ) {
		$this->values[] = $attribute_value;
	}

	/**
	 * @param int $index
	 *
	 * @return bool|mixed|Attribute_Value
	 */
	public function get( $index ) {
		if ( array_key_exists( $index, $this->values ) ) {
			return $this->values[ $index ];
		}

		return false;
	}

	/**
	 * @return Attribute_Value[]
	 */
	public function get_data() {
		$values = array();
		foreach ( $this->values as $value ) {
			$values[] = $value->get_data();
		}

		return $values;
	}

	/**
	 * @return array
	 */
	public function get_slugs() {
		$slugs = array();
		foreach ( $this->values as $value ) {
			$slugs[] = $value->get_slug();
		}

		return $slugs;
	}

	/**
	 * @return array
	 */
	public function get_names() {
		$names = array();
		foreach ( $this->values as $value ) {
			$names[] = $value->get_name();
		}

		return $names;
	}

	/**
	 * @return Attribute_Value
	 */
	public function current() {
		return current( $this->values );
	}

	/**
	 * @return mixed|Attribute_Value
	 */
	public function next() {
		return next( $this->values );
	}

	/**
	 * @return mixed
	 */
	public function key() {
		return key( $this->values );
	}

	/**
	 * @return bool
	 */
	public function valid() {
		$key = key( $this->values );

		return ( $key !== null && $key !== false );
	}

	public function rewind() {
		reset( $this->values );
	}

	/**
	 * @return int
	 */
	public function count() {
		return count( $this->values );
	}

	/**
	 * @return Attribute_Value[]
	 */
	public function get_array() {
		return $this->values;
	}

}