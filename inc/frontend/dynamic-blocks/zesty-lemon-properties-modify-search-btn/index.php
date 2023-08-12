<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertiesModifySearchBtn;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/properties-modify-search-btn", array(
		"attributes" => array(
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;
	ob_start();
	?>
		<div class="zl-block-container">
			<div class="zl-advanced-search-btn-wrapper zl-modify-search-btn-wrapper">
				<button class="zl-advanced-search-btn">Modify search</button>
				<?php get_template_part( 'inc/frontend/dynamic-blocks/zesty-lemon-search-box-block/advanced-search-form' ); ?>
			</div>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
