<?php

add_filter('post_type_link', 'zesty_lemon_remove_cpt_slug', 10, 2);
function zesty_lemon_remove_cpt_slug( $post_link, $post ) {
	if ( 'property' === $post->post_type && 'publish' === $post->post_status ) {
		$terms = get_the_terms( $post->ID, 'property_location' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$parents   = array_map( 'zesty_lemon_get_term_parent', $terms );
			$new_terms = array();
			foreach ( $terms as $key => $term ) {
				if ( ! in_array( $term->term_id, $parents, true ) ) {
					$new_terms[] = $term;
				}
			}
			$term_obj  = reset( $new_terms );
			$term_slug = $term_obj->slug;
			if ( isset( $term_obj->parent ) && $term_obj->parent ) {
				$term_slug = zesty_lemon_get_taxonomy_parents_slug( $term_obj->parent, 'property_location', '/', true ) . $term_slug;
			}
		}
		if ( isset( $term_slug ) ) {
			$post_link = str_replace( '/properties/', '/' . $term_slug . '/', $post_link );
		} else {
			$post_link = str_replace('/properties/', '/', $post_link);
		}
	}
	return $post_link;
}

function zesty_lemon_get_term_parent( $term ) {
	if ( isset( $term->parent ) && $term->parent > 0 ) {
		return $term->parent;
	}
}

function zesty_lemon_get_taxonomy_parents_slug( $term, $taxonomy = 'category', $separator = '/', $nicename = false, $visited = array() ) {
	$chain  = '';
	$parent = get_term( $term, $taxonomy );
	if ( is_wp_error( $parent ) ) {
		return $parent;
	}
	if ( $nicename ) {
		$name = $parent->slug;
	} else {
		$name = $parent->name;
	}
	if ( $parent->parent && ( $parent->parent !== $parent->term_id ) && ! in_array( $parent->parent, $visited, true ) ) {
		$visited[] = $parent->parent;
		$chain     .= zesty_lemon_get_taxonomy_parents_slug( $parent->parent, $taxonomy, $separator, $nicename, $visited );
	}
	$chain .= $name . $separator;
	return $chain;
}

add_filter('term_link', 'zesty_lemon_remove_taxonomy_slug', 10, 2);
function zesty_lemon_remove_taxonomy_slug( $term_link, $term ) {
	if ( 'property_location' === $term->taxonomy ) {
		$term_link = str_replace('/properties/property_location/', '/', $term_link);
		$term_link = str_replace('/property_location/', '/', $term_link);
	}
	return $term_link;
}

/*
	Code commented by NisarAhmed dated 3-1-2023
	Comment out 'posts_clauses' filter as this filer is causing 404 error for many properties pages.
*/
//add_filter('posts_clauses', 'zesty_lemon_query_clauses', 20, 2);
function zesty_lemon_query_clauses($pieces, $query) {
    global $wpdb;
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'property' && is_singular() && isset( $query->query_vars['property_location'] ) && ! empty( $query->query_vars['property_location'] ) ) {
			$property_location = get_term_by( 'slug', $query->query_vars['property_location'], 'property_location' );
			if ( ! empty( $property_location ) ) {
				$pieces['join'] .= " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id AND $wpdb->term_relationships.term_taxonomy_id IN ($property_location->term_id)) ";
			}
		}
	}
    return $pieces;
}

add_action('init', 'zesty_lemon_add_properties_rewrites');
function zesty_lemon_add_properties_rewrites() {
	flush_rewrite_rules();
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$page_slug   = '';
	$page_query  = '';
	if ( strpos( $current_url, '/page/' ) !== false ) {
		$page_slug   = substr( $current_url, strpos( $current_url, '/page/' ) );
		$page_query  = '&paged=' . substr( $page_slug, 6 );
		$current_url = substr( $current_url, 0, strpos( $current_url, '/page/' ) );
	}
	if ( '/' == substr( $current_url, strlen( $current_url ) - 1 ) ) {
		$current_url = substr( $current_url, 0, strlen( $current_url ) - 1 );
	}
	$url_array = explode( '/', $current_url );
	$last_slug = end( $url_array );
	$last_slug = explode( '?', $last_slug );
	$last_slug = $last_slug[0];
	$property_location = get_term_by( 'slug', $last_slug, 'property_location' );
	if( $property_location ) {
		add_rewrite_rule(
			'^(.+?)?(' . $last_slug . ')' . $page_slug . '$',
			'index.php?property_location=' . $last_slug . $page_query,
			'top'
		);
	} else {
		$property = get_page_by_path( $last_slug, OBJECT, 'property' );
		if( $property && isset( $property->post_type ) && $property->post_type == 'property' ) {
			$current_url_temp = str_replace( '/' . $last_slug, '', $current_url );
			$url_array        = explode( '/', $current_url_temp );
			$location_slug    = end( $url_array );
			$location_slug    = explode( '?', $location_slug );
			$location_slug    = $location_slug[0];
			if ( ! empty( $location_slug ) ) {
				$location_slug = 'property_location=' . $location_slug . '&';
			}
			add_rewrite_rule(
				'^(.+?)?(' . $last_slug . ')$',
				'index.php?' . $location_slug . 'property=' . $last_slug,
				'top'
			);
		}
	}
}

add_filter('wp_unique_post_slug', 'b3_non_unique_property_slug', 10, 6);
function b3_non_unique_property_slug( $slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug ) {
    if ( $post_type == 'property' ) {
		$property_locations = array();
        $property_locations_ids = array();
		$admin_level = '';
		for ( $i = 5; $i >= 1 && empty( $admin_level ); $i-- ) { 
			$admin_level = get_field('administrative_level_' . $i, $post_ID);
		}
		if ( ! empty( $admin_level ) ) {
			$admin_level = get_term( $admin_level );
			if ( ! empty( $admin_level ) && ! is_wp_error( $admin_level ) ) {
				$property_locations = array( $admin_level->term_id );
			}	
		}
		if ( empty( $property_locations ) ) {
			$property_locations = get_the_terms( $post_ID, 'property_location' );
			if ( empty($property_locations ) ) {
				return $slug;
			}
			
			foreach ( $property_locations as $property_location ) {
				$property_locations_ids[] = $property_location->term_id;
			}
		}
        $args = array(
            'post_type'     => 'property',
            'post_status'   => 'any',
            'post__not_in'  => array( $post_ID ),
            'post_name__in' => array( $original_slug ),
            'tax_query'     => array(
                array(
                    'taxonomy' => 'property_location',
                    'field'    => 'id',
                    'terms'    => $property_locations_ids,
                    'operator' => 'IN'
                ),
            )
        );
        $properties_with_same_slug = new WP_Query( $args );
        if ( intval( $properties_with_same_slug->found_posts ) <= 0 ) {
            return $original_slug;
        }
    }
    return $slug;
}