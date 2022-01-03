<?php

if ( ! class_exists( 'BB_Recent_Comments' ) ) {
	
	// RECENT COMMENTS	
	
	class BB_Recent_Comments extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_bb_recent_comments', // Base ID
				esc_html__( 'BB Recent Comments', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Recent comments with avatars.', 'bold-builder' ) ) // Args
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
			
			echo '<div class="latestComments"><ul>';
			
			$comments_query = new WP_Comment_Query;
			$recent_comments = $comments_query->query( array( 'number' => $number, 'status' => 'approve' ) );
			if ( $recent_comments ) {
				$date_format = get_option( 'date_format' );
				foreach ( $recent_comments as $recent ) {
					echo '<li><h5><a href="' . esc_url( get_permalink( $recent->comment_post_ID ) ) . '">' . strip_tags( get_the_title( $recent->comment_post_ID ) ) . '</a></h5><p class="posted">' . date_i18n( $date_format, strtotime( $recent->comment_date ) ) . ' &mdash; ' . esc_html__( 'by', 'bold-builder' ) . ' <a href="' . esc_url( $recent->comment_author_url ) . '">' . $recent->comment_author . '</a></p></li>';
				}
			}

			echo '</div></ul>';
				
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Comments', 'bold-builder' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '5';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of comments:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';

			return $instance;
		}
	}
}