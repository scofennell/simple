<?php
/**
 * anchorage post-footer.
 *
 * @todo Display a link to comments.
 * 
 * @package WordPress
 * @subpackage anchorage
 * @since  anchorage 1.0
 */
?>



<?php if( ! is_page() ) { ?>

	<footer class="entry-meta inverse-shadow">

		<?php if ( comments_open() && ! post_password_required() ) { ?>

			<?php echo anchorage_get_comments_link(); ?>

		<?php } ?>

		<?php echo anchorage_get_hard_rule( array( 'break-minor' ) ); ?>

		<div class="tags-byline-wrap has-halfs clear">
	
			<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>
				
				<?php echo anchorage_get_entry_tags(); ?>
				
				<?php echo anchorage_get_entry_byline(); ?>
			
			<?php } ?>

		</div>

		<?php if( is_single() ) { ?>

			<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>

				<?php echo anchorage_get_author_bio(); ?>

			<?php } ?>

		<?php } ?>

	</footer><!-- .entry-meta -->

<?php }