<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Filters\Estate_ID_Filter;

/**
 * Class Property_ID_Attribute
 * @package MyHomeCore\Attributes
 */
class Property_ID_Attribute extends Core_Attribute {

	const BASE_SLUG = 'property_id';


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
			'placeholder' => $this->get_placeholder(),
			'full_width'  => $this->get_full_width(),
			'card_show'   => $this->get_card_show(),
		);
	}

	/**
	 * @return bool
	 */
	public function get_property_show() {
		return $this->get_card_show();
	}

	/**
	 * @param array $attribute_data
	 */
	public function update_options( $attribute_data ) {
		$options_page = $this->get_options_page();
		$options_page->update_options( $attribute_data );
	}

	/**
	 * @return Property_ID_Options_Page()
	 */
	public function get_options_page() {
		return new Property_ID_Options_Page( $this );
	}

	/**
	 * @return string
	 */
	public function get_search_form_control() {
		return Field::ID;
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string           $compare
	 *
	 * @return Estate_ID_Filter
	 */
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '' ) {
		return new Estate_ID_Filter( $this, $attribute_values, $this->get_number_operator() );
	}

	/**
	 * @return bool
	 */
	public function is_estate_attribute() {
		return true;
	}

	/**
	 * @param Estate $estate
	 *
	 * @return Attribute_Values
	 */
	public function get_estate_values( $estate ) {
		$value = new Attribute_Value( $estate->get_ID(), $estate->get_ID() );

		return new Attribute_Values( array( $value ) );
	}

	/**
	 * @param int   $estate_id
	 * @param array $values
	 */
	public function update_estate_values( $estate_id, $values ) {
		return;
	}

}