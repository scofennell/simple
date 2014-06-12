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

<?php if( is_archive() || is_search() || is_404() ) { ?>
	<section class='outer-wrapper'>
		<div class='inner-wrapper'>
			<?php echo icicle_archive_header(); ?>
		</div>
	</section>
<?php } ?>

<main id="loop" class="outer-wrapper" role="main">

	<?php if( is_404() || ! have_posts() ) { ?>
		
		<article class='hentry no-posts inner-wrapper entry-content'>

			<?php echo icicle_no_posts(); ?>

		</article>

	<?php } elseif ( have_posts() ) { ?>

		<?php /* The loop */ ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
		
			<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">

				<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>
	
					<header class="entry-header content-holder">

						<?php if (is_page() ) { ?>

							<?php echo icicle_page_ancestors(); ?>

						<?php } ?>

						<h1 class="entry-title">
						
							<?php echo icicle_get_post_format(); ?>

							<?php if ( has_post_format( 'link' ) ) { ?>
								<a href="<?php echo get_url_in_content( get_the_content() ); ?>" rel="bookmark">					
							<?php } elseif ( ! is_singular() ) { ?>
								<a href="<?php the_permalink(); ?>" rel="bookmark">					
							<?php } ?>
						
							<?php if ( is_home() && is_sticky() ) { ?>
								<?php echo esc_html__('Sticky:', 'icicle'); ?>
							<?php } ?>

							<?php the_title(); ?>
					
							<?php if ( ! is_singular() || has_post_format( 'link' ) ) { ?>
								</a>
							<?php } ?>
				
						</h1>
	
						<?php echo icicle_entry_cats(); ?>

						<?php /* if its a post format: image and it does have an image in the content, skip the post thumbnail business. */ ?>

						<?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
					
							<?php if( ! has_post_format( 'image' ) &&  ! ( icicle_get_first_image() ) ){ ?>

								<div class="entry-thumbnail">
									<?php the_post_thumbnail(); ?>
								</div>

							<?php } ?>

						<?php } ?>

					</header><!-- .entry-header -->

				<?php } ?>

				<section itemprop="articleBody" class="entry-content editable-content content-holder">
	
					<?php if( has_post_format( 'quote' ) ) { ?>
						<span class='quote open-quote'>&ldquo;</span>
					<?php } ?>

					<?php if( has_post_format( 'image' ) ) { ?>
						<?php echo icicle_get_first_image(); ?>
					<?php } elseif( has_post_format( 'gallery' ) && get_post_gallery() ) { ?>
						<?php echo get_post_gallery(); ?>
					<?php } else { ?>
						<?php the_content('<span class="inverse-shadow">Read More&hellip;</span>'); ?>
					<?php } ?>

					<?php if( has_post_format( 'audio' ) ) { ?>
						<?php echo icicle_get_first_media( 'audio' ); ?>
					<?php } ?>

					<?php if( has_post_format( 'video' ) ) { ?>
						<?php echo icicle_get_first_media( 'video' ); ?>
					<?php } ?>

					<?php if( has_post_format( 'status' ) ) { ?>
						<em class='status-time'>&mdash;
							<?php echo get_the_time(); ?>
							<?php echo get_the_date(); ?>
						</em>
					<?php } ?>

					<?php if( has_post_format( 'quote' ) ) { ?>
						<span class='quote close-quote'>&rdquo;</span>
					<?php } ?>

					<?php if( is_singular() ) { ?>
						<?php echo icicle_link_pages(); ?>
					<?php } ?>					

				</section><!-- .entry-content -->

				<hr class="break break-minor">

				<?php if( ! is_page() ) { ?>

					<footer class="entry-meta inverse-shadow content-holder">
		
						<div class="tags-byline-wrap clear">
					
							<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>
								<?php echo icicle_entry_tags(); ?>
								<?php echo icicle_entry_byline(); ?>
							<?php } ?>

						</div>

						<?php if( is_single() ) { ?>

							<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>

								<?php echo icicle_author_bio(); ?>

							<?php } ?>
		
						<?php } ?>

					</footer><!-- .entry-meta -->

				<?php } ?>

			</article><!-- #post -->

		<?php endwhile; ?>

	<?php } ?>

	</main> <!-- end #loop-wrapper -->

	<?php if ( have_posts() ) { ?>

		<?php if ( ! is_singular() ) { ?>
			
			<section id="post-page-nav" class="outer-wrapper">
				<div class="inner-wrapper">
					<?php echo icicle_paging_nav(); ?>
				</div>
			</section>
		
		<?php } elseif( is_single() ) { ?>
			
			<section id="post-page-nav" class="outer-wrapper">
				<div class="inner-wrapper">				
					<?php echo icicle_post_nav(); ?>
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