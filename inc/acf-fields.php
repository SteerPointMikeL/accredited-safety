<?php
/**
 * ACF field group definitions (PHP fallback).
 *
 * These mirror the JSON in /acf-json — the PHP definitions guarantee the
 * fields exist even before an admin first visits the ACF UI to sync JSON.
 *
 * Two field groups are registered:
 *   1. "Page Sections" — single flexible content field reused on every page (page, certification CPT, class CPT, Classes/Certs archive page).
 *   2. "Class Details" — date / time / price fields on the Class CPT.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'accr_register_acf_fields' );
function accr_cta_button_subfields( $key_prefix ) {
	return array(
		array(
			'key'   => $key_prefix . '_label',
			'label' => 'Label',
			'name'  => 'label',
			'type'  => 'text',
		),
		array(
			'key'   => $key_prefix . '_url',
			'label' => 'URL',
			'name'  => 'url',
			'type'  => 'text',
			'instructions' => 'Use a relative path (e.g. /classes/) or full URL. tel: and mailto: also supported.',
		),
		array(
			'key'     => $key_prefix . '_style',
			'label'   => 'Style',
			'name'    => 'style',
			'type'    => 'select',
			'choices' => array(
				'btn--primary'   => 'Primary (orange)',
				'btn--secondary' => 'Secondary (navy)',
				'btn--outline'   => 'Outline',
				'btn--ghost'     => 'Ghost (white on dark)',
			),
			'default_value' => 'btn--primary',
			'return_format' => 'value',
		),
		array(
			'key'     => $key_prefix . '_size',
			'label'   => 'Size',
			'name'    => 'size',
			'type'    => 'select',
			'choices' => array(
				''       => 'Default',
				'btn--lg' => 'Large',
			),
			'default_value' => 'btn--lg',
			'return_format' => 'value',
		),
		array(
			'key'   => $key_prefix . '_open_modal',
			'label' => 'Triggers pricing modal (legacy)',
			'name'  => 'open_modal',
			'type'  => 'true_false',
			'ui'    => 1,
			'instructions' => 'Optional. If checked, this button opens the legacy Request-Pricing modal. Leave off if you use Gravity Forms.',
		),
	);
}

function accr_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ======================================================================
	 * "Page Sections" flexible content group — applied to multiple post types.
	 * ====================================================================== */
	acf_add_local_field_group(
		array(
			'key'    => 'group_page_sections',
			'title'  => 'Page Sections',
			'fields' => array(
				array(
					'key'        => 'field_page_sections',
					'label'      => 'Sections',
					'name'       => 'page_sections',
					'type'       => 'flexible_content',
					'button_label' => 'Add section',
					'layouts'    => array(

						/* -------------------------------------------------- */
						'layout_page_hero' => array(
							'key'     => 'layout_page_hero',
							'name'    => 'page_hero',
							'label'   => 'Page hero (eyebrow + title + lead)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_ph_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_ph_title',   'label' => 'Title (HTML allowed, e.g. <br/>, <em>)', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
								array( 'key' => 'f_ph_lead',    'label' => 'Lead paragraph', 'name' => 'lead', 'type' => 'textarea', 'rows' => 4, 'new_lines' => 'wpautop' ),
							),
						),

						/* -------------------------------------------------- */
						'layout_hero_image' => array(
							'key'     => 'layout_hero_image',
							'name'    => 'hero_image',
							'label'   => 'Image hero (full-bleed, home)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_hi_image',   'label' => 'Background image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
								array( 'key' => 'f_hi_tagline', 'label' => 'Tagline (small)', 'name' => 'tagline', 'type' => 'text' ),
								array( 'key' => 'f_hi_title',   'label' => 'Title (HTML allowed)', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
								array( 'key' => 'f_hi_lead',    'label' => 'Lead paragraph', 'name' => 'lead', 'type' => 'textarea', 'rows' => 4, 'new_lines' => 'wpautop' ),
								array(
									'key'   => 'f_hi_buttons',
									'label' => 'Buttons',
									'name'  => 'buttons',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add button',
									'sub_fields' => accr_cta_button_subfields( 'f_hi_button' ),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_trust_bar' => array(
							'key'     => 'layout_trust_bar',
							'name'    => 'trust_bar',
							'label'   => 'Trust bar (label + icon items)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_tb_label', 'label' => 'Label (HTML allowed)', 'name' => 'label', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
								array(
									'key'   => 'f_tb_items',
									'label' => 'Items',
									'name'  => 'items',
									'type'  => 'repeater',
									'layout' => 'table',
									'sub_fields' => array(
										array( 'key' => 'f_tb_item_icon', 'label' => 'Icon slug', 'name' => 'icon', 'type' => 'select', 'choices' => accr_icon_choices(), 'default_value' => 'shield' ),
										array( 'key' => 'f_tb_item_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
									),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_section_intro' => array(
							'key'     => 'layout_section_intro',
							'name'    => 'section_intro',
							'label'   => 'Section intro (eyebrow + title + lead)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_si_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_si_title',   'label' => 'Title (HTML allowed)', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
								array( 'key' => 'f_si_lead',    'label' => 'Lead paragraph', 'name' => 'lead', 'type' => 'textarea', 'rows' => 4, 'new_lines' => 'wpautop' ),
								array(
									'key'     => 'f_si_align',
									'label'   => 'Alignment',
									'name'    => 'align',
									'type'    => 'select',
									'choices' => array( 'left' => 'Left', 'center' => 'Center' ),
									'default_value' => 'left',
								),
								array(
									'key'     => 'f_si_bg',
									'label'   => 'Background',
									'name'    => 'background',
									'type'    => 'select',
									'choices' => array( 'default' => 'Default', 'surface_2' => 'Tinted (surface 2)' ),
									'default_value' => 'default',
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_split' => array(
							'key'     => 'layout_split',
							'name'    => 'split',
							'label'   => 'Text + image split (two-column)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_sp_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_sp_title',   'label' => 'Title (HTML allowed)', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
								array( 'key' => 'f_sp_body',    'label' => 'Body (rich text)', 'name' => 'body', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 0 ),
								array(
									'key'    => 'f_sp_bullets_heading', 'label' => 'Bullets heading (optional)', 'name' => 'bullets_heading', 'type' => 'text',
								),
								array(
									'key'   => 'f_sp_bullets',
									'label' => 'Bullets',
									'name'  => 'bullets',
									'type'  => 'repeater',
									'layout' => 'table',
									'sub_fields' => array(
										array( 'key' => 'f_sp_bullet_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text' ),
									),
								),
								array(
									'key'     => 'f_sp_bullet_style',
									'label'   => 'Bullet style',
									'name'    => 'bullet_style',
									'type'    => 'select',
									'choices' => array(
										'check'      => 'Checkmark icon',
										'arrow'      => 'Orange ▸ arrow',
										'arrow_2col' => 'Orange ▸ arrow (2 columns)',
									),
									'default_value' => 'check',
								),
								array(
									'key'   => 'f_sp_features',
									'label' => 'Mini-feature columns (optional, used by Navy band variant)',
									'name'  => 'features',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add feature',
									'sub_fields' => array(
										array( 'key' => 'f_sp_feature_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
										array( 'key' => 'f_sp_feature_body',  'label' => 'Body',  'name' => 'body',  'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
									),
								),
								array(
									'key'   => 'f_sp_buttons',
									'label' => 'Buttons',
									'name'  => 'buttons',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add button',
									'sub_fields' => accr_cta_button_subfields( 'f_sp_button' ),
								),
								array( 'key' => 'f_sp_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
								array(
									'key'     => 'f_sp_reverse',
									'label'   => 'Image on left (reverse)',
									'name'    => 'reverse',
									'type'    => 'true_false',
									'ui'      => 1,
								),
								array(
									'key'     => 'f_sp_bg',
									'label'   => 'Background',
									'name'    => 'background',
									'type'    => 'select',
									'choices' => array(
										'default'   => 'Default',
										'surface_2' => 'Tinted (surface 2)',
										'navy_band' => 'Navy band (image overflows top/bottom)',
									),
									'default_value' => 'default',
									'instructions' => 'Use "Navy band" for the Content w/Image (Alternate) layout — blue gradient band with the image extending above/below it. Pairs well with the mini-feature columns.',
								),
								array( 'key' => 'f_sp_anchor', 'label' => 'Anchor ID (optional)', 'name' => 'anchor', 'type' => 'text' ),
							),
						),

						/* -------------------------------------------------- */
						'layout_cards_grid' => array(
							'key'     => 'layout_cards_grid',
							'name'    => 'cards_grid',
								'label'   => 'Card grid (features / testimonials / steps)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_cg_eyebrow', 'label' => 'Eyebrow (optional)', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_cg_title',   'label' => 'Title (optional, HTML allowed)', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
								array( 'key' => 'f_cg_lead',    'label' => 'Lead (optional)', 'name' => 'lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ),
								array(
									'key'     => 'f_cg_variant',
									'label'   => 'Card variant',
									'name'    => 'variant',
									'type'    => 'select',
									'choices' => array(
											'feature'     => 'Feature card (icon + heading + body)',
										'feature_num' => 'Step card (number + heading + body)',
										'testimonial' => 'Testimonial (quote + author)',
									),
									'default_value' => 'feature',
								),
								array(
									'key'     => 'f_cg_cols',
									'label'   => 'Columns',
									'name'    => 'columns',
									'type'    => 'select',
									'choices' => array( '2' => '2', '3' => '3', '4' => '4' ),
									'default_value' => '3',
								),
								array(
									'key'     => 'f_cg_bg',
									'label'   => 'Background',
									'name'    => 'background',
									'type'    => 'select',
									'choices' => array( 'default' => 'Default', 'surface_2' => 'Tinted (surface 2)' ),
									'default_value' => 'default',
								),
								array(
									'key'   => 'f_cg_cards',
									'label' => 'Cards',
									'name'  => 'cards',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add card',
									'sub_fields' => array(
										array( 'key' => 'f_cg_card_image',   'label' => 'Image (cert card)', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
										array( 'key' => 'f_cg_card_badge',   'label' => 'Badge (cert card)', 'name' => 'badge', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_icon',    'label' => 'Icon (feature card)', 'name' => 'icon', 'type' => 'select', 'choices' => accr_icon_choices(), 'allow_null' => 1 ),
										array( 'key' => 'f_cg_card_number',  'label' => 'Step number (step card)', 'name' => 'number', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_title',   'label' => 'Heading', 'name' => 'title', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_body',    'label' => 'Body', 'name' => 'body', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ),
										array( 'key' => 'f_cg_card_link_label', 'label' => 'Link label', 'name' => 'link_label', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_link_url',   'label' => 'Link URL', 'name' => 'link_url', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_anchor',  'label' => 'Anchor ID (optional)', 'name' => 'anchor', 'type' => 'text' ),

										// Testimonial fields.
										array( 'key' => 'f_cg_card_quote',     'label' => 'Quote (testimonial)', 'name' => 'quote', 'type' => 'textarea', 'rows' => 3 ),
										array( 'key' => 'f_cg_card_avatar',    'label' => 'Avatar initials (testimonial)', 'name' => 'avatar', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_author',    'label' => 'Author name (testimonial)', 'name' => 'author', 'type' => 'text' ),
										array( 'key' => 'f_cg_card_role',      'label' => 'Author role (testimonial)', 'name' => 'role', 'type' => 'text' ),
									),
								),
								array(
									'key'   => 'f_cg_footer_button',
									'label' => 'Footer button (optional, centered below grid)',
									'name'  => 'footer_button',
									'type'  => 'group',
									'sub_fields' => array(
										array( 'key' => 'f_cg_fb_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
										array( 'key' => 'f_cg_fb_url',   'label' => 'URL', 'name' => 'url', 'type' => 'text' ),
									),
								),
							),
							),

							/* -------------------------------------------------- */
							'layout_certifications_grid' => array(
								'key'     => 'layout_certifications_grid',
								'name'    => 'certifications_grid',
								'label'   => 'Certifications grid (queries Certification CPT)',
								'display' => 'block',
								'sub_fields' => array(
									array( 'key' => 'f_cq_eyebrow', 'label' => 'Eyebrow (optional)', 'name' => 'eyebrow', 'type' => 'text' ),
									array( 'key' => 'f_cq_title',   'label' => 'Title (optional, HTML allowed)', 'name' => 'title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ),
									array( 'key' => 'f_cq_lead',    'label' => 'Lead (optional)', 'name' => 'lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ),
									array(
										'key'     => 'f_cq_source',
										'label'   => 'Which certifications to show',
										'name'    => 'source',
										'type'    => 'select',
										'choices' => array(
											'featured' => 'Featured certifications',
											'all'      => 'All certifications',
											'selected' => 'Manually selected certifications',
										),
										'default_value' => 'featured',
										'return_format' => 'value',
									),
									array(
										'key'           => 'f_cq_selected',
										'label'         => 'Selected certifications',
										'name'          => 'selected_certifications',
										'type'          => 'relationship',
										'post_type'     => array( 'certification' ),
										'filters'       => array( 'search' ),
										'return_format' => 'id',
										'conditional_logic' => array(
											array(
												array( 'field' => 'f_cq_source', 'operator' => '==', 'value' => 'selected' ),
											),
										),
									),
									array(
										'key'     => 'f_cq_limit',
										'label'   => 'Post limit',
										'name'    => 'limit',
										'type'    => 'number',
										'default_value' => 3,
										'min'     => 0,
										'instructions' => 'Use 0 to show all matching certifications.',
									),
									array(
										'key'     => 'f_cq_cols',
										'label'   => 'Columns',
										'name'    => 'columns',
										'type'    => 'select',
										'choices' => array( '2' => '2', '3' => '3', '4' => '4' ),
										'default_value' => '3',
									),
									array(
										'key'     => 'f_cq_bg',
										'label'   => 'Background',
										'name'    => 'background',
										'type'    => 'select',
										'choices' => array( 'default' => 'Default', 'surface_2' => 'Tinted (surface 2)' ),
										'default_value' => 'default',
									),
									array(
										'key'   => 'f_cq_link_label',
										'label' => 'Card link label',
										'name'  => 'link_label',
										'type'  => 'text',
										'default_value' => 'Get details',
									),
									array(
										'key'   => 'f_cq_footer_button',
										'label' => 'Footer button (optional, centered below grid)',
										'name'  => 'footer_button',
										'type'  => 'group',
										'sub_fields' => array(
											array( 'key' => 'f_cq_fb_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
											array( 'key' => 'f_cq_fb_url',   'label' => 'URL', 'name' => 'url', 'type' => 'text' ),
										),
									),
								),
							),

							/* -------------------------------------------------- */
							'layout_stats_band' => array(
								'key'     => 'layout_stats_band',
								'name'    => 'stats_band',
							'label'   => 'Stats band (numeric strip)',
							'display' => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'f_st_items',
									'label' => 'Stats',
									'name'  => 'items',
									'type'  => 'repeater',
									'layout' => 'table',
									'sub_fields' => array(
										array( 'key' => 'f_st_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text' ),
										array( 'key' => 'f_st_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
									),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_logos_band' => array(
							'key'     => 'layout_logos_band',
							'name'    => 'logos_band',
							'label'   => 'Logos / clients band',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_lb_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_lb_title',   'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
								array(
									'key'   => 'f_lb_items',
									'label' => 'Logo tiles',
									'name'  => 'items',
									'type'  => 'repeater',
									'layout' => 'table',
									'sub_fields' => array(
										array( 'key' => 'f_lb_label', 'label' => 'Label (HTML <br/> allowed)', 'name' => 'label', 'type' => 'text' ),
										array( 'key' => 'f_lb_image', 'label' => 'Image (optional)', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
									),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_cta_banner' => array(
							'key'     => 'layout_cta_banner',
							'name'    => 'cta_banner',
							'label'   => 'CTA banner (dark navy)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_ct_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
								array( 'key' => 'f_ct_text',  'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ),
								array(
									'key'   => 'f_ct_buttons',
									'label' => 'Buttons',
									'name'  => 'buttons',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add button',
									'sub_fields' => accr_cta_button_subfields( 'f_ct_button' ),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_anchor_nav' => array(
							'key'     => 'layout_anchor_nav',
							'name'    => 'anchor_nav',
							'label'   => 'Sticky anchor nav (in-page links)',
							'display' => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'f_an_items',
									'label' => 'Anchor links',
									'name'  => 'items',
									'type'  => 'repeater',
									'layout' => 'table',
									'sub_fields' => array(
										array( 'key' => 'f_an_label',  'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
										array( 'key' => 'f_an_anchor', 'label' => 'Anchor (without #)', 'name' => 'anchor', 'type' => 'text' ),
									),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_notice_bar' => array(
							'key'     => 'layout_notice_bar',
							'name'    => 'notice_bar',
							'label'   => 'Notice / info bar',
							'display' => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'f_nb_tone',
									'label' => 'Tone',
									'name'  => 'tone',
									'type'  => 'select',
									'choices' => array( 'warning' => 'Warning (yellow)', 'info' => 'Info', 'success' => 'Success' ),
									'default_value' => 'warning',
								),
								array( 'key' => 'f_nb_text', 'label' => 'Text (HTML allowed; wrap headline in <strong>)', 'name' => 'text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ),
							),
						),

						/* -------------------------------------------------- */
						'layout_classes_table' => array(
							'key'     => 'layout_classes_table',
							'name'    => 'classes_table',
							'label'   => 'Classes schedule (auto from CPT)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_clt_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_clt_title',   'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
								array(
									'key'   => 'f_clt_show_filters',
									'label' => 'Show category filter buttons',
									'name'  => 'show_filters',
									'type'  => 'true_false',
									'ui'    => 1,
									'default_value' => 1,
								),
								array(
									'key'   => 'f_clt_limit',
									'label' => 'Maximum classes to show',
									'name'  => 'limit',
									'type'  => 'number',
									'default_value' => 50,
								),
								array(
									'key'   => 'f_clt_only_future',
									'label' => 'Only show classes on or after today',
									'name'  => 'only_future',
									'type'  => 'true_false',
									'ui'    => 1,
									'default_value' => 1,
								),
								array( 'key' => 'f_clt_footnote', 'label' => 'Footnote (HTML allowed)', 'name' => 'footnote', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ),
							),
						),

						/* -------------------------------------------------- */
						'layout_openings_list' => array(
							'key'     => 'layout_openings_list',
							'name'    => 'openings_list',
							'label'   => 'Career openings list',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_op_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_op_title',   'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
								array(
									'key'   => 'f_op_items',
									'label' => 'Openings',
									'name'  => 'items',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add opening',
									'sub_fields' => array(
										array( 'key' => 'f_op_type',     'label' => 'Type tag (e.g. Part-time)', 'name' => 'type', 'type' => 'text' ),
										array( 'key' => 'f_op_location', 'label' => 'Location tag', 'name' => 'location', 'type' => 'text' ),
										array( 'key' => 'f_op_title2',   'label' => 'Role title', 'name' => 'title', 'type' => 'text' ),
										array( 'key' => 'f_op_body',     'label' => 'Description', 'name' => 'body', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ),
										array( 'key' => 'f_op_apply_url', 'label' => 'Apply URL (mailto: ok)', 'name' => 'apply_url', 'type' => 'text' ),
										array( 'key' => 'f_op_apply_label', 'label' => 'Apply button label', 'name' => 'apply_label', 'type' => 'text', 'default_value' => 'Apply' ),
									),
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_contact_split' => array(
							'key'     => 'layout_contact_split',
							'name'    => 'contact_split',
							'label'   => 'Contact info + form split',
							'display' => 'block',
							'sub_fields' => array(
								array(
									'key'   => 'f_cs_blocks',
									'label' => 'Info blocks (left column)',
									'name'  => 'blocks',
									'type'  => 'repeater',
									'layout' => 'block',
									'button_label' => 'Add info block',
									'sub_fields' => array(
										array( 'key' => 'f_cs_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
										array( 'key' => 'f_cs_title',   'label' => 'Heading', 'name' => 'title', 'type' => 'text' ),
										array( 'key' => 'f_cs_body',    'label' => 'Body (HTML allowed)', 'name' => 'body', 'type' => 'textarea', 'rows' => 4, 'new_lines' => 'wpautop' ),
										array(
											'key'     => 'f_cs_emphasis',
											'label'   => 'Emphasis style',
											'name'    => 'emphasis',
											'type'    => 'select',
											'choices' => array(
												'none'   => 'No emphasis line',
												'phone'  => 'Large orange phone link',
												'email'  => 'Email link',
												'button' => 'Outline button',
											),
											'default_value' => 'none',
										),
										array( 'key' => 'f_cs_emp_label', 'label' => 'Emphasis label/text', 'name' => 'emphasis_label', 'type' => 'text' ),
										array( 'key' => 'f_cs_emp_url',   'label' => 'Emphasis URL', 'name' => 'emphasis_url', 'type' => 'text' ),
									),
								),
								array( 'key' => 'f_cs_form_title', 'label' => 'Form title', 'name' => 'form_title', 'type' => 'text', 'default_value' => 'Send us a message' ),
								array( 'key' => 'f_cs_form_lead',  'label' => 'Form lead text', 'name' => 'form_lead', 'type' => 'textarea', 'rows' => 2, 'new_lines' => 'wpautop' ),
								array(
									'key'     => 'f_cs_gf_id',
									'label'   => 'Gravity Form ID',
									'name'    => 'gravity_form_id',
									'type'    => 'number',
									'instructions' => 'Optional. If provided and Gravity Forms is active, the form renders here; otherwise a styled fallback is shown.',
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_rich_content' => array(
							'key'     => 'layout_rich_content',
							'name'    => 'rich_content',
							'label'   => 'Rich content (free WYSIWYG)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_rc_content', 'label' => 'Content', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ),
								array(
									'key'     => 'f_rc_bg',
									'label'   => 'Background',
									'name'    => 'background',
									'type'    => 'select',
									'choices' => array( 'default' => 'Default', 'surface_2' => 'Tinted (surface 2)' ),
									'default_value' => 'default',
								),
							),
						),

						/* -------------------------------------------------- */
						'layout_gravity_form' => array(
							'key'     => 'layout_gravity_form',
							'name'    => 'gravity_form',
							'label'   => 'Gravity Form (standalone)',
							'display' => 'block',
							'sub_fields' => array(
								array( 'key' => 'f_gf_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'f_gf_title',   'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
								array( 'key' => 'f_gf_lead',    'label' => 'Lead', 'name' => 'lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ),
								array(
									'key'   => 'f_gf_id',
									'label' => 'Gravity Form ID',
									'name'  => 'form_id',
									'type'  => 'number',
									'required' => 1,
								),
							),
						),

					),
				),
			),
			'location' => array(
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
				),
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'certification' ),
				),
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'class' ),
				),
			),
			'menu_order'   => 0,
			'position'     => 'normal',
			'style'        => 'default',
			'label_placement'    => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => array( 'the_content' ),
			'active'       => true,
			'description'  => 'Shared flexible-content layouts used by every page and CPT in the theme.',
		)
	);

	/* ======================================================================
	 * "Class Details" — date / time / pricing for the Class CPT.
	 * ====================================================================== */
	acf_add_local_field_group(
		array(
			'key'    => 'group_class_details',
			'title'  => 'Class details',
			'fields' => array(
				array(
					'key'     => 'field_class_subtitle',
					'label'   => 'Subtitle (shown beneath class name)',
					'name'    => 'subtitle',
					'type'    => 'text',
				),
				array(
					'key'     => 'field_class_date',
					'label'   => 'Class date',
					'name'    => 'class_date',
					'type'    => 'date_picker',
					'display_format' => 'F j, Y',
					'return_format'  => 'Y-m-d',
					'first_day'      => 0,
				),
				array(
					'key'     => 'field_class_date_display',
					'label'   => 'Date display override',
					'name'    => 'class_date_display',
					'type'    => 'text',
					'instructions' => 'Optional. If set, used verbatim in the schedule table instead of the formatted Class date (e.g. "Apr 28, 2026").',
				),
				array(
					'key'   => 'field_class_time',
					'label' => 'Time (display string)',
					'name'  => 'class_time',
					'type'  => 'text',
					'instructions' => 'Free-form time, e.g. "7:30 AM – 6:30 PM".',
				),
				array(
					'key'   => 'field_class_price',
					'label' => 'Tuition price (numeric)',
					'name'  => 'price',
					'type'  => 'number',
					'instructions' => 'Optional. If empty, "Request pricing" is shown.',
				),
				array(
					'key'   => 'field_class_tuition_display',
					'label' => 'Tuition display override',
					'name'  => 'tuition_display',
					'type'  => 'text',
					'instructions' => 'Optional. Overrides the formatted price (e.g. "Request pricing", "$795 / seat").',
				),
				array(
					'key'   => 'field_class_request_class_label',
					'label' => 'Request-pricing modal class label',
					'name'  => 'request_class_label',
					'type'  => 'text',
					'instructions' => 'Optional. Falls back to the post title. Used as data-class on the table button.',
				),
				array(
					'key'   => 'field_class_show_in_schedule',
					'label' => 'Show in schedule table',
					'name'  => 'show_in_schedule',
					'type'  => 'true_false',
					'ui'    => 1,
					'default_value' => 1,
				),
			),
			'location' => array(
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'class' ),
				),
			),
			'menu_order'   => -10,
			'position'     => 'side',
			'style'        => 'default',
			'label_placement' => 'top',
			'active'       => true,
		)
	);

	/* ======================================================================
	 * "Certification details" — slug used on classes table column linking.
	 * ====================================================================== */
	acf_add_local_field_group(
		array(
			'key'    => 'group_certification_details',
			'title'  => 'Certification details',
			'fields' => array(
				array(
					'key'     => 'field_cert_short_name',
					'label'   => 'Short name (for cards)',
					'name'    => 'short_name',
					'type'    => 'text',
				),
				array(
					'key'     => 'field_cert_card_image',
					'label'   => 'Card image',
					'name'    => 'card_image',
					'type'    => 'image',
					'return_format' => 'array',
				),
				array(
					'key'   => 'field_cert_badge',
					'label' => 'Card badge (e.g. NCCCO, NEW)',
					'name'  => 'badge',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_cert_featured',
					'label' => 'Featured certification',
					'name'  => 'featured_certification',
					'type'  => 'true_false',
					'ui'    => 1,
					'instructions' => 'Featured certifications can be queried by the Certifications grid section, for example the limited grid on the home page.',
				),
			),
			'location' => array(
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'certification' ),
				),
			),
			'menu_order'   => 0,
			'position'     => 'side',
			'style'        => 'default',
			'active'       => true,
		)
	);
}
