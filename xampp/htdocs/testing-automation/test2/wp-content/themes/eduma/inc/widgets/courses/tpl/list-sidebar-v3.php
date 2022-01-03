<?php
global $post;
$limit    = $instance['limit'];
$sort     = $instance['order'];
$featured = ! empty( $instance['featured'] ) ? true : false;

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
    $post_in = eduma_lp_get_popular_courses( $limit );

    $condition['post__in'] = $post_in;
	$condition['orderby']  = 'post__in';
}

if ( $featured ) {
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
	?>
	<div class="thim-course-list-sidebar">
		<?php
		while ( $the_query->have_posts() ) : $the_query->the_post();
			?>
			<div class="lpr_course <?php echo has_post_thumbnail() ? 'has-post-thumbnail' : ''; ?>">
				<?php
				if ( has_post_thumbnail() ) {
					$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
					echo '<div class="course-thumbnail">';
					echo '<img src="' . esc_url( $src[0] ) . '" alt="' . get_the_title() . '"/>';
					echo '</div>';
				}
				?>
				<div class="thim-course-content">
					<h3 class="course-title">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
					</h3>
					<?php if ( class_exists( 'LP_Addon_Coming_Soon_Courses_Preload' ) && learn_press_is_coming_soon( get_the_ID() ) ): ?>
						<div class="message message-warning learn-press-message coming-soon-message">
							<?php esc_html_e( 'Coming soon', 'eduma' ) ?>
						</div>
					<?php else: ?>
						<?php learn_press_courses_loop_item_price(); ?>
					<?php endif; ?>
				</div>
			</div>
		<?php
		endwhile;
		?>
	</div>
<?php
endif;
wp_reset_postdata();