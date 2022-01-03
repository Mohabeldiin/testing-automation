<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-single-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

$course = LP()->global['course'];
$user   = learn_press_get_current_user();
$is_enrolled = $user->has_enrolled_course($course->get_id());

/**
 * @deprecated
 */
do_action( 'learn_press_before_main_content' );
do_action( 'learn_press_before_single_course' );
do_action( 'learn_press_before_single_course_summary' );

/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );

do_action( 'learn-press/before-single-course' );
?>

<?php if( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'new-1' ) {?>

    <div class="content_course_2">

        <div class="row">

            <div class="col-md-9 content-single">
                 <div class="course-summary">
                    <?php
                    /**
                     * @since 3.0.0
                     *
                     * @see learn_press_single_course_summary()
                     */
                    do_action( 'learn-press/single-course-summary' );
                    ?>
                </div>
                <?php thim_related_courses(); ?>

            </div>

            <div id="sidebar" class="col-md-3 sticky-sidebar">

                <div class="course_right">

                    <?php LP()->template( 'course' )->user_progress(); ?>

                    <div class="course-payment">

                        <?php
							LP()->template( 'course' )->course_pricing();
							LP()->template( 'course' )->course_buttons();
                        ?>

                    </div>

                    <?php do_action( 'thim_before_sidebar_course' ); ?>

                    <div class="menu_course">
                        <?php
                        $tabs = learn_press_get_course_tabs();
                        ?>
                        <ul>
                            <?php foreach ( $tabs as $key => $tab ) { ?>
                                <li role="presentation">
                                    <a href="#<?php echo esc_attr( $tab['id'] ); ?>" data-toggle="tab">
                                        <i class="fa <?php echo $tab['icon']; ?>"></i>
                                        <span><?php echo $tab['title']; ?></span>
                                    </a>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                    <div class="social_share">
                        <?php do_action( 'thim_social_share' ); ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

<?php } else {?>

    <div id="learn-press-course" class="course-summary learn-press">
	    <div class="row">

		    <div class="col-sm-8 col-sm-">
			    <div class="course-summary">
				    <?php
				    /**
				     * @since 3.0.0
				     *
				     * @see learn_press_single_course_summary()
				     */

  				    do_action( 'learn-press/single-course-summary' );
				    ?>
			    </div>
			    <?php thim_udemy_related_courses(); ?>
		    </div>

		    <div class="course-info-right col-sm-4 sticky-sidebar">
			    <div class="course-info-wrapper">
				    <div class="right-col__content">
					    <div class="right-col__wrapper">
						    <?php
						    do_action('learn-press/course-info-right');
						    ?>
					    </div>
				    </div>
			    </div>
		    </div>
	    </div>
    </div>

<?php }?>

<?php

/**
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content' );

do_action( 'learn-press/after-single-course' );

/**
 * @deprecated
 */
do_action( 'learn_press_after_single_course_summary' );
do_action( 'learn_press_after_single_course' );
do_action( 'learn_press_after_main_content' );
?>
