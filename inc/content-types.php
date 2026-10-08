<?php
/** Editable homepage content types. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function rjy_group_register_content_types() {
	$types = array(
		'rjy_service' => array( 'singular' => __( 'Service', 'rjy-group' ), 'plural' => __( 'Services', 'rjy-group' ), 'icon' => 'dashicons-admin-tools' ),
		'rjy_project' => array( 'singular' => __( 'Portfolio Project', 'rjy-group' ), 'plural' => __( 'Portfolio Projects', 'rjy-group' ), 'icon' => 'dashicons-portfolio' ),
		'rjy_testimonial' => array( 'singular' => __( 'Testimonial', 'rjy-group' ), 'plural' => __( 'Testimonials', 'rjy-group' ), 'icon' => 'dashicons-format-quote' ),
	);
	foreach ( $types as $slug => $type ) {
		$labels = array( 'name' => $type['plural'], 'singular_name' => $type['singular'], 'add_new_item' => sprintf( __( 'Add New %s', 'rjy-group' ), $type['singular'] ), 'edit_item' => sprintf( __( 'Edit %s', 'rjy-group' ), $type['singular'] ), 'search_items' => sprintf( __( 'Search %s', 'rjy-group' ), $type['plural'] ), 'not_found' => __( 'No entries found.', 'rjy-group' ) );
		$rewrite_slug = 'rjy_service' === $slug ? 'services' : str_replace( 'rjy_', '', $slug );
		register_post_type( $slug, array( 'labels' => $labels, 'public' => true, 'show_in_rest' => true, 'menu_icon' => $type['icon'], 'has_archive' => false, 'rewrite' => array( 'slug' => $rewrite_slug, 'with_front' => false ), 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ) ) );
	}
}
add_action( 'init', 'rjy_group_register_content_types' );
