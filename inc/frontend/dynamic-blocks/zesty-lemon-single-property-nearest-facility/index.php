<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertyNearestFacility;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-nearest-facility", array(
		"attributes" => array(
			"repeater_field" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"name_field" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"distance_field" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"name_field_prefix" => array(
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
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	$editor         = false;
	$use_svg_icon   = false;
	$svg_icon       = '';
	$icon_img_URL   = '';
	$icon_img_id    = '';
	$repeater_field = $name_field = $distance_field = '';
	$name = $distance = '';
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
	if ( ! empty( $attributes['repeater_field'] ) ) {
		$repeater = get_field( $attributes['repeater_field'] );
		if ( isset( $repeater[0] ) ) {
			$nearest_facility = $repeater[0];
			if ( ! empty( $attributes['name_field'] ) && isset( $nearest_facility[ $attributes['name_field'] ] ) ) {
				$name = $nearest_facility[ $attributes['name_field'] ];
				if ( is_int( $name ) ) {
					$name_term = get_term( $name );
					$name      = $name_term->name;
				}
			}
			if ( ! empty( $attributes['distance_field'] ) && isset( $nearest_facility[ $attributes['distance_field'] ] ) ) {
				$distance = $nearest_facility[ $attributes['distance_field'] ];
			}
		}
	}

	if ( $editor ) {
		if ( empty( $name ) ) {
			$name = 'Facility name'; // dummy name
		}
		if ( empty( $distance ) ) {
			$distance = '10 KM'; // dummy distance
		}
	}

	if ( ! empty( $attributes['name_field_prefix'] ) && isset( $attributes['name_field_prefix'] ) ) {
		$name = esc_attr( $attributes['name_field_prefix'] ) . ': ' . $name;
	}
	
	ob_start();
	?>
	<div class="zl-stand-out-feature zl-property-nearest-facility">
		<?php
		if ( $use_svg_icon && ! empty( $svg_icon ) ) {
			echo '<div class="zl-stand-out-feature-icon">' . $svg_icon . '</div>';
		}
		if ( ! $use_svg_icon && ! empty( $icon_img_URL ) ) {
			echo '<div class="zl-stand-out-feature-icon">';
			echo '<img class="wp-image-' . esc_attr( $icon_img_id ) . '" src="' . esc_url( $icon_img_URL ) . '" />';
			echo '</div>';
		}
		echo '<div class="zl-stand-out-feature-number"></div>';
		if ( isset( $name ) && ! empty( $name ) ) {
			echo '<div class="zl-stand-out-feature-label">' . esc_attr( $name ) . '</div>';
		}
		if ( isset( $distance ) && ! empty( $distance ) ) {
			echo '<div class="zl-stand-out-feature-label zl-secondary-label">' . esc_attr( $distance ) . '</div>';
		}
		/*echo '<div class="zl-stand-out-feature-number"></div>';
		if ( isset( $label ) && ! empty( $label ) ) {
			echo '<div class="zl-stand-out-feature-label">' . esc_attr( $label ) . '</div>';
		}*/
		?>
	</div>
	<?php
	$output = ob_get_clean();
	if ( $editor ) {
		wp_reset_postdata();
	}
	return $output;
}
