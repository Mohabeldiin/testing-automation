<?php
/**
 * Template for displaying course rate.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/course-review/course-rate.php.
 *
 * @author  ThimPress
 * @package LearnPress/Course-Review/Templates
 * version  3.0.1
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

$course_id       = get_the_ID();
$course_rate_res = learn_press_get_course_rate( $course_id, false );
$course_rate     = $course_rate_res['rated'];
$total           = $course_rate_res['total'];
?>

<div class="course-rate">
    <div class="course-rate__summary">
        <div class="course-rate__summary-value"><?php echo number_format( $course_rate, 1 ); ?></div>
        <div class="course-rate__summary-stars">
			<?php
			learn_press_course_review_template( 'rating-stars.php', array( 'rated' => $course_rate ) );
			?>
        </div>
        <div class="course-rate__summary-text">
			<?php printf( __( '<span>%d</span> total', 'learnpress-course-review' ), $total ); ?>
        </div>

    </div>

    <div class="course-rate__details">
		<?php
		foreach ( $course_rate_res['items'] as $item ):
			?>
            <div class="course-rate__details-row">
                <span class="course-rate__details-row-star">
                    <?php esc_html_e( $item['rated'] ); ?>
                    <i class="fas" style="color: #ffb60a">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
                    </i>
                </span>
                <div class="course-rate__details-row-value">
                    <div class="rating-gray"></div>
                    <div class="rating" style="width:<?php echo $item['percent']; ?>%;"
                         title="<?php echo esc_attr( $item['percent'] ); ?>%">
                    </div>
                    <span class="rating-count"><?php echo $item['total']; ?></span>
                </div>
            </div>
		<?php
		endforeach;
		?>
    </div>

</div>
