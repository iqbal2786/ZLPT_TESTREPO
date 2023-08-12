<?php

add_filter('acf/fields/taxonomy/query/name=administrative_level_1', 'zesty_lemon_acf_load_administrative_levels_field_choices', 10, 3);
add_filter('acf/fields/taxonomy/query/name=administrative_level_2', 'zesty_lemon_acf_load_administrative_levels_field_choices', 10, 3);
add_filter('acf/fields/taxonomy/query/name=administrative_level_3', 'zesty_lemon_acf_load_administrative_levels_field_choices', 10, 3);
add_filter('acf/fields/taxonomy/query/name=administrative_level_4', 'zesty_lemon_acf_load_administrative_levels_field_choices', 10, 3);
add_filter('acf/fields/taxonomy/query/name=administrative_level_5', 'zesty_lemon_acf_load_administrative_levels_field_choices', 10, 3);
function zesty_lemon_acf_load_administrative_levels_field_choices( $args, $field, $post_id ) {
	$parent = isset( $_POST['parent'] ) ? intval( $_POST['parent'] ) : 0;
	$args['parent'] = $parent;
    return $args;
}

function zesty_lemon_set_meta_query_administrative_level_1( $args ) {
	$administrative_level_1 = isset( $_POST['administrative_level_1'] ) ? intval( $_POST['administrative_level_1'] ) : 0;
	if ( ! empty( $administrative_level_1 ) ) {
		$args['meta_query'] = array(
			array(
				'key'     => 'administrative_level_1',
				'value'   => $administrative_level_1,
				'compare' => '=',
			)
		);
	}
	return $args;
}

add_filter('acf/fields/taxonomy/query/name=nearest_airport', 'zesty_lemon_acf_load_nearest_facilities_field_choices', 10, 3);
add_filter('acf/fields/taxonomy/query/name=nearest_station', 'zesty_lemon_acf_load_nearest_facilities_field_choices', 10, 3);
add_filter('acf/fields/taxonomy/query/name=nearest_beach', 'zesty_lemon_acf_load_nearest_facilities_field_choices', 10, 3);
function zesty_lemon_acf_load_nearest_facilities_field_choices( $args, $field, $post_id ) {
	return zesty_lemon_set_meta_query_administrative_level_1( $args );
}

add_filter('acf/fields/taxonomy/result/name=nearest_airport', 'zesty_lemon_acf_load_nearest_airport_field_result', 10, 4);
function zesty_lemon_acf_load_nearest_airport_field_result( $text, $term, $field, $post_id ) {
	$iata = get_field( 'iata_airport_code', 'nearest_airport_' . $term->term_id );
	return $text . ' (' . $iata . ')';
}

add_filter( 'terms_clauses', 'zesty_lemon_nearest_airport_terms_clauses', 10, 3 );
function zesty_lemon_nearest_airport_terms_clauses( $clauses, $taxonomies, $args ) {
	if ( is_admin() && isset( $_POST['action'] ) && $_POST['action'] == 'acf/fields/taxonomy/query' && isset( $_POST['field_key'] ) && isset( $_POST['s'] ) && ! empty( $_POST['s'] ) ) {
		$acf_filed = get_field_object( esc_attr( $_POST['field_key'] ) );
		if ( $acf_filed['name'] == 'nearest_airport' ) {
			global $wpdb;
			$like = '%' . $wpdb->esc_like( esc_attr( $_POST['s'] ) ) . '%';
			$term_meta_search = $wpdb->prepare( "tm.meta_key = 'iata_airport_code' AND tm.meta_value LIKE %s", $like );
			$clauses['join'] .= ' INNER JOIN wp_termmeta AS tm ON t.term_id = tm.term_id';
			$clauses['where'] = str_replace( 't.name LIKE', $term_meta_search . ") OR (t.name LIKE", $clauses['where'] );
		}
	}
	return $clauses;
}

