<?php

namespace MyHomeCore\Shortcodes;


use MyHomeCore\Users\Users_Factory;

/**
 * Class Agent_Carousel_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Agent_Carousel_Shortcode extends Shortcode {

	/**
	 * @param array       $args
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		wp_enqueue_script( 'myhome-carousel' );

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$args = array_merge( $args, vc_map_get_attributes( 'mh_carousel_agent', $args ) );
		}

		$exclude_admin        = $args['exclude_admin'] == 'true';
		$exclude_agents       = isset( $args['exclude_agents'] ) && $args['exclude_agents'] == 'true';
		$exclude_super_agents = isset( $args['exclude_super_agents'] ) && $args['exclude_super_agents'] == 'true';
		$exclude_agency       = isset( $args['exclude_agency'] ) && $args['exclude_agency'] == 'true';

		if ( ! isset( $args['include'] ) || empty( $args['include'] ) ) {
			$args['include'] = array();
		} else {
			$args['include'] = explode( ',', $args['include'] );
		}

		global $myhome_carousel_agents;
		$myhome_carousel_agents = Users_Factory::get_agents( $args['limit'], $exclude_admin, $args['include'], $exclude_agents, $exclude_agency, $exclude_super_agents );
		global $myhome_carousel_settings;
		$myhome_carousel_settings = array(
			'class'                  => $args['owl_visible'] . ' ' . $args['owl_dots'],
			'style'                  => $args['agent_style'],
			'email_show'             => $args['email_show'],
			'phone_show'             => $args['phone_show'],
			'button_show'            => $args['button_show'],
			'description_show'       => $args['description_show'],
			'social_icons_show'      => $args['social_icons_show'],
			'additional_fields_show' => ! isset( $args['additional_fields_show'] ) ? 1 : $args['additional_fields_show']
		);

		if ( $args['owl_auto_play'] != 'true' ) {
			$myhome_carousel_settings['class'] .= ' owl-carousel--no-auto-play';
		}

		if ( isset( $args['more_page'] ) && ! empty( $args['more_page'] ) && $args['more_page'] != 'not_set' ) {
			$myhome_carousel_settings['more_page'] = get_permalink( $args['more_page'] );
		} else {
			$myhome_carousel_settings['more_page'] = false;
		}

		if ( isset( $args['more_page_text'] ) && ! empty( $args['more_page_text'] ) ) {
			$myhome_carousel_settings['more_page_text'] = $args['more_page_text'];
		} else {
			$myhome_carousel_settings['more_page_text'] = esc_html__( 'View all', 'myhome-core' );
		}


		return $this->get_template();
	}

	/**
	 * @return array
	 */
	public function get_vc_params() {
		$pages      = get_pages( array( 'post_status' => 'publish', 'posts_per_page' => - 1 ) );
		$pages_list = array( esc_html__( 'Not set', 'myhome-core' ) => 'not_set' );

		foreach ( $pages as $page ) {
			/* @var $page \WP_Post */
			$pages_list[ $page->post_title ] = $page->ID;
		}

		return array(
			// Visible
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Visible', 'myhome-core' ),
				'param_name' => 'owl_visible',
				'value'      => array(
					esc_html__( 'Default - 3', 'myhome-core' ) => 'owl-carousel--visible-3',
					esc_html__( '1 ', 'myhome-core' )          => 'owl-carousel--visible-1',
					esc_html__( '2 ', 'myhome-core' )          => 'owl-carousel--visible-2',
					esc_html__( '3 ', 'myhome-core' )          => 'owl-carousel--visible-3',
					esc_html__( '4 ', 'myhome-core' )          => 'owl-carousel--visible-4',
				)
			),
			// Agents limit
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Agents limit', 'myhome-core' ),
				'param_name' => 'limit',
				'value'      => 5,
			),
			array(
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude admins', 'myhome-core' ),
				'param_name' => 'exclude_admin',
				'value'      => 'true',
				'std'        => 'true',
			),
			array(
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude agents', 'myhome-core' ),
				'param_name' => 'exclude_agents',
				'value'      => 'true',
				'std'        => ''
			),
			array(
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude super agents', 'myhome-core' ),
				'param_name' => 'exclude_super_agents',
				'value'      => 'true',
				'std'        => ''
			),
			array(
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude agency', 'myhome-core' ),
				'param_name' => 'exclude_agency',
				'value'      => 'true',
				'std'        => ''
			),
			// Style
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Style', 'myhome-core' ),
				'param_name' => 'agent_style',
				'value'      => array(
					esc_html__( 'Default', 'myhome-core' )          => '',
					esc_html__( 'White Background', 'myhome-core' ) => 'mh-agent--white',
					esc_html__( 'Dark Background', 'myhome-core' )  => 'mh-agent--dark',
				),
			),
			// Show description
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show description', 'myhome-core' ),
				'param_name' => 'description_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Show additional_fields
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show additional fields', 'myhome-core' ),
				'param_name' => 'additional_fields_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Show Social Icons
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show social icons', 'myhome-core' ),
				'param_name' => 'social_icons_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Show email
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show Email', 'myhome-core' ),
				'param_name' => 'email_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Show phone
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show phone', 'myhome-core' ),
				'param_name' => 'phone_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Show button
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show button', 'myhome-core' ),
				'param_name' => 'button_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Dots
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Dots', 'myhome-core' ),
				'param_name' => 'owl_dots',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => '',
					esc_html__( 'No', 'myhome-core' )  => 'owl-carousel--no-dots',
				),
			),
			// Auto play
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Auto play', 'myhome-core' ),
				'param_name'  => 'owl_auto_play',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Users ID numbers', 'myhome-core' ),
				'description' => esc_html__( 'Use it if you wish to display only some of the users or change the order of the cards', 'myhome-core' ),
				'param_name'  => 'include',
				'value'       => '',
				'save_always' => true
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'More button text', 'myhome-core' ),
				'param_name'  => 'more_page_text',
				'value'       => esc_html__( 'View all', 'myhome-core' ),
				'save_always' => true
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'More button', 'myhome-core' ),
				'param_name'  => 'more_page',
				'value'       => $pages_list,
				'save_always' => true
			)
		);
	}

}