<?php
add_filter( 'wp_get_attachment_image_src', 'zesty_lemon_wp_get_attachment_image_src', 10, 4 );
function zesty_lemon_wp_get_attachment_image_src( $image, $attachment_id, $size, $icon ) {
	if ( ! is_admin() ) {
		$backtrace_array = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		$is_rankmath = false;
		foreach ( $backtrace_array as $backtrace_item ) {
			if ( is_array( $backtrace_item ) && isset( $backtrace_item['class'] ) && $backtrace_item['class'] == 'RankMath\OpenGraph\Facebook' ) {
				$is_rankmath = true;
			}
		}
		if ( $is_rankmath ) {
			$image[0] = str_replace( '.webp', '.jpg', $image[0] );
		}
	}
	return $image;
}

//add_filter( 'query', 'temp_query_filter' );
function temp_query_filter( $query ) {
	if ( ! is_admin() ) {
		if ( ! isset( $_GET['context'] ) || $_GET['context'] != 'edit' ) {
			global $wpdb;
			$post_id       = get_the_ID();
			$attachment_id = get_post_thumbnail_id();
			if ( ! empty( $attachment_id ) && strpos( $query, $attachment_id ) !== false && strpos( $query, 'wp_posts' ) !== false ) {
				remove_filter( 'query', 'temp_query_filter' );
				$_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID = %d LIMIT 1", $attachment_id ) );
				add_filter( 'query', 'temp_query_filter' );
				if ( $_post->post_mime_type == 'image/webp' ) {
					$query = 'SELECT ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, "image/jpeg" AS post_mime_type, comment_count, robotsmeta FROM wp_posts WHERE ID = ' . $attachment_id . ' LIMIT 1';
				}
			}
		}
	}
	return $query;
}