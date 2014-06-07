<?php

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
 * @since  Icicle 1.0
 */
function icicle_search_form( $form_classes = array(), $search_input_classes = array() ) {
	
	// An array of CSS classes for the search form.
	$form_classes = array_map( 'sanitize_html_class', $form_classes );
	$form_classes_string = implode( ' ', $form_classes );
	
	// An array of CSS classes for the search input.
	$search_input_classes = array_map( 'sanitize_html_class', $search_input_classes );
	$search_input_string = implode( ' ', $search_input_classes );
	
	$out ="
		<form action='".esc_url( home_url( '/' ) )."' class='$form_classes_string search-form' method='get' role='search'>
			<label for='s'><span class='screen-reader-text'>".esc_html__( 'Search for:', 'icicle' )."</span></label>
			<input id='s' type='search' title='Search for:' name='s' value='".esc_html__( 'Search', 'icicle' )."' class='search-field $search_input_string'>
			<input type='submit' value='".esc_html__( 'Submit', 'icicle' )."' class='screen-reader-text search-submit'>
		</form>
	";

	return $out;

}

/**
 * Return a string to denote the post format, if not standard.
 *
 * @return string [description]
 */
function icicle_get_post_format(){
	global $post;
	$post_id = absint( $post->ID );
	$format = get_post_format( $post_id );

	$format = esc_html( $format );

	if ( empty( $format ) ) {
		return false;
	}

	$out = "<span class='post-format-label'>$format &rarr;</span>";

	return $out;

}



/**
 * Return an HTML img tag for the first image in a post content. Used to draw
 * the content for posts of the "image" format.
 *
 * @return string An HTML img tag for the first image in a post content.
 */
function icicle_get_first_image() {
	
	// Expose information about the current post.
	global $post;
	
	// We'll trap to see if this stays empty later in the function.
	$src = '';
	
	// Grab all img src's in the post content
	$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
	
	// Grab the first img src returned by our regex.
	if( ! isset ( $matches[1][0] ) ) { return false; }
	$src = $matches[1][0];

	// Sanitize for output
	$src = esc_url( $src );

	// Make sure there's still something worth outputting after sanitization.
	if( empty( $src ) ) { return false; }
	
	// Grab and sanitize the post title as an alt.
	$alt = esc_attr( $post->post_title );

	$out = "<img class='first-image' alt='$alt' src='$src'>";

	// Trap to see if the post has a caption shortcode
	$caption = '';
	if( has_shortcode( $post->post_content, 'caption' ) ) {
		
		$output = preg_match_all( '/caption=(.*)\]/smU', $post->post_content, $matches );
		if($output){
			$caption = $matches[1][0];
			$caption = trim($caption, '"');
			$caption = trim($caption, "'");
		} else {

			// Grab the content of the first caption
			preg_match_all( '/\[caption\s?.*\](.*)\[\/caption\]/smU', $post->post_content, $matches );
			
			$caption = strip_tags( $matches[1][0], '<a><b><br><em><i><p><span><strong>' );
		}
		
	}

	// Trap to see if the first image is linked
	$href = '';
	$content_before_first_image = explode( '<img', $post->post_content );
	$content_before_first_image = $content_before_first_image[0];
	
	$href = get_url_in_content( $content_before_first_image );

	if( ! empty( $content_before_first_image ) ) { $out = "<a href='$href'>$out</a>"; }

	// if the file is on the server, grab the exif
	$exif = '';
	$path = icicle_file_is_on_server( $src );
	if( ! empty( $path ) ) { 
		$path = icicle_file_is_on_server( $src );
		$exif = icicle_get_media_meta( $path, 'image' );

	}


//	wp_die( var_dump( $src ) );

	// If there is a caption, return a figure
	if( ! empty( $caption ) ) {
		$out = "
			<figure class='first-image-figure'>
				$out
				<figcaption class='first-image-caption'>$caption</figcaption>
			</figure>
		";
	}

	$out.=$exif;

	return $out;

}


function icicle_uploads_path(){
	$dir = wp_upload_dir();
	$path = $dir['basedir'];

	return $path;

}

function icicle_uploads_url(){
	$dir = wp_upload_dir();
	$url = $dir['baseurl'];

	return $url;

}




function icicle_get_first_media( $type ){
	
	global $post;

	$content = get_media_embedded_in_content( $post->post_content );
	if ( get_url_in_content( $content ) ) {
		$content = get_media_embedded_in_content( $content );
		$w = get_url_in_content( $content );
	} else {
		$content = $post->post_content;
		$w = get_url_in_content( $content );
	}

	$path = icicle_file_is_on_server( $w );	

	if( ! $path ) { return false; }

	if( ( $type == 'audio' ) || ( $type == 'video' ) ) {
		
		return icicle_get_media_meta( $path, $type );

	} else {

		return "$type";

	}

}

