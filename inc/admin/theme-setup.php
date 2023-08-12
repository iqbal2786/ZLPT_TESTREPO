<?php

function zesty_lemon_wp_enqueue_style( $handle, $src, $deps ) {
	wp_register_style(
		$handle,
		get_stylesheet_directory_uri() . $src,
		$deps,
		filemtime( get_stylesheet_directory() . $src )
	);
	wp_enqueue_style( $handle );
}

function zesty_lemon_wp_enqueue_script( $handle, $src, $deps ) {
	wp_register_script(
		$handle,
		get_stylesheet_directory_uri() . $src,
		$deps,
		filemtime( get_stylesheet_directory() . $src ),
		true
	);
	wp_enqueue_script( $handle );
}

 /**
 * Enqueue WordPress theme scripts and styles within Gutenberg.
 */
add_action( 'enqueue_block_editor_assets', 'zesty_lemon_gutenberg_assets' );
function zesty_lemon_gutenberg_assets() {
	$icons_library = zesty_lemon_rest_api_get_icons_library();

	// Scripts
	zesty_lemon_wp_enqueue_script( 'swiper', '/assets/shared/js/swiper-bundle.min.js', ['jquery'] );
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-blocks', '/assets/admin/js/zesty-lemon-editor-blocks.js', ['wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-api'] );
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-gutenberg', '/assets/admin/js/gutenberg.js', ['jquery'] );
	wp_localize_script( 'zesty-lemon-blocks', 'zl_icons_library', $icons_library->data );
	
	// Styles
	zesty_lemon_wp_enqueue_style( 'swiper', '/assets/shared/css/swiper-bundle.min.css', [] );
	zesty_lemon_wp_enqueue_style( 'zesty-lemon-gutenberg', '/assets/admin/css/gutenberg.css', [] );
}

 /**
 * Enqueue WordPress theme scripts and styles in Admin panel.
 */
add_action( 'admin_enqueue_scripts', 'zesty_lemon_admin_scripts' );
function zesty_lemon_admin_scripts() {
	// Scripts
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-admin-scripts', '/assets/admin/js/scripts.js', ['jquery'] );
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-acf-customization', '/assets/admin/js/acf-customization.js', ['jquery', 'wp-i18n', 'acf', 'acf-input'] );
	wp_set_script_translations( 'zesty-lemon-acf-customization', 'zestylemon' );
	wp_localize_script( 'zesty-lemon-admin-scripts', 'zestylemon', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	) );
	
	// Styles
	zesty_lemon_wp_enqueue_style( 'zesty-lemon-admin-style', '/assets/admin/css/style.css', [] );
}

 /**
 * Enqueue WordPress theme scripts and styles which are shared between Gutenberg, Admin panel, and Frontend.
 */
add_action( 'wp_enqueue_scripts', 'zesty_lemon_enqueue_shared_scripts' );
add_action( 'enqueue_block_editor_assets', 'zesty_lemon_enqueue_shared_scripts' );
function zesty_lemon_enqueue_shared_scripts() {
	global $post;
	// Scripts
	if ( is_singular( 'property' ) ) {
		zesty_lemon_wp_enqueue_script( 'jarallax', '/assets/shared/js/jarallax.min.js', ['jquery'] );
	}
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-shared-scripts', '/assets/shared/js/scripts.js', ['jquery'] );

	// Styles
	zesty_lemon_wp_enqueue_style( 'zesty-lemon-icons', '/assets/shared/css/icons.css', [] );
	zesty_lemon_wp_enqueue_style( 'zesty-lemon-shared-style', '/assets/shared/css/style.css', [] );
	// if ( has_category( array('devon', 'cornwall', 'a-z-articles') ) || has_tag( array('legacy-article') ) ) {
	if ( has_tag( array('legacy-a-z-article') ) ) {
		zesty_lemon_wp_enqueue_style( 'old-a-z-articles-style', '/assets/shared/css/old-a-z-articles-style.css', [] );
	}
}

 /**
 * Enqueue WordPress theme scripts and styles in Frontend.
 */
