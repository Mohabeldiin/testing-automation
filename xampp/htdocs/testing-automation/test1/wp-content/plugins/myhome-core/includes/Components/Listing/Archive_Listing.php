<?php

namespace MyHomeCore\Components\Listing;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Common\Breadcrumbs\Breadcrumbs;
use MyHomeCore\Estates\Estate_Factory;

/**
 * Class Archive_Listing
 * @package MyHomeCore\Components\Listing
 */
class Archive_Listing {

	public function display() {
		$show_advanced                  = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_advanced'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_advanced'] );
		$show_clear                     = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_clear'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_clear'] );
		$show_gallery                   = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_gallery'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_gallery'] );
		$show_sort_by                   = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by'] );
		$show_view_types                = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_view_types'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_view_types'] );
		$advanced_number                = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_advanced_number'] ) ? 3 : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_advanced_number'] );
		$show_results_number            = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing_show-results-number'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing_show-results-number'] );
		$listing_type                   = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-type'] ) ? 'load_more' : \MyHomeCore\My_Home_Core()->settings->props['mh-listing-type'];
		$show_sort_by_newest            = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_newest'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_newest'] );
		$show_sort_by_popular           = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_popular'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_popular'] );
		$show_sort_by_price_high_to_low = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_high_to_low'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_high_to_low'] );
		$show_sort_by_price_low_to_high = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_low_to_high'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_low_to_high'] );
		$show_sort_by_alphabetically    = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_alphabetically'] ) ? false : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_alphabetically'] );
		$hide_save_search               = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-hide_save_search'] ) ? false : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-hide_save_search'] );
		$search_form                    = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form'] ) ? 'default' : \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form'];
		$sort_by                        = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_default'] ) ? 'newest' : \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_default'];

		$atts = array(
			'lazy_loading'                   => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-lazy_loading'] ? 'true' : 'false',
			'lazy_loading_limit'             => intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-load_more_button_number'] ),
			'load_more_button'               => apply_filters( 'wpml_translate_single_string', \MyHomeCore\My_Home_Core()->settings->props['mh-listing-load_more_button_label'], 'myhome-core', 'mh-load-more-button' ),
			'listing_default_view'           => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-default_view'],
			'estates_per_page'               => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-estates_limit'],
			'search_form_position'           => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_position'],
			'label'                          => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-label'],
			'search_form_advanced_number'    => $advanced_number,
			'listing_sort_by'                => $sort_by,
			'show_advanced'                  => $show_advanced ? 'true' : 'false',
			'show_clear'                     => $show_clear ? 'true' : 'false',
			'show_sort_by'                   => $show_sort_by ? 'true' : 'false',
			'show_view_types'                => $show_view_types ? 'true' : 'false',
			'show_gallery'                   => $show_gallery ? 'true' : 'false',
			'show_results_number'            => $show_results_number ? 'true' : 'false',
			'listing_type'                   => $listing_type,
			'show_sort_by_newest'            => $show_sort_by_newest ? 'true' : 'false',
			'show_sort_by_popular'           => $show_sort_by_popular ? 'true' : 'false',
			'show_sort_by_price_high_to_low' => $show_sort_by_price_high_to_low ? 'true' : 'false',
			'show_sort_by_price_low_to_high' => $show_sort_by_price_low_to_high ? 'true' : 'false',
			'show_sort_by_alphabetically'    => $show_sort_by_alphabetically ? 'true' : 'false',
			'hide_save_search'               => $hide_save_search ? 'true' : '',
			'search_form'                    => $search_form,
		);

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			if ( isset( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $attribute->get_slug() . '_show' ] ) ) {
				$atts[ $attribute->get_slug() . '_show' ] = ! empty( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $attribute->get_slug() . '_show' ] );
			} else {
				$atts[ $attribute->get_slug() . '_show' ] = true;
			}

			if ( is_tax( $attribute->get_slug() ) && ! is_post_type_archive( 'estate' ) ) {
				$atts[ $attribute->get_slug() . '_show' ] = false;
			}

			if ( $attribute instanceof Text_Attribute ) {
				$breadcrumb_attributes = Breadcrumbs::get_attributes();
				$value                 = get_query_var( $attribute->get_slug(), '' );

				if ( empty( $value ) ) {
					continue;
				}

				foreach ( $breadcrumb_attributes as $attr ) {
					$attr_value = get_query_var( $attr->get_slug() );

					if ( empty( $attr_value ) ) {
						break;
					}

					if ( $attr->get_ID() == $attribute->get_ID() ) {
						$atts[ $attribute->get_slug() . '_show' ] = false;
					}
				}

				$atts[ $attribute->get_slug() ] = $value;
			}
		}

		$listing = new Listing( $atts );
		$listing->display();
	}

}