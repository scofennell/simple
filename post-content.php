<section itemprop="articleBody" class="entry-content editable-content content-holder">
	
	<?php if( has_post_format( 'quote' ) ) { ?>

		<span class='quote open-quote'>
			<?php echo esc_html__( '&ldquo;', 'simple'); ?>
		</span>
	
	<?php } ?>

	<?php if( has_post_format( 'image' ) ) { ?>

		<?php echo simple_get_first_image(); ?>
	
	<?php } elseif( has_post_format( 'gallery' ) && get_post_gallery() ) { ?>
	
		<?php echo get_post_gallery(); ?>
	
	<?php } else { ?>
	
		<?php the_content('<span class="inverse-shadow">'.esc_html__( 'Read More&hellip;', 'simple' ).'</span>'); ?>
	
	<?php } ?>

	<?php if( has_post_format( 'audio' ) ) { ?>
	
		<?php echo simple_get_first_media( 'audio' ); ?>
	
	<?php } ?>

	<?php if( has_post_format( 'video' ) ) { ?>
	
		<?php echo simple_get_first_media( 'video' ); ?>
	
	<?php } ?>

	<?php if( has_post_format( 'status' ) ) { ?>
	
		<em class='status-time'>

			<?php echo esc_html__( '&mdash;', 'simple'); ?>
	
			<?php echo get_the_time(); ?>
	
			<?php echo get_the_date(); ?>
	
		</em>
	
	<?php } ?>

	<?php if( has_post_format( 'quote' ) ) { ?>
	
		<span class='quote close-quote'>

			<?php echo esc_html__( '&rdquo;', 'simple'); ?>

		</span>
	
	<?php } ?>

	<?php if( is_singular() ) { ?>
	
		<?php echo simple_link_pages(); ?>
	
	<?php } ?>					

</section><!-- .entry-content -->