<?php
/**
 * anchorage post-content.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since  anchorage 1.0
 */
?>

<section itemprop="articleBody" class="entry-content editable-content content-holder">
	
	<?php if( has_post_format( 'quote' ) ) { ?>

		<span class='quote open-quote'>
			<?php echo esc_html__( '&ldquo;', 'anchorage'); ?>
		</span>
	
	<?php } ?>

	<?php if( has_post_format( 'image' ) ) { ?>

		<?php echo anchorage_get_first_image(); ?>
	
	<?php } elseif( has_post_format( 'gallery' ) && get_post_gallery() ) { ?>
	
		<?php echo get_post_gallery(); ?>
	
	<?php } else { ?>
	
		<?php the_content( anchorage_get_more_text() ); ?>
	
	<?php } ?>

	<?php if( has_post_format( 'audio' ) ) { ?>
	
		<?php echo anchorage_get_first_media( 'audio' ); ?>
	
	<?php } ?>

	<?php if( has_post_format( 'video' ) ) { ?>
	
		<?php echo anchorage_get_first_media( 'video' ); ?>
	
	<?php } ?>

	<?php if( has_post_format( 'status' ) ) { ?>
	
		<em class='status-time'>

			<?php echo esc_html__( '&mdash;', 'anchorage'); ?>

			<?php echo get_the_time(); ?>
	
			<?php echo get_the_date(); ?>
	
		</em>
	
	<?php } ?>

	<?php if( has_post_format( 'quote' ) ) { ?>
	
		<span class='quote close-quote'>
			<?php echo esc_html__( '&rdquo;', 'anchorage'); ?>
		</span>
	
	<?php } ?>

	<?php if( is_singular() ) { ?>
	
		<?php echo anchorage_get_link_pages(); ?>
	
	<?php } ?>					

</section><!-- .entry-content -->