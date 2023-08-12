$ = jQuery;
jQuery( document ).ready( function( $ ) {
	init_search_box();
	init_advanced_search();
	init_properties_view_mode();
	init_properties_horizontal_filters();
	init_properties_swiper();
	init_posts_swiper();
	$.each($('.zl-map'), function (map_index, map) { 
		init_zl_map( $(map) );
	});
	init_single_property_photo_gallery();
	init_single_property_hero_zoom_image();
	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();
		if ( scroll >= 200 ) {
			$('.single-post.no-sidebar header.site-header').addClass('sticky');
		} else {
			$('.single-post.no-sidebar header.site-header').removeClass('sticky');
		}
	});
	// if ( $.isFunction( init_single_property_photo_gallery ) ) {
	// 	console.log('YES');
	// } else {
	// 	console.log('NO');
	// }
} );

// function getCoords( position ) {
//     console.log( position.coords.latitude );
//     console.log( position.coords.longitude );
// }

// function handleGeoErrors( error ) {
//     switch ( error.code ) {
//       	case error.PERMISSION_DENIED:
// 			alert("You need to share your geolocation data.");
// 			break;

// 		case error.POSITION_UNAVAILABLE:
// 			alert("Current position not available.");
// 			break;

// 		case error.TIMEOUT:
// 			alert("Retrieving position timed out.");
// 			break;

// 		default:
// 			alert("Error");
// 			break;
// 	}
// }

function init_search_box() {
	// $('.zl-search-box-query').focus( function ( event ) { 
	// 	event.preventDefault();
	// 	navigator.geolocation.getCurrentPosition( getCoords, handleGeoErrors );
	// } );
	$('.zl-search-box-type-btn').click( function ( event ) {
		event.preventDefault();
		event.stopPropagation();
		$('.zl-search-box-type-btn').toggleClass('opened');
		$('.zl-search-box-type-drop-down').toggle();
	} );
	$('.zl-search-box-type-drop-down ul li').on( 'click', function ( event ) {
		$('.zl-search-box-type-btn').removeClass('opened');
		$('.zl-search-box-type-drop-down').hide();
		$('.zl-search-box-property-type').val( $( this ).attr('data-id') );
		$('.zl-search-box-type-btn .zl-placeholder').text( $( this ).text() );
	} );
	$('body').click( function ( event ) {
		if ( ! $( event.target ).is('.zl-search-box-type-btn') ) {
			$('.zl-search-box-type-btn').removeClass('opened');
			$('.zl-search-box-type-drop-down').hide();
		}
	} );
}

