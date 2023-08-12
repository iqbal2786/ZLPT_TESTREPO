<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

require_once 'inc/frontend/dynamic-blocks/zesty-lemon-search-box-block/property-locations-walker.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-icon-box/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-search-box-block/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-property-archive-item/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-properties-modify-search-btn/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-properties-horizontal-filters/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-properties-map/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-properties-archive-title/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-properties-carousel/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-properties-grid/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-posts-carousel/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-hero/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-stand-out-feature/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-stand-out-feature-extended/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-map/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-other-properties/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-affiliate-btn/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-gallery/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-features/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-rating/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-nearest-facility/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-good-for/index.php';
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-what-we-like/index.php';
// require_once 'inc/frontend/dynamic-blocks/example-dynamic/index.php';

require_once 'inc/helpers.php';
require_once 'inc/admin/rest-api.php';
require_once 'inc/admin/theme-setup.php';
require_once 'inc/admin/capabilities.php';
require_once 'inc/admin/custom-post-types.php';
require_once 'inc/admin/site-urls.php';
require_once 'inc/admin/acf/acf-fields.php';
require_once 'inc/admin/acf/class-acf-custom-walker-taxonomy-field.php';
require_once 'inc/admin/acf/acf-customization.php';
require_once 'inc/admin/ajax-handlers.php';
require_once 'inc/admin/properties-filters.php';
require_once 'inc/admin/properties-features-filters.php';

require_once 'inc/frontend/rankmath-hooks.php';
require_once 'inc/frontend/properties-search.php';
require_once 'inc/frontend/hooks.php';

// Stop 'article tags' from displaying on tag archives
add_filter('generate_show_tags',function(){
	return '';
});

// Alter the search result to hide the home page
function search_filter_posts_callback($query) {
	if ($query->is_search()) {
		$home_page_id = get_option('page_on_front');
		$query->set('post__not_in', array($home_page_id));
	}
	return $query;
}
add_filter('pre_get_posts','search_filter_posts_callback');

// Filter Element for Search page
add_filter( 'generate_element_display', function( $display, $element_id ) {
	/*	
		Other - HCDC
		 74 - 52135 - Blog – article item
		 77 - 52138
		 78 - 52139
		 79 - 52140
		188 - 52294
		189 - 52295
		190 - 52296 - Properties archives – Header
		191 - 52297 - Properties archives – Item
		192 - 52298 - Properties archives  Layout
		193 - 52299
	*/

	if( !isset($_GET['properties']) 
		&& is_search()){
		if( in_array($element_id, ZL_SEARCH_PAGE_ELEMENID) ){
			$display = true;
		}else{
			$display = false;
		}
	}

    return $display;
}, 10, 2 );

// PT - 16 'Updated date' shown on updated articles published the same day
function get_the_modified_time_callback($the_time, $format, $post ){
	if ( 'post' === get_post_type() && !is_admin() ) {
		if (esc_html(get_post_time( 'j F Y')) == esc_html( date('j F Y',$the_time) )) {
			return false;
		}
		return $the_time;
	}
	return $the_time;
}
add_filter('get_the_modified_time', 'get_the_modified_time_callback', 10, 3 );


//PT-5 :  Pages with no Google map preconnect to maps.googleapis.com
//* Location archive pages - including property search results page
//* Property page
add_action('wp_head', 'zestylemon_preconnect_callback', 1);
function zestylemon_preconnect_callback() {
	if( 
		(isset($_GET['properties']) && $_GET['properties'] == 'yes' && is_search()) 
		||
		(is_tax('property_location')) 
		||
		(is_singular('property')) 
	){
		echo "<link rel='preconnect' href='https://maps.googleapis.com'>";
	}
}


//PT-15 : Element required to customise author archive page
function init_callback(){
	remove_action( 'generate_after_archive_title', 'generate_do_archive_description' );	
	add_action( 'generate_after_archive_title', 'generate_do_archive_description_customize' );

	/*
		Remove feature image hook as feature image is set by the Element Template of Generatepress
	*/
	remove_action( 'generate_after_header', 'generate_featured_page_header', 10 );	
}
add_action('init', 'init_callback');

function generate_do_archive_description_customize() {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$term_description = "";
	if ( is_author() ) {
		if($paged == 1 ){
			$author_id = get_the_author_meta('ID');
			$term_description = get_field('advance_bio', 'user_'. $author_id);	
		}else{
			$term_description = get_the_archive_description();
		}
		if ( ! empty( $term_description ) ) {
			printf( '<div class="author-info">%s</div>', $term_description );
		}
	} else {
		$term_description = get_the_archive_description();
		if ( ! empty( $term_description ) ) {
			printf( '<div class="taxonomy-description">%s</div>', $term_description );
		}	
	}
	
	do_action( 'generate_after_archive_description' );
}