<?php

namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyFeatures;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/single-property-features", array(
		"attributes" => array(
			"headline_tag" => array(
				"type" 		=> "string",
				"default" 	=> "h2"
			),
			"features_title" => array(
				"type" 		=> "string",
				"default" 	=> __("Property Features", "zestylemon")
			),
			"bg_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--accent)"
			),
			"title_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--base-3)"
			),
			"text_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--contrast)"
			),
			"show_features_icons" => array(
				"type" 		=> "boolean",
				"default" 	=> false
			),
			"features_icons_width" => array(
				"type" 		=> "number",
				"default" 	=> 30
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	global $wp_query;
	$editor = false;
	$show_features_icons = false;

	$bg_color = $title_color = $text_color = '';

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

	$headline_tag = $attributes['headline_tag'];
	if ( empty( $headline_tag ) || ! in_array( $headline_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6') ) ) {
		$headline_tag = 'h2';
	}

	if ( isset( $attributes['bg_color'] ) && ! empty( $attributes['bg_color'] ) ) {
		$bg_color = $attributes['bg_color'];
	}
	if ( isset( $attributes['title_color'] ) && ! empty( $attributes['title_color'] ) ) {
		$title_color = $attributes['title_color'];
	}
	if ( isset( $attributes['text_color'] ) && ! empty( $attributes['text_color'] ) ) {
		$text_color = $attributes['text_color'];
	}

	$property_features_categories = array();
	$property_features_categories_temp = get_terms( array(
		'taxonomy'   => 'property_features_category',
		'hide_empty' => false,
	) );
	$args = array(
		'taxonomy'   => 'property_feature',
		'meta_query' => array(
			'relation' => 'AND',
			'feature_category' => array(
				'key'       => 'feature_category',
				'value'     => 0,
				'compare'   => '='
			),
			/*
			'stand_out_feature' => array(
				'relation' => 'OR',
				array(
					'key'       => 'stand_out_feature',
					'value'     => 'not exists',
					'compare'   => 'NOT EXISTS'
				),
				array(
					'key'       => 'stand_out_feature',
					'value'     => '0',
					'compare'   => '='
				),
				array(
					'key'       => 'stand_out_feature',
					'value'     => [''],
					'compare'   => 'IN'
				),
			),
			*/
		)
	);
	if ( ! $editor ) {
		$args['hide_empty'] = true;
		$args['object_ids'] = [ get_the_ID() ];
	}
	foreach ( $property_features_categories_temp as $category ) {
		$args['meta_query']['feature_category']['value'] = $category->term_id;
		$terms = get_terms( $args );
		if ( $editor || ! empty( $terms ) ) {
			$property_features_categories[ $category->term_id ] = array(
				'category' => array(
					'name' => $category->name,
					'slug' => $category->slug,
				),
				'terms' => $terms,
			);
		}
	}
	$args['meta_query']['feature_category'] = array(
		'relation' => 'OR',
		array(
			'key'       => 'feature_category',
			'value'     => 'not exists',
			'compare'   => 'NOT EXISTS'
		),
		array(
			'key'       => 'feature_category',
			'value'     => '0',
			'compare'   => '='
		),
		array(
			'key'       => 'feature_category',
			'value'     => [''],
			'compare'   => 'IN'
		),
	);
	$terms = get_terms( $args );
	if ( $editor || ! empty( $terms ) ) {
		$property_features_categories[ 'dummy' ] = array(
			'category' => array(
				'name' => 'Other Features',
				'slug' => 'dummy',
			),
			'terms' => $terms,
		);
	}
	if ( isset( $attributes['show_features_icons'] ) && $attributes['show_features_icons'] ) {
		$show_features_icons = true;
	}
	if ( ! isset( $attributes['features_icons_width'] ) || empty( $attributes['features_icons_width'] ) ) {
		$attributes['features_icons_width'] = 15;
	} else {
		$attributes['features_icons_width'] = intval( $attributes['features_icons_width'] );
	}
	$icons_style = 'width: ' . $attributes['features_icons_width'] . 'px;';
	ob_start();

	$style = '';
	if ( ! empty( $bg_color ) ) {
		$style = 'background-color: ' . esc_attr( $bg_color );
	}
	?>
	<div class="zl-block-container" style="<?php echo $style; ?>">
		<div class="zl-single-property-features-wrapper">
			<!--<h2 class="zl-single-property-features-headline">Property Features</h2>-->
			<?php
			//echo '<' . $headline_tag . ' class="zl-single-property-features-headline" style="' . $style . '">' . esc_attr( $attributes['what_we_like_title'] ) . '</' . $headline_tag . '>';
			if ( isset( $attributes['features_title'] ) && ! empty( $attributes['features_title'] ) ) {
				$style = '';
				if ( ! empty( $title_color ) ) {
					$style = 'color: ' . esc_attr( $title_color );
				}
				$attributes['features_title'] = str_replace('{{title}}', get_the_title(), $attributes['features_title']);
				echo '<' . $headline_tag . ' class="zl-single-property-features-headline" style="' . $style . '">'.esc_attr( $attributes['features_title'] ) .'</' . $headline_tag . '>';
			}
			?>

			<?php
			$style = '';
			if ( ! empty( $text_color ) ) {
				$style = 'color: ' . esc_attr( $text_color );
			}
			foreach ( $property_features_categories as $category_array ) {
				echo '<div class="zl-single-property-features-category-wrapper">';
					echo '<h3 class="zl-single-property-features-category">' . esc_attr( $category_array['category']['name'] ) . '</h3>';
					echo '<ul class="zl-single-property-features" style="'.$style.'">';
						foreach ( $category_array['terms'] as $feature_term ) {
							$icon = '<span class="zl-property-feature-icon zl-icon-check"></span>';
							if ( $show_features_icons ) {
								$use_svg_icon  = get_field( 'image_svg_code', 'property_feature_' . $feature_term->term_id );
								$svg_icon      = get_field( 'icon_svg', 'property_feature_' . $feature_term->term_id );
								$icon_img_id   = get_field( 'icon_image', 'property_feature_' . $feature_term->term_id );		
								$icon_img_URL  = wp_get_attachment_image_src( $icon_img_id, 'full' );
								if ( $use_svg_icon && ! empty( $svg_icon ) ) {
									$icon = '<span class="zl-property-feature-icon" style="' . $icons_style . '">' . $svg_icon . '</span>';
								}
								if ( ! $use_svg_icon && ! empty( $icon_img_URL ) ) {
									$icon  = '<span class="zl-property-feature-icon" style="' . $icons_style . '">';
									$icon .= '<img class="wp-image-' . esc_attr( $icon_img_id ) . '" src="' . esc_url( $icon_img_URL ) . '" />';
									$icon .= '</span>';
								}
							}
							echo '<li>' . $icon . $feature_term->name . '</li>';
						}
					echo '</ul>';
				echo '</div>';
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
