<?php

namespace MyHomeCore\Shortcodes;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Price_Attribute_Options_Page;
use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Components\Listing\Form\Fields;
use MyHomeCore\Components\Listing\Search_Forms\Search_Form;

class Search_Form_Submit_Shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param null  $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		wp_enqueue_script( 'myhome-frontend' );

		global $myhome_search_form_submit;

		$dependencies = array();
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$dependencies[ $attribute->get_slug() ] = $attribute->get_property_type_dependency();
		}

		$default_values = array();
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$form_control = $attribute->get_search_form_control();
			if ( $form_control == Field::TEXT_RANGE || $form_control == Field::SELECT_RANGE ) {
				$keys = array( $attribute->get_slug() . '_from', $attribute->get_slug() . '_to' );
			} else {
				$keys = array( $attribute->get_slug() );
			}

			foreach ( $keys as $key ) {
				if ( empty( $args[ $key ] ) || $args[ $key ] == 'any' ) {
					continue;
				}
				$default_values[ $key ] = $args[ $key ];
			}
		}

		if ( ! empty( $args['listing_page'] ) ) {
			$listing_page = get_post( $args['listing_page'] );

			if ( is_wp_error( $listing_page ) ) {
				$listing_page_url = '';
				$is_homepage      = false;
			} else {
				$listing_page_url = get_the_permalink( $listing_page );
				$front_page_id    = get_option( 'page_on_front' );
				$is_homepage      = $listing_page->ID == $front_page_id;
			}
		} else {
			$listing_page_url = '';
			$is_homepage      = false;
		}

		$myhome_search_form_submit = array(
			'search_label'   => esc_html__( 'Search', 'myhome-core' ),
			'fields'         => Fields::get( $args )->get_data(),
			'api_endpoint'   => get_rest_url() . 'myhome/v1/estates',
			'dependencies'   => $dependencies,
			'default_values' => $default_values,
			'current_values' => $default_values,
			'currencies'     => Price_Attribute_Options_Page::get_currencies(),
			'site'           => site_url()
		);

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$myhome_search_form_submit = array_merge( $myhome_search_form_submit, vc_map_get_attributes( 'mh_search_form_submit', $args ) );
		}

		$myhome_search_form_submit['homepage']     = $is_homepage;
		$myhome_search_form_submit['listing_page'] = $listing_page_url;

		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		$pages_list = array();
		$pages      = get_pages(
			array(
				'sort_order'  => 'asc',
				'sort_column' => 'post_title',
			)
		);

		foreach ( $pages as $page ) {
			/* @var $page \WP_Post */
			$pages_list[ $page->post_title ] = $page->ID;
		}
		ksort( $pages_list );

		$params = array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Search form', 'myhome-core' ),
				'param_name'  => 'search_form',
				'value'       => Search_Form::get_vc_search_form_list(),
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'save_always' => true
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Search button text', 'myhome-core' ),
				'param_name'  => 'search_label',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'value'       => esc_html__( 'Search', 'myhome-core' )
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Choose page where clicking "Search" button redirect visitors', 'myhome-core' ),
				'param_name'  => 'listing_page',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'value'       => $pages_list
			)
		);

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$params[] = array(
				'type'        => 'checkbox',
				'heading'     => sprintf( esc_html__( 'Show % s', 'myhome-core' ), $attribute->get_name() ),
				'param_name'  => $attribute->get_slug() . '_show',
				'group'       => esc_html__( 'Show filters', 'myhome - core' ),
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			);
			$params   = $attribute->get_vc_control( $params );
		}

		return $params;
	}

}