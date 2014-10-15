<?php

/**
 * anchorage footer scripts.
 *
 * Scripts for minor UX improvements.
 *
 * @package WordPress
 * @subpackage anchorage
 */

/**
 * Outputs jQuery for gallery caption show/hide.
 *
 * @since anchorage 1.0
 */
function anchorage_gallery_captions() {
	?>

		<!-- Added by anchorage to power gallery captions -->
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
add_action('wp_footer', 'anchorage_gallery_captions');

/**
 * Output some jQuery to auto_empty form fields
 *
 * @since anchorage 1.0
 */
function anchorage_auto_empty_forms() {		
	?>

		<!-- Added by anchorage to power form field autoempty -->
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
add_action( 'wp_footer', 'anchorage_auto_empty_forms' );

function anchorage_offscreen_menu() {
	?>
	<script>		
		jQuery( document ).ready( function( $ ) {
			$( '.toggle' ).click( function( event ) {
				event.preventDefault();
				$( this ).toggleClass( 'inverse-color one-eighty opened' );
				var menu = ( $( this ).attr( 'href' ) );
				menu = menu.replace( '#', '' );
				$( '#' + menu ).toggleClass( 'two-of-five zero-width open closed' );
			});

		});
	</script>
	<?php
}
add_action( 'wp_footer', 'anchorage_offscreen_menu' );

function anchorage_sub_menu_show_hide() {
	
	$arrow = anchorage_arrow( 'down', array( 'toggle', 'toggle-hiders' ) );

	?>
	<script>		
		jQuery( document ).ready( function( $ ) {

			var hiders = $( 'ul.children, ul.sub-menu' );

			$( hiders ).hide();
			
			$( "<?php echo $arrow; ?>" ).insertBefore( hiders );
			
			$( '.toggle-hiders' ).click( function( event ) {

				event.preventDefault();
				$( this ).next( hiders ).slideToggle();
				$( this ).toggleClass( 'one-eighty' );

			});

		});
	</script>
	<?php
}
add_action( 'wp_footer', 'anchorage_sub_menu_show_hide' );

/*
function anchorage_responsive_menu_show_hide() {
	?>
	<script>		
		jQuery( document ).ready( function( $ ) {
			$( '.responsive-menu-toggle, .responsive-menu-toggle .toggle' ).click( function( event ){
				event.preventDefault();

    			$('html, body').animate({
        			scrollTop: $(this).offset().top
    			});

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
add_action( 'wp_footer', 'anchorage_responsive_menu_show_hide' );
*/

/**
 * Output JS to smooth scroll internal links
 */
function anchorage_smooth_scroll() {
	?>
	<script>

		// Once the document is ready...
		jQuery( document ).ready( function( $ ) {
			
			// For every link that contains a #, but not ones that are ONLY a #, when clicked...
			$( 'a[href*=#]:not([href=#])' ).click( function() {
				
				// That link we just clicked -- if it points to the current site we're on right now...
				if ( location.pathname.replace( /^\//,'' ) == this.pathname.replace( /^\//,'' ) && location.hostname == this.hostname ) {
					
					// Get the part of the link after the #.
					var target = $(this.hash);

					// Find an html element on the page that has a name that's the same as the part of the link that we clicked, after the #
					target = target.length ? target : $( '[name=' + this.hash.slice(1) +']' );
					
					// If there is such an element...
					if ( target.length ) {
						
						// Animate the page...
						$( 'html,body' ).animate({
							
							// Such that the top of the page is at the top of the element we're skipping to.
							scrollTop: target.offset().top
						
						// Do it FAST!
						}, 'fast' );

						// And don't reload the page.
						return false;
					
					// End if there was an element on that page that matched the link hash.
					}
				
				// End if the link points to the site we're on right now.	
				}
			
			// End for each link that has a #, when clicked.
			});
		
		// End once the document is ready function.
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'anchorage_smooth_scroll' );

function anchorage_masonry( $container = '', $item = '' ) {
	
	$container = esc_attr( $container );
	$item = esc_attr( $item );
	
	?>
		<script>
			
			jQuery( window ).load( function() {
				
				container = document.querySelector( "<?php echo $container; ?>" );
			
				var atts = {
					itemSelector: "<?php echo $item; ?>",
					columnWidth: container.querySelector( "<?php echo $item; ?>" ),
					gutter: 0,
				}

				jQuery( container ).masonry( atts );

				/**
				 * New items toggling in and out of view could break masonry's absolute positioning,
				 * so we give them a moment and then redraw masonry.
				 */
				jQuery(container).find( '.toggle' ).click( function() {
					setTimeout(
						"jQuery(container).masonry();",
						500
					);
					
				});

			});
		</script>
	<?php

}