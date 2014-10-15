<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */
?><!DOCTYPE html>

<?php
	//echoes the opening html tag along with classes for different versions of IE
	echo anchorage_the_html_classes();
?>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">

	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php
		//if it's less than html9, get the html5 shiv & print shiv
		//https://github.com/aFarkas/html5shiv
	?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv-printshiv.js"></script>
	<![endif]-->
	
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<a class="screen-reader-text skip-link" href="#loop"><?php _e( 'Skip to content', 'anchorage' ); ?></a>		
	
	<?php anchorage_header_menu( 'primary-menu' ); ?>

	<div id="body-wrapper" class="">

			<?php echo anchorage_arrow( 'left', array( 'toggle', 'shadowed', 'primary-menu-toggle' ), '#blog-header' ); ?>