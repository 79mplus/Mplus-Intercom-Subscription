(function($) {
    'use strict';

    jQuery(document).on('ready', function(){

	    $( 'form.mpss_intercom' ).submit(function(e){
	        e.preventDefault();

	        $.ajax({
	            url : wp.ajaxurl,
	            type: 'POST',
				data: {
                	action: 'intercom_form_submit',
                	fields: $( 'form.mpss_intercom' ).serializeArray()
            	};
	            
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
