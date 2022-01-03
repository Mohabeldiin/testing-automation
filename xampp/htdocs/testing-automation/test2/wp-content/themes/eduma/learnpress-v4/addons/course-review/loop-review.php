<li>
    <div class="review-container">
        <div class="review-author">
            <?php echo get_avatar( $review->user_email ) ?>
        </div>
        <div class="review-text">
            <h4 class="author-name" itemprop="author">
                <?php do_action( 'learn_press_before_review_username' ); ?>
                <?php echo $review->display_name; ?>
                <?php do_action( 'learn_press_after_review_username' ); ?>
            </h4>

			<?php learn_press_course_review_template( 'rating-stars.php', array( 'rated' => $review->rate ) ); ?>

			<p class="review-title">
                <?php do_action( 'learn_press_before_review_title' ); ?>
                <?php echo $review->title ?>
                <?php do_action( 'learn_press_after_review_title' ); ?>
            </p>

            <div class="description" itemprop="reviewBody">
				<div class="review-content">
					<?php do_action( 'learn_press_before_review_content' ); ?>
					<?php echo $review->content ?>
					<?php do_action( 'learn_press_after_review_content' ); ?>
				</div>
            </div>
        </div>
    </div>
</li>