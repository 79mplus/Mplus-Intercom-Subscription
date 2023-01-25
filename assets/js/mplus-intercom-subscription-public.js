( function( $ ) {
	'use strict';
	jQuery( document ).on( 'ready', function() {
		$( '.mplus-intercom-subscription-form form.mplus_intercom_subscription' ).submit( function( e ) {
			e.preventDefault();
			$.ajax( {
				url: wp.ajaxurl,
				type: 'POST',
				data: {
					action: 'mplus_intercom_subscription_form_submit',
					fields: $( '.mplus-intercom-subscription-form form.mplus_intercom_subscription' ).serializeArray()
				},
				beforeSend: function() {},
				success: function( data, textStatus, jqXHR ) {
					if ( data.success == 1 ) {
						$( ".mplus-intercom-subscription-form form.mplus_intercom_subscription" ).remove();
						$( '.mplus-intercom-subscription-form .message' ).empty().html( data.message ).show();
					} else {
						$( '.mplus-intercom-subscription-form .message' ).empty().html( data.message ).show();
						$( '.mplus-intercom-subscription-form .message' ).fadeIn().delay( 10000 ).fadeOut();
						console.log(data);
					}
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					console.log( 'The following error occured: ' + textStatus,
						errorThrown );
				}
			} )
		} );
	} );

	jQuery( document ).on( 'ready', function() {
		$( '.mplus-intercom-subscription-company-form form.mplus_intercom_subscription' ).submit( function( e ) {
			e.preventDefault();
			var fields = $( '.mplus-intercom-subscription-company-form form.mplus_intercom_subscription' ).serializeArray();
			$.ajax( {
				url: wp.ajaxurl,
				type: 'POST',
				data: {
					action: 'mplus_intercom_subscription_company_form_submit',
					fields: fields
				},
				beforeSend: function() {},
				success: function( data, textStatus, jqXHR ) {
					if ( data.success == 1 ) {
						$( ".mplus-intercom-subscription-company-form form.mplus_intercom_subscription" ).remove();
						$( '.mplus-intercom-subscription-company-form .message' ).empty().html( data.message ).show();
					} else {
						$( '.mplus-intercom-subscription-company-form .message' ).empty().html( data.message ).show();
						$( '.mplus-intercom-subscription-company-form .message' ).fadeIn().delay( 10000 ).fadeOut();
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
