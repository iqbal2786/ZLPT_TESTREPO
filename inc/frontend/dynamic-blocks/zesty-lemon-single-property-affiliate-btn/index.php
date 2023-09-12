<?php

namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyAffiliateBtn;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-affiliate-btn", array(
		"attributes" => array(
			"btn_text" => array(
				"type" 		=> "string",
				"default" 	=> __("Check availability or make a booking", "zestylemon")
			),
			"btn_text_not_allowed" => array(
				"type" 		=> "string",
				"default" 	=> __("Unavailable to book. Click for other properties in the area.", "zestylemon")
			),
			"full_width" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
			"text_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--base-3)"
			),
			"bg_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--accent)"
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;
	$editor = false;
	$text_color = $bg_color = '';
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
	if ( empty( $attributes['btn_text'] ) ) {
		$attributes['btn_text'] = '...';
	}
	$gocode_url = '';
	if ( ! $editor && function_exists('get_field') ) {
		$gocode     = get_field('gocode');
		$gocode_url = site_url( '/go/' . $gocode );
	}
	$class = '';
	if ( isset( $attributes['full_width'] ) && $attributes['full_width'] ) {
		$class = 'full-width';
	}
	if ( isset( $attributes['text_color'] ) && ! empty( $attributes['text_color'] ) ) {
		$text_color = $attributes['text_color'];
	}
	if ( isset( $attributes['bg_color'] ) && ! empty( $attributes['bg_color'] ) ) {
		$bg_color = $attributes['bg_color'];
	}
	ob_start();
	$style = '';
	if ( ! empty( $text_color ) ) {
		$style .= 'color: ' . esc_attr( $text_color ) . ';';
	}
	if ( ! empty( $bg_color ) ) {
		$style .= 'background-color: ' . esc_attr( $bg_color ) . ';';
	}
	$btn_text = $attributes['btn_text'];
	$btn_link = $gocode_url;
	if ( ! $editor && function_exists('get_field') ) {
		$status = get_field('property_status');
		$allow_booking = get_field( 'allow_booking', 'property_status_' . $status );
		if ( ! $allow_booking ) {
			$btn_text = $attributes['btn_text_not_allowed'];
			// $location = '';
			// for ( $i = 5; $i >= 1 && empty( $location ); $i-- ) { 
			// 	$location = get_field('administrative_level_' . $i);
			// }
			// if ( ! empty( $location ) ) {
			// 	$btn_link = get_term_link( $location, 'property_location' );
			// } else {
			// 	$btn_link = '#';
			// }
		}
	}
	
	?>
	<div class="zl-block-container">
		<div class="zesty-lemon-single-property-affiliate-btn <?php echo $class; ?>">
			<a class="zl-cta-button" href="<?php echo ( ! $editor ) ? esc_url( $btn_link ) : 'javascript:;'; ?>" style="<?php echo $style; ?>">
				<span class="zl-button-text">
					<?php echo esc_attr( $btn_text ); ?>
					<span class="zl-button-icon">
						<svg aria-hidden="true" role="img" height="1em" width="1em" viewBox="0 0 256 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg>
					</span>
				</span>
			</a>
		</div>
	</div>
	<?php
	$output = ob_get_clean();
	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
		wp_reset_postdata();
	}
	return $output;
}
