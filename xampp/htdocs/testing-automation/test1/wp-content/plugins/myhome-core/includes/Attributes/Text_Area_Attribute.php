<?php

namespace MyHomeCore\Attributes;

/**
 * Class Text_Area_Attribute
 * @package MyHomeCore\Attributes
 */
class Text_Area_Attribute extends Attribute {

	/**
	 * @return bool
	 */
	public function get_options_page() {
		return false;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return self::TEXTAREA;
	}

	/**
	 * @return string
	 */
	public function get_type_name() {
		return esc_html__( 'Text area', 'myhome-core' );
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
	 * @return Attribute_Values
	 */
	public function get_estate_values( $estate_id ) {
		$values = new Attribute_Values();
		$text   = get_post_meta( $estate_id, 'myhome_estate_attr_' . $this->get_slug(), true );

		if ( empty( $text ) ) {
			return $values;
		}

		$values->add( new Attribute_Value( $this->get_name(), $text ) );

		return $values;
	}

	/**
	 * @return bool
	 */
	public function exclude_from_search_form() {
		return true;
	}

	public function update_options( $attribute_data ) {
		// TODO: Implement update_options() method.
	}

	public function get_values( $all_values = true ) {
		// TODO: Implement get_values() method.
	}

	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '' ) {
		// TODO: Implement get_estate_filter() method.
	}

	/**
	 * @param int   $estate_id
	 * @param array $values
	 */
	public function update_estate_values( $estate_id, $values ) {
		$value = count( $values ) > 0 ? $values[0] : '';
		update_post_meta( $estate_id, 'myhome_estate_attr_' . $this->get_slug(), $value );
	}

}