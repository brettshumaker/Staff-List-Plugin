(function ($) {
	'use strict';

	$(
		function () {

			// Fix broken table width on sort

			$( '.sslp-order td' ).each(
				function () {
					$( this ).css( 'width', $( this ).width() + 'px' );
				}
			);

			// Sortable Table

			if ($( '.sslp-order#sortable-table' ).length > 0) {
				$( '.sslp-order#sortable-table tbody' ).sortable(
					{
						axis: 'y',
						handle: '.column-order img',
						placeholder: 'ui-state-highlight',
						forcePlaceholderSize: true,
						update: function (event, ui) {
							var theOrder = $( this ).sortable( 'toArray' );

							var data = {
								action: 'staff_member_update_post_order',
								postType: $( this ).attr( 'data-post-type' ),
								order: theOrder,
								nonce: $( '#sslp-order' ).val()
							};

							$.post( ajaxurl, data );
						}
					}
				).disableSelection();
			}

			// Collapsible divs on templates page

			$( ".content" ).hide();
			$( ".heading" ).click(
				function () {
					$( this ).next( ".content" ).slideToggle( 500 );
				}
			);

		}
	);

	$( document ).ready(
		function() {

			$('#sslp-export-form').submit(function(e){
				e.preventDefault();

				$( 'input[type="submit"]' ).after( '<span class="spinner is-active" style="float:none"></span>' );

				var formData = new FormData( e.target );

				var data = {
					'action': 'staff_member_export',
					'security': formData.get( 'sslp_export_nonce' ),
				}

				$.post(
					ajaxurl, data, function( response ){
						$( 'input[type="submit"] + .spinner' ).fadeOut(
							300, function(){
								$( this ).remove();
							}
						);

						if ( response.success && response.data.created_file ) {
							window.location = response.data.url;
						} else if ( response.success && ! response.data.created_file ) {
							$( 'input[type="submit"]' ).hide().after( '<a class="button button-primary download-button" download="' + response.data.filename + '">Download</a>' );
							$( 'input[type="submit"]' ).remove();
							$( 'a.download-button' ).attr( 'href', "data:text/plain," + encodeURIComponent( response.data.content ) );
						} else if ( ! response.success ) {
							// Display the error message.
							alert( response.data );
						}

					}
				);
			}
			);
		}
	);

})( jQuery );
