<?php
/**
 * Theme helper functions — icons, button rendering, etc.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline SVG icon library. Single source of truth for icon markup.
 *
 * @param string $slug   Icon slug from accr_icon_choices().
 * @param array  $attrs  Optional. Extra HTML attributes for the <svg> wrapper.
 */
function accr_icon( $slug, $attrs = array() ) {
	$defaults = array(
		'width'           => '24',
		'height'          => '24',
		'viewBox'         => '0 0 24 24',
		'fill'            => 'none',
		'stroke'          => 'currentColor',
		'stroke-width'    => '2',
		'stroke-linecap'  => 'round',
		'stroke-linejoin' => 'round',
		'aria-hidden'     => 'true',
	);
	$attrs = array_merge( $defaults, $attrs );

	$attr_str = '';
	foreach ( $attrs as $k => $v ) {
		$attr_str .= ' ' . $k . '="' . esc_attr( $v ) . '"';
	}

	$paths = array(
		'shield'        => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
		'shield_check'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'check'         => '<path d="M20 6 9 17l-5-5"/>',
		'award'         => '<circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/>',
		'building'      => '<path d="M3 21h18M5 21V7l8-4 8 4v14M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/>',
		'clock'         => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'plus'          => '<path d="M12 2v20M2 12h20"/>',
		'pencil'        => '<path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>',
		'users'         => '<circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2M16 3.13a4 4 0 0 1 0 7.75M23 21v-2a4 4 0 0 0-3-3.87"/>',
		'tower'         => '<path d="M12 2v20M4 6h16M6 2v4M18 2v4M10 22v-6M14 22v-6"/>',
		'building_alt'  => '<path d="M3 21h18M5 21V10l7-5 7 5v11M10 14h4M10 18h4"/>',
		'building_alt2' => '<path d="m 15,17 h 2 V 9 h -6 v 8 h 2 v -6 h 2 z M 1,17 V 2 C 1,1.734784 1.10536,1.48043 1.29289,1.292893 1.48043,1.105357 1.73478,1 2,1 h 14 c 0.2652,0 0.5196,0.105357 0.7071,0.292893 C 16.8946,1.48043 17,1.734784 17,2 v 5 h 2 v 10 h 1 v 2 H 0 V 17 Z M 5,9 v 2 H 7 V 9 Z m 0,4 v 2 H 7 V 13 Z M 5,5 V 7 H 7 V 5 Z" fill="currentColor"/>',
		'layers'        => '<path d="M12 2 4 8l8 6 8-6-8-6zM4 14l8 6 8-6M4 20l8 6 8-6"/>',
		'sun'           => '<circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/>',
		'forklift'      => '<circle cx="6" cy="18" r="3"/><circle cx="18" cy="18" r="3"/><path d="M3 15V8h8l4 4h6v6"/>',
		'classroom'     => '<path d="M3 21h18M12 3v18M5 8l7-5 7 5M9 13h6M9 17h6"/>',
		'calendar'      => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>',
		'cube'          => '<path d="M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>',
		'arrow_right'   => '<path d="M5 12h14M13 5l7 7-7 7"/>',
		'alert'         => '<path d="M3.66346 20.6315C2.51734 19.5245 1.60315 18.2004 0.974242 16.7363C0.345334 15.2723 0.0142989 13.6976 0.000453081 12.1043C-0.0133927 10.5109 0.290228 8.93077 0.893598 7.45601C1.49697 5.98125 2.38801 4.64143 3.51472 3.51472C4.64143 2.38801 5.98125 1.49697 7.45601 0.893598C8.93077 0.290228 10.5109 -0.0133927 12.1043 0.000453081C13.6976 0.0142989 15.2723 0.345334 16.7363 0.974242C18.2004 1.60315 19.5245 2.51734 20.6315 3.66346C22.8174 5.92668 24.0269 8.95791 23.9995 12.1043C23.9722 15.2506 22.7102 18.2604 20.4853 20.4853C18.2604 22.7102 15.2506 23.9722 12.1043 23.9995C8.95791 24.0269 5.92668 22.8174 3.66346 20.6315ZM10.9475 6.14746V13.3475H13.3475V6.14746H10.9475ZM10.9475 15.7475V18.1475H13.3475V15.7475H10.9475Z" />',
		'mail'          => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
		'phone'         => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'check_circle'  => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
		'close'         => '<path d="M18 6 6 18M6 6l12 12"/>',
		'document'      => '<path d="M13.5333 5.83635L9.94586 9.42379C9.70165 9.66632 9.58038 9.78843 9.47596 9.92233C9.35245 10.0806 9.24746 10.2508 9.16101 10.4327C9.08774 10.5859 9.03385 10.7485 8.92521 11.0744L8.57742 12.1186L8.46457 12.4563L8.35257 12.7948C8.32648 12.8734 8.32276 12.9577 8.34184 13.0382C8.36091 13.1188 8.40201 13.1925 8.46056 13.251C8.51911 13.3096 8.59278 13.3507 8.67335 13.3698C8.75392 13.3888 8.83821 13.3851 8.91679 13.359L9.25448 13.2462L9.59217 13.1333L10.6364 12.7855C10.9623 12.6769 11.1248 12.623 11.2781 12.5498C11.46 12.4633 11.6301 12.3583 11.7884 12.2348C11.9223 12.1304 12.0436 12.0091 12.2878 11.7649L15.8752 8.17745L16.2652 7.78671C16.5756 7.47614 16.75 7.05498 16.7499 6.61586C16.7498 6.17674 16.5753 5.75563 16.2647 5.44518C15.9542 5.13473 15.533 4.96037 15.0939 4.96045C14.6548 4.96053 14.2337 5.13504 13.9232 5.44561L13.5333 5.83635ZM13.5333 5.83635C13.5333 5.83635 13.5821 6.665 14.314 7.3968C15.0458 8.12861 15.8752 8.17745 15.8752 8.17745M8.46457 12.4563L9.25448 13.2462" /><path d="M4.96061 10.0133H7.06592M4.96061 6.64486H10.4344M4.96061 13.3818H6.2238M14.9212 1.73697C13.9351 0.75 12.3469 0.75 9.17122 0.75H7.48698C4.31134 0.75 2.72309 0.75 1.73697 1.73697C0.750842 2.72394 0.75 4.31134 0.75 7.48698V10.8555C0.75 14.0311 0.75 15.6194 1.73697 16.6055C2.72394 17.5916 4.31134 17.5925 7.48698 17.5925H9.17122C12.3469 17.5925 13.9351 17.5925 14.9212 16.6055C15.7162 15.8114 15.8703 14.6282 15.9006 12.5397" />',
		'document_alt'  => '<path fill-rule="evenodd" clip-rule="evenodd" d="M 2.172,1.172 C 0.99999988,2.343 1,4.229 1,8 v 4 c 0,3.771 -1.2e-7,5.657 1.172,6.828 C 3.344,19.999 5.229,20 9,20 h 2 c 3.771,0 5.657,0 6.828,-1.172 C 18.999,17.656 19,15.771 19,12 V 8 C 19,4.229 19,2.343 17.828,1.172 16.656,9.99928e-4 14.771,0 11,0 H 9 C 5.229,0 3.343,-1.19209e-7 2.172,1.172 Z M 6,7.25 C 5.80109,7.25 5.61032,7.32902 5.46967,7.46967 5.32902,7.61032 5.25,7.80109 5.25,8 5.25,8.19891 5.32902,8.38968 5.46967,8.53033 5.61032,8.67098 5.80109,8.75 6,8.75 h 8 c 0.1989,0 0.3897,-0.07902 0.5303,-0.21967 C 14.671,8.38968 14.75,8.19891 14.75,8 14.75,7.80109 14.671,7.61032 14.5303,7.46967 14.3897,7.32902 14.1989,7.25 14,7.25 Z m 0,4 c -0.19891,0 -0.38968,0.079 -0.53033,0.2197 C 5.32902,11.6103 5.25,11.8011 5.25,12 c 0,0.1989 0.07902,0.3897 0.21967,0.5303 C 5.61032,12.671 5.80109,12.75 6,12.75 h 5 c 0.1989,0 0.3897,-0.079 0.5303,-0.2197 C 11.671,12.3897 11.75,12.1989 11.75,12 11.75,11.8011 11.671,11.6103 11.5303,11.4697 11.3897,11.329 11.1989,11.25 11,11.25 Z" fill="currentColor"/>',
		'crane'         => '<path d="M18 3V2C18 1.73478 17.8946 1.48043 17.7071 1.29289C17.5196 1.10536 17.2652 1 17 1H7V0H4V1H3V3H4V12H3V10H1V12H0V14H1V18H3V14H8V18H10V14H11V12H10V10H8V12H7V3H15V7.62C14.53 7.79 14.19 8.23 14.19 8.76C14.19 9.2 14.43 9.6 14.8 9.82V11H15.42C15.76 11 16.03 11.28 16.03 11.62C16.03 11.96 15.76 12.24 15.42 12.24C15.2 12.24 15 12.12 14.89 11.93C14.8067 11.7906 14.672 11.6895 14.5149 11.6484C14.3579 11.6073 14.1909 11.6294 14.05 11.71C13.75 11.87 13.65 12.25 13.82 12.55C14.15 13.11 14.76 13.47 15.42 13.47C16.43 13.47 17.26 12.64 17.26 11.62C17.26 10.84 16.76 10.14 16.03 9.88V9.82C16.41 9.6 16.65 9.2 16.65 8.76C16.65 8.3 16.38 7.91 16 7.7V3H18ZM6 10.66L5 11.66V10.24L6 9.24V10.66ZM6 7.71L5 8.71V7.29L6 6.29V7.71ZM5 5.71V4.29L6 3.29V4.71L5 5.71Z" stroke="none" fill="currentColor" />',
		'star'          => '<path d="M 3.825,19.5 5.45,12.475 0,7.75 7.2,7.125 10,0.5 12.8,7.125 20,7.75 14.55,12.475 16.175,19.5 10,15.775 Z" fill="currentColor" />',
		'hard_hat'      => '<path d="M 12,10.25 V 4 C 12,3.446875 11.5531,3 11,3 H 9 C 8.44688,3 8,3.446875 8,4 v 6.25 C 8,10.66563 7.66563,11 7.25,11 6.83437,11 6.5,10.66563 6.5,10.25 V 4.44063 C 3.8125,5.43125 2,7.99375 2,11 v 2 H 18 V 11 C 17.9688,8.025 16.175,5.44687 13.5,4.44375 V 10.25 C 13.5,10.66563 13.1656,11 12.75,11 12.3344,11 12,10.66563 12,10.25 Z M 2.25,14.5 C 1.559375,14.5 1,15.0594 1,15.75 1,16.4406 1.559375,17 2.25,17 h 15.5 C 18.4406,17 19,16.4406 19,15.75 19,15.0594 18.4406,14.5 17.75,14.5 Z" fill="currentColor"/>',
		'facebook'      => '<path fill-rule="evenodd" clip-rule="evenodd" d="M13.0388 19V11.6423H15.5088L15.8783 8.77515H13.0378V6.9445C13.0378 6.1142 13.2687 5.548 14.46 5.548H15.9781V2.983C15.2429 2.90401 14.5039 2.86595 13.7646 2.869C11.5758 2.869 10.0776 4.20565 10.0776 6.6595V8.77515H7.6V11.6423H10.0767V19H1.0488C0.4693 19 0 18.5307 0 17.9512V1.0488C0 0.4693 0.4693 0 1.0488 0H17.9512C18.5307 0 19 0.4693 19 1.0488V17.9512C19 18.5307 18.5307 19 17.9512 19H13.0388Z" fill="currentColor"/>',
		'instagram'     => '<path fill-rule="evenodd" clip-rule="evenodd" d="M4.28607 0C3.14962 -8.37989e-08 2.05969 0.45134 1.25594 1.25478C0.452195 2.05822 0.000436425 3.14797 0 4.28442V15.7139C0 16.8507 0.451566 17.9408 1.25536 18.7446C2.05915 19.5484 3.14933 20 4.28607 20H15.7156C16.852 19.9996 17.9418 19.5478 18.7452 18.7441C19.5487 17.9403 20 16.8504 20 15.7139V4.28442C19.9996 3.14826 19.548 2.05875 18.7446 1.25536C17.9412 0.451969 16.8517 0.000436232 15.7156 0H4.28607ZM16.9484 4.29101C16.9484 4.61841 16.8183 4.9324 16.5868 5.1639C16.3553 5.39541 16.0413 5.52547 15.7139 5.52547C15.3865 5.52547 15.0725 5.39541 14.841 5.1639C14.6095 4.9324 14.4795 4.61841 14.4795 4.29101C14.4795 3.9636 14.6095 3.64961 14.841 3.41811C15.0725 3.1866 15.3865 3.05654 15.7139 3.05654C16.0413 3.05654 16.3553 3.1866 16.5868 3.41811C16.8183 3.64961 16.9484 3.9636 16.9484 4.29101ZM10.0025 6.57559C9.09448 6.57559 8.22368 6.93629 7.58163 7.57834C6.93958 8.22038 6.57888 9.09119 6.57888 9.99918C6.57888 10.9072 6.93958 11.778 7.58163 12.42C8.22368 13.0621 9.09448 13.4228 10.0025 13.4228C10.9105 13.4228 11.7813 13.0621 12.4233 12.42C13.0654 11.778 13.4261 10.9072 13.4261 9.99918C13.4261 9.09119 13.0654 8.22038 12.4233 7.57834C11.7813 6.93629 10.9105 6.57559 10.0025 6.57559ZM4.93128 9.99918C4.93128 8.65465 5.46539 7.36519 6.41612 6.41447C7.36684 5.46375 8.6563 4.92964 10.0008 4.92964C11.3453 4.92964 12.6348 5.46375 13.5855 6.41447C14.5363 7.36519 15.0704 8.65465 15.0704 9.99918C15.0704 11.3437 14.5363 12.6332 13.5855 13.5839C12.6348 14.5346 11.3453 15.0687 10.0008 15.0687C8.6563 15.0687 7.36684 14.5346 6.41612 13.5839C5.46539 12.6332 4.93128 11.3437 4.93128 9.99918Z" fill="currentColor"/>',
		'linkedin'      => '<path d="M16.8889 0C17.4488 0 17.9858 0.22242 18.3817 0.61833C18.7776 1.01424 19 1.55121 19 2.11111V16.8889C19 17.4488 18.7776 17.9858 18.3817 18.3817C17.9858 18.7776 17.4488 19 16.8889 19H2.11111C1.55121 19 1.01424 18.7776 0.61833 18.3817C0.22242 17.9858 0 17.4488 0 16.8889V2.11111C0 1.55121 0.22242 1.01424 0.61833 0.61833C1.01424 0.22242 1.55121 0 2.11111 0H16.8889ZM16.3611 16.3611V10.7667C16.3611 9.85403 15.9986 8.97877 15.3532 8.33343C14.7079 7.6881 13.8326 7.32556 12.92 7.32556C12.0228 7.32556 10.9778 7.87444 10.4711 8.69778V7.52611H7.52611V16.3611H10.4711V11.1572C10.4711 10.3444 11.1256 9.67944 11.9383 9.67944C12.3303 9.67944 12.7061 9.83514 12.9833 10.1123C13.2604 10.3894 13.4161 10.7653 13.4161 11.1572V16.3611H16.3611ZM4.09556 5.86889C4.56587 5.86889 5.01693 5.68206 5.34949 5.34949C5.68206 5.01693 5.86889 4.56587 5.86889 4.09556C5.86889 3.11389 5.07722 2.31167 4.09556 2.31167C3.62244 2.31167 3.1687 2.49961 2.83416 2.83416C2.49961 3.1687 2.31167 3.62244 2.31167 4.09556C2.31167 5.07722 3.11389 5.86889 4.09556 5.86889ZM5.56278 16.3611V7.52611H2.63889V16.3611H5.56278Z" fill="currentColor"/>',
	);

	$inner = isset( $paths[ $slug ] ) ? $paths[ $slug ] : '';

	return '<svg' . $attr_str . '>' . $inner . '</svg>';
}

