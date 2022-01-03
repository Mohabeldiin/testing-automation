<?php

namespace MyHomeCore\Common\Breadcrumbs;

use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Offer_Type_Attribute;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Terms\Term;


/**
 * Class Breadcrumb
 * @package MyHomeCore\Common\Breadcrumbs
 */
class Breadcrumb {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $link;

	/**
	 * @var bool|Text_Attribute
	 */
	private $attribute;

	/**
	 * @var bool|Term
	 */
	private $term;

	/**
	 * @var int
	 */
	private $estate_ids;

	/**
	 * @var int
	 */
	private $count = 0;

	/**
	 * Breadcrumb constructor.
	 *
	 * @param                     $name
	 * @param                     $link
	 * @param Text_Attribute|bool $attribute
	 * @param Term|bool           $term
	 * @param array               $estate_ids
	 */
	public function __construct( $name, $link, $attribute = false, $term = false, $estate_ids = array() ) {
		$this->name       = $name;
		$this->link       = $link;
		$this->attribute  = $attribute;
		$this->term       = $term;
		$this->estate_ids = $estate_ids;
	}

	/**
	 * @return array
	 */
	public function get_ids_filtered() {
		$wp_query = new \WP_Query( array(
			'post_type'      => 'estate',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => - 1,
			'tax_query'      => array(
				array(
					'taxonomy' => $this->attribute->get_slug(),
					'field'    => 'slug',
					'terms'    => $this->term->get_slug()
				)
			)
		) );

		$ids = $wp_query->posts;

		if ( ! empty( $this->estate_ids ) ) {
			$ids = array_filter( $this->estate_ids, function ( $id ) use ( $ids ) {
				return in_array( $id, $ids );
			} );
		}

		$this->count = count( $ids );

		return $ids;
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
		if ( $this->term == false ) {
			return $this->link;
		}

		return $this->link . $this->term->get_slug() . '/';
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		if ( $this->term == false ) {
			return '';
		}

		return $this->term->get_slug();
	}

	/**
	 * @return bool
	 */
	public function has_elements() {
		return ! empty( $this->attribute );
	}

	/**
	 * @return Text_Attribute
	 */
	public function get_attribute() {
		return $this->attribute;
	}

	/**
	 * @return Breadcrumb_Value[]
	 */
	public function get_values() {
		if ( $this->attribute->has_parent() ) {
			$parent_attribute = Attribute::get_by_id( $this->attribute->get_parent_id() );
			$parent_value     = get_query_var( $parent_attribute->get_slug() );
			$values_by_parent = $this->attribute->get_values_by_parent();

			if ( isset( $values_by_parent[ $parent_value ] ) ) {
				$values = $values_by_parent[ $parent_value ];
			} else {
				$values = $values_by_parent['any'];
			}
		} else {
			$attribute_values = $this->attribute->get_values();
			$values           = isset( $attribute_values['any'] ) ? $attribute_values['any'] : array();
		}

		$excluded_offer_types = Offer_Type_Attribute::get_exclude();

		$breadcrumb_values = array();
		foreach ( $values as $value ) {
			$wp_query = new \WP_Query( array(
				'post_type'      => 'estate',
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'tax_query'      => array_merge( array( $excluded_offer_types ), array(
					array(
						'taxonomy' => $this->attribute->get_slug(),
						'field'    => 'slug',
						'terms'    => $value->get_slug()
					)
				) )
			) );

			$ids = $wp_query->posts;

			if ( empty( $this->estate_ids ) ) {
				$current_ids = $ids;
			} else {
				$current_ids = array_filter( $this->estate_ids, function ( $id ) use ( $ids ) {
					return in_array( $id, $ids );
				} );
			}

			$count = count( $current_ids );
			if ( $count ) {
				$breadcrumb_values[] = new Breadcrumb_Value( $value, $this->link, $count );
			}
		}

		return $breadcrumb_values;
	}

	/**
	 * @return int
	 */
	public function get_count() {
		if ( $this->attribute == false && $this->term == false ) {
			$excluded_offer_types = Offer_Type_Attribute::get_exclude();

			$wp_query = new \WP_Query( array(
				'post_type'      => 'estate',
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'tax_query'      => array(
					$excluded_offer_types
				)
			) );

			return $wp_query->found_posts;
		}

		return $this->count;
	}

}