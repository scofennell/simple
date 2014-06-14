<?php

/**
 * simple setup.
 *
 * Register sidebars, global, scripts, theme options.
 *
 * @package WordPress
 * @subpackage simple
 */

/**
 * Set up the content width value based on the theme's design.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/**
 * simple setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * simple supports.
 *
 * @since simple 1.0
 */
function simple_setup() {

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menu( 'primary-menu', __( 'Primary Navigation Menu', 'simple' ) );
	register_nav_menu( 'secondary-menu', __( 'Secondary Navigation Menu', 'simple' ) );

	// Allow for post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Allow for HTML5.	
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

}
add_action( 'after_setup_theme', 'simple_setup' );

/**
 * Grab editor styles from the main stylesheet.
 *
 * @since simple 1.0
 */
function simple_add_editor_styles() {
    add_editor_style( get_stylesheet_directory_uri().'/sass/output.css' );
}
add_action( 'init', 'simple_add_editor_styles' );

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since simple 1.0
 */
function simple_scripts_styles() {
	
	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/**
	 * Loads our main stylesheet, complete with a value for date to break
	 * caching.  That date should be manually updated whenever the stylesheet
	 * is changed,
	 */  
	// wp_enqueue_style( 'simple-style', get_stylesheet_uri(), array(), '2014-05-30' );
	wp_enqueue_style( 'simple-sass', get_stylesheet_directory_uri().'/sass/output.css' , array(), '2014-05-30' );
	
	// Grab wp-includes version of jQuery.
	wp_enqueue_script( 'jquery' );

}
add_action( 'wp_enqueue_scripts', 'simple_scripts_styles' );

/**
 * Register our widget areas.
 *
 * @since simple 1.0
 */
function simple_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'simple' ),
		'id'            => 'footer-widgets',
		'description'   => __( 'Appears in the footer section of the site.', 'simple' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget content-holder %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title footer-widget-title">',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'simple_widgets_init' );