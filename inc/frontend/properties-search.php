<?php
add_action( 'pre_get_posts', 'zesty_lemon_properties_pre_get_posts' );
function zesty_lemon_properties_pre_get_posts( $query ) {
	// if ( ! is_admin() && $query->is_main_query() && ( $query->is_tax('property_location') || ( $query->is_single() && $query->query_vars['post_type'] ) ) ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_tax('property_location') ) {
		$statuses_not_show_on_front = get_terms( array(
			'taxonomy'   => 'property_status',
			'fields'     => 'ids',
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'     => 'show_on_frontend',
					'value'   => array('1'),
					'compare' => 'IN'
				)
			),
		) );
		$meta_query = array(
			'property_status' => array(
				'key'     => 'property_status',
				'value'   => $statuses_not_show_on_front,
				'compare' => 'IN',
			)
		);
		$query->set( 'meta_query', $meta_query );
	}
}

add_action( 'pre_get_posts', 'zesty_lemon_search_results_filter' );
function zesty_lemon_search_results_filter( $query ) {
	if ( ! is_admin() && is_search() && $query->is_main_query() ) {
		if ( isset( $_GET['properties'] ) ) {
			$query->set( 'post_type', array('property') );
			zesty_lemon_set_properties_filter_parameters( $query );
		} else {
			$query->set( 'post_type', array('post', 'page') );
		}
	}
}

add_filter('posts_clauses', 'orderby_tax_clauses', 10, 2 );
function orderby_tax_clauses( $clauses, $wp_query ) {
	global $wpdb;
	if ( ! is_admin() && ( is_array( $wp_query->query_vars['post_type'] ) && in_array( 'property', $wp_query->query_vars['post_type'] ) || is_tax('property_location') ) ) {
		$statuses_not_show_on_front = get_terms( array(
			'taxonomy'   => 'property_status',
			'fields'     => 'ids',
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'     => 'show_on_frontend',
					'value'   => array('1'),
					'compare' => 'IN'
				)
			),
		) );
		// echo '<br><br><br>***************************************<br><br><br>';
		// var_dump( $statuses_not_show_on_front );
		// echo '<br><br><br>***************************************<br><br><br>';
		// $clauses['join'] .=<<<SQL
		// 		INNER JOIN {$wpdb->term_relationships} zl_status_term_rel ON {$wpdb->posts}.ID=zl_status_term_rel.object_id
		// 		INNER JOIN {$wpdb->term_taxonomy} zl_status_term_tax ON zl_status_term_tax.term_taxonomy_id=zl_status_term_rel.term_taxonomy_id
		// 		INNER JOIN {$wpdb->terms} zl_status_terms ON zl_status_terms.term_id=zl_status_term_tax.term_id
		// 	SQL;
		// $clauses['where']   .= " AND (zl_status_term_tax.taxonomy = 'property_status' OR zl_status_terms.term_id IN (" . implode( ', ', $statuses_not_show_on_front ) . ") )";
		if ( isset( $wp_query->query_vars['orderby'] ) && in_array( $wp_query->query_vars['orderby'], array('property_bedrooms', 'property_sleeps') ) ) {
			$taxonomy = $wp_query->query_vars['orderby'];
			$clauses['join'] .=<<<SQL
					LEFT OUTER JOIN {$wpdb->term_relationships} zl_term_rel ON {$wpdb->posts}.ID=zl_term_rel.object_id
					LEFT OUTER JOIN {$wpdb->term_taxonomy} zl_term_tax ON zl_term_tax.term_taxonomy_id=zl_term_rel.term_taxonomy_id
					LEFT OUTER JOIN {$wpdb->terms} zl_terms ON zl_terms.term_id=zl_term_tax.term_id
				SQL;
			$clauses['where']   .= " AND (zl_term_tax.taxonomy = '{$taxonomy}' OR zl_term_tax.taxonomy IS NULL)";
			$clauses['groupby']  = "zl_term_rel.object_id";
			$clauses['orderby']  = "CAST(GROUP_CONCAT(zl_terms.name ORDER BY zl_terms.name ASC) as SIGNED INTEGER) ";
			$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
		}
		if ( isset( $wp_query->query_vars['search_terms'] ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
			if ( empty( $clauses['groupby'] ) ) {
				$clauses['groupby'] = "{$wpdb->posts}.ID";
			}
			$clauses['join'] .=<<<SQL
					LEFT OUTER JOIN {$wpdb->postmeta} zl_postmeta ON {$wpdb->posts}.ID=zl_postmeta.post_id
				SQL;
			$n                = ! empty( $wp_query->query_vars['exact'] ) ? '' : '%';
			$exclusion_prefix = apply_filters( 'wp_query_search_exclusion_prefix', '-' );
			foreach ( $wp_query->query_vars['search_terms'] as $term ) {
				// If there is an $exclusion_prefix, terms prefixed with it should be excluded.
				$exclude = $exclusion_prefix && ( substr( $term, 0, 1 ) === $exclusion_prefix );
				if ( $exclude ) {
					$like_op  = 'NOT LIKE';
					$andor_op = 'AND';
					$term     = substr( $term, 1 );
				} else {
					$like_op  = 'LIKE';
					$andor_op = 'OR';
				}
				$like             = $n . $wpdb->esc_like( $term ) . $n;
				$search_txt       = $wpdb->prepare( "({$wpdb->posts}.post_title $like_op %s)", $like );
				$replace_txt      = $wpdb->prepare( "(zl_postmeta.meta_key = 'property_strapline' AND zl_postmeta.meta_value $like_op %s) $andor_op ({$wpdb->posts}.post_title $like_op %s)", $like, $like );
				$replace_txt      = $wpdb->prepare( "(zl_postmeta.meta_key = 'property_zip_code' AND zl_postmeta.meta_value $like_op %s) $andor_op ({$wpdb->posts}.post_title $like_op %s)", $like, $like );
				$replace_txt      = $wpdb->prepare( "(zl_postmeta.meta_key = 'property_address' AND zl_postmeta.meta_value $like_op %s) $andor_op ({$wpdb->posts}.post_title $like_op %s)", $like, $like );
				$clauses['where'] = str_replace( $search_txt, $replace_txt, $clauses['where'] );
			}
		}
	}
    return $clauses;
}

