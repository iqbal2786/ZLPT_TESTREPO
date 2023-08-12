<?php
 
/**
 * Plugin Name: Gutenberg examples dynamic
 */
 
function gutenberg_examples_dynamic_render_callback( $block_attributes, $content ) {
    $recent_posts = wp_get_recent_posts( array(
        'numberposts' => 1,
        'post_status' => 'publish',
    ) );
    if ( count( $recent_posts ) === 0 ) {
        return 'No posts';
    }
    $post = $recent_posts[ 0 ];
    $post_id = $post['ID'];
    ob_start();
	echo '<br>-------------------------<br>';
	var_dump( $_GET );
	echo '<br>-------------------------<br>';
	sprintf(
        '<a class="wp-block-my-plugin-latest-post" href="%1$s">asasasa - %2$s</a>',
        esc_url( get_permalink( $post_id ) ),
        esc_html( get_the_title( $post_id ) )
    );
	$html = ob_get_clean();
	return $html;
}
 
function gutenberg_examples_dynamic() {
    register_block_type( 'gutenberg-examples/example-dynamic', array(
        'api_version' => 2,
        'editor_script' => 'gutenberg-examples-dynamic',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback'
    ) );
 
}
add_action( 'init', 'gutenberg_examples_dynamic' );