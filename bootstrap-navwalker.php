<?php

/**
 * Bootstrap NavWalker
 * Class Name: Bootstrap_NavWalker
 * Author: Bishal Napit
 * Author URI: https://napitwptech.com/
 * GitHub URI: https://github.com/mebishalnapit/bootstrap-navwalker/
 * Description: A custom WordPress nav walker class to implement the Bootstrap 4 navigation style in a custom WordPress
 * Bootstrap based theme using the WordPress built in menu manager.
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class Bootstrap_NavWalker extends Walker_Nav_Menu {

	// Create the $current_menu_id_bootstrap variable for generating the current menu id
	protected $current_menu_id_bootstrap;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 * @see   Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		// Use the current menu id generated via start_el()
		$current_menu_id = $this->current_menu_id_bootstrap;

		// Assign the dynamic id for use inside the dropdown menu, ie, sub-menu for Bootstrap
		$id = 'aria-labelledby="navbar-dropdown-menu-link-' . $current_menu_id->ID . '"';

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent = str_repeat( $t, $depth );

		/**
		 * Add the classes for the dropdown menu in WordPress
		 *
		 * 1. For WordPress default: '.sub-menu'
		 * 2. For Bootstrap Sub-Menu: '.dropdown-menu'
		 */
		$classes = array( 'sub-menu', 'dropdown-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Change <ul> to <div> for Bootstrap Navigation
		 * Add the current menu id for the sub-menu toggle feature for Bootstrap
		 */
		$output .= "{$n}{$indent}<div $class_names $id>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 * @see   Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent = str_repeat( $t, $depth );

		/**
		 * Change </ul> to </div> for Bootstrap Navigation
		 */
		$output .= "$indent</div>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 * @see   Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		// Find the current menu item id to be used for start_lvl()
		$this->current_menu_id_bootstrap = $item;

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : ( array ) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Add class '.nav-item' inside <li> tag for Bootstrap
		 */
		$classes[] = 'nav-item';

		/**
		 * Add class '.active' inside <li> tag for Bootstrap active menu as well as for the parent menu, which have the active sub-menu
		 */
		if ( in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-parent', $classes ) ) {
			$classes[] = 'active';
		}

		/**
		 * Add class '.dropdown' inside <li> tag for Bootstrap dropdown menu, ie, <li> having sub-menu
		 */
		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$classes[] = 'dropdown';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		/**
		 * <li> is required for parent menu only in Bootstrap
		 */
		if ( $depth === 0 ) {
			$output .= $indent . '<li' . $id . $class_names . '>';
		}

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Add '.nav-link' class for <a> in parent menu for Bootstrap
		 */
		if ( $depth === 0 ) {
			$atts['class'] = 'nav-link';
		}

		/**
		 * Add the attributes for <a> in parent menu
		 *
		 * 1. Add '.dropdown-toggle' class for <a> in parent menu if it has sub-menu as required for Bootstrap
		 * 2. Add '.dropdown' as 'data-toggle' attribute in <a> in parent menu if it has sub-menu as required for Bootstrap
		 * 3. Add the current menu id attribute to indicate the exact menu to toggle for set in sub-menu div
		 * 4. Add the attribute of 'true' for 'aria-haspopup' in parent menu to indicate it has sub-menus
		 * 5. Add the attribute of 'false' for 'aria-expanded' in parent menu to indicate the sub-menus is hidden by default
		 * 6. Add the '#' link in the <a> tag in the parent menu if it has sub-menu as required for Bootstrap
		 */
		if ( $depth === 0 && in_array( 'menu-item-has-children', $classes ) ) {
			$atts['class']         .= ' dropdown-toggle';
			$atts['data-toggle']   = 'dropdown';
			$atts['id']            = 'navbar-dropdown-menu-link-' . $item->ID;
			$atts['aria-haspopup'] = "true";
			$atts['aria-expanded'] = "false";
			$atts['href']          = '#';
		}

		/**
		 * Add the attributes for <a> in sub-menu
		 * 1. Add '.dropdown-item' class for <a> inside sub-menu for Bootstrap
		 * 2. Add the current menu id attribute if you want to style the menu differently
		 */
		if ( $depth > 0 ) {
			$atts['class'] = 'dropdown-item';
			$atts['id']    = 'menu-item-' . $item->ID;
		}

		/**
		 * Add '.active' class inside <a> in sub-menu for Bootstrap
		 */
		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$atts['class'] .= ' active';
		}

		/**
		 * Add '.disabled' class for <a> in menu for Bootstrap disabled class
		 */
		if ( in_array( 'disabled', $item->classes ) ) {
			$atts['class'] .= ' disabled';
		}

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since                  3.6.0
		 * @since                  4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $atts   {
		 *                         The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string    $title  Title attribute.
		 * @type string    $target Target attribute.
		 * @type string    $rel    The rel attribute.
		 * @type string    $href   The href attribute.
		 *                         }
		 *
		 * @param WP_Post  $item   The current menu item.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 * @param int      $depth  Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

				/**
				 * If '.disabled' class is added to the menu, add the url of '#' in it
				 */
				if ( in_array( 'disabled', $item->classes ) ) {
					$value = ( 'href' === $attr ) ? esc_url( '#' ) : esc_attr( $value );
				}

				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 * @see   Walker::end_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		/**
		 * <li> is required for parent menu only in Bootstrap
		 */
		if ( $depth === 0 ) {
			$output .= "</li>{$n}";
		}
	}

	/**
	 * Fallback menu
	 * If you assign the fallback menu for your custom menu setup via wp_nav_menu function, then, this function will be
	 * rendered if no menu is assigned to that menu location. You need to assign it via 'fallback_cb' array. Also, this
	 * will be only rendered to the logged in users pointing them to the menu manager url.
	 *
	 * @param $args Arrays passed from the wp_nav_menu function
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			$container       = $args['container'];
			$container_id    = $args['container_id'];
			$container_class = $args['container_class'];
			$menu_class      = $args['menu_class'];
			$menu_id         = $args['menu_id'];

			// If there is container render it
			if ( $container ) {
				echo '<' . esc_attr( $container );

				// If container id is set render it
				if ( $container_id ) {
					echo ' id="' . esc_attr( $container_id ) . '"';
				}

				// If container class is set render it
				if ( $container_class ) {
					echo ' class="' . esc_attr( $container_class ) . '"';
				}

				echo '>';
			}

			// Default wrapper for menu is <ul>
			echo '<ul';

			// If menu id has been set render it
			if ( $menu_id ) {
				echo ' id="' . esc_attr( $menu_id ) . '"';
			}

			// If menu class has been set render it
			if ( $menu_class ) {
				echo ' class="' . esc_attr( $menu_class ) . '"';
			}

			// Close <ul> div wrapper for menu
			echo '>';

			// Display the link to Add New Menu
			echo '<li class="nav-item active"><a class="nav-link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">';
			esc_html_e( 'Add a menu', 'theme-textdomain' );
			echo '</a></li>';

			// Close the main <ul>
			echo '</ul>';

			// Close the main container div
			if ( $container ) {
				echo '</' . esc_attr( $container ) . '>';
			}

		}
	}

}
