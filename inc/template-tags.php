<?php

/**
 * anchorage template tags.
 *
 * These functions are used in template files susch as header.php or index.php.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */

/**
 * Outputs the opening HTML tag, to include classes for ie in general, and ie 7 through 9.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_the_html_classes' ) ) {
	function anchorage_the_html_classes() {
		?>
			<!--[if IE 7]>
				<html class="ie ie7" <?php language_attributes(); ?>>
			<![endif]-->
			<!--[if IE 8]>
				<html class="ie ie8" <?php language_attributes(); ?>>
			<![endif]-->
			<!--[if IE 9]>
				<html class="ie ie9" <?php language_attributes(); ?>>
			<![endif]-->
			<!--[if !(IE 7) | !(IE 8) | !(IE 9)  ]><!-->
				<html <?php language_attributes(); ?>>
			<!--<![endif]-->
		<?php
	}
}

/**
 * Get the read-more text for our theme.  Used in the_content().
 * 
 * @return  The read-more text for our theme.
 * 
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_more_text' ) ) {
	function anchorage_get_more_text() {
		
		$visible_text = esc_html__( 'more&hellip;', 'anchorage' );
		
		$post_title = esc_html( get_the_title() );
		$screen_reader_text = "<span class='screen-reader-text'>$post_title</span>";
		
		$out = "<span class='inverse-shadow read-more'> $visible_text $screen_reader_text </span>";
		return $out;
	}
}

/**
 * Get a skip-to-content link.  Used in the_content().
 *
 * @param string $skip_to The HTML ID for the element to which we'll skip.
 * @return  A skip-to-content link.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_skip_to_content_link' ) ) {
	function anchorage_get_skip_to_content_link( $skip_to = '#loop' ) {
	
		$skip = esc_html__( 'Skip to content', 'anchorage' );
		$skip_to = esc_attr( $skip_to );
		$out = "<a class='screen-reader-text skip-link' href='$skip_to'>$skip</a>";
		return $out;
	}
}

/**
 * Get a WordPress custom menu.
 *
 * @param  string $which_menu The theme location for a menu.
 * @param  string $menu_class A css class for the menu.
 * @return string A WordPress custom menu.
 * 
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_menu' ) ) {
	function anchorage_get_menu( $which_menu, $menu_class = '' ) {

		if ( ! has_nav_menu( $which_menu ) ) { return false; }

		$args = array(
			'theme_location'  => $which_menu,
			'container'       => false,
			'echo'            => false,
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,

		);

		$menu = wp_nav_menu( $args );

		$menu_class = sanitize_html_class( $which_menu );

		$out = "<nav id='$menu_class' class='$menu_class'>$menu</nav>";

		return $out;

	}
}

/**
 * Output the header menu for our theme along with a toggle arrow to show/hide it.
 *
 * @param string which_menu The theme location for the menu.
 * @param string $menu_class A CSS class for the menu.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_header_menu' ) ) {
	function anchorage_header_menu( $which_menu, $menu_class = '' ) {

		$menu = anchorage_get_menu( $which_menu, $menu_class = '' );

		// Grab the url of the homepage and the blogname to build a link to the homepage.
		$home_href = esc_url( home_url() );
		$blog_title = wp_kses_post( get_bloginfo( 'name' ) );
		$home_link = "<h1 class='blog-title inverse-color shadowed'><a href='$home_href'>$blog_title</a></h1>";

		// Start the menu.
		echo "
			<header id='blog-header' class='marquee zero-width closed inverse-color'>
				$home_link
				$menu
		";

		// Grab the header widget area.
		if ( is_active_sidebar( 'header-widgets' ) ) {
			echo '
				<aside id="header-widgets" class="widgets content-holder header-widgets inverse-color" role="complementary">
			';
					dynamic_sidebar( 'header-widgets' );
			echo '
				</aside>
			';
		}

		// Close the menu.
		echo '
			</header>
		';

		// Grab the toggle arrow.
		echo anchorage_get_arrow( 'left', array( 'toggle', 'shadowed', 'primary-menu-toggle' ), '#blog-header' );

	}
}

/**
 * Returns a WordPress search form.
 *
 * Accepts arguments to inject CSS classes into the form, which this theme uses 
 * in order to comply with SMACCS.  Passing dynamic class values for each 
 * instance would not be possible with the normal use of searchform.php.
 * 
 * @param  array $form_class         CSS Classes for the form.
 * @param  array $search_input_class CSS Classes for the search input.
 * @return string A search form.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_search_form' ) ) {
	function anchorage_get_search_form( $form_classes = array(), $search_input_classes = array( 'search-field' ), $header = '' ) {
		
		// An array of CSS classes for the search form.
		$form_classes = array_map( 'sanitize_html_class', $form_classes );
		$form_classes_string = implode( ' ', $form_classes );
		
		// An array of CSS classes for the search input.
		$search_input_classes = array_map( 'sanitize_html_class', $search_input_classes );
		$search_input_string = implode( ' ', $search_input_classes );
		
		// Grab the search term to use as a watermark.
		$placeholder = esc_attr__( 'Search', 'anchorage' );
		if( isset( $_GET['s'] ) ) {
			$placeholder = esc_attr( $_GET['s'] );
		}

		$action = esc_url( home_url( '/' ) );
		$search_for = esc_html__( 'Search for:', 'anchorage' );
		$search_for_attr = esc_attr__( 'Search for:', 'anchorage' );
		$submit = esc_html__( 'Submit', 'anchorage' );

		if( ! empty( $header ) ) {
			$header = esc_html( $header );
			$header = "<h3 class='search-header'>$header</h3>";
		}

		$out ="
			<form action='$action' class='$form_classes_string search-form' method='get' role='search'>
				$header
				<label for='s'><span class='screen-reader-text'>$search_for</span></label>
				<input id='s' type='search' title='$search_for_attr' name='s' value='$placeholder' class='search-field $search_input_string'>
				<input type='submit' value='$submit' class='screen-reader-text search-submit button'>
			</form>
		";

		return $out;

	}
}

/**
 * Return a string to denote the post format.
 *
 * @return string A string to denote the post format, or false if there is no post format.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_post_format' ) ) {
	function anchorage_get_post_format( $arrow_direction = false ){
		
		// Grab the post format.
		global $post;
		$post_id = absint( $post -> ID );
		$format = get_post_format( $post_id );
		$format = esc_html( $format );

		if ( empty( $format ) ) {
			return false;
		}

		// Grab an arrow.
		$arrow = '';
		if( $arrow_direction ) {
			$arrow = anchorage_get_arrow( $arrow_direction, array( 'post-format-arrow' ), false );
		}
		
		$out = "<span class='post-format-label'>$format$arrow</span>";

		return $out;

	}
}

/**
 * Return an HTML img tag for the first image in a post content. Used to draw
 * the content for posts of the "image" format.
 *
 * @return string An HTML img tag for the first image in a post content.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_first_image' ) ) {
	function anchorage_get_first_image() {
		
		// Expose information about the current post.
		global $post;
		
		// We'll trap to see if this stays empty later in the function.
		$src = '';
		
		// Grab all img src's in the post content
		$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post -> post_content, $matches );
		
		// Grab the first img src returned by our regex.
		if( ! isset ( $matches[1][0] ) ) { return false; }
		$src = $matches[1][0];

		// Sanitize for output
		$src = esc_url( $src );

		// Make sure there's still something worth outputting after sanitization.
		if( empty( $src ) ) { return false; }
		
		// Grab and sanitize the post title as an alt.
		$alt = esc_attr( $post -> post_title );

		$out = "<img class='first-image' alt='$alt' src='$src'>";

		// Trap to see if the post has a caption shortcode
		$caption = '';
		if( has_shortcode( $post -> post_content, 'caption' ) ) {
			
			$output = preg_match_all( '/caption=(.*)\]/smU', $post -> post_content, $matches );
			if( $output ) {
				$caption = $matches[1][0];
				$caption = trim( $caption, '"' );
				$caption = trim( $caption, "'" );
			} else {

				// Grab the content of the first caption
				preg_match_all( '/\[caption\s?.*\](.*)\[\/caption\]/smU', $post -> post_content, $matches );
				
				$caption = strip_tags( $matches[1][0], '<a><b><br><em><i><p><span><strong>' );
			}
			
		}

		// Link the image to the first url before the image.
		$href = '';
		$content_before_first_image = explode( '<img', $post->post_content );
		$content_before_first_image = $content_before_first_image[0];
		
		$href = get_url_in_content( $content_before_first_image );

		if( ! empty( $content_before_first_image ) ) { $out = "<a href='$href'>$out</a>"; }

		// If there is a caption, return a figure.
		if( ! empty( $caption ) ) {
			$out = "
				<figure class='first-image-figure'>
					$out
					<figcaption class='first-image-caption'>$caption</figcaption>
				</figure>
			";
		}

		// If the file is on the server, grab the exif.
		$path = anchorage_file_is_on_server( $src );
		if( ! empty( $path ) ) { 
			$path = anchorage_file_is_on_server( $src );
			$exif = anchorage_get_media_meta( $path, 'image' );
			$out .= $exif;
		}

		return $out;

	}
}

/**
 * Given a chunk of content, grab the media meta for the first piece of media in that content.
 * 
 * @param  string $type The type of media for which we're sniffing.
 * @return string The media meta for the first piece of media in content.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_first_media' ) ) {
	function anchorage_get_first_media( $type ){
		
		// We only sniff for audio or video.
		if( ( $type != 'audio' ) && ( $type != 'video' ) ) { return false; }

		global $post;

		$content = $post -> post_content;

		//  Look for an embed in the content.
		$embed = get_media_embedded_in_content( $content );
		
		//  If there is a url in the embed, use it.
		if ( get_url_in_content( $embed ) ) {
			$href = get_url_in_content( $embed );
		
		// If not, see if there is a url in the content.
		} else {
			$href = get_url_in_content( $content );
		}

		// Do we own this file?
		$path = anchorage_file_is_on_server( $href );	
		if( ! $path ) { return false; }
		
		// If so, grab the media meta.
		return anchorage_get_media_meta( $path, $type );

	}
}

/**
 * Get links to paginated sub pages.
 *
 * @return string Links to paginated sub-pages.
 *
 * @since anchorage 1.0
 */
