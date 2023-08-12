<?php
$headline_tag  = $attributes['headline_tag'];
if ( empty( $headline_tag ) || ! in_array( $headline_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6') ) ) {
	$headline_tag = 'h2';
}
$property_type = $strapline = '';
$property_type_ribbon_bg = 'var(--accent)';
$price = $sleeps = $bedrooms = $bathrooms = $featured = 0;
if ( function_exists('get_field') ) {
	$price         = get_field('property_price');
	$sleeps        = get_field('property_sleeps');
	$bedrooms      = get_field('property_bedrooms');
	$bathrooms     = get_field('property_bathrooms');
	$featured      = get_field('featured_property');
	$property_type = get_field('property_type');
	$strapline     = get_field('property_strapline');
	$price         = get_formated_price( $price );
	$specs         = array();
	if ( ! empty( $sleeps ) ) {
		$sleeps = get_term( $sleeps );
		$sleeps = $sleeps->name;
		$specs['sleeps'] = array(
			//'icon'  => 'zl-icon-profile-male',
			'icon'  => '<svg width="100%" height="100%" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"> <g> 	<path d="M22.501,30.358c1.763,1.092,3.751,1.653,5.774,1.653c0.844,0,1.695-0.098,2.538-0.296 		c5.906-1.389,9.582-7.323,8.194-13.228c-0.672-2.861-2.418-5.29-4.917-6.837c-2.499-1.548-5.451-2.029-8.312-1.357 		c-2.861,0.672-5.289,2.418-6.837,4.917c-1.547,2.498-2.03,5.45-1.357,8.311C18.256,26.382,20.002,28.81,22.501,30.358z 		 M20.642,16.263c1.267-2.044,3.253-3.473,5.594-4.023c0.69-0.162,1.386-0.242,2.077-0.242c1.655,0,3.282,0.459,4.724,1.353 		c2.045,1.266,3.473,3.253,4.023,5.594c0,0,0,0,0,0c0.55,2.341,0.156,4.756-1.11,6.8c-1.266,2.045-3.253,3.473-5.594,4.024 		c-4.835,1.137-9.689-1.872-10.824-6.705C18.981,20.723,19.375,18.308,20.642,16.263z"></path> 	<path d="M71.649,32.007c0.02,0,0.039,0,0.058,0c2.918,0,5.664-1.129,7.739-3.184c4.312-4.268,4.347-11.249,0.078-15.561 		c-4.269-4.312-11.249-4.348-15.561-0.078c-2.088,2.067-3.247,4.825-3.262,7.764c-0.015,2.939,1.116,5.708,3.184,7.796 		C65.953,30.834,68.71,31.992,71.649,32.007z M65.371,14.606c1.698-1.681,3.945-2.605,6.333-2.605c0.015,0,0.031,0,0.046,0 		c2.405,0.012,4.661,0.96,6.353,2.669c1.692,1.709,2.617,3.975,2.604,6.379c-0.012,2.405-0.96,4.661-2.669,6.353 		c-1.709,1.691-3.96,2.59-6.379,2.604c-2.405-0.012-4.661-0.96-6.353-2.669c-1.691-1.709-2.617-3.974-2.604-6.379 		C62.714,18.554,63.662,16.298,65.371,14.606z"></path> 	<path d="M39.591,37.185H17c-3.86,0-7,3.14-7,7V56.53c0,3.165,2.325,5.796,5.356,6.277v26.193c0,0.552,0.448,1,1,1h23.879 		c0.552,0,1-0.448,1-1V62.808c3.031-0.481,5.356-3.113,5.356-6.277V44.185C46.591,40.325,43.451,37.185,39.591,37.185z 		 M44.591,56.53c0,2.402-1.954,4.356-4.356,4.356c-0.552,0-1,0.448-1,1v26.115H17.356V61.886c0-0.552-0.448-1-1-1 		c-2.402,0-4.356-1.954-4.356-4.356V44.185c0-2.757,2.243-5,5-5h22.591c2.757,0,5,2.243,5,5V56.53z"></path> 	<path d="M85.369,43.197c-0.489-3.427-3.468-6.012-6.93-6.012H64.97c-3.462,0-6.441,2.584-6.93,6.012l-4.621,32.397 		c-0.041,0.287,0.045,0.578,0.234,0.796c0.19,0.219,0.465,0.345,0.755,0.345h7.385v12.266c0,0.552,0.448,1,1,1h17.82 		c0.552,0,1-0.448,1-1V76.735H89c0.29,0,0.565-0.126,0.755-0.345c0.19-0.219,0.275-0.51,0.234-0.796L85.369,43.197z M80.615,74.735 		c-0.552,0-1,0.448-1,1v12.266h-15.82V75.735c0-0.552-0.448-1-1-1h-7.232l4.458-31.256c0.35-2.448,2.478-4.294,4.95-4.294h13.469 		c2.473,0,4.601,1.846,4.95,4.294l4.458,31.256H80.615z"></path> </g> </svg>',
			'value' => $sleeps,
			'label' => __( 'Sleeps', 'zestylemon' ),
		);
	}
	if ( ! empty( $bedrooms ) ) {
		$bedrooms = get_term( $bedrooms );
		$bedrooms = $bedrooms->name;
		$specs['bedrooms'] = array(
			//'icon'  => 'zl-icon-bed',
			'icon'  => '<svg width="100%" height="100%" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"> <path d="M87.838,46.867V16.795c0-3.747-3.048-6.795-6.794-6.795H18.956c-3.746,0-6.794,3.048-6.794,6.795v29.856 	C10.818,47.936,10,49.735,10,51.685v26.222c0,0.553,0.448,1,1,1h7.131V89c0,0.553,0.448,1,1,1c0.552,0,1-0.447,1-1V78.906h59.732V89 	c0,0.553,0.448,1,1,1c0.552,0,1-0.447,1-1V78.906H89c0.552,0,1-0.447,1-1V51.913C90,49.941,89.18,48.146,87.838,46.867z 	 M14.162,16.795c0-2.644,2.151-4.795,4.794-4.795h62.087c2.644,0,4.794,2.151,4.794,4.795v28.736 	c-0.379-0.165-0.775-0.303-1.189-0.401c-0.89-0.211-1.772-0.408-2.652-0.601V33.428c0-2.491-2.026-4.517-4.517-4.517H57.345 	c-2.49,0-4.517,2.026-4.517,4.517v7.515c-1.894-0.051-3.733-0.064-5.522-0.047v-7.469c0-2.491-2.026-4.517-4.517-4.517H22.654 	c-2.491,0-4.517,2.026-4.517,4.517v10.786c-1.106,0.258-2.097,0.507-2.953,0.736c-0.355,0.095-0.694,0.221-1.022,0.366V16.795z 	 M79.997,33.428v10.681c-9.042-1.86-17.494-2.785-25.168-3.094v-7.586c0-1.389,1.129-2.517,2.517-2.517H77.48 	C78.868,30.91,79.997,32.039,79.997,33.428z M45.306,33.428v7.499c-10.63,0.243-19.243,1.577-25.169,2.842V33.428 	c0-1.389,1.129-2.517,2.517-2.517h20.135C44.177,30.91,45.306,32.039,45.306,33.428z M88,76.906H12v-11.29h76V76.906z M88,63.616H12 	V51.685c0-2.247,1.521-4.223,3.699-4.804c9.567-2.554,35.391-7.646,68.488,0.194C86.432,47.606,88,49.596,88,51.913V63.616z"></path> </svg>',
			'value' => $bedrooms,
			'label' => __( 'Bedrooms', 'zestylemon' ),
		);
	}
	if ( ! empty( $bathrooms ) ) {
		$bathrooms = get_term( $bathrooms );
		$bathrooms = $bathrooms->name;
		$specs['bathrooms'] = array(
			//'icon'  => 'zl-icon-bath',
			'icon'  => '<svg width="100%" height="100%" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"> <g> <path d="M86.398,49.155h-3.261V19.794c0-5.4-4.396-9.794-9.8-9.794c-5.157,0-9.387,4.005-9.763,9.065 c-5.098,0.487-9.099,4.79-9.099,10.014c0,0.553,0.448,1,1,1H73.6c0.552,0,1-0.447,1-1c0-5.199-3.963-9.488-9.026-10.009 C65.942,15.11,69.281,12,73.337,12c4.301,0,7.8,3.496,7.8,7.794v29.361H13.602c-1.986,0-3.602,1.616-3.602,3.603v4.973 c0,1.986,1.616,3.603,3.602,3.603h0.33l3.865,14.512c0.614,3.723,3.438,6.563,7.006,7.297l-2.958,5.376 c-0.267,0.484-0.09,1.093,0.394,1.358C22.392,89.96,22.557,90,22.72,90c0.353,0,0.695-0.187,0.877-0.518l3.384-6.149h46.01 l3.384,6.149C76.556,89.813,76.898,90,77.251,90c0.163,0,0.328-0.04,0.481-0.124c0.484-0.266,0.661-0.874,0.394-1.358l-2.977-5.409 c3.497-0.779,6.249-3.578,6.837-7.17l3.886-14.605h0.525c1.986,0,3.602-1.616,3.602-3.603v-4.973 C90,50.771,88.384,49.155,86.398,49.155z M72.539,28.078H56.537c0.494-3.976,3.894-7.062,8.001-7.062 C68.644,21.016,72.044,24.103,72.539,28.078z M80.034,75.519c-0.556,3.369-3.439,5.814-6.856,5.814h-46.55 c-3.417,0-6.3-2.445-6.877-5.908l-3.749-14.092h67.802L80.034,75.519z M88,57.73c0,0.884-0.719,1.603-1.602,1.603H13.602 c-0.883,0-1.602-0.719-1.602-1.603v-4.973c0-0.884,0.719-1.603,1.602-1.603h72.796c0.883,0,1.602,0.719,1.602,1.603V57.73z"></path> <path d="M72.039,43.483v-8.32c0-0.553-0.448-1-1-1c-0.552,0-1,0.447-1,1v8.32c0,0.553,0.448,1,1,1 C71.591,44.483,72.039,44.036,72.039,43.483z"></path> <path d="M65.538,43.483v-8.32c0-0.553-0.448-1-1-1c-0.552,0-1,0.447-1,1v8.32c0,0.553,0.448,1,1,1 C65.09,44.483,65.538,44.036,65.538,43.483z"></path> <path d="M59.036,43.483v-8.32c0-0.553-0.448-1-1-1c-0.552,0-1,0.447-1,1v8.32c0,0.553,0.448,1,1,1 C58.588,44.483,59.036,44.036,59.036,43.483z"></path> </g> </svg>',
			'value' => $bathrooms,
			'label' => __( 'Bathrooms', 'zestylemon' ),
		);
	}
	if ( ! empty( $property_type ) ) {
		$temp_ribbon_bg = get_field( 'type_bg_color', 'property_type_' . $property_type );
		if ( ! empty( $temp_ribbon_bg ) ) {
			$property_type_ribbon_bg = $temp_ribbon_bg;
		}
		$property_type = get_term( $property_type );
		$property_type = $property_type->name;
	}
}
?>
<div class="zl-property-archive-item-wrapper">
	<?php
		if ( $featured ) {
			echo '<span class="zl-featured zl-ribbon" style="background-color: var(--featured-ribbon);">' . __( 'Featured', 'zestylemon' ) . '<span style="border-color: var(--featured-ribbon);" class="zl-ribbon-traingle"></span></span>';
		}
	?>
	<?php
		if ( ! empty( $property_type ) ) {
			echo '<span class="zl-property-type zl-ribbon" style="background-color: ' . $property_type_ribbon_bg . ';">' . $property_type . '<span style="border-color: ' . $property_type_ribbon_bg . ';" class="zl-ribbon-traingle"></span></span>';
		}
	?>
	<figure class="zl-property-thumb">
		<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'property-thumb' );
			} else {
				echo '<img src="' . get_stylesheet_directory_uri() . '/assets/shared/images/property-placeholder.png" />';
			}
		?>
		<figcaption>
			<span class="zl-property-price"><?php echo __( 'From', 'zestylemon' ) . ' ' . $price; ?></span>
			<a class="zl-property-btn" href="<?php the_permalink(); ?>"><?php _e( 'View Details', 'zestylemon' ); ?></a>
		</figcaption>
		<a class="zl-property-overlay-link" href="<?php the_permalink(); ?>"></a>
	</figure>
	<div class="zl-property-summary">
		<div class="zl-property-summary-content">
			<<?php echo $headline_tag; ?> class="zl-property-summary-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</<?php echo $headline_tag; ?>>
			<div class="zl-property-summary-excerpt">
				<?php the_excerpt(); ?>
			</div>
			<div class="zl-property-summary-strapline">
				<p><?php echo $strapline; ?></p>
			</div>
		</div>
		<?php if ( ! empty( $specs ) ) { ?>
			<div class="zl-property-summary-footer">
				<ul class="zl-property-specs">
					<?php
						foreach ( $specs as $specs_array ) {
							echo '<li>';
								echo '<span class="zl-property-specs-icon">' . $specs_array['icon'] . '</span>';
								echo '<span class="zl-property-specs-value">' . $specs_array['value'] . '</span>';
								echo '<span class="zl-property-specs-label">' . $specs_array['label'] . '</span>';
							echo '</li>';
						}
					?>
				</ul>
			</div>
		<?php } ?>
	</div>
</div>