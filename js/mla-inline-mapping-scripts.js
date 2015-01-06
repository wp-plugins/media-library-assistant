/* global ajaxurl */

var jQuery,
	mla_inline_mapping_vars,
	mla = {
		// Properties
		settings: {},
		bulkMap: {
			inProcess: false,
			doCancel: false
		},
	
		// Utility functions
		utility: {
		},
	
		// Components
		inlineMapAttachment: null
	};

( function( $ ) {
	/**
	 * Localized settings and strings
	 */
	mla.settings = typeof mla_inline_mapping_vars === 'undefined' ? {} : mla_inline_mapping_vars;
	mla_inline_mapping_vars = void 0; // delete won't work on Globals

	mla.inlineMapAttachment = {
		init : function(){
			var progressDiv = $( '#mla-progress-div' );

			$('#mla-progress-cancel', progressDiv).off( 'click' );
			$('#mla-progress-cancel', progressDiv).click(function(){
				if ( mla.bulkMap.inProcess ) {
					mla.bulkMap.doCancel = true;
					return false;
				} else {
					return mla.inlineMapAttachment.revert();
				}
			});

			// Clicking "Refresh" submits the form, refreshing the page
			$( '#mla-progress-refresh', progressDiv ).off( 'click' );
			$( '#mla-progress-refresh', progressDiv ).click(function(){
				$( '#mla-progress-refresh' ).prop( 'disabled', true );
				$( '#mla-progress-refresh' ).css( 'opacity', '0.5' );
			});

			$('#mla-progress-close', progressDiv).off( 'click' );
			$('#mla-progress-close', progressDiv).click(function( e ){
				if ( mla.bulkMap.inProcess ) {
					return false;
				}

				return mla.inlineMapAttachment.revert();
			});

			// add event handler to the Map All links
			$( 'input[type="submit"].mla-mapping' ).click(function( e ){
				e.preventDefault();
				return mla.inlineMapAttachment.bulkMap( e );
			return false;
			});
		},

		bulkMap : function( e ) {
			mla.bulkMap = {
				inProcess: false,
				doCancel: false,
				chunkSize: +mla.settings.bulkChunkSize,
				targetName: e.target.name,
				fields: $( mla.settings.fieldsId + ' :input').serialize(),
				offset: 0,
				waiting: +mla.settings.totalItems,
				running: 0,
				complete: 0,
				unchanged:0,
				success: 0,
				refresh: false
			};

			mla.inlineMapAttachment.progressOpen();
			mla.inlineMapAttachment.bulkPost();
			return false;
		},

		progressOpen : function(){
			this.revert();

			$( '#mla-progress-meter' ).css( 'width', '0%' );
			$( '#mla-progress-meter' ).html('0%');
			$( '#mla-progress-message' ).html('');
			$( '#mla-progress-error' ).html('');
			$( '#mla-progress-div' ).show();

			// Disable "Close" until the bulk mapping is complete
			$( '#mla-progress-cancel' ).prop( 'disabled', false ).css( 'opacity', '1.0' );
			$( '#mla-progress-refresh' ).hide();
			$( '#mla-progress-close' ).prop( 'disabled', true ).css( 'opacity', '0.5' ).show();
			$( 'html, body' ).animate( { scrollTop: 0 }, 'fast' );
		},

		bulkPost : function() {
			var params, chunk, statusMessage,
				spinner = $('#mla-progress-div p.inline-edit-save .spinner'),
				message = $( '#mla-progress-message' ),
				error = $( '#mla-progress-error' );

			// Find the number of items to process
			if ( mla.bulkMap.waiting < mla.bulkMap.chunkSize ) {
				chunk = mla.bulkMap.waiting;
			} else {
				chunk = mla.bulkMap.chunkSize;
			}
			
			mla.bulkMap.waiting -= chunk;
			mla.bulkMap.running = chunk;

			params = {
				page: mla.settings.page,
				mla_tab: mla.settings.mla_tab,
				screen: mla.settings.screen,
				action: mla.settings.ajax_action,
				nonce: mla.settings.ajax_nonce,
				bulk_action: mla.bulkMap.targetName,
				offset: mla.bulkMap.complete,
				length: chunk
			};

			params = $.param( params ) + '&' + mla.bulkMap.fields;
			
			// make ajax request
			mla.bulkMap.inProcess = true;
			
			percentComplete = Math.floor( ( 100 * mla.bulkMap.complete ) / mla.settings.totalItems ) + '%';
			$( '#mla-progress-meter' ).css( 'width', percentComplete );
			$( '#mla-progress-meter' ).html( percentComplete );

			spinner.show();
			statusMessage = mla.settings.bulkWaiting + ': ' + mla.bulkMap.waiting
				+ ', ' + mla.settings.bulkRunning + ': ' + mla.bulkMap.running
				+ ', ' + mla.settings.bulkComplete + ': ' + mla.bulkMap.complete
				+ ', ' + mla.settings.bulkUnchanged + ': ' + mla.bulkMap.unchanged
				+ ', ' + mla.settings.bulkSuccess + ': ' + mla.bulkMap.success;
			message.html( statusMessage ).show();
			
			$.ajax( ajaxurl, {
				type: 'POST',
				data: params,
				dataType: 'json'
			}).always( function() {
				spinner.hide();
			}).done( function( response, status ) {
					var responseData = 'no response.data', responseMessage;
					
					if ( response ) {
						if ( ! response.success ) {
							if ( response.responseData ) {
								responseData = response.data;
							}
							
							error.html( JSON.stringify( response ) );
							mla.bulkMap.waiting = 0; // Stop
						} else {
							if ( 0 == response.data.processed ) {
								// Something went wrong; we're done
								responseMessage = response.data.message;
								mla.bulkMap.waiting = 0; // Stop
							} else {
								// Move the items from Running to Complete
								mla.bulkMap.complete += response.data.processed;
								mla.bulkMap.running = 0;
								mla.bulkMap.unchanged += response.data.unchanged;
								mla.bulkMap.success += response.data.success;
								
								if ( 'undefined' !== typeof response.data.refresh ) {
									mla.bulkMap.refresh = response.data.refresh;
								}
								
								percentComplete = Math.floor( ( 100 * mla.bulkMap.complete ) / mla.settings.totalItems ) + '%';
								$( '#mla-progress-meter' ).css( 'width', percentComplete );
								$( '#mla-progress-meter' ).html( percentComplete );
	
								responseMessage = mla.settings.bulkWaiting + ': ' + mla.bulkMap.waiting
									+ ', ' + mla.settings.bulkComplete + ': ' + mla.bulkMap.complete
									+ ', ' + mla.settings.bulkUnchanged + ': ' + mla.bulkMap.unchanged
									+ ', ' + mla.settings.bulkSuccess + ': ' + mla.bulkMap.success;
							}
							message.html( responseMessage ).show();
						}
					} else {
						error.html( mla.settings.error );
						mla.bulkMap.waiting = 0; // Stop
					}
					
					if ( mla.bulkMap.doCancel ) {
						message.html( mla.settings.bulkCanceled + '. ' +  responseMessage ).show();
					} else {
						if ( mla.bulkMap.waiting ) {
							mla.inlineMapAttachment.bulkPost();
							return;
						}
					}

					if ( mla.bulkMap.refresh ) {
						$( '#mla-progress-close' ).hide();
						$( '#mla-progress-refresh' ).prop( 'disabled', false ).css( 'opacity', '1.0' ).show();
					} else {
						$( '#mla-progress-close' ).prop( 'disabled', false ).css( 'opacity', '1.0' );
					}
					
					$( '#mla-progress-cancel' ).prop( 'disabled', true ).css( 'opacity', '0.5' );
					mla.bulkMap.inProcess = false;
			}).fail( function( jqXHR, status ) {
				if ( 200 == jqXHR.status ) {
					error.html( '(' + status + ') ' + jqXHR.responseText );
				} else {
					error.html( mla.settings.ajaxFailError + ' (' + status + '), jqXHR( ' + jqXHR.status + ', ' + jqXHR.statusText + ', ' + jqXHR.responseText + ')' );
				}
			});
		},

		revert : function(){
			var progressDiv = $( '#mla-progress-div' );

			if ( progressDiv ) {
				$('p.inline-edit-save .spinner', progressDiv ).hide();

				// Reset Div content to initial values

				$( progressDiv ).hide();
			}

			return false;
		}
	}; // mla.inlineMapAttachment

	$( document ).ready( function() {
		mla.inlineMapAttachment.init();
	});
})( jQuery );