add_action( 'wp_enqueue_scripts', 'zesty_lemon_enqueue_scripts' );
function zesty_lemon_enqueue_scripts() {
	// Scripts
	$google_api_key = $value = apply_filters( 'zesty_lemon_google_api_key', null );
	if ( ! empty( $google_api_key ) ) {
		$language = 'en';
		wp_enqueue_script( 'google-maps-api', '//maps.googleapis.com/maps/api/js?v=3&libraries=places&key=' . $google_api_key . '&language='. $language, array(), '3', true );
	}
	zesty_lemon_wp_enqueue_script( 'jquery-ui', '/assets/frontend/js/jquery-ui.min.js', ['jquery'] );
	zesty_lemon_wp_enqueue_script( 'lightgallery', '/assets/frontend/js/lightgallery.min.js', ['jquery'] );
	zesty_lemon_wp_enqueue_script( 'lg-zoom', '/assets/frontend/js/lg-zoom.min.js', ['jquery'] );
	zesty_lemon_wp_enqueue_script( 'lg-thumbnail', '/assets/frontend/js/lg-thumbnail.min.js', ['jquery'] );
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-photo-gallery', '/assets/frontend/js/photo-gallery.js', ['jquery', 'lightgallery', 'lg-zoom', 'lg-thumbnail'] );
	zesty_lemon_wp_enqueue_script( 'zesty-lemon-frontend-scripts', '/assets/frontend/js/scripts.js', ['jquery'] );

	// Styles
	// wp_enqueue_style( 'google-fonts-montserrat', '//fonts.googleapis.com/css?family=Montserrat' );
	zesty_lemon_wp_enqueue_style( 'lightgallery-bundle', '/assets/frontend/css/lightgallery-bundle.min.css', [] );
	zesty_lemon_wp_enqueue_style( 'jquery-ui', '/assets/frontend/css/jquery-ui.min.css', [] );
	zesty_lemon_wp_enqueue_style( 'zesty-lemon-style', '/assets/frontend/css/style.css', [] );
}

add_action( 'after_setup_theme', 'zesty_lemon_setup', 99 );
function zesty_lemon_setup() {
	// Register primary menu.
	register_nav_menus(
		array(
			'copyright' => __( 'Copyright Menu', 'zestylemon' ),
		)
	);
	add_image_size( 'property-thumb', 475, 335, true );
	load_child_theme_textdomain( 'zestylemon', get_stylesheet_directory() . '/languages' );
}

add_action('admin_init', 'zesty_lemon_remove_metaboxes');
function zesty_lemon_remove_metaboxes() {
    remove_meta_box('property_locationdiv', 'property', 'normal');
    remove_meta_box('tagsdiv-property_status', 'property', 'normal');
    remove_meta_box('tagsdiv-property_bedrooms', 'property', 'normal');
    remove_meta_box('tagsdiv-property_bathrooms', 'property', 'normal');
    remove_meta_box('tagsdiv-property_sleeps', 'property', 'normal');
    remove_meta_box('tagsdiv-property_pets', 'property', 'normal');
    remove_meta_box('tagsdiv-property_type', 'property', 'normal');
    remove_meta_box('tagsdiv-property_feature', 'property', 'normal');
    remove_meta_box('tagsdiv-property_features_category', 'property', 'normal');
    remove_meta_box('tagsdiv-nearest_airport', 'property', 'normal');
    remove_meta_box('tagsdiv-nearest_station', 'property', 'normal');
    remove_meta_box('tagsdiv-nearest_beach', 'property', 'normal');
    remove_meta_box('tagsdiv-property_complex', 'property', 'normal');
    remove_meta_box('tagsdiv-property_swimming_pool_type', 'property', 'normal');
    remove_meta_box('tagsdiv-property_garden_terrace_type', 'property', 'normal');
	remove_filter( 'pre_term_description', 'wp_filter_kses' );
}

add_action('admin_menu', 'zesty_lemon_remove_sub_menus');
function zesty_lemon_remove_sub_menus() {
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_bedrooms&amp;post_type=property');
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_bathrooms&amp;post_type=property');
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_sleeps&amp;post_type=property');
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_pets&amp;post_type=property');
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_swimming_pool_type&amp;post_type=property');
    remove_submenu_page('edit.php?post_type=property', 'edit-tags.php?taxonomy=property_garden_terrace_type&amp;post_type=property');
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page( array(
        'page_title'    => __( 'Zesty Lemon Settings', 'zestylemon' ),
        'menu_title'    => __( 'Zesty Lemon Settings', 'zestylemon' ),
        'menu_slug'     => 'zesty-lemon-settings',
        'capability'    => 'manage_zestylemon_settings',
        'redirect'      => false,
    ) );
}

