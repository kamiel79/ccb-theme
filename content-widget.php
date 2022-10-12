<?php
/*
Template for showing posts in a widget.

@Package ccb
*/
?>
<?php $thumb_url = ccb_thumbnail(get_the_ID(), 'medium', 'url'); ?>
<div class="container">
<article id="post-<?php the_ID(); ?>" <?php post_class("entry"); ?> >
	<div class="entry-border entry-border">
     
			<?php if ($thumb_url) : ?>
						<div class='thumbcrop' style='background-image: url("<?php echo $thumb_url ?>")'>
							<a href='<?php the_permalink(' ') ?>'>
							<IMG src="<?php echo $thumb_url; ?>" style="opacity:0"></IMG></a>
						</div>
			<?php endif; ?>
			<div class="details">
				<h5 class="post-title"><a href="<?php the_permalink(' ') ?>" title="<?php the_title(); ?>" target="_blank"><span><?php the_title(); ?></span></a></h5>
				
				<div class='excerpt'>
				<?php $excerpt = get_the_excerpt(); ?>
				<?php echo implode(' ', array_slice(explode(' ', $excerpt), 0, SHORT_EXCERPT));	 ?>
	
				</div>
				
				<div class="entry-meta">
					<?php ccb_posted_on(); ?>
					<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
					 <span class="comments-link"><?php comments_popup_link( '<span class="comment-icon"></span>', __( '1 Comment', 'ccb' ), __( '% Comments', 'ccb' ) ); ?></span>
					<?php endif; ?>
				</div><!-- .entry-meta -->
			</div>
		
</div><!--/.entry-border-->

</article><!--/.entry-->
</div>