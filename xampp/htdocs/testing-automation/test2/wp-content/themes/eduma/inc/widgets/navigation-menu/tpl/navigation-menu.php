<?php
$nav_menu = ! empty( $instance['menu'] ) ? wp_get_nav_menu_object( $instance['menu'] ) : false;


$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

echo '<div class="widget widget_nav_menu">';

if ( $title ) {
	echo '<h4 class="widget-title">' . $title . '</h4>';
}
if ( $nav_menu ) {
	$nav_menu_args = array(
		'fallback_cb' => '',
		'menu'        => $nav_menu
	);
	wp_nav_menu( $nav_menu_args );

} else {
	echo '<small>'.esc_html__('Edit widget and choose a menu','eduma').'</small>';
}


echo '</div>';
?>