<?php

namespace MyHomeCore\Shortcodes;


/**
 * Class Post_Carousel_Shortcode
 * @package MyHomeCore\Shortcodes
 */
class Post_Carousel_Shortcode extends Shortcode {

	/**
	 * @param array       $options
	 * @param string|null $content
	 *
	 * @return string
	 */
	public function display( $options = array(), $content = null ) {
		wp_enqueue_script( 'myhome-carousel' );

		$args = array(
			'posts_style'    => '',
			'posts_limit'    => 5,
			'category'       => '',
			'read_more_text' => esc_html__( 'Read more', 'myhome-core' ),
			'owl_visible'    => 'owl-carousel--visible-3',
			'owl_dots'       => ''
		);
		$args = array_merge( $args, $options );
		$opts = array(
			'ignore_sticky_posts' => true,
			'posts_per_page'      => intval( $args['posts_limit'] ),
			'suppress_filters'    => false
		);
		if ( isset( $args['category'] ) ) {
			$posts_category = intval( $args['category'] );
			if ( $posts_category ) {
				$opts['category'] = $posts_category;
			}
		}

		$class = $args['owl_visible'] . ' ' . $args['owl_dots'];

		if ( isset ( $args['owl_auto_play'] ) && $args['owl_auto_play'] != 'true' ) {
			$class .= ' owl-carousel--no-auto-play';
		}

		global $myhome_post_carousel;
		$myhome_post_carousel          = $args;
		$myhome_post_carousel['class'] = $class;
		global $myhome_posts;
		$myhome_posts = get_posts( $opts );

		if ( isset( $args['more_page'] ) && ! empty( $args['more_page'] ) && $args['more_page'] != 'not_set' ) {
			$myhome_post_carousel['more_page'] = get_permalink( $args['more_page'] );
		} else {
			$myhome_post_carousel['more_page'] = false;
		}

		if ( isset( $args['more_page_text'] ) && ! empty( $args['more_page_text'] ) ) {
			$myhome_post_carousel['more_page_text'] = $args['more_page_text'];
		} else {
			$myhome_post_carousel['more_page_text'] = esc_html__( 'View all', 'myhome-core' );
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
		$categories   = get_categories();
		$categoryList = array(
			esc_html__( 'Any', 'myhome-core' ) => 'any'
		);
		foreach ( $categories as $category ) {
			/* @var \WP_Term $category */
			$categoryList[ $category->name ] = $category->term_id;
		}

		return array(
			// Visible
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Max Visible', 'myhome-core' ),
				'param_name'  => 'owl_visible',
				'value'       => array(
					esc_html__( 'Default - 3', 'myhome-core' ) => 'owl-carousel--visible-3',
					esc_html__( '1 ', 'myhome-core' )          => 'owl-carousel--visible-1',
					esc_html__( '2 ', 'myhome-core' )          => 'owl-carousel--visible-2',
					esc_html__( '3 ', 'myhome-core' )          => 'owl-carousel--visible-3',
					esc_html__( '4 ', 'myhome-core' )          => 'owl-carousel--visible-4',
					esc_html__( '5 ', 'myhome-core' )          => 'owl-carousel--visible-5',
				),
				'save_always' => true
			),
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Category', 'myhome-core' ),
				'param_name'  => 'category',
				'value'       => $categoryList,
				'std'         => 'any',
				'save_always' => true
			),
			// Posts limit
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Posts limit', 'myhome-core' ),
				'param_name'  => 'posts_limit',
				'value'       => 5,
				'save_always' => true
			),
			// Read more
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Read more text', 'myhome-core' ),
				'param_name'  => 'read_more_text',
				'value'       => esc_html__( 'Read more', 'myhome-core' ),
				'save_always' => true
			),
			// Style
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'myhome-core' ),
				'param_name'  => 'posts_style',
				'value'       => array(
					esc_html__( 'Default', 'myhome-core' )          => '',
					esc_html__( 'White Background', 'myhome-core' ) => 'mh-post-grid--white',
					esc_html__( 'Dark Background', 'myhome-core' )  => 'mh-post-grid--dark',
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
	}


}