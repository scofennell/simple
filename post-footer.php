<?php if( ! is_page() ) { ?>

	<footer class="entry-meta inverse-shadow content-holder">

		<div class="tags-byline-wrap has-halfs clear">
	
			<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>
				
				<?php echo anchorage_entry_tags(); ?>
				
				<?php echo anchorage_entry_byline(); ?>
			
			<?php } ?>

		</div>

		<?php if( is_single() ) { ?>

			<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>

				<?php echo anchorage_author_bio(); ?>

			<?php } ?>

		<?php } ?>

	</footer><!-- .entry-meta -->

<?php }