<?php

namespace MyHomeCore\Estates;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Estates\Filters\Estate_Filter;

class Estate_Attribute {

	/**
	 * @var Attribute
	 */
	public $attribute;

	/**
	 * @var int
	 */
	private $estate;

	/**
	 * @var Attribute_Value[]
	 */
	private $values;

	/**
	 * Estate_Attribute constructor.
	 *
	 * @param Attribute $attribute
	 * @param Estate    $estate
	 */
	public function __construct( Attribute $attribute, Estate $estate ) {
		$this->attribute = $attribute;
		$this->estate    = $estate;
	}

	/**
	 * @return int
	 */
	public function get_ID() {
		return $this->attribute->get_ID();
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->attribute->get_name();
	}

	/**
	 * @return bool
	 */
	public function has_archive() {
		return $this->attribute->has_archive();
	}

	/**
	 * @return bool
	 */
	public function has_values() {
		if ( empty( $this->values ) ) {
			$this->get_values();
		}

		return ! empty( $this->values ) && count( $this->values );
	}

	/**
	 * @return Attribute_Value[]
	 */
	public function get_values() {
		if ( empty( $this->values ) ) {
			$this->values = $this->attribute->get_estate_values( $this->estate );
		}

		return $this->values;
	}

	/**
	 * @return bool
	 */
	public function show_on_card() {
		return $this->attribute->get_card_show();
	}

	/**
	 * @return bool
	 */
	public function new_box() {
		if ( $this->attribute instanceof Text_Attribute ) {
			return $this->attribute->get_new_box_independent() || ( $this->attribute->get_new_box() && $this->attribute->get_tags() );
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function get_display_after() {
		return $this->attribute->get_display_after();
	}

	/**
	 * @return array
	 */
	public function get_data() {
		$values = array();

		foreach ( $this->get_values() as $attribute_value ) {
			$values[] = $attribute_value->get_data();
		}

		return array(
			'name'          => $this->get_name(),
			'slug'          => $this->attribute->get_slug(),
			'has_archive'   => $this->has_archive(),
			'values'        => $values,
			'display_after' => $this->get_display_after(),
			'show'          => $this->attribute->get_property_show(),
			'card_show'     => $this->attribute->get_card_show(),
			'icon'          => $this->attribute->get_icon_class()
		);
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->attribute->get_slug();
	}

	/**
	 * @return Estate_Filter
	 */
	public function get_filter() {
		$attribute_values = new Attribute_Values( $this->get_values() );

		return $this->attribute->get_estate_filter( $attribute_values );
	}

	/**
	 * @return bool
	 */
	public function has_icon() {
		return $this->attribute->has_icon();
	}

	/**
	 * @return mixed
	 */
	public function get_icon() {
		return $this->attribute->get_icon_class();
	}

}