<?php

namespace MyHomeCore\Estates;


/**
 * Class Estates
 * @package MyHomeCore\Estates
 */
class Estates implements \Iterator, \Countable {

	const DEFAULT_DATA = 'get_data';
	const MARKER_DATA = 'get_marker_data';

	/**
	 * @var Estate[]
	 */
	private $estates;

	/**
	 * Estates constructor.
	 *
	 * @param Estate[] $estates
	 */
	public function __construct( $estates = array() ) {
		$this->estates = $estates;
	}

	/**
	 * @param Estate $estate
	 */
	public function add( Estate $estate ) {
		$this->estates[] = $estate;
	}

	/**
	 * @param string $data_type
	 *
	 * @return array
	 */
	public function get_data( $data_type = self::DEFAULT_DATA ) {
		$data = array();
		foreach ( $this->estates as $estate ) {
			$data[] = $estate->$data_type();
		}

		return $data;
	}

	/**
	 * @return mixed|Estate
	 */
	public function current() {
		return current( $this->estates );
	}

	/**
	 * @return mixed|Estate
	 */
	public function next() {
		return next( $this->estates );
	}

	/**
	 * @return mixed
	 */
	public function key() {
		return key( $this->estates );
	}

	/**
	 * @return bool
	 */
	public function valid() {
		$key = key( $this->estates );

		return ( $key !== null && $key !== false );
	}

	public function rewind() {
		reset( $this->estates );
	}

	/**
	 * @return int
	 */
	public function count() {
		return count( $this->estates );
	}

}