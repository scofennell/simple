<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>
		
	

		<footer id="blog-footer" class="inverse-color outer-wrapper" role="contentinfo">
			<div id="blog-footer-inner-wrapper" class="inner-wrapper">

				<h2 class="blog-description shadowed">
					<a href="#blog-header" class="blog-description-link" title="Back to Top">
						<?php bloginfo( 'description' ); ?>
					</a>
				</h2>		

				<?php if ( is_active_sidebar( 'footer-widgets' ) ) { ?>
					<?php wp_enqueue_script( 'masonry'  ); ?>
					<?php sjf_icicle_masonry( '.footer-widgets', '.footer-widget' ); ?>
					<aside id="footer-widgets" class="clear widgets footer-widgets" role="complementary">
						<?php dynamic_sidebar( 'footer-widgets' ); ?>
					</aside>
				<?php } ?>

				<?php
					echo icicle_menu( 'secondary-menu' );
				?>

			</div>
		</footer><!-- #colophon -->

	</div><!-- #body_wrapper -->

	<?php wp_footer(); ?>
	
</body>
</html>