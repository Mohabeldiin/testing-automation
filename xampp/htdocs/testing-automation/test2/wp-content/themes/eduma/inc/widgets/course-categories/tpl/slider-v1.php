<?php

$limit          = (int) $instance['slider-options']['limit'];
$pagination     = $instance['slider-options']['show_pagination'] ? 1 : 0;
$navigation     = $instance['slider-options']['show_navigation'] ? 1 : 0;
$item_visible   = (int) $instance['slider-options']['item_visible'];
$sub_categories = $instance['sub_categories'] ? '' : 0;
$taxonomy       = 'course_category';
$autoplay       = isset( $instance['slider-options']['auto_play'] ) ? $instance['slider-options']['auto_play'] : 0;
if ( isset( $instance['image_size'] ) && strpos( $instance['image_size'] , 'x' ) ) {
	$size       = explode( 'x', $instance['image_size'] );
	$img_with   = $size[0];
	$img_height = $size[1];
} else {
	$img_with   = 150;
	$img_height = 100;
}
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
	$html  = '<div class="thim-carousel-course-categories">';
	$html  .= '<div class="thim-course-slider" data-visible="' . $item_visible . '" data-pagination="' . $pagination . '" data-navigation="' . $navigation . '" data-autoplay="' . esc_attr( $autoplay ) . '">';
	foreach ( $cat_course as $key => $value ) {

		$top_image = get_term_meta( $value->term_id, 'thim_learnpress_top_image', true );

		$img = '<a href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">';
		if ( $top_image && '' != $top_image['id'] ) {
			$img .= thim_get_feature_image( $top_image['id'], 'full', $img_with, $img_height, $value->name );
		} else {
			$img .= thim_get_feature_image( null, 'full',$img_with, $img_height, $value->name );
		}
		$img .= '</a>';

		$html .= '<div class="item">';
		$html .= '<div class="image">';
		$html .= $img;
		$html .= '</div>';
		$html .= '<h3 class="title"><a href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">' . $value->name . '</a></h3>';
		$html .= '</div>';
		if ( $index == $limit ) {
			break;
		}
		$index ++;
	}
	$html .= '</div></div>';

	$html .= '<script type="text/javascript">';
	$html .= 'jQuery(document).ready(function(){';
	$html .= '"use strict";';
	$html .= 'jQuery(".thim-carousel-course-categories .thim-course-slider").each(function() {
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
