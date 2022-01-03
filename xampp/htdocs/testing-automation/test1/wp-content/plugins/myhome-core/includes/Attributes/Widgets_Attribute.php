<?php

namespace MyHomeCore\Attributes;
use MyHomeCore\Estates\Estate_Filter;

/**
 * Class Widgets_Attribute
 * @package MyHomeCore\Attributes
 */
class Widgets_Attribute extends Attribute {

	/**
	 * @return Widgets_Attribute_Options_Page
	 */
	public function get_options_page() {
		return new Widgets_Attribute_Options_Page( $this );
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::WIDGETS;
	}

	/**
	 * @return string
	 */
	public function get_type_name() {
		return esc_html__( 'Widgets', 'myhome-core' );
	}

	/**
	 * @return bool
	 */
	public function has_archive() {
		return false;
	}

	/**
	 * @param int $estate_id
	 *
	 * @return array
	 */
	public function get_estate_values( $estate_id ) {
		return array();
	}

	/**
	 * @return string
	 */
	public function get_class() {
		$columns = get_field( $this->get_slug() . '_columns', 'option' );

		return empty( $columns ) ? 'mh-grid__1of1' : $columns;
	}

	public function update_options( $attribute_data ) {}
	public function get_values( $all_values = true) {}
	public function update_estate_values( $estate_id, $values ) {}
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '=' ) {}


}