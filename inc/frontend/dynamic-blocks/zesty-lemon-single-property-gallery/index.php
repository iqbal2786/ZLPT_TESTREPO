<?php

namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyGallery;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-gallery", array(
		"attributes" => array(
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;
	$editor = false;
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		$editor = true;
		$args = array(
			'post_type' => 'property'
		);
		$properties = new \WP_Query( $args );
		if ( $properties->have_posts() ) {
			$properties->the_post();
		}
	}
	$gallery = get_field('property_gallery');
	ob_start();
	?>
	<div class="zl-block-container">
		<div class="zl-single-property-gallery">
			<?php
			if ( is_array( $gallery ) ) {
				foreach ( $gallery as $image_id ) {
					$full_image_data = wp_get_attachment_image_src( $image_id, 'full' );
					$alt_text = get_post_meta ( $image_id, '_wp_attachment_image_alt', true );
					echo '<a data-lg-size="' . esc_attr( $full_image_data[1] ) . '-' . esc_attr( $full_image_data[2] ) . '" class="zl-gallery-item" data-src="' . esc_url( $full_image_data[0] ) . '" data-sub-html="<p>' . esc_attr( $alt_text ) . '</p>">';
						echo wp_get_attachment_image( $image_id, 'property-thumb' );
						echo '<div class="zl-gallery-item-overlay"></div>';
					echo '</a>';
				}
			}
			?>
		</div>
	</div>
	<?php
	$output = ob_get_clean();
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		wp_reset_postdata();
	}
	return $output;
}
