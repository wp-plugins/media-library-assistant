// These functions are adapted from wp-admin/js/post.js
(function($) {
mlaEditAttachment = {

	init : function(){
		$('.categorydiv').each( function(){
			var this_id = $(this).attr('id'), taxonomyParts, taxonomy, settingName;
	
			taxonomyParts = this_id.split('-');
			taxonomyParts.shift();
			taxonomy = taxonomyParts.join('-');
			settingName = taxonomy + '_tab';
			if ( taxonomy == 'category' )
				settingName = 'cats';
	
			$( '#search-' + taxonomy ).one( 'focus', function() { $( this ).val( '' ).removeClass( 'form-input-tip' ); } );
	
			$.extend( $.expr[":"], {
				"matchTerms": function( elem, i, match, array ) {
					return ( elem.textContent || elem.innerText || "" ).toLowerCase().indexOf( ( match[3] || "" ).toLowerCase() ) >= 0;
				}
			});
			
			$( '#search-' + taxonomy ).keypress( function( event ){
				
				if( 13 === event.keyCode ) {
					event.preventDefault();
					$( '#search-'  + taxonomy ).val( '' );
					$( '#' + taxonomy + '-searcher' ).addClass( 'wp-hidden-children' );

					$( '#' + taxonomy + 'checklist li' ).show();
					$( '#' + taxonomy + 'checklist-pop li' ).show();
					return;
				}

			} );
	
			$( '#search-' + taxonomy ).keyup( function( event ){
				if( 13 === event.keyCode ) {
					event.preventDefault();
					$( '#' + taxonomy + '-search-toggle' ).focus();
					return;
				}

				var searchValue = $( '#search-' + taxonomy ).val(),
					termList = $( '#' + taxonomy + 'checklist li' );
					termListPopular = $( '#' + taxonomy + 'checklist-pop li' );
				
				if ( 0 < searchValue.length ) {
					termList.hide();
					termListPopular.hide();
				} else {
					termList.show();
					termListPopular.show();
				}
				
				var matchingTerms = $( '#' + taxonomy + "checklist label:matchTerms('" + searchValue + "')");
				matchingTerms.closest( 'li' ).find( 'li' ).andSelf().show();
				matchingTerms.parents( '#' + taxonomy + 'checklist li' ).show();

				var matchingTermsPopular = $( '#' + taxonomy + "checklist-pop label:matchTerms('" + searchValue + "')");
				matchingTermsPopular.closest( 'li' ).find( 'li' ).andSelf().show();
				matchingTermsPopular.parents( '#' + taxonomy + 'checklist li' ).show();
			} );
	
			$( '#' + taxonomy + '-search-toggle' ).click( function() {
				$( '#' + taxonomy + '-adder ').addClass( 'wp-hidden-children' );
				$( '#' + taxonomy + '-searcher' ).toggleClass( 'wp-hidden-children' );
				$( 'a[href="#' + taxonomy + '-search"]', '#' + taxonomy + '-tabs' ).click();
				$( '#search-'  + taxonomy ).val( '' );
				$( '#' + taxonomy + 'checklist li' ).show();
				$( '#' + taxonomy + 'checklist-pop li' ).show();
				$( '#search-' + taxonomy ).focus();
				return false;
			});
	
			$( '#' + taxonomy + '-add-toggle' ).click( function() {
				$( '#' + taxonomy + '-searcher' ).addClass( 'wp-hidden-children' );
				return false;
			});
	
		}); // end cats
	}
}

$( document ).ready( function(){ mlaEditAttachment.init(); } );
})( jQuery );
