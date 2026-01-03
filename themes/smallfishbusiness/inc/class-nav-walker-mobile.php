<?php
/**
 * Custom Navigation Walker for Mobile 3-Level Menu
 *
 * @package SmallFishBusiness
 */

class SFB_Nav_Walker_Mobile extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Arguments for walk_nav_menu.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$level  = $depth + 2; // Level 2 for first submenu, Level 3 for second.

		$output .= "\n{$indent}<ul class=\"mobile-submenu mobile-submenu--level-{$level}\" role=\"menu\" hidden>\n";
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
		$classes[] = 'mobile-menu-item';
		$classes[] = 'mobile-menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( $has_children ) {
			$classes[] = 'mobile-has-submenu';
		}

		$classes[] = 'mobile-menu-depth-' . $depth;

		$class_names = implode( ' ', array_filter( $classes ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li' . $class_names . ' role="none">';

		// Wrapper for link and toggle button.
		if ( $has_children ) {
			$output .= '<div class="mobile-menu-item-wrapper">';
		}

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
		$item_output .= '</a>';

		// Add toggle button for items with children.
		if ( $has_children ) {
			$item_output .= '<button type="button" class="mobile-submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Toggle submenu', 'smallfishbusiness' ) . '">';
			$item_output .= '<svg class="mobile-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
			$item_output .= '</button>';
			$item_output .= '</div>'; // Close wrapper.
		}

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
