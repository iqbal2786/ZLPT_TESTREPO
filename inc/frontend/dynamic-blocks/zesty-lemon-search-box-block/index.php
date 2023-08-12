<?php

namespace ZestyLemon\Gutenberg\Blocks\SearchBox;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/search-box", array(
		"attributes" => array(
			"headline" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"headlineTag" => array(
				"type" 		=> "string",
				"default" 	=> "h1"
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	$headline 		   = $attributes["headline"] ? $attributes["headline"] : "";
	$headlineTag       = $attributes["headlineTag"] ? $attributes["headlineTag"] : "h2";
	$property_types    = get_terms_ready_for_drop_menu( 'property_type' );
	// $property_bedrooms = get_terms_ready_for_drop_menu( 'property_bedrooms' );
	// $property_sleeps   = get_terms_ready_for_drop_menu( 'property_sleeps' );
	// $property_types = get_terms( array(
	// 	'taxonomy' => 'property_type'
	// ) );
	// $property_bedrooms = get_terms( array(
	// 	'taxonomy' => 'property_bedrooms'
	// ) );
	// $property_sleeps = get_terms( array(
	// 	'taxonomy' => 'property_sleeps'
	// ) );
	// $property_features = get_terms( array(
	// 	'taxonomy' => 'property_feature'
	// ) );
	// $property_features_categorized = array();
	// foreach ( $property_features as $property_feature_term_key => $property_feature_term ) {
	// 	$feature_category = get_field( 'feature_category', 'property_feature_' . $property_feature_term->term_id );
	// 	if ( ! empty( $feature_category ) ) {
	// 		if ( ! isset( $property_features_categorized[ $feature_category->name ] ) ) {
	// 			$property_features_categorized[ $feature_category->name ] = array();
	// 		}
	// 		$property_features_categorized[ $feature_category->name ][] = $property_feature_term;
	// 		unset( $property_features[ $property_feature_term_key ] );
	// 	}
	// }
	ob_start();
	?>
		<div class="zl-search-box-container">
			<?php
				if ( ! empty( $headline ) ) {
					echo "<" . $headlineTag . " class='zl-search-box-headline'>" . esc_html( $headline ) . "</" . $headlineTag . ">";
				}
			?>
			<form class="zl-search-box-form-wrapper">
				<input type="hidden" name="properties" value="yes" />
				<div class="zl-search-box-form">
					<input type="hidden" name="property-type" class="zl-search-box-property-type" value="" />
					<div class="zl-search-box-type">
						<button class="zl-search-box-type-btn" type="button" title="<?php _e( 'Property type', 'zestylemon' ); ?>">
							<span class="zl-placeholder"><?php _e( 'Property type', 'zestylemon' ); ?></span>
							<span class="zl-icon-down"></span>
						</button>
						<div class="zl-search-box-type-drop-down">
							<ul>
								<?php
									foreach ( $property_types as $term_slug => $name ) {
										echo '<li data-id="' . $term_slug . '">' . $name . '</li>';
									}
								?>
							</ul>
						</div>
					</div>
					<div class="zl-search-box-query-wrapper">
						<span class="zl-icon-location"></span>
						<input type="text" name="s" class="zl-search-box-query" placeholder="<?php _e( 'Enter an address, town, city or zip ...', 'zestylemon' ); ?>" autocomplete="off" />
					</div>
					<button type="submit" class="zl-search-box-submit"><?php _e( 'Search', 'zestylemon' ); ?></button>
				</div>
			</form>
			<button type="button" class="zl-advanced-search-btn">
				<span class="zl-btn-plus-icon">+</span>
				<span class="zl-btn-text"><?php _e( 'Advanced Search', 'zestylemon' ); ?></span>
			</button>
			<?php get_template_part( 'inc/frontend/dynamic-blocks/zesty-lemon-search-box-block/advanced-search-form' ); ?>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
