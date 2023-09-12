<?php

namespace ZestyLemon\Gutenberg\Blocks\PropertiesCarousel;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/properties-carousel", array(
		"attributes" => array(
			"headline_tag" => array(
				"type" 		=> "string",
				"default" 	=> "h2"
			),
			"properties_count" => array(
				"type" 		=> "number",
				"default" 	=> "15"
			),
			"properties_order" => array(
				"type" 		=> "string",
				"default" 	=> "global"
			),
			"properties_display" => array(
				"type" 		=> "string",
				"default" 	=> "all"
			),
			"properties_status" => array(
				"type" 		=> "string",
				"default" 	=> "all"
			),
			"properties_location" => array(
				"type" 		=> "array",
				"default" 	=> array()
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	zesty_lemon_wp_enqueue_style( 'swiper', '/assets/shared/css/swiper-bundle.min.css', [] );
	zesty_lemon_wp_enqueue_script( 'swiper', '/assets/shared/js/swiper-bundle.min.js', ['jquery'] );
	global $wp_query;
	$tax_query    = array();
	$meta_query   = array();
	$headline_tag = $attributes['headline_tag'];
	if ( empty( $headline_tag ) || ! in_array( $headline_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6') ) ) {
		$headline_tag = 'h2';
	}
	if ( ! isset( $attributes['properties_count'] ) || empty( $attributes['properties_count'] ) ) {
		$attributes['properties_count'] = 15;
	} else {
		$attributes['properties_count'] = intval( $attributes['properties_count'] );
	}
	if ( ! isset( $attributes['properties_order'] ) || empty( $attributes['properties_order'] ) ) {
		$attributes['properties_order'] = 'global';
	}
	if ( ! isset( $attributes['properties_display'] ) || empty( $attributes['properties_display'] ) ) {
		$attributes['properties_display'] = 'all';
	}
	$query_vars = array(
		'post_type'      => 'property',
		'posts_per_page' => $attributes['properties_count'],
	);
	switch ( $attributes['properties_order'] ) {
		case 'date_desc':
			$query_vars['orderby'] = 'date';
			$query_vars['order']   = 'DESC';
			break;
		case 'date_asc':
			$query_vars['orderby'] = 'date';
			$query_vars['order']   = 'ASC';
			break;
		case 'title_asc':
			$query_vars['orderby'] = 'title';
			$query_vars['order']   = 'ASC';
			break;
		case 'title_desc':
			$query_vars['orderby'] = 'title';
			$query_vars['order']   = 'DESC';
			break;
		case 'rand':
			$query_vars['orderby'] = 'rand';
			break;
		case 'global':
		default:
			break;
	}
	$statuses_not_show_on_front = get_terms( array(
		'taxonomy'   => 'property_status',
		'fields'     => 'ids',
		'hide_empty' => false,
		'meta_query' => array(
			array(
				'key'     => 'show_on_frontend',
				'value'   => array('1'),
				'compare' => 'IN'
			)
		),
	) );
	if ( ! empty( $statuses_not_show_on_front ) ) {
		$tax_query['property_status'] = array(
			'taxonomy' => 'property_status',
			'terms'    => $statuses_not_show_on_front,
			'operator' => 'IN',
		);
	}
	switch ( $attributes['properties_display'] ) {
		case 'featured':
			$meta_query['featured_property'] = array(
				'key'     => 'featured_property',
				'value'   => '1',
				'compare' => '=',
			);
			break;
		case 'status':
			if ( isset( $attributes['properties_status'] ) ) {
				$attributes['properties_status'] = esc_attr( $attributes['properties_status'] );
			}
			if ( ! isset( $attributes['properties_status'] ) || empty( $attributes['properties_status'] ) ) {
				$attributes['properties_status'] = 'all';
			}
			if ( $attributes['properties_status'] != 'all' ) {
				$tax_query['property_status'] = array(
					'relation' => 'AND',
					$tax_query['property_status'],
					array(
						'taxonomy' => 'property_status',
						'field'    => 'slug',
						'terms'    => $attributes['properties_status'],
						'operator' => 'IN',
					),
				);
			}
			break;
		case 'all':
		default:
			break;
	}

	if ( isset( $attributes['properties_location'] ) ) {
		$attributes['properties_location'] = array_map( 'esc_attr', $attributes['properties_location'] );
		$attributes['properties_location'] = array_filter( $attributes['properties_location'] );
	} else {
		$attributes['properties_location'] = array();
	}
	if ( ! empty( $attributes['properties_location'] ) ) {
		$tax_query['property_location'] = array(
			'taxonomy' => 'property_location',
			'field'    => 'slug',
			'terms'    => $attributes['properties_location'],
			'operator' => 'IN',
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( count( $tax_query ) > 0 ) {
		$query_vars['tax_query'] = $tax_query;
	}
	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'OR';
	}
	if ( count( $meta_query ) > 0 ) {
		$query_vars['meta_query'] = $meta_query;
	}
	
	$properties = new \WP_Query( $query_vars );
	$properties_details = array();
	if ( $properties->have_posts() ) {
		while ( $properties->have_posts() ) {
			$properties->the_post();
			$property_id = get_the_ID();
			$properties_details[] = array(
				'ID'        => $property_id,
				'title'     => get_the_title(),
				'price'     => get_formated_price( get_field('property_price') ),
				'thumb'     => get_the_post_thumbnail( $property_id, 'property-thumb' ),
				'url'       => get_permalink(),
			);
		}
		wp_reset_postdata();
	}
	ob_start();
	?>
		<div class="zl-block-container">
			<div class="zl-properties-carousel-wrapper swiper swiper-container">
				<div class="swiper-wrapper">
					<?php
						foreach ( $properties_details as $property ) {
							?>
							<div class="swiper-slide" onclick="location.href='<?php echo ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) ? 'javascript:;' : esc_url( $property['url'] ); ?>';">
								<div class="slider-swiper-text text-center">
									<<?php echo $headline_tag; ?> class="entry-title-swiper"><a href="<?php echo esc_url( $property['url'] ); ?>"><?php echo esc_html( $property['title'] ); ?></a></<?php echo $headline_tag; ?>>		
									<span class="slider-swiper-price"><?php echo __('Price:', 'zestylemon') . ' ' . esc_html( $property['price'] ); ?></span>
								</div>
								<div class="zl-property-thumb" >
									<a href="<?php echo ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) ? 'javascript:;' : esc_url( $property['url'] ); ?>">
										<?php echo $property['thumb']; ?>
									</a>
								</div>
							</div>
							<?php
						}
					?>
				</div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
