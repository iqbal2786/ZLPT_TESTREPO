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
require_once 'inc/frontend/dynamic-blocks/zesty-lemon-single-property-food-and-drink/index.php';
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


/*
	PT-36 : Only display an article's 'primary category' of blog archive pages
*/
function zestylemon_generate_dynamic_element_text_output_callback( $term_output, $block ){

	if ( 'generateblocks/headline' === $block['blockName'] || 'generateblocks/button' === $block['blockName'] ) {
		if ( ! empty( $block['attrs']['gpDynamicTextType'] ) && ! empty( $block['attrs']['gpDynamicTextReplace'] ) ) {
			$text_to_replace 	= $block['attrs']['gpDynamicTextReplace'];
			$text_type 			= $block['attrs']['gpDynamicTextType'];
			$link_type 			= ! empty( $block['attrs']['gpDynamicLinkType'] ) ? $block['attrs']['gpDynamicLinkType'] : '';
			$source 			= ! empty( $block['attrs']['gpDynamicSource'] ) ? $block['attrs']['gpDynamicSource'] : 'current-post';
			$attributes 		= $block['attrs'];

			/*
			*Get the Source ID logic start here
			*/
			$id 				= get_the_ID();
			if ( 'next-post' === $source ) {
				$in_same_term 	= ! empty( $attributes['gpDynamicSourceInSameTerm'] ) ? true : false;
				$term_taxonomy 	= ! empty( $attributes['gpDynamicSourceInSameTermTaxonomy'] ) ? $attributes['gpDynamicSourceInSameTermTaxonomy'] : 'category';
				$next_post 		= get_next_post( $in_same_term, '', $term_taxonomy );

				if ( ! is_object( $next_post ) ) {
					return false;
				}

				$id 			= $next_post->ID;
			}

			if ( 'previous-post' === $source ) {
				$in_same_term 	= ! empty( $attributes['gpDynamicSourceInSameTerm'] ) ? true : false;
				$term_taxonomy 	= ! empty( $attributes['gpDynamicSourceInSameTermTaxonomy'] ) ? $attributes['gpDynamicSourceInSameTermTaxonomy'] : 'category';
				$previous_post 	= get_previous_post( $in_same_term, '', $term_taxonomy );

				if ( ! is_object( $previous_post ) ) {
					return false;
				}

				$id = $previous_post->ID;
			}

			/*
			*Get the Source ID logic end here
			*/
			if ( ! $id ) {
				return '';
			}

			if ( 'terms' === $text_type && 'generateblocks/headline' === $block['blockName'] ) {
				if ( ! empty( $block['attrs']['gpDynamicTextTaxonomy'] ) ) {

					$term_id = get_post_meta( $id, 'rank_math_primary_'.$block['attrs']['gpDynamicTextTaxonomy'], true );
					if(!empty( $term_id )){
						$term = get_term( $term_id, $block['attrs']['gpDynamicTextTaxonomy'] );	
						$terms[] = $term;
					}else{
						$terms = get_the_terms( $id, $block['attrs']['gpDynamicTextTaxonomy'] );	
					}
					
					if ( is_wp_error( $terms ) ) {
						return $block_content;
					}

					$term_items = array();

					foreach ( (array) $terms as $term ) {
						if ( ! isset( $term->name ) ) {
							continue;
						}

						if ( 'term-archives' === $link_type ) {
							$term_link = get_term_link( $term, $block['attrs']['gpDynamicTextTaxonomy'] );

							if ( ! is_wp_error( $term_link ) ) {
								$term_items[] = sprintf(
									'<span class="post-term-item term-%3$s"><a href="%1$s">%2$s</a></span>',
									rtrim(esc_url( get_term_link( $term, $block['attrs']['gpDynamicTextTaxonomy'] ) ), '/'),
									$term->name,
									$term->slug
								);
							}
						} else {
							$term_items[] = sprintf(
								'<span class="post-term-item term-%2$s">%1$s</span>',
								$term->name,
								$term->slug
							);
						}
					}

					if ( empty( $term_items ) ) {
						return '';
					}

					$sep = isset( $block['attrs']['gpDynamicTextTaxonomySeparator'] ) ? $block['attrs']['gpDynamicTextTaxonomySeparator'] : ', ';
					$term_output = implode( $sep, $term_items );

					if ( ! empty( $block['attrs']['gpDynamicTextBefore'] ) ) {
						$term_output = $block['attrs']['gpDynamicTextBefore'] . $term_output;
					}

				} else {
					return '';
				}
			}
		}
	}

	return $term_output;
}
add_filter('generate_dynamic_element_text_output', 'zestylemon_generate_dynamic_element_text_output_callback', 10, 2);


/*
 * PT- 37  Move blog archive pages to a different URL 
*/
function zestylemon_custom_category_permalink_post( $permalink, $post, $leavename ) {
    // Get the categories for the post
    $category = get_the_category($post->ID); 
    foreach( ZESTYLEMON_REWRITE_URL_ARRAY as $term_id => $term_data ){
		if (  !empty($category) && $category[0]->term_id == $term_id ) {
	        $permalink = trailingslashit( home_url($term_data['new-url'].'/'. $post->post_name . '/' ) );
	    }
	}
    return $permalink;
}
if(ZESTYLEMON_ENABLE_REWRITE_URL){
	//add_filter( 'post_link', 'zestylemon_custom_category_permalink_post', 10, 3 );
}

// Modify the category archive permalink
function zestylemon_custom_category_permalink_archive( $permalink, $term, $taxonomy ){
	// Get the category ID 
	$category_id = $term->term_id;
	// Check for desired category 
	foreach( ZESTYLEMON_REWRITE_URL_ARRAY as $term_id => $term_data ){
		if( !empty( $category_id ) && $category_id == $term_id ) {
	        $permalink = trailingslashit( home_url( $term_data['new-url'] ) );		
		}
	}
	return $permalink;
}
if(ZESTYLEMON_ENABLE_REWRITE_URL){
	add_filter( 'term_link', 'zestylemon_custom_category_permalink_archive', 10, 3 );
}	

// Add rewrite rules so that WordPress delivers the correct content
function zestylemon_custom_rewrite_rules( $wp_rewrite ) {
    // This rule will will match the post name in /indonesia/%postname%/ structure
	//$new_rules['^category/indonesia/bali/our-guide-to-bali/?$'] = 'index.php?cat=3';
	foreach(ZESTYLEMON_REWRITE_URL_ARRAY as $term_id => $term_data ){
		$new_rules['^'.$term_data['new-url'].'/?$'] = 'index.php?cat='.$term_id;
	}
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	return $wp_rewrite;
}
if(ZESTYLEMON_ENABLE_REWRITE_URL){
	add_action('generate_rewrite_rules', 'zestylemon_custom_rewrite_rules');
}

function zestylemon_remove_blog_slug( $post_link, $post, $leavename ) {
    if ( 'post' == $post->post_type && 'publish' == $post->post_status ) {
        $post_link = str_replace( '/blog/', '/', $post_link );
    }
    return $post_link;
}
add_filter( 'post_link', 'zestylemon_remove_blog_slug', 10, 3 );	