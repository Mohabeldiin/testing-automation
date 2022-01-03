<?php

namespace MyHomeCore\Terms;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Text_Attribute;

/**
 * Class Term_Factory
 * @package MyHomeCore\Terms
 */
class Term_Factory {

	public static $offer_types = array();

	const ALL = 0;
	const ORDER_BY_COUNT = 'count';
	const ORDER_BY_NAME = 'name';
	const ORDER_ASC = 'asc';
	const ORDER_DESC = 'desc';

	/**
	 * @param Text_Attribute $attribute
	 * @param int $limit
	 * @param string $orderby
	 * @param string $order
	 * @param bool $hide_empty
	 *
	 * @return Term[]
	 */
	public static function get( Text_Attribute $attribute, $limit = Term_Factory::ALL, $orderby = Term_Factory::ORDER_BY_COUNT, $order = Term_Factory::ORDER_DESC, $hide_empty = false ) {
		$terms    = array();
		$wp_terms = get_terms(
			array(
				'taxonomy'   => $attribute->get_slug(),
				'number'     => intval( $limit ),
				'hide_empty' => $hide_empty,
				'orderby'    => $orderby,
				'order'      => $order
			)
		);

		if ( is_array( $wp_terms ) ) {
			foreach ( $wp_terms as $term ) {
				$terms[ $term->term_id ] = new Term( $term );
			}
		}

		return $terms;
	}

	/**
	 * @param integer|\WP_Post $estate_id
	 * @param Text_Attribute $attribute
	 *
	 * @return Term[]
	 */
	public static function get_from_estate( $estate_id, Text_Attribute $attribute ) {
		$terms    = array();
		$wp_terms = get_the_terms( $estate_id, $attribute->get_slug() );

		if ( is_array( $wp_terms ) ) {
			foreach ( $wp_terms as $term ) {
				$terms[ $term->term_id ] = new Term( $term );
			}
		}

		return $terms;
	}

	/**
	 * @return Term[]
	 */
	public static function get_offer_types() {
		if ( empty( self::$offer_types ) || function_exists( 'icl_object_id' ) ) {
			$offer_type_attribute = Attribute_Factory::get_offer_type();
			if ( ! $offer_type_attribute ) {
				return [];
			}
			self::$offer_types = Term_Factory::get( $offer_type_attribute );
		}

		return self::$offer_types;
	}

}