function icicle_get_media_meta( $path, $type ) {

	$out='';

	$fields = false;
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

	} elseif( $type == 'image' ) {

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$media = exif_read_data( $path );

		$fields = array(
			'ExposureTime',
			array( 'COMPUTED', 'ApertureFNumber' ),
			'Model',
			'DateTimeOriginal'
		);

	}

	if( !is_array( $fields ) ) { return false; }

	$meta = array();	
	foreach( $fields as $f ) {

		if( is_string( $f ) ) {
			if( isset( $media[$f] ) ){
				$meta[$f] = $media[$f]; 
			}

		} elseif( is_array( $f ) ) {

			$first = $f[0];
			$second = $f[1];
			
			if( isset( $media[$first][$second] ) ) {
				$meta[$second] = $media[$first][$second];
			}
		}
	}

	foreach( $meta as $k => $v ) {

		$k = str_replace( '_', ' ', $k );

		if(stristr( $k, 'Date' ) ) {
			$format = get_option( 'date_format' );
			$v = strtotime( $v );
			$v = date( $format, $v );
		}

		if( empty( $v ) ) { continue; }
		
		$out .= "<p class='media-meta-key-value-pair'><span class='media-meta-key'>$k: </span><span class='media-meta-value'>$v</span></p>";
	
	}

	if( empty( $out ) ) { return false; }



	$out="
		<aside class='media-meta icicle-toggle'>
			<a class='button-minor button accent-color closed icicle-toggle-link' href='#'>$type ".esc_html__( 'data', 'icicle' )." <span class='arrow'>&darr;</span></a>
			<div class='media-meta-list icicle-toggle-reveal'>$out</div>
	";

	return $out;
}

function icicle_toggle_script(){
	?>
		<script>
			jQuery( document ).ready(function($) {
				$( '.icicle-toggle-reveal' ).hide();
				$( '.icicle-toggle-link' ).click( function( event ) {
					event.preventDefault();
					$( this ).next( '.icicle-toggle-reveal' ).slideToggle();
					$( this ).toggleClass( 'closed open' );
				});


				$( '.icicle-toggle-link' ).toggle( function() {
       				$( this ).find( '.arrow' ).html( '&uarr;' );
    			}, function() {
     				$( this ).find( '.arrow' ).html('&darr;');
				});

			});
		</script>
	<?php
}
add_action( 'wp_footer', 'icicle_toggle_script' );

function icicle_file_is_on_server( $src ){

	$uploads_url = icicle_uploads_url();
	
	$uploads_path = icicle_uploads_path();
	

	if( esc_url( $src ) != $src ) { return false; }

	if( ! stristr( $src, $uploads_url ) ) { return false; }
					
	$relative_url = str_replace( $uploads_url, '', $src); 

	$path = strtok( $uploads_path.$relative_url, '?' );

	if( ! file_exists( $path ) ) { return false; }

	return $path;
		
}





function icicle_the_html_classes() {
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


/**
 * Display links to paginated sub pages.
 */
if ( ! function_exists( 'icicle_link_pages' ) ) {
	function icicle_link_pages() {
		$args = array(
			'before'           => '<nav class="paging-navigation numeric-pagination accent-font accent-color button link-pages">' . esc_html__( 'Pages:', 'icicle' ),
			'after'            => '</nav>',
			'next_or_number'   => 'number',
			'echo'             => 0
		);
		
		return wp_link_pages( $args );
	}
}

/**
 * Display navigation to next/previous set of posts when applicable.
 */
if ( ! function_exists( 'icicle_paging_nav' ) ) {
	function icicle_paging_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) { return false; }

		$out = "";

		if( get_next_posts_link() ) {
			$out .= "<span class='accent-color button button-minor next next-posts'>".get_next_posts_link( "<span class='arrow next-arrow'>&larr;</span>".esc_html__( 'Older Posts', 'icicle' ) )."</span>";
		}

		if( get_previous_posts_link() ) {
			$out .= "<span class='accent-color button button-minor prev previous-posts'>".get_previous_posts_link( esc_html__( 'Newer Posts', 'icicle' )."<span class='arrow prev-arrow'>&rarr;</span>" )."</span>";
		}


		if( empty( $out ) ) { return false; }

		$out = "
			<nav class='accent-font paging-navigation posts-navigation clear' role='navigation'>
				<h1 class='screen-reader-text'>".esc_html__( 'Posts navigation', 'icicle' )."</h1>
				$out
			</nav>
		";

		return $out;

	}
}

