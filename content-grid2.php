<?php
/*
	Template for showing posts in a grid layout.

	@Package ccb
	@since 1.0
	@multisite enabled
*/

//global $post;
global $ccb_cols;

if (!isset($ccb_cols)) $ccb_cols = CCB_COLS;	//override number of columns  

/* prepare arrays for special treatment of post formats */
if (!isset($noimg_formats)) $noimg_formats = explode(",",CCB_NOIMG_FORMATS);
$nofooter_formats = explode(",",CCB_NOFOOTER_FORMATS);
$notitle_formats = explode(",",CCB_NOTITLE_FORMATS);
$noexcerpt_formats = explode(",",CCB_NOEXCERPT_FORMATS);
$sharer_formats = explode(",",CCB_SHARER_FORMATS);

/* get category and tag list */
$category_list = '<span class="cat_list"><span>' . get_the_category_list(__( ', ', 'ccb')) . '</span></span>';
$tag_list = get_the_tag_list( '<span class="tags_list"><span>', ', ', '</span></span>' );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("entry listing col col{$ccb_cols}"); ?> >

	<div class="entry-border">
		<?php if (has_post_format( $sharer_formats )) : ?>
		<?php endif;?><!--/ sharer formats-->
		
		<?php if (!has_post_format( $notitle_formats )) : ?>
			<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
			<h5 class="post-title"><span><?php the_title(); ?></span></h5>
			</a>
			
		<?php endif;?><!--/ notitle formats-->
	
		<?php if (!has_post_format( $noimg_formats )) : ?>
		<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
			<?php $thumburl = ccb_thumburl($post->blog_id, $post->ID, "medium"); 
			if ("" != $thumburl) : 	?>
			<div class='thumbcrop' style='<?php //echo "background-image: url(". $thumburl.")";?>'>
				<img src="<?php echo $thumburl; ?>" onerror="this.style.display='none'"></img>
			</div><!--/.thumbcrop-->
			<?php endif; ?>
		</a>		
		<?php endif;?><!--/ noimg formats-->	
	
		<div class="details">
			<?php if (!has_post_format( $noexcerpt_formats )) : ?>
				<div class='excerpt'>
				<?php 
					echo get_the_excerpt();
					?>
				</div>
			<?php endif; ?><!-- / noexcerpt_formats -->
			<?php if (!has_post_format( $nofooter_formats )) : ?>
			<div class="entry-footer">
				<div class="entry-metas">
					<div class="entry-meta"><?php ccb_posted_on("","",false);	?></div>
					<div class="entry-meta">
						<?php echo $tag_list;	?>
					</div>
					<div class="entry-meta">
						<?php echo $category_list;	?>
					</div>
				</div>
			</div><!-- /.entry-footer -->
			<?php endif; ?><!-- / nofooter_formats -->
		</div><!-- /.details -->
		</a>		
	</div><!--/.entry-border-->
</article><!--/.entry-->