<?php
/**
 * Template tags for displaying comments and commenting tools.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */

if( ! function_exists( 'anchorage_get_comments_link' ) ) {
	function anchorage_get_comments_link( $post_id = '' ) {
		
		// Determine which post we're grabbing from.
		$post_id = absint( $post_id );
		if( empty( $post_id ) ) {
			global $post;
			$post_id = absint( $post -> ID );
		}

		if( ! anchorage_are_comments_approved() ) { return false; }

		$href = esc_url( get_comments_link( $post_id ) );

		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count -> approved );

		if( empty( $comments_approved ) ) {
			$label = __( 'Comment', 'anchorage' );
		} else {
			$label = sprintf( _n( '1 Comment', '%s Comments', $comments_approved, 'anchorage' ), $comments_approved );
		}
		$label = esc_html( $label );	
		$link = "<a class='comments-link' href='$href'>$label</a>";

		return $link;

	}
}

/**
 * Determine if there are approved comments for a post.
 *
 * @param int $post_id The ID of the post we're checking
 * @return boolean True if there are approved comments, otherwise false
 *
 * @since  anchorage 1.0
 */
if( ! function_exists('anchorage_are_comments_approved' ) ) {
	function anchorage_are_comments_approved( $post_id = '' ) {
		
		// Determine which post we're grabbing from.
		$post_id = absint( $post_id );
		if( empty( $post_id ) ) {
			global $post;
			$post_id = absint( $post -> ID );
		}

		// Are there comments approved for this post?
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count -> approved );
		if( empty ( $comments_approved ) ) { return false; }

		return true;

	}
}

/**
 * Outputs the comments form for a post.
 * 
 * @param $post_id The ID of the post we're grabbing from
 *
 * @since  anchorage 1.0
 */
if ( ! function_exists( 'anchorage_the_comment_form' ) ) {
	function anchorage_the_comment_form( $post_id = '' ) {

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if( empty( $post_id ) ) {
			global $post;
			$post_id = absint( $post -> ID );
		}
	
		$leave_a_comment = esc_html__( 'Leave a Comment', 'anchorage' );

		$br = anchorage_get_hard_rule();

		// There does not seem to be a way to return instead of echo.
		comment_form(
			array(
				'title_reply' => "$leave_a_comment $br",
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'cancel_reply_link'    => '',
			),
			$post_id
		);

	}
}

/**
 * Returns the comments pagination for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments pagination for a post
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_comments_pagination' ) ) {
	function anchorage_get_comments_pagination( $post_id = '' ) {			
		
		// Determine which post we're grabbing from.
		$post_id = absint( $post_id );
		if( empty( $post_id ) ) {
			global $post;
			$post_id = absint( $post -> ID );
		}

		// Does this blog break comments into pages?
		$page_comments = get_option( 'page_comments' );
		if( empty ( $page_comments ) ) { return false; }

		// How many comments per page?
		$comments_per_page = get_option( 'comments_per_page' );
				
		// Get the comment count for this post
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count -> approved );

		// Are there more comments than can fit on one page?  If so, show pagination.
		if( $comments_approved <= $comments_per_page ) { return false; }

		// The link for older comments.
		$next = '';
		if ( get_next_comments_link() ) {
			$older_comments = esc_html__( 'Older Comments', 'anchorage' );
			$arrow = anchorage_get_arrow( 'right', array(), false );
			$next_text = "$older_comments$arrow";
			$next_link = get_next_comments_link( $next_text );
			$next = "<span class='inverse-color next button button-minor next-comments'>$next_link</span>";
		}

		// The link for newer comments.
		$prev='';
		if ( get_previous_comments_link() ) {
			$newer_comments = esc_html__( 'Newer Comments', 'anchorage' );
			$arrow = anchorage_get_arrow( 'left', array(), false );
			$prev_text = "$arrow$newer_comments";
			$prev_link = get_previous_comments_link( $prev_text );
			$prev = "<span class='inverse-color prev button button-minor previous-comments'>$prev_link</span>";
		}
		
		// Wrap the comments pagination.
		$out = "
			<nav class='clear paging-navigation comment-navigation inverse-font' role='navigation'>
				$next
				$prev
			</nav>
		";
	
		return $out;

	}
}

/**
 * Returns the comments title for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments title for a post
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_comments_title' ) ) {
	function anchorage_get_comments_title( $post_id = '' ) {

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if( empty( $post_id ) ) {
			global $post;
			$post_id = absint( $post -> ID );
		}
				
		$post_title = get_the_title( $post_id );

		// create a title for the comments section depending on how many comments there are
		$out = sprintf(
			_nx(
				'One comment on &ldquo;%2$s&rdquo;',
				'%1$s comments on &ldquo;%2$s&rdquo;',
				get_comments_number( $post_id ),
				'comments title',
				'anchorage'
			),
			number_format_i18n(
				get_comments_number( $post_id )
			),
			"<em class='comments-title-post-title'>$post_title</em>"
		);
			
		// wrap the comments title
		$out = "<h2 class='inverse-color inverse-band comments-title'><span class='inner-wrapper'>$out</span></h2>";

		return $out;

	}
}

/**
 * Returns the comments for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments for a post
 *
 * @since  anchorage 1.0
 */
