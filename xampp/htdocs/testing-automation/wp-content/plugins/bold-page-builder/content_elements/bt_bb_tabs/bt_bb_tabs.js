(function( $ ) {
	"use strict";
	
	$( '.bt_bb_tabs li' ).click(function() {
		$( this ).siblings().removeClass( 'on' );
		$( this ).addClass( 'on' );
		$( this ).closest( '.bt_bb_tabs' ).find( '.bt_bb_tab_item' ).removeClass( 'on' );
		$( this ).closest( '.bt_bb_tabs' ).find( '.bt_bb_tab_item' ).eq( $( this ).index() ).addClass( 'on' );
	});
	$( '.bt_bb_tabs' ).each(function() {
		$( this ).find( 'li' ).first().click();
	});
})( jQuery );