<?php
namespace ZestyLemon\Gutenberg\Blocks\PropertyStandOutFeatureExtended;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-stand-out-features-extended", array(
		"attributes" => array(
			"headline" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"expand_standout_features" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;

	ob_start();
	$post_id 			= get_the_ID();
	$property_type 		= get_the_terms( $post_id, 'property_type' );
	$property_type 		= join(', ', wp_list_pluck($property_type, 'slug'));
	$portfolio_rooms 	= get_field( 'portfolio_rooms', $post_id );
	$portfolio_suites 	= get_field( 'portfolio_suites', $post_id );
	$portfolio_villas 	= get_field( 'portfolio_villas', $post_id );
	
	$expand_standout_features = str_replace('{{rooms}}', $portfolio_rooms, $attributes['expand_standout_features']);
	$expand_standout_features = str_replace('{{suites}}', $portfolio_suites, $expand_standout_features);
	$expand_standout_features = str_replace('{{villas}}', $portfolio_villas, $expand_standout_features);
	$expand_standout_features = str_replace('{{title}}', get_the_title(), $expand_standout_features);
	
		if( is_admin() ){
		?>
			<div class="zl-block-container zl-stand-out-feature-extended">
				<div class="zl-stand-out-feature-extended-wrapper">
					<p><?php echo $expand_standout_features; ?></p>
				</div>
			</div>
		<?php
	}else if($property_type == "hotel" || $property_type == "resort"){
		?>
			<div class="zl-block-container zl-stand-out-feature-extended">
				<div class="zl-stand-out-feature-extended-wrapper">
					<p><?php echo $expand_standout_features; ?></p>
				</div>
			</div>
		<?php
	}
	$output = ob_get_clean();
	return $output;
}