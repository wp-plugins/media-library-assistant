(function($){
	var media = wp.media, mlaStrings = {},  mlaSettings = {}, originalMediaAjax = null;
	
/*	for debug : trace every event triggered in the MediaFrame controller * /
	var originalMediaFrameTrigger = wp.media.view.MediaFrame.prototype.trigger;
	wp.media.view.MediaFrame.prototype.trigger = function(){
		console.log('MediaFrame Event: ', arguments[0]);
		originalMediaFrameTrigger.apply(this, Array.prototype.slice.call(arguments));
	} // */
	
/*	for debug : trace every event triggered in the view.Attachment controller * /
	var originalAttachmentTrigger = wp.media.view.Attachment.prototype.trigger;
	wp.media.view.Attachment.prototype.trigger = function(){
		console.log('view.Attachment Event: ', arguments[0]);
		originalAttachmentTrigger.apply(this, Array.prototype.slice.call(arguments));
	} // */
	
/*	for debug : trace every event triggered in the model.Attachment controller * /
	var originalModelAttachmentTrigger = wp.media.model.Attachment.prototype.trigger;
	wp.media.model.Attachment.prototype.trigger = function(){
		console.log('model.Attachment Event: ', arguments[0]);
		originalModelAttachmentTrigger.apply(this, Array.prototype.slice.call(arguments));
	} // */
	
/*	for debug : trace every event triggered in the view.AttachmentCompat controller * /
	var originalAttachmentCompatTrigger = wp.media.view.AttachmentCompat.prototype.trigger;
	wp.media.view.AttachmentCompat.prototype.trigger = function(){
		console.log('view.AttachmentCompat Event: ', arguments[0]);

		originalAttachmentCompatTrigger.apply(this, Array.prototype.slice.call(arguments));
	} // */
	
/*	for debug : trace every event triggered in the model.Selection controller * /
	var originalModelSelectionTrigger = wp.media.model.Selection.prototype.trigger;
	wp.media.model.Selection.prototype.trigger = function(){
		console.log('model.Selection Event: ', arguments[0]);

		originalModelSelectionTrigger.apply(this, Array.prototype.slice.call(arguments));
	} // */
	
/*	for debug : trace every invocation of the media.post method * /
	var originalMediaPost = media.post;
	media.post = function( action, data ) {
		console.log('media.post action: ', action );
		console.log('media.post data: ', JSON.stringify( data ) );
		
		return originalMediaPost.apply(this, Array.prototype.slice.call(arguments));
	}; // */
	
/*	for debug : trace every invocation of the wp.ajax.send function * /
	var originalWpAjaxSend = wp.ajax.send;
	wp.ajax.send = function( action, data ) {
		console.log('wp.ajax.send action: ', JSON.stringify( action ) );
		console.log('wp.ajax.send data: ', JSON.stringify( data ) );
		
		return originalWpAjaxSend.apply(this, Array.prototype.slice.call(arguments));
	}; // */

	/*
	 * Parse outgoing Ajax requests, look for the 'query-attachments' action and stuff
	 * our arguments into the "s" field because MMMW only monitors that one field.
	 */
	originalMediaAjax = media.ajax;
	media.ajax = function( action, options ) {
		if ( _.isObject( action ) ) {
			options = action;
		} else {
//			console.log('media.ajax action: ', JSON.stringify( action ) );
			options = options || {};
			options.data = _.extend( options.data || {}, { action: action });
		}

//		console.log('media.ajax original options: ', JSON.stringify( options ) );
		
		if ( 'query-attachments' == options.data.action ) {
	
			stype = typeof options.data.query.s;
			if ( 'object' == stype )
				s = options.data.query.s;
			else if ( 'string' == stype )
					s = { 'mla_search_value': options.data.query.s };
				else
					s = {};
	
			if ( 'undefined' != typeof s.mla_filter_month )
				mlaSettings.filterMonth = s.mla_filter_month;
				
			if ( 'undefined' != typeof s.mla_filter_term )
				mlaSettings.filterTerm = s.mla_filter_term;
				
			if ( 'undefined' != typeof s.mla_search_value )
				mlaSettings.searchValue = s.mla_search_value;
				
			searchValues = {
				'mla_filter_month': mlaSettings.filterMonth,
				'mla_filter_term': mlaSettings.filterTerm,
				'mla_search_value': mlaSettings.searchValue,
				'mla_search_fields': mlaSettings.searchFields,
				'mla_search_connector': mlaSettings.searchConnector };
				
			options.data.query.s = searchValues;
		}
		
//		console.log('media.ajax final options: ', JSON.stringify( options ) );
		return originalMediaAjax.call(this, options );
	};
	
	/**
	 * Localized settings and strings
	 */
	mlaStrings = typeof media.view.l10n.mla_strings === 'undefined' ? {} : media.view.l10n.mla_strings;
	// delete media.view.l10n.mla_strings;
	
	mlaSettings = typeof wp.media.view.settings.mla_settings === 'undefined' ? {} : wp.media.view.settings.mla_settings;
	// delete wp.media.view.settings.mla_settings;
	
	/**
	 * Extended Filters dropdown with more mimeTypes
	 */
	if ( mlaSettings.enableMimeTypes ) {
		media.view.AttachmentFilters.Mla = media.view.AttachmentFilters.extend({
			createFilters: function() {
				var filters = {};
	
				_.each( mlaSettings.mimeTypes || {}, function( text, key ) {
					filters[ key ] = {
						text: text,
						props: {
							type:    key,
							uploadedTo: null,
							orderby: 'date',
							order:   'DESC'
						}
					};
				});
	
				filters.all = {
					text:  media.view.l10n.allMediaItems,
					props: {
						type:    null,
						uploadedTo: null,
						orderby: 'date',
						order:   'DESC'
					},
					priority: 10
				};
	
				filters.uploaded = {
					text:  media.view.l10n.uploadedToThisPost,
					props: {
						type:    null,
						uploadedTo: media.view.settings.post.id,
						orderby: 'menuOrder',
						order:   'ASC'
					},
					priority: 20
				};
	
				this.filters = filters;
			}
		});
	};

	/**
	 * Extended Filters dropdown with month and year selection values
	 */
	if ( mlaSettings.enableMonthsDropdown ) {
		media.view.AttachmentFilters.MlaMonths = media.view.AttachmentFilters.extend({
			className: 'attachment-months',
	
			createFilters: function() {
				var filters = {};
	
				_.each( mlaSettings.months || {}, function( text, key ) {
					filters[ key ] = {
						text: text,
						props: { s: { 'mla_filter_month': key }	}
					};
				});
	
				this.filters = filters;
			},
			
			select: function() {
				var model = this.model,
					value = mlaSettings.filterMonth,
					props = model.toJSON();
					
				if ( _.isUndefined( props.s ) )
					props.s = {};
					
				if ( 'string' == typeof props.search )
					mlaSettings.searchValue = props.search;
					
				if (_.isUndefined( props.s.mla_filter_month ) )
					props.s.mla_filter_month = mlaSettings.filterMonth;
				else
					mlaSettings.filterMonth =  props.s.mla_filter_month;
					
				_.find( this.filters, function( filter, id ) {
						var equal = _.all( filter.props, function( prop, key ) {
							return prop.mla_filter_month == mlaSettings.filterMonth;
						});
		
					if ( equal )
						return value = id;
				});
	
				this.$el.val( value );
			}	});
	};
	
	/**
	 * Extended Filters dropdown with taxonomy term selection values
	 */
	if ( mlaSettings.enableTermsDropdown ) {
		media.view.AttachmentFilters.MlaTerms = media.view.AttachmentFilters.extend({
			className: 'attachment-terms',
	
			createFilters: function() {
				var filters = {};
	
				_.each( mlaSettings.termsText || {}, function( text, key ) {
					filters[ key ] = {
						text: text,
						props: { s: { 'mla_filter_term': parseInt( mlaSettings.termsValue[ key ] ) } }
					};
				});
	
				this.filters = filters;
			},
			
			select: function() {
				var model = this.model,
					value = mlaSettings.filterTerm,
					props = model.toJSON();
					
				if ( _.isUndefined( props.s ) )
					props.s = {};
	
				if ( 'string' == typeof props.search )
					mlaSettings.searchValue = props.search;
					
				if (_.isUndefined( props.s.mla_filter_term ) )
					props.s.mla_filter_term = mlaSettings.filterTerm;
				else
					mlaSettings.filterTerm =  props.s.mla_filter_term;
						
				_.find( this.filters, function( filter, id ) {
					var equal = _.all( filter.props, function( prop, key ) {
						return prop.mla_filter_term == mlaSettings.filterTerm;
					});
	
					if ( equal )
						return value = id;
				});
	
				this.$el.val( value );
			}	});
	};
	
	/**
	 * Extended wp.media.view.Search
	 */
	if ( mlaSettings.enableSearchBox ) {
		media.view.MlaSearch = media.View.extend({
			tagName:   'div',
			className: 'mla-search-box',
			template: media.template('mla-search-box'),
	
			attributes: {
				type: 'mla-search-box'
			},
	
			events: {
				'change': 'search',
				'click': 'search',
				'search': 'search',
				'MlaSearch': 'search'
			},
	
			render: function() {
				this.$el.html( this.template( mlaStrings ) );
				return this;
			},
	
			search: function( event ) {
				if ( ( 'click' == event.type ) && ( 'mla_search_submit' != event.target.name ) ) {
					return;
				}
					
				switch ( event.target.name ) {
					case 's[mla_search_value]':
						mlaSettings.searchValue = event.target.value;
					case 'mla_search_submit':
						searchValues = {
							'mla_filter_month': mlaSettings.filterMonth,
							'mla_filter_term': mlaSettings.filterTerm,
							'mla_search_value': mlaSettings.searchValue,
							'mla_search_fields': mlaSettings.searchFields,
							'mla_search_connector': mlaSettings.searchConnector };
						this.model.set({ 's': searchValues });
					break;
					case 's[mla_search_connector]':
						mlaSettings.searchConnector = event.target.value;
					break;
					case 's[mla_search_title]':
					index = mlaSettings.searchFields.indexOf( 'title' );
					if ( -1 == index )
						mlaSettings.searchFields.push( 'title' )
					else
						mlaSettings.searchFields.splice( index, 1 );
					break;
					case 's[mla_search_name]':
					index = mlaSettings.searchFields.indexOf( 'name' );
					if ( -1 == index )
						mlaSettings.searchFields.push( 'name' )
					else
						mlaSettings.searchFields.splice( index, 1 );
					break;
					case 's[mla_search_alt_text]':
					index = mlaSettings.searchFields.indexOf( 'alt-text' );
					if ( -1 == index )
						mlaSettings.searchFields.push( 'alt-text' )
					else
						mlaSettings.searchFields.splice( index, 1 );
					break;
					case 's[mla_search_excerpt]':
					index = mlaSettings.searchFields.indexOf( 'excerpt' );
					if ( -1 == index )
						mlaSettings.searchFields.push( 'excerpt' )
					else
						mlaSettings.searchFields.splice( index, 1 );
					break;
					case 's[mla_search_content]':
					index = mlaSettings.searchFields.indexOf( 'content' );
					if ( -1 == index )
						mlaSettings.searchFields.push( 'content' )
					else
						mlaSettings.searchFields.splice( index, 1 );
					break;
				}
			}
		});
	};
	
	/**
	 * Add/replace media-toolbar controls with our own
	 */
	if ( mlaSettings.enableMimeTypes || mlaSettings.enableMonthsDropdown || mlaSettings.enableTermsDropdown || mlaSettings.enableSearchBox ) {
		wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
			createToolbar: function() {
				var filters;

				// Apply the original method to create the toolbar
				wp.media.view.AttachmentsBrowser.__super__.createToolbar.apply(this,arguments);

				filters = this.options.filters;
				
				if ( ( 'all' === filters ) && mlaSettings.enableMimeTypes ) {
					this.toolbar.unset( 'filters', { silent: true } );
					this.toolbar.set( 'filters', new media.view.AttachmentFilters.Mla({
						controller: this.controller,
						model:      this.collection.props,
						priority:   -80
					}).render() );
				}
	
				if ( filters && mlaSettings.enableMonthsDropdown ) {
					this.toolbar.set( 'months', new media.view.AttachmentFilters.MlaMonths({
						controller: this.controller,
						model:      this.collection.props,
						priority:   -80
					}).render() );
				}
	
				if ( filters && mlaSettings.enableTermsDropdown ) {
					this.toolbar.set( 'terms', new media.view.AttachmentFilters.MlaTerms({
						controller: this.controller,
						model:      this.collection.props,
						priority:   -80
					}).render() );
				}

				if ( this.options.search ) {
					if ( mlaSettings.enableSearchBox ) {
						this.toolbar.unset( 'search', { silent: true } );
						this.toolbar.set( 'MlaSearch', new media.view.MlaSearch({
							controller: this.controller,
							model:      this.collection.props,
							priority:   60
						}).render() );
					}
				}
	
				if ( this.options.dragInfo ) {
					this.toolbar.set( 'dragInfo', new media.View({
						el: $( '<div class="instructions">' +  media.view.l10n.dragInfo + '</div>' )[0],
						priority: -40
					}) );
				}
			}
		});
	}; // one or more MLA options enabled
}(jQuery));

