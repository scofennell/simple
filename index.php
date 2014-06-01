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
		<h1 class="inner-wrapper page-title"><?php printf( __( 'Search Results for: %s', 'icicle' ), get_search_query() ); ?></h1>
	</header>
<?php } ?>	

<?php if (is_archive()){ ?>
	<header class="page-header archive-header outer-wrapper">
		<h1 class="inner-wrapper page-title"><?php printf( __( 'Category Archives: %s', 'icicle' ), single_cat_title('', false) ); ?></h1>
	</header>
<?php } ?>	

<?php if ( have_posts() ) : ?>
	<main id="loop" class="outer-wrapper" role="main">

	<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		
			<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
	
				<header class="entry-header accent-shadow">

					<h1 class="entry-title">
							
						<?php if ( !is_single() ) { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark">
						<?php } ?>
							
							<?php the_title(); ?>
						
						<?php if ( !is_single() ) { ?>
							</a>
						<?php } ?>
					
					</h1>
		
					<?php echo icicle_entry_cats(); ?>

					<?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
					<?php } ?>

				</header><!-- .entry-header -->

				<section itemprop="articleBody" class="entry-content">
		
					<?php the_content('<span class="accent-shadow">Read More&hellip;</span>'); ?>
				
					<?php if( is_single() ) { wp_link_pages(); } ?>

				</section><!-- .entry-content -->

				<hr class="break break-minor">

				<footer class="entry-meta accent-shadow">
			
					<div class="tags-byline-wrap clear">
						<?php echo icicle_entry_tags(); ?>

						<?php echo icicle_entry_byline(); ?>

					</div>

					<?php if( is_single() ) { ?>

						<?php echo icicle_author_bio(); ?>
		
					<?php } ?>

				</footer><!-- .entry-meta -->

			</article><!-- #post -->

		<?php endwhile; ?>

	</main> <!-- end #loop-wrapper -->

	<section id="post-page-nav" class="outer-wrapper">
		<div class="inner-wrapper">
			<?php if ( is_single() ) { ?>
				<?php echo icicle_post_nav(); ?>
			<?php } else { ?>
				<?php echo icicle_paging_nav(); ?>
			<?php } ?>
		</div>
	</section>

	
	<?php if ( is_single() && comments_open() && ! post_password_required() ) { ?>

		<?php comments_template(); ?>

	<?php } ?>
		
	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>

<?php get_footer(); ?>