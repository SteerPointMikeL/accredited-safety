<?php
/**
 * Gravity Forms styling hooks.
 *
 * Adds opt-in body classes and CSS targeting so Gravity Forms inherit the
 * theme's form aesthetics (matching the static design's .field/.form-row look)
 * without hard-coding any specific form structure.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body class flag when Gravity Forms is active so CSS can target it.
 */
add_filter( 'body_class', 'accr_body_class_gravityforms' );
function accr_body_class_gravityforms( $classes ) {
	if ( class_exists( 'GFForms' ) ) {
		$classes[] = 'has-gravityforms';
	}
	return $classes;
}

/**
 * Disable Gravity Forms' built-in CSS so the theme styling fully controls form look.
 * Site owners can re-enable by removing this filter via a child theme.
 */
add_filter( 'gform_disable_css', 'accr_gform_disable_css', 10, 1 );
function accr_gform_disable_css( $disable ) {
	return true;
}

/**
 * Wrap each Gravity Form in our themed container so spacing/border treatment
 * matches the static design's form card.
 */
add_filter( 'gform_form_tag', 'accr_gform_form_tag', 10, 2 );
function accr_gform_form_tag( $form_tag, $form ) {
	return $form_tag;
}

/**
 * Inject .field / .form-row compatible classes on Gravity Forms fields so the
 * existing CSS in assets/css/style.css renders them correctly.
 */
add_filter( 'gform_field_container', 'accr_gform_field_container', 10, 6 );
function accr_gform_field_container( $container, $field, $form, $css_class, $style, $field_content ) {
	// $container already contains the wrapper <li> / <div>. Just add an extra "field" class so styles cascade.
	if ( false === strpos( $container, 'class="' ) ) {
		return $container;
	}
	return preg_replace( '/class="([^"]*)"/', 'class="$1 field"', $container, 1 );
}

/**
 * Theme button class on the GF submit button.
 */
add_filter( 'gform_submit_button', 'accr_gform_submit_button', 10, 2 );
function accr_gform_submit_button( $button, $form ) {
	$button = preg_replace( '/class="([^"]*)"/', 'class="$1 btn btn--primary btn--lg btn--block"', $button, 1 );
	return $button;
}

/**
 * Render a Gravity Form by ID, returning a placeholder if Gravity Forms isn't
 * available so admins see *something* during local dev.
 *
 * @param int $form_id
 * @return string HTML
 */
function accr_render_gravity_form( $form_id ) {
	$form_id = absint( $form_id );
	if ( ! $form_id ) {
		return '';
	}
	if ( function_exists( 'gravity_form' ) ) {
		ob_start();
		gravity_form( $form_id, false, false, false, null, true, 0, true );
		return ob_get_clean();
	}
	// Soft fallback so editors can see where the form will appear.
	return '<div class="gf-placeholder" style="border:1px dashed var(--color-divider); padding: var(--space-6); border-radius: var(--radius-md); color: var(--color-text-muted);">'
		. esc_html( sprintf( 'Gravity Form #%d will render here (Gravity Forms is not active).', $form_id ) )
		. '</div>';
}
