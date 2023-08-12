var floating_message_timer;
$ = jQuery;
jQuery( document ).ready( function( $ ) {
	$('.toggle-featured-property').on('click', function ( event ) {
		var data = {
			action: 'toggle_featured_property',
			property_id: $(this).val()
		};
		$.post( zestylemon.ajaxurl, data,
			function (response) {
				show_floating_message( response.data.message, response.success );
			}
		);
	} );

	$('.toggle-show-on-frontend').on('click', function ( event ) {
		var data = {
			action: 'toggle_show_on_front',
			term_id: $(this).val()
		};
		$.post( zestylemon.ajaxurl, data,
			function (response) {
				show_floating_message( response.data.message, response.success );
			}
		);
	} );

	$('.toggle-allow-booking').on('click', function ( event ) {
		var data = {
			action: 'toggle_allow_booking',
			term_id: $(this).val()
		};
		$.post( zestylemon.ajaxurl, data,
			function (response) {
				show_floating_message( response.data.message, response.success );
			}
		);
	} );
	
	$('.toggle-stand-out-feature').on('click', function ( event ) {
		var data = {
			action: 'toggle_stand_out_feature',
			term_id: $(this).val()
		};
		$.post( zestylemon.ajaxurl, data,
			function (response) {
				show_floating_message( response.data.message, response.success );
			}
		);
	} );
} );

function show_floating_message( message, success ) {
	var message_class = 'failed';
	if ( success ) {
		message_class = 'success';
	}
	if ( $('.zestylemon-floating-message').length > 0 ) {
		$('.zestylemon-floating-message').remove();
	}
	$container = $('<div class="zestylemon-floating-message ' + message_class + '"></div>');
	$close     = $('<span class="zestylemon-floating-message-close"></span>');
	$container.append( $close );
	$container.append( message );
	$('body').append( $container );
	$('.zestylemon-floating-message').fadeIn('fast', function () {
		clearTimeout( floating_message_timer );
		floating_message_timer = setTimeout(() => {
			hide_floating_message();
		}, 5000);
	});
	$('body').on('click', '.zestylemon-floating-message-close', function ( event ) {
		clearTimeout( floating_message_timer );
		hide_floating_message();
	});
}

function hide_floating_message() {
	$('.zestylemon-floating-message').fadeOut('fast', function () {
		$('.zestylemon-floating-message').remove();
	});
}