if ( ! function_exists( 'anchorage_get_link_pages' ) ) {
	function anchorage_get_link_pages() {
		$pages = esc_html__( 'Pages:', 'anchorage' );
		$args = array(
			'before'           => "<nav class='paging-navigation numeric-pagination inverse-font inverse-color button link-pages'>$pages",
			'after'            => '</nav>',
			'next_or_number'   => 'number',
			'echo'             => 0,
		);
		
		return wp_link_pages( $args );
	}
}

/**
 * Get navigation links to next/previous post when applicable.
 *
 * @return string Navigation links to next/previous post when applicable.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_post_nav' ) ) {
	function anchorage_get_post_nav() {
		global $post;

		$out = '';

		if( get_previous_post_link() ) {
			$arrow = anchorage_get_arrow( 'right', array(), false );
			$prev_post_link = get_previous_post_link( "%link", "%title" . $arrow );
			$out .= "<span class='inverse-color button button-minor next next-post'>$prev_post_link</span>";
		}

		if( get_next_post_link() ) {
			$arrow = anchorage_get_arrow( 'left', array(), false );
			$next_post_link = get_next_post_link( "%link", $arrow . "%title" );
			$out .= "<span class='inverse-color button button-minor prev previous-post'>$next_post_link</span>";
		}

		if( empty( $out ) ) { return false; }

		$post_navigation = esc_html__( 'Post navigation', 'anchorage' );

		$out = "
			<nav class='inverse-font paging-navigation post-navigation clear' role='navigation'>
				<h1 class='screen-reader-text'>$post_navigation</h1>
				$out
			</nav>
		";
		return $out;	
	}
}

/**
 * Get navigation to next/previous set of posts when applicable.
 *
 * @return string Navigation to next/previous set of posts when applicable.
 *
 * @since anchorage 1.0
 */
