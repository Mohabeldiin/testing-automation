<?php

if ( ! class_exists( 'BB_Gallery' ) ) {

	// GALLERY

	class BB_Gallery extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_bb_gallery', // Base ID
				esc_html__( 'BB Gallery (deprecated)', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Gallery widget.', 'bold-builder' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			if ( $instance['ids'] != '' ) {
				echo do_shortcode( '[gallery ids="' . $instance['ids'] . '"]' );
			}
			
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Gallery', 'bold-builder' );
			$ids = ! empty( $instance['ids'] ) ? $instance['ids'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>"><?php _e( 'List of image IDs (comma-separated):', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ids' ) ); ?>" type="text" value="<?php echo esc_attr( $ids ); ?>">
			</p>			
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['ids'] = ( ! empty( $new_instance['ids'] ) ) ? strip_tags( $new_instance['ids'] ) : '';

			return $instance;
		}
	}	
}