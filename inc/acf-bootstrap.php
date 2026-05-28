<?php
/**
 * ACF safety bootstrap.
 *
 * Provides no-op fallbacks for ACF functions used in templates so the theme
 * doesn't throw fatal errors when ACF Pro isn't yet installed. Real data
 * only appears once ACF Pro is active.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_field' ) ) {
	function get_field( $selector, $post_id = false, $format_value = true ) {
		return null;
	}
}

if ( ! function_exists( 'have_rows' ) ) {
	function have_rows( $selector, $post_id = false ) {
		return false;
	}
}

if ( ! function_exists( 'the_row' ) ) {
	function the_row( $format = false ) {
		return array();
	}
}

if ( ! function_exists( 'get_row_layout' ) ) {
	function get_row_layout() {
		return '';
	}
}

if ( ! function_exists( 'get_sub_field' ) ) {
	function get_sub_field( $selector, $format_value = true ) {
		return null;
	}
}
