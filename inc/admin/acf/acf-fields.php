<?php

function zesty_lemon_define_acf_fields() {
	if( function_exists('acf_add_local_field_group') ) {
		
		/*
		$locations = get_terms( array(
			'taxonomy'   => 'property_location',
			'hide_empty' => false,
		) );
		
		acf_add_local_field_group(array(
			'key' => 'group_61935de26ec6c',
			'title' => 'Property Location',
			'fields' => array(
				array(
					'key' => 'field_61935ded946b9_1',
					'label' => 'Administrative Level 1',
					'name' => 'administrative_level_1',
					'type' => 'taxonomy',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'taxonomy' => 'property_location',
					'field_type' => 'select',
					'allow_null' => 0,
					'add_term' => 0,
					'save_terms' => 1,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 0,
				),
				array(
					'key' => 'field_61935ded946b9_2',
					'label' => 'Administrative Level 2',
					'name' => 'administrative_level_2',
					'type' => 'taxonomy',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'taxonomy' => 'property_location',
					'field_type' => 'select',
					'allow_null' => 0,
					'add_term' => 0,
					'save_terms' => 1,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'property',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));
		*/


		/*ACF Field for Advance Bio for Author archieve page*/
		acf_add_local_field_group(array(
			'key' => 'group_638191ac02e31',
			'title' => 'Author’s expanded bio',
			'fields' => array(
				array(
					'key' => 'field_638191b861049',
					'label' => 'Expanded bio',
					'name' => 'advance_bio',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 1,
					'delay' => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'user_form',
						'operator' => '==',
						'value' => 'all',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));


	}
}

add_action( 'init', 'zesty_lemon_define_acf_fields' );