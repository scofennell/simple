<?php


/**
 * Outputs the comments area for a post
 * 
 * @param  $post_id The ID of the post we're grabbing from
 * @return bool|void Will return false if comments closed or PW-protected.  Otherwise, outputs comments
 */
if (!function_exists('icicle_the_comments')){
	function icicle_the_comments( $post_id='' ){

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// don't show comments if pw-protected
		if ( post_password_required( $post_id ) ) { return false; }

		// if comments are not open for the post, return false
		if ( ! comments_open( $post_id ) ) {return false;}
			
		// if there are comments, this will contain text for the comments section title
		$comments_title = '';
		
		// if there are comments, this will contain them
		$the_comments = '';
		
		// if there are more comments than the pagination setting, this will contain them
		$comments_pagination = '';
		
		// get the comment count for this post
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count->approved );
		
		// are there comments approved for this post?
		if( !empty ( $comments_approved ) ) {
				
			$comments_title = icicle_comments_title( $post_id );

			$the_comments = icicle_get_post_comments( $post_id );

			$comments_pagination = icicle_comments_pagination( $post_id );

		}

		// start the output
		echo "
			<section id='comments' class='outer-wrapper'>
				$comments_title
				<div class='inner-wrapper'>
					$the_comments
					$comments_pagination";

					// There does not seem to be a way to return instead of echo.
					comment_form(
						array(
							'title_reply'=>'<span class="inverse-shadow">'.esc_html__( 'Leave a Comment', 'icicle' ).'</span>' 
						),
						$post_id
					);
			echo "</div>	
			</section>
		";
	
	}
}

/**
 * Returns the comments pagination for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments pagination for a post
 */
if(!function_exists('icicle_comments_pagination')){
	function icicle_comments_pagination($post_id=''){			
		
		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// does this blog break comments into pages?
		$page_comments = get_option( 'page_comments' );
		if(empty ( $page_comments ) ) { return false; }

		// how many comments per page?
		$comments_per_page = get_option( 'comments_per_page' );
				
		// get the comment count for this post
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count->approved );

		// are there more comments than can fit on one page?  if so, show pagination
		if( $comments_approved <= $comments_per_page ) { return false; }

		// the link for newer comments
		$next = '';
		if ( get_next_comments_link() ) {
			$next_text = "<span class='next-arrow arrow'>&larr;</span>".esc_html__( 'Older Comments', 'icicle' );
			$next = "<span class='inverse-color next button button-minor next-comments'>".get_next_comments_link( $next_text )."</span>";
		}

		$prev='';
		if ( get_previous_comments_link() ) {
			$prev_text = esc_html__( 'Newer Comments', 'icicle' )."<span class='prev-arrow arrow'>&rarr;</span>";
			$prev = "<span class='inverse-color prev button button-minor previous-comments'>".get_previous_comments_link( $prev_text )."</span>";
		}
		
		// wrap the comments pagination
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
 */
if(!function_exists('icicle_comments_title')){
	function icicle_comments_title( $post_id='' ){

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// create a title for the comments section depending on how many comments there are
		$out = sprintf(
			_nx(
				'One comment on &ldquo;%2$s&rdquo;',
				'%1$s comments on &ldquo;%2$s&rdquo;',
				get_comments_number( $post_id ),
				'comments title',
				'icicle'
			),
			number_format_i18n(
				get_comments_number( $post_id )
			),
			'<em class="comments-title-post-title">' . get_the_title( $post_id ) . '</em>'
		);
			
		// wrap the comments title
		$out = '<h2 class="inverse-color inverse-band comments-title"><span class="inner-wrapper">'.$out.'</span></h2>';

		return $out;

	}
}

/**
 * Returns the comments for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments for a post
 */
if(!function_exists('icicle_get_post_comments')){
	function icicle_get_post_comments( $post_id='' ){

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		//Gather an array of comment objects for a specific page/post 
		$comments = get_comments( array(
			'post_id' => $post_id,
			'status' => 'approve'
		) );

		// format the comments
		$out = wp_list_comments( array(
			'style'       => 'div',
			'short_ping'  => true,
			'avatar_size' => 150,
			'echo' => false,
			'callback' => 'icicle_comment',
		), $comments );

		// wrap the comments
		
		return "<div class='comments-loop'>".$out."</div>";

	}
}





function icicle_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	
	$ping = "";
	$comment_type = get_comment_type();
	if( $comment_type != 'comment' ) {
		$ping = '<span class="comment-type">'.esc_html( "$comment_type: " ).'</span>';
	}

	?>
	<div <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
		
		<div class='comment-header'>
			<span class="comment-author vcard">
				<?php echo $ping; ?>
				<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				<?php echo '<cite class="inverse-font fn">'.get_comment_author_link().'</cite>'; ?>
			</span>

		</div>

		<?php if ( $comment->comment_approved == '0' ) { ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'icicle' ); ?></em>
			<br />
		<?php } ?>

		<div class="comment-text content-holder editable-content">
			<?php comment_text(); ?>
		</div>
		<hr class="break break-minor">
		<div class="comment-meta inverse-shadow commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __('%1$s at %2$s', 'icicle' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
			?>
			&mdash;
			<span class="comment-reply-wrap">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Respond <span class="arrow">&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</span>

		</div>
		
<?php

}