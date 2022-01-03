<?php

$number_posts = $instance['number_posts'] ? $instance['number_posts'] : 10;
$link         = get_post_type_archive_link( 'tp_event' );
$list_status  = $instance['status'] ? $instance['status'] : array( 'happening', 'upcoming' );
$list_cat     = $instance['cat_id'] ? $instance['cat_id'] : '';

$query_args = array(
	'post_type'           => 'tp_event',
	'posts_per_page'      => - 1,
	'meta_query'          => array(
		array(
			'key'     => 'tp_event_status',
			'value'   => $list_status,
			'compare' => 'IN',
		),
	),
	'ignore_sticky_posts' => true
);

if ( $list_cat && $list_cat != 'all' ) {
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

$item_visible = $instance['number_posts_slider'];
if ( $events->have_posts() ) {
	echo '<div class="list-event-' . $instance['layout'] . '">';
	if ( $instance['title'] || $instance['sub_title'] ) {
		echo '<div class="event-widget-title">';
		if ( $instance['title'] ) {
			echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
		}
		if ( $instance['sub_title'] ) {
			echo '<div class="sub_title">' . $instance['sub_title'] . '</div>';
		}
		echo '</div>';
	}
	echo '<div class="thim_full_right thim-event-' . $instance['layout'] . '"><div class="inner-content-thim-event">';
	echo '<div class="thim-carousel-wrapper"  data-visible="' . esc_attr( $item_visible ) . '" data-itemtablet="2" data-itemmobile="1" data-pagination="0" data-navigation="1" data-autoplay="0">';
	while ( $events->have_posts() ) {
		$events->the_post();
		$time_format = get_option( 'time_format' );

		$time_from = wpems_event_start( get_option( 'time_format' ) );
		$time_end  = wpems_event_end( get_option( 'time_format' ) );

		$location  = wpems_event_location();
		$date_show = wpems_get_time( 'd' );

		?>
		<div class="item-slider">
			<div class="image">
				<?php echo get_the_post_thumbnail( get_the_ID(), array(475,350) ); ?>
				<div class="date">
					<?php echo esc_html( $date_show ); ?>
				</div>
			</div>
			<div class="event-wrapper">
				<h5 class="title">
					<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"> <?php echo get_the_title(); ?></a>
				</h5>
				<?php
				echo '<div class="event-meta">';
				if ( $time_from || $time_end ) {
					echo '<span class="time-from-end"><i class="las la-clock"></i>' . $time_from . ' - ' . $time_end . '</span>';
				}
				if ( $location ) {
					echo '<span class="location"><i class="las la-map"></i>' . $location . '</span>';
				}
				echo '</div>';
				?>
				<div class="desc">
					<?php echo thim_excerpt( 15 ); ?>
				</div>
				<a class="link-event"
				   href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><?php esc_html_e( 'View Details', 'eduma' ); ?>
				</a>
			</div>
		</div>
		<?php
	}
	echo '</div>';
	echo '</div></div>';

	if ( $instance['text_link'] != '' ) {
		echo '<a class="view-all" href="' . esc_url( $link ) . '">' . $instance['text_link'] . '</a>';
	}

	echo '</div>';
}
wp_reset_postdata();
?>