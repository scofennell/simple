<?php if( ! has_post_format( 'aside' ) && ! has_post_format( 'status' ) ) { ?>

	<header class="entry-header content-holder">

		<?php if (is_page() ) { ?>

			<?php echo simple_page_ancestors(); ?>

		<?php } ?>

		<h1 class="entry-title">
		
			<?php echo simple_get_post_format(); ?>

			<?php if ( has_post_format( 'link' ) ) { ?>

				<a href="<?php echo get_url_in_content( get_the_content() ); ?>" rel="bookmark">					
			
			<?php } elseif ( ! is_singular() ) { ?>
			
				<a href="<?php the_permalink(); ?>" rel="bookmark">					
			
			<?php } ?>
		
			<?php if ( is_home() && is_sticky() ) { ?>
			
				<?php echo esc_html__('Sticky:', 'simple'); ?>
			
			<?php } ?>

			<?php the_title(); ?>
	
			<?php if ( ! is_singular() || has_post_format( 'link' ) ) { ?>
			
				</a>
			
			<?php } ?>

		</h1>

		<?php echo simple_entry_cats(); ?>

		<?php /* if its a post format: image and it does have an image in the content, skip the post thumbnail business. */ ?>

		<?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
	
			<?php if( ! has_post_format( 'image' ) &&  ! ( simple_get_first_image() ) ){ ?>

				<div class="entry-thumbnail">

					<?php the_post_thumbnail(); ?>
				
				</div>

			<?php } ?>

		<?php } ?>

	</header><!-- .entry-header -->

<?php }