<?php



namespace ZestyLemon\Gutenberg\Blocks\SinglePropertyWhatWeLike;



add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );



function register_dynamic_block() {

	if ( ! function_exists( "register_block_type" ) ) {

		return;

	}

	register_block_type( "zestylemon/single-property-what-we-like", array(

		"attributes" => array(

			"headline_tag" => array(

				"type" 		=> "string",

				"default" 	=> "h2"

			),

			"what_we_like_title" => array(

				"type" 		=> "string",

				"default" 	=> __("What we like...", "zestylemon")

			),

			"what_we_like_1" => array(

				"type" 		=> "string",

				"default" 	=> ""

			),

			"what_we_like_2" => array(

				"type" 		=> "string",

				"default" 	=> ""

			),

			"what_we_like_3" => array(

				"type" 		=> "string",

				"default" 	=> ""

			),

			"bg_color" => array(

				"type" 		=> "string",

				"default" 	=> "var(--accent)"

			),

			"title_color" => array(

				"type" 		=> "string",

				"default" 	=> "var(--base-3)"

			),

			"text_color" => array(

				"type" 		=> "string",

				"default" 	=> "var(--contrast)"

			),

		),

		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"

	) );

}



function render_dynamic_block( $attributes ) {

	global $wp_query;

	$editor = false;

	$bg_color = $title_color = $text_color = '';

	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {

		$editor = true;

		$args = array(

			'post_type' => 'property'

		);

		$properties = new \WP_Query( $args );

		if ( $properties->have_posts() ) {

			$properties->the_post();

		}

	}

	$headline_tag = $attributes['headline_tag'];

	if ( empty( $headline_tag ) || ! in_array( $headline_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6') ) ) {

		$headline_tag = 'h2';

	}

	$what_we_like_1 = '';

	$what_we_like_2 = '';

	$what_we_like_3 = '';

	if ( isset( $attributes['what_we_like_1'] ) && ! empty( $attributes['what_we_like_1'] ) ) {

		$what_we_like_1 = get_field( esc_attr( $attributes['what_we_like_1'] ) );

	}

	if ( isset( $attributes['what_we_like_2'] ) && ! empty( $attributes['what_we_like_2'] ) ) {

		$what_we_like_2 = get_field( esc_attr( $attributes['what_we_like_2'] ) );

	}

	if ( isset( $attributes['what_we_like_3'] ) && ! empty( $attributes['what_we_like_3'] ) ) {

		$what_we_like_3 = get_field( esc_attr( $attributes['what_we_like_3'] ) );

	}

	if ( $editor ) {

		if ( ! isset( $attributes['what_we_like_title'] ) || empty( $attributes['what_we_like_title'] ) ) {

			$attributes['what_we_like_title'] = 'Dummy title';

		}

		if ( empty( $what_we_like_1 ) ) {

			$what_we_like_1 = 'Dummy what we like 1';

		}

		if ( empty( $what_we_like_2 ) ) {

			$what_we_like_2 = 'Dummy what we like 2';

		}

		if ( empty( $what_we_like_3 ) ) {

			$what_we_like_3 = 'Dummy what we like 3';

		}

	}

	if ( isset( $attributes['bg_color'] ) && ! empty( $attributes['bg_color'] ) ) {

		$bg_color = $attributes['bg_color'];

	}

	if ( isset( $attributes['title_color'] ) && ! empty( $attributes['title_color'] ) ) {

		$title_color = $attributes['title_color'];

	}

	if ( isset( $attributes['text_color'] ) && ! empty( $attributes['text_color'] ) ) {

		$text_color = $attributes['text_color'];

	}

	ob_start();

	if ( ! empty( $what_we_like_1 ) || ! empty( $what_we_like_2 ) || ! empty( $what_we_like_3 ) ) {

		?>

		<div class="zl-block-container">

			<?php

				$style = '';

				if ( ! empty( $bg_color ) ) {

					$style = 'background-color: ' . esc_attr( $bg_color );

				}

			?>

			<div class="zesty-lemon-single-property-what-we-like" style="<?php echo $style; ?>">

				<div class="grid-container">

					<?php

						if ( isset( $attributes['what_we_like_title'] ) && ! empty( $attributes['what_we_like_title'] ) ) {

							$style = '';

							if ( ! empty( $title_color ) ) {

								$style = 'color: ' . esc_attr( $title_color );

							}

							$attributes['what_we_like_title'] = str_replace('{{title}}', get_the_title(), $attributes['what_we_like_title']);
							
							echo '<' . $headline_tag . ' class="what-we-like-title fff" style="' . $style . '">' . esc_attr( $attributes['what_we_like_title'] ) . '</' . $headline_tag . '>';

						}

					?>

					<?php

						$style = '';

						if ( ! empty( $text_color ) ) {

							$style = 'color: ' . esc_attr( $text_color );

						}

					?>

					<ul class="what-we-like-list" style="<?php echo $style; ?>">

						<?php

							if ( ! empty( $what_we_like_1 ) ) {

								echo '<li>' . esc_attr( $what_we_like_1 ) . '</li>';

							}

							if ( ! empty( $what_we_like_2 ) ) {

								echo '<li>' . esc_attr( $what_we_like_2 ) . '</li>';

							}

							if ( ! empty( $what_we_like_3 ) ) {

								echo '<li>' . esc_attr( $what_we_like_3 ) . '</li>';

							}

						?>

					</ul>

				</div>

			</div>

		</div>

		<?php

	}

	$output = ob_get_clean();

	if ( isset( $_GET['context'] ) && $_GET['context'] == 'edit' ) {

		wp_reset_postdata();

	}

	return $output;

}

