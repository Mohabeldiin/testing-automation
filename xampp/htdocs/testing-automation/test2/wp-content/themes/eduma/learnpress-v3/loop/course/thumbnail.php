<?php
/**
 * Template for displaying thumbnail of course within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/thumbnail.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.1
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
if ( ! $course ) {
    return;
}
?>

<div class="course-thumbnail">
    <a class="thumb" href="<?php echo get_the_permalink(); ?>">
	    <?php
            $el_image = $course->get_image( 'course_thumbnail' );
            if ( is_string( $el_image ) ) {
                echo $el_image;
            }
        ?>
    </a>
    <?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
</div>
