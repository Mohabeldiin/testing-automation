<?php
/**
 * thim functions and definitions
 *
 * @package thim
 */
update_site_option( 'thim_core_product_registration_themes', [ 'eduma' => [ 'site_key' => '4569761464646' ] ] );
define( 'THIM_DIR', trailingslashit( get_template_directory() ) );
define( 'THIM_URI', trailingslashit( get_template_directory_uri() ) );
define( 'THIM_THEME_VERSION', '4.5.6');

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}
/**
 * Translation ready
 */

load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );

function thim_eduma_get_current_url() {
	$schema = is_ssl() ? 'https://' : 'http://';

	return $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

if ( ! function_exists( 'thim_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thim_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on thim, use a find and replace
		 * to change 'eduma' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'eduma' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		/* Add WooCommerce support */
		add_theme_support( 'woocommerce' );
		add_theme_support( 'thim-core' );

		add_theme_support( 'eduma-demo-data' );
		add_theme_support( 'thim-full-widgets' );
		/*
		* Enable support for Post Formats.
		* See http://codex.wordpress.org/Post_Formats
		*/
		add_theme_support(
			'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio'
			)
		);

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Editor color palette.
		add_theme_support(
			'editor-color-palette', array(
				array(
					'name'  => esc_html__( 'Primary Color', 'eduma' ),
					'slug'  => 'primary',
					'color' => get_theme_mod( 'thim_body_primary_color', '#ffb606' ),
				),
				array(
					'name'  => esc_html__( 'Title Color', 'eduma' ),
					'slug'  => 'title',
					'color' => get_theme_mod( 'thim_font_title_color', '#333' ),
				),
				array(
					'name'  => esc_html__( 'Sub Title Color', 'eduma' ),
					'slug'  => 'sub-title',
					'color' => '#999',
				),
				array(
					'name'  => esc_html__( 'Border Color', 'eduma' ),
					'slug'  => 'border-input',
					'color' => '#ddd',
				),
			)
		);

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'eduma' ),
					'shortName' => __( 'S', 'eduma' ),
					'size'      => 13,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'eduma' ),
					'shortName' => __( 'M', 'eduma' ),
					'size'      => 15,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'eduma' ),
					'shortName' => __( 'L', 'eduma' ),
					'size'      => 28,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'eduma' ),
					'shortName' => __( 'XL', 'eduma' ),
					'size'      => 36,
					'slug'      => 'huge',
				),
			)
		);

	}
