<?php

/**
 * simple footer scripts.
 *
 * Scripts for minor UX improvements.
 *
 * @package WordPress
 * @subpackage simple
 */

/**
 * Outputs jQuery for gallery caption show/hide.
 *
 * @since simple 1.0
 */
function simple_gallery_captions() {
	?>

		<!-- Added by simple to power gallery captions -->
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
add_action('wp_footer', 'simple_gallery_captions');

/**
 * Output some jQuery to auto_empty form fields
 *
 * @since simple 1.0
 */
function simple_auto_empty_forms() {		
	?>

		<!-- Added by simple to power form field autoempty -->
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
add_action( 'wp_footer', 'simple_auto_empty_forms' );

function simple_sub_menu_show_hide() {
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
add_action( 'wp_footer', 'simple_sub_menu_show_hide' );

function simple_cat_widget_hide() {
	
	$arrow = simple_arrow( 'down', array( 'toggle', 'cat-widget-toggle', 'closed' ) );

	?>
	<script>		
		jQuery( document ).ready( function( $ ) {
			$( '.widget .children' ).hide();
			$("<?php echo $arrow; ?>").insertBefore('.widget .children');
			//$( '.widget .children' ).parent().find( ' > a:first-child ' ).append( "<?php echo $arrow; ?>" );
			$( '.widget .children' ).parent().find( '.toggle' ).click( function( event ) {
				event.preventDefault();
				$( this ).parent().find( ' > .children' ).slideToggle();
				$( this ).toggleClass( 'closed open' );
			});
			

		});
	</script>
	<?php
}
add_action( 'wp_footer', 'simple_cat_widget_hide' );


function simple_responsive_menu_show_hide() {
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
add_action( 'wp_footer', 'simple_responsive_menu_show_hide' );

// smooth scroll internal links
function simple_smooth_scroll() {
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
add_action( 'wp_footer', 'simple_smooth_scroll' );

function simple_masonry( $container = '', $item = '' ) {
	
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