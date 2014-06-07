<?php

/**
 * Icicle filtration.
 *
 * Miscellaneous filter functions for things like post class, body class,
 * menu item classes, etc.
 *
 * @package WordPress
 * @subpackage icicle
 */


/**
 * Filter the document title, as in <title></title>.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.  Is mostly a rip-off of Twenty Thirteen.
 *
 * @since Icicle 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function icicle_wp_title( $title, $sep ) {

	global $paged, $page;

	// If we're on the feed, just return the title as-is.
	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'icicle' ), max( $paged, $page ) );
	}

	return $title;

}
add_filter( 'wp_title', 'icicle_wp_title', 10, 2 );

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Icicle 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function icicle_body_class( $classes ) {
	
	if ( ! is_multi_author() ) { $classes[] = 'single-author'; }

	if ( ! get_option( 'show_avatars' ) ){ $classes[] = 'no-avatars'; }

	return $classes;

}
add_filter( 'body_class', 'icicle_body_class' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds post classes:
 * 1. Adds our standard 'inner-wrapper' class, used for layout styles.
 *
 * @since Icicle 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function icicle_post_class( $classes ) {

	global $post;

	if( is_single( $post ) ) {
		$classes[] = 'hentry-single';
	}

	$classes[] = 'inner-wrapper';

	return $classes;

}
add_filter( 'post_class', 'icicle_post_class' );

/**
 * Extend the default WordPress menu classes.
 *
 * Adds menu classes:
 * 1. If the menu item has children, it gets 'menu-parent-item'.
 *
 * @since Icicle 1.0
 *
 * @param array $items Menu item objects.
 * @return array The filtered array of menu item objects.
 */
/*
function icicle_add_menu_parent_class( $items ) {
	
	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = absint($item->menu_item_parent);
		}
	}
	
	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'menu-parent-item'; 
		}
	}
	
	return $items;    
}
add_filter( 'wp_nav_menu_objects', 'icicle_add_menu_parent_class' );
*/