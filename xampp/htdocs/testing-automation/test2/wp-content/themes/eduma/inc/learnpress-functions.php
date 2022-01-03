<?php

if ( ! function_exists( 'thim_learnpress_page_title' ) ) {
	function thim_learnpress_page_title( $echo = true ) {
		$title = '';
		if ( get_post_type() == 'lp_course' && ! is_404() && ! is_search() || learn_press_is_courses() || learn_press_is_course_taxonomy() ) {
			if ( learn_press_is_course_taxonomy() ) {
				$title = learn_press_single_term_title( '', false );
			} else {
				$title = esc_html__( 'All Courses', 'eduma' );
			}
		}

		if ( get_post_type() == 'lp_course' && is_search() ) {
			$title = esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() );
		}

		if ( get_post_type() == 'lp_quiz' && ! is_404() && ! is_search() ) {
			if ( is_tax() ) {
				$title = learn_press_single_term_title( '', false );
			} else {
				$title = esc_html__( 'Quiz', 'eduma' );
			}
		}
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

/**
 * Breadcrumb for LearnPress
 */
if ( ! function_exists( 'thim_learnpress_breadcrumb' ) ) {
	function thim_learnpress_breadcrumb() {

		// Do not display on the homepage
		if ( is_front_page() || is_404() ) {
			return;
		}

		// Get the query & post information
		global $post;
		// Build the breadcrums
		echo '<ul itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
  				<a itemprop="item" href="' . esc_url( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '">
					 <span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span>
   				</a>
  				<meta itemprop="position" content="1" />
 			</li>';

		if ( is_single() ) {
			$position   = 3;
			$categories = get_the_terms( $post, 'course_category' );
			if ( isset( $categories[0] ) ) {
				$cate_obj = $categories[0];
			}
			if ( class_exists( 'WPSEO_Primary_Term' ) ) {
				$primary_term = new WPSEO_Primary_Term( 'course_category', get_the_ID() );
				$primary      = $primary_term->get_primary_term();
				if ( $primary ) {
					$cate_obj = get_term_by( 'id', $primary, 'course_category' );
				}
			}

			if ( get_post_type() == 'lp_course' ) {
				// All courses
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
 						<a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '">
							<span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span>
 						</a>
						<meta itemprop="position" content="2" />
  					</li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
 							<a itemprop="item" href="' . esc_url( get_permalink( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '" title="' . esc_attr( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '">
								<span itemprop="name">' . esc_html( get_the_title( get_post_meta( $post->ID, '_lp_course', true ) ) ) . '</span>
 							</a>
							<meta itemprop="position" content="2" />
						</li>';
			}

			// Single post (Only display the first category)
			if ( isset( $cate_obj ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
 						<a itemprop="item" href="' . esc_url( get_term_link( $cate_obj ) ) . '" title="' . esc_attr( $cate_obj->name ) . '">
							<span itemprop="name">' . esc_html( $cate_obj->name ) . '</span>
  						</a>
 						<meta itemprop="position" content="3" />
					</li>';
				$position = " 4";
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
 						<span itemprop="name">' . esc_html( get_the_title() ) . '</span>
 						<meta itemprop="position" content="' . esc_attr( $position ) . '" />
 					</li>';

		} else {
			if ( learn_press_is_course_taxonomy() || learn_press_is_course_tag() ) {
				// All courses
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
							<a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '">
								<span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span>
							</a>
						<meta itemprop="position" content="2"/>
						</li>';

				// Category page
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"> 
						<span itemprop="name" title="' . esc_attr( learn_press_single_term_title( '', false ) ) . '">' . esc_html( learn_press_single_term_title( '', false ) ) . '</span>
						<meta itemprop="position" content="3"/></li>';
			} else {
				if ( ! empty( $_REQUEST['s'] ) && ! empty( $_REQUEST['ref'] ) && ( $_REQUEST['ref'] == 'course' ) ) {
					// All courses
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_course' ) ) . '" title="' . esc_attr__( 'All courses', 'eduma' ) . '">
						<span itemprop="name">' . esc_html__( 'All courses', 'eduma' ) . '</span></a>
						<meta itemprop="position" content="2"/>
					</li>';

					// Search result
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<span itemprop="name" title="' . esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'eduma' ) . ' ' . esc_html( get_search_query() ) . '</span>
						<meta itemprop="position" content="3"/></li>';
				} else {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
							<span itemprop="name" title="' . esc_attr__( 'All courses', 'eduma' ) . '">' . esc_html__( 'All courses', 'eduma' ) . '</span>
							<meta itemprop="position" content="2"/></li>';
				}
			}
		}

		echo '</ul>';
	}
}

//learn_press_is_courses() || learn_press_is_course_taxonomy()

/**
 * Display co instructors
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_co_instructors' ) ) {
	function thim_co_instructors( $course_id, $author_id ) {
		if ( ! $course_id ) {
			return;
		}
		if ( class_exists( 'LP_Co_Instructor_Preload' ) && thim_is_version_addons_instructor( '3' ) ) {
			$instructors = get_post_meta( $course_id, '_lp_co_teacher' );
			$instructors = array_diff( $instructors, array( $author_id ) );
			if ( $instructors ) {
				foreach ( $instructors as $instructor ) {
					//Check if instructor not exist
					$user = get_userdata( $instructor );
					if ( $user === false ) {
						break;
					}
					$lp_info = get_the_author_meta( 'lp_info', $instructor );
					$link    = learn_press_user_profile_link( $instructor );
					?>
					<div class="thim-about-author thim-co-instructor" itemprop="contributor" itemscope
						 itemtype="http://schema.org/Person">
						<div class="author-wrapper">
							<div class="author-avatar">
								<a href="<?php echo esc_url( $link ); ?>"><?php echo get_avatar( $instructor, 110 ); ?></a>
							</div>
							<div class="author-bio">
								<div class="author-top">
									<a itemprop="url" class="name" href="<?php echo esc_url( $link ); ?>">
										<span
											itemprop="name"><?php echo get_the_author_meta( 'display_name', $instructor ); ?></span>
									</a>
									<?php if ( isset( $lp_info['major'] ) && $lp_info['major'] ) : ?>
										<p class="job"><?php echo esc_html( $lp_info['major'] ); ?></p>
									<?php endif; ?>
								</div>
								<?php if ( function_exists( 'thim_lp_social_user' ) ) {
									thim_lp_social_user( $instructor );
								} ?>
							</div>
							<div class="author-description" itemprop="description">
								<?php echo get_the_author_meta( 'description', $instructor ); ?>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}
	}
}

/**
 * Display ratings count
 */

if ( ! function_exists( 'thim_course_ratings_count' ) ) {
	function thim_course_ratings_count( $course_id = null ) {
		if ( ! class_exists( 'LP_Addon_Course_Review' ) ) {
			return;
		}
		if ( ! $course_id ) {
			$course_id = get_the_ID();
		}
		$ratings = learn_press_get_course_rate_total( $course_id ) ? learn_press_get_course_rate_total( $course_id ) : 0;
		echo '<div class="course-comments-count">';
		echo '<div class="value"><i class="fa fa-comment"></i>';
		echo esc_html( $ratings );
		echo '</div>';
		echo '</div>';
	}
}

/**
 * Display review button
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_review_button' ) ) {
	function thim_review_button( $course_id ) {
		if ( ! class_exists( 'LP_Addon_Course_Review' ) || ! thim_is_version_addons_review( '3' ) ) {
			return;
		}

		if ( ! get_current_user_id() ) {
			return;
		}
		$user = learn_press_get_current_user();
		if ( $user->has_course_status( $course_id, array( 'enrolled', 'completed', 'finished' ) ) ) {
			if ( ! learn_press_get_user_rate( $course_id ) ) {
				?>
				<div class="add-review">
					<h3 class="title"><?php esc_html_e( 'Leave A Review', 'eduma' ); ?></h3>

					<p class="description"><?php esc_html_e( 'Please provide as much detail as you can to justify your rating and to help others.', 'eduma' ); ?></p>
					<?php do_action( 'learn_press_before_review_fields' ); ?>
					<form method="post">
						<div>
							<label for="review-title"><?php esc_html_e( 'Title', 'eduma' ); ?>
								<span class="required">*</span></label>
							<input required type="text" id="review-title" name="review-course-title"/>
						</div>
						<div>

							<label><?php esc_html_e( 'Rating', 'eduma' ); ?>
								<span class="required">*</span></label>

							<div class="review-stars-rated">
								<ul class="review-stars">
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
								</ul>
								<ul class="review-stars filled" style="width: 100%">
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
								</ul>
							</div>
						</div>
						<div>
							<label for="review-content"><?php esc_html_e( 'Comment', 'eduma' ); ?>
								<span class="required">*</span></label>
							<textarea required id="review-content" name="review-course-content"></textarea>
						</div>
						<input type="hidden" id="review-course-value" name="review-course-value" value="5"/>
						<input type="hidden" id="comment_course_ID" name="comment_course_ID"
							   value="<?php echo get_the_ID(); ?>"/>
						<button type="submit"><?php esc_html_e( 'Submit Review', 'eduma' ); ?></button>
					</form>
					<?php do_action( 'learn_press_after_review_fields' ); ?>
				</div>
				<?php
			}
		}

	}
}

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings' ) ) {
	function thim_course_ratings() {

		if ( ! class_exists( 'LP_Addon_Course_Review' ) || ! thim_is_version_addons_review( '3' ) ) {
			return;
		}
		$_reviewCount = $_aggregateRating = $rating_meta = '';
		$course_id    = get_the_ID();
		$course_rate  = learn_press_get_course_rate( $course_id );
		$ratings      = learn_press_get_course_rate_total( $course_id );
		if ( is_single() && $ratings > 0 ) {
			$_aggregateRating = 'itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"';
			$_reviewCount     = 'itemprop="reviewCount"';
			$rating_meta      = '<meta itemprop="ratingValue" content="' . esc_attr( $course_rate ) . '"/>
						<meta itemprop="ratingCount" content="' . esc_attr( $ratings ) . '"/>
						<div itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/Organization">
							<meta itemprop="name" content="' . get_the_title( $course_id ) . '"/>
						</div>';
		}
		?>
		<div class="course-review">
			<label><?php esc_html_e( 'Review', 'eduma' ); ?></label>
			<div class="value"<?php echo $_aggregateRating; ?>>
				<?php thim_print_rating( $course_rate ); ?>
				<span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), '<span ' . $_reviewCount . '>' . number_format_i18n( $ratings ) . '</span>' ) : printf( __( '(%1$s review)', 'eduma' ), '<span ' . $_reviewCount . '>0</span>' ); ?></span>
				<?php
				echo $rating_meta;
				?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'thim_print_rating' ) ) {
	function thim_print_rating( $rate ) {
		if ( ! class_exists( 'LP_Addon_Course_Review' ) || ! thim_is_version_addons_review( '3' ) ) {
			return;
		}

		?>
		<div class="review-stars-rated">
			<ul class="review-stars">
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
			</ul>
			<ul class="review-stars filled"
				style="<?php echo esc_attr( 'width: calc(' . ( $rate * 20 ) . '% - 2px)' ) ?>">
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
			</ul>
		</div>
		<?php

	}
}

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings_meta' ) ) {
	function thim_course_ratings_meta() {

		if ( ! class_exists( 'LP_Addon_Course_Review' ) || ! thim_is_version_addons_review( '3' ) ) {
			return;
		}

		$course_id   = get_the_ID();
		$course_rate = learn_press_get_course_rate( $course_id );
		$ratings     = learn_press_get_course_rate_total( $course_id );
		?>
		<div class="course-review">
			<label><?php esc_html_e( 'Review', 'eduma' ); ?></label>

			<div class="value">
				<?php echo $course_rate; ?> <?php esc_html_e( 'Stars', 'eduma' ); ?>
				<span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), number_format_i18n( $ratings ) ) : printf( __( '(%1$s review)', 'eduma' ), '<span itemprop="reviewCount">0</span>' ); ?></span>
			</div>
		</div>
		<?php
	}
}
/**
 * Breadcrumb for Courses Collection
 */
if ( ! function_exists( 'thim_courses_collection_breadcrumb' ) ) {
	function thim_courses_collection_breadcrumb() {

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_html( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span></a></li>';

		if ( is_single() ) {
			if ( get_post_type() == 'lp_collection' ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'lp_collection' ) ) . '" title="' . esc_attr__( 'Collections', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Collections', 'eduma' ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';
		} else {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html__( 'Collections', 'eduma' ) . '</span></li>';
		}

		echo '</ul>';
	}
}

if ( ! function_exists( 'thim_content_item_edit_link' ) ) {
	function thim_content_item_edit_link() {
		$course      = LP_Global::course();
		$course_item = LP_Global::course_item();
		$user        = LP_Global::user();
		if ( $user->can_edit_item( $course_item->get_id(), $course->get_id() ) ): ?>
			<p class="edit-course-item-link">
				<a href="<?php echo get_edit_post_link( $course_item->get_id() ); ?>"><i
						class="fa fa-pencil-square-o"></i> <?php _e( 'Edit item', 'eduma' ); ?>
				</a>
			</p>
		<?php endif;
	}
}
add_action( 'learn-press/after-course-item-content', 'thim_content_item_edit_link', 3 );

if ( ! function_exists( 'thim_content_item_lesson_media' ) ) {
	function thim_content_item_lesson_media() {
		$item                  = LP_Global::course_item();
		$user                  = LP_Global::user();
		$course_item           = LP_Global::course_item();
		$course                = LP_Global::course();
		$is_no_required_enroll = $is_block = false;
		if ( thim_is_new_learnpress( '4.0.8' ) ) {
			$can_view_content_course = $user->can_view_content_course( $course->get_id() );
			$is_no_required_enroll   = $course->is_no_required_enroll();
			$can_view_item           = $user->can_view_item( $course_item->get_id(), $can_view_content_course );
			$is_block                = $can_view_item->flag;
		} else {
			$can_view_item = $user->can_view_item( $course_item->get_id(), $course->get_id() );
			if ( ! $course_item->is_blocked() && $can_view_item ) {
				$is_block = true;
			}
		}

		$media_intro = get_post_meta( $item->get_id(), '_lp_lesson_video_intro', true );

		if ( ! empty( $media_intro ) && $is_block || $is_no_required_enroll ) {
			?>
			<div class="learn-press-video-intro">
				<div class="video-content">
					<?php
					if ( wp_oembed_get( $media_intro ) ) {
						echo '<div class="responsive-iframe">' . wp_oembed_get( $media_intro ) . '</div>';
					} else {
						echo str_replace(
							array( "<iframe", "</iframe>" ), array(
							'<div class="responsive-iframe"><iframe',
							"</iframe></div>"
						), do_shortcode( $media_intro )
						);
					}
					?>
				</div>
			</div>
			<?php
		}
	}
}
add_action( 'learn-press/before-course-item-content', 'thim_content_item_lesson_media', 5 );

/*
 * Before Curiculumn on item page
 */
if ( ! function_exists( 'thim_before_curiculumn_item_func' ) ) {
	function thim_before_curiculumn_item_func() {
		$args = array();
		$args = wp_parse_args(
			$args, apply_filters(
				'learn_press_breadcrumb_defaults', array(
					'delimiter'   => '<i class="fa-angle-right fa"></i>',
					'wrap_before' => '<nav class="thim-font-heading learn-press-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
					'wrap_after'  => '</nav>',
					'before'      => '',
					'after'       => '',
				)
			)
		);

		$breadcrumbs = new LP_Breadcrumb();


		$args['breadcrumb'] = $breadcrumbs->generate();

		learn_press_get_template( 'global/breadcrumb.php', $args );
	}
}
add_action( 'thim_before_curiculumn_item', 'thim_before_curiculumn_item_func' );

/**
 * @param LP_Quiz $item
 */
function thim_item_quiz_meta_questions( $item ) {
	$count = $item->count_questions();
	echo '<span class="meta count-questions">' . sprintf( $count ? _n( '%d question', '%d questions', $count, 'eduma' ) : __( '%d question', 'eduma' ), $count ) . '</span>';
}

add_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'thim_item_quiz_meta_questions', 5 );

/**
 * @param LP_Quiz|LP_Lesson $item
 */
if ( ! function_exists( 'thim_item_meta_duration' ) ) {
	function thim_item_meta_duration( $item ) {
		$duration = $item->get_duration();

		if ( is_a( $duration, 'LP_Duration' ) && $duration->get() ) {
			$format = array(
				'day'    => '%s ' . _x( 'day', 'duration', 'eduma' ),
				'hour'   => '%s ' . _x( 'hour', 'duration', 'eduma' ),
				'minute' => '%s ' . _x( 'min', 'duration', 'eduma' ),
				'second' => '%s ' . _x( 'sec', 'duration', 'eduma' ),
			);

			if ( 1 == absint( $duration->to_timer( $format, true ) ) ) {
				$format = array(
					'day'    => '%s ' . _x( 'day', 'duration', 'eduma' ),
					'hour'   => '%s ' . _x( 'hour', 'duration', 'eduma' ),
					'minute' => '%s ' . _x( 'min', 'duration', 'eduma' ),
					'second' => '%s ' . _x( 'sec', 'duration', 'eduma' ),
				);
			}

			echo '<span class="meta duration">' . $duration->to_timer( $format, true ) . '</span>';

		} elseif ( is_string( $duration ) && strlen( $duration ) ) {
			echo '<span class="meta duration">' . $duration . '</span>';
		}
	}
}
add_action( 'learn-press/course-section-item/before-lp_lesson-meta', 'thim_item_meta_duration', 5 );

/**
 * Add format icon before curriculum items
 *
 * @param $lesson_or_quiz
 * @param $enrolled
 */
if ( ! function_exists( 'thim_add_format_icon' ) ) {
	function thim_add_format_icon( $item ) {
		$format = get_post_format( $item->get_id() );
		if ( get_post_type( $item->get_id() ) == 'lp_quiz' || get_post_type( $item->get_id() ) == 'lp_h5p' ) {
			echo '<span class="course-format-icon"><i class="fa fa-puzzle-piece"></i></span>';
		} elseif ( get_post_type( $item->get_id() ) == 'lp_assignment' ) {
			echo '<span class="course-format-icon"><i class="fa fa-book"></i></span>';
		} elseif ( $format == 'video' ) {
			echo '<span class="course-format-icon"><i class="fa fa-play-circle"></i></span>';
		} elseif ( $format == 'image' ) {
			echo '<span class="course-format-icon"><i class="fa fa-file-image-o"></i></span>';
		} elseif ( $format == 'link' ) {
			echo '<span class="course-format-icon"><i class="fa fa-link"></i></span>';
		} elseif ( $format == 'gallery' ) {
			echo '<span class="course-format-icon"><i class="fa fa-images"></i></span>';
		} elseif ( $format == 'audio' ) {
			echo '<span class="course-format-icon"><i class="fa fa-volume-up"></i></span>';
		} else {
			echo '<span class="course-format-icon"><i class="fa fa-file-o"></i></span>';
		}
	}
}

add_action( 'learn_press_before_section_item_title', 'thim_add_format_icon', 10, 1 );

if ( ! function_exists( 'thim_course_tabs_content' ) ) {
	function thim_course_tabs_content( $defaults ) {
		$arr                = array();
		$course             = learn_press_get_course();
		$user               = learn_press_get_current_user();
		$theme_options_data = get_theme_mods();
		$group_tab          = isset( $theme_options_data['group_tabs_course'] ) && is_array( $theme_options_data['group_tabs_course'] ) ? $theme_options_data['group_tabs_course'] : array(
			'description',
			'curriculum',
			'instructor',
			'announcements',
			'students-list',
			'review'
		);

		//active tab
		$request_tab = ! empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : '';
		$has_active  = false;
		if ( $request_tab != '' ) {
			foreach ( $defaults as $k => $v ) {
				$v['id'] = ! empty( $v['id'] ) ? $v['id'] : 'tab-' . $k;

				if ( $request_tab === $v['id'] ) {
					$v['active'] = true;
					$has_active  = $k;
				}
				$defaults[$k] = $v;
			}
		} else {
			/**
			 * Active Curriculum tab if user has enrolled course
			 */
			if ( $course && $user->has_course_status(
					$course->get_id(), array(
						'enrolled',
						'finished'
					)
				) && ! empty( $defaults['curriculum'] ) && array_keys( $group_tab, "curriculum" )
			) {
				$defaults['curriculum']['active'] = true;
				$has_active                       = 'curriculum';
			}
		}
		foreach ( $defaults as $k => $v ) {

			switch ( $k ) {
				case 'overview':
					$v['icon']  = 'fa-bookmark';
					$new_prioty = array_keys( $group_tab, "description" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'description' && ! $has_active ) {
							$v['active'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[$k]       = $v;
					}
					break;
				case 'curriculum':
					$v['icon']  = 'fa-cube';
					$new_prioty = array_keys( $group_tab, "curriculum" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'curriculum' && ! $has_active ) {
							$v['active'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[$k]       = $v;
					}
					break;
				case 'instructor':
					$v['icon']  = 'fa-user';
					$new_prioty = array_keys( $group_tab, "instructor" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'instructor' && ! $has_active ) {
							$v['active'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[$k]       = $v;
					}
					break;
				case 'announcements':
					$v['icon'] = 'fa-envelope';

					if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == '0' ) {
						$theme_options_data['default_tab_course'] = 'announcements';
					}
					$new_prioty = array_keys( $group_tab, "announcements" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'announcements' && ! $has_active ) {
							$v['active'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[$k]       = $v;
					}
					break;
				case 'students-list':
					$v['icon']  = 'fa-list';
					$new_prioty = array_keys( $group_tab, "students-list" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'students-list' && ! $has_active ) {
							$v['active'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[$k]       = $v;
					}
					break;
				case 'reviews':
					$v['icon']  = 'fa-comments';
					$new_prioty = array_keys( $group_tab, "review" );
					if ( $new_prioty ) {
						if ( isset( $theme_options_data['default_tab_course'] ) && $theme_options_data['default_tab_course'] == 'review' && ! $has_active ) {
							$v['active'] = true;
						}
						$v['priority'] = $new_prioty[0];
						$arr[$k]       = $v;
					}
					break;
				case 'faqs':
					$v['icon'] = 'fa-question-circle';
					$arr[$k]   = $v;
					break;
			}
		}

		return $arr;
	}
}
add_filter( 'learn-press/course-tabs', 'thim_course_tabs_content', 9999 );

/**
 * Display feature certificate
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_course_certificate' ) ) {
	function thim_course_certificate( $course_id ) {

		if ( class_exists( 'LP_Addon_Certificates_Preload' ) && thim_is_version_addons_certificates( '3' ) ) {
			?>
			<li class="cert-feature">
				<i class="fa fa-rebel"></i>
				<span class="label"><?php esc_html_e( 'Certificate', 'eduma' ); ?></span>
				<span
					class="value"><?php echo ( get_post_meta( $course_id, '_lp_cert', true ) ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'No', 'eduma' ); ?></span>
			</li>
			<?php
		}
	}
}
if ( ! function_exists( 'thim_get_post_translated_duration' ) ) {
	function thim_get_post_translated_duration( $course_duration, $default = '' ) {
		$duration_arr = isset( $course_duration ) ? explode( ' ', $course_duration ) : '';
		$duration_str = '';
		if ( count( $duration_arr ) > 1 ) {
			$duration_number = $duration_arr[0];
			$duration_text   = $duration_arr[1];
			switch ( strtolower( $duration_text ) ) {
				case 'minute':
					$duration_str = sprintf( _n( '%s minute', '%s minutes', $duration_number, 'learnpress' ), $duration_number );
					break;
				case 'hour':
					$duration_str = sprintf( _n( '%s hour', '%s hours', $duration_number, 'learnpress' ), $duration_number );
					break;
				case 'day':
					$duration_str = sprintf( _n( '%s day', '%s days', $duration_number, 'learnpress' ), $duration_number );
					break;
				case 'week':
					$duration_str = sprintf( _n( '%s week', '%s weeks', $duration_number, 'learnpress' ), $duration_number );
					break;
				default:
					$duration_str = $course_duration;
			}
		}

		return empty( absint( $course_duration ) ) ? $default : $duration_str;
	}
}
/**
 * Display course info
 */
if ( ! function_exists( 'thim_course_info' ) ) {
	function thim_course_info( $for_single = true ) {
		$course    = LP()->global['course'];
		$course_id = get_the_ID();

		if ( thim_is_new_learnpress( '4.0' ) ) {
			$course_skill_level = get_post_meta( $course_id, '_lp_level', true );
			switch ( $course_skill_level ) {
				case 'beginner':
					$course_skill_level = esc_html__( 'Beginner', 'learnpress' );
					break;
				case 'intermediate':
					$course_skill_level = esc_html__( 'Intermediate', 'learnpress' );
					break;
				case 'expert':
					$course_skill_level = esc_html__( 'Expert', 'learnpress' );
					break;
				default:
					$course_skill_level = esc_html__( 'All levels', 'learnpress' );
			}
			$course_duration = get_post_meta( $course_id, 'thim_course_duration', true ) ? get_post_meta( $course_id, 'thim_course_duration', true ) : get_post_meta( $course_id, '_lp_duration', true );
		} else {
			$course_skill_level = get_post_meta( $course_id, 'thim_course_skill_level', true );
			$course_duration    = get_post_meta( $course_id, 'thim_course_duration', true );
		}
		$course_language = get_post_meta( $course_id, 'thim_course_language', true );

		?>
		<div class="thim-course-info">
			<?php if ( $for_single ) {
				echo '<h3 class="title">' . esc_html__( 'Course Features', 'eduma' ) . '</h3>';
			} ?>

			<ul>
				<li class="lectures-feature">
					<i class="fa fa-files-o"></i>
					<span class="label"><?php esc_html_e( 'Lectures', 'eduma' ); ?></span>
					<span
						class="value"><?php echo $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) : 0; ?></span>
				</li>
				<li class="quizzes-feature">
					<i class="fa fa-puzzle-piece"></i>
					<span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
					<span
						class="value"><?php echo $course->get_curriculum_items( 'lp_quiz' ) ? count( $course->get_curriculum_items( 'lp_quiz' ) ) : 0; ?></span>
				</li>

				<?php if ( ! empty( $course_duration ) ): ?>
					<li class="duration-feature">
						<i class="fa fa-clock-o"></i>
						<span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
						<span
							class="value"><?php echo thim_get_post_translated_duration( $course_duration, esc_html__( 'Lifetime access', 'learnpress' ) ); ?></span>
					</li>
				<?php endif; ?>

				<?php if ( ! empty( $course_skill_level ) ): ?>
					<li class="skill-feature">
						<i class="fa fa-level-up"></i>

						<span class="label"><?php esc_html_e( 'Skill level', 'eduma' ); ?></span>
						<span class="value"><?php echo esc_html( $course_skill_level ); ?></span>
					</li>
				<?php endif; ?>
				<?php if ( ! empty( $course_language ) ): ?>
					<li class="language-feature">
						<i class="fa fa-language"></i>
						<span class="label"><?php esc_html_e( 'Language', 'eduma' ); ?></span>
						<span class="value"><?php echo esc_html( $course_language ); ?></span>
					</li>
				<?php endif; ?>
				<li class="students-feature">
					<i class="fa fa-users"></i>
					<span class="label"><?php esc_html_e( 'Students', 'eduma' ); ?></span>
					<?php $user_count = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0; ?>
					<span class="value"><?php echo esc_html( $user_count ); ?></span>
				</li>
				<?php thim_course_certificate( $course_id ); ?>
				<li class="assessments-feature">
					<i class="fa fa-check-square-o"></i>
					<span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
					<span
						class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
				</li>
			</ul>
			<?php if ( $for_single ) {
				do_action( 'thim_after_course_info' );
			} ?>
		</div>
		<?php
	}
}

/**
 * Display the link to course forum
 */
if ( ! function_exists( 'thim_course_forum_link' ) ) {
	function thim_course_forum_link() {

		if ( ( thim_plugin_active( 'bbpress/bbpress.php' ) && thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) ) && thim_is_version_addons_bbpress( '3' ) ) {
			LP_Addon_bbPress::instance()->forum_link();
		}
	}
}

/*
 * Add real students enrolled meta for orderby attribute in the query
 * */
add_action( 'init', 'thim_add_real_student_enrolled_meta' );

if ( ! function_exists( 'thim_add_real_student_enrolled_meta' ) ) {
	function thim_add_real_student_enrolled_meta( $lp ) {
		if ( isset( $_POST['course_orderby'] ) && 'most-members' == $_POST['course_orderby'] ) {
			$arg = array(
				'post_type'           => 'lp_course',
				'post_status'         => 'publish',
				'posts_per_page'      => - 1,
				'ignore_sticky_posts' => true
			);

			$course_query = new WP_Query( $arg );

			if ( $course_query->have_posts() ) {
				while ( $course_query->have_posts() ) {
					$course_query->the_post();

					$course = learn_press_get_course( get_the_ID() );

					update_post_meta( get_the_ID(), 'thim_real_student_enrolled', $course->get_users_enrolled() );
				}

				wp_reset_postdata();
			}
		}
	}
}
/**
 * Add filter link login redirecti frontend editor
 */
add_filter( 'learn-press/frontend-editor/login-redirect', 'thim_custom_link_login_frontend_editor', 10, 1 );
if ( ! function_exists( 'thim_custom_link_login_frontend_editor' ) ) {
	function thim_custom_link_login_frontend_editor() {
		$url = add_query_arg( 'redirect_to', get_permalink( get_the_ID() ), thim_get_login_page_url() );

		return $url;
	}
}
/**
 * Display related courses
 */
if ( ! function_exists( 'thim_related_courses' ) ) {
	function thim_related_courses() {
		$related_courses    = thim_get_related_courses( 5 );
		$theme_options_data = get_theme_mods();
		$style_content      = isset( $theme_options_data['thim_layout_content_page'] ) ? $theme_options_data['thim_layout_content_page'] : 'normal';

		if ( $related_courses ) {
			$layout_grid = get_theme_mod( 'thim_learnpress_cate_layout_grid', '' );
			$cls_layout  = ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) ? ' cls_courses_2' : ' ';
			?>
			<div class="thim-ralated-course <?php echo $cls_layout; ?>">

				<?php if ( $style_content == 'new-1' ) { ?>
					<div class="sc_heading clone_title  text-left">
						<h2 class="title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h2>
						<div class="clone"><?php esc_html_e( 'You May Like', 'eduma' ); ?></div>
					</div>
				<?php } else { ?>
					<h3 class="related-title">
						<?php esc_html_e( 'You May Like', 'eduma' ); ?>
					</h3>
				<?php } ?>

				<div class="thim-course-grid">
					<div class="thim-carousel-wrapper" data-visible="3" data-itemtablet="2" data-itemmobile="1"
						 data-pagination="1">
						<?php foreach ( $related_courses as $course_item ) : ?>
							<?php
							$course                     = learn_press_get_course( $course_item->ID );
							$is_required                = $course->is_required_enroll();
							$course_des                 = get_post_meta( $course_item->ID, '_lp_coming_soon_msg', true );
							$course_item_excerpt_length = get_theme_mod( 'thim_learnpress_excerpt_length', 25 );
							$html_price                 = '';
							if ( $price = $course->get_price_html() ) {
								$origin_price = $course->get_origin_price_html();
								$sale_price   = $course->get_sale_price();
								$sale_price   = isset( $sale_price ) ? $sale_price : '';
								$class        = '';
								if ( $course->is_free() || ! $is_required ) {
									$class .= ' free-course';
									$price = esc_html__( 'Free', 'eduma' );
								}
								$html_price .= '<div class="course-price">';
								$html_price .= '<div class="value ' . $class . '">';
								if ( $sale_price ) {
									$html_price .= '<span class="course-origin-price">' . $origin_price . '</span>';
								}
								$html_price .= $price;
								$html_price .= '</div></div>';
							}

							?>
							<article class="lpr_course">
								<div class="course-item">
									<div class="course-thumbnail">
										<a class="thumb" href="<?php echo get_the_permalink( $course_item->ID ); ?>">
											<?php
											if ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) {
												echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 500, 400, get_the_title( $course_item->ID ) );
											} else {
												echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 450, 450, get_the_title( $course_item->ID ) );
											}
											if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'layout_style_2' ) {
												echo '<i class="lnr icon-arrow-right"></i>';
											}
											?>
										</a>
										<?php
										if ( class_exists( 'LP_Addon_Wishlist' ) && is_user_logged_in() ) {
											LP_Addon_Wishlist::instance()->wishlist_button( $course_item->ID );
										}
										?>

									</div>
									<div class="thim-course-content">
										<?php
										if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'layout_style_2' ) {
											echo $html_price;
											echo list_item_course_cat( get_the_ID() );
											?>
											<h2 class="course-title">
												<a rel="bookmark"
												   href="<?php echo get_the_permalink( $course_item->ID ); ?>"><?php echo esc_html( $course_item->post_title ); ?></a>
											</h2> <!-- .entry-header -->
											<div class="course-author">
												<?php echo get_avatar( $course_item->post_author, 50 ); ?>
												<div class="author-contain">
													<div class="value">
														<a href="<?php echo esc_url( learn_press_user_profile_link( $course_item->post_author ) ); ?>">
															<?php echo get_the_author_meta( 'display_name', $course_item->post_author ); ?>
														</a>
													</div>
												</div>
											</div>
										<?php } else { ?>
											<div class="course-author">
												<?php echo get_avatar( $course_item->post_author, 50 ); ?>
												<div class="author-contain">
													<div class="value">
														<a href="<?php echo esc_url( learn_press_user_profile_link( $course_item->post_author ) ); ?>">
															<?php echo get_the_author_meta( 'display_name', $course_item->post_author ); ?>
														</a>
													</div>
												</div>
											</div>
											<h2 class="course-title">
												<a rel="bookmark"
												   href="<?php echo get_the_permalink( $course_item->ID ); ?>"><?php echo esc_html( $course_item->post_title ); ?></a>
											</h2> <!-- .entry-header -->
										<?php } ?>
										<?php if ( class_exists( 'LP_Addon_Coming_Soon_Courses_Preload' ) && learn_press_is_coming_soon( $course_item->ID ) ): ?>
											<?php if ( intval( $course_item_excerpt_length ) && $course_des ): ?>
												<div class="course-description">
													<?php echo wp_trim_words( $course_des, $course_item_excerpt_length ); ?>
												</div>
											<?php endif; ?>

											<div
												class="message message-warning learn-press-message coming-soon-message">
												<?php esc_html_e( 'Coming soon', 'eduma' ) ?>
											</div>
										<?php else: ?>
											<div class="course-meta">
												<?php
												$count_student = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0;
												?>
												<div class="course-students">
													<label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
													<?php do_action( 'learn_press_begin_course_students' ); ?>

													<div class="value"><i class="fa fa-group"></i>
														<?php echo esc_html( $count_student ); ?>
													</div>
													<?php do_action( 'learn_press_end_course_students' ); ?>

												</div>
												<?php thim_course_ratings_count( $course_item->ID ); ?>
												<?php
												if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) == 'layout_style_2' ) {
													thim_course_ratings();
												} else {
													echo $html_price;
												} ?>
											</div>

										<?php endif; ?>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'thim_get_related_courses' ) ) {
	function thim_get_related_courses( $limit ) {
		if ( ! $limit ) {
			$limit = 3;
		}
		$course_id = get_the_ID();

		$tag_ids = array();
		$tags    = get_the_terms( $course_id, 'course_tag' );

		if ( $tags ) {
			foreach ( $tags as $individual_tag ) {
				$tag_ids[] = $individual_tag->term_id;
			}
		}

		$args = array(
			'posts_per_page'      => $limit,
			'paged'               => 1,
			'ignore_sticky_posts' => 1,
			'post__not_in'        => array( $course_id ),
			'post_type'           => 'lp_course'
		);

		if ( $tag_ids ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'course_tag',
					'field'    => 'term_id',
					'terms'    => $tag_ids
				)
			);
		}
		$related = array();
		if ( $posts = new WP_Query( $args ) ) {
			global $post;
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$related[] = $post;
			}
		}
		wp_reset_query();

		return $related;
	}
}

