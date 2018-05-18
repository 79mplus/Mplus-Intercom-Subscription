( function( $ ) {
	'use strict';
	jQuery( document ).on( 'ready', function() {
		$( 'form.mplus_intercom_subscription' ).submit( function( e ) {
			e.preventDefault();
			$.ajax( {
				url: wp.ajaxurl,
				type: 'POST',
				data: {
					action: 'mplus_intercom_subscription_form_submit',
					fields: $( 'form.mplus_intercom_subscription' ).serializeArray()
				},
				beforeSend: function() {},
				success: function( data, textStatus, jqXHR ) {
					if ( data.success == 1 ) {
						$( "form.mplus_intercom_subscription" ).remove();
						$( '.message' ).show();
					} else {
						$( '.message' ).html( data.message ).show();
						$( '.message' ).fadeIn().delay( 10000 ).fadeOut();
					}
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					console.log( 'The following error occured: ' + textStatus,
						errorThrown );
				}
			} )
		} );
	} );
} )( jQuery );
