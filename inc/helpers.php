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
		'width'         => '24',
		'height'        => '24',
		'viewBox'       => '0 0 24 24',
		'fill'          => 'none',
		'stroke'        => 'currentColor',
		'stroke-width'  => '2',
		'stroke-linecap' => 'round',
		'stroke-linejoin' => 'round',
		'aria-hidden'   => 'true',
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
		'layers'        => '<path d="M12 2 4 8l8 6 8-6-8-6zM4 14l8 6 8-6M4 20l8 6 8-6"/>',
		'sun'           => '<circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/>',
		'forklift'      => '<circle cx="6" cy="18" r="3"/><circle cx="18" cy="18" r="3"/><path d="M3 15V8h8l4 4h6v6"/>',
		'classroom'     => '<path d="M3 21h18M12 3v18M5 8l7-5 7 5M9 13h6M9 17h6"/>',
		'calendar'      => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>',
		'cube'          => '<path d="M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>',
		'arrow_right'   => '<path d="M5 12h14M13 5l7 7-7 7"/>',
		'alert'         => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
		'mail'          => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
		'phone'         => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'check_circle'  => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
		'close'         => '<path d="M18 6 6 18M6 6l12 12"/>',
	);

	$inner = isset( $paths[ $slug ] ) ? $paths[ $slug ] : $paths['shield'];

	return '<svg' . $attr_str . '>' . $inner . '</svg>';
}

/**
 * Choices list for ACF icon selects.
 */
