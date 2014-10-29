<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage anchorage
 * @since anchorage 1.0
 */
?>

<?php /* Don't show comments if pw-protected or comments closed. */ ?>
<?php if ( post_password_required() || ! comments_open() ) { exit; } ?>

<section id='comments' class='outer-wrapper'>

	<?php /* If there are approved comments, grab the section header. */ ?>
	<?php if( anchorage_are_comments_approved() ) { ?>
		<?php echo anchorage_get_comments_title(); ?>
	<?php } ?>

	<div class='inner-wrapper'>

		<?php /* If comments are approved, grab the comments and their pagination. */ ?>
		<?php if( anchorage_are_comments_approved() ) { ?>

			<?php echo anchorage_get_post_comments(); ?>

			<?php echo anchorage_get_comments_pagination(); ?>

		<?php } ?>

		<?php anchorage_the_comment_form(); ?>
	
	</div>	

</section>