/*
 * Courses filter template
 */

if ( ! function_exists( 'thim_display_course_filter' ) ) {
	function thim_display_course_filter() {
		if ( ! get_theme_mod( 'thim_display_course_filter', false ) ) {
			return;
		}

		// Only display in the courses and course category pages
		if ( ! learn_press_is_courses() && ! learn_press_is_course_category() ) {
			return;
		}

		$total_course = wp_count_posts( 'lp_course' );

		if ( ! $total_course->publish ) {
			return;
		}
		?>
		<aside class="thim-course-filter-wrapper">
			<form action="" name="thim-course-filter" method="POST" class="thim-course-filter">
				<?php
				// Filter courses by categories
				if ( get_theme_mod( 'thim_filter_by_cate', false ) ) {
					// Check total terms and empty terms
					if ( ! wp_count_terms( 'course_category' ) ) {
						return;
					}

					$course_term = get_terms(
						apply_filters(
							'thim_display_cate_filter', array(
								'taxonomy' => 'course_category',
								'parent'   => 0
							)
						)
					);
					if ( is_wp_error( $course_term ) ) {
						return;
					}
					?>

					<h4 class="filter-title"><?php echo esc_html_x( 'Course categories', 'Course filter widget', 'eduma' ); ?></h4>

					<ul class="list-cate-filter">
						<?php
						foreach ( $course_term as $term ) {
							$input_id = $term->slug . '_' . $term->term_id;
							?>
							<li class="term-item">
							<input type="checkbox" name="course-cate-filter" id="<?php esc_attr_e( $input_id ); ?>"
								   value="<?php esc_attr_e( $term->term_id ); ?>">
							<label for="<?php esc_attr_e( $input_id ) ?>">
								<?php esc_html_e( $term->name ); ?>
								<span><?php echo '(' . $term->count . ')'; ?></span>
							</label>
							<?php
							$terms_child = get_term_children( $term->term_id, 'course_category' );
							if ( $terms_child && ! is_wp_error( $terms_child ) ) {
								echo '<ul>';
								foreach ( $terms_child as $child_id ) {
									$term_child     = get_term_by( 'id', $child_id, 'course_category' );
									$input_child_id = $term_child->slug . '_' . $child_id;
									?>
									<li class="term-item">
										<input type="checkbox" name="course-cate-filter"
											   id="<?php esc_attr_e( $input_child_id ); ?>"
											   value="<?php esc_attr_e( $child_id ); ?>">
										<label for="<?php esc_attr_e( $input_child_id ) ?>">
											<?php esc_html_e( $term_child->name ); ?>
											<span><?php echo '(' . $term_child->count . ')'; ?></span>
										</label>
									</li>
								<?php }
								echo '</ul>';
							}
							?>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}

				// Filter courses by instructors
				if ( get_theme_mod( 'thim_filter_by_instructor', false ) ) {
					// TODO can create custom query to optimize speed
					$course_query = new WP_Query(
						array(
							'post_type'      => 'lp_course',
							'posts_per_page' => - 1,
						)
					);
					?>
					<h4 class="filter-title"><?php echo esc_html_x( 'Instructors', 'Course filter widget', 'eduma' ); ?></h4>
					<?php
					if ( $course_query->have_posts() ) {
						global $post;
						$list_instructor = array();
						?>
						<ul class="list-instructor-filter">
							<?php
							while ( $course_query->have_posts() ) {
								$course_query->the_post();

								$instructor_id = $post->post_author;

								if ( ! array_key_exists( $instructor_id, $list_instructor ) ) {
									$list_instructor[$instructor_id] = 1;
								} else {
									$list_instructor[$instructor_id] += 1;
								}
							}
							wp_reset_postdata();

							foreach ( $list_instructor as $instructor_id => $total ) {
								?>
								<li class="instructor-item">
									<input type="checkbox" name="course-instructor-filter"
										   id="instructor-id_<?php esc_attr_e( $instructor_id ) ?>"
										   value="<?php esc_attr_e( $instructor_id ); ?>">
									<label for="instructor-id_<?php esc_attr_e( $instructor_id ) ?>">
										<?php echo get_the_author_meta( 'display_name', $instructor_id ) ?>
										<span><?php echo '(' . $total . ')'; ?></span>
									</label>
								</li>
								<?php
							}
							?>
						</ul>
						<?php
					}
				}

				// Filter by price
				if ( get_theme_mod( 'thim_filter_by_price' ) ) {
					$paid_course_query = new WP_Query(
						array(
							'post_type'      => 'lp_course',
							'posts_per_page' => - 1,
							//							'meta_key'       => '_lp_price',
							//							'meta_compare'   => 'EXISTS'
						)
					);

					$data       = $paid_course_query->posts;
					$count_free = 0;
					foreach ( $data as $value ) {
						$price      = (int) get_post_meta( $value->ID, '_lp_price', true );
						$sale_price = get_post_meta( $value->ID, '_lp_sale_price', true );

						if ( $price == 0 ) {
							update_post_meta( $value->ID, '_lp_free', 'yes' );
							$count_free ++;
						} else {

							if ( $sale_price == 0 && $sale_price != '' ) {
								update_post_meta( $value->ID, '_lp_free', 'yes' );
								$count_free ++;
							} else {
								update_post_meta( $value->ID, '_lp_free', 'no' );
							}

						}
					}
					$number_course      = $paid_course_query->post_count;
					$number_paid_course = $number_course - $count_free;
					?>
					<h4 class="filter-title"><?php echo esc_html_x( 'Price', 'Course filter widget', 'eduma' ); ?></h4>

					<ul class="list-price-filter">
						<?php do_action( 'thim_before_course_filters' ); ?>
						<li class="price-item">
							<input type="radio" id="price-filter_all" name="course-price-filter" value="all" checked>
							<label for="price-filter_all">
								<?php esc_html_e( 'All', 'eduma' ); ?>
								<span><?php echo '(' . $number_course . ')'; ?></span>
							</label>
						</li>
						<li class="price-item">
							<input type="radio" id="price-filter_free" name="course-price-filter" value="free">
							<label for="price-filter_free">
								<?php esc_html_e( 'Free', 'eduma' ); ?>
								<span><?php echo '(' . $count_free . ')'; ?></span>
							</label>
						</li>
						<li class="price-item">
							<input type="radio" id="price-filter_paid" name="course-price-filter" value="paid">
							<label for="price-filter_paid">
								<?php esc_html_e( 'Paid', 'eduma' ); ?>
								<span><?php echo '(' . $number_paid_course . ')'; ?></span>
							</label>
						</li>
						<?php do_action( 'thim_after_course_filters' ); ?>
					</ul>
					<?php
				}
				?>

				<?php do_action( 'thim_before_button_submit_filter_courses' ); ?>
				<div class="filter-submit">
					<button type="submit"><?php esc_html_e( 'Filter Results', 'eduma' ) ?></button>
				</div>
			</form>
		</aside>
		<?php
	}
}

add_action( 'thim_before_sidebar_course', 'thim_display_course_filter' );

/*
 * Change query get courses
 * */
add_action( 'pre_get_posts', 'thim_course_order_query', 15 );

if ( ! function_exists( 'thim_course_order_query' ) ) {
	function thim_course_order_query( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( ! is_post_type_archive( 'lp_course' ) && ! is_tax( 'course_category' ) ) {
			return;
		}

		// Sort
		if ( isset( $_POST['course_orderby'] ) ) {
			switch ( $_POST['course_orderby'] ) {
				case 'alphabetical':
					$query->set( 'orderby', 'title' );
					$query->set( 'order', 'ASC' );

					break;
				case 'most-members':
					$query->set( 'orderby', 'meta_value_num' );
					$query->set( 'meta_key', 'thim_real_student_enrolled' );
					$query->set( 'order', 'DESC' );

					break;
				default:
					$query->set( 'orderby', 'date' );
					$query->set( 'order', 'DESC' );
			}
		}

		// Pagination
		if ( isset( $_POST['course_paged'] ) ) {
			$query->set( 'paged', $_POST['course_paged'] );
		}

		// Filter by categories
		if ( isset( $_POST['course_cate_filter'] ) && is_array( $_POST['course_cate_filter'] ) ) {
			$query->set(
				'tax_query', array(
					array(
						'taxonomy' => 'course_category',
						'field'    => 'term_id',
						'terms'    => $_POST['course_cate_filter'],
					)
				)
			);
			//$query->set( 'posts_per_page', - 1 );
		}

		// Filter by instructor
		if ( isset( $_POST['course_instructor_filter'] ) && is_array( $_POST['course_instructor_filter'] ) ) {
			$query->set( 'author__in', $_POST['course_instructor_filter'] );
		}

		// Filter by price
		// TODO query courses has sale price
		if ( isset( $_POST['course_price_filter'] ) ) {
			switch ( $_POST['course_price_filter'] ) {
				case 'free':
					$query->set(
						'meta_query', array(
							array(
								'key'     => '_lp_free',
								'value'   => 'yes',
								'compare' => '=',
							)
						)
					);
					break;
				case 'paid':
					$query->set(
						'meta_query', array(
							array(
								'key'     => '_lp_free',
								'value'   => 'no',
								'compare' => '=',

							)
						)
					);
					break;
				case 'all':
					break;

				default:

			}
		}
	}
}

/**
 * Create ajax handle for courses searching
 */
if ( ! function_exists( 'thim_courses_searching_callback' ) ) {
	function thim_courses_searching_callback() {
		ob_start();
		$keyword = $_REQUEST['keyword'];
		if ( $keyword ) {
			$keyword   = strtoupper( $keyword );
			$arr_query = array(
				'post_type'           => 'lp_course',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				's'                   => $keyword,
				'posts_per_page'      => '-1'
			);

			$search = new WP_Query( $arr_query );

			$newdata = array();
			foreach ( $search->posts as $post ) {
				$newdata[] = array(
					'id'    => $post->ID,
					'title' => $post->post_title,
					'guid'  => get_permalink( $post->ID ),
				);
			}

			ob_end_clean();
			if ( count( $search->posts ) ) {
				echo json_encode( $newdata );
			} else {
				$newdata[] = array(
					'id'    => '',
					'title' => '<i>' . esc_html__( 'No course found', 'eduma' ) . '</i>',
					'guid'  => '#',
				);
				echo json_encode( $newdata );
			}
			wp_reset_postdata();
		}
		die();
	}
}

add_action( 'wp_ajax_nopriv_courses_searching', 'thim_courses_searching_callback' );
add_action( 'wp_ajax_courses_searching', 'thim_courses_searching_callback' );
/**
 * Show thumbnail single course
 */
if ( ! function_exists( 'thim_course_thumbnail_item' ) ) {
	function thim_course_thumbnail_item() {
		learn_press_get_template( 'single-course/thumbnail.php' );
	}
}

if ( ! function_exists( 'thim_landing_tabs' ) ) {
	function thim_landing_tabs() {
		if ( thim_is_new_learnpress( '4.0.0-beta-0' ) ) {
			get_template_part( 'learnpress-v4/single-course/tabs/tabs', 'landing' );
		} else {
			get_template_part( 'learnpress-v3/single-course/tabs/tabs', 'landing' );
		}
	}
}

if ( ! function_exists( 'show_pass_text' ) ) {
	function show_pass_text() {
		$user   = learn_press_get_current_user();
		$course = LP()->global['course'];
		$grade  = $user->get_course_grade( $course->get_id() );
		if ( $grade == 'passed' ) {
			echo '<div class="message message-success learn-press-success">' . ( __( 'You have finished this course.', 'eduma' ) ) . '</div>';
		}
	}
}
add_action( 'thim_begin_curriculum_button', 'show_pass_text', 5 );
/**
 * Show popular Courses
 */
if ( ! function_exists( 'thim_show_popular_courses' ) ) {
	function thim_show_popular_courses() {
		$show_popular = get_theme_mod( 'thim_learnpress_cate_show_popular' );
		if ( $show_popular && is_post_type_archive( 'lp_course' ) ) {
			//Get layout Grid/List Courses
			$layout_grid = get_theme_mod( 'thim_learnpress_cate_layout_grid', '' );
			$cls_layout  = ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) ? ' cls_courses_2' : '';

			$condition = array(
				'post_type'           => 'lp_course',
				'posts_per_page'      => 6,
				'ignore_sticky_posts' => true,
				'meta_query'          => array(
					array(
						'key'   => '_lp_featured',
						'value' => 'yes',
					)
				)
			);
			$the_query = new WP_Query( $condition );
			if ( $the_query->have_posts() ) :
				?>
				<div class="feature_box_before_archive<?php echo $cls_layout; ?>">
					<div class="container">
						<div class="thim-widget-heading thim-widget-heading-base">
							<div class="sc_heading clone_title  text-center">
								<h2 class="title"><?php esc_html_e( 'Popular Courses', 'eduma' ); ?></h2>
								<div class="clone"><?php esc_html_e( 'Popular Courses', 'eduma' ); ?></div>
							</div>
						</div>
						<div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="4"
							 data-pagination="true" data-navigation="false" data-autoplay="false">
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<div class="course-item">
									<?php
									// @since 3.0.0
									//do_action( 'learn-press/before-courses-loop-item' );
									?>

									<?php
									// @thim
									do_action( 'thim_courses_loop_item_thumb' );
									?>
									<div class="thim-course-content">
										<?php learn_press_courses_loop_item_instructor(); ?>
										<?php
										//thim_courses_loop_item_author();
										//do_action( 'learn_press_before_the_title' );
										the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
										do_action( 'learn_press_after_the_title' );
										?>
										<div class="course-meta">
											<?php learn_press_courses_loop_item_instructor(); ?>
											<?php thim_course_ratings(); ?>
											<?php learn_press_get_template( 'loop/course/students.php' ); ?>
											<?php thim_course_ratings_count(); ?>
											<?php learn_press_courses_loop_item_price(); ?>
										</div>

										<div class="course-description">
											<?php
											do_action( 'learn_press_before_course_content' );
											echo thim_excerpt( 25 );
											do_action( 'learn_press_after_course_content' );
											?>
										</div>
										<?php learn_press_courses_loop_item_price(); ?>
										<div class="course-readmore">
											<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
										</div>
									</div>
								</div>
							<?php
							endwhile;
							?>
						</div>
					</div>
				</div>
			<?php
			endif;
		}
	}
}
add_action( 'thim_before_site_content', 'thim_show_popular_courses' );