function init_advanced_search() {
	$('.zl-advanced-search-form-wrapper').appendTo('body');
	$('.zl-advanced-search-btn').click( function ( event ) {
		event.preventDefault();
		event.stopPropagation();
        /*
			Hide few section if property type is Hotels
			hide-on-hotels
		*/
		$('.hide-on-hotels').show();
		if($('input[name=property-type]').val() == 'hotels'){
			$('.hide-on-hotels').hide();
		}

		$('.zl-advanced-search').show();
		$('body').addClass('zl-modal-open');
		setTimeout(() => {
			$('.zl-advanced-search').addClass('zl-in');
		}, 50);
	} );
	$('.zl-modal-dialog-close, .zl-modal-overlay').click( function ( event ) {
		event.preventDefault();
		event.stopPropagation();
		if ( $( event.target ).hasClass('zl-modal-dialog-close') || $( event.target ).hasClass('zl-modal-overlay') ) {
			$('.zl-advanced-search').removeClass('zl-in');
			setTimeout(() => {
				$('.zl-advanced-search').hide();
				$('body').removeClass('zl-modal-open');
			}, 350);
		}
	} );
	$('.zl-modal-btn[type="button"]').click( function ( event ) {
		event.preventDefault();
		event.stopPropagation();
		// console.log( event );
		// console.log( event.target );
		if ( $('.zl-modal-btn.opened').length > 0 && ! $( event.currentTarget ).is('.opened') ) {
			$('.zl-modal-btn.opened').parents('.zl-form-group').find('.zl-modal-drop-down').hide();
			$('.zl-modal-btn.opened').removeClass('opened');
		}
		$(this).toggleClass('opened');
		$(this).parents('.zl-form-group').find('.zl-modal-drop-down').toggle();
	} );
	$('.zl-filter-options li').on( 'click', function ( event ) {
		$this = $( event.target );
		if ( $this.is('span') ) {
			$this = $( event.target ).parent();
		}
		$is_multiselect = $this.parents('.zl-filter-options').is('.zl-multiselect');
		if ( $is_multiselect ) {
			if ( $this.hasClass('selected') ) {
				$this.removeClass('selected');
			} else {
				$this.addClass('selected');
			}
		} else {
			$this.parents('ul').find('li.selected').removeClass('selected');
			$this.addClass('selected');
		}
		var value = [];
		$.each( $this.parents('ul').find('li.selected'), function ( item_index, item ) { 
			value.push( $(item).attr('data-id') );
		} );
		value = value.join(', ');
		$this.parents('.zl-filter-group-body').find('.zl-hidden-field').val( value );
	} );
	$('.zl-modal-drop-down ul li').on( 'click', function ( event ) {
		$this = $( event.target );
		$is_multiselect = $this.parents('.zl-modal-drop-down').is('.zl-multiselect');
		if ( $is_multiselect ) {
			if ( $this.hasClass('selected') ) {
				$this.removeClass('selected');
			} else {
				$this.addClass('selected');
			}
			var value = [];
			var placeholder = [];
			$.each( $this.parents('ul').find('li.selected'), function ( item_index, item ) { 
				value.push( $(item).attr('data-id') );
				placeholder.push( $.trim( $(item).text() ) );
			} );
			if ( placeholder.length <= 0 ) {
				$this.parents('.zl-form-group').find('.zl-modal-btn .zl-placeholder').text( $this.parents('.zl-form-group').find('.zl-modal-btn').attr('title') );
			} else {
				placeholder = placeholder.join(', ');
				$this.parents('.zl-form-group').find('.zl-modal-btn .zl-placeholder').text( placeholder );
			}
			value = value.join(', ');
			$this.parents('.zl-form-group').find('.zl-hidden-field').val( value );
		} else {
			if ( $this.hasClass('zl-empty-option') ) {
				$this.hide();
			} else {
				$this.parents('ul').find('.zl-empty-option').show();
			}
			$this.parents('.zl-form-group').find('.zl-modal-btn').removeClass('opened');
			$this.parents('.zl-form-group').find('.zl-modal-drop-down').hide();
			$this.parents('.zl-form-group').find('.zl-hidden-field').val( $this.attr('data-id') );
			$this.parents('.zl-form-group').find('.zl-placeholder').text( $this.text() );
		}
	} );
	if ( $('#zl-price-range-slider').length > 0 ) {
		var min_price = $('.property-min-price').val();
		var max_price = $('.property-max-price').val();
		if ( min_price.length <= 0 ) {
			min_price = $('#zl-price-range-slider').data('min-price');
		}
		if ( max_price.length <= 0 ) {
			max_price = $('#zl-price-range-slider').data('max-price');
		}
		$('#zl-price-range-slider').slider( {
			range: true,
			min: $('#zl-price-range-slider').data('min-price'),
			max: $('#zl-price-range-slider').data('max-price'),
			values: [ min_price, max_price ],
			step: 100,
			slide: function( event, ui ) {
				$('.zl-price-range-min').text( ui.values[0] );
				$('.property-min-price').val( ui.values[0] );
				$('.zl-price-range-max').text( ui.values[1] );
				$('.property-max-price').val( ui.values[1] );
			}
		} );
		$('.zl-price-range-min').text( $('#zl-price-range-slider').slider('values', 0) );
		$('.zl-price-range-max').text( $('#zl-price-range-slider').slider('values', 1) );
	}
	$('.zl-more-refinements-btn').click( function ( event ) {
		$this = $( event.target );
		event.preventDefault();
		event.stopPropagation();
		$('.zl-modal-properties-features-wrapper').slideToggle('fast');
		$this.parents('.zl-form-btn-group').find('.zl-btn-plus-icon').toggle();
		$this.parents('.zl-form-btn-group').find('.zl-btn-minus-icon').toggle();
	} );
	$('.zl-advanced-search-form-wrapper').on('reset', function ( event ) {
		$this = $( event.target );
		$this.find('.zl-modal-query').removeAttr('value');
		$this.find('.zl-hidden-field').val('');
		$this.find('.zl-filter-options li.selected').removeClass('selected');
		$this.find('input:checked').removeAttr('checked');
		// $.each( $this.find('.zl-modal-btn'), function ( btn_index, btn_element ) { 
		// 	$( btn_element ).find('.zl-placeholder').text( $( btn_element ).attr('title') );
		// } );
		// $this.find('.zl-modal-drop-down ul li.selected').removeClass('selected');
		$this.find('#zl-price-range-slider').slider('values', [ $('#zl-price-range-slider').data('min-price'), $('#zl-price-range-slider').data('max-price') ]);
		$this.find('.zl-price-range-min').text( $('#zl-price-range-slider').data('min-price') );
		$this.find('.zl-price-range-max').text( $('#zl-price-range-slider').data('max-price') );
		$this.find('.property-min-price').val('');
		$this.find('.property-max-price').val('');
	} );
	$('.zl-modal-dialog').click( function ( event ) {
		close_drop_down( event );
	} );
	if ( $('.zl-quick-filters-wrapper').length > 0 ) {
		$('body').click( function ( event ) {
			if ( $( event.target ).parents('.zl-form-group').length <= 0 ) {
				$('.zl-modal-btn').removeClass('opened');
				$('.zl-modal-drop-down').hide();
			}
		} );
	}
}

