<?php
/**
 * Primary nav walker — adds dropdown / ARIA markup for sub-menus.
 *
 * @package ACCR_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACCR_Primary_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$classes = array( 'nav-submenu' );
		if ( $depth > 0 ) {
			$classes[] = 'nav-submenu--nested';
		}
		$class_attr = esc_attr( implode( ' ', $classes ) );
		$output    .= "\n{$indent}<ul class=\"{$class_attr}\" role=\"menu\">\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );
		if ( $has_children ) {
			$classes[] = 'has-submenu';
			if ( 0 === $depth ) {
				$classes[] = 'has-dropdown';
			}
		}

		$class_names = join(
			' ',
			apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth )
		);
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names;
		if ( $has_children ) {
			$output .= ' role="none"';
		}
		$output .= '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		if ( $has_children ) {
			$attributes .= ' aria-haspopup="true" aria-expanded="false"';
		}
		if ( in_array( 'current-menu-item', $classes, true ) ) {
			$attributes .= ' aria-current="page"';
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$link  = ( ! empty( $args->before ) ? $args->before : '' ) . '<a' . $attributes . '>';
		$link .= ( ! empty( $args->link_before ) ? $args->link_before : '' ) . $title . ( ! empty( $args->link_after ) ? $args->link_after : '' );
		$link .= '</a>' . ( ! empty( $args->after ) ? $args->after : '' );

		$output .= $link;

		if ( $has_children ) {
			$output .= sprintf(
				'<button type="button" class="nav-submenu-toggle" aria-expanded="false" aria-label="%1$s">'
				. '<span aria-hidden="true" class="nav-submenu-toggle__icon"></span>'
				. '</button>',
				esc_attr(
					sprintf(
						/* translators: %s: parent menu item title. */
						__( 'Show submenu for %s', 'accr-theme' ),
						wp_strip_all_tags( $title )
					)
				)
			);
		}
	}
}
