<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertiesArchiveTitle;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/properties-archive-title", array(
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
	$title       = __('Properties', 'zestylemon');
	$description = '';
	if ( ! is_search() ) {
		$taxonomy    = get_query_var('taxonomy');
		$term        = get_query_var('term');
		if ( ! empty( $taxonomy ) && ! empty( $term ) ) {
			$term_obj     = get_term_by( 'slug', $term, $taxonomy );
			$title        = $term_obj->name;
			if ( function_exists('get_field') ) {
				$title_prefix = get_field( 'location_name_prefix', 'property_location_' . $term_obj->term_id );
				if ( empty( $title_prefix ) && function_exists('acf_get_field') ) {
					$title_prefix = acf_get_field('location_name_prefix')['default_value'];
				}
				$title = trim( esc_attr( $title_prefix ) ) . ' ' . $title;
			}
			$description  = wpautop( $term_obj->description );
		}
	}
	ob_start();
	?>
		<div class="zl-block-container">
			<div class="zl-properties-archive-title-wrapper">
				<div class="zl-properties-archive-title-inner-wrapper">
					<h1 class="zl-properties-archive-title"><?php echo esc_attr( $title ); ?></h1>
					<p class="zl-properties-archive-found-properties"><?php echo $wp_query->found_posts; ?> properties found</p>
				</div>
				<div class="zl-advanced-search-btn-wrapper">
					<button class="zl-advanced-search-btn">Modify search</button>
					<?php get_template_part( 'inc/frontend/dynamic-blocks/zesty-lemon-search-box-block/advanced-search-form' ); ?>
				</div>
			</div>
			<?php if ( ! empty( $description ) ) { ?>
				<div class="property-location-description">
					<?php echo $description; ?>
				</div>
			<?php } ?>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
