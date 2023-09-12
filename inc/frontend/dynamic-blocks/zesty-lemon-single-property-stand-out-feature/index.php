<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertyStandOutFeature;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-stand-out-feature", array(
		"attributes" => array(
			"source_type" => array(
				"type" 		=> "string",
				"default" 	=> "feature"
			),
			"source_feature" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"source_taxonomy" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"enable_secondary_feature" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
			"secondary_source_type" => array(
				"type" 		=> "string",
				"default" 	=> "postmeta"
			),
			"secondary_postmeta" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"secondary_feature" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"use_svg_icon" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
			"svg_icon" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"icon_img_id" => array(
				"type" 		=> "number",
			),
			"icon_img_URL" => array(
				"type" 		=> "string",
			),
			"label_true_settings" => array(
				"type" 		=> "string",
				"default" 	=> "taxonomy"
			),
			"label_true" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"label_false" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"secondary_label_true" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"use_secondary_icon" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
			"secondary_use_svg_icon" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
			"secondary_svg_icon" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"secondary_icon_img_id" => array(
				"type" 		=> "number",
			),
			"secondary_icon_img_URL" => array(
				"type" 		=> "string",
			),
			"replace_main_feature" => array(
				"type" 		=> "string",
				"default" 	=> "no"
			),
			"number_settings" => array(
				"type" 		=> "string",
				"default" 	=> "disabled"
			),
			"hide_block_if_no_main_feature" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	$number                = 0;
	$label                 = '';
	$secondary_label       = '';
	$replace_label         = false;
	$editor                = false;
	$main_label_class      = '';
	$secondary_label_class = 'zl-secondary-label';
	$use_svg_icon          = false;
	$svg_icon              = '';
	$icon_img_URL          = '';
	$icon_img_id           = '';
	$hide_block            = false;
	if ( isset( $attributes['use_svg_icon'] ) ) {
		$use_svg_icon = $attributes['use_svg_icon'];
	}
	if ( isset( $attributes['svg_icon'] ) ) {
		$svg_icon = $attributes['svg_icon'];
	}
	if ( isset( $attributes['icon_img_URL'] ) ) {
		$icon_img_URL = $attributes['icon_img_URL'];
	}
	if ( isset( $attributes['icon_img_id'] ) ) {
		$icon_img_id = $attributes['icon_img_id'];
	}
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

	if ( ! isset( $attributes['number_settings'] ) || empty( $attributes['number_settings'] ) ) {
		$attributes['number_settings'] = 'disabled';
	}
	if ( ! isset( $attributes['label_true_settings'] ) || empty( $attributes['label_true_settings'] ) ) {
		$attributes['label_true_settings'] = 'custom';
	}

	if ( isset( $attributes['source_type'] ) ) {
		if ( $attributes['source_type'] == 'taxonomy' ) {
			if ( isset( $attributes['source_taxonomy'] ) && ! empty( $attributes['source_taxonomy'] ) ) {
				// get the term of sleeps, bedrooms, bathrooms
				$term_id = get_field( $attributes['source_taxonomy'] );
				if ( ! empty( $term_id ) ) {
					$term = get_term( $term_id );
					if ( ! empty( $term ) ) {
						$number  = $term->name;
					}
				}
			}
			$attributes['number_settings'] = 'enabled';
			$label = $attributes['label_true'];
		} elseif ( $attributes['source_type'] == 'feature' ) {
			if ( isset( $attributes['source_feature'] ) && ! empty( $attributes['source_feature'] ) ) {
				$feature = esc_attr( $attributes['source_feature'] );
				$feature_term  = get_term_by( 'slug', $feature, 'property_feature' );
				$use_svg_icon  = get_field( 'image_svg_code', 'property_feature_' . $feature_term->term_id );
				$svg_icon      = get_field( 'icon_svg', 'property_feature_' . $feature_term->term_id );
				$icon_img_id   = get_field( 'icon_image', 'property_feature_' . $feature_term->term_id );		
				$icon_img_URL  = wp_get_attachment_image_src( $icon_img_id, 'full' );
				if ( has_term( $feature, 'property_feature' ) ) {
					if ( $attributes['label_true_settings'] == 'taxonomy' ) {
						$connect_to_extra_taxonomy = get_field( 'connect_to_extra_taxonomy', 'property_feature_' . $feature_term->term_id );
						if ( isset( $connect_to_extra_taxonomy ) && ! empty( $connect_to_extra_taxonomy ) ) {
							$extra_taxonomy_terms = get_the_terms( get_the_ID(), $connect_to_extra_taxonomy );
							if ( isset( $extra_taxonomy_terms[0] ) && isset( $extra_taxonomy_terms[0]->name ) ) {
								$label = $extra_taxonomy_terms[0]->name;
							}
						} else {
							$attributes['label_true_settings'] = 'custom';
						}
					}
					if ( $attributes['label_true_settings'] == 'custom' ) {
						if ( isset( $attributes['label_true'] ) && ! empty( $attributes['label_true'] ) ) {
							$label = $attributes['label_true'];
						}
					}
					if ( $attributes['number_settings'] == 'taxonomy' ) {
						$connect_to_extra_taxonomy = get_field( 'connect_to_extra_taxonomy', 'property_feature_' . $feature_term->term_id );
						if ( isset( $connect_to_extra_taxonomy ) && ! empty( $connect_to_extra_taxonomy ) ) {
							$extra_taxonomy_terms = get_the_terms( get_the_ID(), $connect_to_extra_taxonomy );
							if ( isset( $extra_taxonomy_terms[0] ) && isset( $extra_taxonomy_terms[0]->name ) ) {
								$number = $extra_taxonomy_terms[0]->name;
							}
						}
					}
				} else {
					if ( isset( $attributes['label_false'] ) && ! empty( $attributes['label_false'] ) ) {
						$label = $attributes['label_false'];
					}
					if ( isset( $attributes['hide_block_if_no_main_feature'] ) && ! empty( $attributes['hide_block_if_no_main_feature'] ) ) {
						$hide_block = $attributes['hide_block_if_no_main_feature'];
					}
				}
				if ( ! $hide_block && $attributes['enable_secondary_feature'] ) {
					if ( $attributes['secondary_source_type'] == 'postmeta' ) {
						$secondary_postmeta = get_field( $attributes['secondary_postmeta'] );
						if ( $secondary_postmeta ) {
							if ( ! empty( $attributes['secondary_label_true'] ) ) {
								$secondary_label = $attributes['secondary_label_true'];
							}
						}
					}
					if ( $attributes['secondary_source_type'] == 'feature' ) {
						$secondary_feature = esc_attr( $attributes['secondary_feature'] );
						if ( has_term( $secondary_feature, 'property_feature' ) ) {
							if ( ! empty( $attributes['secondary_label_true'] ) ) {
								$secondary_label = $attributes['secondary_label_true'];
							}
						}
					}
					switch ( $attributes['replace_main_feature'] ) {
						case 'no':
							break;
						case 'if_main_true':
							if ( has_term( $feature, 'property_feature' ) ) {
								$replace_label = true;
							}
							break;
						case 'if_main_false':
							if ( ! has_term( $feature, 'property_feature' ) ) {
								$replace_label = true;
							} else {
								$secondary_label = '';
							}
							break;
						default:
							break;
					}
				}
			}
		}
	}

	if ( $replace_label & isset( $secondary_label ) && ! empty( $secondary_label ) ) {
		$label                 = $secondary_label;
		$main_label_class      = $secondary_label_class;
		$secondary_label       = '';
		$secondary_label_class = '';
	}
	if ( $replace_label & isset( $attributes['use_secondary_icon'] ) && ! empty( $attributes['use_secondary_icon'] ) ) {
		if ( isset( $attributes['secondary_use_svg_icon'] ) ) {
			$use_svg_icon = $attributes['secondary_use_svg_icon'];
		}
		if ( isset( $attributes['secondary_svg_icon'] ) ) {
			$svg_icon = $attributes['secondary_svg_icon'];
		}
		if ( isset( $attributes['secondary_icon_img_URL'] ) ) {
			$icon_img_URL = $attributes['secondary_icon_img_URL'];
		}
		if ( isset( $attributes['secondary_icon_img_id'] ) ) {
			$icon_img_id = $attributes['secondary_icon_img_id'];
		}
	}

	if ( $editor ) {
		if ( empty( $number ) && $attributes['number_settings'] != 'disabled' ) {
			$number = 5; // dummy number
		}
		if ( empty( $label ) ) {
			$label = 'Label'; // dummy label
		}
	} else {
		if ( $hide_block ) {
			return '';
		}
	}

	ob_start();
	?>
	<div class="zl-stand-out-feature">
		<?php
		if ( $use_svg_icon && ! empty( $svg_icon ) ) {
			echo '<div class="zl-stand-out-feature-icon">' . $svg_icon . '</div>';
		}
		if ( ! $use_svg_icon && ! empty( $icon_img_URL ) ) {
			echo '<div class="zl-stand-out-feature-icon">';
			echo '<img class="wp-image-' . esc_attr( $icon_img_id ) . '" src="' . esc_url( $icon_img_URL ) . '" />';
			echo '</div>';
		}
		echo '<div class="zl-stand-out-feature-number">';
			if ( isset( $number ) && ! empty( $number ) ) {
				echo $number;
			}
		echo '</div>';
		if ( isset( $label ) && ! empty( $label ) ) {
			echo '<div class="zl-stand-out-feature-label ' . $main_label_class . '">' . esc_attr( $label ) . '</div>';
		}
		if ( isset( $secondary_label ) && ! empty( $secondary_label ) ) {
			echo '<div class="zl-stand-out-feature-label ' . $secondary_label_class . '">' . esc_attr( $secondary_label ) . '</div>';
		}
		?>
	</div>
	<?php
	$output = ob_get_clean();
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		wp_reset_postdata();
	}
	return $output;
}
