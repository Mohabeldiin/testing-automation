<?php

$limit          = (int) $instance['slider-options']['limit'];
$pagination     = $instance['slider-options']['show_pagination'] ? 1 : 0;
$navigation     = $instance['slider-options']['show_navigation'] ? 1 : 0;
$sub_categories = $instance['sub_categories'] ? '' : 0;
$item_visible   = (int) $instance['slider-options']['item_visible'];
$taxonomy       = 'course_category';
$autoplay       = isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;


$args_cat = array(
	'taxonomy' => $taxonomy,
	'parent'   => $sub_categories
);
$args_cat = apply_filters( 'thim_query_slider_categories', $args_cat );

$cat_course = get_categories( $args_cat );

$demo_image_source_category = get_template_directory_uri() . '/images/demo_images/demo_image_category.jpg';

$html = '';
if ( $cat_course ) {
	$index = 1;
	$html  = '<div class="thim-carousel-course-categories-tabs">';
	$html  .= '<div class="thim-course-slider" data-visible="' . $item_visible . '" data-desktop="' . $item_visible . '" data-tablet="4" data-mobile="1" data-pagination="' . $pagination . '" data-navigation="' . $navigation . '" data-autoplay="' . esc_attr( $autoplay ) . '">';
	$i     = 0;
	foreach ( $cat_course as $key => $value ) {
		$i ++;
		if ( get_term_meta( $value->term_id, 'thim_learnpress_cate_icon', true ) ) {
			$icon = get_term_meta( $value->term_id, 'thim_learnpress_cate_icon', true );
		}
		$top_image = get_term_meta( $value->term_id, 'thim_learnpress_top_image', true );

		$img = '<a href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">';
		if ( ! empty( $top_image ) && '' != $top_image['id'] ) {
			$img .= thim_get_feature_image( $top_image['id'], 'full', apply_filters( 'thim_course_category_thumbnail_width', 150 ), apply_filters( 'thim_course_category_thumbnail_height', 100 ), $value->name );
		} else {
			$img .= thim_get_feature_image( null, 'full', apply_filters( 'thim_course_category_thumbnail_width', 150 ), apply_filters( 'thim_course_category_thumbnail_height', 100 ), $value->name );
		}
		$img    .= '</a>';
		$active = ( $i == 1 ) ? 'active' : '';

		$html .= '<div class="item ' . $active . '">';
		$html .= '<div class="icon">';
		if ( ! empty( $icon ) ) {
			$alt = '';
			$alt = get_post_meta( $icon['id'], '_wp_attachment_image_alt', true ) ? get_post_meta( $icon['id'], '_wp_attachment_image_alt', true ) : $value->name;
			if ( is_array( $icon ) ) {
				$html .= '<img alt="' . $alt . '" src="' . $icon['url'] . '">';
			} else {
				$html .= '<i class="' . $icon . '"></i>';
			}
		}
		$html .= '</div>';
		$html .= '<h3 class="title"><a href="#' . $value->slug . '">' . $value->name . '</a></h3>';
		$html .= '</div>';
		if ( $index == $limit ) {
			break;
		}
		$index ++;
	}
	$html .= '</div>';
	$html .= '<div class="content_items">';
	$i    = 0;
	foreach ( $cat_course as $key => $value ) {
		$i ++;
		$active  = ( $i == 1 ) ? 'active' : '';
		$thumb   = get_term_meta( $value->term_id, 'thim_learnpress_cate_thumnail', true );
		$content = get_term_meta( $value->term_id, 'thim_learnpress_cate_content', true );
		$content = htmlspecialchars_decode( $content );
		$content = wpautop( $content );
		if ( isset( $thumb["url"] ) ) {
			$link_cat = '<a class="view_all_courses" href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">' . esc_html__( 'View all courses', 'eduma' ) . ' <i class="lnr icon-arrow-right"></i></a>';
		}
		$html .= '<div class="item_content ' . $active . '" id="' . $value->slug . '">';
		if ( isset( $thumb["url"] ) ) {
			$html .= '<img class="fleft" src="' . $thumb["url"] . '">';
		}
		$html .= '<div class="content">' . $content . '</div>';
		$html .= $link_cat;
		$html .= '</div>';
	}
	$html .= '</div>';
	$html .= '</div>';

	$html .= '<script type="text/javascript">';
	$html .= 'jQuery(document).ready(function(){';
	$html .= '"use strict";';
	$html .= 'jQuery(".thim-carousel-course-categories-tabs .thim-course-slider").each(function() {
					var item_visible = jQuery(this).data("visible") ? parseInt(jQuery(this).data("visible")) : 7,
                    item_desktop = jQuery(this).data("desktop") ? parseInt(jQuery(this).data("desktop")) : item_visible,
                    item_desktopsmall = jQuery(this).data("desktopsmall")
                        ? parseInt(jQuery(this).data("desktopsmall"))
                        : 6,
                    item_tablet = jQuery(this).data("tablet") ? parseInt(jQuery(this).data("tablet")) : 4,
                    item_mobile = jQuery(this).data("mobile") ? parseInt(jQuery(this).data("mobile")) : 2,
                    pagination = !!jQuery(this).data("pagination"),
                    navigation = !!jQuery(this).data("navigation"),
                    autoplay = jQuery(this).data("autoplay") ? parseInt(jQuery(this).data("autoplay")) : false,
                    is_rtl = jQuery("body").hasClass("rtl");

                    jQuery(this).owlCarousel({
                        items            : item_visible,
                        itemsDesktop     : [1800, item_desktop],
                        itemsDesktopSmall: [1024, item_desktopsmall],
                        itemsTablet      : [768, item_tablet],
                        itemsMobile      : [480, item_mobile],
                        navigation       : navigation,
                        pagination       : pagination,
                        autoPlay         : autoplay,
                        navigationText   : [
							"<i class=\"fa fa-chevron-left \"></i>",
							"<i class=\"fa fa-chevron-right \"></i>",
						],
                    });
            });';
	$html .= '});';
	$html .= '</script>';
}

if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
}
echo ent2ncr( $html );