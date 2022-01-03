<?php
$number_post = $instance['number_post'];
$columns     = $instance['columns'] ? $instance['columns'] : 4;
$pagination  = ( ! empty( $instance['show_pagination'] ) && $instance['show_pagination'] == 'no' ) ? 0 : 1;

$layout_demo_elegant = $instance['layout_demo_elegant'];
$class_demo = '';
if ( $layout_demo_elegant != 'no' ) {
    $class_demo = 'demo-elegant';
}

$our_team_args = array(
	'posts_per_page'      => $number_post,
	'post_type'           => 'our_team',
	'ignore_sticky_posts' => true
);

if ( $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'our_team_category' ) ) {
		$our_team_args['tax_query'] = array(
			array(
				'taxonomy' => 'our_team_category',
				'field'    => 'term_id',
				'terms'    => $instance['cat_id']
			),
		);
	}
}

$our_team = new WP_Query( $our_team_args );
$html     = $extral_url = '';
if ( $our_team->have_posts() ) {
	$html .= '<div class="wrapper-lists-our-team '. $class_demo .'">';
	if ( is_array( $instance['link'] ) ) {
		$link       = $instance['link']['url'];
		$extral_url = isset($instance['link']['rel']) ? ' rel="nofollow"' : '';
		$extral_url .= isset($instance['link']['target']) ? ' target="_blank"' : '';
	} else {
		$link       = $instance['link'];
		$extral_url = isset( $instance['nofollow'] ) ? ' rel="nofollow"' : '';
		$extral_url .= isset( $instance['is_external'] ) ? ' target="_blank"' : '';
	}
	if ( empty( $link ) ) {
		$link       = "#";
		$extral_url = '';
	}
	if ( $instance['text_link'] && $instance['text_link'] != '' ) {
		$html .= '<a class="join-our-team" href="' . $link . '" title="' . $instance['text_link'] . '"' . $extral_url . '>' . $instance['text_link'] . '</a>';
	}

	$html .= '<div class="thim-carousel-wrapper" data-visible="' . $columns . '" data-pagination="' . $pagination . '" >';
	while ( $our_team->have_posts() ): $our_team->the_post();
		$team_id      = get_the_ID();
		$regency      = get_post_meta( $team_id, 'regency', true );
		$link_face    = get_post_meta( $team_id, 'face_url', true );
		$link_twitter = get_post_meta( $team_id, 'twitter_url', true );
		$skype_url    = get_post_meta( $team_id, 'skype_url', true );
		$dribbble_url = get_post_meta( $team_id, 'dribbble_url', true );
		$linkedin_url = get_post_meta( $team_id, 'linkedin_url', true );

		$html .= '<div class="our-team-item">';
		$html .= '<div class="our-team-image"><a class="link-img" href="' . esc_url( get_permalink() ) . '"></a> ' . thim_get_feature_image( get_post_thumbnail_id(), 'full', apply_filters( 'thim_member_thumbnail_width', 200 ), apply_filters( 'thim_member_thumbnail_height', 200 ) );
		$html .= '<div class="social-team">';
		if ( $link_face <> '' ) {
			$html .= '<a target="_blank" href="' . $link_face . '"><i class="fa fa-facebook"></i></a>';
		}
		if ( $link_twitter <> '' ) {
			$html .= '<a target="_blank" href="' . $link_twitter . '"><i class="fa fa-twitter"></i></a>';
		}
		if ( $dribbble_url <> '' ) {
			$html .= '<a target="_blank" href="' . $dribbble_url . '"><i class="fa fa-dribbble"></i></a>';
		}
		if ( $skype_url <> '' ) {
			$html .= '<a target="_blank" href="' . $skype_url . '"><i class="fa fa-skype"></i></a>';
		}
		if ( $linkedin_url <> '' ) {
			$html .= '<a target="_blank" href="' . $linkedin_url . '"><i class="fa fa-linkedin"></i></a>';
		}
		$html .= '</div></div>';
		$html .= '<div class="content-team">';
		if ( ! empty( $instance['link_member'] ) ) {
			$html .= '<h4 class="title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h4>';
		} else {
			$html .= '<h4 class="title">' . get_the_title() . '</h4>';
		}

		if ( $regency <> '' ) {
			$html .= '<div class = "regency">' . $regency . '</div>';
		}
		$html .= '</div></div>';
	endwhile;
	$html .= '</div>';

	$html .= '<script type="text/javascript">';
	$html .= 'jQuery(document).ready(function(){';
	$html .= '"use strict";';
	$html .= 'jQuery(".thim-carousel-wrapper").each(function() {
				var item_visible = jQuery(this).data("visible") ? parseInt(
					jQuery(this).data("visible")) : 4,
					item_desktopsmall = jQuery(this).data("desktopsmall") ? parseInt(
						jQuery(this).data("desktopsmall")) : item_visible,
					itemsTablet = jQuery(this).data("itemtablet") ? parseInt(
						jQuery(this).data("itemtablet")) : 2,
					itemsMobile = jQuery(this).data("itemmobile") ? parseInt(
						jQuery(this).data("itemmobile")) : 1,
					pagination = !!jQuery(this).data("pagination"),
					navigation = !!jQuery(this).data("navigation"),
					autoplay = jQuery(this).data("autoplay") ? parseInt(
						jQuery(this).data("autoplay")) : false,
					navigation_text = (jQuery(this).data("navigation-text") &&
						jQuery(this).data("navigation-text") === "2") ? [
						"<i class=\"fa fa-long-arrow-left \"></i>",
						"<i class=\"fa fa-long-arrow-right \"></i>",
					] : [
						"<i class=\"fa fa-chevron-left \"></i>",
						"<i class=\"fa fa-chevron-right \"></i>",
					];

				jQuery(this).owlCarousel({
					items            : item_visible,
					itemsDesktop     : [1200, item_visible],
					itemsDesktopSmall: [1024, item_desktopsmall],
					itemsTablet      : [768, itemsTablet],
					itemsMobile      : [480, itemsMobile],
					navigation       : navigation,
					pagination       : pagination,
					lazyLoad         : true,
					autoPlay         : autoplay,
					navigationText   : navigation_text
				});
			});';
	$html .= '});';
	$html .= '</script>';

	$html .= '</div>';
}

wp_reset_postdata();

echo ent2ncr( $html );