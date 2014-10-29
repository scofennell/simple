<?php

/**
 * anchorage filtration.
 *
 * Miscellaneous filter functions for things like post class, body class,
 * menu item classes, etc.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since  anchorage 1.0
 */

/**
 * Filter the document title, as in <title></title>.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.  Is mostly a rip-off of Twenty Thirteen.
 *
 * @param string $title Default title text for current view
 * @param string $sep   Optional separator
 * @return string The filtered title
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_wp_title' ) ) {
	function anchorage_wp_title( $title, $sep ) {

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
			$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'anchorage' ), max( $paged, $page ) );
		}

		return $title;

	}
}
add_filter( 'wp_title', 'anchorage_wp_title', 10, 2 );

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_body_class' ) ) {
	function anchorage_body_class( $classes ) {
		
		// Add a class to respond to is_multi_author().
		if ( ! is_multi_author() ) {
			$classes[] = 'single-author';
		} else {
			$classes[] = 'multi-author';
		}

		if ( ! get_option( 'show_avatars' ) ){ $classes[] = 'no-avatars'; }

		return $classes;

	}
}
add_filter( 'body_class', 'anchorage_body_class' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds post classes:
 * 1. Adds our standard 'inner-wrapper' class, used for layout styles.
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_post_class' ) ) {
	function anchorage_post_class( $classes ) {

		global $post;

		if( is_single( $post ) ) {
			$classes[] = 'hentry-single';
		}

		$classes[] = 'inner-wrapper';

		return $classes;

	}
}
add_filter( 'post_class', 'anchorage_post_class' );

/**
 * Filter and return the post password form.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_post_password_form' ) ) {
	function anchorage_get_post_password_form() {
	    
	    global $post;
	    
	    // Build a unique string for each post to use as a "for" attribute.
	    $slug = sanitize_html_class( $post -> post_name );
	    $id = absint( $post -> ID );
	    $unique = $slug . $id;
	    
	    $label = __( 'To view this protected post, enter the password below:', 'anchorage' );
	    
	    $url = esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );
	    
	    $submit = esc_attr__( 'Submit', 'anchorage' );
	    
	    $out = "
	    	<form action='$url' method='post' class='post-password-form'>
	    		<label for='$unique'>$label</label>
	    		<input name='post_password' id='$unique' type='password'>
	    		<input type='submit' name='Submit' value='$submit' class='button'>
	    	</form>
	    ";

	    /**
	     * This function will get hit with whatever filters hit the_content, to include wpautop,
	     * so let's strip any line breaks to avoid unwanted <p> tags.
	     */
	    $out = trim( preg_replace( '/\s+/', ' ', $out ) );

	    return $out;
	}
}
add_filter( 'the_password_form', 'anchorage_get_post_password_form' );

/**
 * The cancel-reply-link is clutter.
 *
 * @since anchorage 1.0
 */
add_filter( 'cancel_comment_reply_link', '__return_false' );