function accr_icon_choices() {
	return array(
		'shield'       => 'Shield',
		'shield_check' => 'Shield with check',
		'check'        => 'Check',
		'check_circle' => 'Check (in circle)',
		'award'        => 'Award',
		'building'     => 'Building',
		'building_alt' => 'Building (alt)',
		'clock'        => 'Clock',
		'plus'         => 'Plus',
		'pencil'       => 'Pencil',
		'users'        => 'Users',
		'tower'        => 'Tower / crane',
		'layers'       => 'Layers / rigging',
		'sun'          => 'Sun / signal',
		'forklift'     => 'Forklift / telehandler',
		'classroom'    => 'Classroom / on-site',
		'calendar'     => 'Calendar',
		'cube'         => 'Cube / impact',
		'arrow_right'  => 'Arrow right',
		'alert'        => 'Alert / info',
		'mail'         => 'Mail',
		'phone'        => 'Phone',
	);
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
			'<button class="%1$s" data-request-pricing data-class="%2$s">%3$s</button>',
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
function accr_format_class_date( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
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
	return date_i18n( 'M&nbsp;j, Y', $ts );
}

/**
 * Format a class CPT tuition for display.
 */
function accr_format_class_tuition( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return '';
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
function accr_class_request_label( $post_id ) {
	$label = '';
	if ( function_exists( 'get_field' ) ) {
		$label = get_field( 'request_class_label', $post_id );
	}
	return $label ? $label : get_the_title( $post_id );
}

/**
 * Render a section wrapper opening tag from common parameters.
 *
 * Accepts an associative array with optional keys:
 *   - background ("default" | "surface_2")
 *   - class      (additional class names)
 *   - id         (anchor id)
 *   - style      (inline style)
 */
/**
 * Companion-meta image fallback for the WXR import.
 *
 * The shipped WXR import file stores image URL/alt under companion meta
 * `{meta_key}__url` and `{meta_key}__alt` because portable imports can't
 * ship pre-attached attachment IDs. When an image ACF field's underlying
 * meta is empty, this filter synthesises an array of the shape ACF would
 * return for a real attachment so templates render without modification.
 *
 * Hooked at acf/format_value so we run AFTER ACF's normal value loader.
 */
add_filter( 'acf/format_value/type=image', 'accr_image_url_fallback', 20, 3 );
function accr_image_url_fallback( $value, $post_id, $field ) {
	if ( ! empty( $value ) ) {
		return $value;
	}

	if ( empty( $field['name'] ) ) {
		return $value;
	}

	$rid = is_numeric( $post_id ) ? (int) $post_id : get_the_ID();
	if ( ! $rid ) {
		return $value;
	}

	$field_name = (string) $field['name'];
	$candidates = array( $field_name );

	if ( ! empty( $field['_name'] ) && $field['_name'] !== $field_name ) {
		$candidates[] = (string) $field['_name'];
	}

	// ACF loop state differs between versions. Some versions return one
	// associative loop, while others expose a numeric loop stack. Support both
	// without assuming the presence of a numeric index 0.
	if ( function_exists( 'acf_get_loop' ) ) {
		$loops = acf_get_loop();
		if ( is_array( $loops ) && ! empty( $loops ) ) {
			$loop_stack = array();

			if ( isset( $loops['name'], $loops['i'] ) ) {
				$loop_stack[] = $loops;
			} elseif ( isset( $loops[0] ) && is_array( $loops[0] ) ) {
				$loop_stack = array_values( array_filter( $loops, 'is_array' ) );
			} elseif ( isset( $loops['loops'] ) && is_array( $loops['loops'] ) ) {
				$loop_stack = array_values( array_filter( $loops['loops'], 'is_array' ) );
			}

			if ( 1 === count( $loop_stack ) ) {
				$loop = $loop_stack[0];
				if ( isset( $loop['name'], $loop['i'] ) ) {
					$candidates[] = $loop['name'] . '_' . $loop['i'] . '_' . $field_name;
				}
			} elseif ( count( $loop_stack ) > 1 ) {
				$prefix = '';
				foreach ( $loop_stack as $index => $loop ) {
					if ( ! isset( $loop['i'] ) ) {
						continue;
					}

					if ( 0 === $index && ! empty( $loop['name'] ) ) {
						$prefix = $loop['name'] . '_' . $loop['i'];
						continue;
					}

					$loop_name = '';
					if ( ! empty( $loop['field']['name'] ) ) {
						$loop_name = $loop['field']['name'];
					} elseif ( ! empty( $loop['name'] ) ) {
						$loop_name = $loop['name'];
					}

					if ( $loop_name ) {
						$prefix .= '_' . $loop_name . '_' . $loop['i'];
					}
				}

				if ( $prefix ) {
					$candidates[] = $prefix . '_' . $field_name;
				}
			}
		}
	}

	$candidates = array_values( array_unique( array_filter( $candidates ) ) );
	foreach ( $candidates as $base ) {
		$url = get_post_meta( $rid, $base . '__url', true );
		$alt = get_post_meta( $rid, $base . '__alt', true );
		if ( $url ) {
			return array(
				'ID'    => 0,
				'id'    => 0,
				'url'   => $url,
				'alt'   => $alt,
				'title' => '',
			);
		}
	}

	// Last-resort fallback: if exactly one companion meta key matches this
	// field's suffix, use it. If there are multiple matches, leave the value
	// empty rather than guessing and rendering the wrong image.
	$all_meta = get_post_meta( $rid );
	$matches  = array();
	foreach ( $all_meta as $meta_key => $meta_value ) {
		if ( preg_match( '/(^|_)' . preg_quote( $field_name, '/' ) . '__url$/', $meta_key ) ) {
			$matches[] = substr( $meta_key, 0, -5 );
		}
	}
	if ( 1 === count( $matches ) ) {
		$url = get_post_meta( $rid, $matches[0] . '__url', true );
		$alt = get_post_meta( $rid, $matches[0] . '__alt', true );
		if ( $url ) {
			return array(
				'ID'    => 0,
				'id'    => 0,
				'url'   => $url,
				'alt'   => $alt,
				'title' => '',
			);
		}
	}

	return $value;
}

function accr_section_open( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'background' => 'default',
		'class'      => 'section',
		'id'         => '',
		'style'      => '',
	) );

	$style = $args['style'];
	if ( 'surface_2' === $args['background'] ) {
		//$style .= 'background: var(--color-surface-2); border-top: 1px solid var(--color-divider); border-bottom: 1px solid var(--color-divider);';
		$style .= 'background: var(--color-surface-2);';
	}

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
