<?php

$thim_uri_screenshot = THIM_URI . 'inc/data/demos/demo-so/';

//$page_builder_choosen = get_theme_mod( 'thim_page_builder_chosen', '' );
$new_demo = array();

if ( apply_filters( 'thim-importer-demo-vc', false ) || get_theme_mod( 'thim_page_builder_chosen' ) == 'visual_composer' ) {
	$folder_demo          = 'demo-vc';
	$plugin_required_demo = array( 'js_composer' );
 } elseif ( apply_filters( 'thim-importer-demo-so', false ) || get_theme_mod( 'thim_page_builder_chosen' ) == 'site_origin') { // support importer with
	$folder_demo          = 'demo-so';
	$plugin_required_demo = array( 'siteorigin-panels', 'classic-editor' );
} else {
	$folder_demo          = 'demo-el';
	$plugin_required_demo = array( 'elementor', 'anywhere-elementor' );
	$new_demo             = array(
		"demo-el/demo-elegant"    => array(
			'title'            => esc_html__( 'Demo Elegant', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/eduma-elegant/',
			'thumbnail_url'    => 'https://raw.githubusercontent.com/ThimPressWP/demo-data/master/eduma/images/elegant_thumb.jpg',
			'plugins_required' => array(
				'eduma-demo-data',
				'elementor',
				'anywhere-elementor',
				'learnpress',
				'mailchimp-for-wp',
				'contact-form-7',
				'woocommerce',
				'revslider',
				'wp-events-manager',
				'tp-portfolio',
				'thim-testimonials',
				'thim-our-team',
				'paid-memberships-pro',
				'learnpress-course-review',
				'learnpress-paid-membership-pro',
				'learnpress-wishlist',
				'bbpress',
				'learnpress-bbpress',
			),
			'revsliders'       => array(
				'eduma-elegant.zip'
			),
		),
		"demo-el/demo-restaurant" => array(
			'title'            => esc_html__( 'Demo Restaurant', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-restaurant/',
			'thumbnail_url'    => 'https://raw.githubusercontent.com/ThimPressWP/demo-data/master/eduma/images/restaurant_thumb.jpg',
			'plugins_required' => array(
				'eduma-demo-data',
				'elementor',
				'anywhere-elementor',
				'learnpress',
				'mailchimp-for-wp',
				'contact-form-7',
				'woocommerce',
				'revslider',
				'wp-events-manager',
				'tp-portfolio',
				'thim-testimonials',
				'thim-our-team',
				'paid-memberships-pro',
				'learnpress-course-review',
				'learnpress-paid-membership-pro',
				'learnpress-wishlist',
				'bbpress',
				'learnpress-bbpress',
			),
			'revsliders'       => array(
				'demo-restaurant.zip'
			),
		),
	);
}

$demo_datas = array_merge(
	array(
		// Elementor
		"$folder_demo/demo-01"  => array(
			'title'            => esc_html__( 'Demo Main Demo', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-01.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-paid-membership-pro',
					'learnpress-wishlist',
					'bbpress',
					'learnpress-bbpress',
				),
				$plugin_required_demo
			),
			'revsliders'       => array(
				'home-page.zip'
			),
		),
		"$folder_demo/demo-02"  => array(
			'title'            => esc_html__( 'Demo Course Era', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-2/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-02.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
				),
				$plugin_required_demo
			),
		),
		"$folder_demo/demo-03"  => array(
			'title'            => esc_html__( 'Demo Online School', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-3/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-03.jpg',
			'plugins_required' =>
				array_merge(
					array(
						'eduma-demo-data',
						'learnpress',
						'mailchimp-for-wp',
						'contact-form-7',
						'woocommerce',
						'revslider',
						'wp-events-manager',
						'tp-portfolio',
						'thim-testimonials',
						'thim-our-team',
						'learnpress-course-review',
						'learnpress-wishlist',
						'widget-logic',
					),
					$plugin_required_demo
				),
			'revsliders'       => array(
				'home-page-video.zip'
			),
		),
		"$folder_demo/demo-rtl" => array(
			'title'            => esc_html__( 'Demo RTL', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-rtl/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-rtl.png',
			'plugins_required' =>
				array_merge(
					array(
						'eduma-demo-data',
						'learnpress',
						'mailchimp-for-wp',
						'contact-form-7',
						'woocommerce',
						'revslider',
						'wp-events-manager',
						'tp-portfolio',
						'thim-testimonials',
						'thim-our-team',
						'paid-memberships-pro',
						'learnpress-course-review',
						'learnpress-paid-membership-pro',
						'learnpress-wishlist',
						'bbpress',
						'learnpress-bbpress',
						'widget-logic',
					),
					$plugin_required_demo
				),
			'revsliders'       => array(
				'home-page.zip'
			),
		),

		"$folder_demo/demo-languages-school" => array(
			'title'            => esc_html__( 'Demo Languages School', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-languages-school/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-languages-school.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-languages-school.zip'
			),
		),
		"$folder_demo/demo-courses-hub"      => array(
			'title'            => esc_html__( 'Demo Courses Hub', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-courses-hub/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-courses-hub.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'widget-logic',
				), $plugin_required_demo
			),
		),
		"$folder_demo/demo-university"       => array(
			'title'            => esc_html__( 'Demo Classic University', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-university/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-university.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-university.zip'
			),
		),
		"$folder_demo/demo-university-2"     => array(
			'title'            => esc_html__( 'Demo Modern University', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-university-2/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-university-2.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
					'woocommerce-products-filter',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-university-2.zip'
			),
		),
		"$folder_demo/demo-university-3"     => array(
			'title'            => esc_html__( 'Demo Ivy League', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-university-3/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-university-3.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
					'thim-twitter',
					'instagram-feed',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-university-3.zip'
			),
		),
		"$folder_demo/demo-university-4"     => array(
			'title'            => esc_html__( 'Demo Stanford', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-university-4/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-university-4.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
					'thim-twitter',
					'instagram-feed',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-university-4.zip'
			),
		),
		"$folder_demo/demo-kindergarten"     => array(
			'title'                => esc_html__( 'Demo Kindergarten - Offline', 'eduma' ),
			'demo_url'             => 'https://eduma.thimpress.com/demo-kindergarten/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-kindergarten.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-kindergarten',
			'revsliders'           => array(
				'home-kindergarten.zip'
			),
		),
		"$folder_demo/demo-one-instructor"   => array(
			'title'            => esc_html__( 'Demo One Instructor', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-one-instructor/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-one-instructor.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-one-instructor.zip'
			),
		),
		"$folder_demo/demo-one-course"       => array(
			'title'            => esc_html__( 'Demo One Course', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-one-course/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2017/06/eduma-demo-one-course.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'widget-logic',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-one-course.zip'
			),
		),
		"$folder_demo/demo-edtech"           => array(
			'title'            => esc_html__( 'Demo Edtech', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-edtech/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2018/01/eduma-demo-edtech.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-co-instructor',
					'widget-logic',
					'thim-twitter',
					'instagram-feed',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-edtech.zip'
			),
		),
		"$folder_demo/demo-react"            => array(
			'title'            => esc_html__( 'Demo React', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-react/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2018/04/eduma-demo-react.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-co-instructor',
					'widget-logic',
					'thim-twitter',
					'instagram-feed',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-react.zip'
			),
		),
		"$folder_demo/demo-grad-school"      => array(
			'title'            => esc_html__( 'Demo Grad School', 'eduma' ),
			'demo_url'         => 'https://eduma.thimpress.com/demo-grad-school/',
			'thumbnail_url'    => 'https://updates.thimpress.com/wp-content/uploads/2018/07/eduma-demo-grad-school.jpg',
			'plugins_required' => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-paid-membership-pro',
					'learnpress-wishlist',
					'learnpress-collections',
					'bbpress',
					'learnpress-bbpress',
					'widget-logic',
				), $plugin_required_demo
			),
			'revsliders'       => array(
				'home-grad-school.zip'
			),
		),
		"$folder_demo/demo-edume"            => array(
			'title'                => esc_html__( 'Demo New Edu', 'eduma' ),
			'demo_url'             => 'https://eduma.thimpress.com/demo-edume/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2019/08/eduma-demo-edume.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'thim-twitter',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-udemy',
			'revsliders'           => array(
				'slider-home-udemy.zip'
			),
		),
		"$folder_demo/demo-instructor"       => array(
			'title'                => esc_html__( 'Demo New Instructor', 'eduma' ),
			'demo_url'             => 'https://eduma.thimpress.com/demo-instructor/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2019/08/eduma-demo-instructor.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'thim-twitter',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-instructor',
			'revsliders'           => array(
				'slider-home-instructor.zip'
			),
		),

		"$folder_demo/demo-crypto"    => array(
			'title'                => esc_html__( 'Demo Crypto', 'eduma' ),
			'demo_url'             => 'https://eduma.thimpress.com/demo-crypto/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2019/11/eduma-demo-crypto.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'thim-twitter',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-crypto',
			'revsliders'           => array(
				'slider-home-crypto.zip'
			),
		),
		"$folder_demo/demo-new-art"   => array(
			'title'                => esc_html__( 'Demo New Art', 'eduma' ),
			'demo_url'             => 'https://eduma.thimpress.com/demo-new-art/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2019/11/eduma-demo-new-art.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'revslider',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'thim-twitter',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-new-art',
			'revsliders'           => array(
				'slider-home-new-art.zip'
			),
		),
		"$folder_demo/demo-kid-art"   => array(
			'title'                => esc_html__( 'Demo Kid Art', 'eduma' ) . ' - Offline',
			'demo_url'             => 'https://eduma.thimpress.com/demo-kid-art/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2019/11/eduma-demo-kid-art.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'thim-twitter',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-kid-art',
		),
		"$folder_demo/demo-tech-camp" => array(
			'title'                => esc_html__( 'Demo Tech Camp', 'eduma' ),
			'demo_url'             => 'https://eduma.thimpress.com/demo-tech-camp/',
			'thumbnail_url'        => 'https://updates.thimpress.com/wp-content/uploads/2019/11/eduma-demo-tech-camp.jpg',
			'plugins_required'     => array_merge(
				array(
					'eduma-demo-data',
					'learnpress',
					'mailchimp-for-wp',
					'contact-form-7',
					'woocommerce',
					'wp-events-manager',
					'tp-portfolio',
					'thim-testimonials',
					'thim-our-team',
					'paid-memberships-pro',
					'learnpress-course-review',
					'learnpress-wishlist',
					'learnpress-collections',
					'thim-twitter',
					'widget-logic',
				), $plugin_required_demo
			),
			'child_theme_required' => 'eduma-child-tech-camps',
		),
	),
	$new_demo
);

return $demo_datas;