<?php

namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyHero;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-hero", array(
		"attributes" => array(
			"headline_tag" => array(
				"type" 		=> "string",
				"default" 	=> "h1"
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
	ob_start();
	include('single-property-hero-html.php');
	$output = ob_get_clean();
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		wp_reset_postdata();
	}
	return $output;
}
