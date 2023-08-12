<?php
add_action( 'rest_api_init', 'zesty_lemon_rest_api_init' );
function zesty_lemon_rest_api_init() {
	$namespace = 'zestylemon/v1';
	register_rest_route( $namespace, '/locations', array(
		'methods'  => 'GET',
		'callback' => 'zesty_lemon_rest_api_get_locations',
	) );
	register_rest_route( $namespace, '/stand-out-features', array(
		'methods'  => 'GET',
		'callback' => 'zesty_lemon_rest_api_get_stand_out_features',
	) );
	register_rest_route( $namespace, '/not-stand-out-features', array(
		'methods'  => 'GET',
		'callback' => 'zesty_lemon_rest_api_get_not_stand_out_features',
	) );
	// register_rest_route( $namespace, '/get-repeater-sub-fields/(?P<id>\w+)', array(
	// 	'methods'  => 'GET',
	// 	'callback' => 'zesty_lemon_rest_api_get_repeater_sub_fields',
	// ) );
	register_rest_route( $namespace, '/icons-library', array(
		'methods'  => 'GET',
		'callback' => 'zesty_lemon_rest_api_get_icons_library',
	) );
}

function zesty_lemon_rest_api_get_locations() {
	$return = array();
	$locations = get_terms( array(
		'taxonomy'   => 'property_location',
		'hide_empty' => false,
	) );
	foreach ( $locations as $location_obj ) {
		$return[] = array(
			'slug' => $location_obj->slug,
			'name' => $location_obj->name,
		);
	}
	return new WP_REST_Response($return, 200);
}

function zesty_lemon_rest_api_get_stand_out_features() {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return;
	}
	$return = array();
	$args = array(
		'taxonomy'   => 'property_feature',
		'hide_empty' => false,
		'meta_query' => array(
			array(
				'key'       => 'stand_out_feature',
				'value'     => '1',
				'compare'   => '='
			),
		),
	);
	$features = get_terms( $args );
	foreach ( $features as $feature_term ) {
		$connect_to_extra_taxonomy = get_field( 'connect_to_extra_taxonomy', 'property_feature_' . $feature_term->term_id );
		if ( is_null( $connect_to_extra_taxonomy ) || empty( $connect_to_extra_taxonomy ) ) {
			$connect_to_extra_taxonomy = false;
		}
		$return[] = array(
			'slug' => $feature_term->slug,
			'name' => $feature_term->name . ' (' . $feature_term->count . ')',
			'extra_tax' => $connect_to_extra_taxonomy,
		);
	}
	return new WP_REST_Response($return, 200);
}

function zesty_lemon_rest_api_get_not_stand_out_features() {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return;
	}
	$return = array();
	$args = array(
		'taxonomy'   => 'property_feature',
		'hide_empty' => false,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'       => 'stand_out_feature',
				'value'     => 'not exists',
				'compare'   => 'NOT EXISTS'
			),
			array(
				'key'       => 'stand_out_feature',
				'value'     => '0',
				'compare'   => '='
			),
			array(
				'key'       => 'stand_out_feature',
				'value'     => [''],
				'compare'   => 'IN'
			),
		),
	);
	$features = get_terms( $args );
	foreach ( $features as $feature_term ) {
		$return[] = array(
			'slug' => $feature_term->slug,
			'name' => $feature_term->name . ' (' . $feature_term->count . ')',
		);
	}
	return new WP_REST_Response($return, 200);
}

function zesty_lemon_rest_api_get_icons_library() {
	$return = array();
	$zl_icons_library = get_field( 'zl_icons_library', 'options' );
	foreach ( $zl_icons_library as $icon ) {
		$return[] = array(
			'icon'           => $icon['icon_name'],
			'icon_slug'      => slugify( $icon['icon_name'] ),
			'use_svg'        => $icon['image_svg_code'],
			'icon_image'     => $icon['icon_image'],
			'icon_image_url' => wp_get_attachment_image_url( $icon['icon_image'], 'full' ),
			'icon_svg'       => $icon['icon_svg'],
		);
	}
	return new WP_REST_Response($return, 200);
}

// function zesty_lemon_rest_api_get_repeater_sub_fields( $request ) {
// 	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
// 		return;
// 	}
// 	$return    = array();
// 	$field     = esc_attr( $request['id'] );
// 	$field_key = get_field_key_by_field_name( $field );
// 	$field_obj = get_field_object( $field_key );
// 	if ( isset( $field_obj['sub_fields'] ) && ! empty( $field_obj['sub_fields'] ) ) {
// 		foreach ( $field_obj['sub_fields'] as $sub_field ) {
// 			$return[] = array(
// 				'slug' => $sub_field['name'],
// 				'name' => $sub_field['label'],
// 			);
// 		}
// 	}
// 	return new WP_REST_Response($return, 200);
// }

// function get_field_key_by_field_name( $name ) {
// 	global $wpdb;
// 	$field_name = $wpdb->get_var(
// 		$wpdb->prepare(
// 			"
// 			SELECT post_name
// 			FROM {$wpdb->posts}
// 			WHERE
// 			post_excerpt = %s
// 			LIMIT 1
// 			",
// 			esc_attr( $name )
// 		)
// 	);
// 	return esc_attr( $field_name );
// }