if( ! function_exists('anchorage_get_post_comments' ) ) {
	function anchorage_get_post_comments( $post_id = '' ){

		// Determine which post we're grabbing from.
		$post_id = absint( $post_id );
		if( empty( $post_id ) ) {
			global $post;
			$post_id = absint( $post -> ID );
		}

		// Gather an array of comment objects for a specific page/post.
		$comments = get_comments( array(
			'post_id' => $post_id,
			'status' => 'approve'
		) );

		// Format the comments.
		$out = wp_list_comments( array(
			'style'       => 'div',
			'short_ping'  => true,
			'avatar_size' => 150,
			'echo' => false,
			'callback' => 'anchorage_comment',
		), $comments );

		// Wrap the comments.
		return "<div class='comments-loop'>$out</div>";

	}
}

/**
 * Grabs a comment for output.
 * 
 * @todo  I don't understand why this function has to echo; it seems like it should be able to just return
 * 
 * @param  object $comment The comment we are outputting
 * @param  array $args    An array of args for things like get_avatar(), comment_class(), and get_comment_reply_link()
 * @param  integer $depth How deeply this comment is nested as a reply to other comments
 * @param  integer $post_id THe post from which we are grabbing this comment
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_comment' ) ) {
	function anchorage_comment( $comment, $args, $depth, $post_id = '' ) {

		$comment_id = absint( $comment -> comment_ID );

		// If the comment is not yet approved, grab a message as such.
		if ( $comment -> comment_approved == '0' ) {
		
			$awating = esc_html__( 'Your comment is awaiting moderation.', 'anchorage' );
			$out = "<p class='comment-awaiting-moderation'>$awaiting</p>";
		
		// If the comment is approved, grab the comment.
		} else {

			$header = anchorage_get_comment_header( $comment, $args, $depth );

			$body = anchorage_get_comment_body( $comment, $args, $depth );

			$break = anchorage_get_hard_rule( array( 'break-minor' ) );

			$footer = anchorage_get_comment_footer( $comment, $args, $depth );

			$out = $header . $body . $break . $footer;

		}

		if( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}
		$post_id = absint( $post_id );

		// Apply a class to the comment depending on is/not a parent of other comments.
		$maybe_parent = 'not-parent';
		if( ! empty( $args['has_children'] ) ) { $maybe_parent = 'parent'; }
		$comment_class = comment_class( $maybe_parent, $comment_id, $post_id, false );

		/**
		 * This is left unclosed because wp_list_comments automatically closes it after any child comments.
		 */
		$out = "
			<div $comment_class id='comment-$comment_id'>
				$out
		";

		/**
		 * I don't know why this has to be echoed, but it does.  Returning it results in no comments displayed.
		 */
		echo $out;

	}
}

/**
 * Get the comment header, which includes comment author info.
 * 
 * @param  object $comment The comment from which we are grabbing
 * @param  array $args an array of args passed to get_avatar()
 * @param  integer $depth The depth at which this comment is nested inside other comments
 * @return string The comment header
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_comment_header' ) ) {
		function anchorage_get_comment_header( $comment, $args, $depth ) {
		
		// Comment, pingback, or trackback.
		$comment_type = esc_html( get_comment_type() );
		
		// If it's a ping or trackback, label it as such.
		$ping = "";
		if( $comment_type != 'comment' ) {
			$ping = '<span class="comment-type">$comment_type</span>';
		}

		// Grab the avatar for the comment author.
		$avatar = '';
		if ( $args['avatar_size'] != 0 ) {
			$avatar = get_avatar( $comment, $args['avatar_size'] );
		}

		$author_link = strip_tags( get_comment_author_link(), '<a>' );

		$out = "
			<div class='comment-header'>
				<span class='comment-author vcard'>
					$ping
					$avatar
					<cite class='inverse-font fn'>$author_link</cite>
				</span>
			</div>
		";

		return $out;

	}
}

/**
 * Get the comment body.
 * 
 * @param  object $comment The comment from which we are grabbing
 * @param  array $args an array of args passed to get_avatar()
 * @param  integer $depth The depth at which this comment is nested inside other comments
 * @return string The comment body
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_comment_body' ) ) {
	function anchorage_get_comment_body( $comment, $args, $depth ) {

		$comment_text = get_comment_text();

		$out = "
			<div class='comment-text content-holder editable-content'>
				$comment_text
			</div>
		";

		return $out;

	}
}

/**
 * Get the comment footer, which includes a reply link and permalinked date.
 * 
 * @param  object $comment The comment from which we are grabbing
 * @param  array $args an array of args passed to get_avatar()
 * @param  integer $depth The depth at which this comment is nested inside other comments
 * @return string The comment footer
 *
 * @since  anchorage 1.0
 */
if( ! function_exists( 'anchorage_get_comment_footer' ) ) {
	function anchorage_get_comment_footer( $comment, $args, $depth ) {

		// The permalink to this comment.  We'll use the date as the clickable text.
		$href = esc_url( get_comment_link() );
		$date = esc_html( get_comment_date() );
		
		// The link to edit this comment.
		$edit_href = esc_url( get_edit_comment_link() );
		$edit = esc_html__( '(Edit)', 'anchorage' );
		
		$reply = esc_html__( 'Respond', 'anchorage' ) . anchorage_get_arrow( 'down', array(), false );
		$reply_args = array( 'reply_text' => $reply, 'depth' => $depth, 'max_depth' => $args['max_depth'] );
		$reply_link = get_comment_reply_link( array_merge( $args, $reply_args ) );
		
		$dash = esc_html__( '&mdash;', 'anchorage' );

		$out = "
			<div class='comment-meta inverse-shadow commentmetadata'>
				<a href='$href'>$date</a>
				<a href='$edit_href'>$edit</a> $dash
				<span class='comment-reply-wrap'>
					$reply_link				
				</span>
			</div>
		";

		return $out;

	}
}