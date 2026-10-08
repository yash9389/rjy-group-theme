<?php
/**
 * Creates editable starter pages (Services by Industry, About Us).
 *
 * Pages are created once on theme activation, or on demand from
 * Appearance > RJY Starter Pages. Existing pages are never overwritten,
 * so all edits made in the WordPress editor are preserved.
 *
 * @package RJY_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Starter page definitions.
 *
 * @return array<string, array{title: string, file: string, excerpt: string}>
 */
function rjy_group_starter_pages() {
	return array(
		'services-by-industry' => array(
			'title'   => __( 'Services by Industry', 'rjy-group' ),
			'file'    => 'services-by-industry.html',
			'excerpt' => __( 'The complete list of RJY Group services and how they are delivered across healthcare, government, industrial, education, and transportation facilities.', 'rjy-group' ),
		),
		'about-us'             => array(
			'title'   => __( 'About Us', 'rjy-group' ),
			'file'    => 'about-us.html',
			'excerpt' => __( 'Company overview, mission, founder, leadership, certifications, and capabilities of RJY Group, a Houston-based SDVOSB facilities partner.', 'rjy-group' ),
		),
	);
}

/**
 * Creates any starter pages that do not yet exist.
 *
 * @return int Number of pages created.
 */
function rjy_group_create_starter_pages() {
	$created = 0;
	foreach ( rjy_group_starter_pages() as $slug => $page ) {
		if ( get_page_by_path( $slug, OBJECT, 'page' ) ) {
			continue;
		}
		$path    = get_template_directory() . '/inc/page-content/' . $page['file'];
		$content = file_exists( $path ) ? file_get_contents( $path ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$id      = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $page['title'],
				'post_excerpt' => $page['excerpt'],
				'post_content' => wp_kses_post( $content ),
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			++$created;
		}
	}
	return $created;
}
add_action( 'after_switch_theme', 'rjy_group_create_starter_pages' );

/**
 * Registers the admin screen for restoring missing starter pages.
 */
function rjy_group_starter_pages_menu() {
	add_theme_page(
		__( 'RJY Starter Pages', 'rjy-group' ),
		__( 'RJY Starter Pages', 'rjy-group' ),
		'edit_pages',
		'rjy-starter-pages',
		'rjy_group_starter_pages_screen'
	);
}
add_action( 'admin_menu', 'rjy_group_starter_pages_menu' );

/**
 * Renders the starter pages admin screen.
 */
function rjy_group_starter_pages_screen() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	$created = null;
	if ( isset( $_POST['rjy_create_pages'] ) && check_admin_referer( 'rjy_starter_pages' ) ) {
		$created = rjy_group_create_starter_pages();
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'RJY Starter Pages', 'rjy-group' ) . '</h1>';
	if ( null !== $created ) {
		/* translators: %d: number of pages created. */
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( _n( '%d page created.', '%d pages created.', $created, 'rjy-group' ), $created ) ) . '</p></div>';
	}
	echo '<p>' . esc_html__( 'These pages are fully editable in Pages. Missing pages can be recreated below; existing pages are never changed.', 'rjy-group' ) . '</p><ul>';
	foreach ( rjy_group_starter_pages() as $slug => $page ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		echo '<li><strong>' . esc_html( $page['title'] ) . '</strong>: ';
		if ( $existing ) {
			echo '<a href="' . esc_url( get_edit_post_link( $existing->ID ) ) . '">' . esc_html__( 'Edit page', 'rjy-group' ) . '</a> | <a href="' . esc_url( get_permalink( $existing ) ) . '">' . esc_html__( 'View', 'rjy-group' ) . '</a>';
		} else {
			esc_html_e( 'Not created yet', 'rjy-group' );
		}
		echo '</li>';
	}
	echo '</ul><form method="post">';
	wp_nonce_field( 'rjy_starter_pages' );
	submit_button( __( 'Create missing pages', 'rjy-group' ), 'primary', 'rjy_create_pages' );
	echo '</form></div>';
}
