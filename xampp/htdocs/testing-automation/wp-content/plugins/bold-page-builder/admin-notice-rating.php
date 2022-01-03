<?php

add_action( 'admin_notices', 'bt_bb_rating_notice' );
function bt_bb_rating_notice() {

	global $current_user;
	
	$user_id = $current_user->ID;

	if ( ! get_user_meta( $user_id, 'bt_bb_rating_notice_ignore' ) && get_user_meta( $user_id, 'bt_bb_rating_notice_enable' ) ) {
		
		echo '<div class="notice notice-info is-dismissible bt_bb_rating_notice" style="background:#f2f8ff;"><p>'. esc_html__( 'Do you like Bold Builder?', 'bold-builder' ) . ' ' . esc_html__( 'You can contribute by leaving us a', 'bold-builder' ) . ' ' . ' <a href="https://wordpress.org/support/plugin/bold-page-builder/reviews/?filter=5" target="_blank">'. esc_html__( 'review', 'bold-builder' ) . '</a>' . '. ' . esc_html__( 'Thanks!', 'bold-builder' ) . ' 🙂</p></div>';
		
	}
	
}

add_action( 'admin_footer', 'bt_bb_rating_notice_js' );
function bt_bb_rating_notice_js() {
	?>
	<script>
	jQuery(function( $ ) {
		
		$( document ).ready(function() {
			
			let alo = localStorage.getItem( 'bt_bb_alo' );
			if ( alo ) {
				try {
					alo = JSON.parse( alo );
				} catch ( error ) {
					console.error( error );
					alo = {};
				}
			} else {
				alo = {};
			}
			
			if ( ( alo.copy !== undefined && alo.copy > 9 ) && ( alo.paste !== undefined && alo.paste > 9 ) && ( alo.clone !== undefined && alo.clone > 4 ) && ( alo.add_horizontal !== undefined && alo.add_horizontal > 4 ) && ( alo.add !== undefined && alo.add > 4 ) && ( alo.edit !== undefined && alo.edit > 4 ) && ( alo.add_root !== undefined && alo.add_root > 4 ) && ( alo.delete !== undefined && alo.delete > 4 ) && ( alo.move_down !== undefined && alo.move_down > 4 ) ) {
				$.ajax( ajaxurl, {
					type: 'POST',
					data: {
						action: 'bt_bb_rating_notice_enable'
					}
				});
			}
			
			$( document ).on( 'click', '.bt_bb_rating_notice .notice-dismiss', function() {
				$.ajax( ajaxurl, {
					type: 'POST',
					data: {
						action: 'bt_bb_dismissed_rating_notice_handler'
					}
				});
			});
			
		});

	});
	</script>
	<?php
}

add_action( 'wp_ajax_bt_bb_dismissed_rating_notice_handler', 'bt_bb_ajax_rating_notice_handler' );
function bt_bb_ajax_rating_notice_handler() {
	global $current_user;
	$user_id = $current_user->ID;
    add_user_meta( $user_id, 'bt_bb_rating_notice_ignore', 'true', true );
}

add_action( 'wp_ajax_bt_bb_rating_notice_enable', 'bt_bb_ajax_rating_notice_enable' );
function bt_bb_ajax_rating_notice_enable() {
	global $current_user;
	$user_id = $current_user->ID;
    add_user_meta( $user_id, 'bt_bb_rating_notice_enable', 'true', true );
}