/**
 * Choices list for ACF icon selects.
 */
function accr_icon_choices() {
	return array( 
		'shield'        => 'Shield',
		'shield_check'  => 'Shield with check',
		'check'         => 'Check',
		'check_circle'  => 'Check (in circle)',
		'award'         => 'Award',
		'building_alt'  => 'Building (Alt)',
		'building_alt2' => 'Building (Alt) (2)',
		'clock'         => 'Clock',
		'plus'          => 'Plus',
		'pencil'        => 'Pencil',
		'users'         => 'Users',
		'tower'         => 'Tower / crane',
		'layers'        => 'Layers / rigging',
		'sun'           => 'Sun / signal',
		'forklift'      => 'Forklift / telehandler',
		'classroom'     => 'Classroom / on-site',
		'calendar'      => 'Calendar',
		'cube'          => 'Cube / impact',
		'arrow_right'   => 'Arrow right',
		'alert'         => 'Alert / info',
		'mail'          => 'Mail',
		'phone'         => 'Phone',
		'document'      => 'Document',
		'document_alt'  => 'Document (Alt)',
		'crane'         => 'Crane',
		'star'          => 'Star',
		'hard_hat'      => 'Hard Hat',
		'facebook'      => 'Facebook',
		'instagram'     => 'Instagram',
		'linkedin'      => 'LinkedIn',
	);
}

