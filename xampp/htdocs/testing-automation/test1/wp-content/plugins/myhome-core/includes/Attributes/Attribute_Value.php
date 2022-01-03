<?php

namespace MyHomeCore\Attributes;


/**
 * Class Attribute_Value
 * @package MyHomeCore\Attributes
 */
class Attribute_Value {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $value;

	/**
	 * @var string
	 */
	private $link;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * Attribute_Value constructor.
	 *
	 * @param string $name
	 * @param string $value
	 * @param string $link
	 * @param string $slug
	 * @param array  $options
	 */
	public function __construct( $name = '', $value, $link = '', $slug = '', $options = array() ) {
		$this->name    = $name;
		$this->value   = $value;
		$this->link    = $link;
		$this->slug    = $slug;
		$this->options = $options;
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
	public function get_link() {
		return $this->link;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return array(
			'name'    => $this->name,
			'value'   => $this->value,
			'link'    => $this->link,
			'slug'    => $this->slug,
			'options' => $this->options
		);
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get_option( $key ) {
		if ( isset( $this->options[$key] ) ) {
			return $this->options[$key];
		}

		return '';
	}

}