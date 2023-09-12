<?php

namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyMap;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-map", array(
		"attributes" => array(
			"full_width" => array(
				"type" 		=> "boolean",
				"default" 	=> true
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		$args = array(
			'post_type' => 'property'
		);
		$properties = new \WP_Query( $args );
		if ( $properties->have_posts() ) {
			$properties->the_post();
		}
	}
	$address = get_field('property_address');
	$map_class = '';
	if ( isset( $attributes['full_width'] ) && $attributes['full_width'] ) {
		$map_class = 'full-width';
	}
	ob_start();
	if ( ! empty( $address ) ) {
		$map_markers = array(
			array(
				'title'     => get_the_title(),
				'ID'        => get_the_ID(),
				'url'       => get_permalink(),
				'address'   => $address['address'],
				'strapline' => wp_slash( get_field('property_strapline') ),
				'lat'       => $address['lat'],
				'lng'       => $address['lng'],
				'thumb'     => get_the_post_thumbnail_url( get_the_ID(), 'property-thumb' )
			),
		);
		include(__DIR__ . '/../zesty-lemon-properties-map/map-html.php');
	}
	$output = ob_get_clean();
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		wp_reset_postdata();
	}
	return $output;
}