/**
 * Keep the class detail-column icon select in sync with accr_icon_choices()
 * so the icon library remains the single source of truth.
 */
add_filter( 'acf/load_field/key=field_class_detail_icon', 'accr_load_icon_choices' );
function accr_load_icon_choices( $field ) {
	$field['choices'] = accr_icon_choices();
	return $field;
}

/**
 * Render a button HTML element from an ACF row produced by the shared CTA repeater.
 *
 * @param array  $btn   Repeater row.
 * @param string $extra Extra class names appended to the button.
 * @return string HTML markup.
 */
function accr_render_button( $btn, $extra = '' ) {
	if ( empty( $btn['label'] ) ) {
		return '';
	}
	$label = $btn['label'];
	$url   = isset( $btn['url'] ) ? $btn['url'] : '#';
	$style = isset( $btn['style'] ) && $btn['style'] ? $btn['style'] : 'btn--primary';
	$size  = isset( $btn['size'] ) ? $btn['size'] : '';
	$modal = ! empty( $btn['open_modal'] );

	$classes = trim( 'btn ' . $style . ' ' . $size . ' ' . $extra );

	if ( $modal ) {
		return sprintf(
			'<button class="%1$s" data-request-pricing data-class="%2$s" data-operators="5-9 (group pricing)">%3$s</button>',
			esc_attr( $classes ),
			esc_attr( $label ),
			esc_html( $label )
		);
	}

	return sprintf(
		'<a class="%1$s" href="%2$s">%3$s</a>',
		esc_attr( $classes ),
		esc_url( $url ),
		esc_html( $label )
	);
}