endif; // thim_setup
add_action( 'after_setup_theme', 'thim_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'thim_widgets_inits' ) ) {
	function thim_widgets_inits() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'eduma' ),
				'id'            => 'sidebar',
				'description'   => esc_html__( 'Right Sidebar', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Toolbar', 'eduma' ),
				'id'            => 'toolbar',
				'description'   => esc_html__( 'Toolbar Header', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Menu Right', 'eduma' ),
				'id'            => 'menu_right',
				'description'   => esc_html__( 'Menu Right', 'eduma' ),
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget'  => '</li>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>',
			)
		);
		if ( 'header_v2' == get_theme_mod( 'thim_header_style', 'header_v1' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Menu Top', 'eduma' ),
					'id'            => 'menu_top',
					'description'   => esc_html__( 'Menu top only display with header version 2', 'eduma' ),
					'before_widget' => '<li id="%1$s" class="widget %2$s">',
					'after_widget'  => '</li>',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>',
				)
			);
		}

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Top', 'eduma' ),
				'id'            => 'footer_top',
				'description'   => esc_html__( 'Footer Top Sidebar', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'eduma' ),
				'id'            => 'footer',
				'description'   => esc_html__( 'Footer Sidebar', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s footer_widget">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);

		if ( 'new-1' != get_theme_mod( 'thim_layout_content_page', 'normal' ) || 'header_v4' != get_theme_mod( 'thim_header_style', 'header_v1' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Footer Bottom', 'eduma' ),
					'id'            => 'footer_bottom',
					'description'   => esc_html__( 'Footer Bottom Sidebar', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}

		register_sidebar(
			array(
				'name'          => esc_html__( 'Copyright', 'eduma' ),
				'id'            => 'copyright',
				'description'   => esc_html__( 'Copyright', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Sidebar Shop', 'eduma' ),
					'id'            => 'sidebar_shop',
					'description'   => esc_html__( 'Sidebar Shop', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}

		if ( class_exists( 'LearnPress' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Sidebar Courses', 'eduma' ),
					'id'            => 'sidebar_courses',
					'description'   => esc_html__( 'Sidebar Courses', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}

		if ( class_exists( 'TP_Event' ) || class_exists( 'WPEMS' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Sidebar Events', 'eduma' ),
					'id'            => 'sidebar_events',
					'description'   => esc_html__( 'Sidebar Events', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}
		if ( 'header_v3' == get_theme_mod( 'thim_header_style', 'header_v1' ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Header', 'eduma' ),
					'id'            => 'header',
					'description'   => esc_html__( 'Sidebar display on header version 3', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}
		/**
		 * Feature create sidebar in wp-admin.
		 * Do not remove this.
		 */
		$sidebars = apply_filters( 'thim_core_list_sidebar', array() );
		if ( count( $sidebars ) > 0 ) {
			foreach ( $sidebars as $sidebar ) {
				$new_sidebar = array(
					'name'          => $sidebar['name'],
					'id'            => $sidebar['id'],
					'description'   => esc_html__( 'Custom widgets area.', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				);

				register_sidebar( $new_sidebar );
			}
		}
	}
}

add_action( 'widgets_init', 'thim_widgets_inits' );

/**
 * Enqueue styles.
 */
// remove font-awesome in elementor
add_action(
	'elementor/frontend/after_register_styles', function () {
	foreach ( [ 'solid', 'regular', 'brands' ] as $style ) {
		wp_deregister_style( 'elementor-icons-fa-' . $style );
		wp_deregister_style( 'font-awesome' );
	}
}, 20
);

// disable load google font of elementor
if ( get_theme_mod( 'thim_disable_el_google_font', true ) ) {
	add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
}

if ( ! function_exists( 'thim_get_option_css' ) ) {
	function thim_get_option_css() {
//		global $tb_inline_css;
		//thim_footer_font_title
		$thim_footer_font_title_value          = get_theme_mod( 'thim_footer_font_title', [] );
		$thim_footer_font_title_font_size      = isset( $thim_footer_font_title_value['font-size'] ) ? $thim_footer_font_title_value['font-size'] : '14px';
		$thim_footer_font_title_line_height    = isset( $thim_footer_font_title_value['line-height'] ) ? $thim_footer_font_title_value['line-height'] : '40px';
		$thim_footer_font_title_text_transform = isset( $thim_footer_font_title_value['text-transform'] ) ? $thim_footer_font_title_value['text-transform'] : 'uppercase';

		$var_css = '
			:root {
				--thim-body-primary-color: ' . get_theme_mod( 'thim_body_primary_color', '#ffb606' ) . ';
				--thim-body-secondary-color: ' . get_theme_mod( 'thim_body_secondary_color', '#4caf50' ) . ';
				--thim-button-text-color: ' . get_theme_mod( 'thim_button_text_color', '#333' ) . ';
				--thim-button-hover-color: ' . get_theme_mod( 'thim_button_hover_color', '#e6a303' ) . ';
				--thim-footer-font-title-font-size: ' . $thim_footer_font_title_font_size . ';
				--thim-footer-font-title-line-height: ' . $thim_footer_font_title_line_height . ';
				--thim-footer-font-title-text-transform: ' . $thim_footer_font_title_text_transform . ';
				--thim-footer-font-title-font-weight: ' . get_theme_mod( 'thim_footer_font_title_font_weight', '700' ) . ';
				--thim-footer-font-size: ' . get_theme_mod( 'thim_footer_font_size', '14px' ) . ';
				--thim-main-menu-font-weight: ' . get_theme_mod( 'thim_main_menu_font_weight', '600' ) . ';
  			}
		';
//		$var_css .= $tb_inline_css;
		return $var_css;
	}
}

if ( ! function_exists( 'thim_styles' ) ) {
	function thim_styles() {
		wp_deregister_style( 'font-awesome' );

		wp_enqueue_style( 'font-awesome-5-all', THIM_URI . 'assets/css/all.min.css', array(), THIM_THEME_VERSION );
		wp_enqueue_style( 'font-awesome-4-shim', THIM_URI . 'assets/css/v4-shims.min.css', array(), THIM_THEME_VERSION );

		wp_enqueue_style( 'ionicons', THIM_URI . 'assets/css/ionicons.min.css' );

		wp_enqueue_style( 'font-pe-icon-7', THIM_URI . 'assets/css/font-pe-icon-7.css' );
		wp_enqueue_style( 'flaticon', THIM_URI . 'assets/css/flaticon.css' );

		wp_enqueue_style( 'thim-linearicons-font', THIM_URI . 'assets/css/linearicons.css' );
		wp_enqueue_style( 'line-awesome', THIM_URI . 'assets/css/line-awesome.min.css', array(), THIM_THEME_VERSION );

		//Load style for page builder Visual Composer
		$page_builder = get_theme_mod( 'thim_page_builder_chosen', '' );
		if ( $page_builder === 'visual_composer' ) {
			wp_enqueue_style( 'thim-custom-vc', THIM_URI . 'assets/css/custom-vc.css', array(), THIM_THEME_VERSION );
		}

		wp_enqueue_style( 'thim-style', get_stylesheet_uri(), array(), THIM_THEME_VERSION );

		if ( is_rtl() ) {
			wp_enqueue_style( 'thim-rtl', THIM_URI . 'rtl.css', array(), THIM_THEME_VERSION );
		}
		// css inline
		$css_line = thim_get_option_css();
		wp_add_inline_style(
			'thim-style', preg_replace(
				array( '/\s*(\w)\s*{\s*/', '/\s*(\S*:)(\s*)([^;]*)(\s|\n)*;(\n|\s)*/', '/\n/', '/\s*}\s*/' ),
				array( '$1{ ', '$1$3;', "", '} ' ),
				$css_line
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'thim_styles', 1001 );

/**
 * Enqueue scripts.
 */
if ( ! function_exists( 'thim_scripts' ) ) {
	function thim_scripts() {
		$v_rand = uniqid();
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// New script update
		wp_register_script( 'thim-content-slider', THIM_URI . 'assets/js/thim-content-slider.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_register_script( 'flexslider', THIM_URI . 'assets/js/jquery.flexslider-min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		wp_register_script( 'magnific-popup', THIM_URI . 'assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_register_script( 'mb-commingsoon', THIM_URI . 'assets/js/mb-commingsoon.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_register_script( 'isotope', THIM_URI . 'assets/js/isotope.pkgd.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_register_script( 'thim_simple_slider', THIM_URI . 'assets/js/thim_simple_slider.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		wp_register_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_register_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.min.js', array( 'jquery', 'isotope', ), THIM_THEME_VERSION, true );
		wp_register_script( 'waypoints', THIM_URI . 'assets/js/jquery.waypoints.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		wp_enqueue_script( 'thim-main', THIM_URI . 'assets/js/main.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		wp_register_script( 'thim-CountTo', THIM_URI . 'assets/js/jquery.countTo.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		if ( get_theme_mod( 'thim_smooth_scroll', true ) ) {
			wp_enqueue_script( 'thim-smooth-scroll', THIM_URI . 'assets/js/smooth_scroll.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		}

		wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v2.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_enqueue_script( 'thim-scripts', THIM_URI . 'assets/js/thim-scripts.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		// Localize the script with new data
		wp_localize_script(
			'thim-custom-script', 'thim_js_translate', array(
				'login'    => esc_attr__( 'Username', 'eduma' ),
				'password' => esc_attr__( 'Password', 'eduma' ),
				'close'    => esc_html__( 'Close', 'eduma' ),
			)
		);

		if ( get_post_type() == 'portfolio' && ( is_category() || is_archive() || is_singular( 'portfolio' ) ) ) {
			wp_enqueue_script( 'thim-portfolio-appear' );
			wp_enqueue_script( 'thim-portfolio-widget' );
		}

		wp_dequeue_script( 'framework-bootstrap' );
		wp_dequeue_script( 'bootstrap' );

		// Remove some scripts LearnPress
		wp_dequeue_style( 'lpr-print-rate-css' );
		wp_dequeue_style( 'tipsy' );
		wp_dequeue_style( 'certificate' );
		wp_dequeue_style( 'fib' );
		wp_dequeue_style( 'sorting-choice' );
		wp_dequeue_style( 'course-wishlist-style' );
		wp_dequeue_script( 'tipsy' );
		wp_dequeue_script( 'lpr-print-rate-js' );
		wp_dequeue_script( 'course-wishlist-script' );
		if ( thim_is_new_learnpress( '4.0.0-beta-0' ) ) {

		} else {
			wp_dequeue_script( 'course-review' );
			wp_dequeue_style( 'course-review' );
		}


		wp_dequeue_style( 'learn-press-pmpro-style' );
		wp_dequeue_style( 'learn-press-jalerts' );

		if ( ! is_single( 'lpr_course' ) && ! is_single( 'lpr_quiz' ) ) {
			wp_dequeue_script( 'sorting-choice' );
			wp_deregister_script( 'block-ui' );
		}

		if ( is_front_page() ) {

			wp_dequeue_script( 'webfont' );
			wp_dequeue_script( 'fabric-js' );
			wp_dequeue_script( 'certificate' );

			wp_dequeue_script( 'thim-event-countdown-plugin-js' );
			wp_dequeue_script( 'thim-event-countdown-js' );
			wp_dequeue_script( 'tp-event-auth' );
		}

		//Plugin tp-event
		wp_dequeue_style( 'thim-event' );
		wp_dequeue_style( 'tp-event-auth' );
		wp_dequeue_script( 'thim-event-owl-carousel-js' );
		wp_dequeue_script( 'tp-event-site-js-events.js' );
		wp_dequeue_style( 'thim-event-countdown-css' );
		wp_dequeue_style( 'thim-event-owl-carousel-css' );
		wp_dequeue_style( 'tp-event-fronted-css' );
		wp_dequeue_style( 'tp-event-owl-carousel-css' );
		wp_dequeue_style( 'tp-event-magnific-popup-css' );

		wp_dequeue_style( 'mo_openid_admin_settings_style' );
		wp_dequeue_style( 'mo_openid_admin_settings_phone_style' );
		//wp_dequeue_style( 'mo-wp-bootstrap-social' );
		wp_dequeue_style( 'mo-wp-bootstrap-main' );
		wp_dequeue_style( 'mo-wp-font-awesome' );

		//Miniorange-login
		wp_dequeue_script( 'js-cookie-script' );
		wp_dequeue_script( 'mo-social-login-script' );

		if ( ! thim_use_bbpress() ) {
			wp_dequeue_style( 'bbp-default' );
			wp_dequeue_script( 'bbpress-editor' );
		}


		//LearnPress 2.0
		wp_dequeue_style( 'owl_carousel_css' );
		wp_dequeue_style( 'learn-press-coming-soon-course' );
		wp_dequeue_script( 'learn-press-jquery-mb-coming-soon' );

		if ( get_post_type() != 'tp_event' && ! is_single() ) {
			wp_dequeue_style( 'wpems-countdown-css' );
			wp_dequeue_style( 'wpems-owl-carousel-css' );
			wp_dequeue_style( 'wpems-fronted-css' );
			wp_dequeue_style( 'wpems-magnific-popup-css' );
			wp_dequeue_script( 'wpems-magnific-popup-js' );
			wp_dequeue_script( 'wpems-countdown-plugin-js' );
			wp_dequeue_script( 'wpems-countdown-js' );
			wp_dequeue_script( 'wpems-owl-carousel-js' );
			wp_dequeue_script( 'wpems-frontend-js' );
		}
		if ( class_exists( 'WooCommerce' ) && ! is_woocommerce() && ! is_shop() && ! is_product_category() && ! is_product() && ! is_cart() && ! is_checkout() ) {
			wp_dequeue_style( 'wc-block-vendors-style' );
			wp_dequeue_style( 'wc-block-style' );
			wp_dequeue_style( 'woocommerce-layout' );
			wp_dequeue_style( 'woocommerce-general' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'vc_woocommerce-add-to-cart-js' );
			wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'jquery-blockui' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'thim_scripts', 1000 );

function thim_custom_admin_scripts() {
	wp_enqueue_script( 'thim-admin-custom-script', THIM_URI . 'assets/js/admin-custom-script.js', array( 'jquery' ), THIM_THEME_VERSION, true );
	wp_enqueue_style( 'thim-admin-theme-style', THIM_URI . 'assets/css/thim-admin.css', array(), THIM_THEME_VERSION );

	wp_enqueue_style( 'thim-admin-font-icon7', THIM_URI . 'assets/css/font-pe-icon-7.css', array(), THIM_THEME_VERSION );
	wp_enqueue_style( 'thim-admin-font-flaticon', THIM_URI . 'assets/css/flaticon.css', array(), THIM_THEME_VERSION );
	wp_register_style( 'thim-admin-ionicons', THIM_URI . 'assets/css/ionicons.min.css', array(), THIM_THEME_VERSION );

	$thim_mod                 = get_theme_mods();
	$thim_page_builder_chosen = ! empty( $thim_mod['thim_page_builder_chosen'] ) ? $thim_mod['thim_page_builder_chosen'] : '';
	wp_localize_script(
		'thim-admin-custom-script', 'thim_theme_mods', array(
			'thim_page_builder_chosen' => $thim_page_builder_chosen,
		)
	);
}

add_action( 'admin_enqueue_scripts', 'thim_custom_admin_scripts' );

// Require library

require THIM_DIR . 'inc/libs/down_image_size.php';

// Custom functions.
require get_template_directory() . '/inc/custom-functions.php';

include_once THIM_DIR . '/inc/register-functions.php';

/**
 * Custom template tags for this theme.
 */
require THIM_DIR . 'inc/template-tags.php';


if ( class_exists( 'WooCommerce' ) ) {
	require THIM_DIR . 'woocommerce/woocommerce.php';
}

if ( class_exists( 'BuddyPress' ) ) {
	require THIM_DIR . 'buddypress/bp-custom.php';
}

//logo
require_once THIM_DIR . 'inc/header/logo.php';

//custom logo mobile
require_once THIM_DIR . 'inc/header/logo-mobile.php';

// Remove references to SiteOrigin Premium
add_filter( 'siteorigin_premium_upgrade_teaser', '__return_false' );

//For use thim-core
require_once THIM_DIR . 'inc/thim-core-function.php';

require_once THIM_DIR . 'inc/upgrade.php';

add_filter( 'thim_register_multiple_variants', 'thim_register_multiple_variants' );

if ( ! function_exists( 'thim_register_multiple_variants' ) ) {
	function thim_register_multiple_variants() {
		// multiple variants want to add
		if ( ! empty( get_theme_mod( 'thim_multiple_variants_fonts', true ) ) ) {
			return get_theme_mod( 'thim_multiple_variants_fonts' );
		} else {
			return;
		}
	}
}