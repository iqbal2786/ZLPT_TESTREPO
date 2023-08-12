<?php

function zesty_lemon_register_post_types() {
	$args =  array(
		'labels' => array(
			'name'                  => _x( 'Properties', 'Post Type General Name', 'zestylemon' ),
			'singular_name'         => _x( 'Property', 'Post Type Singular Name', 'zestylemon' ),
			'menu_name'             => __( 'Properties', 'zestylemon' ),
			'name_admin_bar'        => __( 'Property', 'zestylemon' ),
			'archives'              => __( 'Item Archives', 'zestylemon' ),
			'attributes'            => __( 'Item Attributes', 'zestylemon' ),
			'parent_item_colon'     => __( 'Parent Item:', 'zestylemon' ),
			'all_items'             => __( 'All properties', 'zestylemon' ),
			'add_new_item'          => __( 'Add New property', 'zestylemon' ),
			'add_new'               => __( 'Add New', 'zestylemon' ),
			'new_item'              => __( 'New property', 'zestylemon' ),
			'edit_item'             => __( 'Edit property', 'zestylemon' ),
			'update_item'           => __( 'Update property', 'zestylemon' ),
			'view_item'             => __( 'View property', 'zestylemon' ),
			'view_items'            => __( 'View properties', 'zestylemon' ),
			'search_items'          => __( 'Search property', 'zestylemon' ),
			'not_found'             => __( 'Not found', 'zestylemon' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'zestylemon' ),
			'featured_image'        => __( 'Featured Image', 'zestylemon' ),
			'set_featured_image'    => __( 'Set featured image', 'zestylemon' ),
			'remove_featured_image' => __( 'Remove featured image', 'zestylemon' ),
			'use_featured_image'    => __( 'Use as featured image', 'zestylemon' ),
			'insert_into_item'      => __( 'Insert into property', 'zestylemon' ),
			'uploaded_to_this_item' => __( 'Uploaded to this property', 'zestylemon' ),
			'items_list'            => __( 'Properties list', 'zestylemon' ),
			'items_list_navigation' => __( 'Properties list navigation', 'zestylemon' ),
			'filter_items_list'     => __( 'Filter properties list', 'zestylemon' ),
		),
		'description'         => '' ,
		'public'              => true ,
		'publicly_queryable'  => true ,
		'show_ui'             => true ,
		'show_in_nav_menus'   => true ,
		'has_archive'         => true ,
		'show_in_menu'        => true ,
		'show_in_rest'        => false ,
		'rest_base'           => NULL ,
		'exclude_from_search' => false ,
		'map_meta_cap'        => true ,
		'capabilities'        => array(
			'edit_others_posts'     => 'edit_others_properties',
			'delete_others_posts'   => 'delete_others_properties',
			'delete_private_posts'  => 'delete_private_properties',
			'edit_private_posts'    => 'edit_private_properties',
			'read_private_posts'    => 'read_private_properties',
			'edit_published_posts'  => 'edit_published_properties',
			'publish_posts'         => 'publish_properties',
			'delete_published_posts'=> 'delete_published_properties',
			'edit_posts'            => 'edit_properties'   ,
			'delete_posts'          => 'delete_properties',
			'edit_post'             => 'edit_property',
			'read_post'             => 'read_property',
			'delete_post'           => 'delete_property',
		),
		'hierarchical'=> false ,
		'rewrite'     => array(
			'slug'       => 'properties' ,
			'with_front' => true 
		),
		'menu_position' => NULL ,
		'menu_icon'     => 'dashicons-admin-home',
		'query_var'     => true ,
		'supports'      => array('title' ,'editor', 'thumbnail', 'excerpt'),
	);
	register_post_type( 'property', $args );

	register_taxonomy('property_location', 'property', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                       => _x( 'Property locations', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property location', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property locations', 'zestylemon' ),
			'all_items'                  => __( 'All property locations', 'zestylemon' ),
			'parent_item'                => __( 'Parent property location', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property location:', 'zestylemon' ),
			'new_item_name'              => __( 'New property location name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property location', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property location', 'zestylemon' ),
			'update_item'                => __( 'Update property location', 'zestylemon' ),
			'view_item'                  => __( 'View property location', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property locations with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property locations', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property locations', 'zestylemon' ),
			'search_items'               => __( 'Search property locations', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property locations', 'zestylemon' ),
			'items_list'                 => __( 'Property locations list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property locations list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property locations', 'zestylemon' ),
			'item_link'                  => __( 'Property location link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property location', 'zestylemon' ),
		),
		'show_in_rest'		=> true,
		'rewrite'           => array('hierarchical' => true), 
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_locations',
				'edit_terms'   => 'manage_property_locations',
				'delete_terms' => 'manage_property_locations',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_status', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property statuses', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property status', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property statuses', 'zestylemon' ),
			'all_items'                  => __( 'All property statuses', 'zestylemon' ),
			'parent_item'                => __( 'Parent property status', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property status:', 'zestylemon' ),
			'new_item_name'              => __( 'New property status name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property status', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property status', 'zestylemon' ),
			'update_item'                => __( 'Update property status', 'zestylemon' ),
			'view_item'                  => __( 'View property status', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property statuses with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property statuses', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property statuses', 'zestylemon' ),
			'search_items'               => __( 'Search property statuses', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property statuses', 'zestylemon' ),
			'items_list'                 => __( 'Property statuses list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property statuses list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property statuses', 'zestylemon' ),
			'item_link'                  => __( 'Property status link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property status', 'zestylemon' ),
		),
		'show_in_rest'		=> true,
		'query_var'         => true,
		'show_admin_column' => true,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_statuses',
				'edit_terms'   => 'manage_property_statuses',
				'delete_terms' => 'manage_property_statuses',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_bedrooms', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property bedrooms', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property bedrooms', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property bedrooms', 'zestylemon' ),
			'all_items'                  => __( 'All property bedrooms', 'zestylemon' ),
			'parent_item'                => __( 'Parent property bedrooms', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property bedrooms:', 'zestylemon' ),
			'new_item_name'              => __( 'New property bedrooms name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property bedrooms', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property bedrooms', 'zestylemon' ),
			'update_item'                => __( 'Update property bedrooms', 'zestylemon' ),
			'view_item'                  => __( 'View property bedrooms', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property bedrooms with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property bedrooms', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property bedrooms', 'zestylemon' ),
			'search_items'               => __( 'Search property bedrooms', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property bedrooms', 'zestylemon' ),
			'items_list'                 => __( 'Property bedrooms list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property bedrooms list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property bedrooms', 'zestylemon' ),
			'item_link'                  => __( 'Property bedrooms link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property bedrooms', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_bedrooms',
				'edit_terms'   => 'manage_property_bedrooms',
				'delete_terms' => 'manage_property_bedrooms',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_bathrooms', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property bathrooms', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property bathrooms', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property bathrooms', 'zestylemon' ),
			'all_items'                  => __( 'All property bathrooms', 'zestylemon' ),
			'parent_item'                => __( 'Parent property bathrooms', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property bathrooms:', 'zestylemon' ),
			'new_item_name'              => __( 'New property bathrooms name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property bathrooms', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property bathrooms', 'zestylemon' ),
			'update_item'                => __( 'Update property bathrooms', 'zestylemon' ),
			'view_item'                  => __( 'View property bathrooms', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property bathrooms with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property bathrooms', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property bathrooms', 'zestylemon' ),
			'search_items'               => __( 'Search property bathrooms', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property bathrooms', 'zestylemon' ),
			'items_list'                 => __( 'Property bathrooms list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property bathrooms list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property bathrooms', 'zestylemon' ),
			'item_link'                  => __( 'Property bathrooms link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property bathrooms', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_bathrooms',
				'edit_terms'   => 'manage_property_bathrooms',
				'delete_terms' => 'manage_property_bathrooms',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_sleeps', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property sleeps', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property sleeps', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property sleeps', 'zestylemon' ),
			'all_items'                  => __( 'All property sleeps', 'zestylemon' ),
			'parent_item'                => __( 'Parent property sleeps', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property sleeps:', 'zestylemon' ),
			'new_item_name'              => __( 'New property sleeps name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property sleeps', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property sleeps', 'zestylemon' ),
			'update_item'                => __( 'Update property sleeps', 'zestylemon' ),
			'view_item'                  => __( 'View property sleeps', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property sleeps with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property sleeps', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property sleeps', 'zestylemon' ),
			'search_items'               => __( 'Search property sleeps', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property sleeps', 'zestylemon' ),
			'items_list'                 => __( 'Property sleeps list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property sleeps list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property sleeps', 'zestylemon' ),
			'item_link'                  => __( 'Property sleeps link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property sleeps', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_sleeps',
				'edit_terms'   => 'manage_property_sleeps',
				'delete_terms' => 'manage_property_sleeps',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_pets', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property pets', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property pets', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property pets', 'zestylemon' ),
			'all_items'                  => __( 'All property pets', 'zestylemon' ),
			'parent_item'                => __( 'Parent property pets', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property pets:', 'zestylemon' ),
			'new_item_name'              => __( 'New property pets name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property pets', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property pets', 'zestylemon' ),
			'update_item'                => __( 'Update property pets', 'zestylemon' ),
			'view_item'                  => __( 'View property pets', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property pets with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property pets', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property pets', 'zestylemon' ),
			'search_items'               => __( 'Search property pets', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property pets', 'zestylemon' ),
			'items_list'                 => __( 'Property pets list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property pets list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property pets', 'zestylemon' ),
			'item_link'                  => __( 'Property pets link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property pets', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_pets',
				'edit_terms'   => 'manage_property_pets',
				'delete_terms' => 'manage_property_pets',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_type', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property types', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property type', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property types', 'zestylemon' ),
			'all_items'                  => __( 'All property types', 'zestylemon' ),
			'parent_item'                => __( 'Parent property type', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property type:', 'zestylemon' ),
			'new_item_name'              => __( 'New property type name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property type', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property type', 'zestylemon' ),
			'update_item'                => __( 'Update property type', 'zestylemon' ),
			'view_item'                  => __( 'View property type', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property types with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property types', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property types', 'zestylemon' ),
			'search_items'               => __( 'Search property types', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property types', 'zestylemon' ),
			'items_list'                 => __( 'Property types list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property types list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property types', 'zestylemon' ),
			'item_link'                  => __( 'Property type link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property type', 'zestylemon' ),
		),
		'show_in_rest'		=> true,
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_types',
				'edit_terms'   => 'manage_property_types',
				'delete_terms' => 'manage_property_types',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_feature', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property features', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property feature', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property features', 'zestylemon' ),
			'all_items'                  => __( 'All property features', 'zestylemon' ),
			'parent_item'                => __( 'Parent property feature', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property feature:', 'zestylemon' ),
			'new_item_name'              => __( 'New property feature name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property feature', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property feature', 'zestylemon' ),
			'update_item'                => __( 'Update property feature', 'zestylemon' ),
			'view_item'                  => __( 'View property feature', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property features with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property features', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property features', 'zestylemon' ),
			'search_items'               => __( 'Search property features', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property features', 'zestylemon' ),
			'items_list'                 => __( 'Property features list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property features list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property features', 'zestylemon' ),
			'item_link'                  => __( 'Property feature link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property feature', 'zestylemon' ),
		),
		'show_in_rest'		=> true,
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_features',
				'edit_terms'   => 'manage_property_features',
				'delete_terms' => 'manage_property_features',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_features_category', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property features categories', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property features category', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property features categories', 'zestylemon' ),
			'all_items'                  => __( 'All property features categories', 'zestylemon' ),
			'parent_item'                => __( 'Parent property features category', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property features category:', 'zestylemon' ),
			'new_item_name'              => __( 'New property features category name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property features category', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property features category', 'zestylemon' ),
			'update_item'                => __( 'Update property features category', 'zestylemon' ),
			'view_item'                  => __( 'View property features category', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property features categories with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property features categories', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property features categories', 'zestylemon' ),
			'search_items'               => __( 'Search property features categories', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property features categories', 'zestylemon' ),
			'items_list'                 => __( 'Property features categories list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property features categories list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property features categories', 'zestylemon' ),
			'item_link'                  => __( 'Property features category link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property features category', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_features_categories',
				'edit_terms'   => 'manage_property_features_categories',
				'delete_terms' => 'manage_property_features_categories',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('nearest_airport', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Nearest airports', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Nearest airport', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Nearest airports', 'zestylemon' ),
			'all_items'                  => __( 'All nearest airports', 'zestylemon' ),
			'parent_item'                => __( 'Parent nearest airport', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent nearest airport:', 'zestylemon' ),
			'new_item_name'              => __( 'New nearest airport name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new nearest airport', 'zestylemon' ),
			'edit_item'                  => __( 'Edit nearest airport', 'zestylemon' ),
			'update_item'                => __( 'Update nearest airport', 'zestylemon' ),
			'view_item'                  => __( 'View nearest airport', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate nearest airports with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove nearest airports', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular nearest airports', 'zestylemon' ),
			'search_items'               => __( 'Search nearest airports', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No nearest airports', 'zestylemon' ),
			'items_list'                 => __( 'Nearest airports list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Nearest airports list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to nearest airports', 'zestylemon' ),
			'item_link'                  => __( 'Nearest airport link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a nearest airport', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_nearest_airports',
				'edit_terms'   => 'manage_nearest_airports',
				'delete_terms' => 'manage_nearest_airports',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('nearest_station', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Nearest stations', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Nearest station', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Nearest stations', 'zestylemon' ),
			'all_items'                  => __( 'All nearest stations', 'zestylemon' ),
			'parent_item'                => __( 'Parent nearest station', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent nearest station:', 'zestylemon' ),
			'new_item_name'              => __( 'New nearest station name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new nearest station', 'zestylemon' ),
			'edit_item'                  => __( 'Edit nearest station', 'zestylemon' ),
			'update_item'                => __( 'Update nearest station', 'zestylemon' ),
			'view_item'                  => __( 'View nearest station', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate nearest stations with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove nearest stations', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular nearest stations', 'zestylemon' ),
			'search_items'               => __( 'Search nearest stations', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No nearest stations', 'zestylemon' ),
			'items_list'                 => __( 'Nearest stations list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Nearest stations list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to nearest stations', 'zestylemon' ),
			'item_link'                  => __( 'Nearest station link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a nearest station', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_nearest_stations',
				'edit_terms'   => 'manage_nearest_stations',
				'delete_terms' => 'manage_nearest_stations',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('nearest_beach', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Nearest beaches', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Nearest beach', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Nearest beaches', 'zestylemon' ),
			'all_items'                  => __( 'All nearest beaches', 'zestylemon' ),
			'parent_item'                => __( 'Parent nearest beach', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent nearest beach:', 'zestylemon' ),
			'new_item_name'              => __( 'New nearest beach name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new nearest beach', 'zestylemon' ),
			'edit_item'                  => __( 'Edit nearest beach', 'zestylemon' ),
			'update_item'                => __( 'Update nearest beach', 'zestylemon' ),
			'view_item'                  => __( 'View nearest beach', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate nearest beaches with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove nearest beaches', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular nearest beaches', 'zestylemon' ),
			'search_items'               => __( 'Search nearest beaches', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No nearest beaches', 'zestylemon' ),
			'items_list'                 => __( 'Nearest beaches list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Nearest beaches list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to nearest beaches', 'zestylemon' ),
			'item_link'                  => __( 'Nearest beach link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a nearest beach', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_nearest_beaches',
				'edit_terms'   => 'manage_nearest_beaches',
				'delete_terms' => 'manage_nearest_beaches',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_complex', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property complexes', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property complex', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property complexes', 'zestylemon' ),
			'all_items'                  => __( 'All property complexes', 'zestylemon' ),
			'parent_item'                => __( 'Parent property complex', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property complex:', 'zestylemon' ),
			'new_item_name'              => __( 'New property complex name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property complex', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property complex', 'zestylemon' ),
			'update_item'                => __( 'Update property complex', 'zestylemon' ),
			'view_item'                  => __( 'View property complex', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property complexes with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property complexes', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property complexes', 'zestylemon' ),
			'search_items'               => __( 'Search property complexes', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property complexes', 'zestylemon' ),
			'items_list'                 => __( 'Property complexes list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property complexes list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property complexes', 'zestylemon' ),
			'item_link'                  => __( 'Property complex link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property complex', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_complexes',
				'edit_terms'   => 'manage_property_complexes',
				'delete_terms' => 'manage_property_complexes',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_swimming_pool_type', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property swimming pool types', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property swimming pool type', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property swimming pool types', 'zestylemon' ),
			'all_items'                  => __( 'All property swimming pool types', 'zestylemon' ),
			'parent_item'                => __( 'Parent property swimming pool type', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property swimming pool type:', 'zestylemon' ),
			'new_item_name'              => __( 'New property swimming pool type name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property swimming pool type', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property swimming pool type', 'zestylemon' ),
			'update_item'                => __( 'Update property swimming pool type', 'zestylemon' ),
			'view_item'                  => __( 'View property swimming pool type', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property swimming pool types with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property swimming pool types', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property swimming pool types', 'zestylemon' ),
			'search_items'               => __( 'Search property swimming pool types', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property swimming pool types', 'zestylemon' ),
			'items_list'                 => __( 'Property swimming pool types list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property swimming pool types list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property swimming pool types', 'zestylemon' ),
			'item_link'                  => __( 'Property swimming pool type link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property swimming pool type', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_swimming_pool_types',
				'edit_terms'   => 'manage_property_swimming_pool_types',
				'delete_terms' => 'manage_property_swimming_pool_types',
				'assign_terms' => 'edit_properties'
			)
		)
	);

	register_taxonomy('property_garden_terrace_type', 'property', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => _x( 'Property garden/terrace types', 'Taxonomy General Name', 'zestylemon' ),
			'singular_name'              => _x( 'Property garden/terrace type', 'Taxonomy Singular Name', 'zestylemon' ),
			'menu_name'                  => __( 'Property garden/terrace types', 'zestylemon' ),
			'all_items'                  => __( 'All property garden/terrace types', 'zestylemon' ),
			'parent_item'                => __( 'Parent property garden/terrace type', 'zestylemon' ),
			'parent_item_colon'          => __( 'Parent property garden/terrace type:', 'zestylemon' ),
			'new_item_name'              => __( 'New property garden/terrace type name', 'zestylemon' ),
			'add_new_item'               => __( 'Add new property garden/terrace type', 'zestylemon' ),
			'edit_item'                  => __( 'Edit property garden/terrace type', 'zestylemon' ),
			'update_item'                => __( 'Update property garden/terrace type', 'zestylemon' ),
			'view_item'                  => __( 'View property garden/terrace type', 'zestylemon' ),
			'separate_items_with_commas' => __( 'Separate property garden/terrace types with commas', 'zestylemon' ),
			'add_or_remove_items'        => __( 'Add or remove property garden/terrace types', 'zestylemon' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'zestylemon' ),
			'popular_items'              => __( 'Popular property garden/terrace types', 'zestylemon' ),
			'search_items'               => __( 'Search property garden/terrace types', 'zestylemon' ),
			'not_found'                  => __( 'Not found', 'zestylemon' ),
			'no_terms'                   => __( 'No property garden/terrace types', 'zestylemon' ),
			'items_list'                 => __( 'Property garden/terrace types list', 'zestylemon' ),
			'items_list_navigation'      => __( 'Property garden/terrace types list navigation', 'zestylemon' ),
			'back_to_items'              => __( '&larr; Go to property garden/terrace types', 'zestylemon' ),
			'item_link'                  => __( 'Property garden/terrace type link', 'zestylemon' ),
			'item_link_description'      => __( 'A link to a property garden/terrace type', 'zestylemon' ),
		),
		'query_var'         => true,
		'show_admin_column' => false,
		'capabilities'      => array (
				'manage_terms' => 'manage_property_garden_terrace_types',
				'edit_terms'   => 'manage_property_garden_terrace_types',
				'delete_terms' => 'manage_property_garden_terrace_types',
				'assign_terms' => 'edit_properties'
			)
		)
	);
}
add_action( 'init', 'zesty_lemon_register_post_types' );

