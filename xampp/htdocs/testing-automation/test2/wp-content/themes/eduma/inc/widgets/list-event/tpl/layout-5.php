<?php

$display_year = get_theme_mod( 'thim_event_display_year', false );
$number_posts = $instance['number_posts'] ? $instance['number_posts'] : 10;
$number_posts_slider = $instance['number_posts_slider'] ? $instance['number_posts_slider'] : 3;
$link         = get_post_type_archive_link( 'tp_event' );
$list_status = $instance['status'] ? $instance['status'] : array( 'happening', 'upcoming' );
$list_cat = $instance['cat_id'] ? $instance['cat_id'] : '';
$background_image  = $instance['background_image'] ? $instance['background_image'] : '';
$background_image_info = wp_get_attachment_image_src( $background_image, 'full' );

if( version_compare(WPEMS_VER, '2.1.5', '>=') ) {
    $query_args = array(
        'post_type'           => 'tp_event',
        'posts_per_page'      => $number_posts,
        'meta_query' => array(
            array(
                'key'     => 'tp_event_status',
                'value'   => $list_status,
                'compare' => 'IN',
            ),
        ),
        'ignore_sticky_posts' => true
    );
} else {
    $list_status = $instance['status'] ? $instance['status'] : array( 'tp-event-happenning', 'tp-event-upcoming' );
    $query_args = array(
        'post_type'           => 'tp_event',
        'posts_per_page'      => $number_posts,
        'post_status'         => $list_status,
        'ignore_sticky_posts' => true
    );
}

if( $list_cat && $list_cat != 'all' ) {
	$list_cat_arr            = explode( ',', $list_cat );
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'tp_event_category',
            'field'    => 'term_id',
            'terms'    => $list_cat_arr
        ),
    );
}

$events = new WP_Query( $query_args );
//$html    = array();
//$sorting = array();

$event_class = $instance['layout'];
if( $display_year ) {
	$event_class.= ' has-year';
}

if ( $events->have_posts() ) {
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	echo '<div class="thim-list-event ' . $event_class . '">';

	echo '<div class="thim-column-slider thim-carousel-wrapper" data-visible="1" data-itemtablet="1" data-pagination="0" data-navigation="1" data-autoplay="0">';
	$i = 1;
	while ( $events->have_posts() ) {
		$events->the_post();
		$class = 'item-event';
		$time_format = get_option( 'time_format' );

		if ( class_exists( 'WPEMS' ) ) {
			$time_start = wpems_event_start( get_option('time_format') );
			$time_end  = wpems_event_end( get_option('time_format') );

			$location   = wpems_event_location();
			$date_show  = wpems_get_time( 'd' );
			$month_show = wpems_get_time( 'M' );
			$year_show = wpems_get_time( 'Y' );
		} else {
			$time_start  = tp_event_start( get_option('time_format') );
			$time_end  = tp_event_end( get_option('time_format') );

			$location   = tp_event_location();
			$date_show  = tp_event_get_time( 'd' );
			$month_show = tp_event_get_time( 'M' );
			$year_show = tp_event_get_time( 'Y' );
		}
		if ($i <= $number_posts_slider) {
			?>
			<div <?php post_class( $class ); ?>>
				<div class="event-image">
<!--					--><?php //echo get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
					<?php echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', 590, 615, get_the_title() ); ?>
				</div>
				<div class="event-info">
					<div class="time-from">
						<?php do_action( 'thim_before_event_time' ); ?>
						<div class="date">
							<?php echo esc_html( $date_show ); ?>
						</div>
						<div class="month">
							<?php echo esc_html( $month_show ); ?>, <?php echo esc_html( $year_show ); ?>
						</div>
						<?php do_action( 'thim_after_event_time' ); ?>
					</div>

					<div class="event-wrapper">
						<h5 class="title">
							<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"> <?php echo get_the_title(); ?></a>
						</h5>

						<div class="meta">
							<?php echo ent2ncr( $location ) . ' - ' . esc_html( $time_start ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		$i++;
	}
	echo '</div>';

	echo '<div class="thim-column-list">';
	$j = 1;
	while ( $events->have_posts() ) {

		$events->the_post();
		$class = 'item-event';
		$time_format = get_option( 'time_format' );

		if ( class_exists( 'WPEMS' ) ) {
			$time_start = wpems_event_start( get_option('time_format') );
			$time_end  = wpems_event_end( get_option('time_format') );

			$location   = wpems_event_location();
			$date_show  = wpems_get_time( 'd' );
			$month_show = wpems_get_time( 'M' );
			$year_show = wpems_get_time( 'Y' );
		} else {
			$time_start  = tp_event_start( get_option('time_format') );
			$time_end  = tp_event_end( get_option('time_format') );

			$location   = tp_event_location();
			$date_show  = tp_event_get_time( 'd' );
			$month_show = tp_event_get_time( 'M' );
			$year_show = tp_event_get_time( 'Y' );
		}
		if($j > $number_posts_slider){
			?>
			<div <?php post_class( $class ); ?>>
				<div class="time-from">
					<?php do_action( 'thim_before_event_time' ); ?>
					<div class="date">
						<?php echo esc_html( $date_show ); ?>
					</div>
					<div class="month">
						<?php echo esc_html( $month_show ); ?>, <?php echo esc_html( $year_show ); ?>
					</div>
					<?php do_action( 'thim_after_event_time' ); ?>
				</div>
				<div class="event-image">
					<?php echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', 94, 94, get_the_title() ); ?>
				</div>
				<div class="event-wrapper">
					<h5 class="title">
						<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"> <?php echo get_the_title(); ?></a>
					</h5>

					<div class="meta">
						<?php echo ent2ncr( $location ) . ' - ' . esc_html( $time_start ); ?>
					</div>
				</div>
			</div>
			<?php
		}
		$j++;
	}
	if($background_image_info){
		echo '<div class="background-image">';
		echo '<img src="'. $background_image_info[0] .'" width="'. $background_image_info[1] .'" height="'. $background_image_info[2] .'" alt="background image"/>';
		echo '</div>';
	}
	echo '</div>';

//	if ( $instance['text_link'] != '' ) {
//		echo '<a class="view-all" href="' . esc_url( $link ) . '">' . $instance['text_link'] . '</a>';
//	}
	echo '</div>';
}
wp_reset_postdata();

?>
