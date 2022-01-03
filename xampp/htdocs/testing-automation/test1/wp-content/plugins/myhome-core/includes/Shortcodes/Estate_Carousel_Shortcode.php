<?php

namespace MyHomeCore\Shortcodes;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Estates\Estates;
use MyHomeCore\Users\Users_Factory;

/**
 * Class Estate_Carousel_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Estate_Carousel_Shortcode extends Shortcode {

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		wp_enqueue_script( 'myhome-carousel' );

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$args = array_merge( $args, vc_map_get_attributes( 'mh_carousel_estate', $args ) );
		}

		global $myhome_estate_carousel;
		$myhome_estate_carousel          = $args;
		$myhome_estate_carousel['class'] = $args['owl_visible'] . ' ' . $args['owl_dots'];
		if ( $args['owl_auto_play'] != 'true' ) {
			$myhome_estate_carousel['class'] .= ' owl-carousel--no-auto-play';
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_show_date'] ) ) {
			$myhome_estate_carousel['show_date'] = filter_var( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_show_date'], FILTER_VALIDATE_BOOLEAN );
		} else {
			$myhome_estate_carousel['show_date'] = true;
		}

		if ( isset( $args['more_page'] ) && ! empty( $args['more_page'] ) && $args['more_page'] != 'not_set' ) {
			$myhome_estate_carousel['more_page'] = get_permalink( $args['more_page'] );
		} else {
			$myhome_estate_carousel['more_page'] = false;
		}

		if ( isset( $args['more_page_text'] ) && ! empty( $args['more_page_text'] ) ) {
			$myhome_estate_carousel['more_page_text'] = $args['more_page_text'];
		} else {
			$myhome_estate_carousel['more_page_text'] = esc_html__( 'View all', 'myhome-core' );
		}

		global $myhome_estates;
		$myhome_estates = $this->get_estates( $args );

		return $this->get_template();
	}

	/**
	 * @param array $args
	 *
	 * @return Estates
	 */
	private function get_estates( $args ) {
		$estate_factory = new Estate_Factory();

		if ( ! empty( $args['limit'] ) ) {
			$estate_factory->set_limit( $args['limit'] );
		}

		if ( ! empty( $args['estates__in'] ) ) {
			$estate_ids = explode( ',', $args['estates__in'] );
			$estate_factory->set_estates__in( $estate_ids );
		}

		if ( ! empty( $args['sort'] ) ) {
			$estate_factory->set_sort_by( $args['sort'] );
		}

		if ( ! empty( $args['agent_id'] ) ) {
			$estate_factory->set_user_id( $args['agent_id'] );
		}

		if ( ! empty( $args['featured'] ) && $args['featured'] == 'true' ) {
			$estate_factory->set_featured_only();
		}

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

				$values = new Attribute_Values(
					array( new Attribute_Value( $args[ $key ], $args[ $key ], '', $args[ $key ] ) )
				);
				if ( ! $attribute instanceof Price_Attribute ) {
					$estate_factory->add_filter( $attribute->get_estate_filter( $values ) );
				} elseif ( strpos( $key, 'from' ) !== false ) {
					$estate_factory->add_filter( $attribute->get_estate_filter( $values, '>=' ) );
				} elseif ( strpos( $key, 'to' ) !== false ) {
					$estate_factory->add_filter( $attribute->get_estate_filter( $values, '<=' ) );
				} else {
					$estate_factory->add_filter( $attribute->get_estate_filter( $values ) );
				}
			}
		}

		return $estate_factory->get_results();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		$agents      = Users_Factory::get_agents();
		$agents_list = array( esc_html__( 'Any', 'myhome-core' ) => 0 );
		foreach ( $agents as $agent ) {
			$agents_list[ $agent->get_name() ] = $agent->get_ID();
		}

		$pages      = get_pages( array( 'post_status' => 'publish', 'posts_per_page' => - 1 ) );
		$pages_list = array( esc_html__( 'Not set', 'myhome-core' ) => 'not_set' );

		foreach ( $pages as $page ) {
			/* @var $page \WP_Post */
			$pages_list[ $page->post_title ] = $page->ID;
		}

		$fields = array(
			// Style
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'myhome-core' ),
				'param_name'  => 'estate_style',
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' )          => '',
					esc_html__( 'White Background', 'myhome-core' ) => 'mh-estate-vertical--white',
					esc_html__( 'Dark Background', 'myhome-core' )  => 'mh-estate-vertical--dark',
				),
				'save_always' => true
			),
			// Properties in
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Properties IDs', 'myhome-core' ),
				'param_name'  => 'estates__in',
				'value'       => '',
				'save_always' => true
			),
			// Properties limit
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Properties limit', 'myhome-core' ),
				'param_name'  => 'limit',
				'value'       => 5,
				'save_always' => true
			),
			// Featured
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Featured', 'myhome-core' ),
				'param_name'  => 'featured',
				'save_always' => true,
				'value'       => 'true'
			),
			// Sort by
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Sort by', 'myhome-core' ),
				'param_name'  => 'sort',
				'value'       => array(
					esc_html__( 'Newest', 'myhome-core' )                     => Estate_Factory::ORDER_BY_NEWEST,
					esc_html__( 'Price (high to low)', 'myhome-core' )        => Estate_Factory::ORDER_BY_PRICE_HIGH_TO_LOW,
					esc_html__( 'Price (low to high)', 'myhome-core' )        => Estate_Factory::ORDER_BY_PRICE_LOW_TO_HIGH,
					esc_html__( 'Popular', 'myhome-core' )                    => Estate_Factory::ORDER_BY_POPULAR,
					esc_html__( 'Alphabetical order', 'myhome-core' )         => Estate_Factory::ORDER_BY_TITLE_ASC,
					esc_html__( 'Reverse alphabetical order', 'myhome-core' ) => Estate_Factory::ORDER_BY_TITLE_DESC,
				),
				'save_always' => true
			),
			// Agent
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Agent', 'myhome-core' ),
				'param_name'  => 'agent_id',
				'value'       => $agents_list,
				'save_always' => true
			),
			// Visible
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Visible', 'myhome-core' ),
				'param_name'  => 'owl_visible',
				'value'       => array(
					esc_html__( 'Default - 3', 'myhome-core' ) => 'owl-carousel--visible-3',
					esc_html__( '1 ', 'myhome-core' )          => 'owl-carousel--visible-1',
					esc_html__( '2 ', 'myhome-core' )          => 'owl-carousel--visible-2',
					esc_html__( '3 ', 'myhome-core' )          => 'owl-carousel--visible-3',
				),
				'save_always' => true
			),
			// Dots
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Dots', 'myhome-core' ),
				'param_name'  => 'owl_dots',
				'value'       => array(
					esc_html__( 'Yes', 'myhome-core' ) => '',
					esc_html__( 'No', 'myhome-core' )  => 'owl-carousel--no-dots',
				),
				'save_always' => true
			),
			// Auto play
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Auto play', 'myhome-core' ),
				'param_name'  => 'owl_auto_play',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true
			),
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'More button text', 'myhome-core' ),
				'param_name'  => 'more_page_text',
				'value'       => esc_html__( 'View all', 'myhome-core' ),
				'save_always' => true
			),
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'More button', 'myhome-core' ),
				'param_name'  => 'more_page',
				'value'       => $pages_list,
				'save_always' => true
			)
		);

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$fields = $attribute->get_vc_control( $fields );
		}

		return $fields;
	}

}