function icicle_archive_header(){
	global $wp_query;
	
	if ( is_search() ){
		
		if( have_posts() ) {
			$message = sprintf( esc_html__( 'Search results for %s', 'icicle' ), get_search_query() );
			$class = 'search';
		} else {
			$message = sprintf( esc_html__( 'No results found for %s', 'icicle' ), get_search_query() );
			$class = 'search';	
		}

	} elseif( is_category() ) {
		$message = sprintf( esc_html__( 'Category Archives: %s', 'icicle' ), single_cat_title( '', false ) );
		$class = 'category';
	} elseif( is_tag() ) {
		$message = sprintf( esc_html__( 'Tag Archives: %s', 'icicle' ), single_tag_title( '', false ) );
		$class = 'tag';
	} elseif( is_year() ) {
		$message = sprintf( esc_html__( 'Year Archives: %s', 'icicle' ), get_the_date( 'Y' ) );
		$class = 'year';
	} elseif( is_month() ) {
		$message = sprintf( esc_html__( 'Month Archives: %s', 'icicle' ), get_the_date( 'F Y' ) );
		$class = 'month';
	} elseif( is_day() ) {
		$message = sprintf( esc_html__( 'Date Archives: %s', 'icicle' ), get_the_date() );
		$class = 'day';
	} elseif( is_author() ) {
		$message = sprintf( esc_html__( 'Author Archives: %s', 'icicle' ), get_the_author() );
		$class = 'author';
	} elseif( is_404() ) {
		$message = esc_html__( 'Your page could not be found.', 'icicle' );
		$class = '404';
	} else {
		$message = esc_html__( 'Archives:', 'icicle' );
		$class = 'default';
	}

	$paged = '';
	if ( is_paged() ) {
		$paged = get_query_var( 'paged' );
		$paged = absint( $paged );
		if( $paged > 1 ) {
			$paged = " &mdash; ".esc_html__('Page')." $paged";
		}
	}

	$header_class = "archive-header-$class";
	$title_class = "archive-title-$class";
	
	$out = "
		<header class='archive-header $header_class'>
			<h1 class='archive-title $title_class'>
				$message $paged
			</h1>
		</header>
	";

	return $out;

}

function icicle_no_posts() {

	$out ="<h3 class='no-posts-header'>".esc_html__( 'Find your way by searching:', 'icicle' )."</h3>";
	$out .= icicle_search_form( array( 'no-posts-searchform' ), array ( 'no-posts-search-input') );
	$out .="<h3 class='no-posts-header'>".esc_html__( 'Or browse by archive:', 'icicle' )."</h3>";
	
	$jump = get_transient( 'icicle_no_posts' );
	if ( ! empty( $jump ) ) {
		$out .= $jump;
	} else {

		$jump = icicle_jump_nav( 'category' );
		$jump .= icicle_jump_nav( 'tag' );
		$jump .= icicle_jump_nav( 'author' );
		$jump .= icicle_jump_nav( 'month' );

		$jump = "
			<div class='jump-nav-form'>
				$jump
			</div>
		";

		set_transient( 'icicle_no_posts', $out, DAY_IN_SECONDS );

		$out .= $jump;
	}

	return $out;
}

function icicle_jump_nav( $archive_type ) {


	$options = '';

	if( $archive_type == 'category' ) {
		$label = esc_attr__( 'Choose a category to jump to that page.', 'icicle' );
		
		$categories=get_categories(); 
		foreach ($categories as $category) {
			$cat_id = absint($category->cat_ID);
			$cat_link = esc_url(get_category_link( $cat_id ));
		  	$option = "<option value='$cat_link'>";
			$option .= esc_html($category->cat_name);
			$option .= '</option>';
			$options.= $option;
  		}

	
	} elseif ( $archive_type == 'tag' ) {
		$label = esc_html__( 'Choose a tag to jump to that page.', 'icicle' );

		$tags = get_tags();
		foreach ( $tags as $tag ) {
			$tag_id = absint($tag->term_id);
			$tag_link = esc_url($tag_link = get_tag_link($tag_id));
			$option = "<option value='$tag_link'>";
			$option .= esc_html($tag->name);
			$option .= '</option>';
			$options.= $option;
  		}
	
	} elseif ( $archive_type == 'author' ) {
		$label = esc_html__( 'Choose an author to jump to that page.', 'icicle' );

		$args = array(
			'who'	=> 'authors'
		);
		$authors=get_users($args); 
					
		foreach($authors as $author){
			$user_id = absint($author->ID);
			
			$post_count = absint(count_user_posts( $user_id ));
			if( empty( $post_count ) ) { continue; }
			
			$display_name = esc_html($author->display_name);
			$author_url = esc_url( get_author_posts_url( $user_id) );
			$options.="<option value='$author_url'>$display_name</option>";
			
		}
	
	} elseif ( $archive_type == 'month' ) {
		$label = esc_html__( 'Choose a month to jump to that page.', 'icicle' );
		$options = wp_get_archives( array( 'type' => 'monthly', 'format' => 'option', 'echo' => false, 'show_post_count' => 0 ) );
	}
	
	$out = "
		<select title='$label' name='jump-nav' onchange='document.location.href=this.options[this.selectedIndex].value;'>
		<option value=''>$label</option>
		$options
		</select>
	";

	return $out;
}

