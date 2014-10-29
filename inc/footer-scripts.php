<?php

/**
 * anchorage footer scripts.
 *
 * Scripts for minor UX improvements.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */

/**
 * Outputs jQuery for gallery caption show/hide.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_gallery_captions' ) ) {
	function anchorage_gallery_captions() {
		?>

			<!-- Added by anchorage to power gallery captions -->
			<script>
				
				jQuery( document ).ready( function( $ ) {
					
					var item = $( '.gallery-item' );
					var caption = $( '.gallery-caption' );

					// Hide gallery captions.
					$( caption ).hide();

					// When we click a gallery item...
					$( item ).click( function( event ) {
						
						// Don't navigate the page.
						event.preventDefault();

						// Instead, fire the caption.
						$( this ).find( caption ).fadeToggle();
					});
				});
				
			</script>

		<?php
	}
}
add_action('wp_footer', 'anchorage_gallery_captions');

/**
 * Output some jQuery to auto_empty form fields
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_auto_empty_forms' ) ) {
	function anchorage_auto_empty_forms() {		
	?>

		<!-- Added by anchorage to power form field autoempty -->
		<script>		
	
			jQuery( document ).ready( function( $ ) {
			
				// Elements that we will empty.
				var empty = $( 'input[type="text"], input[type="email"], input[type="search"], textarea' );

				// When one of our elments is focused, if it still has the default value, strip it.
				$( empty ) . focus( function() {
					if ( this.value == this.defaultValue ) {
						this.value = '';
					}
				});

				// When one of our elements is blurred, if it's still empty, reset it to it's default value.
				$( empty ) . blur( function() {
					if ( this.value == '' ) {
						this.value = this.defaultValue;
					}
				});

			});

		</script>

	<?php
	}
}
add_action( 'wp_footer', 'anchorage_auto_empty_forms' );

/**
 * Outputs JS to hide the blog header and reveal it on click.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_offscreen_menu' ) ) {
	function anchorage_offscreen_menu() {
		?>

			<script>	
				jQuery( document ).ready( function( $ ) {
					
					// The blog header.
					var blogHeader = $( '#blog-header' );

					// The item that toggles the blog header.
					var toggle = $( '[href="#blog-header"]');
					
					// The classes that are toggled on/off the blog header.
					var blogHeaderClasses = 'two-of-five zero-width open closed';
					
					// The classes that are toggled on/off the toggle link.
					var toggleClasses = 'inverse-color one-eighty opened';
					
					// Stuff that's not the blog header.
					var notHeader = $( '#loop, .archive-header, #blog-footer' )

					// When we click the toggle, toggle the classes on/off.
					$( toggle ).click( function( event ) {
						event.preventDefault();
						$( this ).toggleClass( toggleClasses );
						$( blogHeader ).toggleClass( blogHeaderClasses );
					});

					// When we click something other than the blog header, if the blog header is open, close it.
					$( notHeader ).click( function() {

						if( $( '#blog-header' ).hasClass( 'open' ) ) {

							$( toggle ).toggleClass( toggleClasses );
							$( blogHeader ).toggleClass( blogHeaderClasses );			
					
						}
					});
						
				});
			</script>

		<?php
	}
}
add_action( 'wp_footer', 'anchorage_offscreen_menu' );

/**
 * Outputs JS to power a show/hide for nested menus and also nested category widgets.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_sub_menu_show_hide' ) ) {
	function anchorage_sub_menu_show_hide() {
		
		// Grab an arrow to give  visual indication of toggle state.
		$arrow = anchorage_get_arrow( 'down', array( 'toggle', 'toggle-hiders' ) );

		?>
		<script>		
			jQuery( document ).ready( function( $ ) {

				// This stuff will get hidden.
				var hiders = $( '.children, .sub-menu' );
				$( hiders ).hide();
				
				// Add an arrow before the stuff that is hidden.
				$( "<?php echo $arrow; ?>" ).insertBefore( hiders );
				$( '.toggle-hiders' ).click( function( event ) {

					event.preventDefault();
					
					// When the arrow is ckicked, the hidden elements are revealed and the arrow is rotated.
					$( this ).next( hiders ).slideToggle();
					$( this ).toggleClass( 'one-eighty' );

				});

			});
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'anchorage_sub_menu_show_hide' );

/**
 * Outputs JS to power a show/hide button for various template tags.
 *
 * @todo Figure out a way to refactor this into anchorage_sub_menu_show_hide().
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_toggle_script' ) ) {
	function anchorage_toggle_script(){
		?>
			<script>
				jQuery( document ).ready( function( $ ) {
					
					// Hide the stuff we want to hide.
					var hiders = $( '.anchorage-toggle-reveal' );
					$( hiders ).hide();
					
					// When we click the link that reveals stuff, reveal the stuff.
					$( '.anchorage-toggle-link' ).click( function( event ) {
						event.preventDefault();
						
						// Reveal the hiders.
						$( this ).next( hiders ).slideToggle();
						
						// Toggle classes on the link.
						$( this ).toggleClass( 'closed open' );
						
						// If the link contains an arrow, rotate it.
						$( this ).find( '.arrow' ).toggleClass( 'one-eighty' );
					});

				});
			</script>
		<?php
	}
}
add_action( 'wp_footer', 'anchorage_toggle_script' );

/**
 * Output JS to smooth scroll internal links.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_smooth_scroll' ) ) {
	function anchorage_smooth_scroll() {
		?>
		<script>

			// Once the document is ready...
			jQuery( document ).ready( function( $ ) {
				
				// For every link that contains a #, but not ones that are ONLY a #, when clicked...
				$( 'a[href*=#]:not([href=#])' ).click( function( event ) {

					// That link we just clicked -- if it points to the current site we're on right now...
					if ( location.pathname.replace( /^\//,'' ) == this.pathname.replace( /^\//,'' ) && location.hostname == this.hostname ) {
						
						// Get the part of the link after the #.
						var target = $( this.hash );

						// Find an html element on the page that has a name that's the same as the part of the link that we clicked, after the #
						target = target.length ? target : $( '[name=' + this.hash.slice(1) +']' );
						
						// If there is such an element...
						if ( target.length ) {
							
							// Animate the page...
							$( 'html, body' ).animate({
								
								// Such that the top of the page is at the top of the element we're skipping to.
								scrollTop: target.offset().top
							
							// Do it FAST!
							}, 'fast' );

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
}
add_action( 'wp_footer', 'anchorage_smooth_scroll' );

/**
 * Initiate masonry to columnize footer widgets.
 *
 * @param string $container A CSS selector for the masonry container.
 * @param string $item A CSS selector for the masonry item.
 * @todo  See if there is a way to do no-conflict mode with window.load.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_masonry' ) ) {
	function anchorage_masonry( $container = '', $item = '' ) {
		
		// A selector for the masonry wall.
		$container = esc_attr( $container );

		// A selector for the masonry bricks.
		$item = esc_attr( $item );
		
		?>
			<script>
				
				// We do onload to wait for images to finish loading.
				jQuery( window ).load( function() {
					
					container = document.querySelector( "<?php echo $container; ?>" );
				
					/**
					 * Masonry config.
					 * @see http://masonry.desandro.com/
					 */
					var atts = {
						itemSelector: "<?php echo $item; ?>",
						columnWidth: container.querySelector( "<?php echo $item; ?>" ),
						gutter: 0,
					}

					jQuery( container ).masonry( atts );

					/**
					 * New items toggling in and out of view could break masonry's absolute positioning,
					 * so we give them a moment and then redraw masonry.
					 *
					 * @todo Instead of relying on a time delay, register an event after the toggle is done.
					 */
					jQuery( container ).find( '.toggle' ).click( function() {
						setTimeout(
							"jQuery( container ).masonry();",
							500
						);
						
					});

				});
			</script>
		<?php
	}
}