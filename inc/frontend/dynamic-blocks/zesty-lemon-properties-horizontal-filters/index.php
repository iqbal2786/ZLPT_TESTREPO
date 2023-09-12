<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertiesHorizontalFilters;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/properties-horizontal-filters", array(
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
	$search    = isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '';
	$types     = isset( $_GET['property-type'] ) ? esc_attr( $_GET['property-type'] ) : '';
	$bedrooms  = isset( $_GET['property-bedrooms'] ) ? intval( $_GET['property-bedrooms'] ) : '';
	$sleeps    = isset( $_GET['property-sleeps'] ) ? intval( $_GET['property-sleeps'] ) : '';
	$sorting   = isset( $_GET['sorting'] ) ? esc_attr( $_GET['sorting'] ) : '';
	$locations = isset( $_GET['property-location'] ) ? esc_attr( $_GET['property-location'] ) : '';
	$min_price = ( isset( $_GET['property-min-price'] ) && ! empty( $_GET['property-min-price'] ) ) ? intval( $_GET['property-min-price'] ) : '';
	$max_price = ( isset( $_GET['property-max-price'] ) && ! empty( $_GET['property-max-price'] ) ) ? intval( $_GET['property-max-price'] ) : '';
	$features  = ( isset( $_GET['features'] ) && is_array( $_GET['features'] ) ) ? array_map( 'esc_attr', $_GET['features'] ) : array();
	$property_types    = get_terms_ready_for_drop_menu( 'property_type' );
	$property_bedrooms = get_terms_ready_for_drop_menu( 'property_bedrooms' );
	$property_sleeps   = get_terms_ready_for_drop_menu( 'property_sleeps' );
	$sorting_types     = array(
		'price-low-to-high' => 'Price (low to high)',
		'price-high-to-low' => 'Price (high to low)',
		'sleeps-low-to-high' => 'No of Sleeps (low to high)',
		'sleeps-high-to-low' => 'No of Sleeps (high to low)',
		'bedrooms-low-to-high' => 'No of bedrooms (low to high)',
		'bedrooms-high-to-low' => 'No of bedrooms (hight to low)',
	);
	$type_placeholder = __( 'Property type', 'zestylemon' );
	$types_array = array();
	if ( ! empty( $types ) ) {
		$types_array = explode( ',', $types );
		$types_array = array_map( 'trim', $types_array );
		$temp_placeholder = array();
		foreach ( $types_array as $type ) {
			if ( isset( $property_types[ $type ] ) ) {
				$temp_placeholder[ $type ] = $property_types[ $type ];
			}
		}
		if ( ! empty( $temp_placeholder ) ) {
			$type_placeholder = implode( ', ', $temp_placeholder );
		}
	}
	$bedrooms_placeholder = __( 'Bedrooms', 'zestylemon' );
	if ( ! empty( $bedrooms ) && isset( $property_bedrooms[ $bedrooms ] ) ) {
		$bedrooms_placeholder = $property_bedrooms[ $bedrooms ];
	}
	$sleeps_placeholder = __( 'Sleeps', 'zestylemon' );
	if ( ! empty( $sleeps ) && isset( $property_sleeps[ $sleeps ] ) ) {
		$sleeps_placeholder = $property_sleeps[ $sleeps ];
	}
	$sorting_placeholder = __( 'Default sorting', 'zestylemon' );
	if ( ! empty( $sorting ) && isset( $sorting_types[ $sorting ] ) ) {
		$sorting_placeholder = $sorting_types[ $sorting ];
	}
	ob_start();
	?>
		<div class="zl-block-container">
			<form class="zl-properties-horizontal-filters-form-wrapper" action="<?php echo esc_url( site_url() ); ?>">
				<input type="hidden" name="properties" value="yes" />
				<input type="hidden" name="s" value="<?php echo $search; ?>" />
				<input type="hidden" name="property-location" class="zl-hidden-field" value="<?php echo $locations; ?>" />
				<input type="hidden" name="property-min-price" class="zl-hidden-field" value="<?php echo $min_price; ?>" />
				<input type="hidden" name="property-max-price" class="zl-hidden-field" value="<?php echo $max_price; ?>" />
				<?php foreach ( $features as $feature_slug ) {
					echo '<input type="hidden" name="features[]" class="zl-hidden-field" value="' . $feature_slug . '" />';
				}?>
				<div class="zl-properties-horizontal-filters">
					<div class="zl-view-mode">
						<span class="zl-grid-view zl-icon-grid-view active"></span>
						<span class="zl-list-view zl-icon-list-view"></span>
					</div>
					<div class="zl-quick-filters-wrapper">
						<div class="zl-form-group">
							<input type="hidden" name="property-bedrooms" class="zl-hidden-field" value="<?php echo $bedrooms; ?>" />
							<button class="zl-modal-btn" type="button" title="<?php _e( 'Bedrooms', 'zestylemon' ); ?>">
								<span class="zl-placeholder"><?php echo $bedrooms_placeholder; ?></span>
								<span class="zl-icon-down"></span>
							</button>
							<div class="zl-modal-drop-down">
								<ul>
									<li class="zl-empty-option" data-id="" style="<?php echo ! empty( $bedrooms ) ? 'display: list-item;' : ''; ?>"><?php _e( 'Any Bedrooms', 'zestylemon' ); ?></li>
									<?php
										foreach ( $property_bedrooms as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( $bedrooms == $term_slug ? 'selected' : '' ) . '">' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
						<div class="zl-form-group">
							<input type="hidden" name="property-sleeps" class="zl-hidden-field" value="<?php echo $sleeps; ?>" />
							<button class="zl-modal-btn" type="button" title="<?php _e( 'Sleeps', 'zestylemon' ); ?>">
								<span class="zl-placeholder"><?php echo $sleeps_placeholder; ?></span>
								<span class="zl-icon-down"></span>
							</button>
							<div class="zl-modal-drop-down">
								<ul>
									<li class="zl-empty-option" data-id="" style="<?php echo ! empty( $sleeps ) ? 'display: list-item;' : ''; ?>"><?php _e( 'Any Sleeps', 'zestylemon' ); ?></li>
									<?php
										foreach ( $property_sleeps as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( $sleeps == $term_slug ? 'selected' : '' ) . '">' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
						<div class="zl-form-group">
							<input type="hidden" name="property-type" class="zl-hidden-field" value="<?php echo $types; ?>" />
							<button class="zl-modal-btn" type="button" title="<?php _e( 'Property type', 'zestylemon' ); ?>">
								<span class="zl-placeholder"><?php echo $type_placeholder; ?></span>
								<span class="zl-icon-down"></span>
							</button>
							<div class="zl-modal-drop-down zl-multiselect">
								<ul>
									<?php
										foreach ( $property_types as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( in_array( $term_slug, $types_array ) ? 'selected' : '' ) . '">' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
						<div class="zl-form-group">
							<input type="hidden" name="sorting" class="zl-hidden-field" value="<?php echo $sorting; ?>" />
							<button class="zl-modal-btn" type="button" title="<?php _e( 'Sorting', 'zestylemon' ); ?>">
								<span class="zl-placeholder"><?php echo $sorting_placeholder; ?></span>
								<span class="zl-icon-down"></span>
							</button>
							<div class="zl-modal-drop-down">
								<ul>
									<li class="zl-empty-option" data-id="" style="<?php echo ! empty( $sorting ) ? 'display: list-item;' : ''; ?>"><?php _e( 'Default sorting', 'zestylemon' ); ?></li>
									<?php
										foreach ( $sorting_types as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( $sorting == $term_slug ? 'selected' : '' ) . '">' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