add_filter( 'manage_property_posts_columns', 'zesty_lemon_property_columns', 20 );
function zesty_lemon_property_columns( $columns ) {
	$columns['taxonomy-property_status'] = _x('Status', 'Properties list - Admin panel', 'zestylemon');
	$position = 1;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('thumb' => _x('Thumb', 'Properties list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 2;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('property_id' => _x('Property ID', 'Properties list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 4;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('price' => _x('Price', 'Properties list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 5;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('featured' => _x('Featured', 'Properties list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_property_posts_custom_column', 'zesty_lemon_show_property_posts_meta_info_in_columns', 10, 2 );
function zesty_lemon_show_property_posts_meta_info_in_columns( $column_name, $post_id ) {
	switch ( $column_name ) {
		case 'thumb':
			the_post_thumbnail( array(100, 100) );
			break;
		case 'property_id':
			$property_id = get_field( 'property_id', $post_id );
			echo esc_attr( $property_id );
			break;
		case 'price':
			$price = get_field( 'property_price', $post_id );
			echo get_formated_price( $price );
			break;
		case 'featured':
			$featured = get_field( 'featured_property', $post_id );
			echo '<input class="toggle-featured-property" type="checkbox" ' . checked( $featured, true, false ) . ' value="' . $post_id . '">';
			break;
		default:
			break;
	}
}

add_filter( 'post_column_taxonomy_links', 'zesty_lemon_post_column_taxonomy_links', 10, 3 );
function zesty_lemon_post_column_taxonomy_links( $term_links, $taxonomy, $terms ) {
	if ( ! is_admin() ) {
        return;
    }
	if ( $taxonomy == 'property_status' ) {
		$term_links = array();
		foreach ( $terms as $term_obj ) {
			$posts_in_term_query_variables = array(
				'post_type' => 'property',
				'taxonomy'  => $taxonomy,
				'term'      => $term_obj->slug,
			);
			$label = esc_html( sanitize_term_field( 'name', $term_obj->name, $term_obj->term_id, $taxonomy, 'display' ) );
			$bg_color = get_field( 'status_bg_color', 'property_status_' . $term_obj->term_id );
			$url = add_query_arg( $posts_in_term_query_variables, 'edit.php' );
			$term_links[] = sprintf(
				'<a href="%s" class="property_status-color" style="background-color: ' . $bg_color . '">%s</a>',
				esc_url( $url ),
				$label
			);
		}
	}
	return $term_links;
}

add_filter( 'manage_edit-property_location_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_status_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_bedrooms_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_bathrooms_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_sleeps_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_pets_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_type_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_feature_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_features_category_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-nearest_airport_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-nearest_station_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-nearest_beach_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_complex_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_swimming_pool_type_columns', 'zesty_lemon_remove_description_column' );
add_filter( 'manage_edit-property_garden_terrace_type_columns', 'zesty_lemon_remove_description_column' );
function zesty_lemon_remove_description_column( $columns ) {
	unset( $columns['description'] );
	return $columns;
}

add_filter( 'manage_edit-nearest_airport_columns', 'zesty_lemon_nearest_airport_columns' );
function zesty_lemon_nearest_airport_columns( $columns ) {
	$position = 2;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('iata' => _x('IATA airport code', 'Nearest airports list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 3;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('admin-level-1' => _x('Administrative Level 1', 'Nearest airports list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_nearest_airport_custom_column', 'zesty_lemon_show_nearest_airport_meta_info_in_columns', 10, 3 );
function zesty_lemon_show_nearest_airport_meta_info_in_columns( $string, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'iata':
			echo esc_html( get_field( 'iata_airport_code', 'nearest_airport_' . $term_id ) );
			break;
		case 'admin-level-1':
			$admin_level_1 = get_field( 'administrative_level_1', 'nearest_station_' . $term_id );
			if ( is_int( $admin_level_1 ) ) {
				$admin_level_1 = get_term( $admin_level_1 );
			}
			if ( $admin_level_1 instanceof WP_Term ) {
				$admin_level_1 = $admin_level_1->name;
				echo esc_html( $admin_level_1 );
			} else {
				echo '--';
			}
			break;
		default:
			break;
	}
}

add_filter( 'manage_edit-nearest_station_columns', 'zesty_lemon_nearest_station_columns' );
function zesty_lemon_nearest_station_columns( $columns ) {
	$position = 2;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('admin-level-1' => _x('Administrative Level 1', 'Nearest stations list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_nearest_station_custom_column', 'zesty_lemon_show_nearest_station_meta_info_in_columns', 10, 3 );
function zesty_lemon_show_nearest_station_meta_info_in_columns( $string, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'admin-level-1':
			$admin_level_1 = get_field( 'administrative_level_1', 'nearest_station_' . $term_id );
			if ( is_int( $admin_level_1 ) ) {
				$admin_level_1 = get_term( $admin_level_1 );
			}
			if ( $admin_level_1 instanceof WP_Term ) {
				$admin_level_1 = $admin_level_1->name;
				echo esc_html( $admin_level_1 );
			} else {
				echo '--';
			}
			break;
		default:
			break;
	}
}

add_filter( 'manage_edit-nearest_beach_columns', 'zesty_lemon_nearest_beach_columns' );
function zesty_lemon_nearest_beach_columns( $columns ) {
	$position = 2;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('admin-level-1' => _x('Administrative Level 1', 'Nearest beachs list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_nearest_beach_custom_column', 'zesty_lemon_show_nearest_beach_meta_info_in_columns', 10, 3 );
function zesty_lemon_show_nearest_beach_meta_info_in_columns( $string, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'admin-level-1':
			$admin_level_1 = get_field( 'administrative_level_1', 'nearest_beach_' . $term_id );
			if ( is_int( $admin_level_1 ) ) {
				$admin_level_1 = get_term( $admin_level_1 );
			}
			if ( $admin_level_1 instanceof WP_Term ) {
				$admin_level_1 = $admin_level_1->name;
				echo esc_html( $admin_level_1 );
			} else {
				echo '--';
			}
			break;
		default:
			break;
	}
}

add_filter( 'manage_edit-property_status_columns', 'zesty_lemon_property_status_columns' );
function zesty_lemon_property_status_columns( $columns ) {
	$position = 2;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('bg-color' => _x('BG Color', 'Property statuses list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 3;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('show-on-frontend' => _x('Show on front-end', 'Property statuses list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 4;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('allow-booking' => _x('Allow Booking', 'Property statuses list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_property_status_custom_column', 'zesty_lemon_show_property_status_meta_info_in_columns', 10, 3 );
function zesty_lemon_show_property_status_meta_info_in_columns( $string, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'bg-color':
			$bg_color = get_field( 'status_bg_color', 'property_status_' . $term_id );
			echo '<span class="property_status-color" style="background-color: ' . $bg_color . '"></span>';
			break;
		case 'show-on-frontend':
			$show_on_frontend = get_field( 'show_on_frontend', 'property_status_' . $term_id );
			if ( is_null( $show_on_frontend ) ) {
				$show_on_frontend = true;
			}
			echo '<input class="toggle-show-on-frontend" type="checkbox" ' . checked( $show_on_frontend, true, false ) . ' value="' . $term_id . '">';
			break;
		case 'allow-booking':
			$allow_booking = get_field( 'allow_booking', 'property_status_' . $term_id );
			if ( is_null( $allow_booking ) ) {
				$allow_booking = true;
			}
			echo '<input class="toggle-allow-booking" type="checkbox" ' . checked( $allow_booking, true, false ) . ' value="' . $term_id . '">';
			break;
		default:
			break;
	}
}

add_filter( 'manage_edit-property_type_columns', 'zesty_lemon_property_type_columns' );
function zesty_lemon_property_type_columns( $columns ) {
	$position = 2;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('bg-color' => _x('BG Color', 'Property types list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_property_type_custom_column', 'zesty_lemon_show_property_type_meta_info_in_columns', 10, 3 );
function zesty_lemon_show_property_type_meta_info_in_columns( $string, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'bg-color':
			$bg_color = get_field( 'type_bg_color', 'property_type_' . $term_id );
			echo '<span class="property_type-color" style="background-color: ' . $bg_color . '"></span>';
			break;
		default:
			break;
	}
}

add_filter( 'manage_edit-property_feature_columns', 'zesty_lemon_property_feature_columns' );
function zesty_lemon_property_feature_columns( $columns ) {
	$position = 1;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('icon' => _x('Icon', 'Property features list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 3;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('category' => _x('Category', 'Property features list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	$position = 4;
	$columns  = array_slice( $columns, 0, $position, true ) + 
		array('stand-out' => _x('Stand-out', 'Property features list - Admin panel', 'zestylemon') ) + 
		array_slice( $columns, $position, count( $columns ) - 1, true );
	return $columns;
}

add_action( 'manage_property_feature_custom_column', 'zesty_lemon_show_property_feature_meta_info_in_columns', 10, 3 );
function zesty_lemon_show_property_feature_meta_info_in_columns( $string, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'icon':
			$is_svg = get_field( 'image_svg_code', 'property_feature_' . $term_id );
			if ( $is_svg ) {
				$svg = get_field( 'icon_svg', 'property_feature_' . $term_id );
				if ( ! empty( $svg ) ) {
					echo '<div class="feature-icon-holder">' . $svg . '</div>';
				}
			} else {
				$image_id = get_field( 'icon_image', 'property_feature_' . $term_id );
				if ( ! empty( $image_id ) ) {
					echo wp_get_attachment_image( $image_id, array(35, 35) );
				}
			}
			break;
		case 'category':
			$feature_category = get_field( 'feature_category', 'property_feature_' . $term_id );
			if ( ! empty( $feature_category ) ) {
				echo '<a href="' . admin_url('/edit-tags.php?taxonomy=property_feature&post_type=property&property_features_category=' . $feature_category->term_id) . '">' . esc_html( $feature_category->name ) . '</a>';
			}
			break;
		case 'stand-out':
			$stand_out_feature = get_field( 'stand_out_feature', 'property_feature_' . $term_id );
			if ( is_null( $stand_out_feature ) ) {
				$stand_out_feature = false;
			}
			echo '<input class="toggle-stand-out-feature" type="checkbox" ' . checked( $stand_out_feature, true, false ) . ' value="' . $term_id . '">';
			break;
		default:
			break;
	}
}

add_action( 'saved_property_feature', 'zesty_lemon_saved_property_feature', 10, 3 );
function zesty_lemon_saved_property_feature( $term_id, $tt_id, $update ) {
	delete_transient( 'property_features_connected_to_extra_taxonomy' );
}

/**
* Add new custom Gutenberg blocks category
*
* @since   1.0
* @access  protected
* @param array $categories current blocks category array.
* @param object $post current post object.
* @return array
*/
add_filter( 'block_categories', 'zesty_lemon_block_category', 10, 2 );
function zesty_lemon_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'zesty-lemon-blocks',
				'title' => __( 'Zesty Lemon Blocks' , 'zestylemon' ),
			),
		)
	);
}

add_action('rank_math/vars/register_extra_replacements', 'zesty_lemon_register_extra_replacements');
function zesty_lemon_register_extra_replacements() {
	for ( $i = 1; $i <= 5; $i++ ) { 
		rank_math_register_var_replacement(
			'administrative_area_' . $i,
			[
				'name'        => sprintf( esc_html__('Administrative Area ', 'zestylemon'), $i),
				'description' => sprintf( esc_html__('Administrative Area % for the current property', 'zestylemon'), $i),
				'variable'    => 'administrative_area_' . $i,
				'example'     => call_user_func('zesty_lemon_get_administrative_area_' . $i),
			],
			'zesty_lemon_get_administrative_area_' . $i
		);
	}
}
function zesty_lemon_get_administrative_area_1() {
	return zesty_lemon_get_administrative_area(1);
}
function zesty_lemon_get_administrative_area_2() {
	return zesty_lemon_get_administrative_area(2);
}
function zesty_lemon_get_administrative_area_3() {
	return zesty_lemon_get_administrative_area(3);
}
function zesty_lemon_get_administrative_area_4() {
	return zesty_lemon_get_administrative_area(4);
}
function zesty_lemon_get_administrative_area_5() {
	return zesty_lemon_get_administrative_area(5);
}
function zesty_lemon_get_administrative_area( $level ) {
	if ( is_admin() ) {
		return 'Administrative Area ' . $level;
	}
	if ( is_singular( 'property' ) ) {
		$location = get_field('administrative_level_' . $level);
		$location = get_term( $location, 'property_location' );
		if ( ! empty( $location ) && ! is_wp_error( $location ) ) {
			return $location->name;
		}
	}
	return '';
}