add_filter( 'acf/load_value/name=property_features', 'zesty_lemon_acf_load_value', 10, 3 );
function zesty_lemon_acf_load_value( $value, $post_id, $field ) {
	if ( $field['type'] == 'repeater' ) {
		$sub_fields = array();
		foreach ( $field['sub_fields'] as $sub_field ) {
			$sub_fields[ $sub_field['name'] ] = $sub_field['key'];
		}
		$property_features_categories_temp = get_terms( array(
			'taxonomy'   => 'property_features_category',
			'hide_empty' => false,
		) );
		$property_features_categories = array();
		foreach ( $property_features_categories_temp as $category ) {
			$property_features_categories[ $category->term_id ] = $category;
		}
		if ( empty( $value ) ) {
			$value = array();
		} else {
			foreach ( $value as $fields_key => $fields ) {
				if ( isset( $property_features_categories[ $fields[ $sub_fields[ 'property_features_category' ] ] ] ) ) {
					$value[ $fields_key ][ $sub_fields[ 'property_features' ] ]['category'] = $fields[ $sub_fields[ 'property_features_category' ] ];
					$value[ $fields_key ][ $sub_fields[ 'property_features' ] ]['category-name'] = $property_features_categories[ $fields[ $sub_fields[ 'property_features_category' ] ] ]->name;
					unset( $property_features_categories[ $fields[ $sub_fields[ 'property_features_category' ] ] ] );
				} else {
					unset( $value[ $fields_key ] );
				}
			}
		}
		foreach ( $property_features_categories as $category_id => $category ) {
			$fields = array(
				$sub_fields[ 'property_features_category' ] => $category_id,
				$sub_fields[ 'property_features' ] => array(
					'category' => $category_id,
					'category-name' => $category->name,
				),
			);
			$value[] = $fields;
		}
		$fields = array(
			$sub_fields[ 'property_features_category' ] => 'dummy',
			$sub_fields[ 'property_features' ] => array(
				'category' => 'dummy',
				'category-name' => __( 'Other features', 'zestylemon' ),
			),
		);
		$value[] = $fields;
	}
	return $value;
}

add_filter( 'acf/fields/taxonomy/wp_list_categories/name=property_features', 'zesty_lemon_acf_load_property_features_field_choices', 10, 2 );
function zesty_lemon_acf_load_property_features_field_choices( $args, $field ) {
	if ( isset( $field['value']['category'] ) && ! empty( $field['value']['category'] ) ) {
		if ( $field['value']['category'] == 'dummy') {
			// $args['meta_key']     = 'feature_category';
			// $args['meta_value']   = 'not exists';
			// $args['meta_compare'] = 'NOT EXISTS';
			$args['meta_query']['feature_category'] = array(
				'relation' => 'OR',
				array(
					'key'       => 'feature_category',
					'value'     => 'not exists',
					'compare'   => 'NOT EXISTS'
				),
				array(
					'key'       => 'feature_category',
					'value'     => '0',
					'compare'   => '='
				),
				array(
					'key'       => 'feature_category',
					'value'     => [''],
					'compare'   => 'IN'
				),
			);
			$args['walker']       = new ACF_Taxonomy_Field_Custom_Walker( $field );
		} else {
			$category = intval( $field['value']['category'] );
			if ( $category ) {
				$args['meta_key']     = 'feature_category';
				$args['meta_value']   = $category;
				$args['meta_compare'] = '=';
				$args['walker']       = new ACF_Taxonomy_Field_Custom_Walker( $field );
			}
		}
	}
	return $args;
}

add_filter('acf/prepare_field/name=property_features', 'zesty_lemon_acf_prepare_property_features_field');
function zesty_lemon_acf_prepare_property_features_field( $field ) {
	if ( $field['type'] == 'taxonomy' ) {
		if ( isset( $field['value']['category-name'] ) ) {
			$field['label'] = $field['value']['category-name'];
		}
	}
	return $field;
}

add_filter('acf/prepare_field/name=property_features_category', 'zesty_lemon_acf_property_features_category_field_dummy_not_required');
function zesty_lemon_acf_property_features_category_field_dummy_not_required( $field ) {
	if ( isset( $field['value'] ) && ! empty( $field['value'] ) && $field['value'] == 'dummy' ) {
		$field['required'] = 0;
	}
	return $field;
}

