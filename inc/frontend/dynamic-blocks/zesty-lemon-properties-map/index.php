<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertiesMap;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/properties-map", array(
		"attributes" => array(
			"headline" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"headlineTag" => array(
				"type" 		=> "string",
				"default" 	=> "h2"
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;
	ob_start();
	$map_query_vars = $wp_query->query_vars;
	$map_query_vars['posts_per_page'] = -1;
	$map_query_vars['nopaging'] = true;
	unset( $map_query_vars['paged'] );
	unset( $map_query_vars['taxonomy'] );
	unset( $map_query_vars['term'] );
	$map_query   = new \WP_Query( $map_query_vars );
	$map_markers = array();
	$map_class   = 'full-width';
	if ( $map_query->have_posts() ) {
		while ( $map_query->have_posts() ) {
			$map_query->the_post();
			$address = get_field('property_address');
			if ( isset( $address['lat'] ) && ! empty( $address['lat'] ) && isset( $address['lng'] ) && ! empty( $address['lng'] ) ) {
				$map_markers[] = array(
					'title'     => get_the_title(),
					'ID'        => get_the_ID(),
					'url'       => get_permalink(),
					'price'     => get_formated_price( get_field('property_price') ),
					'address'   => $address['address'],
					'strapline' => wp_slash( get_field('property_strapline') ),
					'lat'       => $address['lat'],
					'lng'       => $address['lng'],
					'thumb'     => get_the_post_thumbnail_url( get_the_ID(), 'property-thumb' )
				);
			}
		}
		wp_reset_postdata();
	}
	?>
		<div class="zl-block-container">
			<div class="zl-properties-map-wrapper">
				<?php include('map-html.php'); ?>
			</div>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
