<?php
/**
 * Template for displaying rating stars.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/course-review/rating-stars.php.
 *
 * @author  ThimPress
 * @package LearnPress/Course-Review/Templates
 * version  3.0.6
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

$percent = ( ! isset( $rated ) ) ? 0 : min( 100, ( round( $rated * 2 ) / 2 ) * 20 );
$title   = sprintf( __( '%s out of 5 stars', 'learnpress-course-review' ), round( $rated, 2 ) );

?>
<div class="review-stars-rated" title="<?php echo esc_attr( $title ); ?>">
	<?php for ( $i = 1; $i <= 5; $i ++ ) {
		$p = ( $i * 20 );
		$r = max( $p <= $percent ? 100 : ( $percent - ( $i - 1 ) * 20 ) * 5, 0 );

		?>
        <div class="review-star">
            <i class="far"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg></i>
            <i class="fas" style="width:<?php echo $r; ?>%;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg></i>
        </div>
	<?php } ?>
</div>