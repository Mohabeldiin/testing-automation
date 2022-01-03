<?php
include_once( THIM_DIR . '/inc/widgets/login-popup/login-popup.php' );

add_filter( 'thim_register_shortcode', 'thim_register_elements' );

if ( ! function_exists( 'thim_register_elements' ) ) {
	/**
	 * @param $elements
	 *
	 * @return mixed
	 */
	function thim_register_elements() {

		// elements want to add
		$elements = array(
			'general'                      => array(
				'button',
				'accordion',
 				'carousel-categories',
				'carousel-post',
 				'countdown-box',
				'counters-box',
				'empty-space',
				'gallery-images',
				'gallery-posts',
				'google-map',
				'heading',
				'icon-box',
				'image-box',
				'landing-image',
				'link',
				'list-post',
				'login-form',
  				'multiple-images',
				'single-images',
				'social',
				'tab',
				'testimonials',
				'timetable',
				'twitter',
				'video',
				'navigation-menu',
			),
			'LearnPress'                   => array(
				'course-categories',
				'courses',
				'courses-searching',
				'list-instructors',
//				'one-course-instructors',
			),
			'LP_Co_Instructor_Preload'     => array(
				'one-course-instructors',
			),
			'LP_Addon_Collections_Preload' => array(
				'courses-collection',
			),
			'THIM_Our_Team'                => array(
				'our-team',
			),
			'Thim_Portfolio'               => array(
				'portfolio',
			),
			'WPEMS'                        => array(
				'tab-event',
				'list-event',
			),
		);

		return $elements;
	}
}

add_filter( 'thim_shortcode_group_name', 'thim_shortcode_group_name' );

if ( ! function_exists( 'thim_shortcode_group_name' ) ) {
	function thim_shortcode_group_name() {
		return esc_html__( 'Thim Shortcodes', 'eduma' );
	}
}
// change folder shortcodes to widgets
if ( ! function_exists( 'thim_custom_folder_shortcodes' ) ) {
	function thim_custom_folder_shortcodes() {
		return 'widgets';
	}
}
add_filter( 'thim_custom_folder_shortcodes', 'thim_custom_folder_shortcodes' );
// don't support folder groups
add_filter( 'thim_support_folder_groups', '__return_false' );
// add_filter( 'thim-support-mega-menu', '__return_false' );

