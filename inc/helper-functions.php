<?php

/**
 * anchorage helper functions.
 *
 * These functions are used to build other functions in /inc files such template-tags.php or comment-template-tags.php
 *
 * @package WordPress
 * @subpackage anchorage
 * @since  anchorage 1.0
 */

/**
 * Get an HTML <hr /> with classes expected by our stylesheet.
 *
 * @param array $classes An array of HTML classes.
 * @return  string An HTML <hr /> with classes expected by our stylesheet.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_hard_rule' ) ) {
	function anchorage_get_hard_rule( $classes = array() ) {
		
		$classes     = array_map( 'sanitize_html_class', $classes );
		$classes   []= 'break';
		$classes_str = implode( ' ', $classes );
		
		$out = "<hr class='$classes_str' />";
		return $out;
	}
}

/**
 * Get the post date for current post.
 *
 * @return string The HTML-formatted post date.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_entry_date' ) ) {
	function anchorage_get_entry_date() {
		
		$permalink  = esc_url( get_permalink() );
		$title_attr = esc_attr( get_the_title() );
		$datetime   = esc_attr( get_the_date( 'c' ) );
		$date       = esc_html( get_the_date() );
		
		$out = "
			<a class='date' href='$permalink' title='$title_attr' rel='bookmark'>
				<time class='entry-date' datetime='$datetime'>$date</time>
			</a>
		";

		return $out;
	}
}

/**
 * Get a jump menu to provide navigation for various types of archives.
 *
 * @param  string $archive_type The type of archive: category, tag, author, or month.
 * @return string The jump menu for the designated archive type.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_jump_nav' ) ) {
	function anchorage_get_jump_nav( $archive_type ) {

		$options = '';

		// If it's a category archive, get all categories and read them into a jump menu.
		if( $archive_type == 'category' ) {
			$label = esc_attr__( 'Choose a category to jump to that page.', 'anchorage' );
			$categories = get_categories();
			if( ! empty( $categories ) ) {
				foreach ( $categories as $category ) {
					$cat_id   = absint( $category -> cat_ID );
					$cat_link = esc_url( get_category_link( $cat_id ) );
				  	$cat_name = esc_html( $category -> cat_name );
				  	$option   = "<option value='$cat_link'>$cat_name</option>";
					$options .= $option;
		  		}
		  	}
		
		// If it's a tag archive, get all tags and read them into a jump menu.
		} elseif ( $archive_type == 'tag' ) {
			$label = esc_html__( 'Choose a tag to jump to that page.', 'anchorage' );
			$tags = get_tags();
			if( ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$tag_id   = absint( $tag -> term_id );
					$tag_link = esc_url( $tag_link = get_tag_link( $tag_id ) );
					$tag_name = esc_html( $tag -> name );
					$option   = "<option value='$tag_link'>$tag_name</option>";
					$options .= $option;
	  			}
			}

		// If it's an author achive, get all authors and read them into a jump menu.
		} elseif ( $archive_type == 'author' ) {
			$label = esc_html__( 'Choose an author to jump to that page.', 'anchorage' );
			$args = array( 'who' => 'authors' );
			$authors = get_users( $args ); 
			if( ! empty( $authors ) ) {
				foreach( $authors as $author ) {
					$user_id = absint( $author -> ID );
					
					$post_count = absint(count_user_posts( $user_id ) );
					if( empty( $post_count ) ) { continue; }
					
					$display_name = esc_html( $author -> display_name );
					$author_url   = esc_url( get_author_posts_url( $user_id ) );
					$options     .= "<option value='$author_url'>$display_name</option>";
				}	
			}
		
		// If it's a month archive, use wp_get_archives to get months.
		} elseif ( $archive_type == 'month' ) {
			$label = esc_html__( 'Choose a month to jump to that page.', 'anchorage' );
			$args = array(
				'type'   		  => 'monthly',
				'format' 		  => 'option',
				'echo'   		  => false,
				'show_post_count' => 0,
			);
			if( wp_get_archives( $args ) ) {
				$options = wp_get_archives( $args );
			}
		
		// If it's a page archive, get all pages.
		} elseif ( $archive_type == 'page' ) {
			$label = esc_html__( 'Choose a page to jump to that page.', 'anchorage' );
			$pages = get_pages();
			if( ! empty( $pages ) ) {
				foreach( $pages as $page ) {
					$title    = esc_html( $page -> post_title );
					$id       = absint( $page -> ID );
					$href     = esc_url( get_permalink( $id ) );
					$options .= "<option value='$href'>$title</option>";
				}
			}
		}

		if( empty ( $options ) ) { return false; }

		// Wrap the options in a select.	
		$out = "
			<select class='jump-nav' title='$label' name='jump-nav' onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<option value=''>$label</option>
				$options
			</select>
		";

		return $out;
	}
}

/**
 * Given the url to a file, determine if it exists on our server.
 *
 * @param string The url to a file.
 * @return string The path to the file, or false if non-existant.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_file_is_on_server' ) ) {
	function anchorage_file_is_on_server( $src ) {

		// Wraps wp_upload_dir()'s ['base_url'].
		$uploads_url = anchorage_get_uploads_url();
		
		// Wraps wp_upload_dir()'s ['base_path']
		$uploads_path = anchorage_get_uploads_path();
		
		// If we weren't given a valid url, just bail.
		if( esc_url( $src ) != $src ) { return false; }

		// If the img src doesn't contain our base url, bail.
		if( ! stristr( $src, $uploads_url ) ) { return false; }
		
		// Strip out the first part of the url.
		$relative_url = str_replace( $uploads_url, '', $src); 

		// Replace it with the base part of our server path.
		$path = $uploads_path . $relative_url;

		// Strip out any query string portion of the url.
		$path = strtok( $path, '?' );

		// See if it exists.
		if( ! file_exists( $path ) ) { return false; }

		return $path;
			
	}
}

/**
 * Get meta info for a media file.
 * 
 * @param  string $path The path to a media file.
 * @param  string $type The type of meta data to gather.
 * @return string Meta info for a media file.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_media_meta' ) ) {
	function anchorage_get_media_meta( $path, $type ) {

		$out='';

		// Will hold an array of fields to gather from the file.
		$fields = false;

		// For audio files, grab media.php, read the file, and define which fields we need.
		if( $type == 'audio' ) {

			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$media = wp_read_audio_metadata( $path );

			$fields = array(
				'bitrate',
				'year',
				'artist',
				'genre',
				'title',
				'album',
				'length_formatted',
			);

		// For video files, grab media.php, read the file, and define which fields we need.
		} elseif( $type == 'video' ) {

			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$media = wp_read_video_metadata( $path );

			$fields = array(
				'length_formatted',
				'fileformat',
				'dataformat',
				'mime_type',
				'codec',
			);

		// For image files, grab image.php, read the file, and define which fields we need.
		} elseif( $type == 'image' ) {

			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$media = exif_read_data( $path );

			$fields = array(
				'ExposureTime',
				array(
					'COMPUTED',
					'ApertureFNumber',
				),
				'Model',
				'DateTimeOriginal'
			);

		}

		// If fields is still false, bail.
		if( ! is_array( $fields ) ) { return false; }

		// Will hold the meta values for our media file.
		$meta = array();

		// For each field...
		foreach( $fields as $f ) {

			// If it's a string, grab the value.
			if( is_string( $f ) ) {
				if( isset( $media[$f] ) ){
					$meta[$f] = $media[$f]; 
				}

			// If it's an array, reach into the meta value to get the part we want.
			/**
			 * @todo  This is really weak.
			 */
			} elseif( is_array( $f ) ) {

				$first = $f[0];
				$second = $f[1];
				
				if( isset( $media[$first][$second] ) ) {
					$meta[$second] = $media[$first][$second];
				}
			}
		}

		// For each meta that we grabbed...
		foreach( $meta as $k => $v ) {

			// Make them more readable.
			$k = str_replace( '_', ' ', $k );

			// If it's some kind of a date, format according to the setting for date format.
			if( stristr( $k, 'Date' ) ) {
				$format = get_option( 'date_format' );
				$v = strtotime( $v );
				$v = date( $format, $v );
			}

			// If it's empty, skip it.
			if( empty( $v ) ) { continue; }
			
			// If it's not empty, wrap it and add it to the output.
			$out .= "<p class='media-meta-key-value-pair'><span class='media-meta-key'>$k: </span><span class='media-meta-value'>$v</span></p>";
		
		}

		if( empty( $out ) ) { return false; }

		// Grab a toggle arrow.
		$arrow = anchorage_get_arrow( 'down', array(), false );

		$data = esc_html__( 'data', 'anchorage' );

		// Wrap the whole thing for output.
		$out="
			<aside class='media-meta anchorage-toggle'>
				<a class='button-minor button inverse-color closed anchorage-toggle-link' href='#'>$type $data $arrow</a>
				<div class='media-meta-list anchorage-toggle-reveal'>$out</div>
			</aside>
		";

		return $out;
	}

}

