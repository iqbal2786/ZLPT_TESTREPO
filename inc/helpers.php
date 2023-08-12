<?php

function slugify($text)
{
	// replace non letter or digits by -
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);
	// trim
	$text = trim($text, '-');
	// remove duplicate -
	$text = preg_replace('~-+~', '-', $text);
	// lowercase
	$text = strtolower($text);
	if (empty($text)) {
		return 'n-a';
	}
	return $text;
}

function get_terms_ready_for_drop_menu( $taxonomy ) {
	$terms = get_terms( array(
		'taxonomy' => $taxonomy
	) );
	$terms_ready_for_drop_menu = array();
	foreach ( $terms as $term_obj ) {
		$terms_ready_for_drop_menu[ $term_obj->slug ] = $term_obj->name;
	}
	if ( in_array( $taxonomy, array('property_bedrooms', 'property_sleeps') ) ) {
		$temp = $terms_ready_for_drop_menu;
		$terms_ready_for_drop_menu = array();
		sort( $temp );
		foreach ( $temp as $value ) {
			$terms_ready_for_drop_menu[ $value ] = $value;
		}
	}
	return $terms_ready_for_drop_menu;
}

add_filter( 'zesty_lemon_google_api_key', 'zesty_lemon_google_api_key' );
function zesty_lemon_google_api_key( $key ) {
	if ( defined('ZL_GOOGLE_MAPS_API') ) {
		return ZL_GOOGLE_MAPS_API;
	}
	return 'AIzaSyDSsdrv4xmFELdN3SOzx1GrYIIPT1trG1Y';
}

add_action('acf/init', 'my_acf_init');
function my_acf_init() {
	$key = apply_filters( 'zesty_lemon_google_api_key', null );
	acf_update_setting('google_api_key', $key);
}

function property_currency() {
	// TBD: the currency symbol should come from the settings page
	return '£';
}

function get_formated_price( $price ) {
	if ( empty( $price ) ) {
		return 'POA';
	}
	// TBD: these options should come from the settings page
	$price_format      = 'comma';
	$currency_position = 'left';
	$round_price       = false;
	$decimals          = 2;
	$dec_point         = '.';
	$thousands_sep     = ',';
	$price             = floatval( $price );
	if ( $round_price ) {
		$decimals = 0;
	}
	if ( $price_format != 'comma' ) {
		$dec_point     = ',';
		$thousands_sep = '.';
	}
	$price = number_format( $price, $decimals, $dec_point, $thousands_sep);
	if ( $currency_position == 'left' ) {
		$price = property_currency() . $price;
	} else {
		$price = $price . property_currency();
	}
	return $price;
}

function get_meta_values($key = '', $type = 'post', $status = 'publish') {
	global $wpdb;
	if ( empty( $key ) ) {
		return;
	}
	$values = $wpdb->get_col( $wpdb->prepare( "
		SELECT pm.meta_value FROM {$wpdb->postmeta} pm
		LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		WHERE pm.meta_key = '%s' 
		AND p.post_status = '%s' 
		AND p.post_type = '%s'
  	", $key, $status, $type ) );
	return $values;
}

function get_max_property_price() {
	$values = get_meta_values('property_price', 'property');
	if ( empty( array_filter( $values ) ) ) {
		return 0;
	}
	$max = max( array_filter( $values ) );
	if ( ! $max ) {
		return 0;
	}
	$last_digit = substr( $max, 0, 1 );
	$factor = intval( '1' . str_repeat( '0', strlen( $max ) - 1 ) );
	$max = ( intval( $last_digit ) + 1 ) * $factor;
	if ( empty( $max ) ) {
		$max = 12000;
	}
	return $max;
}

function get_property_permalink() {
	$property_id = get_the_ID();
	$breadcumbs = array();
	for ( $i = 1; $i <= 5; $i++ ) { 
		$admin_level = get_breadcrumbs_admin_level( $i );
		if ( ! empty( $admin_level ) ) {
			$breadcumbs[] = $admin_level;
		}
	}
	$breadcumbs[] = '<span class="zl-breadcrumbs-property-name">' . get_the_title() . '</span>';
	$breadcumbs   = implode( '<span class="zl-breadcrumbs-separator">&gt;</span>', $breadcumbs );
	if ( ! empty( $breadcumbs ) ) {
		$breadcumbs = '<div class="zl-breadcrumbs">' . $breadcumbs . '</div>';
	}
	return $breadcumbs;
}

function get_breadcrumbs_admin_level( $level ) {
	$return = '';
	if ( function_exists('get_field') ) {
		$admin_level = get_field( 'administrative_level_' . $level );
		if ( ! empty( $admin_level ) ) {
			$admin_level = get_term( $admin_level );
			if ( $admin_level instanceof WP_Term ) {
				$link = get_term_link( $admin_level );
				$return = '<span><a href="' . esc_url( $link ) . '">' . esc_attr( $admin_level->name ) . '</a></span>';
			}
		}
	}
	return $return;
}

function zesty_lemon_limit_words( $string, $word_limit ) {
	$words = explode( ' ', $string );
	return implode( ' ', array_slice( $words, 0, $word_limit ) );
}

function zl_get_icon_from_library( $icon_slug ) {
	$zl_icons_library = get_field( 'zl_icons_library', 'options' );
	foreach ( $zl_icons_library as $icon ) {
		if ( $icon_slug == slugify( $icon['icon_name'] ) ) {
			$icon = array(
				'icon'           => $icon['icon_name'],
				'icon_slug'      => slugify( $icon['icon_name'] ),
				'use_svg'        => $icon['image_svg_code'],
				'icon_image'     => $icon['icon_image'],
				'icon_image_url' => wp_get_attachment_image_url( $icon['icon_image'], 'full' ),
				'icon_svg'       => $icon['icon_svg'],
			);
			return $icon;
		}
	}
	return false;
}