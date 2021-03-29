<?php
/**
 * Template for all cases where multiple articles are shown, except archives
 * @package Creative Choice Blog
 */
 
 $tag_list = get_the_tag_list( '<span class="ccb_taglist">', ', ', '</span>' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("listing"); ?>>
	<?php if (has_post_thumbnail()): ?>
		<center class="featuredwrapper">
		<img class="featured" src="<?php echo ccb_thumburl($post->blog_id, $post->ID, "large"); ?>"/>
		</center>
		<?php endif; ?>
		
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		
	</header><!-- .entry-header -->
	
	<div class="entry-content">
		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ccb' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ccb' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : ?>
				<?php ccb_posted_on(); ?>
			<?php endif; ?>
			<?php if ( 'post' == get_post_type() ) : // Hide category for pages on Search ?>
				<?php
					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( __( ', ', 'ccb' ) );
					if ( $categories_list && ccb_categorized_blog() ) :
				?>
				<span class="cat-link">
					<?php echo $categories_list; ?>
				</span>
				<?php echo $tag_list;?>
				<?php endif; // End if categories ?>

			<?php endif; // End if 'post' == get_post_type() ?>
		
			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'ccb' ), __( '1 Comment', 'ccb' ), __( '% Comments', 'ccb' ) ); ?></span>
			<?php endif; ?>
			<?php edit_post_link( __( 'Edit', 'ccb' ), '<span class="edit-link">', '</span>' ); ?>
		</div>	<!-- .entry-meta -->
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->