/**
 * Render a list of buttons from a repeater rows array.
 */
function accr_render_buttons( $buttons, $wrapper_style = 'display:flex; gap: var(--space-3); flex-wrap: wrap;' ) {
	if ( empty( $buttons ) || ! is_array( $buttons ) ) {
		return '';
	}
	$out = '<div style="' . esc_attr( $wrapper_style ) . '">';
	foreach ( $buttons as $b ) {
		$out .= accr_render_button( $b );
	}
	$out .= '</div>';
	return $out;
}

/**
 * Format a class CPT date for display.
 *
 * @param int $post_id
 * @return string
 */
function accr_format_class_date( $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$override = get_field( 'class_date_display', $post_id );
	if ( $override ) {
		return $override;
	}
	$raw = get_field( 'class_date', $post_id );
	if ( ! $raw ) {
		return '';
	}
	$ts = strtotime( $raw );
	if ( ! $ts ) {
		return $raw;
	}
	return date_i18n( 'M j, Y', $ts );
}

/**
 * Format a class CPT date range for display.
 *
 * Prefers the verbatim display override (class_date_display). Otherwise derives
 * a range from the start (class_date) and optional end (class_end_date) dates,
 * collapsing to a single date when no end date is set or the two match.
 *
 * @param int $post_id
 * @return string
 */
