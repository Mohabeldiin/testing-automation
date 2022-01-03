<?php

// WIDGETS

if ( ! function_exists( 'register_bb_widgets' ) ) {
	function register_bb_widgets() {
		$bb_dir = plugin_dir_path( __FILE__ );
		require_once( $bb_dir . 'bb_gallery/init.php' );
		require_once( $bb_dir . 'bb_text_image/init.php' );
		require_once( $bb_dir . 'bb_icon/init.php' );
		require_once( $bb_dir . 'bb_weather/init.php' );
		require_once( $bb_dir . 'bb_time/init.php' );
		require_once( $bb_dir . 'bb_recent_posts/init.php' );
		require_once( $bb_dir . 'bb_recent_comments/init.php' );
		require_once( $bb_dir . 'bb_instagram/init.php' );
		require_once( $bb_dir . 'bb_twitter/init.php' );
		register_widget( 'BB_Gallery' );
		register_widget( 'BB_Text_Image' );
		register_widget( 'BB_Icon_Widget' );
		register_widget( 'BB_Weather_Widget' );
		register_widget( 'BB_Time_Widget' );
		register_widget( 'BB_Recent_Posts' );
		register_widget( 'BB_Recent_Comments' );
		register_widget( 'BB_Instagram' );
		register_widget( 'BB_Twitter_Widget' );
	}
}
add_action( 'widgets_init', 'register_bb_widgets' );

function bt_bb_get_widget_font_array() {
	$fonts = array();
	$glob_match = glob( get_template_directory() . '/fonts/*/*.php' );
	if ( $glob_match ) {
		foreach( $glob_match as $file ) {
			if ( preg_match( '/(\w+)\/\1.php$/', $file, $match ) ) {
				if ( substr( $match[1], 0, 1 ) != '_' ) {
					$fonts[ $match[1] ] = $file;
				}
			}
		}
	}

	$icon_arr = array( ' ' => 'no_icon' );

	foreach( $fonts as $key => $value ) {
		require( $value );
		$icon_arr += $$set;
	}
	
	ksort( $icon_arr );

	if ( count( $icon_arr ) == 1 ) {
		require_once( dirname(__FILE__) . '/../content_elements_misc/fa_icons.php' );
		require_once( dirname(__FILE__) . '/../content_elements_misc/s7_icons.php' );
		$icon_arr += bt_bb_fa_icons();
		$icon_arr += bt_bb_s7_icons();
	}

	return $icon_arr;
}