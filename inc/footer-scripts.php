<?php

/**
 * Icicle footer scripts.
 *
 * Scripts for minor UX improvements.
 *
 * @package WordPress
 * @subpackage icicle
 */

/**
 * Outputs jQuery for gallery caption show/hide.
 *
 * @since Icicle 1.0
 */
function icicle_gallery_captions() {
	?>

		<!-- Added by icicle to power gallery captions -->
		<script>
			
			jQuery(document).ready(function() {
				
				jQuery( '.gallery-item .gallery-caption' ).hide();
				jQuery( '.gallery-item' ).click( function( event ) {
					event.preventDefault();
					jQuery( this ).find( '.gallery-caption' ).fadeToggle();
				});
			});
			
		</script>

	<?php
}
add_action('wp_footer', 'icicle_gallery_captions');

/**
 * Output some jQuery to auto_empty form fields
 *
 * @since Icicle 1.0
 */
function icicle_auto_empty_forms() {		
	?>

		<!-- Added by icicle to power form field autoempty -->
		<script>		
	
			jQuery(document).ready(function() {
			
				jQuery('input[type="text"], input[type="email"], input[type="search"], textarea').focus(function() {
					if (this.value == this.defaultValue){
						this.value = '';
					}
					if(this.value != this.defaultValue){
						this.select();
					}
				});

				jQuery('input[type="text"], input[type="email"], input[type="search"], textarea').blur(function() {
					if (this.value == ''){
						this.value = this.defaultValue;
					}
				});

			});

		</script>

	<?php
	}
add_action( 'wp_footer', 'icicle_auto_empty_forms' );