(function ($) {
    'use strict';


    $(function () {

        // Fix broken table width on sort

        $('.sslp-order td').each(function () {
            $(this).css('width', $(this).width() + 'px');
        });


        // Sortable Table

        if ($('.sslp-order#sortable-table').length > 0) {
            $('.sslp-order#sortable-table tbody').sortable({
                axis: 'y',
                handle: '.column-order img',
                placeholder: 'ui-state-highlight',
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    var theOrder = $(this).sortable('toArray');

                    var data = {
                        action: 'staff_member_update_post_order',
                        postType: $(this).attr('data-post-type'),
                        order: theOrder
                    };

                    $.post(ajaxurl, data);
                }
            }).disableSelection();
        }


        // Collapsible divs on templates page

        $(".content").hide();
        $(".heading").click(function () {
            $(this).next(".content").slideToggle(500);
        });

    });
    
    $(document).ready(function() {
		
		// Export button
		$('a.export-button').on( 'click', function(e){
			e.preventDefault();
			$('a.export-button').after('<span class="spinner is-active" style="float:none"></span>');
			
			var data = {
				'action': 'staff_member_export',
			};
			
			$.post( ajaxurl, data, function( response ){
				
				if ( response.success && response.data.created_file ) {
					$('a.export-button + .spinner').fadeOut(300, function(){
						$(this).remove();
					});
					window.location = response.data.url;
				} else if ( response.success && ! response.data.created_file ) {
					$('a.export-button + .spinner').fadeOut(300, function(){
						$(this).remove();
					});
					$('a.export-button').hide().after('<a class="button button-primary download-button" download="' + response.data.filename + '">Download</a>');
					$('a.export-button').remove();
					$('a.download-button').attr( 'href', "data:text/plain," + encodeURIComponent( response.data.content ) );
				}
				
			});
			
		});
		
	});
    

})(jQuery);