<?php
/** Markup helpers that mirror the React site's components (src/components). */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** WordPress URL for a React route path such as "/services/bas". */
function rjy_group_url( $path ) {
	$path = trim( (string) $path, '/' );
	return $path ? home_url( '/' . $path . '/' ) : home_url( '/' );
}

/** Class list produced by the shadcn Button component (src/components/ui/button.tsx). */
function rjy_group_button_class( $variant = 'default', $size = 'default', $extra = '' ) {
	$base = 'inline-flex items-center justify-center gap-2 whitespace-nowrap %s text-sm font-semibold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 disabled:cursor-not-allowed [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0';
	$variants = array(
		'default' => 'bg-primary text-primary-foreground hover:bg-accent',
		'outline' => 'border border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground',
		'ghost' => 'hover:bg-accent hover:text-accent-foreground',
	);
	$sizes = array( 'default' => 'h-11 px-5 py-2', 'lg' => 'h-12 rounded-sm px-7', 'icon' => 'h-9 w-9' );
	// tailwind-merge drops the base rounded-sm when the lg size supplies its own.
	$class = sprintf( $base, 'lg' === $size ? '' : 'rounded-sm' ) . ' ' . $variants[ $variant ] . ' ' . $sizes[ $size ];
	return trim( preg_replace( '/\s+/', ' ', $class . ' ' . $extra ) );
}

/** Button-styled link with a trailing arrow, as used throughout the React pages. */
function rjy_group_button_link( $path, $label, $variant = 'default', $size = 'lg', $extra = '' ) {
	return sprintf( '<a class="%1$s" href="%2$s">%3$s %4$s</a>', esc_attr( rjy_group_button_class( $variant, $size, $extra ) ), esc_url( rjy_group_url( $path ) ), esc_html( $label ), rjy_group_icon( 'arrow-right' ) );
}

/**
 * Dropdown groups for the header and mobile navigation.
 * Uses the menu assigned to the Primary location when present, otherwise the React site's defaults.
 */
function rjy_group_nav_groups() {
	$locations = get_nav_menu_locations();
	$items = ! empty( $locations['primary'] ) ? wp_get_nav_menu_items( $locations['primary'] ) : array();
	if ( $items ) {
		$groups = array();
		foreach ( $items as $item ) {
			if ( ! $item->menu_item_parent ) { $groups[ $item->ID ] = array( 'label' => $item->title, 'items' => array() ); }
		}
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && isset( $groups[ $item->menu_item_parent ] ) ) {
				$groups[ $item->menu_item_parent ]['items'][] = array( 'label' => $item->title, 'url' => $item->url );
			}
		}
		$groups = array_values( array_filter( $groups, static function ( $group ) { return ! empty( $group['items'] ); } ) );
		if ( $groups ) { return $groups; }
	}
	$groups = array();
	foreach ( rjy_group_default_menu_items() as $item ) {
		if ( empty( $item['children'] ) ) { continue; }
		$groups[] = array( 'label' => $item['label'], 'items' => array_map( static function ( $child ) { return array( 'label' => $child['label'], 'url' => home_url( $child['url'] ) ); }, $item['children'] ) );
	}
	return $groups;
}

function rjy_group_contact_mod( $key ) {
	$defaults = array( 'rjy_phone' => '(713) 838-1147', 'rjy_toll_free' => '(866) 989-0869', 'rjy_email' => 'info@rjygroup.net', 'rjy_address' => '9040 Kirby Dr.', 'rjy_city' => 'Houston, TX 77054' );
	return rjy_group_get_mod( $key, $defaults[ $key ] ?? '' );
}