if ( ! function_exists( 'anchorage_get_paging_nav' ) ) {
	function anchorage_get_paging_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query -> max_num_pages < 2 ) { return false; }

		$out = "";

		if( get_previous_posts_link() ) {
			$arrow = anchorage_get_arrow( 'left', array(), false );
			$prev_link = get_previous_posts_link( $arrow . '&nbsp;' . esc_html__( 'Newer Posts', 'anchorage' ) );
			$out .= "<span class='inverse-color button button-minor prev previous-posts'> $prev_link </span>";
		}

		if( get_next_posts_link() ) {
			$arrow = anchorage_get_arrow( 'right', array(), false );
			$next_link = get_next_posts_link( esc_html__( 'Older Posts', 'anchorage' ) . $arrow );
			$out .= "<span class='inverse-color button button-minor next next-posts'> $next_link </span>";
		}

		if( empty( $out ) ) { return false; }

		$posts_navigation = esc_html__( 'Posts navigation', 'anchorage' );

		$out = "
			<nav class='inverse-font paging-navigation posts-navigation clear' role='navigation'>
				<h1 class='screen-reader-text'>$posts_navigation</h1>
				$out
			</nav>
		";

		return $out;

	}
}

/**
 * Get an HTML header for the current archive page.
 *
 * @return string An HTML header for the current archive page.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_archive_header' ) ) {
	function anchorage_get_archive_header() {
		
		if( is_single() || is_page() || is_singular() ) { return false; }

		// Grab the number of posts found by the current query.
		global $wp_query;
		$post_count ='';
		if ( isset ( $wp_query -> found_posts ) ) {
			$found_posts = $wp_query -> found_posts;
			$post_count = sprintf( _n( '1 post', "%s posts", $found_posts, 'anchorage' ), $found_posts );
		}

		// If it was a search query, grab the search form.
		$search = '';
		if ( is_search() ){

			$query = get_search_query();
			
			// If the search query returned results, announce how many and add a search form.
			if( have_posts() ) {

				$message = sprintf( esc_html__( 'There are %s search results for %s', 'anchorage' ), $found_posts, "<mark class='archive-term'>$query</mark>" );
				$search = anchorage_get_search_form( array(), array() );
			
			// If there were no search results, say so.  No need to add a search form, as our no-posts handler will add one.
			} else {
				$message = sprintf( esc_html__( 'No results found for %s', 'anchorage' ), "<mark class='archive-term'>$query</mark>" );	
			}
			$class = 'search';

		} elseif( is_category() ) {

			$title = single_cat_title( '', false );
			$message = "<mark class='archive-term'>$title</mark>: $post_count";
			$class = 'category';
		} elseif( is_tag() ) {
			$title = single_tag_title( '', false );
			$message = "<mark class='archive-term'>$title</mark>: $post_count";
			$class = 'tag';
		} elseif( is_year() ) {
			$title = get_the_date( 'Y' );
			$message = "<mark class='archive-term'>$title</mark>: $post_count";
			$class = 'year';
		} elseif( is_month() ) {
			$title = get_the_date( 'F Y' );
			$message = "<mark class='archive-term'>$title</mark>: $post_count";
			$class = 'month';
		} elseif( is_day() ) {
			$title = get_the_date();
			$message = "<mark class='archive-term'>$title</mark>: $post_count";
			$class = 'day';
		} elseif( is_author() ) {
			$title = get_the_author();
			$message = "<mark class='archive-term'>$title</mark>: $post_count";
			$class = 'author';
		} elseif( is_404() ) {
			$message = esc_html__( 'Your page could not be found.', 'anchorage' );
			$class = '404';
		} else {
			$message = esc_html__( 'Archives:', 'anchorage' ) . " $post_count";
			$class = 'default';
		}

		// Grab a message to denote the page number we're on.
		$paged = '';
		if ( is_paged() ) {
			$paged = get_query_var( 'paged' );
			$paged = absint( $paged );
			if( $paged > 1 ) {
				$page = esc_html__('Page');
				$paged = " &mdash; $page $paged";
			}
		}

		$header_class = "archive-header-$class";
		$title_class = "archive-title-$class";
		
		$out = "
			<header class='archive-header mild-contrast-color content-holder accent-block $header_class'>
				<h1 class='archive-title $title_class'>
					$message $paged
				</h1>
				$search
			</header>
		";

		return $out;

	}
}

/**
 * Get an apology message and some navigation for 404's and empty archive pages.
 * 
 * @return string An apology message and some navigation for 404's and empty archive pages.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_no_posts' ) ) {
	function anchorage_get_no_posts() {

		$find_your_way = esc_html__( 'Find your way by searching:', 'anchorage' );
		
		$search_header  = "$find_your_way";
		$out .= anchorage_get_search_form( array( 'no-posts-searchform' ), array ( 'no-posts-search-input' ), $search_header );

		// The jump menus are a lot of querying, so see if we have it as a transient first.
		$jump = get_transient( 'anchorage_get_jump_menus' );
		
		// If we don't have it, build it.
		if ( empty( $jump ) ) {
			
			$jump  = anchorage_get_jump_nav( 'category' );
			$jump .= anchorage_get_jump_nav( 'tag' );
			$jump .= anchorage_get_jump_nav( 'author' );
			$jump .= anchorage_get_jump_nav( 'month' );
			$jump .= anchorage_get_jump_nav( 'page' );

			if( ! empty( $jump ) ) { 
		
				$or_browse = esc_html__( 'Or browse by archive:', 'anchorage' );

				$jump = "
					<form class='jump-nav-wrapper'>
						<h3 class='no-posts-header'>$or_browse</h3>
						$jump
					</form>
				";
			}

			// Now that we have it, save it for next time.
			set_transient( 'anchorage_get_jump_menus', $jump, DAY_IN_SECONDS );

		}

		$br = anchorage_get_hard_rule();

		$out .= $jump;

		$out = "
			<div class='no-posts-wrapper accent-block mild-contrast-color'>$out</div>
		";

		return $out;
	}
}

/**
 * Get a breadcrumb nav.
 * 
 * @return string A breadcrumb nav.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_breadcrumbs' ) ) {
	function anchorage_get_breadcrumbs() {

		$out = '';

		/**
		 * Determine what sort of page view this is.
		 * Based on that, we might grab parent posts, parent terms, or just a home link.
		 */

		// If we're viewing a term archive, denote that resource type.
		if( anchorage_is_termish() ) {

			$resource_type = 'taxonomy';
			$object_type = get_queried_object() -> taxonomy;
			$object_id = get_queried_object() -> term_id;

		// Or, if we're viewing a single post or page, get more specific.
		} elseif( anchorage_is_singlish() ) {
			
			$object_id = get_the_ID();
			$object_type = get_post_type();
			$post_type_obj = get_post_type_object( $object_type );

			// If it's a hierarchial post type, that's our resource type.
			if( is_post_type_hierarchical( $object_type ) ) {
				$resource_type = 'hierarchical_post_type';
			
			// If it's a custom post type, that's our resource.
			} elseif( anchorage_is_custom_post_type( $object_type ) ) {
				$resource_type = 'flat_custom_post_type';

			} else {
				$resource_type = 'flat_post_type';
			}

		// Of if it's a post type archive, that's our resource.
		} elseif( is_post_type_archive() ) {
			
			$resource_type = 'post_type_archive';
			$object_type = get_post_type();
			$post_type_obj = get_post_type_object( $object_type );

		}

		// Start an array to hold the breadcrumbs, starting with a string to denote the homepage.
		$crumb_array = array( 'home' );

		// If it's a tax or nested post type, we can use the get_ancestors() function.
		if( ( $resource_type == 'taxonomy' ) || ( $resource_type == 'hierarchical_post_type' ) ) {	
			$get_ancestors = get_ancestors( $object_id, $object_type );
		
		// If it's a flat custom post type, we'll add a string to denote that.
		} elseif( $resource_type == 'flat_custom_post_type' ) {
			$crumb_array []= 'post_type_archive';
		
		// And if it's a flat standard post type, again we can use get_ancestors(), but we'll do so with the first category. 
		} elseif( $resource_type == 'flat_post_type' ) {	
			$post_categories = get_the_category(); 
			$first_category = $post_categories[0];
			$get_ancestors = get_ancestors( $first_category -> term_id, 'category' );
			
			// We also have to add that first category in at the end.
			$get_ancestors []= $first_category -> term_id;

		}
		
		// If we did a call to get_ancestors(), we want to remove empty elements, reverse the order, and merge it in with the rest of the breadcrumbs.
		if( isset( $get_ancestors ) ) {
			$get_ancestors = array_filter( $get_ancestors );
			$get_ancestors = array_reverse( $get_ancestors );
			$crumb_array = array_merge( $crumb_array, $get_ancestors );
		}

		// Add a string to denote the current page.
		$crumb_array []= 'current';

		/**
		 * Let's see if the current view has child terms or child posts.
		 */
		$children = '';

		// If we are browsing a term, look for child terms.
		if( $resource_type == 'taxonomy' ) {
			
			$get_children = get_term_children( $object_id, $object_type );
		
			// If we find child terms, build them into an array.
			if( is_array( $get_children ) ) {
				foreach( $get_children as $c ) {
					
					$child = array();
					$term = get_term( $c, $object_type );
					$title = $term -> name;
					$href = get_term_link( $term -> term_id, $object_type );
					$child []= $title;
					$child []= $href;
					$children []= $child;
				
				}
			}

		// If we are browsing a post, look for child posts.
		} elseif( $resource_type == 'hierarchical_post_type' ) {
			
			$args = array(
				'post_parent' => $object_id,
				'post_type'   => $object_type,
				'posts_per_page' => get_option( 'posts_per_page' ),
				'post_status' => 'publish'
			); 
			$get_children = get_children( $args );
			
			// If we found some child posts, build them into an array.
			if( is_array( $get_children ) ) {
				foreach( $get_children as $c ) {
					$child = array();
					$title = get_the_title( $c -> ID );
					$href = get_permalink( $c -> ID );
					$child []= $title;
					$child []= $href;
					$children []= $child;
				}
			}
		}
		
		// If we had some children, add that to the crumbs.
		if( ! empty( $get_children ) ) {
			$crumb_array []= 'children';
		}

		// We'll put an arrow between each breadcrumb.
		$arrow = anchorage_get_arrow( 'right', array( 'breadcrumbs-arrow' ), false );

		// Grab a count of the ancestors so we know when to stop adding arrows.
		$count = count( $crumb_array );

		// For each parent, output a breacrumb link, to include microformat.		
		$i = 0;
		foreach ( $crumb_array as $crumb ) {

			$crumb_link = '';
			$crumb_title = '';
			$this_crumb = '';

			$i++;

			// Provide a link to the home page.
			if( $crumb == 'home' ) {

				$crumb_title = esc_html__( 'Home', 'anchorage' );
				$crumb_link = home_url();

			// Provide the title of the current page, unlinked.
			} elseif( $crumb == 'current' ) {

				if( anchorage_is_singlish() ) {
					$crumb_title = get_the_title();
				
				} elseif( is_404() ) {
					$crumb_title = esc_html__( '404', 'anchorage' );
				
				} elseif( is_author() ) {
					$crumb_title = get_the_author();				
				
				} elseif( is_search() ) {
					$crumb_title = esc_html__( 'Search', 'anchorage' );
				
				} elseif( $resource_type == 'post_type_archive' ) {

					$crumb_title = $post_type_obj -> labels -> name;

				} else {
					$term = get_queried_object();
					$crumb_title = wp_kses_post( $term -> name );
				}

			// If this is the crumb for child links, output each child, comma-seperated.
			} elseif( $crumb == 'children' ) {
				if( is_array( $children ) ) {
					
					// Grab a comma.
					$comma = esc_html__( ', ', 'anchorage' );
					
					$child_count = count( $children );
					$child_i = 0;
					foreach( $children as $child ) {
						
						$child_i++;
						$crumb_title = $child[0];
						$crumb_link = $child[1];
						$this_crumb .= anchorage_get_breadcrumb( $crumb_title, $crumb_link );
						
						// If we're not at the end, add a comma.
						if( $child_count != $child_i ) {
							$this_crumb .= $comma;
						}
					}
				}

			// If this breadcrumb is not for one of our special strings, dig into it and output the correct data.
			} else {

				// If it's a taxonomy resource or a flat post type resource, then the breadcrumbs are term links.
				if( ( $resource_type == 'taxonomy' ) || ( $resource_type == 'flat_post_type' ) ) {
				
					$obj = get_category( $crumb );
					$crumb_title = $obj -> name;
					$crumb_link = get_term_link( $obj -> term_id, 'category' );

				// If it's a flat custom post type, just link back to the post type archive.
				} elseif( $resource_type == 'flat_custom_post_type' ) {
	
					$crumb_title = $post_type_obj -> labels -> name;
					$crumb_link = get_post_type_archive_link( $object_type );

				// For anything else, grab the title and permalink.
				} else {

					$crumb_title = get_the_title( $crumb );
					$crumb_link =  get_permalink( $crumb );
				
				}
	
			}

			if( empty( $this_crumb ) ) {
				$this_crumb = anchorage_get_breadcrumb( $crumb_title, $crumb_link );
			}

			$out .= $this_crumb;

			// Unless we're at the end of the crumbs, add an arrow.
			if( $i != $count ) {
				$out .= $arrow;
			}

		}
	
		if( empty ( $out ) ) { return false; }

		$class = "breadcrumbs breadcrumbs-$resource_type";

		$out .= anchorage_get_hard_rule();

		// Wrap the breadcrumbs.
		$out = "
			<nav itemscope itemtype='http://data-vocabulary.org/Breadcrumb' rel='navigation' class='breadcrumbs breadcrumbs-$resource_type'>
				$out
			</nav>
		";
	
		return $out;

	}
}

