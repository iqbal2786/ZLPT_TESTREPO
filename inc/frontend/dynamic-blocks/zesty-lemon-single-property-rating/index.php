<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertyRating;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-rating", array(
		"attributes" => array(
			"main_postmeta" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"secondary_postmeta" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"main_label" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"secondary_label" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	$editor          = false;
	$rating          = 0;
	$label           = '';
	$main_label      = $attributes['main_label'];
	$secondary_label = $attributes['secondary_label'];
	$use_svg_icon    = false;
	$svg_icon        = '';
	$icon_img_URL    = '';
	$icon_img_id     = '';
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
	if ( ! empty( $attributes['main_postmeta'] ) ) {
		$rating = get_field( $attributes['main_postmeta'] );
		$label  = $main_label;
		if ( empty( $rating ) && ! empty( $attributes['secondary_postmeta'] ) ) {
			$rating = get_field( $attributes['secondary_postmeta'] );
			$label  = $secondary_label;
		}
	}
	
	if ( $editor ) {
		if ( empty( $rating ) ) {
			$rating = 5; // dummy rating
		}
		if ( empty( $label ) ) {
			$label = 'Label'; // dummy label
		}
	}

	$zl_rating_icons = get_field( 'zl_rating_icons', 'options' );
	foreach ( $zl_rating_icons as $zl_rating_icon ) {
		if ( $zl_rating_icon['rating_value'] == $rating ) {
			$use_svg_icon = $zl_rating_icon['image_svg_code'];
			$svg_icon     = $zl_rating_icon['icon_svg'];
			$icon_img_id  = $zl_rating_icon['icon_image'];
			$icon_img_URL = wp_get_attachment_image_src( $icon_img_id, 'full' );;
		}
	}


	ob_start();
	?>
	<div class="zl-stand-out-feature zl-property-rating">
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
		if ( isset( $label ) && ! empty( $label ) ) {
			echo '<div class="zl-stand-out-feature-label">' . esc_attr( $label ) . '</div>';
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
