<?php
/**
 * Template for displaying progress of single course.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$course_data       = $user->get_course_data( $course->get_id() );
$course_results    = $course_data->get_results( false );
$graduation = $course_data->get_graduation();
$passing_condition = $course->get_passing_condition();
$quiz_false        = 0;

if ( ! empty( $course_results['items'] ) ) {
	$quiz_false = $course_results['items']['quiz']['completed'] - $course_results['items']['quiz']['passed'];
}
if ( ! isset( $graduation ) ) {
	$graduation = _x( 'un-graduated', 'course graduation', 'learnpress' );
}
$classes = array(
	'lp-course-graduation',
	$graduation,
	$graduation === 'passed' ? 'success' : ( $graduation === 'failed' ? 'error' : '' ),
);


?>

<div class="course-results-progress">
	<?php do_action( 'learn-press/user-item-progress' ); ?>

	<div class="course-progress">
		<?php if ( false !== ( $heading = apply_filters( 'learn-press/course/result-heading', __( 'Course results', 'eduma' ) ) ) ) { ?>
			<label class="lp-course-progress-heading"><?php echo $heading; ?>
				<span class="value result"><b class="number">
                        <?php echo round( $course_results['result'], 2 ); ?></b>%
                </span>
			</label>
		<?php } ?>
		<div class="learn-press-progress lp-course-progress <?php echo $course_data->is_passed() ? ' passed' : ''; ?>"
			 data-value="<?php echo $course_results['result']; ?>"
			 data-passing-condition="<?php echo $passing_condition; ?>"
			 title="<?php echo esc_attr( learn_press_translate_course_result_required( $course ) ); ?>">
			<div class="progress-bg">
				<div class="progress-active lp-progress-value" style="left: <?php echo $course_results['result']; ?>%;">
				</div>
			</div>
			<div class="lp-passing-conditional"
				 data-content="<?php printf( esc_html__( 'Passing condition: %s%%', 'learnpress' ), $passing_condition ); ?>"
				 style="left: <?php echo $passing_condition; ?>%;">
			</div>
		</div>
 	</div>
	<div class="<?php echo implode( ' ', $classes ); ?>"><?php learn_press_course_grade_html( $graduation ); ?></div>
</div>
