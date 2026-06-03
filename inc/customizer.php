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
	/* ---------------------------------------------------------------------
	 * Contact info — phone + email read across header, footer, single-class.
	 *
	 * Note: single-class.php historically hardcoded a different phone fallback
	 * (844-484-9628) than header/footer (844-717-3665). These all read the same
	 * theme_mod keys, so once set in the Customizer a single value applies
	 * site-wide; the README-canonical 844-717-3665 is used as the default.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'accr_contact',
		array(
			'title'    => __( 'Contact Info', 'accr-theme' ),
			'priority' => 155,
		)
	);

	$wp_customize->add_setting(
		'accr_phone_display',
		array(
			'default'           => '844-717-3665',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'accr_phone_display',
		array(
			'label'       => __( 'Phone number (display)', 'accr-theme' ),
			'description' => __( 'Human-readable phone number shown in the header, footer, and class pages.', 'accr-theme' ),
			'section'     => 'accr_contact',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'accr_phone_link',
		array(
			'default'           => 'tel:8447173665',
			'sanitize_callback' => 'accr_sanitize_tel',
		)
	);
	$wp_customize->add_control(
		'accr_phone_link',
		array(
			'label'       => __( 'Phone number (tel: link)', 'accr-theme' ),
			'description' => __( 'The href used for click-to-call links, e.g. tel:8447173665.', 'accr-theme' ),
			'section'     => 'accr_contact',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'accr_email',
		array(
			'default'           => 'info@accredited-safety.com',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'accr_email',
		array(
			'label'       => __( 'Contact email address', 'accr-theme' ),
			'description' => __( 'Email address shown in the header and footer.', 'accr-theme' ),
			'section'     => 'accr_contact',
			'type'        => 'email',
		)
	);

	/* ---------------------------------------------------------------------
	 * Footer — copyright + legal line read in footer.php bottom bar.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'accr_footer',
		array(
			'title'    => __( 'Footer', 'accr-theme' ),
			'priority' => 158,
		)
	);

	$wp_customize->add_setting(
		'accr_copyright',
		array(
			'default'           => '© ' . date_i18n( 'Y' ) . ' Accredited Safety Solutions. All rights reserved.',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'accr_copyright',
		array(
			'label'       => __( 'Copyright line', 'accr-theme' ),
			'description' => __( 'Shown in the footer bottom bar. Leave blank to fall back to the current-year default.', 'accr-theme' ),
			'section'     => 'accr_footer',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'accr_legal_line',
		array(
			'default'           => 'NCCCO is a registered trademark of the National Commission for the Certification of Crane Operators.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'accr_legal_line',
		array(
			'label'   => __( 'Legal / trademark line', 'accr-theme' ),
			'section' => 'accr_footer',
			'type'    => 'textarea',
		)
	);

	/* ---------------------------------------------------------------------
	 * Forms — Gravity Forms IDs read in footer.php (pricing modal +
	 * newsletter modal). The newsletter form ID lives in its own section
	 * below for backwards compatibility.
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'accr_forms',
		array(
			'title'    => __( 'Forms', 'accr-theme' ),
			'priority' => 159,
		)
	);

	$wp_customize->add_setting(
		'accr_pricing_form_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'accr_pricing_form_id',
		array(
			'label'       => __( 'Request-Pricing Gravity Forms ID', 'accr-theme' ),
			'description' => __( 'The Gravity Forms form that replaces the legacy Request-Pricing modal. Leave at 0 to show the built-in fallback form.', 'accr-theme' ),
			'section'     => 'accr_forms',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'step' => 1 ),
		)
	);

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

/**
 * Sanitize a click-to-call value. Accepts a bare number or a tel: URI and
 * always returns a normalized tel: link with non-dialable characters removed.
 */
function accr_sanitize_tel( $value ) {
	$value = sanitize_text_field( $value );
	$value = preg_replace( '/^tel:/i', '', $value );
	$value = preg_replace( '/[^0-9+\-().\s]/', '', $value );
	$value = trim( $value );

	return '' === $value ? '' : 'tel:' . $value;
}
