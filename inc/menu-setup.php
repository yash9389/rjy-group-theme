<?php
/** Create an editable default navigation menu on fresh theme activation. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rjy_group_default_menu_items() {
	return array(
		array( 'label' => __( 'Home', 'rjy-group' ), 'url' => '/', 'children' => array() ),
		array(
			'label' => __( 'Services', 'rjy-group' ),
			'url' => '/services/',
			'children' => array(
				array( 'label' => __( 'Building Automation Systems (BAS)', 'rjy-group' ), 'url' => '/services/building-automation-systems/' ),
				array( 'label' => __( 'Cyber Security for OT and ICS', 'rjy-group' ), 'url' => '/services/cyber-security-for-ot-and-ics/' ),
				array( 'label' => __( 'HVAC Systems', 'rjy-group' ), 'url' => '/services/hvac-systems/' ),
				array( 'label' => __( 'Generators', 'rjy-group' ), 'url' => '/services/generator-services/' ),
				array( 'label' => __( 'Support Staffing', 'rjy-group' ), 'url' => '/services/support-staffing/' ),
				array( 'label' => __( 'Chiller Preventive Maintenance', 'rjy-group' ), 'url' => '/services/chiller-preventive-maintenance/' ),
			),
		),
		array(
			'label' => __( 'Industries', 'rjy-group' ),
			'url' => '/industries/',
			'children' => array(
				array( 'label' => __( 'Healthcare & Hospitals', 'rjy-group' ), 'url' => '/industries/healthcare-and-hospitals/' ),
				array( 'label' => __( 'Government & Public Safety', 'rjy-group' ), 'url' => '/industries/government-and-public-safety/' ),
				array( 'label' => __( 'Industrial & Commercial Campuses', 'rjy-group' ), 'url' => '/industries/industrial-and-commercial-campuses/' ),
				array( 'label' => __( 'Higher Education', 'rjy-group' ), 'url' => '/industries/higher-education/' ),
				array( 'label' => __( 'Transportation & Utilities', 'rjy-group' ), 'url' => '/industries/transportation-and-utilities/' ),
			),
		),
		array(
			'label' => __( 'About Us', 'rjy-group' ),
			'url' => '/about/',
			'children' => array(
				array( 'label' => __( 'Company Overview', 'rjy-group' ), 'url' => '/about/about-rjy-group-houston/' ),
				array( 'label' => __( 'Why RJY Group', 'rjy-group' ), 'url' => '/about/why-rjy-group/' ),
				array( 'label' => __( 'Mission & Core Values', 'rjy-group' ), 'url' => '/mission-core-values/' ),
				array( 'label' => __( 'About the Founder', 'rjy-group' ), 'url' => '/about/founder/' ),
				array( 'label' => __( 'Certifications & Accreditations', 'rjy-group' ), 'url' => '/about/certifications/' ),
				array( 'label' => __( 'Federal & Regional Capabilities', 'rjy-group' ), 'url' => '/about/federal-capabilities/' ),
			),
		),
		array(
			'label' => __( 'Support', 'rjy-group' ),
			'url' => '/support/',
			'children' => array(
				array( 'label' => __( 'Request an Inspection / Get a Quote', 'rjy-group' ), 'url' => '/support/request-quote/' ),
				array( 'label' => __( 'Contact Support', 'rjy-group' ), 'url' => '/support/contact/' ),
				array( 'label' => __( 'FAQs', 'rjy-group' ), 'url' => '/support/faqs/' ),
				array( 'label' => __( 'Resources & Downloads', 'rjy-group' ), 'url' => '/support/resources/' ),
			),
		),
	);
}

function rjy_group_footer_link_groups() {
	$locations = get_nav_menu_locations();
	$items = ! empty( $locations['primary'] ) ? wp_get_nav_menu_items( $locations['primary'] ) : array();
	$groups = array();
	if ( $items ) {
		foreach ( $items as $item ) {
			if ( ! $item->menu_item_parent ) {
				$groups[ $item->ID ] = array( 'title' => 'About Us' === $item->title ? __( 'Company', 'rjy-group' ) : $item->title, 'items' => array() );
			}
		}
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && isset( $groups[ $item->menu_item_parent ] ) ) {
				$path = wp_parse_url( $item->url, PHP_URL_PATH );
				$groups[ $item->menu_item_parent ]['items'][] = array( 'label' => $item->title, 'url' => $path ? $path : '/' );
			}
		}
		$groups = array_values( array_filter( $groups, static function ( $g ) { return ! empty( $g['items'] ); } ) );
	}
	if ( ! $groups ) {
		$menu = rjy_group_default_menu_items();
		$groups = array(
			array( 'title' => __( 'Services', 'rjy-group' ), 'items' => $menu[1]['children'] ),
			array( 'title' => __( 'Industries', 'rjy-group' ), 'items' => $menu[2]['children'] ),
			array( 'title' => __( 'Company', 'rjy-group' ), 'items' => $menu[3]['children'] ),
			array( 'title' => __( 'Support', 'rjy-group' ), 'items' => $menu[4]['children'] ),
		);
	}
	foreach ( $groups as $k => $g ) {
		if ( 'Company' === $g['title'] ) {
			$groups[ $k ]['items'][] = array( 'label' => __( 'Testimonials', 'rjy-group' ), 'url' => '/testimonials/' );
		}
	}
	return $groups;
}

function rjy_group_create_default_menu() {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['primary'] ) ) {
		$assigned = wp_get_nav_menu_object( $locations['primary'] );
		if ( $assigned && ! is_wp_error( $assigned ) && wp_get_nav_menu_items( $assigned->term_id ) ) {
			return (int) $assigned->term_id;
		}
		$menu_id = $assigned && ! is_wp_error( $assigned ) ? (int) $assigned->term_id : 0;
	} else {
		$menu = wp_get_nav_menu_object( 'rjy-main-menu' );
		$menu_id = $menu && ! is_wp_error( $menu ) ? (int) $menu->term_id : 0;
	}

	if ( ! $menu_id ) {
		$menu_id = wp_create_nav_menu( __( 'RJY Main Menu', 'rjy-group' ) );
		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}
	}

	foreach ( rjy_group_default_menu_items() as $item ) {
		$parent_id = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title' => $item['label'],
				'menu-item-url' => home_url( $item['url'] ),
				'menu-item-type' => 'custom',
				'menu-item-status' => 'publish',
			)
		);
		if ( is_wp_error( $parent_id ) ) {
			continue;
		}
		foreach ( $item['children'] as $child ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title' => $child['label'],
					'menu-item-url' => home_url( $child['url'] ),
					'menu-item-type' => 'custom',
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => $parent_id,
				)
			);
		}
	}

	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
	return (int) $menu_id;
}
add_action( 'after_switch_theme', 'rjy_group_create_default_menu' );
