<?php
global $post;

$limit             = $instance['limit'];
$columns           = $instance['grid-options']['columns'];
$view_all_course   = ( $instance['view_all_courses'] && '' != $instance['view_all_courses'] ) ? $instance['view_all_courses'] : false;
$view_all_position = ( $instance['view_all_position'] && '' != $instance['view_all_position'] ) ? $instance['view_all_position'] : 'top';
$sort              = $instance['order'];
$feature           = ! empty( $instance['featured'] ) ? true : false;
$thumb_w           = ( ! empty( $instance['thumbnail_width'] ) && '' != $instance['thumbnail_width'] ) ? $instance['thumbnail_width'] : apply_filters( 'thim_course_thumbnail_width', 450 );
$thumb_h           = ( ! empty( $instance['thumbnail_height'] ) && '' != $instance['thumbnail_height'] ) ? $instance['thumbnail_height'] : apply_filters( 'thim_course_thumbnail_height', 400 );

$condition = array(
	'post_type'           => 'lp_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {
		$condition['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $instance['cat_id']
			),
		);
	}
}

if ( $sort == 'popular' ) {
	$post_in               = eduma_lp_get_popular_courses( $limit );
	$condition['post__in'] = $post_in;
	$condition['orderby']  = 'post__in';
}

if ( $feature ) {
	$condition['meta_query'] = array(
		array(
			'key'   => '_lp_featured',
			'value' => 'yes',
		)
	);
}

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	echo '<div class="grid-1">';
	if ( $view_all_course && 'top' == $view_all_position ) {
		echo '<a class="view-all-courses position-top" href="' . get_post_type_archive_link( 'lp_course' ) . '">' . esc_attr( $view_all_course ) . ' <i class="lnr icon-arrow-right"></i></a>';
	}
	?>
	<div class="thim-course-grid">
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div class="lpr_course <?php echo 'course-grid-' . $columns; ?>">
				<div class="course-item">
					<div class="course-thumbnail">
						<a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>">
							<?php echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', $thumb_w, $thumb_h, get_the_title() ); ?>
						</a>
						<?php do_action( 'thim_inner_thumbnail_course' ); ?>
						<?php if ( class_exists( 'LP_Addon_Course_Review' ) ) { ?>
							<?php $course_rate = learn_press_get_course_rate( get_the_ID() ); ?>
							<div class="rate">
								<i class="lnr icon-star"></i>
								<span
									class="number_rate"><?php echo ( ! empty( $course_rate ) ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?></span>
							</div>
						<?php } ?>
						<?php
						// only button read more
						do_action( 'thim-lp-course-button-read-more' );
						?>
					</div>
					<div class="thim-course-content">
						<?php
						learn_press_courses_loop_item_instructor();
						the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						?>

						<?php if ( class_exists( 'LP_Addon_Coming_Soon_Courses_Preload' ) && learn_press_is_coming_soon( get_the_ID() ) ): ?>
							<div class="message message-warning learn-press-message coming-soon-message">
								<?php esc_html_e( 'Coming soon', 'eduma' ) ?>
							</div>
						<?php else: ?>
							<div class="course-meta">
								<?php learn_press_courses_loop_item_instructor(); ?>
								<?php thim_course_ratings(); ?>
								<?php learn_press_get_template( 'loop/course/students.php' ); ?>
								<?php thim_course_ratings_count(); ?>
								<?php learn_press_courses_loop_item_price(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		endwhile;
		?>
	</div>
	<?php
	if ( $view_all_course && 'bottom' == $view_all_position ) {
		echo '<div class="wrapper-bottom-view-courses"><a class="view-all-courses position-bottom" href="' . get_post_type_archive_link( 'lp_course' ) . '">' . esc_attr( $view_all_course ) . ' <i class="lnr icon-arrow-right"></i></a></div>';
	}
	echo '</div>';

endif;

wp_reset_postdata();
