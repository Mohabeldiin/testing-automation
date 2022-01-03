<?php

function bt_bb_tips() {
	
	$screen = get_current_screen();
	if ( $screen->base != 'post' ) {
        return;
    }
	
	$options = get_option( 'bt_bb_settings' );
	
	$post_types = get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ) );
	$active_pt = array();
	foreach( $post_types as $pt ) {
		if ( ! $options || ( ! array_key_exists( $pt, $options ) || ( array_key_exists( $pt, $options ) && $options[ $pt ] == '1' ) ) ) {
			$active_pt[] = $pt;
		}
	}
	$screens = $active_pt;
	if ( ! in_array( $screen->post_type, $screens ) && $screen->base == 'post' ) {
		return;
	}
	
	echo '<script>';

	?>
	(function( $ ) {
		function get_random_int( min, max ) {
			min = Math.ceil( min );
			max = Math.floor( max );
			return Math.floor( Math.random() * ( max - min ) + min ); // The maximum is exclusive and the minimum is inclusive
		}
		$( window ).on( 'load', function() {
			
			$( '#bt_bb_editor_toolbar' ).append( ' <span class="bt_bb_tips"><span class="bt_bb_tips_content"></span><span class="bt_bb_tips_prev" title="' + window.bt_bb_text.prev_tip + '"></span><span class="bt_bb_tips_next" title="' + window.bt_bb_text.next_tip + '"></span></span>' );
			let random_int = get_random_int( 0, window.bt_bb_text._tips.length );
			$( '.bt_bb_tips' ).data( 'i', random_int );
			$( '.bt_bb_tips_content' ).html( window.bt_bb_text._tips[ random_int ] );
			
			$( '.bt_bb_tips_prev' ).on( 'click', function() {
				let i = $( '.bt_bb_tips' ).data( 'i' ) - 1;
				if ( i < 0 ) {
					i = window.bt_bb_text._tips.length - 1;
				}
				$( '.bt_bb_tips_content' ).html( window.bt_bb_text._tips[ i ] );
				$( '.bt_bb_tips' ).data( 'i', i );
			});
			
			$( '.bt_bb_tips_next' ).on( 'click', function() {
				let i = $( '.bt_bb_tips' ).data( 'i' ) + 1;
				if ( i > window.bt_bb_text._tips.length - 1 ) {
					i = 0;
				}
				$( '.bt_bb_tips_content' ).html( window.bt_bb_text._tips[ i ] );
				$( '.bt_bb_tips' ).data( 'i', i );
			});
			
		});
			
	}( jQuery ));
	<?php
	
	echo '</script>';
}
add_action( 'admin_footer', 'bt_bb_tips', 20 );