<?php
// inner function to be used to render the taxonomies filters for properties in "All properties" page
function zesty_lemon_show_filter_dropdown( $taxonomy, $option_all, $show_count ) {
	$selected = isset( $_GET[ $taxonomy ] ) ? esc_attr( $_GET[ $taxonomy ] ) : null;
	$args = array(
		'hide_empty'      => false,
		'hierarchical'    => true,
		'show_option_all' => $option_all,
		'value_field'     => 'slug',
		'taxonomy'        => $taxonomy,
		'name'            => $taxonomy,
		'show_count'      => $show_count,
		'selected'        => $selected,
	);
	wp_dropdown_categories( $args );
}

// Show the filters in "All properties" page above the WP_List_Table
add_action( 'restrict_manage_posts', 'zesty_lemon_show_properties_admin_filters' );
function zesty_lemon_show_properties_admin_filters() {
	if ( is_admin() && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == 'property' ) {
		$property_id        = isset( $_GET[ 'property_id' ] ) ? sanitize_text_field( $_GET[ 'property_id' ] ) : null;
		$property_price_min = isset( $_GET[ 'property_price_min' ] ) ? sanitize_text_field( $_GET[ 'property_price_min' ] ) : null;
		$property_price_max = isset( $_GET[ 'property_price_max' ] ) ? sanitize_text_field( $_GET[ 'property_price_max' ] ) : null;
		zesty_lemon_show_filter_dropdown( 'property_location', 'Filter by location', true );
		zesty_lemon_show_filter_dropdown( 'property_status', 'Filter by status', true );
		zesty_lemon_show_filter_dropdown( 'property_type', 'Filter by type', true );
		zesty_lemon_show_filter_dropdown( 'property_bedrooms', 'Filter by bedrooms', true );
		zesty_lemon_show_filter_dropdown( 'property_bathrooms', 'Filter by bathrooms', true );
		zesty_lemon_show_filter_dropdown( 'property_sleeps', 'Filter by sleeps', true );
		?>
		<input type="text" name="property_id" placeholder="<?php _e('Search by property ID','zestylemon'); ?>" id="property_id" value="<?php echo $property_id; ?>" autocomplete="off"/>
		<input type="text" name="property_price_min" placeholder="<?php _e('Min Price','zestylemon'); ?>" id="property_price_min" value="<?php echo $property_price_min; ?>" autocomplete="off"/>
		<input type="text" name="property_price_max" placeholder="<?php _e('Max Price','zestylemon'); ?>" id="property_price_max" value="<?php echo $property_price_max; ?>" autocomplete="off"/>
		<a href="<?php echo admin_url('/edit.php?post_type=property'); ?>" class="zestylemon-rest-filter"><?php _e( 'Reset Filter', 'zestylemon' ); ?></a>
		<?php
	}
}

// append some text "labels" to the numbers in the "Bedrooms, Bathrooms, Sleeps" dropdown lists in the filters of "All properties" page
add_filter( 'list_cats', 'zesty_lemon_list_cats', 10, 2 );
function zesty_lemon_list_cats( $term_name, $term ) {
	if ( is_admin() ) {
		$screen = get_current_screen();
		if ( $screen->id == 'edit-property' ) {
			if ( isset( $term->taxonomy ) ) {
				switch ( $term->taxonomy ) {
					case 'property_bedrooms':
						$term_name .= _nx( ' Bedroom', ' Bedrooms', intval( $term_name ), 'Bedrooms filter - Properties list - Admin panel', 'zestylemon' );
						break;
					case 'property_bathrooms':
						$term_name .= _nx( ' Bathroom', ' Bathrooms', intval( $term_name ), 'Bathrooms filter - Properties list - Admin panel', 'zestylemon' );
						break;
					case 'property_sleeps':
						$term_name .= _nx( ' Sleep', ' Sleeps', intval( $term_name ), 'Sleeps filter - Properties list - Admin panel', 'zestylemon' );
						break;
					default:
						break;
				}
			}
		}
	}
	return $term_name;
}

// Apply the custom filters "Property ID, Price" to "All properties" page
add_action( 'pre_get_posts', 'zesty_lemon_apply_properties_admin_filters' );
function zesty_lemon_apply_properties_admin_filters( $query ) {
	if ( is_admin() && $query->is_main_query() && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == 'property' ) {
		$property_id        = isset( $_GET[ 'property_id' ] ) ? sanitize_text_field( $_GET[ 'property_id' ] ) : null;
		$property_price_min = isset( $_GET[ 'property_price_min' ] ) ? sanitize_text_field( $_GET[ 'property_price_min' ] ) : 0;
		$property_price_max = isset( $_GET[ 'property_price_max' ] ) ? sanitize_text_field( $_GET[ 'property_price_max' ] ) : 0;
		$property_price_min = floatval( $property_price_min );
		$property_price_max = floatval( $property_price_max );
		$meta_query = array();
		$meta_query['property_price'] = array(
			'relation' => 'OR',
			array(
				'key'     => 'property_price',
				'value'   => array('0', ''),
				'compare' => 'IN',
			),
		);
		if ( ! empty( $property_price_min ) && ! empty( $property_price_max ) ) {
			$meta_query['property_price'][] = array(
				'key'     => 'property_price',
				'value'   => array( floatval( $property_price_min ), floatval( $property_price_max ) ),
				'type'    => 'numeric',
				'compare' => 'BETWEEN',
			);
		} elseif ( ! empty( $property_price_min ) ) {
			$meta_query['property_price'][] = array(
				'key'     => 'property_price',
				'value'   => floatval( $property_price_min ),
				'type'    => 'numeric',
				'compare' => '>=',
			);
		} elseif ( ! empty( $property_price_max ) ) {
			$meta_query['property_price'][] = array(
				'key'     => 'property_price',
				'value'   => floatval( $property_price_max ),
				'type'    => 'numeric',
				'compare' => '<=',
			);
		} else {
			unset( $meta_query['property_price'] );
		}
		if ( ! empty( $property_id ) ) {
			$meta_query['property_id'] = array(
				'key'     => 'property_id',
				'value'   => $property_id,
				'compare' => 'LIKE',
			);
		}
		if ( ! empty( $meta_query ) ) {
			if ( count( $meta_query ) ) {
				$meta_query['relation'] = 'AND';
			}
			$query->set( 'meta_query', $meta_query );
		}
	}
}

