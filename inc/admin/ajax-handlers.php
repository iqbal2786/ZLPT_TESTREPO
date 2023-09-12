<?php

add_action( 'wp_ajax_toggle_featured_property', 'zesty_lemon_toggle_featured_property' );
function zesty_lemon_toggle_featured_property() {
	$property_id = isset( $_POST['property_id'] ) ? intval( $_POST['property_id'] ) : 0;
	if ( ! empty( $property_id ) ) {
		$acf_field = 'featured_property';
		$old_value = get_field( $acf_field, $property_id );
		$new_value = $old_value ? false : true;
		update_field( $acf_field, $new_value, $property_id ); 
		$response = array(
			'message' => __( 'Featured property toggled successfully.', 'zestylemon' ),
		);
		wp_send_json_success( $response );
	}
	$response = array(
		'message' => __( 'Unexpected error happened, please try again.', 'zestylemon' ),
	);
	wp_send_json_error( $response );
}

add_action( 'wp_ajax_toggle_show_on_front', 'zesty_lemon_toggle_show_on_front' );
function zesty_lemon_toggle_show_on_front() {
	$term_id = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
	if ( ! empty( $term_id ) ) {
		$acf_field = 'show_on_frontend';
		$old_value = get_field( $acf_field, 'property_status_' . $term_id );
		$new_value = $old_value ? false : true;
		update_field( $acf_field, $new_value, 'property_status_' . $term_id ); 
		$response = array(
			'message' => __( 'Status show on front-end toggled successfully.', 'zestylemon' ),
		);
		wp_send_json_success( $response );
	}
	$response = array(
		'message' => __( 'Unexpected error happened, please try again.', 'zestylemon' ),
	);
	wp_send_json_error( $response );
}

add_action( 'wp_ajax_toggle_allow_booking', 'zesty_lemon_toggle_allow_booking' );
function zesty_lemon_toggle_allow_booking() {
	$term_id = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
	if ( ! empty( $term_id ) ) {
		$acf_field = 'allow_booking';
		$old_value = get_field( $acf_field, 'property_status_' . $term_id );
		$new_value = $old_value ? false : true;
		update_field( $acf_field, $new_value, 'property_status_' . $term_id ); 
		$response = array(
			'message' => __( 'Status allow booking toggled successfully.', 'zestylemon' ),
		);
		wp_send_json_success( $response );
	}
	$response = array(
		'message' => __( 'Unexpected error happened, please try again.', 'zestylemon' ),
	);
	wp_send_json_error( $response );
}

add_action( 'wp_ajax_toggle_stand_out_feature', 'zesty_lemon_toggle_stand_out_feature' );
function zesty_lemon_toggle_stand_out_feature() {
	$term_id = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
	if ( ! empty( $term_id ) ) {
		$acf_field = 'stand_out_feature';
		$old_value = get_field( $acf_field, 'property_feature_' . $term_id );
		$new_value = $old_value ? false : true;
		update_field( $acf_field, $new_value, 'property_feature_' . $term_id );
		$response = array(
			'message' => __( 'Feature stand-out toggled successfully.', 'zestylemon' ),
		);
		wp_send_json_success( $response );
	}
	$response = array(
		'message' => __( 'Unexpected error happened, please try again.', 'zestylemon' ),
	);
	wp_send_json_error( $response );
}

add_action( 'wp_ajax_get_taxonomy_terms', 'zesty_lemon_get_taxonomy_terms' );
function zesty_lemon_get_taxonomy_terms() {
	$taxonomy = isset( $_POST['taxonomy'] ) ? esc_attr( $_POST['taxonomy'] ) : '';
	if ( ! empty( $taxonomy ) ) {
		$terms = get_terms( array(
			'taxonomy'     => $taxonomy,
			'hide_empty'   => false,
			'fields'       => 'id=>name',
		) );
		wp_send_json_success( $terms );
	} else {
		wp_send_json_error();
	}
}
