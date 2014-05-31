<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

<?php if (is_search()){ ?>
	<header class="page-header search-header outer-wrapper">
		<h1 class="inner-wrapper page-title"><?php printf( __( 'Search Results for: %s', 'twentythirteen' ), get_search_query() ); ?></h1>
	</header>
<?php } ?>	

<?php if (is_archive()){ ?>
	<header class="page-header archive-header outer-wrapper">
		<h1 class="inner-wrapper page-title"><?php printf( __( 'Category Archives: %s', 'twentythirteen' ), single_cat_title('', false) ); ?></h1>
	</header>
<?php } ?>	

<?php if ( have_posts() ) : ?>
	<main id="loop" class="outer-wrapper" role="main">

	<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		
			<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
	
				<header class="entry-header">

					<h1 class="entry-title">
							
						<?php if ( !is_single() ) { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark">
						<?php } ?>
							
							<?php the_title(); ?>
						
						<?php if ( !is_single() ) { ?>
							</a>
						<?php } ?>
					
					</h1>
		
					<?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
					<?php } ?>

				</header><!-- .entry-header -->

				<section itemprop="articleBody" class="accent-font entry-content">
		
					<?php the_content('<span class="accent-shadow">Read More&hellip;</span>'); ?>
				
					<?php if( is_single() ) { wp_link_pages(); } ?>

				</section><!-- .entry-content -->

				<hr class="break break-minor">

				<footer class="entry-meta accent-font accent-shadow">
			
					<?php twentythirteen_entry_meta(); ?>

					<?php if( is_single() ) { ?>

						<?php icicle_the_author_bio(); ?>
		
					<?php } ?>

				</footer><!-- .entry-meta -->

			</article><!-- #post -->

		<?php endwhile; ?>

	</main> <!-- end #loop-wrapper -->

	<nav id="page-nav" class="outer-wrapper">
		<div class="inner-wrapper">
			<?php if ( is_single() ) { ?>
				<?php twentythirteen_post_nav(); ?>
			<?php } else { ?>
				<?php twentythirteen_paging_nav(); ?>
			<?php } ?>
		</div>	
	</nav>

	<?php if ( is_single() && comments_open() && ! post_password_required() ) { ?>

		<section id="comments" class="outer-wrapper inverted">
			<div id="comments-inner-wrapper" class="inner-wrapper">

					<?php comments_template(); ?>

			</div>
		</section><!-- #comments -->

	<?php } ?>
		
	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>

<?php get_footer(); ?>