<?php
/** RJY Group theme functions. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'RJY_GROUP_VERSION', '2.0.1' );
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/markup.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/content-types.php';
require_once get_template_directory() . '/inc/default-pages.php';
require_once get_template_directory() . '/inc/form-backend.php';
require_once get_template_directory() . '/inc/cf7-quote.php';
require_once get_template_directory() . '/inc/menu-setup.php';
require_once get_template_directory() . '/inc/content-migration.php';
require_once get_template_directory() . '/inc/internal-pages.php';
require_once get_template_directory() . '/inc/page-editor.php';

function rjy_group_setup() {
	load_theme_textdomain( 'rjy-group', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_editor_style( 'assets/css/editor-style.css' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 281, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array( 'primary' => __( 'Primary Menu', 'rjy-group' ), 'footer' => __( 'Footer Menu', 'rjy-group' ), 'legal' => __( 'Legal Menu', 'rjy-group' ) ) );
	add_image_size( 'rjy-card', 900, 675, true );
	add_image_size( 'rjy-hero', 1600, 900, true );
}
add_action( 'after_setup_theme', 'rjy_group_setup' );

function rjy_group_assets() {
	// app.css is the compiled stylesheet of the React site (src/styles.css), so both builds share one design.
	wp_enqueue_style( 'rjy-group-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'rjy-group-app', get_template_directory_uri() . '/assets/css/app.css', array( 'rjy-group-fonts' ), RJY_GROUP_VERSION );
	wp_enqueue_style( 'rjy-group-style', get_stylesheet_uri(), array( 'rjy-group-app' ), RJY_GROUP_VERSION );
	wp_enqueue_script( 'rjy-group-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), RJY_GROUP_VERSION, true );
	wp_dequeue_style( 'classic-theme-styles' );
	// Pages rendered from the React data use no blocks, and the block library's unlayered rules would override app.css.
	if ( is_front_page() || is_404() || is_page( 'advisor' ) || ( is_singular( array( 'page', 'rjy_service' ) ) && rjy_group_is_data_page( get_queried_object_id() ) ) ) {
		wp_dequeue_style( 'wp-block-library' );
	}
	if ( is_page( 'advisor' ) ) { wp_enqueue_script( 'rjy-group-advisor', get_template_directory_uri() . '/assets/js/advisor.js', array(), RJY_GROUP_VERSION, true ); }
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_action( 'wp_enqueue_scripts', 'rjy_group_assets' );

function rjy_group_widgets_init() {
	register_sidebar( array( 'name' => __( 'Content Sidebar', 'rjy-group' ), 'id' => 'sidebar-1', 'before_widget' => '<section id="%1$s" class="content-widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2>', 'after_title' => '</h2>' ) );
	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar( array( 'name' => sprintf( __( 'Footer Column %d', 'rjy-group' ), $i ), 'id' => 'footer-' . $i, 'before_widget' => '<section id="%1$s" class="footer-widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2>', 'after_title' => '</h2>' ) );
	}
}
add_action( 'widgets_init', 'rjy_group_widgets_init' );

function rjy_group_get_mod( $key, $default = '' ) { return get_theme_mod( $key, $default ); }
function rjy_group_asset( $file ) {
	$relative_path = '/assets/images/' . ltrim( $file, '/' );
	if ( ! file_exists( get_template_directory() . $relative_path ) ) {
		$relative_path = '/assets/' . ltrim( $file, '/' );
	}
	return esc_url( get_template_directory_uri() . $relative_path );
}
function rjy_group_phone_href( $phone ) { $digits = preg_replace( '/[^0-9+]/', '', $phone ); return 'tel:' . ( 10 === strlen( $digits ) ? '+1' . $digits : $digits ); }
function rjy_group_excerpt_length() { return 24; }
add_filter( 'excerpt_length', 'rjy_group_excerpt_length' );

function rjy_group_fallback_menu() {
	echo '<ul class="primary-menu">';
	foreach ( rjy_group_default_menu_items() as $item ) {
		$has_children = ! empty( $item['children'] );
		echo $has_children ? '<li class="menu-item menu-item-has-children">' : '<li class="menu-item">';
		printf( '<a href="%1$s">%2$s</a>', esc_url( home_url( $item['url'] ) ), esc_html( $item['label'] ) );
		if ( $has_children ) {
			echo '<ul class="sub-menu">';
			foreach ( $item['children'] as $child ) {
				printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( home_url( $child['url'] ) ), esc_html( $child['label'] ) );
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
function rjy_group_body_classes( $classes ) { if ( is_front_page() ) { $classes[] = 'rjy-front-page'; } return $classes; }
add_filter( 'body_class', 'rjy_group_body_classes' );

function rjy_group_schema() {
	if ( ! is_front_page() ) { return; }
	$data = array( '@context' => 'https://schema.org', '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ), 'telephone' => rjy_group_get_mod( 'rjy_phone', '(713) 838-1147' ), 'email' => sanitize_email( rjy_group_get_mod( 'rjy_email', 'info@rjygroup.net' ) ), 'address' => array( '@type' => 'PostalAddress', 'streetAddress' => rjy_group_get_mod( 'rjy_address', '9040 Kirby Dr.' ), 'addressLocality' => 'Houston', 'addressRegion' => 'TX', 'postalCode' => '77054' ) );
	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>';
}
add_action( 'wp_head', 'rjy_group_schema' );

function rjy_group_title_separator() { return '|'; }
add_filter( 'document_title_separator', 'rjy_group_title_separator' );

function rjy_group_favicon() {
	if ( ! has_site_icon() ) { printf( '<link rel="icon" href="%s" type="image/png">',rjy_group_asset( 'favicon.png' ) ); }
}
add_action( 'wp_head', 'rjy_group_favicon' );

/** theme.json global styles (body font, line height, link rules) would override app.css, so they stay editor-only. */
function rjy_group_disable_front_global_styles() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'rjy_group_disable_front_global_styles' );


// Block search engine indexing (remove when the site goes live)
add_filter( 'wp_robots', function ( $robots ) {
    $robots['noindex']  = true;
    $robots['nofollow'] = true;
    unset( $robots['follow'], $robots['index'] );
    return $robots;
});