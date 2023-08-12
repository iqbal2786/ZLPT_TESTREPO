<div class="zl-map <?php echo $map_class; ?>" data-zoom="13" style="height: 550px;" data-marker-icon="<?php echo get_stylesheet_directory_uri() . '/assets/shared/images/map-mark-red.png'; ?>">
	<?php
		foreach ( $map_markers as $marker_data ) {
			$property_image = '<img src="' . esc_attr( $marker_data['thumb'] ) . '" width="150px" alt="' . esc_attr( $marker_data['title'] ) . '"/>';
			echo '<div class="zl-marker" data-title="' . esc_attr( $marker_data['title'] ) . '" data-lat="' . esc_attr( $marker_data['lat'] ) . '" data-lng="' . esc_attr( $marker_data['lng'] ) . '">';
				echo '<div class="property-info-window">';
					echo '<div class="property-info-window-inner">';
						echo '<div class="zl-property-thumb-img">';
							if ( is_single() ) {
								echo $property_image;
							} else {
								echo '<a href="' . esc_attr( $marker_data['url'] ) . '">';
									echo $property_image;
								echo '</a>';
							}
						echo '</div>';
						echo '<div class="zl-property-info">';
							if ( is_single() ) {
								echo '<p class="property-title">' . esc_attr( $marker_data['title'] ) . '</p>';
							} else {
								echo '<p class="property-title"><a href="' . esc_attr( $marker_data['url'] ) . '">' . esc_attr( $marker_data['title'] ) . '</a></p>';
							}
							echo '<p class="property-strapline">' . esc_attr( $marker_data['strapline'] ) . '</p>';
						echo '</div>';
					echo '</div>';
					if ( isset( $marker_data['price'] ) ) {
						echo '<p class="property-price">Price: ' . esc_attr( $marker_data['price'] ) . '</p>';
					}
				echo '</div>';
			echo '</div>';
		}
	?>
</div>
