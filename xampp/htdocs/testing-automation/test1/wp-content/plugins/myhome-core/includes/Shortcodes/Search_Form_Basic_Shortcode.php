<?php

namespace MyHomeCore\Shortcodes;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Components\Listing\Form\Fields;

/**
 * Class Search_Form_Basic_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Search_Form_Basic_Shortcode extends Shortcode {

	public function display( $args = array(), $content = null ) {
		$config = array();

		if ( isset( $args['primary_selector'] ) && $args['primary_selector'] != 'empty' ) {
			$primary_selector_id = intval( $args['primary_selector'] );
			$primary_attribute   = Attribute_Factory::get_by_ID( $primary_selector_id );

			if ( ! $primary_attribute instanceof Text_Attribute ) {
				$config['primary_selector']       = '';
				$config['primary_selector_value'] = '';
			} else {
				$field                            = new Field( $primary_attribute );
				$config['primary_selector']       = $field->get_data();
				$config['primary_selector_value'] = $args[ 'primary_selector_value_' . $primary_attribute->get_ID() ];
			}
		}

		$config['key']    = 'MyHomeSearchFormBasic' . time();
		$config['fields'] = array();

		if ( ! empty( $args['listing_page'] ) ) {
			if ( $args['listing_page'] == 'properties_archive' ) {
				$config['listing_page_url'] = get_post_type_archive_link( 'estate' );
				$config['is_homepage']      = false;
			} else {
				$listing_page = get_post( $args['listing_page'] );

				if ( is_wp_error( $listing_page ) ) {
					$config['listing_page_url'] = '';
					$config['is_homepage']      = false;
				} else {
					$config['listing_page_url'] = get_the_permalink( $listing_page );
					$front_page_id              = get_option( 'page_on_front' );
					$config['is_homepage']      = $listing_page->ID == $front_page_id;
				}
			}
		} else {
			$config['listing_page_url'] = '';
			$config['is_homepage']      = false;
		}

		$fields = array();
		for ( $i = 1; $i <= 3; $i ++ ) {
			if ( ! isset( $args[ 'field_' . $i ] ) ) {
				continue;
			}

			$attribute = Attribute_Factory::get_by_ID( $args[ 'field_' . $i ] );
			if ( $attribute instanceof Attribute ) {
				$fields[] = new Field( $attribute );
			}
		}

		$fields_obj             = new Fields( $fields );
		$config['fields']       = $fields_obj->get_data();
		$config['search_label'] = $args['search_label'];

		wp_localize_script( 'myhome-frontend', $config['key'], $config );

		global $myhome_search_form_basic;
		$myhome_search_form_basic['key']     = $config['key'];
		$myhome_search_form_basic['heading'] = $args['heading'];

		$image = wp_get_attachment_image_src( $args['image'], 'full' );
		if ( ! empty( $image ) ) {
			$myhome_search_form_basic['image'] = $image[0];
		} else {
			$myhome_search_form_basic['image'] = '';
		}

		return $this->get_template();
	}

	public function get_vc_params() {
		$text_attributes        = Attribute_Factory::get_text();
		$primary_selector       = array( esc_html__( 'Not selected', 'myhome-core' ) => 'empty' );
		$primary_selector_terms = array();
		foreach ( $text_attributes as $attribute ) {
			$primary_selector[ $attribute->get_name() ] = $attribute->get_ID();

			$primary_selector_terms[ $attribute->get_ID() ] = get_terms( $attribute->get_slug() );
		}

		$fields = array(
			array(
				'heading'     => esc_html__( 'Primary selector', 'myhome-core' ),
				'param_name'  => 'primary_selector',
				'type'        => 'dropdown',
				'value'       => $primary_selector,
				'save_always' => true,
			)
		);

		foreach ( $primary_selector_terms as $attribute_id => $terms ) {
			$terms_list = array();
			foreach ( $terms as $term ) {
				/*  @var $term \WP_Term */
				$terms_list[ $term->name ] = $term->slug;
			}

			$fields[] = array(
				'heading'     => esc_html__( 'Initial value', 'myhome-core' ),
				'param_name'  => 'primary_selector_value_' . $attribute_id,
				'type'        => 'dropdown',
				'value'       => $terms_list,
				'save_always' => true,
				'dependency'  => array(
					'element' => 'primary_selector',
					'value'   => array( "$attribute_id" )
				)
			);
		}

		$attribute_list = array( esc_html__( 'Not selected', 'myhome-core' ) => 'empty' );
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$attribute_list[ $attribute->get_name() ] = $attribute->get_ID();
		}

		$fields[]   = array(
			'heading'     => esc_html__( 'Field 1', 'myhome-core' ),
			'param_name'  => 'field_1',
			'type'        => 'dropdown',
			'value'       => $attribute_list,
			'save_always' => true
		);
		$fields[]   = array(
			'heading'     => esc_html__( 'Field 2', 'myhome-core' ),
			'param_name'  => 'field_2',
			'type'        => 'dropdown',
			'value'       => $attribute_list,
			'save_always' => true
		);
		$fields[]   = array(
			'heading'     => esc_html__( 'Field 3', 'myhome-core' ),
			'param_name'  => 'field_3',
			'type'        => 'dropdown',
			'value'       => $attribute_list,
			'save_always' => true
		);
		$fields[]   = array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Heading', 'myhome-core' ),
			'param_name'  => 'heading',
			'save_always' => true,
			'value'       => ''
		);
		$fields[]   = array(
			'type'        => 'attach_image',
			'heading'     => esc_html__( 'Background image', 'myhome-core' ),
			'param_name'  => 'image',
			'save_always' => true
		);
		$fields[]   = array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Search button text', 'myhome-core' ),
			'param_name'  => 'search_label',
			'value'       => esc_html__( 'Search', 'myhome-core' ),
			'save_always' => true
		);
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
		$pages_list = array_merge( array( esc_html__( 'Properties archive', 'myhome-core' ) => 'properties_archive' ), $pages_list );

		$fields[] = array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Choose page where clicking "Search" button redirect visitors', 'myhome-core' ),
			'param_name'  => 'listing_page',
			'save_always' => true,
			'value'       => $pages_list
		);


		return $fields;
	}

}