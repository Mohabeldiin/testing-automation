<?php

if ( ! function_exists( 'thim_wrapper_layout' ) ) :
	function thim_wrapper_layout() {
		$theme_options_data = get_theme_mods();
		$get_post_type      = get_post_type();
		global $wp_query;
		$using_custom_layout = $wrapper_layout = $cat_ID = '';
		$class_col           = 'col-sm-9 alignleft';

		if ( $get_post_type == "product" ) {
			$prefix = 'thim_woo';
		} elseif ( $get_post_type == "lpr_course" || $get_post_type == "lpr_quiz" || $get_post_type == "lp_course" || $get_post_type == "lp_quiz" || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
			$prefix = 'thim_learnpress';
		} elseif ( $get_post_type == "lp_collection" ) {
			$prefix = 'thim_collection';
		} elseif ( $get_post_type == "portfolio" ) {
			$prefix = 'thim_portfolio';
		} elseif ( $get_post_type == "tp_event" ) {
			$prefix = 'thim_event';
		} elseif ( $get_post_type == "testimonials" ) {
			$prefix = 'thim_testimonials';
		} elseif ( $get_post_type == "our_team" ) {
			$prefix = 'thim_team';
		} elseif ( $get_post_type == "forum" || $get_post_type == "topic" ) {
			$prefix = 'thim_forum';
		} else {
			if ( is_front_page() || is_home() ) {
				$prefix = 'thim_front_page';
			} elseif ( is_page() ) {
				$prefix = 'thim_page';
			} else {
				$prefix = 'thim_archive';
			}
		}
		if ( is_search() ) {
			$prefix = 'thim_archive';
		}
		// get id category
		$cat_obj = $wp_query->get_queried_object();
		if ( isset( $cat_obj->term_id ) ) {
			$cat_ID = $cat_obj->term_id;
		}
		// get layout
		if ( is_page() || is_single() ) {

			$postid = get_the_ID();
			if ( $get_post_type == "forum" || $get_post_type == "topic" ) {
				$wrapper_layout = isset( $theme_options_data[$prefix . '_cate_layout'] ) ? $theme_options_data[$prefix . '_cate_layout'] : '';
			}

			if ( isset( $theme_options_data[$prefix . '_single_layout'] ) ) {
				$wrapper_layout = $theme_options_data[$prefix . '_single_layout'];
			}
			if ( is_page() ) {
				if ( ! empty( $theme_options_data[$prefix . '_layout'] ) ) {
					$wrapper_layout = $theme_options_data[$prefix . '_layout'];
				} else {
					$wrapper_layout = 'full-content';
				}
			}
			/***********custom layout*************/
			$using_custom_layout = get_post_meta( $postid, 'thim_mtb_custom_layout', true );
			if ( $using_custom_layout ) {
				$wrapper_layout = get_post_meta( $postid, 'thim_mtb_layout', true );
			}

		} else {
			if ( isset( $theme_options_data[$prefix . '_cate_layout'] ) ) {
				$wrapper_layout = $theme_options_data[$prefix . '_cate_layout'];
			}
			if ( $get_post_type == "lp_collection" ) {
				$wrapper_layout = $theme_options_data[$prefix . '_single_layout'];
			}
			/***********custom layout*************/
			$using_custom_layout = get_term_meta( $cat_ID, 'thim_layout', true );
			if ( $using_custom_layout <> '' ) {
				$wrapper_layout = get_term_meta( $cat_ID, 'thim_layout', true );
			}
		}

		if ( $wrapper_layout == 'full-content' ) {
			$class_col = "col-sm-12 full-width";
		}
		if ( $wrapper_layout == 'sidebar-right' ) {
			$class_col = "col-sm-9 alignleft";
		}
		if ( $wrapper_layout == 'sidebar-left' ) {
			$class_col = 'col-sm-9 alignright';
		}

		if ( $wrapper_layout == 'full-width' ) {
			$class_col = 'content-wide';
		}


		return $class_col;
	}
endif;

//
add_action( 'thim_wrapper_loop_start', 'thim_wrapper_loop_start', 10 );

if ( ! function_exists( 'thim_wrapper_loop_start' ) ) :
	function thim_wrapper_loop_start() {
		$theme_options_data = get_theme_mods();

		$class_no_padding = '';
		if ( is_page() || is_single() ) {
			$mtb_no_padding = get_post_meta( get_the_ID(), 'thim_mtb_no_padding', true );
			if ( $mtb_no_padding ) {
				$class_no_padding = ' no-padding-top';
			}
		}
		$class_col     = thim_wrapper_layout();
		$sidebar_class = '';
		if ( is_404() ) {
			$class_col = 'col-sm-12 full-width';
		}
		if ( $class_col == "col-sm-9 alignleft" ) {
			$sidebar_class = ' sidebar-right';
		}
		if ( $class_col == "col-sm-9 alignright" ) {
			$sidebar_class = ' sidebar-left';
		}
		if ( $class_col == "content-wide" ) {
			$sidebar_class = '-fluid';
		}
		// add new style for top heading

		if ( ! empty( $theme_options_data['thim_layout_content_page'] ) ) {
			$layout_style_2 = $theme_options_data['thim_layout_content_page'];
			$sidebar_class  .= ( $layout_style_2 == 'layout_style_2' ) ? ' top-heading-style-3' : '';
		}
		do_action( 'thim_before_site_content' );

		echo '<div class="container' . $sidebar_class . $class_no_padding . ' site-content">';

		echo '<div class="row"><main id="main" class="site-main ' . $class_col . '">';
	}
endif;

add_action( 'thim_wrapper_loop_end', 'thim_wrapper_loop_end', 10 );
if ( ! function_exists( 'thim_wrapper_loop_end' ) ) :
	function thim_wrapper_loop_end() {
		$class_col     = thim_wrapper_layout();
		$get_post_type = get_post_type();
		if ( is_404() ) {
			$class_col = 'col-sm-12 full-width';
		}
		echo '</main>';

		if ( $class_col == 'col-sm-9 alignleft' || $class_col == 'col-sm-9 alignright' ) {
			if ( is_search() ) {
				get_sidebar();
			} else if ( $get_post_type == "lpr_course" || $get_post_type == "lpr_quiz" || $get_post_type == "lp_course" || $get_post_type == "lp_quiz" || thim_check_is_course() || thim_check_is_course_taxonomy() ) {
				get_sidebar( 'courses' );
			} else if ( $get_post_type == "tp_event" ) {
				get_sidebar( 'events' );
			} else if ( $get_post_type == "product" ) {
				get_sidebar( 'shop' );
			} else {
				get_sidebar();
			}
		}
		echo '</div>';

		do_action( 'thim_after_site_content' );

		echo '</div>';
	}
endif;


add_action( 'thim_wrapper_loop_start', 'thim_wrapper_div_open', 1 );
if ( ! function_exists( 'thim_wrapper_div_open' ) ) {
	function thim_wrapper_div_open() {

		echo '<section class="content-area">';
	}
}

add_action( 'thim_wrapper_loop_end', 'thim_wrapper_div_close', 30 );

if ( ! function_exists( 'thim_wrapper_div_close' ) ) {
	function thim_wrapper_div_close() {
		echo '</section>';
	}
}