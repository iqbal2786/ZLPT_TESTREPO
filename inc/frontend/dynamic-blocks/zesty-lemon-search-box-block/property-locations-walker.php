<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Property_Locations_Walker' ) ) :

	class Property_Locations_Walker extends Walker {
		public $tree_type = 'category';
		public $db_fields = array(
			'parent' => 'parent',
			'id'     => 'term_id',
		);
	
		public function start_lvl( &$output, $depth = 0, $args = array() ) {}
	
		public function end_lvl( &$output, $depth = 0, $args = array() ) {}
	
		public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
			$locations = isset( $_GET['property-location'] ) ? esc_attr( $_GET['property-location'] ) : '';
			$locations_array = array();
			if ( ! empty( $locations ) ) {
				$locations_array = explode( ',', $locations );
				$locations_array = array_map( 'trim', $locations_array );
			}			
			$category = $data_object;
			$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );
			$indent   = str_repeat( '&nbsp;&nbsp;&nbsp;&nbsp;', $depth );
			$output  .= '<li data-id="' . $category->slug . '" class="' . ( in_array( $category->slug, $locations_array ) ? 'selected' : '' ) . '">' . $indent . '<span></span>' . $cat_name;
		}
	
		public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
			$output .= "</li>";
		}
	}

endif;
