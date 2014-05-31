/* global ajaxurl, mla  */

( function( $ ) {
	/**
	 * setParent displays a popup modal window with a post/page list
	 * from which a new parent can be selected.
	 * setParent.open is called from an "onclick" attribute in the submenu table links
	 */
	mla.setParent = {
		init: function() {
			// Send setParent selected parent
			$( '#mla-set-parent-submit' ).click( function( event ) {
				if ( ! $( '#mla-set-parent-response-div input[type="radio"]:checked' ).length )
					event.preventDefault();
			});
			
			// Send setParent parent keywords for filtering
			$( '#mla-set-parent-search' ).click( mla.setParent.send );
			$( '#mla-set-parent-search-div :input' ).keypress( function( event ) {
				if ( 13 == event.which ) {
					mla.setParent.send();
					return false;
				}
			});
			
			// Close the setParent pop-up
			$( '#mla-set-parent-close-div' ).click( mla.setParent.close );
			
			$( '#mla-set-parent-cancel' ).click( function ( event ) {
				event.preventDefault();
				return mla.setParent.close();
			});

			// Enable whole row to be clicked
			$( '#mla-set-parent-inside-div' ).on( 'click', 'tr', function() {
				$( this ).find( '.found-radio input' ).prop( 'checked', true );
			});
		},

		open: function( affectedParent, affectedChild, affectedTitles ) {
			var overlay = $( '#mla-set-parent-overlay' );

			if ( overlay.length === 0 ) {
				$( 'body' ).append( '<div id="mla-set-parent-overlay"></div>' );
				mla.setParent.overlay();
			}

			overlay.show();

			if ( affectedParent && affectedChild ) {
				$( '#mla-set-parent-parent' ).val( affectedParent );
				$( '#mla-set-parent-children' ).val( affectedChild );
			}

			if ( affectedTitles ) {
				$( '#mla-set-parent-titles' ).html( affectedTitles );
			}

			if ( mla.settings.useDashicons ) {
				$( '#mla-set-parent-close-div' ).addClass("mla-set-parent-close-div-dashicons");
			} else {
				$( '#mla-set-parent-close-div' ).html( 'x' );
			}
			
			$( '#mla-set-parent-div' ).show();

			$( '#mla-set-parent-input ' ).focus().keyup( function( event ){
				if ( event.which == 27 ) {
					mla.setParent.close();
				} // close on Escape
			});

			// Pull some results up by default
			mla.setParent.send();

			return false;
		},

		close: function() {
			$( '#mla-set-parent-response-div' ).html('');
			$( '#mla-set-parent-div' ).hide();
			$( '#mla-set-parent-overlay' ).hide();
		},

		overlay: function() {
			$( '#mla-set-parent-overlay' ).on( 'click', function () {
				mla.setParent.close();
			});
		},

		send: function() {
			var post = {
					ps: $( '#mla-set-parent-input' ).val(),
					action: 'find_posts',
					_ajax_nonce: $('#mla-set-parent-ajax-nonce').val()
				},
				spinner = $( '#mla-set-parent-search-div .spinner' ),
				ajaxResponse = null;

			spinner.show();

			$.ajax( ajaxurl, {
				type: 'POST',
				data: post,
				dataType: mla.settings.setParentDataType
			}).always( function() {
				spinner.hide();
			}).done( function( response ) {
				var responseData = 'no response.data', id = 0;
				
				if ( 'xml' === mla.settings.setParentDataType ) {
					if ( 'string' === typeof( response ) ) {
						response = { 'success': false, data: response };
					} else {
						ajaxResponse = wpAjax.parseAjaxResponse( response );
						
						if ( ajaxResponse.errors ) {
							response = { 'success': false, data: wpAjax.broken };
						} else {
							response = { 'success': true, data: ajaxResponse.responses[0].data };
						}
					}
				}
				
				if ( ! response.success ) {
					if ( response.responseData ) {
						responseData = response.data;
					}
						
					$( '#mla-set-parent-response-div' ).text( mla.settings.ajaxDoneError + ' (' + responseData + ')' );
				} else {
					/*
					 * Add the (Unattached) row, then the post/page list
					 */
					$( '#mla-set-parent-response-div' ).html( response.data );
					$( '#mla-set-parent-response-div table tbody tr:eq(0)' ).before( $( '#found-0-row' ).clone() );
					
					/*
					 * See if we can "check" the current parent
					 */
					id = $( '#mla-set-parent-parent' ).val();
					$( '#mla-set-parent-response-div #found-' + id ).each(function( index, element ){
						$( this ).prop( 'checked', true );
					});
				}
			}).fail( function( jqXHR, status ) {
				if ( 200 == jqXHR.status ) {
					$( '#mla-set-parent-response-div' ).text( '(' + status + ') ' + jqXHR.responseText );
				} else {
					$( '#mla-set-parent-response-div' ).text( mla.settings.ajaxFailError + ' (' + status + '), jqXHR( ' + jqXHR.status + ', ' + jqXHR.statusText + ', ' + jqXHR.responseText + ')' );
				}
			});
		}
	}; // mla.setParent

	$( document ).ready( function() {
		mla.setParent.init();
	});
})( jQuery );
