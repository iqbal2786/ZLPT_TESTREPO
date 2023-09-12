jQuery( document ).ready( function( $ ) {
	setInterval(() => {
		init_properties_swiper();
		init_posts_swiper();
	}, 250);
	$('body').on( 'keyup', '.zesty-lemon-filter-icons input', function ( event ) {
		var query = $( this ).val();
		if ( query.length <= 0 ) {
			$('.zesty-lemon-icon-selector-container .zesty-lemon-icon-selector span').show();
			return;
		}
		$.each( $('.zesty-lemon-icon-selector-container .zesty-lemon-icon-selector span'), function ( icone_index, icon ) { 
			if ( $( icon ).is('[class*="' + query + '"]') ) {
				$( icon ).show();
			} else {
				$( icon ).hide();
			}
		} );
	} );
} );
