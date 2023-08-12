<?php

namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyOtherProperties;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-other-properties", array(
		"attributes" => array(
			"headline_tag" => array(
				"type" 		=> "string",
				"default" 	=> "h2"
			),
			"grid_style" => array(
				"type" 		=> "string",
				"default" 	=> "list"
			),
			"properties_count" => array(
				"type" 		=> "number",
				"default" 	=> "8"
			),
			"properties_order" => array(
				"type" 		=> "string",
				"default" 	=> "global"
			),
			"properties_display" => array(
				"type" 		=> "string",
				"default" 	=> "all"
			),
			"properties_status" => array(
				"type" 		=> "string",
				"default" 	=> "all"
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;
	$tax_query  = array();
	$meta_query = array();
	$grid_style = 'zl-list-view';
	if ( isset( $attributes['grid_style'] ) && ! empty( $attributes['grid_style'] ) ) {
		$attributes['grid_style'] = esc_attr( $attributes['grid_style'] );
		if ( $attributes['grid_style'] == 'grid' ) {
			$grid_style = 'zl-grid-view';
		}
	}
	if ( ! isset( $attributes['properties_count'] ) || empty( $attributes['properties_count'] ) ) {
		$attributes['properties_count'] = 8;
	} else {
		$attributes['properties_count'] = intval( $attributes['properties_count'] );
	}
	if ( ! isset( $attributes['properties_order'] ) || empty( $attributes['properties_order'] ) ) {
		$attributes['properties_order'] = 'global';
	}
	if ( ! isset( $attributes['properties_display'] ) || empty( $attributes['properties_display'] ) ) {
		$attributes['properties_display'] = 'all';
	}
	$query_vars = array(
		'post_type'      => 'property',
		'posts_per_page' => $attributes['properties_count'],
		'post__not_in' 	 => array( get_the_ID() ),
	);
	switch ( $attributes['properties_order'] ) {
		case 'date_desc':
			$query_vars['orderby'] = 'date';
			$query_vars['order']   = 'DESC';
			break;
		case 'date_asc':
			$query_vars['orderby'] = 'date';
			$query_vars['order']   = 'ASC';
			break;
		case 'title_asc':
			$query_vars['orderby'] = 'title';
			$query_vars['order']   = 'ASC';
			break;
		case 'title_desc':
			$query_vars['orderby'] = 'title';
			$query_vars['order']   = 'DESC';
			break;
		case 'rand':
			$query_vars['orderby'] = 'rand';
			break;
		case 'global':
		default:
			break;
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
	switch ( $attributes['properties_display'] ) {
		case 'featured':
			$meta_query['featured_property'] = array(
				'key'     => 'featured_property',
				'value'   => '1',
				'compare' => '=',
			);
			break;
		case 'status':
			if ( isset( $attributes['properties_status'] ) ) {
				$attributes['properties_status'] = esc_attr( $attributes['properties_status'] );
			}
			if ( ! isset( $attributes['properties_status'] ) || empty( $attributes['properties_status'] ) ) {
				$attributes['properties_status'] = 'all';
			}
			if ( $attributes['properties_status'] != 'all' ) {
				$tax_query['property_status'] = array(
					'relation' => 'AND',
					$tax_query['property_status'],
					array(
						'taxonomy' => 'property_status',
						'field'    => 'slug',
						'terms'    => $attributes['properties_status'],
						'operator' => 'IN',
					),
				);
			}
			break;
		case 'all':
		default:
			break;
	}
	$sleeps_term_id = get_field('property_sleeps');
	$sleeps = 0;
	if ( ! empty( $sleeps_term_id ) ) {
		$sleeps_term = get_term( $sleeps_term_id );
		if ( ! empty( $sleeps_term ) ) {
			$sleeps = $sleeps_term->name;
		}
	}
	if ( ! empty( $sleeps ) ) {
		$tax_query['property_sleeps'] = array(
			'taxonomy' => 'property_sleeps',
			'terms'    => array( $sleeps_term_id ),
			'operator' => 'IN',
		);
		if ( intval( $sleeps ) % 2 > 0 ) {
			$sleeps_plus_1 = intval( $sleeps ) + 1;
			$sleeps_plus_1_term = get_term_by( 'slug', $sleeps_plus_1, 'property_sleeps' );
			if ( ! empty( $sleeps_plus_1_term ) ) {
				$tax_query['property_sleeps'] = array(
					'relation'               => 'OR',
					'property_sleeps'        => $tax_query['property_sleeps'],
					'property_sleeps_plus_1' => array(
						'taxonomy' => 'property_sleeps',
						'terms'    => array( $sleeps_plus_1_term->term_id ),
						'operator' => 'IN',
					)
				);
			}
		}
	}
	$matching_admin_level = intval( get_field( 'other_props_admin_level', 'option' ) );
	if ( empty( $matching_admin_level ) ) {
		$matching_admin_level = 1;
	}
	$admin_level = '';
	for ( $i = $matching_admin_level; $i >= 1 && empty( $admin_level ); $i-- ) { 
		$admin_level = get_field('administrative_level_' . $i);
	}
	if ( ! empty( $admin_level ) ) {
		$admin_level = get_term( $admin_level );
		if ( ! empty( $admin_level ) && ! is_wp_error( $admin_level ) ) {
			$tax_query['property_location'] = array(
				'taxonomy' => 'property_location',
				'terms'    => array( $admin_level->term_id ),
				'operator' => 'IN',
			);
		}
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( count( $tax_query ) > 0 ) {
		$query_vars['tax_query'] = $tax_query;
	}
	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'OR';
	}
	if ( count( $meta_query ) > 0 ) {
		$query_vars['meta_query'] = $meta_query;
	}

	$properties = new \WP_Query( $query_vars );
	ob_start();
	if ( $properties->have_posts() ) {
		echo '<div class="zl-block-container">';
		echo '<div class="zesty-lemon-properties-entries ' . $grid_style . '">';
		while ( $properties->have_posts() ) {
			$properties->the_post();
			$post_classes = get_post_class( '', get_the_ID() );
			echo '<article id="post-' . get_the_ID() . '" class="' . esc_attr( implode( ' ', $post_classes ) ) . '">';
			include(__DIR__ . '/../zesty-lemon-property-archive-item/zesty-lemon-property-archive-item-html.php');
			echo '</article>';
		}
		echo '</div>';
		echo '</div>';
		wp_reset_postdata();
	}
	$output = ob_get_clean();
	return $output;
}
