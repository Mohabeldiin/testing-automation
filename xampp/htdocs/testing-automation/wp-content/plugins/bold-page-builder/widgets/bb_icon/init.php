<?php

if ( ! class_exists( 'BB_Icon_Widget' ) ) {

	// ICON

	class BB_Icon_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_bb_icon_widget', // Base ID
				esc_html__( 'BB Icon', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Icon with text and link.', 'bold-builder' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
		
			$icon = ! empty( $instance['icon'] ) ? $instance['icon'] : '';
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
			$url = ! empty( $instance['url'] ) ? $instance['url'] : '';
			$show_button = ! empty( $instance['show_button'] ) ? $instance['show_button'] : '';
			$target = ! empty( $instance['target'] ) ? $instance['target'] : '_self';
			$extra_class = ! empty( $instance['extra_class'] ) ? $instance['extra_class'] : '';

			$extra_class = array( $extra_class );
			
			if ( $show_button != '' ) {
				$extra_class[] = 'btAccentIconWidget';
			}
			
			if ( $text != '' || $title != '' ) {
				$extra_class[] = 'btWidgetWithText';
			}

			$wrap_start_tag = '<div class="btIconWidget ' . esc_attr( implode( ' ', $extra_class ) ) . '">';
			$wrap_end_tag = '</div>';

			if ( $url != '' ) {
				$link = bt_bb_get_url( $url );
				$wrap_start_tag = '<a href="' . esc_url_raw( $link ) . '" target="' . esc_attr( $target ) . '" class="btIconWidget ' . esc_attr( implode( ' ', $extra_class ) ) . '">';
				$wrap_end_tag = '</a>';
			}

			echo $wrap_start_tag;
				if ( $icon != '' && $icon != 'no_icon' ) {
					echo '<div class="btIconWidgetIcon">';
						echo bt_bb_icon::get_html( $icon );
					echo '</div>';
				}
				if ( $title != '' || $text != '' ) {
					echo '<div class="btIconWidgetContent">';
						if ( $title != '' ) echo '<span class="btIconWidgetTitle">' . $title . '</span>';
						if ( $text != '' ) echo '<span class="btIconWidgetText">' . $text . '</span>';
					echo '</div>';
				}
			echo $wrap_end_tag;
		}

		public function form( $instance ) {
			$icon = ! empty( $instance['icon'] ) ? $instance['icon'] : '';
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
			$url = ! empty( $instance['url'] ) ? $instance['url'] : '';
			$show_button = ! empty( $instance['show_button'] ) ? $instance['show_button'] : '';
			$target = ! empty( $instance['target'] ) ? $instance['target'] : '';
			$extra_class = ! empty( $instance['extra_class'] ) ? $instance['extra_class'] : '';

			if ( function_exists( 'boldthemes_get_icon_fonts_bb_array' ) ) {
				$icon_arr = boldthemes_get_icon_fonts_bb_array();
			} else {
				require_once( dirname(__FILE__) . '/../../content_elements_misc/fa_icons.php' );
				require_once( dirname(__FILE__) . '/../../content_elements_misc/s7_icons.php' );
				$icon_arr = array( 'Font Awesome' => bt_bb_fa_icons(), 'S7' => bt_bb_s7_icons() );
			}

			$clear_display = $icon != '' ? 'block' : 'none';
			
			$icon_set = '';
			$icon_code = '';
			$icon_name = '';

			if ( $icon != '' ) {
				$icon_set = substr( $icon, 0, -5 );
				$icon_code = substr( $icon, -4 );
				$icon_code = '&#x' . $icon_code;
				foreach( $icon_arr as $k => $v ) {
					foreach( $v as $k_inner => $v_inner ) {
						if ( $icon == $v_inner ) {
							$icon_name = $k_inner;
						}
					}
				}
			}

			?>		
			<div class="bt_bb_iconpicker_widget_container">
				<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php _e( 'Icon:', 'bold-builder' ); ?></label>
				<div class="bt_bb_iconpicker">
					<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" value="<?php echo esc_attr( $icon ); ?>">
					<div class="bt_bb_iconpicker_select">
						<div class="bt_bb_icon_preview bt_bb_icon_preview_<?php echo $icon_set; ?>" data-icon="<?php echo $icon; ?>" data-icon-code="<?php echo $icon_code; ?>"></div>
						<div class="bt_bb_iconpicker_select_text"><?php echo $icon_name; ?></div>
						<i class="fa fa-close bt_bb_iconpicker_clear" style="display:<?php echo $clear_display; ?>"></i>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="bt_bb_iconpicker_filter_container">
						<input type="text" class="bt_bb_filter" placeholder="<?php _e( 'Filter...', 'bold-builder' ); ?>">
					</div>
					<div class="bt_bb_iconpicker_icons">
						<?php
						$icon_content = '';
						foreach( $icon_arr as $k => $v ) {
							$icon_content .= '<div class="bt_bb_iconpicker_title">' . $k . '</div>';
							foreach( $v as $k_inner => $v_inner ) {
								$icon = $v_inner;
								$icon_set = substr( $icon, 0, -5 );
								$icon_code = substr( $icon, -4 );
								$icon_content .= '<div class="bt_bb_icon_preview bt_bb_icon_preview_' . esc_attr( $icon_set ) . '" data-icon="' . esc_attr( $icon ) . '" data-icon-code="&#x' . esc_attr( $icon_code ) . '" title="' . esc_attr( $k_inner ) . '"></div>';
							}
						}
						echo $icon_content;
						?>
					</div>
				</div>
			</div>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php _e( 'URL or slug:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>">
			</p>
			<?php
				if ( class_exists( 'BoldThemes_Customize_Default' ) ) { ?>
					<p>
						<input class="checkbox" type="checkbox" <?php checked( $show_button, 'on' ); ?> id="<?php echo $this->get_field_id('show_button'); ?>" name="<?php echo $this->get_field_name('show_button'); ?>" /> 
						<label for="<?php echo $this->get_field_id('show_button'); ?>"><?php _e( 'Show highlighted', 'bold-builder' ); ?></label>
					</p>
				<?php }
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php _e( 'Target:', 'bold-builder' ); ?></label> 
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>">
					<option value=""></option>;
					<?php
					$target_arr = array("Self" => "_self", "Blank" => "_blank", "Parent" => "_parent", "Top" => "_top");
					foreach( $target_arr as $key => $value ) {
						if ( $value == $target ) {
							echo '<option value="' . esc_attr( $value ) . '" selected>' . $key . '</option>';
						} else {
							echo '<option value="' . esc_attr( $value ) . '">' . $key . '</option>';
						}
					}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'extra_class' ) ); ?>"><?php _e( 'CSS extra class:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'extra_class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'extra_class' ) ); ?>" type="text" value="<?php echo esc_attr( $extra_class ); ?>">
			</p>			
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['icon'] = ( ! empty( $new_instance['icon'] ) ) ? strip_tags( $new_instance['icon'] ) : '';
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
			$instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
			$instance['show_button'] = ( ! empty( $new_instance['show_button'] ) ) ? strip_tags( $new_instance['show_button'] ) : '';
			$instance['target'] = ( ! empty( $new_instance['target'] ) ) ? strip_tags( $new_instance['target'] ) : '';
			$instance['extra_class'] = ( ! empty( $new_instance['extra_class'] ) ) ? strip_tags( $new_instance['extra_class'] ) : '';

			return $instance;
		}
	}	
}