var mla = {
	// Properties
	strings: {},
	settings: {},
	initialHTML: {},
	uploading: false,
	cid: null,

	// Utility functions
	utility: {
		arrayCleanup: null,
		parseTaxonomyId: null,
		hookCompatTaxonomies: null,
		fillCompatTaxonomies: null,
		supportCompatTaxonomies: null
	},

	// Components
	tagBox: null
};

(function($){
	/**
	 * Localized settings and strings
	 */
	mla.strings = typeof wp.media.view.l10n.mla_strings === 'undefined' ? {} : wp.media.view.l10n.mla_strings;
	//delete media.view.l10n.mla_strings;
	
	mla.settings = typeof wp.media.view.settings.mla_settings === 'undefined' ? {} : wp.media.view.settings.mla_settings;
	//delete wp.media.view.settings.mla_settings;
	
	/**
	 * return a sorted array with any duplicate, whitespace or values removed
	 * Adapted from /wp-admin/js/post.js
	 */
	mla.utility.arrayCleanup = function ( arrayIn ) {
		var arrayOut = [], isString = ( 'string' === typeof arrayIn );
		
		if( isString ) {
			arrayIn = arrayIn.split( postL10n.comma );
		}
		
		jQuery.each( arrayIn, function( key, val ) {
			val = jQuery.trim( val );

			if ( val && jQuery.inArray( val, arrayOut ) == -1 ) {
				arrayOut.push( val );
			}

		});

		arrayOut.sort();

		if( isString ) {
			arrayOut = arrayOut.join( postL10n.comma );
		}
		
		return arrayOut;
	};

	/**
	 * Extract the taxonomy name from an HTML id attribute,
	 * removing the 'mla-' and 'taxonomy-' prefixes.
	 */
	mla.utility.parseTaxonomyId = function ( id ) {
		var taxonomyParts = id.split( '-' );
		
		taxonomyParts.shift(); // 'mla-'
		taxonomyParts.shift(); // 'taxonomy-'
		return taxonomyParts.join('-');
	};

	/**
	 * Support functions for flat taxonomies, e.g. Tags, Att. Tags
	 */
	mla.tagBox = {
		/**
		 * Remove duplicate commas and whitespace from a string containing a tag list
		 */
		cleanTags : function( tags ) {
			var comma = postL10n.comma;
			if ( ',' !== comma ) {
				tags = tags.replace( new RegExp( comma, 'g' ), ',' );
			}
			
			tags = tags.replace( /\s*,\s*/g, ',' ).replace( /,+/g, ',' ).replace( /[,\s]+$/, '' ).replace( /^[,\s]+/, '' );
			
			if ( ',' !== comma ) {
				tags = tags.replace( /,/g, comma );
			}
			
			return tags;
		},

		/**
		 * Remove a tag from the list when the "X" button is clicked
		 */
		parseTags : function( el ) {
			var id = el.id, num = id.split( '-check-num-' )[1],
				tagsDiv = $( el ).closest( '.tagsdiv' ),
				thetags = tagsDiv.find( '.the-tags' ), comma = postL10n.comma,
				current_tags = thetags.val().split( comma ), new_tags = [];
				
			delete current_tags[ num ];
	
			$.each( current_tags, function( key, val ) {
				val = $.trim( val );
				if ( val ) {
					new_tags.push( val );
				}
			});
	
			thetags.val( this.cleanTags( new_tags.join( comma ) ) );
	
			this.quickClicks( tagsDiv );
			return false;
		},

		/**
		 * Build or rebuild the current tag list prefaced with "X" buttons,
		 * using the hidden '.the-tags' textbox field as input
		 */
		quickClicks : function( el ) {
			var thetags = $( '.the-tags', el ),
				tagchecklist = $( '.tagchecklist', el ),
				id = $( el ).attr( 'id' ),
				current_tags, disabled;
	
			if ( !thetags.length ) {
				return;
			}
	
			disabled = thetags.prop( 'disabled' );
	
			current_tags = thetags.val().split( postL10n.comma );
			tagchecklist.empty();
	
			$.each( current_tags, function( key, val ) {
				var span, xbutton;
	
				val = $.trim( val );
	
				if ( ! val ) {
					return;
				}
	
				// Create a new span, and ensure the text is properly escaped.
				span = $( '<span />' ).text( val );
	
				// If tags editing isn't disabled, create the X button.
				if ( ! disabled ) {
					xbutton = $( '<a id="' + id + '-check-num-' + key + '" class="ntdelbutton">X</a>' );
					xbutton.click( function(){ mla.tagBox.parseTags( this ); });
					span.prepend( '&nbsp;' ).prepend( xbutton );
				}
	
				// Append the span to the tag list.
				tagchecklist.append( span );
			});
		},

		/**
		 * Add one or more tags from the 'input.newtag' text field or from the "a" element
		 */
		flushTags : function( tagsDiv, a, f ) {
			var tagsval, newtags, text,
				tags = $( '.the-tags', tagsDiv ),
				newtag = $( 'input.newtag', tagsDiv ),
				comma = postL10n.comma;
				
			a = a || false;
	
			text = a ? $( a ).text() : newtag.val();
			tagsval = tags.val();
			newtags = tagsval ? tagsval + comma + text : text;
	
			newtags = mla.utility.arrayCleanup( this.cleanTags( newtags ) );
			tags.val( newtags );
			this.quickClicks( tagsDiv );
	
			if ( !a ) {
				newtag.val( '' );
			}
			
			if ( 'undefined' == typeof( f ) ) {
				newtag.focus();
			}
	
			return false;
		},

		/**
		 * Retrieve the tag cloud for this taxonomy
		 */
		getCloud : function( id, taxonomy ) {
			$.post( ajaxurl, {'action':'get-tagcloud', 'tax':taxonomy}, function( r, stat ) {
				if ( 0 === r || 'success' != stat ) {
					r = wpAjax.broken;
				}
	
				r = $( '<p id="tagcloud-'+taxonomy+'" class="the-tagcloud">'+r+'</p>' );
				$( 'a', r ).click( function(){
					mla.tagBox.flushTags( $( this ).closest( '.mla-taxonomy-field' ).children( '.tagsdiv' ), this );
					return false;
				});
	
				$( '#'+id ).after( r );
			});
		},
	
		init : function( attachmentId, taxonomy, context ) {
			var tagsDiv, ajaxTag;
			tagsDiv = $( '#mla-taxonomy-' + taxonomy, context );
			ajaxTag = $( 'div.ajaxtag', tagsDiv );
	
			mla.tagBox.quickClicks( tagsDiv );
	
			$( 'input.tagadd', ajaxTag ).click(function(){
				mla.tagBox.flushTags( $(this).closest( '.tagsdiv' ) );
			});
	
			$( 'input.newtag', ajaxTag ).keyup( function( e ){
				if ( 13 == e.which ) {
					mla.tagBox.flushTags( tagsDiv );
					return false;
				}
			}).keypress( function( e ){
				if ( 13 == e.which ) {
					e.preventDefault();
					return false;
				}
			}).each( function(){
				$( this ).suggest( ajaxurl + '?action=ajax-tag-search&tax=' + taxonomy, { delay: 500, resultsClass: 'mla_ac_results', selectClass: 'mla_ac_over', matchClass: 'mla_ac_match', minchars: 2, multiple: true, multipleSep: postL10n.comma + ' ' } );
			});
	
			// get the tag cloud on first click, then toggle visibility
			tagsDiv.siblings( ':first' ).click( function(){
				mla.tagBox.getCloud( $( 'a', this ).attr( 'id' ), taxonomy );
				$( 'a', this ).unbind().click( function(){
					$( this ).siblings( '.the-tagcloud' ).toggle();
					return false;
				});
				return false;
			});
			
			// Update the taxonomy terms, if changed, on the server when the mouse leaves the tagsdiv area
			$( '.compat-field-' + taxonomy + ' td', context ).on( "mouseleave", function( event ) {
				var query, tableData = this,
					oldTerms = mla.utility.arrayCleanup( $( '.server-tags', tableData ).val() ),
					termList = mla.utility.arrayCleanup( $( '.the-tags', tableData ).val() );
				
				if ( oldTerms === termList ) {
					return;
				}
				
				$( tableData ).css( 'opacity', '0.5' );

				/**
				 * wp.ajax.send( [action], [options] )
				 */
				query = {
					id: attachmentId,
					//_wpnonce:     settings.post.nonce
				};
				query[ taxonomy ] = termList;
				
				wp.media.post( mla.settings.ajaxUpdateCompatAction, query ).done( function( results ) {
						var taxonomy, list;
					
					for ( taxonomy in results ) {
						for ( list in results[ taxonomy ] ) {
							$( "#" + list, tableData ).replaceWith( results[ taxonomy ][ list ] );
						}
					}
		
					$( tableData ).css( 'opacity', '1.0' );
				});
			});

			// Don't let changes propogate to the Backbone model
			tagsDiv.on( 'change', function( event ) {
				event.stopPropagation();
				return false;
			});

			$( '.the-tags, .server-tags .newtag', tagsDiv ).on( 'change', function( event ) {
				event.stopPropagation();
				return false;
			});
		}
	}; // mla.tagBox

	/*
	 * We can extend the AttachmentCompat object because it's not instantiated until
	 * the sidebar is created for a selected attachment.
	 */
	if ( mla.settings.enableDetailsCategory || mla.settings.enableDetailsTag ) {
		wp.media.view.AttachmentCompat = wp.media.view.AttachmentCompat.extend({
			initialize: function() {
				// Call the base method in the super class
				wp.media.view.AttachmentCompat.__super__.initialize.apply( this, arguments );
				
				// Hook the 'ready' event when the sidebar has been rendered so we can add our enhancements
				this.on( 'ready', function( event ) {
					//console.log( 'view.AttachmentCompat ready Event: ', this.model.get('id') );
					mla.utility.hookCompatTaxonomies( this.model.get('id'), this.el );
				});
			}
		});
	}

	/*
	 * We can extend the model.Selection object because it's not instantiated until
	 * the sidebar is created for a selected attachment.
	 */
	if ( mla.settings.enableDetailsCategory || mla.settings.enableDetailsTag ) {
		wp.media.model.Selection = wp.media.model.Selection.extend({
			initialize: function() {
				// Call the base method in the super class
				wp.media.model.Selection.__super__.initialize.apply( this, arguments );
				
				// Hook the 'selection:reset' event so we can add our enhancements when it's done
				this.on( 'selection:reset', function( model ) {
					//console.log( 'model.Selection selection:reset Event: cid ', model.cid, ', id ', model.get('id') );
					mla.cid = null;
				});
	
				// Hook the 'selection:unsingle' event so we can add our enhancements when it's done
				this.on( 'selection:unsingle', function( model ) {
					//console.log( 'model.Selection selection:unsingle Event: cid ', model.cid, ', id ', model.get('id') );
					mla.cid = null;
				});
	
				// Hook the 'selection:single' event so we can add our enhancements when it's done
				this.on( 'selection:single', function( model ) {
					//console.log( 'model.Selection selection:single Event: cid ', model.cid, ', id ', model.get('id') );
					mla.cid = model.cid;
				});
	
				// Hook the 'change:uploading' event so we can add our enhancements when it's done
				this.on( 'change:uploading', function( model ) {
					//console.log( 'model.Selection change:uploading Event: cid ', model.cid, ', id ', model.get('id') );
					mla.uploading = true;
				});
	
				// Hook the 'change' event when the sidebar has been rendered so we can add our enhancements
				this.on( 'change', function( model ) {
					//console.log( 'model.Selection change Event: cid ', model.cid, ', id ', model.get('id') );
					
					if ( mla.uploading && mla.cid === model.cid ) {
						var mediaFrame = wp.media.editor.get('content'),
						compat = mediaFrame.content.get('compat');
						mla.utility.hookCompatTaxonomies( model.get('id'), compat.sidebar.$el );
						mla.uploading = false;
					}
				});
			}
		});
	}
	
	/**
	 * Install the "click to expand" handler for MLA Searchable Taxonomy Meta Boxes
	 */
	mla.utility.hookCompatTaxonomies = function( attachmentId, context ) {
		var taxonomy;

//		console.log( 'hookCompatTaxonomies attachmentId: ', attachmentId );
//		console.log( 'hookCompatTaxonomies context: ', JSON.stringify( context ) );
		
		if ( mla.settings.enableDetailsCategory ) {
			$('.mla-taxonomy-field .categorydiv', context ).each( function(){
				taxonomy = mla.utility.parseTaxonomyId( $(this).attr('id') );
	
				// Load the taxonomy checklists on first expansion
				$( '.compat-field-' + taxonomy + ' th', context ).click( { id: attachmentId, currentTaxonomy: taxonomy, el: context }, function( event ) {
					mla.utility.fillCompatTaxonomies( event.data );
				});
			});
		} // enableDetailsCategory

		if ( mla.settings.enableDetailsTag ) {
			$('.mla-taxonomy-field .tagsdiv', context ).each( function(){
				taxonomy = mla.utility.parseTaxonomyId( $(this).attr('id') );
	
				// Load the taxonomy checklists on first expansion
				$( '.compat-field-' + taxonomy + ' th', context ).click( { id: attachmentId, currentTaxonomy: taxonomy, el: context }, function( event ) {
					mla.utility.fillCompatTaxonomies( event.data );
				});
			});
		} // enableDetailsTag
	};
	
	/**
	 * Replace the "Loading..." placeholders with the MLA Searchable Taxonomy Meta Boxes
	 */
	mla.utility.fillCompatTaxonomies = function( data ) {
		var context = data.el, query = [], taxonomy, fieldClass;
		
		if ( mla.settings.enableDetailsCategory ) {
			$('.mla-taxonomy-field .categorydiv', context ).each( function(){
				taxonomy = mla.utility.parseTaxonomyId( $(this).attr('id') );
				query[ query.length ] = taxonomy;
				fieldClass = '.compat-field-' + taxonomy;

				// Save the initial markup for when we change attachments
				if ( "undefined" === typeof( mla.initialHTML[ taxonomy ] ) ) {
					mla.initialHTML[ taxonomy ] = $( fieldClass, context ).html();
				} else {
					$( fieldClass, context ).html( mla.initialHTML[ taxonomy ] );
				}
	
				$( fieldClass + ' .categorydiv', context ).html( mla.strings.loadingText );
			});
		} // mla.settings.enableDetailsCategory

		if ( mla.settings.enableDetailsTag ) {
			$( '.mla-taxonomy-field .tagsdiv', context ).each( function(){
				taxonomy = mla.utility.parseTaxonomyId( $(this).attr('id') );
				query[ query.length ] = taxonomy;
				fieldClass = '.compat-field-' + taxonomy;
	
				if ( "undefined" === typeof( mla.initialHTML[ taxonomy ] ) ) {
					mla.initialHTML[ taxonomy ] = $( fieldClass, context ).html();
				} else {
					$( fieldClass, context ).html( mla.initialHTML[ taxonomy ] );
				}
	
				$( fieldClass + ' .tagsdiv', context ).html( mla.strings.loadingText );
			});
		} // mla.settings.enableDetailsTag


		if ( query.length ) {
			/**
			 * wp.ajax.send( [action], [options] )
			 *
			 * Sends a POST request to WordPress.
			 *
			 * @param  {string} action  The slug of the action to fire in WordPress.
			 * @param  {object} options The options passed to jQuery.ajax.
			 * @return {$.promise}      A jQuery promise that represents the request.
			 */
			wp.media.post( mla.settings.ajaxFillCompatAction, {
				// json: true,
				id: data.id,
				query: query,
				//_wpnonce:     settings.post.nonce
			}).done( function( results ) {
				var taxonomy, fieldClass;
				
				for ( taxonomy in results ) {
					fieldClass = '.compat-field-' + taxonomy;
					
					$( fieldClass, context ).html( results[ taxonomy ] );
				}
	
				mla.utility.supportCompatTaxonomies( data );
				$( '.compat-field-' + data.currentTaxonomy + ' td', context ).show();
			});
		} // query.length
	};
	
	/**
	 * Support the MLA Searchable Taxonomy Meta Boxes
	 */
	mla.utility.supportCompatTaxonomies = function( data ) {
		var attachmentId = data.id, context = data.el;
		
		if ( mla.settings.enableDetailsCategory ) {
			$( '.mla-taxonomy-field .categorydiv', context ).each( function(){
				var thisJQuery = $(this), catAddBefore, catAddAfter, taxonomy, settingName,
					taxonomyIdPrefix, taxonomyNewIdSelector, taxonomySearchIdSelector, taxonomyTermsId;
		
				taxonomy = mla.utility.parseTaxonomyId( $(this).attr('id') );
				settingName = taxonomy + '_tab';
				taxonomyIdPrefix = '#mla-' + taxonomy;
				taxonomyNewIdSelector = '#mla-new-' + taxonomy;
				taxonomySearchIdSelector = '#mla-search-' + taxonomy;
				taxonomyTermsId = '#mla-attachments-' + attachmentId + '-' + taxonomy;
				
				if ( taxonomy == 'category' ) {
					settingName = 'cats';
				}
	
				// override "Media Categories" style sheet
				thisJQuery.find( '.category-tabs' ).show();
	
				// Expand/collapse the meta box contents
				$( '.compat-field-' + taxonomy + ' th', context ).click( function() {
					$(this).siblings( 'td' ).slideToggle();
				});
	
				// Update the taxonomy terms, if changed, on the server when the mouse leaves the checklist area
				thisJQuery.on( "mouseleave", function( event ) {
					var query, oldTerms, termList = [], checked =  thisJQuery.find( taxonomyIdPrefix + '-checklist input:checked' );
					
					checked.each( function( index ) {
						termList[ termList.length ] = $(this).val();
					});
					
					termList.sort( function( a, b ) { return a - b; } );
					termList = termList.join( ',' );
	
					oldTerms = thisJQuery.siblings( taxonomyTermsId ).val();
					if ( oldTerms === termList ) {
						return;
					}
					
					thisJQuery.siblings( taxonomyTermsId ).val( termList );
					thisJQuery.prop( 'disabled', true );
	
					/**
					 * wp.ajax.send( [action], [options] )
					 */
					query = {
						id: attachmentId,
						//_wpnonce:     settings.post.nonce
					};
					query[ taxonomy ] = termList;
					
					wp.media.post( mla.settings.ajaxUpdateCompatAction, 
						query ).done( function( results ) {
						var taxonomy, list;

						for ( taxonomy in results ) {
							for ( list in results[ taxonomy ] ) {
								thisJQuery.find( "#" + list ).html( results[ taxonomy ][ list ] );
							}
						}
			
					thisJQuery.find( taxonomySearchIdSelector ).val( '' );
					thisJQuery.find( taxonomyIdPrefix + '-searcher' ).addClass( 'mla-hidden-children' );
					thisJQuery.prop( 'disabled', false );
					});
				});
	
				// Don't let checkbox changes propogate to the Backbone model
				thisJQuery.on( 'change input[type="checkbox"]', function( event ) {
					event.stopPropagation();
					return false;
				});
	
				/*
				 * Taxonomy meta box code from /wp-admin/js/post.js
				 */
	
				// Switch between "All ..." and "Most Used"
				thisJQuery.find( taxonomyIdPrefix + '-tabs a' ).click( function(){
					var t = $(this).attr('href');
					$(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
					thisJQuery.find( taxonomyIdPrefix + '-tabs' ).siblings('.tabs-panel').hide();
					thisJQuery.find( t ).show();
					$(this).focus();
					
					// Store the "all/most used" setting in a cookie
					if ( "#mla-" + taxonomy + '-all' == t ) {
						deleteUserSetting( settingName );
					} else {
						setUserSetting( settingName, 'pop' );
					}
					
					return false;
				});
	
				// Reflect tab selection remembered in cookie
				if ( getUserSetting( settingName ) ) {
					thisJQuery.find( taxonomyIdPrefix + '-tabs a[href="#mla-' + taxonomy + '-pop"]' ).click();
				}
				
				// Toggle the "Add New ..." sub panel
				thisJQuery.find( taxonomyIdPrefix + '-add-toggle' ).click( function() {
					thisJQuery.find( taxonomyIdPrefix + '-searcher' ).addClass( 'mla-hidden-children' );
					thisJQuery.find( taxonomyIdPrefix + '-adder' ).toggleClass( 'mla-hidden-children' );
					thisJQuery.find( taxonomyIdPrefix + '-tabs a[href="#mla-' + taxonomy + '-all"]' ).click();
					
					thisJQuery.find( taxonomyIdPrefix + '-checklist li' ).show();
					thisJQuery.find( taxonomyIdPrefix + '-checklist-pop li' ).show();
					
					if ( false === thisJQuery.find( taxonomyIdPrefix + '-adder' ).hasClass( 'mla-hidden-children' ) ) {
						thisJQuery.find( taxonomyNewIdSelector ).val( '' ).removeClass( 'form-input-tip' );
						thisJQuery.find( taxonomyNewIdSelector ).focus();
					}
					return false;
				});
		
				// Convert "Enter" key to a click
				thisJQuery.find( taxonomyNewIdSelector ).keypress( function(event){
					if( 13 === event.keyCode ) {
						event.preventDefault();
						thisJQuery.find( taxonomyIdPrefix + '-add-submit' ).click();
					}
				});
				
				thisJQuery.find( taxonomyIdPrefix + '-add-submit' ).click( function(){
					thisJQuery.find( taxonomyNewIdSelector ).focus();
				});
		
				catAddBefore = function( s ) {
					if ( ! thisJQuery.find( taxonomyNewIdSelector ).val() )
						return false;
						
					s.data += '&' + thisJQuery.find( taxonomyIdPrefix + '-checklist :checked' ).serialize();
					thisJQuery.prop( 'disabled', true );
					return s;
				};
	
				catAddAfter = function( r, s ) {
					var sup, drop = thisJQuery.find( taxonomyNewIdSelector + '_parent' );
		
					thisJQuery.prop( 'disabled', false );
					if ( 'undefined' != s.parsed.responses[0] && (sup = s.parsed.responses[0].supplemental.newcat_parent) ) {
						drop.before(sup);
						drop.remove();
					}
				};
	
				// wpList is in /wp-includes/js/wp-lists.js
				thisJQuery.find( taxonomyIdPrefix + '-checklist' ).wpList({
					alt: '',
					response: 'mla-' + taxonomy + '-ajax-response',
					addBefore: catAddBefore,
					addAfter: catAddAfter
				});
	
				// Synchronize checkbox changes between "All ..." and "Most Used" panels
				thisJQuery.find( taxonomyIdPrefix + '-checklist, ' + taxonomyIdPrefix + '-checklist-pop' ).on( 'click', 'li.popular-category > label input[type="checkbox"]', function() {
					var t = $(this), c = t.is(':checked'), id = t.val();
	
					if ( id && t.parents( '#mla-taxonomy-'+ taxonomy ).length ) {
						$('#in-' + taxonomy + '-' + id + ', #in-popular-' + taxonomy + '-' + id).prop( 'checked', c );
					}
				});
	
				/*
				 * Searchable meta box code from mla-edit-media-scripts.js
				 */
				$.extend( $.expr[":"], {
					"matchTerms": function( elem, i, match, array ) {
						return ( elem.textContent || elem.innerText || "" ).toLowerCase().indexOf( ( match[3] || "" ).toLowerCase() ) >= 0;
					}
				});
	
				thisJQuery.find( taxonomySearchIdSelector ).keypress( function( event ){
					// Enter key cancels the filter and closes the search field
					if( 13 === event.keyCode ) {
						event.preventDefault();
						thisJQuery.find( taxonomySearchIdSelector ).val( '' );
						thisJQuery.find( taxonomyIdPrefix + '-searcher' ).addClass( 'mla-hidden-children' );
	
						thisJQuery.find( taxonomyIdPrefix + '-checklist li' ).show();
						thisJQuery.find( taxonomyIdPrefix + '-checklist-pop li' ).show();
						return;
					}
	
				} );
		
				thisJQuery.find( taxonomySearchIdSelector ).keyup( function( event ){
					var searchValue, matchingTerms, matchingTermsPopular;
	
					// keyup happens after keypress; change the focus if the text box has been closed
					if( 13 === event.keyCode ) {
						event.preventDefault();
						thisJQuery.find( taxonomyIdPrefix + '-search-toggle' ).focus();
						return;
					}
	
					searchValue = thisJQuery.find( taxonomySearchIdSelector ).val(),
						termList = thisJQuery.find( taxonomyIdPrefix + '-checklist li' );
						termListPopular = thisJQuery.find( taxonomyIdPrefix + '-checklist-pop li' );
					
					if ( 0 < searchValue.length ) {
						termList.hide();
						termListPopular.hide();
					} else {
						termList.show();
						termListPopular.show();
					}
					
					matchingTerms = thisJQuery.find( taxonomyIdPrefix + "-checklist label:matchTerms('" + searchValue + "')");
					matchingTerms.closest( 'li' ).find( 'li' ).andSelf().show();
					matchingTerms.parents( taxonomyIdPrefix + '-checklist li' ).show();
	
					matchingTermsPopular = thisJQuery.find( taxonomyIdPrefix + "-checklist-pop label:matchTerms('" + searchValue + "')");
					matchingTermsPopular.closest( 'li' ).find( 'li' ).andSelf().show();
					matchingTermsPopular.parents( taxonomyIdPrefix + '-checklist li' ).show();
				} );
	
				// Toggle the "Search" sub panel
				thisJQuery.find( taxonomyIdPrefix + '-search-toggle' ).click( function() {
					thisJQuery.find( taxonomyIdPrefix + '-adder ').addClass( 'mla-hidden-children' );
					thisJQuery.find( taxonomyIdPrefix + '-searcher' ).toggleClass( 'mla-hidden-children' );
					thisJQuery.find( taxonomyIdPrefix + '-tabs a[href="#mla-' + taxonomy + '-all"]' ).click();
					
					thisJQuery.find( taxonomyIdPrefix + '-checklist li' ).show();
					thisJQuery.find( taxonomyIdPrefix + '-checklist-pop li' ).show();
					
					if ( false === thisJQuery.find( taxonomyIdPrefix + '-searcher' ).hasClass( 'mla-hidden-children' ) ) {
						thisJQuery.find( taxonomySearchIdSelector ).val( '' ).removeClass( 'form-input-tip' );
						thisJQuery.find( taxonomySearchIdSelector ).focus();
					}
					
					return false;
				});
			}); // .categorydiv.each
		} // mla.settings.enableDetailsCategory
		
		if ( mla.settings.enableDetailsTag ) {
			$('.mla-taxonomy-field .tagsdiv', context ).each( function(){
				var taxonomy = mla.utility.parseTaxonomyId( $(this).attr('id') );
	
				// Expand/collapse the meta box contents
				$( '.compat-field-' + taxonomy + ' th', context ).click( function() {
					$(this).siblings( 'td' ).slideToggle();
				});
				
				// Install support for flat taxonomies
				mla.tagBox.init( attachmentId, taxonomy, context );
			}); // .tagsdiv.each
		} // mla.settings.enableDetailsTag
	};
	
	/*
	* Add a utility method to jQuery
	* See if elements exists
	* /
	$.fn.extend({
		mlaExists: function() {
			return $(this).length>0;
		}
	}); // */
   
	/**
	 * Extend the WP AttachmentCompat object
	 * /
	mla.compat = {
		init: function() {
			console.log( 'mla.compat.init' );
			
			// Intercept the view.AttachmentCompat methods
			var original = wp.media.view.AttachmentCompat.prototype;
			console.log( 'mla.compat.init tagName: ', original.tagName );
			console.log( 'mla.compat.init className: ', original.className );
			
			// Save the original methods
			original.mla_old_initialize = original.initialize;
			original.mla_old_dispose = original.dispose;
			original.mla_old_render = original.render;
			original.mla_old_preventDefault = original.preventDefault;
			original.mla_old_save = original.save;
			
			original.initialize = function() {
				console.log( 'mla.compat.initialize' );
				this.mla_old_initialize();
			};
			
			original.dispose = function() {
				console.log( 'mla.compat.dispose' );
				this.mla_old_dispose();
				return this; // allow chaining
			};
			
			original.render = function() {
				console.log( 'mla.compat.render' );
				this.mla_old_render();
				return this; // allow chaining
			};
			
			original.preventDefault = function() {
				console.log( 'mla.compat.preventDefault' );
				this.mla_old_preventDefault();
			};
			
			// Note: Advanced Custom Fields (ACF) overrides this method and never calls the original!
			original.save = function() {
				console.log( 'mla.compat.save' );
				this.mla_old_save();
			};
		}
	}; // mla.compat */
	
//$( document ).ready( function(){ mla.compat.init(); } )
}( jQuery ) );
