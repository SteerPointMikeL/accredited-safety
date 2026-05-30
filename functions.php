<?php
/**
 * Accredited Safety Solutions — theme functions
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ACCR_THEME_VERSION', '1.0.2' );
define( 'ACCR_THEME_DIR', get_template_directory() );
define( 'ACCR_THEME_URI', get_template_directory_uri() );

/* --------------------------------------------------------------------------
 * Theme setup
 * -------------------------------------------------------------------------- */
add_action( 'after_setup_theme', 'accr_theme_setup' );
function accr_theme_setup() {
	load_theme_textdomain( 'accr-theme', ACCR_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-logo' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'accr-theme' ),
			'footer' => __( 'Footer', 'accr-theme' ),
		)
	);

	add_image_size( 'accr-card', 800, 600, true );
	add_image_size( 'accr-hero', 2000, 1200, true );
}

/* --------------------------------------------------------------------------
 * Enqueue styles & scripts
 * -------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'accr_theme_enqueue' );
function accr_theme_enqueue() {
	// Google Fonts — same families as the static design.
	wp_enqueue_style(
		'accr-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=Barlow+Semi+Condensed:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'accr-base', ACCR_THEME_URI . '/assets/css/base.css', array(), ACCR_THEME_VERSION );
	wp_enqueue_style( 'accr-style', ACCR_THEME_URI . '/assets/css/style.css', array( 'accr-base' ), ACCR_THEME_VERSION );
	wp_enqueue_style( 'accr-staff', ACCR_THEME_URI . '/assets/css/staff.css', array( 'accr-base' ), ACCR_THEME_VERSION );

	// Theme stylesheet (mostly for WordPress validation; design lives in base+style).
	wp_enqueue_style( 'accr-theme', get_stylesheet_uri(), array( 'accr-style' ), ACCR_THEME_VERSION );

	wp_enqueue_script( 'accr-main', ACCR_THEME_URI . '/assets/js/main.js', array(), ACCR_THEME_VERSION, true );
}

/* --------------------------------------------------------------------------
 * Include modules
 * -------------------------------------------------------------------------- */
require_once ACCR_THEME_DIR . '/inc/cpt.php';
require_once ACCR_THEME_DIR . '/inc/acf-fields.php';
require_once ACCR_THEME_DIR . '/inc/acf-bootstrap.php';
require_once ACCR_THEME_DIR . '/inc/gravity-forms.php';
require_once ACCR_THEME_DIR . '/inc/helpers.php';
require_once ACCR_THEME_DIR . '/inc/nav-walker.php';

/* --------------------------------------------------------------------------
 * ACF Local JSON: save/load to /acf-json so field groups stay in version control.
 * -------------------------------------------------------------------------- */
add_filter( 'acf/settings/save_json', 'accr_acf_json_save_point' );
function accr_acf_json_save_point( $path ) {
	return ACCR_THEME_DIR . '/acf-json';
}

add_filter( 'acf/settings/load_json', 'accr_acf_json_load_point' );
function accr_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = ACCR_THEME_DIR . '/acf-json';
	return $paths;
}

/* --------------------------------------------------------------------------
 * Admin notice when ACF Pro is missing.
 * -------------------------------------------------------------------------- */
add_action( 'admin_notices', 'accr_theme_acf_pro_notice' );
function accr_theme_acf_pro_notice() {
	if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'ACF' ) ) {
		echo '<div class="notice notice-error"><p><strong>Accredited Safety Solutions theme:</strong> Advanced Custom Fields Pro is required for page layouts to render. Please install and activate ACF Pro.</p></div>';
	}
}

/* --------------------------------------------------------------------------
 * Render the page sections flexible content. Used by templates.
 * -------------------------------------------------------------------------- */
function accr_render_page_sections( $post_id = null ) {
	if ( ! function_exists( 'have_rows' ) ) {
		return;
	}
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	if ( have_rows( 'page_sections', $post_id ) ) {
		while ( have_rows( 'page_sections', $post_id ) ) {
			the_row();
			$layout = get_row_layout();
			$slug   = sanitize_file_name( $layout );
			$file   = ACCR_THEME_DIR . '/template-parts/flexible/' . $slug . '.php';
			if ( file_exists( $file ) ) {
				include $file;
			}
		}
	}
}