/**
 * Get the path to the uploads dir.
 * 
 * @return string The path to the uploads dir.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_uploads_path' ) ) {
	function anchorage_get_uploads_path(){
		$dir = wp_upload_dir();
		$path = $dir['basedir'];

		return $path;

	}
}

/**
 * Get the url to the uploads dir.
 * 
 * @return string The path to the uploads dir.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_uploads_url' ) ) {
	function anchorage_get_uploads_url(){
		$dir = wp_upload_dir();
		$url = $dir['baseurl'];

		return $url;
	}
}

/**
 * Grab the unicode char for an arrow in a given direction, with classes and an href.
 * 
 * @param  string $direction The direction in which the arrow will point.
 * @param  array  $classes   An array of HTML classes for the arrow.
 * @param  string $href      Link the arrow to a url.
 * @return string The arrow, with classes, wrapped in either a link or span.
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_arrow' ) ) {
	function anchorage_get_arrow( $direction = 'down', $classes = array(), $href = '#' ) {

		// Grab the correct arrow char for the direction.
		if ( $direction == 'left' ) {
			$out = esc_html__( '&larr;', 'anchorage' );
		} elseif ( $direction == 'up' ) {
			$out = esc_html__( '&uarr;', 'anchorage' );
		} elseif ( $direction == 'right' ) {
			$out = esc_html__( '&rarr;', 'anchorage' );
		} else {
			$out = esc_html__( '&darr;', 'anchorage' );
		}

		// Build the classes.
		$classes = array_map( 'sanitize_html_class', $classes );
		$classes = implode( ' ', $classes );
		$classes = " class='$classes arrow' ";

		$out = " $out ";

		// If there's an href, wrap the arrow in a link.
		$href = esc_attr( $href );
		if( ! empty( $href ) ) {
			$out = "<a href='$href' $classes>$out</a>";
		
		// Else, if there are classes, wrap it in a span.
		} elseif( ! empty( $classes ) ) {
			$out = "<span $classes>$out</span>";
		}
		
		return $out;
	}
}