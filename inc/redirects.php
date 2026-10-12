<?php
/** 301 redirects from the original short URLs to the full service and industry names. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function rjy_group_legacy_redirects() {
	return array(
		'services/bas' => 'services/building-automation-systems',
		'services/cybersecurity-ot-ics' => 'services/cyber-security-for-ot-and-ics',
		'services/hvac' => 'services/hvac-systems',
		'services/generators' => 'services/generator-services',
		'industries/healthcare-hospitals' => 'industries/healthcare-and-hospitals',
		'industries/government-public-safety' => 'industries/government-and-public-safety',
		'industries/industrial-commercial' => 'industries/industrial-and-commercial-campuses',
		'industries/transportation-utilities' => 'industries/transportation-and-utilities',
		'about/overview' => 'about/about-rjy-group-houston',
	);
}

function rjy_group_maybe_redirect_legacy_url() {
	if ( is_admin() || empty( $_SERVER['REQUEST_URI'] ) ) { return; }
	$req = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' );
	$home = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( $home && 0 === strpos( $req, $home . '/' ) ) { $req = substr( $req, strlen( $home ) + 1 ); }
	$map = rjy_group_legacy_redirects();
	if ( isset( $map[ $req ] ) ) {
		$qs = isset( $_SERVER['QUERY_STRING'] ) && '' !== $_SERVER['QUERY_STRING'] ? '?' . wp_unslash( $_SERVER['QUERY_STRING'] ) : '';
		wp_safe_redirect( home_url( '/' . $map[ $req ] . '/' ) . $qs, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'rjy_group_maybe_redirect_legacy_url', 1 );