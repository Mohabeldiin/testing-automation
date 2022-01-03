<?php

namespace MyHomeCore\Estates\Filters;


use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Terms\Term;
use MyHomeCore\Terms\Term_Factory;

/**
 * Class Estate_Offer_Type_Filter
 * @package MyHomeCore\Estates\Filters
 */
class Estate_Offer_Type_Filter extends Estate_Text_Filter {

	/**
	 * @return Term[]
	 */
	public function get_selected_offer_types() {
		$offer_types             = Term_Factory::get_offer_types();
		$selected_offer_type_ids = array();

		foreach ( $this->get_terms() as $term ) {
			if ( $this->attribute->get_search_form_control() == Field::TEXT ) {
				$term = get_term_by( 'name', $term, $this->attribute->get_slug() );
			} else {
				$term = get_term_by( 'slug', $term, $this->attribute->get_slug() );
			}

			if ( ! $term instanceof \WP_Term ) {
				continue;
			}

			$selected_offer_type_ids[] = $term->term_id;
		}

		$selected_offer_type = array_filter( $offer_types, function ( $offer_type ) use ( $selected_offer_type_ids ) {
			/* @var $offer_type \MyHomeCore\Terms\Term */
			return in_array( $offer_type->get_ID(), $selected_offer_type_ids ) && $offer_type->specify_price();
		} );

		return $selected_offer_type;
	}

}