add_action('acf/validate_save_post', 'my_acf_validate_save_post');
function my_acf_validate_save_post() {
	$acf_groups = acf_get_field_groups();
	$acf_group_fields = array();
	foreach ( $acf_groups as $acf_group ) {
		if ( isset( $acf_group['location'][0][0]['param'] ) && 'post_type' == $acf_group['location'][0][0]['param'] && isset( $acf_group['location'][0][0]['value'] ) && 'property' == $acf_group['location'][0][0]['value'] ) {
			$acf_group_fields = acf_get_fields( $acf_group['key'] );
		}
	}
	$property_features_repeater_key = '';
	$property_features_category_key = '';
	foreach ( $acf_group_fields as $acf_field ) {
		if ( $acf_field['name'] == 'property_features' && $acf_field['type'] == 'repeater' ) {
			$property_features_repeater_key = $acf_field['key'];
			foreach ( $acf_field['sub_fields'] as $sub_field ) {
				if ( $sub_field['name'] == 'property_features_category' ) {
					$property_features_category_key = $sub_field['key'];
				}
			}
			break;
		}
	}
	foreach ( $_POST['acf'][ $property_features_repeater_key ] as $property_features_posted_data_key => $property_features_posted_data ) {
		if ( empty( $property_features_posted_data[ $property_features_category_key ] ) ) {
			$_POST['acf'][ $property_features_repeater_key ][ $property_features_posted_data_key ][ $property_features_category_key ] = 'dummy';
		}
	}
}

add_filter('acf/validate_value/name=property_features_category', 'zesty_lemon_acf_validate_value', 10, 4);
function zesty_lemon_acf_validate_value( $valid, $value, $field, $input_name ) {
	return true;
}

add_filter( 'acf/field_wrapper_attributes', 'zesty_lemon_acf_hide_property_features_categories_field', 10, 2 );
function zesty_lemon_acf_hide_property_features_categories_field( $wrapper, $field ) {
	$screen = get_current_screen();
	if ( is_admin() && $screen->id == 'property' && $field['type'] == 'taxonomy' ) {
		if ( ! isset( $wrapper['style'] ) ) {
			$wrapper['style'] = '';
		}
		if ( $field['taxonomy'] == 'property_features_category' ) {
			$wrapper['style'] .= ' display:none;';
		}
		$property_features = get_transient( 'property_features_connected_to_extra_taxonomy' );
		if ( empty( $property_features ) ) {
			$property_features = get_terms( array(
				'taxonomy'     => 'property_feature',
				'hide_empty'   => false,
				'meta_key'     => 'connect_to_extra_taxonomy',
				'meta_compare' => 'EXISTS',
			) );
			foreach ( $property_features as $feature_key => $feature ) {
				$extra_taxonomy = get_field( 'connect_to_extra_taxonomy', 'property_feature_' . $feature->term_id );
				$property_features[ $feature_key ]->extra_taxonomy = $extra_taxonomy;
			}
			set_transient( 'property_features_connected_to_extra_taxonomy', $property_features );
		}
		foreach ( $property_features as $feature ) {
			if ( $field['taxonomy'] == $feature->extra_taxonomy ) {
				if ( ! has_term( $feature->term_id, $feature->taxonomy ) ) {
					$wrapper['style'] .= ' display:none;';
				}
				break;
			}
		}
	}
	return $wrapper;
}

add_filter('acf/prepare_field/name=connect_to_extra_taxonomy', 'acf_load_connect_to_extra_taxonomy_field_choices');
function acf_load_connect_to_extra_taxonomy_field_choices( $field ) {
    $field['choices'] = array();
	$taxonomies = get_object_taxonomies('property', 'objects');
	foreach ( $taxonomies as $taxonomy_key => $taxonomy ) {
		$field['choices'][ $taxonomy_key ] = $taxonomy->label;
	}
    return $field;
}


