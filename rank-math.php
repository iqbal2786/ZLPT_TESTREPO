<?php
add_filter( 'rank_math/sitemap/post_type_archive_link', function( $archive_url, $post_type ){
	return "";
}, 10, 2 );
?>