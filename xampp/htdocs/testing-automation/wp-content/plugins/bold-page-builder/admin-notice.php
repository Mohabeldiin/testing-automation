<?php

//add_action( 'admin_notices', 'bt_bb_plugin_notice' );
function bt_bb_plugin_notice() {

	global $current_user;
	
	$user_id = $current_user->ID;
	
	if ( ! get_user_meta( $user_id, 'bt_bb_plugin_notice_ignore' ) ) {
		
		echo '<div class="notice notice-warning is-dismissible bt_bb_plugin_notice"><p>'. esc_html__( 'Because of deprecation of Instagram API, BB Instagram widget requires Instagram username instead of access token since Bold Builder version 2.5.0.', 'bold-builder' ) . '<br><br>'. esc_html__( 'If you are using BB Instagram widget, please insert Instagram username in appropriate widget input field.', 'bold-builder' ) .'</p></div>';
		
	}
	
}

add_action( 'admin_footer', 'bt_bb_plugin_notice_js' );
function bt_bb_plugin_notice_js() {
	?>
	<script>
	jQuery(function( $ ) {
		$( document ).on( 'click', '.bt_bb_plugin_notice .notice-dismiss', function() {
			$.ajax( ajaxurl, {
				type: 'POST',
				data: {
					action: 'bt_bb_dismissed_notice_handler'
				}
			});
		});
	});
	</script>
	<?php
}

add_action( 'wp_ajax_bt_bb_dismissed_notice_handler', 'bt_bb_ajax_notice_handler' );
function bt_bb_ajax_notice_handler() {
	global $current_user;
	$user_id = $current_user->ID;
    add_user_meta( $user_id, 'bt_bb_plugin_notice_ignore', 'true', true );
}
