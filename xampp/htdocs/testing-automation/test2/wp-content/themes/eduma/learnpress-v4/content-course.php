<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user = LP_Global::user();

$theme_options_data         = get_theme_mods();
$course_item_excerpt_length = intval( get_theme_mod( 'thim_learnpress_excerpt_length', 25 ) );

$class = isset( $theme_options_data['thim_learnpress_cate_grid_column'] ) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-' . $theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$class .= ' lpr_course';

// edit by tuanta
$thim_show_course_meta = apply_filters( 'thim_show_course_meta', true );
$cl_coming_soon        = '';
if ( class_exists( 'LP_Addon_Coming_Soon_Courses' ) ) {
	$instance_addon = LP_Addon_Coming_Soon_Courses::instance();
	if ( $instance_addon->is_coming_soon( get_the_ID() ) ) {
		$thim_show_course_meta = false;
		$cl_coming_soon        = ' archive_coming_soon';
	}
}

?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>

	<?php
	// @deprecated
	do_action( 'learn_press_before_courses_loop_item' );
	?>

	<div class="course-item">

		<?php
		// @since 4.0.0
		//do_action( 'learn-press/before-courses-loop-item' );
		?>

		<?php
		// @thim
		do_action( 'thim_courses_loop_item_thumb' );
		?>

		<div class="thim-course-content<?php echo $cl_coming_soon; ?>">
			<?php
			if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'layout_style_2' ) {
				echo list_item_course_cat( get_the_ID() );
				the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				learn_press_courses_loop_item_instructor();
			} else {
				learn_press_courses_loop_item_instructor();
				the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			}
			?>
			<?php if ( $course_item_excerpt_length ): ?>
				<div class="course-description">
					<?php
					do_action( 'learn_press_before_course_content' );
					echo thim_excerpt( $course_item_excerpt_length );
					do_action( 'learn_press_after_course_content' );
					?>
				</div>
			<?php endif; ?>
			<?php
			if ( $thim_show_course_meta ) {
				echo '<div class="course-meta">';
				learn_press_courses_loop_item_instructor();
				thim_course_ratings();
				learn_press_get_template( 'loop/course/students.php' );
				thim_course_ratings_count();
				learn_press_courses_loop_item_price();
				echo '</div>';
			} else {
				echo '<div class="message message-warning learn-press-message coming-soon-message">' . esc_html__( 'Coming soon', 'eduma' ) . '</div>';
			}

			learn_press_courses_loop_item_price();

			if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'normal' ) {
				do_action( 'thim-lp-course-button-read-more' );
			}
			?>
		</div>

		<?php
		// @since 4.0.0
		//do_action( 'learn-press/after-courses-loop-item' );
		?>

	</div>

	<?php
	// @deprecated
	do_action( 'learn_press_after_courses_loop_item' );
	?>

</div>