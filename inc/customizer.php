<?php
/** Theme Customizer controls. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function rjy_group_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'rjy_options', array( 'title' => __( 'RJY Theme Settings', 'rjy-group' ), 'priority' => 30 ) );
	$sections = array( 'rjy_brand' => __( 'Brand and Colors', 'rjy-group' ), 'rjy_hero' => __( 'Hero', 'rjy-group' ), 'rjy_home' => __( 'Homepage Headings', 'rjy-group' ), 'rjy_contact' => __( 'Contact Information', 'rjy-group' ), 'rjy_social' => __( 'Social Links', 'rjy-group' ) );
	foreach ( $sections as $id => $title ) { $wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'rjy_options' ) ); }
	$colors = array( 'rjy_primary_color' => array( 'Primary Navy', '#123b72' ), 'rjy_accent_color' => array( 'Accent Blue', '#1684d8' ) );
	foreach ( $colors as $id => $data ) { $wp_customize->add_setting( $id, array( 'default' => $data[1], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) ); $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => __( $data[0], 'rjy-group' ), 'section' => 'rjy_brand' ) ) ); }
	$fields = array(
		'rjy_hero_eyebrow' => array( 'Hero Eyebrow', 'Integrated facility expertise', 'rjy_hero', 'sanitize_text_field' ),
		'rjy_hero_title' => array( 'Hero Heading', 'Built for facilities that cannot stop.', 'rjy_hero', 'sanitize_text_field' ),
		'rjy_hero_text' => array( 'Hero Description', 'Reliable building systems, critical infrastructure, and operational technology support from one experienced team.', 'rjy_hero', 'sanitize_textarea_field' ),
		'rjy_hero_button' => array( 'Hero Button', 'Request an Inspection', 'rjy_hero', 'sanitize_text_field' ),
		'rjy_hero_url' => array( 'Hero Button URL', '#contact', 'rjy_hero', 'esc_url_raw' ),
		'rjy_about_heading' => array( 'About Heading', 'Delivering Service and Integrity', 'rjy_home', 'sanitize_text_field' ),
		'rjy_services_heading' => array( 'Services Heading', 'One team across your building systems.', 'rjy_home', 'sanitize_text_field' ),
		'rjy_portfolio_heading' => array( 'Portfolio Heading', 'See how disciplined planning becomes action.', 'rjy_home', 'sanitize_text_field' ),
		'rjy_testimonials_heading' => array( 'Testimonials Heading', 'A higher standard of facility partnership.', 'rjy_home', 'sanitize_text_field' ),
		'rjy_contact_heading' => array( 'Contact Heading', 'Let’s scope your facility priorities.', 'rjy_contact', 'sanitize_text_field' ),
		'rjy_phone' => array( 'Houston Phone', '(713) 838-1147', 'rjy_contact', 'sanitize_text_field' ),
		'rjy_toll_free' => array( 'Toll-Free Phone', '(866) 989-0869', 'rjy_contact', 'sanitize_text_field' ),
		'rjy_email' => array( 'Email', 'info@rjygroup.net', 'rjy_contact', 'sanitize_email' ),
		'rjy_address' => array( 'Street Address', '9040 Kirby Dr.', 'rjy_contact', 'sanitize_text_field' ),
		'rjy_city' => array( 'City, State and ZIP', 'Houston, TX 77054', 'rjy_contact', 'sanitize_text_field' ),
	);
	foreach ( $fields as $id => $data ) { $wp_customize->add_setting( $id, array( 'default' => $data[1], 'sanitize_callback' => $data[3], 'transport' => 'refresh' ) ); $wp_customize->add_control( $id, array( 'label' => __( $data[0], 'rjy-group' ), 'section' => $data[2], 'type' => in_array( $id, array( 'rjy_hero_text' ), true ) ? 'textarea' : 'text' ) ); }
	$media = array( 'rjy_hero_image' => array( 'Hero Image', 'rjy_hero' ), 'rjy_hero_video' => array( 'Hero Video', 'rjy_hero' ), 'rjy_about_image' => array( 'About Image', 'rjy_home' ) );
	foreach ( $media as $id => $data ) { $wp_customize->add_setting( $id, array( 'sanitize_callback' => 'absint' ) ); $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, array( 'label' => __( $data[0], 'rjy-group' ), 'section' => $data[1], 'mime_type' => 'rjy_hero_video' === $id ? 'video' : 'image' ) ) ); }
	foreach ( array( 'facebook', 'x', 'instagram', 'linkedin', 'vimeo' ) as $social ) { $id = 'rjy_' . $social; $wp_customize->add_setting( $id, array( 'sanitize_callback' => 'esc_url_raw' ) ); $wp_customize->add_control( $id, array( 'label' => ucfirst( $social ) . ' URL', 'section' => 'rjy_social', 'type' => 'url' ) ); }
}
add_action( 'customize_register', 'rjy_group_customize_register' );