/**
 * Get the byline for the current post.
 *
 * @return string The byline for the current post.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_entry_byline' ) ) {
	function anchorage_get_entry_byline() {
		
		// The date on which the post was published.
		$date = anchorage_get_entry_date();
		
		// The display name of the author.
		$display_name = get_the_author();
		
		// The website field in the user bio, if it exists.
		$href = esc_url( get_the_author_meta( 'user_url' ) );
		
		// If there is no website field, grab the archives page for the author.
		if( empty( $href ) ) {
			$href = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
		}

		$by = sprintf( esc_html__( 'By %s', 'anchorage' ), $display_name );

		$out = "
			<div class='entry-byline'>
				&mdash; <address class='vcard'><a href='$href' class='fn url'>$by</a></address>, $date
			</div>
		";
		return $out;
	}
}

/**
 * Get the categories for the current post.
 * 
 * @return string The categories for the current post
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_entry_cats' ) ) {
	function anchorage_get_entry_cats() {

		$out = '';

		// Get all the category links for this post, comma-sep.
		$categories_list = get_the_category_list( esc_html__( ', ', 'anchorage' ) );
		if ( $categories_list ) {
			$out = "<div class='category-links'>&mdash; $categories_list &mdash;</div>";
		}

		return $out;

	}
}

/**
 * Get the tags for the current post.
 * 
 * @return string The tags for the current post.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_entry_tags' ) ) {
	function anchorage_get_entry_tags() {

		$out = '';
		
		// Get links to all the tags for this post, comma-sep.
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'anchorage' ), '' );
		
		if ( $tags_list ) {
			$tags_label = esc_html__( 'Tags:', 'anchorage' );
			$out = "
				<div class='tag-links'>
					<span class='tag-label'>$tags_label</span>
					$tags_list
				</div>
			";
		}

		return $out;
	}
}

/**
 * Get a biography paragraph for the post author.
 * 
 * @return string A biography paragraph for the post author.
 *
 * @since anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_author_bio' ) ) {
	function anchorage_get_author_bio() {
		
		// Grab the author bio.
		$desc = wp_kses_post( get_the_author_meta( 'description' ) );
		if( ! empty ( $desc ) ) {
			$desc = "<div class='content-holder author-description'>$desc</div>";	
		}
			
		// Grab the author avatar.
		$author_email = sanitize_email( get_the_author_meta( 'user_email' ) );
		$display_name_attr = esc_attr( get_the_author_meta( 'display_name' ) );
		$avatar = get_avatar( $author_email, 250, '', $display_name_attr );

		// The fail image from gravatar has the string 'blank.gif' and we don't want to show the fail image
		if( empty( $avatar ) || stristr( $avatar, 'blank.gif' ) ) {
			$avatar = "";
		} else {
			$avatar ="
				<div class='vcard avatar-wrap'>
					<span class='fn'>
						$avatar
					</span>
				</div>
			";
		}

		$out = $avatar . $desc;

		$break = anchorage_get_hard_rule( array( 'break-minor' ) );

		if( ! empty( $out ) ) {
			$out = "
				<div class='content-holder author-bio'>
					$avatar
					$desc
				</div>
				$break
			";
		}

		return $out;
	}
}