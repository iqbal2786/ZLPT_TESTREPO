<?php
$property_locations = get_terms_ready_for_drop_menu( 'property_location' );
$property_types     = get_terms_ready_for_drop_menu( 'property_type' );
$property_bedrooms  = get_terms_ready_for_drop_menu( 'property_bedrooms' );
$property_sleeps    = get_terms_ready_for_drop_menu( 'property_sleeps' );
$property_features  = get_terms( array(
	'taxonomy' => 'property_feature'
) );
$property_features_categorized = array();
foreach ( $property_features as $property_feature_term_key => $property_feature_term ) {
	$feature_category = get_field( 'feature_category', 'property_feature_' . $property_feature_term->term_id );
	if ( ! empty( $feature_category ) ) {
		if ( ! isset( $property_features_categorized[ $feature_category->name ] ) ) {
			$property_features_categorized[ $feature_category->name ] = array();
		}
		$property_features_categorized[ $feature_category->name ][] = $property_feature_term;
		unset( $property_features[ $property_feature_term_key ] );
	}
}
$search    = isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '';
$types     = isset( $_GET['property-type'] ) ? esc_attr( $_GET['property-type'] ) : '';
$locations = isset( $_GET['property-location'] ) ? esc_attr( $_GET['property-location'] ) : '';
$bedrooms  = isset( $_GET['property-bedrooms'] ) ? intval( $_GET['property-bedrooms'] ) : '';
$sleeps    = isset( $_GET['property-sleeps'] ) ? intval( $_GET['property-sleeps'] ) : '';
$sorting   = isset( $_GET['sorting'] ) ? esc_attr( $_GET['sorting'] ) : '';
$min_price = ( isset( $_GET['property-min-price'] ) && ! empty( $_GET['property-min-price'] ) ) ? intval( $_GET['property-min-price'] ) : '';
$max_price = ( isset( $_GET['property-max-price'] ) && ! empty( $_GET['property-max-price'] ) ) ? intval( $_GET['property-max-price'] ) : '';
$features  = ( isset( $_GET['features'] ) && is_array( $_GET['features'] ) ) ? array_map( 'esc_attr', $_GET['features'] ) : array();
$properties_max_price = get_max_property_price();
$location_placeholder = __( 'All areas', 'zestylemon' );
$locations_array = array();
if ( ! empty( $locations ) ) {
	$locations_array = explode( ',', $locations );
	$locations_array = array_map( 'trim', $locations_array );
	$temp_placeholder = array();
	foreach ( $locations_array as $location ) {
		if ( isset( $property_locations[ $location ] ) ) {
			$temp_placeholder[ $location ] = $property_locations[ $location ];
		}
	}
	if ( ! empty( $temp_placeholder ) ) {
		$location_placeholder = implode( ', ', $temp_placeholder );
	}
}
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
?>
<form class="zl-advanced-search-form-wrapper" action="<?php echo esc_url( site_url() ); ?>">
	<input type="hidden" name="properties" value="yes" />
	<input type="hidden" name="sorting" class="zl-hidden-field" value="<?php echo $sorting; ?>" />
	<div class="zl-advanced-search zl-modal zl-fade">
		<div class="zl-modal-overlay"></div>
		<div class="zl-modal-dialog">
			<div class="zl-modal-dialog-close"></div>
			<div class="zl-modal-dialog-body">
				<div class="zl-modal-row">
					<div class="zl-modal-col-full">
						<div class="zl-modal-query-wrapper">
							<input type="text" name="s" value="<?php echo $search; ?>" class="zl-modal-query" placeholder="<?php _e( 'Enter an address, town, city or zip ...', 'zestylemon' ); ?>" autocomplete="off" />
							<div class="zl-form-group zl-form-btn-group">
								<button type="reset" class="zl-modal-btn zl-reset-btn"><?php _e( 'Reset', 'zestylemon' ); ?></button>
								<button type="submit" class="zl-modal-btn zl-search-btn"><?php _e( 'Search', 'zestylemon' ); ?></button>
							</div>
						</div>
					</div>
				</div>
				<div class="zl-modal-row">
					<div class="zl-modal-col-forth">
						<div class="zl-filter-group">
							<div class="zl-filter-group-title"><?php _e( 'Locations', 'zestylemon' ); ?></div>
							<div class="zl-filter-group-body">
								<input type="hidden" name="property-location" class="zl-hidden-field" value="<?php echo $locations; ?>" />
								<ul class="zl-filter-options zl-multiselect">
									<?php
										$args = array(
											'title_li' => '',
											'taxonomy' => 'property_location',
											'walker'   => new \Property_Locations_Walker(),
										);
										wp_list_categories( $args );
									?>
								</ul>
							</div>
						</div>
						<div class="zl-filter-group">
							<div class="zl-filter-group-title"><?php _e( 'Property types', 'zestylemon' ); ?></div>
							<div class="zl-filter-group-body">
								<input type="hidden" name="property-type" class="zl-hidden-field" value="<?php echo $types; ?>" />
								<ul class="zl-filter-options zl-multiselect zl-two-col">
									<?php
										foreach ( $property_types as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( in_array( $term_slug, $types_array ) ? 'selected' : '' ) . '"><span></span>' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
					</div>
					<div class="zl-modal-col-forth hide-on-hotels">
						<div class="zl-filter-group">
							<div class="zl-filter-group-title"><?php _e( 'Sleeps', 'zestylemon' ); ?></div>
							<div class="zl-filter-group-body">
								<input type="hidden" name="property-sleeps" class="zl-hidden-field" value="<?php echo $sleeps; ?>" />
								<ul class="zl-filter-options zl-two-col">
									<?php
										echo '<li data-id="" class="zl-empty-option ' . ( empty( $sleeps ) ? 'selected' : '' ) . '"><span></span>' . __('Show all', 'zestylemon') . '</li>';
										foreach ( $property_sleeps as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( $sleeps == $term_slug ? 'selected' : '' ) . '"><span></span>' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
						<div class="zl-filter-group">
							<div class="zl-filter-group-title"><?php _e( 'Bedrooms', 'zestylemon' ); ?></div>
							<div class="zl-filter-group-body">
								<input type="hidden" name="property-bedrooms" class="zl-hidden-field" value="<?php echo $bedrooms; ?>" />
								<ul class="zl-filter-options zl-two-col">
									<?php
										echo '<li data-id="" class="zl-empty-option ' . ( empty( $bedrooms ) ? 'selected' : '' ) . '"><span></span>' . __('Show all', 'zestylemon') . '</li>';
										foreach ( $property_bedrooms as $term_slug => $name ) {
											echo '<li data-id="' . $term_slug . '" class="' . ( $bedrooms == $term_slug ? 'selected' : '' ) . '"><span></span>' . $name . '</li>';
										}
									?>
								</ul>
							</div>
						</div>
						<div class="zl-filter-group">
							<div class="zl-filter-group-title"><?php _e( 'Price', 'zestylemon' ); ?></div>
							<div class="zl-filter-group-body">
								<input type="hidden" name="property-min-price" class="property-min-price" class="zl-hidden-field" value="<?php echo $min_price; ?>" />
								<input type="hidden" name="property-max-price" class="property-max-price" class="zl-hidden-field" value="<?php echo $max_price; ?>" />
								<div id="zl-price-range-slider" data-min-price="0" data-max-price="<?php echo $properties_max_price; ?>"></div>
								<div class="zl-price-range">
									<label class="zl-price-range-amount">
										<span class="zl-price-range-currency">£</span>
										<span class="zl-price-range-min"></span>
										<span class="zl-price-range-separator">-</span>
										<span class="zl-price-range-currency">£</span>
										<span class="zl-price-range-max"></span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="zl-modal-col-half">
						<div class="zl-filter-group">
							<div class="zl-filter-group-title"><?php _e( 'Features', 'zestylemon' ); ?></div>
							<div class="zl-filter-group-body">
								<?php
									foreach ( $property_features_categorized as $category => $category_property_features ) {
										echo '<div class="zl-modal-properties-features-inner-wrapper">';
											echo '<div class="zl-modal-properties-features-category">' . $category . '</div>';
											echo '<ul class="zl-modal-properties-features">';
												foreach ( $category_property_features as $property_feature_term ) {
													echo '<li><label><input type="checkbox" name="features[]" value="' . $property_feature_term->slug . '" ' . ( in_array( $property_feature_term->slug, $features ) ? 'checked' : '' ) . '><span></span>' . $property_feature_term->name . '</label></li>';
												}
											echo '</ul>';
										echo '</div>';
									}
									if ( ! empty( $property_features ) ) {
										echo '<div class="zl-modal-properties-features-inner-wrapper">';
											echo '<div class="zl-modal-properties-features-category">Other features</div>';
											echo '<ul class="zl-modal-properties-features">';
												foreach ( $property_features as $property_feature_term ) {
													echo '<li><label><input type="checkbox" name="features[]" value="' . $property_feature_term->slug . '" ' . ( in_array( $property_feature_term->slug, $features ) ? 'checked' : '' ) . '><span></span>' . $property_feature_term->name . '</label></li>';
												}
											echo '</ul>';
										echo '</div>';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>


			<?php /*
			<div class="zl-modal-row" style="display: none;">
				<div class="zl-modal-col-half">
					<div class="zl-form-group">
						<input type="text" name="s" value="<?php echo $search; ?>" class="zl-modal-query" placeholder="<?php _e( 'Enter an address, town, city or zip ...', 'zestylemon' ); ?>" autocomplete="off" />
					</div>
				</div>
				<div class="zl-modal-col-half">
					<div class="zl-form-group">
						<input type="hidden" name="property-location" class="zl-hidden-field" value="<?php echo $locations; ?>" />
						<button class="zl-modal-btn" type="button" title="<?php _e( 'All areas', 'zestylemon' ); ?>">
							<span class="zl-placeholder"><?php echo $location_placeholder; ?></span>
							<span class="zl-icon-down"></span>
						</button>
						<div class="zl-modal-drop-down zl-multiselect">
							<ul>
								<?php
									$args = array(
										'title_li' => '',
										'taxonomy' => 'property_location',
										'walker'   => new \Property_Locations_Walker(),
									);
									wp_list_categories( $args );
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="zl-modal-row" style="display: none;">
				<div class="zl-modal-col-third">
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
				</div>
				<div class="zl-modal-col-third">
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
				</div>
				<div class="zl-modal-col-third">
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
				</div>
			</div>
			<div class="zl-modal-row" style="display: none;">
				<div class="zl-modal-col-half">
					<div class="zl-form-group">
						<div class="zl-price-range">
							<label class="zl-price-range-label"><?php _e( 'Price:', 'zestylemon' ); ?></label>
							<label class="zl-price-range-amount">
								<span class="zl-price-range-currency">£</span>
								<span class="zl-price-range-min"></span>
								<span class="zl-price-range-separator">-</span>
								<span class="zl-price-range-currency">£</span>
								<span class="zl-price-range-max"></span>
							</label>
						</div>
						<div id="zl-price-range-slider" data-min-price="0" data-max-price="<?php echo $properties_max_price; ?>"></div>
						<input type="hidden" name="property-min-price" class="property-min-price" class="zl-hidden-field" value="<?php echo $min_price; ?>" />
						<input type="hidden" name="property-max-price" class="property-max-price" class="zl-hidden-field" value="<?php echo $max_price; ?>" />
					</div>
				</div>
				<div class="zl-modal-col-half">
				</div>
			</div>
			<div class="zl-modal-row" style="display: none;">
				<div class="zl-modal-col-half">
					<div class="zl-form-group zl-form-btn-group">
						<button type="button" class="zl-more-refinements-btn">
							<span class="zl-btn-plus-icon">+</span>
							<span class="zl-btn-minus-icon">-</span>
							<span class="zl-btn-text"><?php _e( 'More refinements', 'zestylemon' ); ?></span>
						</button>
					</div>
				</div>
				<div class="zl-modal-col-half">
					<div class="zl-form-group zl-form-btn-group">
						<button type="reset" class="zl-modal-btn zl-reset-btn"><?php _e( 'Reset', 'zestylemon' ); ?></button>
						<button type="submit" class="zl-modal-btn zl-search-btn"><?php _e( 'Search', 'zestylemon' ); ?></button>
					</div>
				</div>
			</div>
			<div class="zl-modal-row zl-modal-properties-features-wrapper" style="display: none;">
				<div class="zl-form-group">
					<?php
						foreach ( $property_features_categorized as $category => $category_property_features ) {
							echo '<div class="zl-modal-properties-features-inner-wrapper">';
								echo '<div class="zl-modal-properties-features-category">' . $category . '</div>';
								echo '<ul class="zl-modal-properties-features">';
									foreach ( $category_property_features as $property_feature_term ) {
										echo '<li><span><label><input type="checkbox" name="features[]" value="' . $property_feature_term->slug . '" ' . ( in_array( $property_feature_term->slug, $features ) ? 'checked' : '' ) . '><span>' . $property_feature_term->name . '</span></label></span></li>';
									}
								echo '</ul>';
							echo '</div>';
						}
						if ( ! empty( $property_features ) ) {
							echo '<div class="zl-modal-properties-features-inner-wrapper">';
								echo '<div class="zl-modal-properties-features-category">Other features</div>';
								echo '<ul class="zl-modal-properties-features">';
									foreach ( $property_features as $property_feature_term ) {
										echo '<li><span><label><input type="checkbox" name="features[]" value="' . $property_feature_term->slug . '" ' . ( in_array( $property_feature_term->slug, $features ) ? 'checked' : '' ) . '><span>' . $property_feature_term->name . '</span></label></span></li>';
									}
								echo '</ul>';
							echo '</div>';
						}
					?>
				</div>
			</div>
			*/ ?>
		</div>
	</div>
</form>