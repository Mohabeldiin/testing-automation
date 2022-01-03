<?php

namespace MyHomeCore\Attributes;


use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Filters\Estate_Offer_Type_Filter;
use MyHomeCore\Terms\Term_Factory;

/**
 * Class Offer_Type_Attribute
 * @package MyHomeCore\Attributes
 */
class Offer_Type_Attribute extends Text_Attribute {

	public static $exclude = false;

	/**
	 * @return string
	 */
	public function get_display_after() {
		return '';
	}

	/**
	 * @param Estate $estate
	 *
	 * @return Attribute_Values
	 */
	public function get_estate_values( $estate ) {
		$values = array();
		$terms  = Term_Factory::get_from_estate( $estate->get_ID(), $this );

		foreach ( $terms as $term ) {
			$options = array(
				'has_label' => $term->has_label(),
				'bg_color'  => $term->get_bg_color(),
				'color'     => $term->get_color()
			);

			$values[] = new Attribute_Value( $term->get_name(), $term->get_name(), $term->get_link(), $term->get_slug(), $options );
		}

		return new Attribute_Values( $values );
	}

	/**
	 * @param Attribute_Values $attribute_values
	 * @param string $compare
	 *
	 * @return Estate_Offer_Type_Filter
	 */
	public function get_estate_filter( Attribute_Values $attribute_values, $compare = '' ) {
		return new Estate_Offer_Type_Filter( $this, $attribute_values, $this->get_number_operator() );
	}

	/**
	 * @return array|bool
	 */
	public static function get_exclude() {
		if ( self::$exclude != false ) {
			return self::$exclude;
		}

		$offer_type_attribute = Attribute_Factory::get_offer_type();

		if ( $offer_type_attribute == false ) {
			return array();
		}

		$offer_types = Term_Factory::get( $offer_type_attribute );
		$not_in      = array();

		foreach ( $offer_types as $offer_type ) {
			if ( $offer_type->is_excluded_from_search() ) {
				$not_in[] = $offer_type->get_slug();
			}
		}

		if ( ! empty( $not_in ) ) {
			self::$exclude = array(
				'taxonomy' => $offer_type_attribute->get_slug(),
				'operator' => 'NOT IN',
				'field'    => 'slug',
				'terms'    => $not_in
			);
		}

		return self::$exclude;
	}

}