<?php

$limit        = (int) $instance['slider-options']['limit'];
$pagination   = $instance['slider-options']['show_pagination'] ? 1 : 0;
$navigation   = $instance['slider-options']['show_navigation'] ? 1 : 0;
$item_visible = (int) $instance['slider-options']['item_visible'];
if ( isset( $instance['image_size'] ) && strpos( $instance['image_size'] , 'x' ) ) {
	$size       = explode( 'x', $instance['image_size'] );
	$img_with   = $size[0];
	$img_height = $size[1];
} else {
	$img_with   = 150;
	$img_height = 100;
}
$sub_categories = $instance['sub_categories'] ? '' : 0;
$taxonomy       = 'course_category';

$args_cat = array(
	'taxonomy' => $taxonomy,
	'parent'   => $sub_categories
);

$cat_course = get_categories( $args_cat );

$demo_image_source_category = get_template_directory_uri() . '/images/demo_images/demo_image_category.jpg';

$html = '';
if ( $cat_course ) {
	$index = 1;
	$html  = '<div class="thim-carousel-course-categories">';
	$html  .= '<div class="thim-course-slider" data-visible="' . $item_visible . '" data-pagination="' . $pagination . '" data-navigation="' . $navigation . '">';
	foreach ( $cat_course as $key => $value ) {

		$top_image = get_term_meta( $value->term_id, 'thim_learnpress_top_image', true );

		$img = '<a href="' . esc_url( get_term_link( (int) $value->term_id, $taxonomy ) ) . '">';
		if ( $top_image && '' != $top_image['id'] ) {
			$img .= thim_get_feature_image( $top_image['id'], 'full', $img_with, $img_height, $value->name );
		} else {
			$img .= thim_get_feature_image( null, 'full', $img_with, $img_height, $value->name );
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
}

?>
<?php if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
} ?>
<?php
echo ent2ncr( $html );