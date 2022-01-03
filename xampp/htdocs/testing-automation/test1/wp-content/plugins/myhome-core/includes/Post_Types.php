<?php

namespace MyHomeCore;

/**
 * Class Post_Types
 * @package MyHomeCore
 */
class Post_Types {

	/**
	 * Post_Types constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {
		register_post_type(
			'client', array(
				'labels'             => array(
					'name'               => esc_html__( 'Clients', 'myhome-core' ),
					'singular_name'      => esc_html__( 'Client', 'myhome-core' ),
					'menu_name'          => esc_html__( 'Clients', 'myhome-core' ),
					'name_admin_bar'     => esc_html__( 'Client', 'myhome-core' ),
					'add_new'            => esc_html__( 'Add New Client', 'myhome-core' ),
					'add_new_item'       => esc_html__( 'Add New Client', 'myhome-core' ),
					'new_item'           => esc_html__( 'New Client', 'myhome-core' ),
					'edit_item'          => esc_html__( 'Edit Client', 'myhome-core' ),
					'view_item'          => esc_html__( 'View Client', 'myhome-core' ),
					'all_items'          => esc_html__( 'Clients', 'myhome-core' ),
					'search_items'       => esc_html__( 'Search Clients', 'myhome-core' ),
					'not_found'          => esc_html__( 'No Clients found.', 'myhome-core' ),
					'not_found_in_trash' => esc_html__( 'No Clients found in Trash.', 'myhome-core' )
				),
				'show_in_rest'       => false,
				'query_var'          => false,
				'public'             => true,
				'has_archive'        => false,
				'show_in_nav_menus'  => false,
				'publicly_queryable' => false,
				'menu_position'      => 10,
				'supports'           => array(
					'title',
					'thumbnail',
				)
			)
		);

		register_post_type(
			'testimonial', array(
				'labels'             => array(
					'name'               => esc_html__( 'Testimonials', 'myhome-core' ),
					'singular_name'      => esc_html__( 'Testimonial', 'myhome-core' ),
					'menu_name'          => esc_html__( 'Testimonials', 'myhome-core' ),
					'name_admin_bar'     => esc_html__( 'Testimonial', 'myhome-core' ),
					'add_new'            => esc_html__( 'Add New Testimonial', 'myhome-core' ),
					'add_new_item'       => esc_html__( 'Add New Testimonial', 'myhome-core' ),
					'new_item'           => esc_html__( 'New Testimonial', 'myhome-core' ),
					'edit_item'          => esc_html__( 'Edit Testimonial', 'myhome-core' ),
					'view_item'          => esc_html__( 'View Testimonial', 'myhome-core' ),
					'all_items'          => esc_html__( 'Testimonials', 'myhome-core' ),
					'search_items'       => esc_html__( 'Search Testimonials', 'myhome-core' ),
					'not_found'          => esc_html__( 'No Testimonials found.', 'myhome-core' ),
					'not_found_in_trash' => esc_html__( 'No Testimonials found in Trash.', 'myhome-core' )
				),
				'show_in_rest'       => false,
				'query_var'          => empty( My_Home_Core()->settings->props['mh-testimonials'] ),
				'publicly_queryable' => empty( My_Home_Core()->settings->props['mh-testimonials'] ),
				'public'             => true,
				'has_archive'        => false,
				'show_in_nav_menus'  => false,
				'menu_position'      => 10,
				'supports'           => array(
					'title',
					'editor',
					'thumbnail',
				)
			)
		);
	}

}