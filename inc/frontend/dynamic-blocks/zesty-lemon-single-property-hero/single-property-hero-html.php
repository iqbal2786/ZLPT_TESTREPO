<?php
$headline_tag  = $attributes['headline_tag'];
if ( empty( $headline_tag ) || ! in_array( $headline_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6') ) ) {
	$headline_tag = 'h1';
}
$property_type = $strapline = '';
$property_type_ribbon_bg = '#0099ff';
$price = $featured = $property_featured_img_id = 0;
$property_featured_img_url = get_stylesheet_directory_uri() . '/assets/shared/images/property-placeholder.png';
if ( has_post_thumbnail() ) {
	$property_featured_img_id       = get_post_thumbnail_id();
	$property_featured_img_data     = wp_get_attachment_image_src( $property_featured_img_id, 'full' );
	$property_featured_img_alt_text = get_post_meta ( $property_featured_img_id, '_wp_attachment_image_alt', true );
	$property_featured_img_url      = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	if ( empty( $property_featured_img_alt_text ) ) {
		$property_featured_img_alt_text = get_the_title();
	}
}
if ( function_exists('get_field') ) {
	$price         = get_field('property_price');
	$featured      = get_field('featured_property');
	$property_type = get_field('property_type');
	$strapline     = get_field('property_strapline');
	$price         = get_formated_price( $price );
	if ( ! empty( $property_type ) ) {
		$temp_ribbon_bg = get_field( 'type_bg_color', 'property_type_' . $property_type );
		if ( ! empty( $temp_ribbon_bg ) ) {
			$property_type_ribbon_bg = $temp_ribbon_bg;
		}
		$property_type = get_term( $property_type );
		$property_type = $property_type->name;
	}
}
?>
<div class="jarallax zl-single-property-hero-wrapper">
	<img class="jarallax-img" src="<?php echo esc_url( $property_featured_img_url ); ?>" alt="<?php echo esc_attr( $property_featured_img_alt_text ); ?>" />
	<div class="zl-top-shadow"></div>
	<div class="zl-shadow-overlay"></div>
	<div class="zl-hero-img-trigger-wrapper">
		<a class="zl-hero-img-trigger" data-lg-size="<?php echo esc_attr( $property_featured_img_data[1] ) . '-' . esc_attr( $property_featured_img_data[2] ); ?>" data-src="<?php echo esc_url( $property_featured_img_url ); ?>" data-sub-html="<p><?php echo esc_attr( $property_featured_img_alt_text ); ?></p>">
			<?php echo wp_get_attachment_image( $property_featured_img_id, 'full' ); ?>
		</a>
	</div>
	<?php /* <div class="zl-zoomify-img-trigger" data-img-src="<?php echo esc_url( $property_featured_img_url ); ?>" data-img-alt="<?php the_title(); ?>"></div> */ ?>
	<div class="zl-single-property-hero-content-wrapper grid-container">
		<?php echo get_property_permalink(); ?>
		<div class="zl-single-property-hero-title-wrapper">
			<div class="zl-single-property-hero-title-inner-wrapper">
				<div class="zl-single-property-hero-labels-wrapper">
					<?php
						if ( $featured ) {
							echo '<label class="zl-property-label zl-property-featured">' . __( 'Featured', 'zestylemon' ) . '</label>';
						}
					?>
					<?php
						if ( ! empty( $property_type ) ) {
							echo '<label class="zl-property-label zl-property-type" style="background-color: ' . $property_type_ribbon_bg . ';">' . $property_type . '</label>';
						}
					?>
				</div>
				<<?php echo $headline_tag; ?> class="zl-property-name"><?php the_title(); ?></<?php echo $headline_tag; ?>>
				<div class="zl-property-strapline"><?php echo $strapline; ?></div>
			</div>
			<div class="zl-single-property-hero-price-wrapper">
				<span class="zl-single-property-hero-price-label"><?php echo __( 'Price from', 'zestylemon' ); ?></span>
				<span class="zl-single-property-hero-price"><?php echo $price; ?></span>
			</div>
		</div>
	</div>
</div>
