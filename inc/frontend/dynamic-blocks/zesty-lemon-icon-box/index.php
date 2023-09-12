<?php

namespace ZestyLemon\Gutenberg\Blocks\IconBox;

add_action( "init", __NAMESPACE__ . "\\register_dynamic_block" );

function register_dynamic_block() {
	if ( ! function_exists( "register_block_type" ) ) {
		return;
	}
	register_block_type( "zestylemon/icon-box", array(
		"attributes" => array(
			"uid" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"icon" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"headline" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"headline_tag" => array(
				"type" 		=> "string",
				"default" 	=> "h2"
			),
			"content" => array(
				"type" 		=> "string",
				"default" 	=> ""
			),
			"headline_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--accent)"
			),
			"text_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--contrast)"
			),
			"link_color" => array(
				"type" 		=> "string",
				"default" 	=> "var(--accent)"
			),
		),
		"render_callback" => __NAMESPACE__ . "\\render_dynamic_block"
	) );
}

function render_dynamic_block( $attributes ) {
	$editor    = false;
	$icon_slug = $uid_class = $link_color = $headline = $headline_color = $content = $text_color = '';
	$icon      = array();
	$headline_tag = 'h2';
	if ( isset( $attributes['uid'] ) && ! empty( $attributes['uid'] ) ) {
		$uid_class = 'zl-block-icon-box-wrapper-' . esc_attr( $attributes['uid'] );
	}
	if ( isset( $attributes['icon'] ) && ! empty( $attributes['icon'] ) ) {
		$icon_slug = esc_attr( $attributes['icon'] );
		$icon = zl_get_icon_from_library( $icon_slug );
	}
	if ( isset( $attributes['link_color'] ) && ! empty( $attributes['link_color'] ) ) {
		$link_color = $attributes['link_color'];
	}
	if ( isset( $attributes['headline'] ) && ! empty( $attributes['headline'] ) ) {
		$headline = $attributes['headline'];
	}
	if ( isset( $attributes['headline_tag'] ) && ! empty( $attributes['headline_tag'] ) ) {
		$headline_tag = $attributes['headline_tag'];
	}
	if ( isset( $attributes['headline_color'] ) && ! empty( $attributes['headline_color'] ) ) {
		$headline_color = $attributes['headline_color'];
	}
	if ( isset( $attributes['content'] ) && ! empty( $attributes['content'] ) ) {
		$content = $attributes['content'];
	}
	if ( isset( $attributes['text_color'] ) && ! empty( $attributes['text_color'] ) ) {
		$text_color = $attributes['text_color'];
	}
	ob_start();
	?>
	<div class="zesty-lemon-block-container zl-block-icon-box-wrapper <?php echo $uid_class; ?>">
		<div class="zl-block-icon-box-icon">
			<?php
				if ( ! empty( $icon ) ) {
					if ( $icon['use_svg'] && ! empty( $icon['icon_svg'] ) ) {
						echo $icon['icon_svg'];
					}
					if ( ! $icon['use_svg'] && ! empty( $icon['icon_image_url'] ) ) {
						echo '<img class="wp-image-' . esc_attr( $icon['icon_image'] ) . '" src="' . esc_url( $icon['icon_image_url'] ) . '" />';
					}
				}
			?>
		</div>
		<style>
			<?php
			if ( ! empty( $uid_class ) && ! empty( $link_color ) ) {
				echo $uid_class . ' .zl-block-icon-box-content a {';
					echo 'color: ' . esc_attr( $link_color );
				echo '}';
			}
			?>
		</style>
		<div class="zl-block-icon-box-content-wrapper">
			<?php
			if ( ! empty( $headline ) && ! empty( $headline_tag ) ) {
				$style = '';
				if ( ! empty( $headline_color ) ) {
					$style = 'color: ' . esc_attr( $headline_color );
				}
				echo '<' . esc_attr( $headline_tag ) . ' class="zl-block-icon-box-headline" style="' . $style . '">';
					echo esc_html( $headline );
				echo '</' . esc_attr( $headline_tag ) . '>';
			}
			?>
			<?php
			if ( ! empty( $content ) ) {
				$style = '';
				if ( ! empty( $text_color ) ) {
					$style = 'color: ' . esc_attr( $text_color );
				}
				echo '<div class="zl-block-icon-box-content" style="' . $style . '">';
					echo wp_kses( $content, wp_kses_allowed_html('post') );
				echo '</div>';
			}
			?>
		</div>
	</div>
	<?php
	$output = ob_get_clean();
	if ( $editor ) {
		wp_reset_postdata();
	}
	return $output;
}
