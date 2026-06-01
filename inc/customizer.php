<?php
/**
 * Theme Customizer settings.
 *
 * Surfaces the theme_mods used throughout the templates (footer contact info,
 * form IDs, newsletter content) so site owners can manage copy and form wiring
 * without editing template files.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', 'accr_customize_register' );
function accr_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'accr_newsletter',
		array(
			'title'    => __( 'Footer Newsletter', 'accr-theme' ),
			'priority' => 160,
		)
	);

	$wp_customize->add_setting(
		'accr_newsletter_form_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'accr_newsletter_form_id',
		array(
			'label'       => __( 'Newsletter Gravity Forms ID', 'accr-theme' ),
			'description' => __( 'The Gravity Forms form shown in the newsletter modal. Leave at 0 to show a placeholder until a form is configured.', 'accr-theme' ),
			'section'     => 'accr_newsletter',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'step' => 1 ),
		)
	);

	$wp_customize->add_setting(
		'accr_newsletter_heading',
		array(
			'default'           => 'Sign up for our newsletter',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'accr_newsletter_heading',
		array(
			'label'   => __( 'Newsletter card heading', 'accr-theme' ),
			'section' => 'accr_newsletter',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'accr_newsletter_description',
		array(
			'default'           => 'Stay current on NCCCO requirements, upcoming classes, and safety regulations.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'accr_newsletter_description',
		array(
			'label'   => __( 'Newsletter card description', 'accr-theme' ),
			'section' => 'accr_newsletter',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'accr_newsletter_modal_heading',
		array(
			'default'           => 'Sign up for our newsletter',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'accr_newsletter_modal_heading',
		array(
			'label'   => __( 'Newsletter modal heading', 'accr-theme' ),
			'section' => 'accr_newsletter',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'accr_newsletter_modal_subtitle',
		array(
			'default'           => 'Get NCCCO updates, class schedules, and safety guidance delivered to your inbox.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'accr_newsletter_modal_subtitle',
		array(
			'label'   => __( 'Newsletter modal subtitle', 'accr-theme' ),
			'section' => 'accr_newsletter',
			'type'    => 'textarea',
		)
	);
}
