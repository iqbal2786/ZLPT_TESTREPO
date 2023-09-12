<?php

namespace ZestyLemon\Gutenberg\Blocks\PostsCarousel;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/posts-carousel", array(
		"attributes" => array(
			"headline" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"headlineTag" => array(
				"type" 		=> "string",
				"default" 	=> "h2"
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	zesty_lemon_wp_enqueue_style( 'swiper', '/assets/shared/css/swiper-bundle.min.css', [] );
	zesty_lemon_wp_enqueue_script( 'swiper', '/assets/shared/js/swiper-bundle.min.js', ['jquery'] );
	global $wp_query;
	ob_start();
	$query_vars = array(
		'posts_per_page' => 15,
	);
	
	$posts         = new \WP_Query( $query_vars );
	$posts_details = array();
	$allowed_html  = wp_kses_allowed_html();
	unset( $allowed_html['a'] );
	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {
			$posts->the_post();
			$post_id = get_the_ID();
			$posts_details[ $post_id ] = array(
				'ID'        => $post_id,
				'classes'   => implode( ' ', get_post_class( '', $post_id ) ),
				'title'     => get_the_title(),
				'excerpt'   => wp_kses( get_the_excerpt(), $allowed_html ),
				'bg'        => get_the_post_thumbnail_url( $post_id, 'large' ),
				'url'       => get_permalink(),
			);
			$posts_details[ $post_id ]['excerpt'] = zesty_lemon_limit_words( $posts_details[ $post_id ]['excerpt'], 20 );
		}
		wp_reset_postdata();
	}
	?>
		<div class="zl-block-container">
			<div class="zl-posts-carousel-wrapper swiper swiper-container">
				<div class="swiper-wrapper">
					<?php
						foreach ( $posts_details as $post ) {
							?>
								<div class="swiper-slide <?php echo esc_attr( $post['classes'] ); ?>" style="background-image: url(<?php echo esc_url( $post['bg'] ); ?>);">
									<div class="zl-slide-description">
										<h3 class="zl-slide-title">
											<a href="<?php echo esc_url( $post['url'] ); ?>"><?php echo esc_html( $post['title'] ); ?></a>
										</h3>
										<div class="zl-slide-content">
											<?php echo wpautop( esc_html( $post['excerpt'] ) ); ?>
										</div>
										<div class="zl-slide-button-wrapper">
											<a class="zl-slide-button cta-button" href="<?php echo esc_url( $post['url'] ); ?>">
												<span class="gb-button-text"><?php _e('Read More', 'zesty_lemon'); ?></span>
												<span class="gb-icon">
													<svg aria-hidden="true" role="img" height="1em" width="1em" viewBox="0 0 256 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg>
												</span>
											</a>
										</div>
									</div>
								</div>
							<?php
						}
					?>
				</div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	<?php
	$output = ob_get_clean();
	return $output;
}
