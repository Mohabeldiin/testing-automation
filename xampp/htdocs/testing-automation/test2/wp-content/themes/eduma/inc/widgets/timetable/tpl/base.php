<?php
$html       = '';
$title      = $instance['title'] ? $instance['title'] : '';
$panel_list = $instance['panel'] ? $instance['panel'] : '';
?>

<div class="thim-widget-timetable">
	<?php
	if ( $title != '' ) {
		echo '<h3 class="widget-title">' . $title . '</h3>';
	}
	?>
	<div class="timetable-group">
		<?php foreach ( $panel_list as $key => $panel ) : ?>
			<?php

			$item_data = $item_style = '';

			$class_color = ! empty( $panel['panel_color_style'] ) ? $panel['panel_color_style'] : '';

			if ( ! empty( $panel['panel_background'] ) ) {
				//$item_data .= ' data-background="' . $panel['panel_background'] . '"';
				$item_style .= 'background: ' . $panel['panel_background'];
			}

			if ( ! empty( $panel['panel_background_hover'] ) ) {
				$item_data .= 'background: ' . $panel['panel_background_hover'];
			}

			?>
			<div class="timetable-item <?php echo esc_attr( $class_color ); ?>" style="<?php echo esc_attr( $item_style ); ?>"  data-hover="<?php echo esc_attr( $item_data ); ?>">
				<?php

				echo ( ! empty( $panel['panel_title'] ) ) ? '<h5 class="title">' . $panel['panel_title'] . '</h5>' : '';

				echo ( ! empty( $panel['panel_time'] ) ) ? '<div class="time">' . $panel['panel_time'] . '</div>' : '';

				echo ( ! empty( $panel['panel_location'] ) ) ? '<div class="location">' . $panel['panel_location'] . '</div>' : '';

				echo ( ! empty( $panel['panel_teacher'] ) ) ? '<div class="teacher">' . $panel['panel_teacher'] . '</div>' : '';

				?>

			</div>

		<?php endforeach; ?>
	</div>

</div>