<?php

namespace MyHomeCore\Shortcodes;


use MyHomeCore\Users\Users_Factory;

/**
 * Class List_Agent_shortcode
 * @package MyHomeCore\Shortcodes
 */
class List_Agent_shortcode extends Shortcode {

	/**
	 * @param array $args
	 * @param null $content
	 *
	 * @return string
	 */
	public function display( $args = array(), $content = null ) {
		$atts = array(
			'limit'                  => 5,
			'agent_style'            => '',
			'description_show'       => 1,
			'social_icons_show'      => 1,
			'email_show'             => 1,
			'phone_show'             => 1,
			'button_show'            => 1,
			'exclude_admin'          => 'true',
			'additional_fields_show' => 1
		);

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			$atts = array_merge( $atts, vc_map_get_attributes( 'mh_list_agent', $args ) );
		}

		$exclude_admin        = $atts['exclude_admin'] == 'true';
		$exclude_agents       = isset( $args['exclude_agents'] ) && $args['exclude_agents'] == 'true';
		$exclude_super_agents = isset( $args['exclude_super_agents'] ) && $args['exclude_super_agents'] == 'true';
		$exclude_agency       = isset( $args['exclude_agency'] ) && $args['exclude_agency'] == 'true';

		if ( ! isset( $atts['include'] ) || empty( $atts['include'] ) ) {
			$atts['include'] = array();
		} else {
			$atts['include'] = explode( ',', $atts['include'] );
		}

		global $myhome_agents_list;
		$myhome_agents_list = $atts;

		$sort_by = isset( $args['sort_by'] ) ? $args['sort_by'] : '';

		$myhome_agents_list['agents'] = Users_Factory::get_agents( $atts['limit'], $exclude_admin, $atts['include'], $exclude_agents, $exclude_agency, $exclude_super_agents, $sort_by );

		if ( isset( $args['more_page'] ) && ! empty( $args['more_page'] ) && $args['more_page'] != 'not_set' ) {
			$myhome_agents_list['more_page'] = get_permalink( $args['more_page'] );
		} else {
			$myhome_agents_list['more_page'] = false;
		}

		if ( isset( $args['more_page_text'] ) && ! empty( $args['more_page_text'] ) ) {
			$myhome_agents_list['more_page_text'] = $args['more_page_text'];
		} else {
			$myhome_agents_list['more_page_text'] = esc_html__( 'View all', 'myhome-core' );
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
			// Agents limit
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Agents limit', 'myhome-core' ),
				'param_name' => 'limit',
				'value'      => 5,
			),
			// Exclude admins (only agent role)
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude admins', 'myhome-core' ),
				'param_name' => 'exclude_admin',
				'value'      => 'true',
				'std'        => 'true',
			),
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude agents', 'myhome-core' ),
				'param_name' => 'exclude_agents',
				'value'      => 'true',
				'std'        => ''
			),
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude super agents', 'myhome-core' ),
				'param_name' => 'exclude_super_agents',
				'value'      => 'true',
				'std'        => ''
			),
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Exclude agency', 'myhome-core' ),
				'param_name' => 'exclude_agency',
				'value'      => 'true',
				'std'        => ''
			),
			// Sort by
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Sort by', 'myhome-core' ),
				'param_name' => 'sort_by',
				'value'      => array(
					esc_html__( 'ID', 'myhome-core' )   => 'ID',
					esc_html__( 'Name', 'myhome-core' ) => 'name',
				),
			),
			// Style
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
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
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show description', 'myhome-core' ),
				'param_name' => 'description_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			// Show additional fields
			array(
				'group'      => esc_html__( 'General', 'myhome-core' ),
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
				'group'      => esc_html__( 'General', 'myhome-core' ),
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
				'group'      => esc_html__( 'General', 'myhome-core' ),
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
				'group'      => esc_html__( 'General', 'myhome-core' ),
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
				'group'      => esc_html__( 'General', 'myhome-core' ),
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Show button', 'myhome-core' ),
				'param_name' => 'button_show',
				'value'      => array(
					esc_html__( 'Yes', 'myhome-core' ) => 1,
					esc_html__( 'No', 'myhome-core' )  => 0,
				),
			),
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Users ID numbers', 'myhome-core' ),
				'description' => esc_html__( 'Use it if you wish to display only some of the users or change the order of the cards', 'myhome-core' ),
				'param_name'  => 'include',
				'value'       => '',
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
	}

}