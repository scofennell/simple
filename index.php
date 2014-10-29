<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */

get_header(); ?>

<main id="loop" class="outer-wrapper" role="main">

	<?php
		/**
		 * This applies to 404's empty archive pages.
		 */
	?>

	<?php if( is_404() || ! have_posts() ) { ?>
		
		<article class='hentry no-posts inner-wrapper entry-content'>
			
			<?php echo anchorage_get_no_posts(); ?>

		</article>

	<?php } elseif ( have_posts() ) { ?>

		<?php /* The loop */ ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
		
			<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">

				<?php get_template_part( 'post', 'header' ); ?>
				
				<?php get_template_part( 'post', 'content' ); ?>

				<?php get_template_part( 'post', 'footer' ); ?>
				
			</article><!-- #post -->

		<?php endwhile; ?>

	<?php } ?>

	</main> <!-- end #loop-wrapper -->

	<?php if ( have_posts() ) { ?>

		<?php if ( ! is_singular() ) { ?>
			
			<?php
				/**
				 * Anything other than single posts getsget next/prev posts links.
				 */
			?>

			<section id="post-page-nav" class="outer-wrapper">
			
				<div class="inner-wrapper">
			
					<?php echo anchorage_get_paging_nav(); ?>
			
				</div>
			
			</section>
		
		<?php } elseif( is_single() ) { ?>
			
			<?php
				/**
				 * Single posts getsget next/prev post links.
				 */
			?>

			<section id="post-page-nav" class="outer-wrapper">
			
				<div class="inner-wrapper">				
			
					<?php echo anchorage_get_post_nav(); ?>
			
				</div>
			
			</section>
		
		<?php } ?>

		<?php if ( is_singular() && comments_open() ) { ?>

			<?php if ( ! post_password_required() ) { ?>
			
				<?php comments_template(); ?>

			<?php } ?>	
	
		<?php } ?>	

	<?php } ?>

<?php get_footer(); ?>