function close_drop_down( event ) {
	if ( ! $( event.target ).is('.zl-modal-drop-down ul li') ) {
		$( event.target ).parents('.zl-advanced-search').find('.zl-modal-btn').removeClass('opened');
		$( event.target ).parents('.zl-advanced-search').find('.zl-modal-drop-down').hide();
	}
}

function init_properties_view_mode() {
	$('.zl-properties-horizontal-filters .zl-grid-view').click( function ( event ) { 
		event.preventDefault();
		$(this).parent().find('.active').removeClass('active');
		$(this).addClass('active');
		$('.zesty-lemon-properties-entries').addClass('zl-grid-view');
		$('.zesty-lemon-properties-entries').removeClass('zl-list-view');
	} );
	$('.zl-properties-horizontal-filters .zl-list-view').click( function ( event ) { 
		event.preventDefault();
		$(this).parent().find('.active').removeClass('active');
		$(this).addClass('active');
		$('.zesty-lemon-properties-entries').addClass('zl-list-view');
		$('.zesty-lemon-properties-entries').removeClass('zl-grid-view');
	} );
}

function init_properties_horizontal_filters() {
	$('.zl-quick-filters-wrapper .zl-modal-drop-down.zl-multiselect ul').append('<li class="zl-apply-filter">Apply</li>');
	$('.zl-quick-filters-wrapper .zl-modal-drop-down ul').on( 'click', 'li', function ( event ) {
		$is_multiselect = $this.parents('.zl-modal-drop-down').is('.zl-multiselect');
		event.preventDefault();
		if ( ! $is_multiselect ) {
			$(this).parents('form').eq(0).submit();
		} else {
			$(this).parent().find('.zl-apply-filter').show();
			if ( $(this).is('.zl-apply-filter') ) {
				$(this).parents('form').eq(0).submit();
			}
		}
	});
}

function init_zl_map( $el ) {
	if ( $el ) {
		var marker_icon = $el.data('marker-icon');
		// Find marker elements within map.
		var $markers = $el.find('.zl-marker');
		// Create gerenic map.
		var centerLatLng = {
			lat: 0,
			lng: 0
		};
		var mapArgs = {
			zoom        : $el.data('zoom') || 13,
			scrollwheel : false,
			mapTypeId   : google.maps.MapTypeId.ROADMAP,
			center      : centerLatLng,
			styles      : [
				{
					"featureType": "poi.business.lodging",
					"elementType": "labels",
					"stylers": [
						{ "visibility": "off" }
					]
				}
			],
		};
		var map = new google.maps.Map( $el[0], mapArgs );
		// Add markers.
		map.markers = [];
		var info_windows = [];
		$markers.each(function(){
			init_zl_marker( $(this), marker_icon, map, info_windows );
		});
		// Center map based on markers.
		center_map( map );
		// Return map instance.
		return map;
	}
}

function init_zl_marker( $marker, marker_icon, map, info_windows ) {
    // Get position from marker.
    var lat    = $marker.data('lat');
    var lng    = $marker.data('lng');
    var title  = $marker.data('title');
    var latLng = {
        lat: parseFloat( lat ),
        lng: parseFloat( lng )
    };
    // Create marker instance.
	var marker = new google.maps.Marker( {
		position: latLng,
		map: map,
		icon: {
			url: marker_icon,
			scaledSize: new google.maps.Size(75, 62)
		} ,
		optimized: false,
		title: title
	});
	marker.setAnimation( google.maps.Animation.BOUNCE );
	setTimeout( function() {
		marker.setAnimation(null);
	}, 1000 );
    // Append to reference for later use.
    map.markers.push( marker );
    // If marker contains HTML, add it to an infoWindow.
    if( $marker.html() ) {
        // Create info window.
        var info_window = new google.maps.InfoWindow({
            content: $marker.html()
        });
		info_windows.push( info_window );
        // Show info window when marker is clicked.
        google.maps.event.addListener(marker, 'click', function() {
			for ( var i = 0; i < info_windows.length; i++ ) {
				info_windows[i].close();
			}
            info_window.open( map, marker );
        });
    }
}

function center_map( map ) {
    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
        bounds.extend({
            lat: marker.position.lat(),
            lng: marker.position.lng()
        });
    });
    // Case: Single marker.
    if( map.markers.length == 1 ){
        map.setCenter( bounds.getCenter() );
    // Case: Multiple markers.
    } else{
        map.fitBounds( bounds );
    }
}

function init_single_property_photo_gallery() {
	var galleries = $('.zl-single-property-gallery');
	$.each(galleries, function (gallery_index, gallery) {
		init_light_gallery( gallery );
	});
}

function init_single_property_hero_zoom_image() {
	var hero_images = $('.zl-hero-img-trigger-wrapper');
	$.each(hero_images, function (hero_index, hero) {
		init_light_gallery( hero );
	});
}

function init_light_gallery( container ) {
		lightGallery(container, {
			controls: true,
			speed: 500,
			download: false,
			showZoomInOutIcons: true,
			actualSize: false,
			licenseKey: '8CA6824F-B34C450A-A5DC756B-42FE810F',
			plugins: [lgZoom, lgThumbnail]
		});
}