if ( ! function_exists( 'thim_course_wishlist_button' ) ) {
	function thim_course_wishlist_button( $course_id = null ) {
		if ( ! class_exists( 'LP_Addon_Wishlist' ) ) {
			return;
		}

		LP_Addon_Wishlist::instance()->wishlist_button( $course_id );
	}
}

/*
 * Hide ads Learnpress
 */
if ( get_theme_mod( 'thim_learnpress_hidden_ads', false ) ) {
	remove_action( 'admin_footer', 'learn_press_footer_advertisement', - 10 );
}

#
# Mark flag $_SESSION['eduma_do_redirect_to'] for redirect
#
add_action( 'miniorange_collect_attributes_for_authenticated_user', 'eduma_callback_action_miniorange_collect_attributes_for_authenticated_user', 10, 2 );
function eduma_callback_action_miniorange_collect_attributes_for_authenticated_user( $user, $mo_openid_redirect_url ) {
	$_SESSION['eduma_do_redirect_to'] = true;

	return $user;
}

#
# Do redirect if flag $_SESSION['eduma_do_redirect_to'] == true
#
add_filter( 'wp_redirect', 'eduma_redirect_after_mo_login', 10 );
function eduma_redirect_after_mo_login( $redirect_url ) {
	if ( isset( $_SESSION['mo_login'] ) && $_SESSION['mo_login'] == false
		&& isset( $_SESSION['eduma_do_redirect_to'] ) && $_SESSION['eduma_do_redirect_to'] == true
		&& isset( $_SESSION['eduma_redirect_to'] )
	) {
		$redirect_url = $_SESSION['eduma_redirect_to'];
		unset( $_SESSION['eduma_redirect_to'] );
		unset( $_SESSION['eduma_do_redirect_to'] );
	}
	if ( is_user_logged_in() ) {
		if ( ! session_id() ) {
			return $redirect_url;
		}
		if ( isset( $_SESSION['eduma_redirect_to'] ) ) {
			$redirect_url = $_SESSION['eduma_redirect_to'];
			unset( $_SESSION['eduma_redirect_to'] );
			if ( isset( $_SESSION['eduma_do_redirect_to'] ) ) {
				unset( $_SESSION['eduma_do_redirect_to'] );
			}
		}
	}

	return $redirect_url;
}


