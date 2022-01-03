<?php
$review_stars = 0;

if ( isset( $rated ) ) {
	$review_stars = (float) $rated;
}
?>
<div class="review-stars-rated">
	<div class="review-stars empty"></div>
	<div class="review-stars filled" style="width:<?php echo $review_stars * 20; ?>%;"></div>
</div>