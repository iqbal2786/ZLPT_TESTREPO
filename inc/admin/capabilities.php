<?php

function zesty_lemon_register_capabilities() {
	$zesty_lemon_capabilities = array(
		'edit_others_properties',
		'delete_others_properties',
		'delete_private_properties',
		'edit_private_properties',
		'read_private_properties',
		'edit_published_properties',
		'publish_properties',
		'delete_published_properties',
		'edit_properties',
		'edit_published_properties',
		'delete_properties',
		'edit_property',
		'read_property',
		'delete_property',
		'read',
		'manage_zestylemon_settings',
		'manage_property_locations',
		'manage_property_statuses',
		'manage_property_bedrooms',
		'manage_property_bathrooms',
		'manage_property_sleeps',
		'manage_property_pets',
		'manage_property_types',
		'manage_property_features',
		'manage_property_features_categories',
		'manage_nearest_airports',
		'manage_nearest_stations',
		'manage_nearest_beaches',
		'manage_property_complexes',
		'manage_property_swimming_pool_types',
		'manage_property_garden_terrace_types',
	);
	
	$role = get_role( 'administrator' );
	if ( is_null( $role ) ) {
		return;
	}
	foreach ( $zesty_lemon_capabilities as $capability ) {
		$role->add_cap( $capability );
	}
}
add_action( 'admin_init', 'zesty_lemon_register_capabilities' );
