<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertyGoodFor;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-good-for", array(
		"attributes" => array(
			"source_type" => array(
				"type" 		=> "string",
				"default" 	=> "sleeps"
			),
			"couples_feature" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"couples_icon_slug" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"families_icon_slug" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"couples_label" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"families_label" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"celebrations_feature" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"celebrations_label" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"decorated_feature" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"decorated_label" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	$editor    = false;
	$icon_slug = '';
	$label     = $secondary_label = '';
	$icon      = array();
	$celebrations_feature_term = null;
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		$editor = true;
		$args   = array(
			'post_type' => 'property'
		);
		$properties = new \WP_Query( $args );
		if ( $properties->have_posts() ) {
			$properties->the_post();
		}
	}

	if ( isset( $attributes['celebrations_feature'] ) && ! empty( $attributes['celebrations_feature'] ) ) {
		$celebrations_feature = esc_attr( $attributes['celebrations_feature'] );
		if ( has_term( $celebrations_feature, 'property_feature' ) ) {
			$celebrations_feature_term = get_term_by( 'slug', $celebrations_feature, 'property_feature' );
			$label = esc_attr( $attributes['celebrations_label'] );
		}
	}
	if ( empty( $label ) ) {
		$source_type = 'sleeps';
		if ( isset( $attributes['source_type'] ) && ! empty( $attributes['source_type'] ) ) {
			$source_type = esc_attr( $attributes['source_type'] );
		}
		switch ( $source_type ) {
			case 'features':
				$label     = esc_attr( $attributes['families_label'] );
				$icon_slug = esc_attr( $attributes['families_icon_slug'] );
				if ( isset( $attributes['couples_feature'] ) && ! empty( $attributes['couples_feature'] ) ) {
					$couples_feature = esc_attr( $attributes['couples_feature'] );
					if ( has_term( $couples_feature, 'property_feature' ) ) {
						$label     = esc_attr( $attributes['couples_label'] );
						$icon_slug = esc_attr( $attributes['couples_icon_slug'] );
					}
				}
				break;
			case 'sleeps':
			default:
				$label     = esc_attr( $attributes['families_label'] );
				$icon_slug = esc_attr( $attributes['families_icon_slug'] );
				$term_id   = get_field( 'property_sleeps' );
				if ( ! empty( $term_id ) ) {
					$term = get_term( $term_id );
					if ( ! empty( $term ) ) {
						if ( intval( $term->name ) == 2 ) {
							$label     = esc_attr( $attributes['couples_label'] );
							$icon_slug = esc_attr( $attributes['couples_icon_slug'] );
						}
					}
				}
				break;
		}
	}
	if ( isset( $attributes['decorated_feature'] ) && ! empty( $attributes['decorated_feature'] ) ) {
		$decorated_feature = esc_attr( $attributes['decorated_feature'] );
		if ( has_term( $decorated_feature, 'property_feature' ) ) {
			$secondary_label = esc_attr( $attributes['decorated_label'] );
		}
	}

	if ( ! empty( $celebrations_feature_term ) ) {
		$icon_name     = 'Good for celebrations';
		$use_svg_icon  = get_field( 'image_svg_code', 'property_feature_' . $celebrations_feature_term->term_id );
		$svg_icon      = get_field( 'icon_svg', 'property_feature_' . $celebrations_feature_term->term_id );
		$icon_img_id   = get_field( 'icon_image', 'property_feature_' . $celebrations_feature_term->term_id );		
		$icon_img_URL  = wp_get_attachment_image_src( $icon_img_id, 'full' );
		$icon = array(
			'icon'           => $icon_name,
			'icon_slug'      => slugify( $icon_name ),
			'use_svg'        => $use_svg_icon,
			'icon_image'     => $icon_img_id,
			'icon_image_url' => $icon_img_URL,
			'icon_svg'       => $svg_icon,
		);
	} else if ( ! empty( $icon_slug ) ) {
		$icon = zl_get_icon_from_library( $icon_slug );
	}

	if ( $editor ) {
		if ( empty( $label ) ) {
			$label = 'Good for families'; // dummy label
		}
		if ( empty( $secondary_label ) ) {
			$secondary_label = 'Decorated for christmas'; // dummy secondary_label
		}
	}
	
	ob_start();
	?>
	<div class="zl-stand-out-feature zl-property-good-for">
		<?php
		if ( ! empty( $icon ) ) {
			if ( $icon['use_svg'] && ! empty( $icon['icon_svg'] ) ) {
				echo '<div class="zl-stand-out-feature-icon">' . $icon['icon_svg'] . '</div>';
			}
			if ( ! $icon['use_svg'] && ! empty( $icon['icon_image_url'] ) ) {
				echo '<div class="zl-stand-out-feature-icon">';
				echo '<img class="wp-image-' . esc_attr( $icon['icon_image'] ) . '" src="' . esc_url( $icon['icon_image_url'] ) . '" />';
				echo '</div>';
			}
		}
		echo '<div class="zl-stand-out-feature-number"></div>';
		if ( isset( $label ) && ! empty( $label ) ) {
			echo '<div class="zl-stand-out-feature-label">' . esc_attr( $label ) . '</div>';
		}
		if ( isset( $secondary_label ) && ! empty( $secondary_label ) ) {
			echo '<div class="zl-stand-out-feature-label zl-secondary-label">' . esc_attr( $secondary_label ) . '</div>';
		}
		?>
	</div>
	<?php
	$output = ob_get_clean();
	if ( $editor ) {
		wp_reset_postdata();
	}
	return $output;
}