// get page ancestors 
function icicle_page_ancestors() {
	global $post;
	$parents = get_post_ancestors( $post->ID );

	if( !is_array( $parents ) ) {
		return false;
	}

	$parents = array_reverse( $parents );

	$out = '';

	foreach ( $parents as $p ) {
		//$parent = get_post( $p );
		$parent_title = get_the_title( $p );
		$parent_link = get_permalink( $p );
		$out.= " &mdash; <a href='$parent_link' class='ancestor'>$parent_title</a>";
	}

	if( empty ( $out ) ) { return false; }

	$out = "<nav rel='navigation' class='ancestors'>$out</nav>";

	return $out;

}

/**
 * Display navigation to next/previous post when applicable.
 */
function icicle_post_nav() {
	global $post;

	if( get_next_post_link() ) {
		$out .= "<span class='accent-color button button-minor next next-post'>".get_next_post_link( "%link", "<span class='arrow next-arrow'>&larr;</span>%title" )."</span>";
	}

	if( get_previous_post_link() ) {
		$out .= "<span class='accent-color button button-minor prev previous-post'>".get_previous_post_link( "%link", "%title<span class='arrow prev-arrow'>&rarr;</span>" )."</span>";
	}

	if( empty( $out ) ) { return false; }

	$out = "
		<nav class='accent-font paging-navigation post-navigation clear' role='navigation'>
			<h1 class='screen-reader-text'>".esc_html__( 'Post navigation', 'icicle' )."</h1>
			$out
		</nav>
	";
	return $out;	
}

/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own icicle_entry_meta() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 */
function icicle_entry_byline() {
	
	$date = icicle_entry_date();

	$out = "
		<div class='entry-byline'>
			&mdash; <address class='vcard'><a href='".get_bloginfo('url')."' class='fn url'>By ".get_the_author()."</a></address>,
			$date
		</div>
	";
	return $out;
}



function icicle_entry_cats(){

	$out = '';
	$categories_list = get_the_category_list( esc_html__( ', ', 'icicle' ) );
	if ( $categories_list ) {
		$out = "<div class='category-links'>&mdash; $categories_list &mdash;</div>";
	}

	return $out;

}





function icicle_entry_tags(){

	$out = '';
	$tags_list = get_the_tag_list( '', esc_html__( ', ', 'icicle' ), '' );
	if ( $tags_list ) {
		$out = "
			<div class='tag-links'>
				<span class='tag-label'>".esc_html__( 'Tags:', 'icicle' )."</span>
				$tags_list
			</div>";
	}

	return $out;

}



function icicle_author_bio(){
	
	//global $post;

	$desc = wp_kses_post( get_the_author_meta('description') );
	if( !empty ( $desc ) ) {
		$desc = "<div class='content-holder author-description'>$desc</div>";	
	}
		
	$author_email = sanitize_email( get_the_author_meta( 'user_email' ) );
	$display_name_attr = esc_attr( get_the_author_meta( 'display_name' ) );
	$avatar = get_avatar( $author_email, 250, '', $display_name_attr );

	//the fail image from gravatar has the string 'blank.gif' and we don't want to show the fail image
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

	$out = $avatar.$desc;

	if( !empty( $out ) ){
		$out = "
			<div class='content-holder author-bio'>
				$avatar
				$desc
			</div>
			<hr class='break break-minor'>
		";
	}

	return $out;
}


// Get src URL from avatar <img> tag (add to functions.php)
function get_avatar_url($author_id, $size){
    $get_avatar = get_avatar( $author_id, $size );
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return ( $matches[1] );
}



/**
 * Print HTML with date information for current post.
 *
 * Create your own icicle_entry_date() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function icicle_entry_date( ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'icicle' );
	else
		$format_prefix = '%2$s';

	$out = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'icicle' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	return $out;
}

