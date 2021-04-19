<?php
/**
 * Displays excerpts from posts in a chronological list.
 *
 * Updated 14 Jan 2017
 * @package Creative Choice Blog
 */
 

/* prepare arrays for special treatment of post formats */
$noimg_formats = explode(",",CCB_NOIMG_FORMATS);
$nofooter_formats = explode(",",CCB_NOFOOTER_FORMATS);
$notitle_formats = explode(",",CCB_NOTITLE_FORMATS);
$noexcerpt_formats = explode(",",CCB_NOEXCERPT_FORMATS);
$sharer_formats = explode(",",CCB_SHARER_FORMATS);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("entry"); ?>>
							
		<header class="entry-header">
			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php ccb_posted_on(); ?>
			</div><!-- .entry-meta -->
			<div class="line"></div>
			<div class="entry-meta category">
				<?php $categories = get_the_category();
				if ( ! empty( $categories ) ) {
					echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
					}
				?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->
		<div class="details">
				
			<h5><a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>"><span class="post-title"> <?php the_title(); ?></span></a></h5>
			<?php if (!has_post_format( $noimg_formats )) : ?>
			
					<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
					<?php $thumburl = ccb_thumbnail($post->ID, "large", "url"); 
					if ("" != $thumburl) : 	?>
					<div class='thumbcrop' style='<?php //echo "background-image: url(". $thumburl.")";?>'>
						<IMG src="<?php echo $thumburl; ?>" onerror="this.style.display='none'"></IMG>
					</div><!--/.thumbcrop-->
					<?php endif; ?>
					</a>		
			<?php endif;?><!--/ noimg formats-->
			<div class="post-excerpt">
				<?php the_excerpt();
			?>
			</div><!--.post-excerpt-->

		</div><!--/.entry-details -->  

	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'ccb' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'ccb' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'ccb' ), __( '1 Comment', 'ccb' ), __( '% Comments', 'ccb' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'ccb' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->