<?php
/**
 * Custom Navigation Walker for 3-Level Dropdown Menu
 *
 * @package SmallFishBusiness
 */

class SFB_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Arguments for walk_nav_menu.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		// Level 2: Dropdown (opens below parent)
		// Level 3: Flyout submenu (opens to the right)
		$submenu_class = $depth === 0
			? 'dropdown-menu dropdown-menu--level-2'
			: 'dropdown-menu dropdown-menu--level-3';

		$output .= "\n{$indent}<ul class=\"{$submenu_class}\" role=\"menu\">\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Arguments for walk_nav_menu.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
	}

	/**
	 * Starts the element output.
	 *
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Arguments for walk_nav_menu.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = str_repeat( "\t", $depth );

		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Check if item has children.
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( $has_children ) {
			$classes[] = 'has-dropdown';
		}

		// Add depth class.
		$classes[] = 'menu-depth-' . $depth;

		$class_names = implode( ' ', array_filter( $classes ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		// ARIA attributes for accessibility.
		$aria_attrs = '';
		if ( $has_children ) {
			$aria_attrs = ' aria-haspopup="true" aria-expanded="false"';
		}

		$output .= $indent . '<li' . $class_names . $aria_attrs . ' role="none">';

		// Build link attributes.
		$atts           = [];
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['role']   = 'menuitem';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before ?? '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( $args->link_before ?? '' ) . esc_html( $title ) . ( $args->link_after ?? '' );

		// Add dropdown indicator arrow for items with children.
		if ( $has_children ) {
			if ( $depth === 0 ) {
				// Level 1: Arrow pointing down.
				$item_output .= '<svg class="dropdown-arrow dropdown-arrow--down" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
			} else {
				// Level 2+: Arrow pointing right.
				$item_output .= '<svg class="dropdown-arrow dropdown-arrow--right" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
			}
		}

		$item_output .= '</a>';
		$item_output .= $args->after ?? '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output.
	 *
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Arguments for walk_nav_menu.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
