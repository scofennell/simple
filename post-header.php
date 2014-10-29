<?php
/**
 * anchorage post-header.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since  anchorage 1.0
 */
?>

<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>

	<?php
	/**
	 * As long as we're not on some abbreviated post format, we'll do a post header.
	 */
	?>

	<header class="entry-header content-holder">

		<?php if ( is_page() ) { ?>

			<?php
			/**
			 * Pages show a breadcrumbs menu back up the tree of child pages.
			 */
			?>

			<?php echo anchorage_get_page_ancestors(); ?>

		<?php } ?>

		<h1 class="entry-title">
		
			<?php
			/**
			 * If there is a special post format, we label it as such.
			 */
			?>

			<?php echo anchorage_get_post_format( 'right' ); ?>

			<?php if ( has_post_format( 'link' ) ) { ?>

				<?php
				/**
				 * If it's a post format: link, we link the title to the first link in the post content.
				 */
				?>

				<a href="<?php echo esc_url( get_url_in_content( get_the_content() ) ); ?>" rel="bookmark">					
			
			<?php } elseif ( ! is_singular() ) { ?>
			
				<a href="<?php the_permalink(); ?>" rel="bookmark">					
			
			<?php } ?>
		
			<?php if ( is_home() && is_sticky() ) { ?>
			
				<?php echo esc_html__( 'Sticky:', 'anchorage' ); ?>
			
			<?php } ?>

			<?php the_title(); ?>
	
			<?php if ( ! is_singular() || has_post_format( 'link' ) ) { ?>
			
				</a>
			
			<?php } ?>

		</h1>

		<?php echo anchorage_get_entry_cats(); ?>

		<?php
		/**
		 * If its a post format: image and it does have an image in the content, skip the post thumbnail business.
		 */
		?>

		<?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
	
			<?php if( ! has_post_format( 'image' ) &&  ! ( anchorage_get_first_image() ) ) { ?>

				<div class="entry-thumbnail">

					<?php the_post_thumbnail(); ?>
				
				</div>

			<?php } ?>

		<?php } ?>

	</header><!-- .entry-header -->

<?php }