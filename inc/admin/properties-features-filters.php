<?php

add_action( 'admin_footer', 'zesty_lemon_properties_features_categories_filter' );
function zesty_lemon_properties_features_categories_filter() {
	$screen = get_current_screen();
	if ( is_admin() && $screen->id == 'edit-property_feature' && $screen->base == 'edit-tags' ) {
		ob_start();
		?>
		<div class="alignleft actions zesty-lemon-features-filters" style="display: none;">
			<?php
				$taxonomy = 'property_features_category';
				$selected = isset( $_GET[ $taxonomy ] ) ? esc_attr( $_GET[ $taxonomy ] ) : null;
				$args = array(
					'hide_empty'      => false,
					'hierarchical'    => true,
					'show_option_all' => 'Filter by feature category',
					'value_field'     => 'id',
					'taxonomy'        => $taxonomy,
					'name'            => $taxonomy,
					'show_count'      => false,
					'selected'        => $selected,
				);
				wp_dropdown_categories( $args );
			?>
			<input type="button" name="filter_action" id="terms-query-submit" class="button" value="Filter">
			<a href="<?php echo admin_url('/edit-tags.php?taxonomy=property_feature&post_type=property'); ?>" class="zestylemon-rest-filter"><?php _e( 'Reset Filter', 'zestylemon' ); ?></a>
		</div>
		<script id="zesty-lemon-features-categories-script">
			jQuery(document).ready(function ($) {
				$('.tablenav.top .bulkactions').after( $('.zesty-lemon-features-filters') );
				$('.zesty-lemon-features-filters').show();
				$('#zesty-lemon-features-categories-script').remove();
			});
		</script>
		<script>
			jQuery(document).ready(function ($) {
				$('#terms-query-submit').click(function (e) { 
					e.preventDefault();
					var category = $('#property_features_category').val();
					window.location = '<?php echo admin_url('/edit-tags.php?taxonomy=property_feature&post_type=property'); ?>' + '&property_features_category=' + category;
				});
			});
		</script>
		<?php
		$html = ob_get_clean();
		echo $html;
	}
}

add_filter( 'get_terms_args', 'zesty_lemon_apply_features_admin_filters', 10, 2 );
function zesty_lemon_apply_features_admin_filters( $args, $taxonomies ) {
	if ( is_admin() && in_array( 'property_feature', $taxonomies ) ) {
		$category_taxonomy = 'property_features_category';
		$category = isset( $_GET[ $category_taxonomy ] ) ? esc_attr( $_GET[ $category_taxonomy ] ) : false;
		if ( $category ) {
			$args['meta_key']     = 'feature_category';
			$args['meta_value']   = array( $category );
			$args['meta_compare'] = 'IN';
		}
	}
	return $args;
}