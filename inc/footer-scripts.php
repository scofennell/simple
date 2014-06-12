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
	
			jQuery( document ).ready( function( $ ) {
			
				$('input[type="text"], input[type="email"], input[type="search"], textarea').focus(function() {
					if (this.value == this.defaultValue){
						this.value = '';
					}
					if(this.value != this.defaultValue){
						this.select();
					}
				});

				$('input[type="text"], input[type="email"], input[type="search"], textarea').blur(function() {
					if (this.value == ''){
						this.value = this.defaultValue;
					}
				});

			});

		</script>

	<?php
	}
add_action( 'wp_footer', 'icicle_auto_empty_forms' );

function icicle_sub_menu_show_hide() {
	?>
	<script>		
		jQuery( document ).ready( function( $ ) {
			$( '.sub-menu' ).hide();
			$( '.toggle' ).click( function( event ) {
				event.preventDefault();
				$( this ).parent().children('.sub-menu').slideToggle();
				$( this ).toggleClass( 'open closed' );		
			});

		});
	</script>
	<?php
}
add_action( 'wp_footer', 'icicle_sub_menu_show_hide' );

function icicle_responsive_menu_show_hide() {
	?>
	<script>		
		jQuery( document ).ready( function( $ ) {
			$( '.responsive-menu-toggle, .responsive-menu-toggle .toggle' ).click( function( event ){
				$( this ).parent().find('.menu').slideToggle();
				$( this ).parent().find('.responsive-menu-toggle > .toggle').toggleClass( 'open closed' );
				if( $( this ).hasClass('toggle') ) {
					$( this ).toggleClass( 'open closed' );
				}
			});	
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'icicle_responsive_menu_show_hide' );

// smooth scroll internal links
function icicle_smooth_scroll() {
	?>
	<script>
		jQuery( document ).ready( function( $ ) {
			$( 'a[href*=#]:not([href=#])' ).click( function() {
				if ( location.pathname.replace( /^\//,'' ) == this.pathname.replace( /^\//,'' ) && location.hostname == this.hostname ) {
					var target = $(this.hash);
					target = target.length ? target : $( '[name=' + this.hash.slice(1) +']' );
					if ( target.length ) {
						$( 'html,body' ).animate({
							scrollTop: target.offset().top
						}, 'fast' );
						return false;
					}
				}
			});
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'icicle_smooth_scroll' );

function sjf_icicle_masonry( $container = '', $item = '' ) {
	
	$container = esc_attr( $container );
	$item = esc_attr( $item );
	
	?>
		<script>
			jQuery( window ).load( function() {
				//container = jQuery( "<?php echo $container; ?>" );
				container = document.querySelector( "<?php echo $container; ?>" );
				jQuery(container).masonry({
					itemSelector: "<?php echo $item; ?>",
					columnWidth: container.querySelector( "<?php echo $item; ?>" ),
					gutter: 0,
				});
			});
		</script>
	<?php

}