function accr_format_class_date_range( $post_id = null, $args = array() ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}
	$args = wp_parse_args( $args, array(
		'show_weekday' => false,
	) );
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$override = get_field( 'class_date_display', $post_id );
	if ( $override ) {
		return $override;
	}
	$start_raw = get_field( 'class_date', $post_id );
	$end_raw   = get_field( 'class_end_date', $post_id );
	if ( ! $start_raw ) {
		return '';
	}
	$start_ts = strtotime( $start_raw );
	if ( ! $start_ts ) {
		return $start_raw;
	}
	$end_ts = $end_raw ? strtotime( $end_raw ) : 0;
	if ( ! $end_ts || $end_ts <= $start_ts ) {
		return date_i18n( 'M j, Y', $start_ts );
	}
	
	if ( $args['show_weekday'] ) {
		// Same month + year: "Tuesday, April 28 – Thursday, April 30, 2026"; otherwise full both ends.
		if ( date( 'Y', $start_ts ) === date( 'Y', $end_ts ) ) {
			return date_i18n( 'l, M j', $start_ts ) . ' &ndash; ' . date_i18n( 'l, M j, Y', $end_ts );
		}
		return date_i18n( 'l, M j, Y', $start_ts ) . ' &ndash; ' . date_i18n( 'l, M j, Y', $end_ts );
	}
	
	// Same month + year: "April 28 – 30, 2026"; otherwise full both ends.
	if ( date( 'Y', $start_ts ) === date( 'Y', $end_ts ) ) {
		if ( date( 'n', $start_ts ) === date( 'n', $end_ts ) ) {
			return date_i18n( 'M j', $start_ts ) . ' &ndash; ' . date_i18n( 'j, Y', $end_ts );
		}
		return date_i18n( 'M j', $start_ts ) . ' &ndash; ' . date_i18n( 'M j, Y', $end_ts );
	}
	return date_i18n( 'M j, Y', $start_ts ) . ' &ndash; ' . date_i18n( 'M j, Y', $end_ts );
}

