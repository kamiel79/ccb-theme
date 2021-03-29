<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Creative Choice Blog
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$c = get_comments_number($post->ID);
				$args = array(
					'post_id' => $post->ID, 
					'count'   => true // Return only the count
				);
				$c = get_comments( $args );

				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $c, 'comments title', 'ccb' ),
					number_format_i18n( $c ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'ccb' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'ccb' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'ccb' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'ccb' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'ccb' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'ccb' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'ccb' ); ?></p>
	<?php endif; ?>

	<?php 
	$aria_req = "";
	$comments_args =  array(
	  'author' =>
		'<p class="comment-form-author"><label for="author">' . __( 'Name', 'ccb' ) .
		( $req ? '<span class="required">*</span>' : '' ) . '</label> '.
		'<input id="author" name="author" type="text" minlength="3" required value="' . esc_attr( $commenter['comment_author'] ) .
		'" size="30"' . $aria_req . ' /></p>',

	  'email' =>
		'<p class="comment-form-email"><label for="email">' . __( 'Email', 'ccb' ) . 
		( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="email" name="email" type="email" required value="' . esc_attr(  $commenter['comment_author_email'] ) .
		'" size="30"' . $aria_req . ' /></p>',

	  'url' =>
		'<p class="comment-form-url"><label for="url">' . __( 'Website', 'ccb' ) . '</label>' .
		'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
		'" size="30" /></p>',
	  'comment_field' =>
		'<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" minlength=10 aria-required="true" required></textarea></p>'
	);

	//$comments_args = array(
	//	'fields' => apply_filters( 'comment_form_default_fields', $fields ),
	//	'comment_notes_after' => ''
	//);
	comment_form($comments_args); 
	?>

</div><!-- #comments -->
