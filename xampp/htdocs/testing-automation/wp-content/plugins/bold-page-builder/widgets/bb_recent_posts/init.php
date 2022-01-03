<?php

if ( ! class_exists( 'BB_Recent_Posts' ) ) {
	
	// RECENT POSTS	
	
	class BB_Recent_Posts extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_bb_recent_posts', // Base ID
				esc_html__( 'BB Recent Posts', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Recent posts with thumbnails.', 'bold-builder' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}

			$number = intval( trim( $instance['number'] ) );
			if ( $number < 1 ) {
				$number = 5;
			} else if ( $number > 30 ) {
				$number = 30;
			}

			$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : '';
			
			echo '<div class="btImageTextWidgetWraper"><ul>';
			
			$recent_posts = wp_get_recent_posts( array( 'numberposts' => $number, 'post_status' => 'publish' ) );
			foreach ( $recent_posts as $recent ) {
				$link = get_permalink( $recent['ID'] );
				$user_data = get_userdata( $recent['post_author'] );
				$user_url = $user_data->data->user_url;
				
				$post_format = get_post_format( $recent['ID'] );
				
				$img = get_the_post_thumbnail( $recent['ID'], 'thumbnail' );				

				echo '<li><div class="btImageTextWidget">';
				if ( $img != '' ) {
					echo '<div class="btImageTextWidgetImage"><a href="' . esc_url( $link ) . '">' . $img . '</a></div>';
				}

				$supertitle = '';
				$title = '';
				$subtitle = '';
				$date_format = get_option( 'date_format' );
				if ( $show_date != '' ) {
					$supertitle = date_i18n( $date_format, strtotime( get_the_time( 'Y-m-d', $recent['ID'] ) ) );
				}	
				echo '<div class="btImageTextWidgetText">' . do_shortcode( '[bt_bb_headline superheadline="' . htmlspecialchars( $supertitle ) . '" headline="' . htmlspecialchars($recent['post_title']) . '" url="' . $link . '" size="small" html_tag="h4"]' ) . '</div>';
				echo '</div></li>';
			}
			
			echo '</ul></div>';
				
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'bold-builder' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '5';
			$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $show_date, 'on' ); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e( 'Show date', 'bold-builder' ); ?></label>
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
			$instance['show_date'] = $new_instance['show_date'];

			return $instance;
		}
	}
}
