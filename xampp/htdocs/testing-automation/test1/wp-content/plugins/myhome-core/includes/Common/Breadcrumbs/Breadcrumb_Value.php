<?php

namespace MyHomeCore\Common\Breadcrumbs;


use MyHomeCore\Attributes\Attribute_Value;

/**
 * Class Breadcrumb_Value
 * @package MyHomeCore\Common\Breadcrumbs
 */
class Breadcrumb_Value {

	/**
	 * @var Attribute_Value
	 */
	private $value;

	/**
	 * @var string
	 */
	private $current_link;

	/**
	 * @var int
	 */
	private $count;

	/**
	 * Breadcrumb_Value constructor.
	 *
	 * @param Attribute_Value $value
	 * @param \string         $current_link
	 * @param int             $count
	 */
	public function __construct( Attribute_Value $value, $current_link, $count = 0 ) {
		$this->value        = $value;
		$this->current_link = $current_link;
		$this->count        = $count;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->value->get_name();
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return $this->current_link . $this->value->get_slug();
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->value->get_slug();
	}

	/**
	 * @return int
	 */
	public function get_count() {
		return $this->count;
	}

}