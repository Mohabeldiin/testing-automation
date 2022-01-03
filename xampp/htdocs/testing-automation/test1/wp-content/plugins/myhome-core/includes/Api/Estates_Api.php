<?php

namespace MyHomeCore\Api;

use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Estates\Estates;
use MyHomeCore\Terms\Term;


/**
 * Class Estates_Api
 * @package MyHomeCore\Api
 */
class Estates_Api {

	/**
	 * Estates_Api constructor.
	 */
	public function __construct() {
		/*
		 * Set endpoints
		 */
		add_action(
			'rest_api_init', function () {
			register_rest_route(
				'myhome/v1', '/estates', array(
					'methods'  => 'POST',
					'callback' => array( $this, 'get' ),
                    'permission_callback' => '__return_true',
                )
			);
		}
		);

		add_action(
			'rest_api_init', function () {
			register_rest_route(
				'myhome/v1', '/suggestions-attributes', array(
					'methods'  => 'GET',
					'callback' => array( $this, 'suggestions_attributes' ),
                    'permission_callback' => '__return_true',
                )
			);
		}
		);
	}

	public function suggestions_attributes( $request ) {
		$params = $request->get_params();

		if ( empty( $params['slug'] ) || empty( $params['query'] ) ) {
			return;
		}

		$suggestions = array();
		$wp_terms    = get_terms(
			array(
				'taxonomy'   => $params['slug'],
				'name__like' => $params['query'],
				'number'     => apply_filters( 'mh_suggestions_limit', 0 )
			)
		);

		foreach ( $wp_terms as $wp_term ) {
			$term = new Term( $wp_term );
			if ( $term->is_excluded_from_search() ) {
				continue;
			}

			/* @var $wp_term \WP_Term */
			$suggestions[] = array(
				'name'           => $wp_term->name,
				'slug'           => $wp_term->slug,
				'link'           => get_term_link( $wp_term, $params['slug'] ),
				'attribute_slug' => $params['slug']
			);
		}

		return $suggestions;
	}

	public static function get( $request ) {
		if ( is_object( $request ) && method_exists( $request, 'get_params' ) ) {
			$params = $request->get_params();
		} else {
			$params = $request;
		}
		$filters         = empty( $params['data'] ) ? array() : $params['data'];
		$estates_factory = new Estate_Factory( [], true );

		foreach ( $filters as $filter ) {
			$attribute = Attribute_Factory::get_by_slug( $filter['key'] );
			if ( empty( $attribute ) ) {
				continue;
			}

			$values = new Attribute_Values();
			foreach ( $filter['values'] as $value ) {
				$values->add( new Attribute_Value( $value['name'], $value['name'], '', $value['value'] ) );
			}
			$estates_factory->add_filter( $attribute->get_estate_filter( $values, $filter['compare'] ) );
		}

		if ( isset( $params['currency'] ) && $params['currency'] != \MyHomeCore\My_Home_Core()->currency ) {
			setcookie( 'myhome_currency', $params['currency'], time() + 3600, "/" );
			\MyHomeCore\My_Home_Core()->currency = $params['currency'];
		}

		if ( isset( $params['page'] ) ) {
			$estates_factory->set_page( $params['page'] );
		}

		if ( isset( $params['sortBy'] ) ) {
			$estates_factory->set_sort_by( $params['sortBy'] );
		}

		if ( isset( $params['limit'] ) ) {
			$estates_factory->set_limit( $params['limit'] );
		}

		if ( ! empty( $params['mh_lang'] ) && function_exists( 'icl_object_id' ) ) {
			\MyHomeCore\My_Home_Core()->current_language = $params['mh_lang'];
			global $sitepress;
			$sitepress->switch_lang( $params['mh_lang'] );
		}

		if ( isset( $params['userId'] ) ) {
			$estates_factory->set_user_id( $params['userId'] );
		}

		if ( isset( $params['users'] ) ) {
			$estates_factory->set_users( $params['users'] );
		}

		if ( isset( $params['estates__in'] ) ) {
			$estates_factory->set_estates__in( $params['estates__in'] );
		}

		if ( isset( $params['featured'] ) ) {
			$estates_factory->set_featured_only();
		}

		if ( isset( $params['published_after'] ) ) {
			$estates_factory->set_published_after( $params['published_after'] );
		}

		if ( isset( $params['map'] ) && $params['map'] == 'true' ) {
			$estates_factory->set_limit( Estate_Factory::NO_LIMIT );

			return array(
				'results'       => $estates_factory->get_results()->get_data( Estates::MARKER_DATA ),
				'found_results' => $estates_factory->get_found_number()
			);
		}

		return array(
			'results'       => $estates_factory->get_results()->get_data(),
			'found_results' => $estates_factory->get_found_number()
		);
	}

}