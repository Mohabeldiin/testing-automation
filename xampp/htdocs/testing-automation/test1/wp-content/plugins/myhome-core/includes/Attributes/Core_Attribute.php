<?php

namespace MyHomeCore\Attributes;

use MyHomeCore\Estates\Estate_Filter;
use MyHomeCore\Estates\Estate_Text_Filter;


/**
 * Class Core_Attribute
 * @package MyHomeCore\Attributes
 */
abstract class Core_Attribute extends Attribute {

	/**
	 * @return bool
	 */
	public function has_archive() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function show() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function card_show() {
		return false;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::CORE;
	}

	/**
	 * @return string
	 */
	public function get_type_name() {
		return esc_html__( 'Core', 'myhome-core' );
	}

	/**
	 * @return Core_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Core_Attribute_Options_Page( $this );
	}

	/**
	 * @param  int $estate_id
	 *
	 * @return Attribute_Values
	 */
	public function get_estate_values( $estate_id ) {
		return new Attribute_Values();
	}

	/**
	 * @param bool $all_values
	 *
	 * @return Attribute_Values[]
	 */
	public function get_values( $all_values = false ) {
		return array();
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return array(
			'id'          => $this->get_ID(),
			'name'        => $this->get_name(),
			'full_name'   => $this->get_full_name(),
			'base_slug'   => $this->get_base_slug(),
			'slug'        => $this->get_slug(),
			'type'        => $this->get_type(),
			'type_name'   => $this->get_type_name(),
			'form_order'  => $this->get_form_order(),
			'placeholder' => $this->get_placeholder()
		);
	}

	public function update_options( $attribute_data ) {
		$options_page = $this->get_options_page();
		$options_page->update_options( $attribute_data );
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string           $compare
	 *
	 * @return Estate_Text_Filter
	 */
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '' ) {
		return new Estate_Text_Filter( $this, $attribute_values, $this->get_number_operator() );
	}

	/**
	 * @param int   $estate_id
	 * @param array $values
	 */
	public function update_estate_values( $estate_id, $values ) {
		// TODO: Implement update_estate_values() method.
	}


}