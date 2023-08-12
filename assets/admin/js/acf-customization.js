const { __, _x, _n, _nx } = wp.i18n;
jQuery( document ).ready( function( $ ) {
	if ( typeof acf != 'undefined' ) {
		var fields = {
			administrative_level_1: $('div[data-name="administrative_level_1"]').length > 0 ? acf.getField( $('div[data-name="administrative_level_1"]').attr('data-key') ) : null,
			administrative_level_2: $('div[data-name="administrative_level_2"]').length > 0 ? acf.getField( $('div[data-name="administrative_level_2"]').attr('data-key') ) : null,
			administrative_level_3: $('div[data-name="administrative_level_3"]').length > 0 ? acf.getField( $('div[data-name="administrative_level_3"]').attr('data-key') ) : null,
			administrative_level_4: $('div[data-name="administrative_level_4"]').length > 0 ? acf.getField( $('div[data-name="administrative_level_4"]').attr('data-key') ) : null,
			administrative_level_5: $('div[data-name="administrative_level_5"]').length > 0 ? acf.getField( $('div[data-name="administrative_level_5"]').attr('data-key') ) : null,
			nearest_airport: $('div[data-name="nearest_airport"]').length > 0 ? acf.getField( $('div[data-name="nearest_airport"]').attr('data-key') ) : null,
			nearest_station: $('div[data-name="nearest_station"]').length > 0 ? acf.getField( $('div[data-name="nearest_station"]').attr('data-key') ) : null,
			nearest_beach: $('div[data-name="nearest_beach"]').length > 0 ? acf.getField( $('div[data-name="nearest_beach"]').attr('data-key') ) : null,
		};
		if ( fields.administrative_level_1 != null && $('body').hasClass('post-type-property') && ( $('body').hasClass('post-php') || $('body').hasClass('post-new-php') ) ) {
			fields.administrative_level_1.on('change', function( event ) {
				fields.administrative_level_2.val('');
				fields.administrative_level_3.val('');
				fields.administrative_level_4.val('');
				fields.administrative_level_5.val('');
				fields.nearest_airport.val('');
				fields.nearest_station.val('');
				fields.nearest_beach.val('');
			} );
		}
		if ( fields.administrative_level_2 != null ) {
			fields.administrative_level_2.on('change', function( event ) {
				fields.administrative_level_3.val('');
				fields.administrative_level_4.val('');
				fields.administrative_level_5.val('');
			} );
		}
		if ( fields.administrative_level_3 != null ) {
			fields.administrative_level_3.on('change', function( event ) {
				fields.administrative_level_4.val('');
				fields.administrative_level_5.val('');
			} );
		}
		if ( fields.administrative_level_4 != null ) {
			fields.administrative_level_4.on('change', function( event ) {
				fields.administrative_level_5.val('');
			} );
		}
		acf.add_filter('select2_ajax_data', function( data, args, $input, field, instance ) {
			switch ( $( field ).data('name') ) {
				case 'administrative_level_2':
					data.parent = fields.administrative_level_1.val();
					break;
				case 'administrative_level_3':
					data.parent = fields.administrative_level_2.val();
					break;
				case 'administrative_level_4':
					data.parent = fields.administrative_level_3.val();
					break;
				case 'administrative_level_5':
					data.parent = fields.administrative_level_4.val();
					break;
				case 'nearest_airport':
				case 'nearest_station':
				case 'nearest_beach':
					data.administrative_level_1 = fields.administrative_level_1.val();
					break;
				default:
					break;
			}
			return data;
		} );

		$('input[data-extra-taxonomy]').on('change', function ( event ) {
			console.log('Test');
			$this = $( this );
			$extra_taxonomy_container = $('div[data-taxonomy="' + $this.data('extra-taxonomy') + '"]').parents('.acf-field-taxonomy');
			if ( $this.is(':checked') ) {
				$extra_taxonomy_container.show();
				
				//PT-23 Extra info fields for pets displaying on wp-admin property page when 'Pets allowed' = 0
				if($this.data('extra-taxonomy') == 'property_pets'){
					$('div[data-name="pets_for_free"]').show();	
				}
				
			} else {
				$extra_taxonomy_container.find('select').val('').trigger('change');
				$extra_taxonomy_container.hide();

				//PT-23 Extra info fields for pets displaying on wp-admin property page when 'Pets allowed' = 0
				if($this.data('extra-taxonomy') == 'property_pets'){
					$('div[data-name="pets_for_free"]').find('.acf-switch.-on .acf-switch-slider').trigger('click');
					$('div[data-name="pets_for_free"]').hide();
				}

			}
		} );


		$('input[data-extra-taxonomy]').trigger('change');
		

		var property_pets = $('div[data-name="property_pets"]').length > 0 ? acf.getField( $('div[data-name="property_pets"]').attr('data-key') ) : null;
		if ( property_pets != null ) {
			set_property_pets_width( property_pets );
			property_pets.on('change', function( event ) {
				console.log( property_pets.val() );
				set_property_pets_width( property_pets );
			} );
		}
	}
} );

function set_property_pets_width( property_pets ) {
	if( property_pets.val() ) {
		$('div[data-name="property_pets"]').css('width', '70%');
	} else {
		$('div[data-name="property_pets"]').css('width', '70%');
	}
}

( function ( $, undefined ) {
	var TaxonomyEqualTo = acf.Condition.extend({
		type: 'TaxonomyEqualTo',
		operator: 'term',
		label: __('Taxonomy term is equal to'),
		fieldTypes: ['taxonomy'],
		match: function (rule, field) {
			return parseInt( field.val() ) === parseInt( rule.value );
		},
		choices: function (fieldObject) {
			var taxonomy = fieldObject.$setting('taxonomy select').val();
			var choices = [];
			choices.push({
			  id: '',
			  text: ''
			});
			var data = {
				action: 'get_taxonomy_terms',
				taxonomy: taxonomy
			};
			$.ajax( {
				type: "POST",
				url: zestylemon.ajaxurl,
				data: data,
				async: !1,
				success: function (response) {
					if ( response.success ) {
						console.log( response.data );
						$.each( response.data, function ( item_index, item ) {
							choices.push({
								id: item_index,
								text: item
							});
						});
					}
				}
			} );
			return choices;
		}
	});
	acf.registerConditionType( TaxonomyEqualTo );
} )( jQuery );