<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till #loop
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */
?><!DOCTYPE html>

<?php
	/**
	 * Echoes the opening html tag along with classes for different versions of IE.
	 */
	echo anchorage_the_html_classes();
?>

	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width">

		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php echo anchorage_get_skip_to_content_link(); ?>		
		
		<?php anchorage_header_menu( 'primary-menu' ); ?>

		<?php if( is_archive() || is_search() || is_404() ) { ?>

			<section class='outer-wrapper'>
	
				<div class='inner-wrapper'>
	
					<?php echo anchorage_get_archive_header(); ?>
		
				</div>
	
			</section>

		<?php } ?>