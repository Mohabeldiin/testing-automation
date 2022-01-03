(function( $ ) {
	
	var data;
	
	$( document ).ready(function() {

		function GetURLParameter(sParam) {
			let sPageURL = window.location.search.substring(1);
			let sURLVariables = sPageURL.split('&');
			for (let i = 0; i < sURLVariables.length; i++) {
				let sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam) {
					return sParameterName[1];
				}
			}
			return 0;
		}
			
		$( '.bt_import_xml' ).on( 'click', function() {
			
			window.bt_import_file = $( this ).data( 'file' );
			
			//var bt_import_step = localStorage.getItem( 'bt_import_step' ) ? parseInt( localStorage.getItem( 'bt_import_step' ) ) : 0;
			
			window.bt_import_step = 0;
			
			window.bt_import_step_attempt = 1;
			
			window.force_download = GetURLParameter('force_download');
			
			window.bt_import_reader_index = 0;
				
			data = {
				'action': 'bt_import_ajax',
				'file': window.bt_import_file,
				'step': window.bt_import_step,
				'reader_index': 0,
				'force_download': window.force_download,
				'_ajax_nonce': window.bt_import_ajax_nonce
			}
			
			$( '.bt_import_select_xml' ).hide();
			$( '.bt_import_xml_container' ).hide();

			$( '.bt_import_progress' ).show();
			
			window.bt_import_ajax( data );
			
		});
		
		window.bt_import_ajax = function( data ) {
			$.ajax({
				type: 'POST',
				url: window.bt_import_ajax_url,
				data: data,
				async: true,
				success: function( response ) {
					response = $.trim( response );
					if ( response.endsWith( 'bt_import_end' ) ) {
						$( '.bt_import_report' ).html( '<b>Import finished!</b>' );
						$( '.bt_import_progress' ).hide();
					} else if ( response.startsWith( '<p><strong>Error' ) ) {
						window.bt_import_step_attempt++;
						$( '.bt_import_report' ).html( $( '.bt_import_report' ).html() + response );
						$( '.bt_import_progress' ).hide();
						//window.bt_import_ajax( data );
					} else {
						try {
							var json = JSON.parse( response );
							$( '.bt_import_progress span' ).html( json.progress );
							window.bt_import_reader_index = json.reader_index;
							window.bt_import_step++;
							window.bt_import_step_attempt = 1;
							data = {
								'action': 'bt_import_ajax',
								'file': window.bt_import_file,
								'step': window.bt_import_step,
								'reader_index': json.reader_index,
								'force_download': window.force_download,
								'_ajax_nonce': window.bt_import_ajax_nonce
							}
							window.bt_import_ajax( data );
						} catch( err ) {
							$( '.bt_import_report' ).html( $( '.bt_import_report' ).html() + err.message + ' ' + response );
							$( '.bt_import_progress' ).hide();
						}
					}
				},
				error: function( xhr, status, error ) {
					//console.log( status + ' ' + error );
					window.bt_import_step_attempt++;
					//$( '.bt_import_report' ).html( $( '.bt_import_report' ).html() + '<span style="color:red;">' + status + ' ' + error + '</span>' + '<br/>' );
                    if ( xhr.status == 404 || bt_import_step_attempt > 2 ) {
						window.bt_import_step++;
						window.bt_import_reader_index++;
						window.bt_import_step_attempt = 1;
            			data = {
            				'action': 'bt_import_ajax',
            				'file': window.bt_import_file,
            				'step': window.bt_import_step,
            				'reader_index': window.bt_import_reader_index,
            				'force_download': window.force_download,
            				'_ajax_nonce': window.bt_import_ajax_nonce
            			}
                    }
					window.bt_import_ajax( data );
				}
			});
		}
		
		
	});
	
})( jQuery );

String.prototype.endsWith = function(suffix) {
    return this.match(suffix+"$") == suffix;
};

if (!String.prototype.startsWith) {
  String.prototype.startsWith = function(searchString, position) {
    position = position || 0;
    return this.indexOf(searchString, position) === position;
  };
}