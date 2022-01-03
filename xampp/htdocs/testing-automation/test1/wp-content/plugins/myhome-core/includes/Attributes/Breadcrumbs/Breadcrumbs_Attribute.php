<?php

namespace MyHomeCore\Attributes\Breadcrumbs;


use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Common\Breadcrumbs\Breadcrumbs;
use MyHomeCore\Terms\Term;

/**
 * Class Breadcrumbs_Attribute
 * @package MyHomeCore\Attributes\Breadcrumbs
 */
class Breadcrumbs_Attribute {

	/**
	 * @var Text_Attribute
	 */
	private $term;

	/**
	 * @var Term[]
	 */
	private $elements = array();

	/**
	 * Breadcrumbs_Attribute constructor.
	 */
	public function __construct() {
		$attributes = Breadcrumbs::get_attributes();

		foreach ( $attributes as $key => $attribute ) {
			$value = get_query_var( $attribute->get_slug(), '' );

			if ( empty( $value ) ) {
				continue;
			}

			$wp_term          = get_term_by( 'slug', $value, $attribute->get_slug() );
			$term             = new Term( $wp_term );
			$this->elements[] = $term;
			$this->term       = $term;
		}
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->term->get_name();
	}

	public function get_elements() {

	}

}