#
# Rewrite javascript function moOpenIdLogin to add redirect url after login
#
add_action( 'learn_press_after_single_course', 'thim_action_callback_learn_press_after_single_course', 100 ); // work for LearnPress 2
add_action( 'learn-press/after-single-course', 'thim_action_callback_learn_press_after_single_course', 100 ); // work for LearnPress 3
if ( ! function_exists( 'thim_action_callback_learn_press_after_single_course' ) ) {
	function thim_action_callback_learn_press_after_single_course() {
		if ( is_user_logged_in() || ! learn_press_is_course() ) {
			return;
		}
		$course = learn_press_get_course();
		if ( ! is_object( $course ) ) {
			return;
		}
		$is_free_course = $course->is_free();
		?>
		<script type="text/javascript">
			function moOpenIdLogin(app_name, is_custom_app) {
				<?php

				if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
					$http = "https://";
				} else {
					$http = "http://";
				}
				?>
				var base_url = '<?php echo site_url();?>';
				var request_uri = '<?php echo $_SERVER['REQUEST_URI'];?>';
				var http = '<?php echo $http;?>';
				var http_host = '<?php echo $_SERVER['HTTP_HOST'];?>';
				if (is_custom_app == 'false') {
					if (request_uri.indexOf('wp-login.php') != -1) {
						var redirect_url = base_url + '/?option=getmosociallogin&app_name=';

					} else {
						var redirect_url = http + http_host + request_uri;
						if (redirect_url.indexOf('?') != -1) {
							redirect_url = redirect_url + '&option=getmosociallogin&app_name=';
						} else {
							redirect_url = redirect_url + '?option=getmosociallogin&app_name=';
						}
					}
				} else {
					var current_url = window.location.href;
					var cname = 'redirect_current_url';
					var d = new Date();
					d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
					var expires = 'expires=' + d.toUTCString();
					document.cookie = cname + '=' + current_url + ';' + expires + ';path=/';   //path = root path(/)
					if (request_uri.indexOf('wp-login.php') != -1) {
						var redirect_url = base_url + '/?option=oauthredirect&app_name=';
					} else {
						var redirect_url = http + http_host + request_uri;
						if (redirect_url.indexOf('?') != -1)
							redirect_url = redirect_url + '&option=oauthredirect&app_name=';
						else
							redirect_url = redirect_url + '?option=oauthredirect&app_name=';
					}
				}
				var redirect_to = jQuery('#loginform input[name="redirect_to"]').val();
				redirect_url = redirect_url + app_name + '&redirect_to=' + encodeURIComponent(redirect_to);
				window.location.href = redirect_url;
			}
		</script>
		<?php
	}
}
if ( ! function_exists( 'thim_get_all_courses_instructors' ) ) {
	function thim_get_all_courses_instructors( $limits = 4 ) {
		$teacher       = array();
		$args_users    = array(
			'role'   => 'lp_teacher',
			'number' => $limits
		);
		$my_user_query = new WP_User_Query( $args_users );
		$users_by_role = $my_user_query->get_results();

		if ( $users_by_role ) {
			foreach ( $users_by_role as $user ) {
				$teacher[] = array(
					'user_id' => $user->ID,
				);
			}
		}

		return $teacher;
	}
}

/*
 * add button read more action thim-lp-course-button-read-more
 * Fix for add-on LP Woo Payment has button add-to-cart
 */
if ( ! function_exists( 'thim_button_read_more_course' ) ) {
	function thim_button_read_more_course() {
		$course = LP_Global::course();
		if ( $course ) {
			echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course->get_id() ) ) . '" >' . esc_html__( 'Read More', 'eduma' ) . '</a>';
		}
	}
}