function zesty_lemon_set_properties_filter_parameters( &$query ) {
	$tax_query  = array();
	$meta_query = array();
	$search     = isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '';
	$types      = isset( $_GET['property-type'] ) ? esc_attr( $_GET['property-type'] ) : '';
	$bedrooms   = isset( $_GET['property-bedrooms'] ) ? intval( $_GET['property-bedrooms'] ) : '';
	$sleeps     = isset( $_GET['property-sleeps'] ) ? intval( $_GET['property-sleeps'] ) : '';
	$sorting    = isset( $_GET['sorting'] ) ? esc_attr( $_GET['sorting'] ) : '';
	$locations  = isset( $_GET['property-location'] ) ? esc_attr( $_GET['property-location'] ) : '';
	$min_price  = ( isset( $_GET['property-min-price'] ) && ! empty( $_GET['property-min-price'] ) ) ? intval( $_GET['property-min-price'] ) : '';
	$max_price  = ( isset( $_GET['property-max-price'] ) && ! empty( $_GET['property-max-price'] ) ) ? intval( $_GET['property-max-price'] ) : '';
	$features   = ( isset( $_GET['features'] ) && is_array( $_GET['features'] ) ) ? array_map( 'esc_attr', $_GET['features'] ) : array();
	if ( empty( $min_price ) ) {
		$min_price = 0;
	}
	if ( empty( $max_price ) ) {
		$max_price = get_max_property_price();
	}
	if ( ! empty( $bedrooms ) ) {
		$tax_query['property_bedrooms'] = array(
			'taxonomy' => 'property_bedrooms',
			'field'    => 'slug',
			'terms'    => $bedrooms
		);
	}
	if ( ! empty( $sleeps ) ) {
		$tax_query['property_sleeps'] = array(
			'taxonomy' => 'property_sleeps',
			'field'    => 'slug',
			'terms'    => $sleeps
		);
	}
	if ( ! empty( $types ) ) {
		$types_array = explode( ',', $types );
		$types_array = array_map( 'trim', $types_array );
		$tax_query['property_type'] = array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $types_array,
			'operator' => 'IN',
		);
	}
	if ( ! empty( $locations ) ) {
		$locations_array = explode( ',', $locations );
		$locations_array = array_map( 'trim', $locations_array );
		$tax_query['property_location'] = array(
			'taxonomy' => 'property_location',
			'field'    => 'slug',
			'terms'    => $locations_array,
			'operator' => 'IN',
		);
	}
	// var_dump( $features );
	if ( ! empty( $features ) ) {
		$tax_query['property_feature'] = array(
			'taxonomy' => 'property_feature',
			'field'    => 'slug',
			'terms'    => $features,
			'operator' => 'IN',
		);
	}
	$statuses_not_show_on_front = get_terms( array(
		'taxonomy'   => 'property_status',
		'fields'     => 'ids',
		'hide_empty' => false,
		'meta_query' => array(
			array(
				'key'     => 'show_on_frontend',
				'value'   => array('1'),
				'compare' => 'IN'
			)
		),
	) );
	if ( ! empty( $statuses_not_show_on_front ) ) {
		$tax_query['property_status'] = array(
			'taxonomy' => 'property_status',
			'terms'    => $statuses_not_show_on_front,
			'operator' => 'IN',
		);
	}
	$order    = '';
	$orderby  = '';
	$meta_key = '';
	if ( ! empty( $sorting ) ) {
		switch ( $sorting ) {
			case 'price-low-to-high':
				$order    = 'ASC';
				$orderby  = 'meta_value_num';
				$meta_key = 'property_price';
				break;
			case 'price-high-to-low':
				$order    = 'DESC';
				$orderby  = 'meta_value_num';
				$meta_key = 'property_price';
				break;
			case 'sleeps-low-to-high':
				$order   = 'ASC';
				$orderby = 'property_sleeps';
				break;
			case 'sleeps-high-to-low':
				$order   = 'DESC';
				$orderby = 'property_sleeps';
				break;
			case 'bedrooms-low-to-high':
				$order   = 'ASC';
				$orderby = 'property_bedrooms';
				break;
			case 'bedrooms-high-to-low':
				$order   = 'DESC';
				$orderby = 'property_bedrooms';
				break;
			default:
				break;
		}
	}
	if ( ! empty( $order ) ) {
		$query->set( 'order', $order );
	}
	if ( ! empty( $orderby ) ) {
		$query->set( 'orderby', $orderby );
	}
	if ( ! empty( $meta_key ) ) {
		$query->set( 'meta_key', $meta_key );
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( count( $tax_query ) > 0 ) {
		$query->set( 'tax_query', $tax_query );
	}
	$meta_query['property_price'] = array(
		'key'     => 'property_price',
		'value'   => array( floatval( $min_price ), floatval( $max_price ) ),
		'type'    => 'numeric',
		'compare' => 'BETWEEN',
	);
	$meta_query['property_no_price'] = array(
		'key'     => 'property_price',
		'value'   => '0',
		'compare' => '=',
	);
	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'OR';
	}
	if ( count( $meta_query ) > 0 ) {
		$query->set( 'meta_query', $meta_query );
	}
}
