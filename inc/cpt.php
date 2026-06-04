<?php
/**
 * Custom Post Types: certification & class.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'accr_register_post_types', 5 );
function accr_register_post_types() {

	/* ----------------------------------------------------------------------
	 * Certifications CPT
	 * ---------------------------------------------------------------------- */
	register_post_type(
		'certification',
		array(
			'labels' => array(
				'name'               => __( 'Certifications', 'accr-theme' ),
				'singular_name'      => __( 'Certification', 'accr-theme' ),
				'add_new'            => __( 'Add Certification', 'accr-theme' ),
				'add_new_item'       => __( 'Add new certification', 'accr-theme' ),
				'edit_item'          => __( 'Edit certification', 'accr-theme' ),
				'new_item'           => __( 'New certification', 'accr-theme' ),
				'view_item'          => __( 'View certification', 'accr-theme' ),
				'search_items'       => __( 'Search certifications', 'accr-theme' ),
				'not_found'          => __( 'No certifications found.', 'accr-theme' ),
				'not_found_in_trash' => __( 'No certifications found in trash.', 'accr-theme' ),
				'menu_name'          => __( 'Certifications', 'accr-theme' ),
			),
			'show_ui'            => true,
			'capability_type'    => 'post',
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-awards',
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
		)
	);

	/* ----------------------------------------------------------------------
	 * Classes CPT — individual scheduled classes with dates/pricing.
	 * ---------------------------------------------------------------------- */
	register_post_type(
		'class',
		array(
			'labels' => array(
				'name'               => __( 'Classes', 'accr-theme' ),
				'singular_name'      => __( 'Class', 'accr-theme' ),
				'add_new'            => __( 'Add Class', 'accr-theme' ),
				'add_new_item'       => __( 'Add new class', 'accr-theme' ),
				'edit_item'          => __( 'Edit class', 'accr-theme' ),
				'new_item'           => __( 'New class', 'accr-theme' ),
				'view_item'          => __( 'View class', 'accr-theme' ),
				'search_items'       => __( 'Search classes', 'accr-theme' ),
				'not_found'          => __( 'No classes found.', 'accr-theme' ),
				'not_found_in_trash' => __( 'No classes found in trash.', 'accr-theme' ),
				'menu_name'          => __( 'Classes', 'accr-theme' ),
			),
			'public'             => true,
			'rewrite'            => array( 'slug' => 'class', 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false, // Schedule rendered via "Classes" page + classes_table layout.
			'menu_position'      => 21,
			'menu_icon'          => 'dashicons-calendar-alt',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		)
	);

	/* ----------------------------------------------------------------------
	 * Class category taxonomy (Mobile, Articulating, Rigger, etc.)
	 * Used both for filtering classes and for linking class entries to certification topics.
	 * ---------------------------------------------------------------------- */
	register_taxonomy(
		'class_category',
		array( 'class' ),
		array(
			'labels' => array(
				'name'          => __( 'Class Categories', 'accr-theme' ),
				'singular_name' => __( 'Class Category', 'accr-theme' ),
				'menu_name'     => __( 'Categories', 'accr-theme' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'class-category' ),
		)
	);
}

/* ----------------------------------------------------------------------
 * Admin columns for Classes — date + tuition at-a-glance.
 * ---------------------------------------------------------------------- */
add_filter( 'manage_class_posts_columns', 'accr_class_admin_columns' );
function accr_class_admin_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['class_date']    = __( 'Date', 'accr-theme' );
			$new['class_time']    = __( 'Time', 'accr-theme' );
			$new['class_tuition'] = __( 'Tuition', 'accr-theme' );
		}
	}
	return $new;
}

add_action( 'manage_class_posts_custom_column', 'accr_class_admin_column_content', 10, 2 );
function accr_class_admin_column_content( $col, $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	switch ( $col ) {
		case 'class_date':
			$d = get_field( 'class_date', $post_id );
			echo esc_html( $d ? $d : '—' );
			break;
		case 'class_time':
			$t = get_field( 'class_time', $post_id );
			echo esc_html( $t ? $t : '—' );
			break;
		case 'class_tuition':
			$display = get_field( 'tuition_display', $post_id );
			$price   = get_field( 'price', $post_id );
			if ( $display ) {
				echo esc_html( $display );
			} elseif ( $price ) {
				echo esc_html( '$' . number_format( (float) $price, 2 ) );
			} else {
				echo '<em>Request pricing</em>';
			}
			break;
	}
}
