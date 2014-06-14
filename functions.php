<?php

/**
 * simple manifest.
 *
 * require_once()'s other files for theme functionality.  This file is just a 
 * manifest:  It contains no function definitions, only calls to other files.
 *
 * @package WordPress
 * @subpackage simple
 * @since simple 1.0
 */

/**
 * Enqueue scripts, establish theme-wide values, setup widgets,
 * call theme-supports.
 */
require_once( get_template_directory()."/inc/setup.php" );

/**
 * Body classes, posts classes, wp_title, etc.
 * @todo See if the menu item filter is still necessary in recent wordpress 
 * versions.
 */
require_once( get_template_directory()."/inc/misc-filters.php" );

/**
 * Styles that need to be able to pass php variables to selectors.
 * @todo Right now, there are none.  Remember to remove this is there end up
 * not being any use cases.
 */
// require_once( get_template_directory()."/inc/header-styles.php" );

/**
 * jQuery snippets for minor UX improvements.
 */
require_once( get_template_directory()."/inc/footer-scripts.php" );

/**
 * Custom template tags used in theme template files.
 */
require_once( get_template_directory()."/inc/template-tags.php" );

/**
 * Custom template tags related to displaying the post comment area.
 * @todo Refactor so that there are no layout #selectors in the template tags
 */
require_once( get_template_directory()."/inc/comment-template-tags.php" );

/**
 * Theme modification API.
 * @todo  over riding sass generated styles from php seems to be too cumbersome
 */
require_once( get_template_directory()."/inc/customization.php" );

/**
 * Functions best reserved for a child theme -- not everyone wants to use my
 * Google Analytics code, for example.
 * 
 * @todo Move the contents of this file to a chile theme.
 */
require_once( get_template_directory()."/inc/move-to-child-theme-eventually.php" );