/**
 * Format a class CPT tuition for display.
 */
function accr_format_class_tuition( $post_id = null) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
	}
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$override = get_field( 'tuition_display', $post_id );
	if ( $override ) {
		return $override;
	}
	$price = get_field( 'price', $post_id );
	if ( $price ) {
		return '$' . number_format( (float) $price, 2 );
	}
	return '<em>Request pricing</em>';
}

/**
 * data-class label for the request pricing button on a class CPT.
 */
function accr_class_request_label( $post_id = null ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	
	$label = '';
	if ( function_exists( 'get_field' ) ) {
		$label = get_field( 'request_class_label', $post_id );
	}
	return $label ? $label : get_the_title( $post_id );
}

function accr_section_open( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'background' => 'default',
		'class'      => 'section',
		'id'         => '',
		'style'      => '',
	) );

	$style = $args['style'];
	
	$args['class'] .= ' section--' . $args['background'];

	$attrs = '';
	if ( $args['id'] ) {
		$attrs .= ' id="' . esc_attr( $args['id'] ) . '"';
	}
	if ( $style ) {
		$attrs .= ' style="' . esc_attr( $style ) . '"';
	}

	echo '<section class="' . esc_attr( $args['class'] ) . '"' . $attrs . '>';
}

function accr_section_close() {
	echo '</section>';
}
