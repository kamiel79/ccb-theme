<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Creative Choice Blog
 */

if ( ! function_exists( 'ccb_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function ccb_paging_nav($query='wp_query') {
	/* Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	} */
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'ccb' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( '<span class="meta-nav"></span>'.__('Older posts', 'ccb' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts ', 'ccb' ).'<span class="meta-nav"></span>' ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'ccb_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function ccb_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'ccb' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-arrowleft"></div><div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'ccb' ) );
				next_post_link('<div class="nav-next">%link</div>', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'ccb' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'ccb_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * When false is passed as 2nd argument, the byline is skipped.
 */
function ccb_posted_on($posted_on_text = "", $updated_text = "", $byline_text = "") {
	/** Prepare <time> tags **/
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	$time_string = sprintf( $time_string,
	esc_attr( get_the_date( 'c' ) ),
	esc_html( get_the_date() ));
	$posted_on = sprintf(
		$posted_on_text." "._x( '%s', 'post date', 'ccb' ),
		'<span class="posted-on"><a href="' . esc_url( ccb_get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>');
	
	// If a NEWER modified date, share modified date, use get_the_time for time comparison
	if ( strtotime(get_the_date( 'U' )) < strtotime(get_the_modified_date( 'U' )) ) {
		$time_string2 = '<time class="updated" datetime="%1$s">%2$s</time>';
		$time_string2 = sprintf ($time_string2,
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
		);
		$posted_on .= "<span class='updated-on'>".sprintf ($updated_text." "._x( '%s', 'modified date', 'ccb'),
		'<a href="' . esc_url( ccb_get_permalink() ) . '" rel="bookmark">' . $time_string2 . '</a></span>');
	}
	$byline="";
	if ($byline_text) {
		$byline = '<span class="byline">' . sprintf(
		_x( "{$byline_text} %s", "post author", "ccb" ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span></span>'
		);
	}

	echo $posted_on.$byline;

}
endif;

/** 
 *  Posted by
 * 
 */
function ccb_posted_by($byline_text = "") {
	$byline = sprintf(
		_x( "{$byline_text} %s", 'post author', 'ccb' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
	echo '<span class="byline"> ' . $byline . '</span>';
}
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function ccb_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'ccb_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'ccb_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so ccb_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so ccb_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in ccb_categorized_blog.
 */
function ccb_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'ccb_categories' );
}
add_action( 'edit_category', 'ccb_category_transient_flusher' );
add_action( 'save_post',     'ccb_category_transient_flusher' );
