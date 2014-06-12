<?php

/**
 * Icicle setup.
 *
 * Register sidebars, global, scripts, theme options.
 *
 * @package WordPress
 * @subpackage icicle
 */

/**
 * Set up the content width value based on the theme's design.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/**
 * Icicle setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Icicle supports.
 *
 * @since Icicle 1.0
 */
function icicle_setup() {

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menu( 'primary-menu', __( 'Primary Navigation Menu', 'icicle' ) );
	register_nav_menu( 'secondary-menu', __( 'Secondary Navigation Menu', 'icicle' ) );

	// Allow for post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Allow for HTML5.	
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

}
add_action( 'after_setup_theme', 'icicle_setup' );


/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Icicle 1.0
 */
function icicle_scripts_styles() {
	
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
	// wp_enqueue_style( 'icicle-style', get_stylesheet_uri(), array(), '2014-05-30' );
	wp_enqueue_style( 'icicle-sass', get_stylesheet_directory_uri().'/sass/output.css' , array(), '2014-05-30' );
	
	// Grab wp-includes version of jQuery.
	wp_enqueue_script( 'jquery' );

}
add_action( 'wp_enqueue_scripts', 'icicle_scripts_styles' );

/**
 * Register our widget areas.
 *
 * @since Icicle 1.0
 */
function icicle_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'icicle' ),
		'id'            => 'footer-widgets',
		'description'   => __( 'Appears in the footer section of the site.', 'icicle' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget content-holder %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title footer-widget-title">',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'icicle_widgets_init' );