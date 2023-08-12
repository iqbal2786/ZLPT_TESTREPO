<?php

add_filter( 'generate_copyright', 'zesty_lemon_copyright' );
function zesty_lemon_copyright ( $copyright ) {
	ob_start();
	wp_nav_menu(
		array(
			'theme_location' => 'copyright',
			'container' => 'div',
			'container_class' => 'copyright-nav',
			'container_id' => 'copyright-menu',
			'menu_class' => '',
		)
	);
	printf(
		'<span class="copyright">&copy; %1$s %2$s</span>',
		date( 'Y' ), // phpcs:ignore
		'Zesty Lemon Ltd',
	);
	$html = ob_get_clean();
	return $html;
}

add_filter( 'generate_search_title_output', 'zesty_lemon_generate_search_title_output' );
function zesty_lemon_generate_search_title_output( $title ) {
	return '';
}

add_action( 'wp', 'zesty_lemon_hide_archive_title' );
function zesty_lemon_hide_archive_title() {
	if ( is_tax( 'property_location' ) ) {
		remove_action( 'generate_archive_title', 'generate_archive_title' );
	}
}

add_shortcode( 'zesty_lemon_properties_archives_header', 'zesty_lemon_properties_archives_header' );
function zesty_lemon_properties_archives_header() {
	do_action( 'zesty_lemon_properties_archives_header' );
}

add_action( 'generate_before_loop', 'zesty_lemon_generate_before_loop' );
function zesty_lemon_generate_before_loop( $template ) {
	$taxonomy = get_query_var( 'taxonomy' );
	if ( $template == 'search' || ( $template == 'archive' && $taxonomy == 'property_location' ) ) {
		zesty_lemon_properties_archives_header();
		echo '<div class="zesty-lemon-properties-entries">';
	}
}

add_action( 'generate_after_loop', 'zesty_lemon_generate_after_loop' );
function zesty_lemon_generate_after_loop( $template ) {
	$taxonomy = get_query_var( 'taxonomy' );
	if ( $template == 'search' || ( $template == 'archive' && $taxonomy == 'property_location' ) ) {
		echo '</div>';
	}
}

add_action( 'save_post', 'zesty_lemon_save_plain_location' );
function zesty_lemon_save_plain_location( $post_id ) {
	$address = get_post_meta( $post_id, 'address' );
	if ( isset( $address[0]['address'] ) && ! empty( $address[0]['address'] ) ) {
		update_post_meta( $post_id, 'plain_address', $address[0]['address'] );
	}
}


add_filter('embed_oembed_html', 'zesty_lemon_custom_instagram_settings');
function zesty_lemon_custom_instagram_settings($code){
	if(strpos($code, 'instagr.am') !== false || strpos($code, 'instagram.com') !== false){ // if instagram embed
		$return = preg_replace("@data-instgrm-captioned@", "", $code); // remove caption class
	    return $return;
    }
    return $code;
}

add_filter( 'generate_leave_comment', 'zesty_lemon_generate_leave_comment', 10, 1 );
function zesty_lemon_generate_leave_comment( $title_reply ) {
	return __( 'Join the convesation...', 'zestylemon' );
}

add_filter( 'generate_post_comment', 'zesty_lemon_generate_post_comment', 10, 1 );
function zesty_lemon_generate_post_comment( $label_submit ) {
	return __( 'Submit comment', 'zestylemon' );
}

add_filter( 'comment_form_logged_in', 'zesty_lemon_comment_form_logged_in', 10, 3 );
function zesty_lemon_comment_form_logged_in( $logged_in_as, $commenter, $user_identity ) {
	global $post;
	$post_id = $post->ID;
	$logged_in_as = sprintf(
		'<p class="logged-in-as">%s</p>',
		sprintf(
			/* translators: 1: Edit user link, 2: Accessibility text, 3: User name, 4: Logout URL. */
			__( '<a href="%1$s" aria-label="%2$s">Logged in as %3$s</a>. <a href="%4$s">Log out?</a>', 'zestylemon' ),
			get_edit_user_link(),
			/* translators: %s: User name. */
			esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.', 'zestylemon' ), $user_identity ) ),
			$user_identity,
			/** This filter is documented in wp-includes/link-template.php */
			wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ), $post_id ) )
		)
	);
	return $logged_in_as;
}

add_filter( 'comment_form_defaults', 'zesty_lemon_comment_form_defaults', 11, 1 );
function zesty_lemon_comment_form_defaults( $defaults ) {
	$defaults['comment_field'] = sprintf(
		'<p class="comment-form-comment"><label for="comment">%1$s</label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>',
		esc_html__( 'Comment', 'generatepress' )
	);
	return $defaults;
}

/**
 * Automatic preloading featured image
 */
add_action( 'wp_head', 'zesty_lemon_wp_head' );
function zesty_lemon_wp_head() {
	$featured_img_url = get_the_post_thumbnail_url( get_the_ID(),'full' );
	if ( ! empty( $featured_img_url ) ) {
		echo '<link rel="preload" fetchpriority="high" as="image" href="' . esc_url( $featured_img_url ) . '"/>' . "\n";
	}

	if ( is_search() ) {
		echo '<link rel="canonical" href="' . esc_url( home_url('/') ) . '" />' . "\n";
	}
}

add_filter( 'generate_navigation_search_output', 'zesty_lemon_generate_navigation_search_output' );
function zesty_lemon_generate_navigation_search_output( $search_form ) {
	$search_form = sprintf(
		'<form method="get" class="search-form navigation-search" action="%1$s">
			<input type="search" class="search-field" value="%2$s" name="s" title="%3$s" />
			<input type="hidden" value="yes" name="properties" />
		</form>',
		esc_url( home_url( '/' ) ),
		esc_attr( get_search_query() ),
		esc_attr_x( 'Search', 'label', 'generatepress' )
	);
	return $search_form;
}

add_filter( 'generate_mobile_menu_media_query', function() {
    return '(max-width: 1023px)';
} );

add_filter( 'generate_not_mobile_menu_media_query', function() {
    return '(min-width: 1024px)';
} );