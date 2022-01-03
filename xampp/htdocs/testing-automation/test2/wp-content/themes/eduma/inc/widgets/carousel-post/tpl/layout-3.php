<?php
global $post;

//return;
$navigation = ( $instance['show_nav'] == 'yes' ) ? 1 : 0;
$pagination = ( $instance['show_pagination'] == 'yes' ) ? 1 : 0;
$autoplay   = isset( $instance['auto_play'] ) ? $instance['auto_play'] : 0;
$layout     = !empty( $instance['layout'] ) ? $instance['layout'] : '';

$number_posts = !empty( $instance['number_posts'] ) ? $instance['number_posts'] : 8;
$visible_post = !empty( $instance['visible_post'] ) ? $instance['visible_post'] : 2;

$query_args = array(
	'posts_per_page'      => $number_posts,
	'post_type'           => 'post',
	'order'               => $instance['order'] == 'asc' ? 'asc' : 'desc',
	'ignore_sticky_posts' => true
);

if ( $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'category' ) ) {
		$query_args['cat'] = $instance['cat_id'];
	}
}
switch ( $instance['orderby'] ) {
	case 'recent' :
		$query_args['orderby'] = 'post_date';
		break;
	case 'title' :
		$query_args['orderby'] = 'post_title';
		break;
	case 'popular' :
		$query_args['orderby'] = 'comment_count';
		break;
	default : //random
		$query_args['orderby'] = 'rand';
}

$posts_display = new WP_Query( $query_args );
$id            = uniqid();
$number = count($posts_display->posts);

wp_enqueue_script( 'magnific-popup');

if ( $posts_display->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	?>
	<div class="thim-owl-carousel-post thim-carousel-wrapper <?php echo esc_attr( $layout ); ?>" data-visible="<?php echo esc_attr( $visible_post ); ?>"
	     data-pagination="<?php echo esc_attr( $pagination ); ?>" data-navigation="<?php echo esc_attr( $navigation ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-navigation-text="2">
		<?php
		$index = 1;
		while ( $posts_display->have_posts() ) :
			$posts_display->the_post();
			if ( $index == 1 || $index > 5 ) {
				echo '<div class="item">';
			} else if ( $index == 2 ) {
				echo '<div class="item item-contain">';
			}
			$format = get_post_format();
			if ( $format == 'video' ) {
				$icon = '<span class="fa fa-play"></span>';
			} elseif ( $format == 'gallery' ) {
				$icon = '<span class="fa fa-photo"></span>';
			} else {
				$icon = '<span class="fa fa-edit"></span>';
			}
			?>
			<div class="post-inner">
				<?php
				$img = thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', 420, 420, get_the_title() );
				echo ent2ncr( $icon );
				?>
				<div class="image">
					<a href="<?php echo esc_url( get_permalink() ) ?>" class="thim-gallery-popup" data-id="<?php echo get_the_ID(); ?>">
						<?php echo ent2ncr( $img ); ?>
					</a>
				</div>
				<div class="content">
					<p class="date"><?php echo get_the_date( 'Y' ); ?></p>
					<p class="title"><?php echo get_the_title(); ?></p>
				</div>
			</div>
			<?php
			if ( $index == 1 || $index >= 5 || $index == $number) {
				echo '</div>';
			}
			$index ++;
		endwhile;
		?>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			"use strict";

			jQuery('.thim-carousel-wrapper').each(function() {
				var item_visible = jQuery(this).data('visible') ? parseInt(
					jQuery(this).data('visible')) : 4,
					item_desktopsmall = jQuery(this).data('desktopsmall') ? parseInt(
						jQuery(this).data('desktopsmall')) : item_visible,
					itemsTablet = jQuery(this).data('itemtablet') ? parseInt(
						jQuery(this).data('itemtablet')) : 2,
					itemsMobile = jQuery(this).data('itemmobile') ? parseInt(
						jQuery(this).data('itemmobile')) : 1,
					pagination = !!jQuery(this).data('pagination'),
					navigation = !!jQuery(this).data('navigation'),
					autoplay = jQuery(this).data('autoplay') ? parseInt(
						jQuery(this).data('autoplay')) : false,
					navigation_text = (jQuery(this).data('navigation-text') &&
						jQuery(this).data('navigation-text') === '2') ? [
						'<i class=\'fa fa-long-arrow-left \'></i>',
						'<i class=\'fa fa-long-arrow-right \'></i>',
					] : [
						'<i class=\'fa fa-chevron-left \'></i>',
						'<i class=\'fa fa-chevron-right \'></i>',
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
					navigationText   : navigation_text,
					afterAction    : function () {
						var width_screen = jQuery(window).width();
						var width_container = jQuery('#main-home-content').width();
						var elementInstructorCourses = jQuery('.thim-instructor-courses');

						if(elementInstructorCourses.length){
							if( width_screen > width_container ){
								var margin_left_value = ( width_screen - width_container ) / 2 ;
								jQuery('.thim-instructor-courses .thim-course-slider-instructor .owl-controls .owl-buttons').css('left',margin_left_value+'px');
							}
						}
					}
				});
			});
		});
	</script>

	<div class="thim-gallery-show"></div>

	<?php
}
wp_reset_postdata();