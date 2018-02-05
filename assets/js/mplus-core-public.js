(function($) {
    'use strict';

    jQuery(document).on('ready', function(){

	    $( 'form.mpss_intercom' ).submit(function(e){
	        e.preventDefault();

	        var data;

	        data = $( this ).serializeArray();
	        console.log( data );

	        $.ajax({
	            url : wp.ajaxurl,
	            data : data,
	            type: 'POST',
	            action: 'intercom_form_submit',
	            
	            beforeSend: function() {

	            },

	            success: function(data, textStatus, jqXHR) {

	            },

	            error: function(jqXHR, textStatus, errorThrown) {

	                console.log('The following error occured: ' + textStatus, errorThrown);
	            }
	        })

	    });


	});
	
})(jQuery);
