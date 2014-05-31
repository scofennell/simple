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
		
	

		<footer id="blog-footer" class="<?php if(!is_single()){echo" accent-color ";} ?> outer-wrapper" role="contentinfo">
			<div id="blog-footer-inner-wrapper" class="inner-wrapper">

				<h2 class="blog-description shadowed">
						<a href="#blog-header" class="blog-description-link" title="Back to Top">
						<?php echo do_shortcode('[sjf_string_with_wraps string="desc"]'); ?>
					</a>
				</h2>		

				<div id="colophon" class="accent-font">
					<a class="colophon-link" href="mailto:scofennell@gmail.com.com">scofennell@gmail.com</a>
					<a class="colophon-link" href="http://www.scottfennell.com">Dehydrated</a>
					<a class="colophon-link" href="https://gist.github.com/scofennell/">Gist<a>
					<a class="colophon-link" href="https://github.com/scofennell">GitHub</a>
					<a class="colophon-link" href="https://plus.google.com/u/0/+scottfennell123/about?rel=author">G+</a>
				</div>

			</div>
		</footer><!-- #colophon -->

	</div><!-- #body_wrapper -->

	<?php wp_footer(); ?>
	
</body>
</html>