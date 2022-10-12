<?php
/*
	Template for showing posts in a grid layout.

	@Package ccb
	@since 1.0
	@multisite enabled
*/

/* prepare arrays for special treatment of post formats */
if (!isset($noimg_formats)) $noimg_formats = explode(",",CCB_NOIMG_FORMATS);
$nofooter_formats = explode(",",CCB_NOFOOTER_FORMATS);
$notitle_formats = explode(",",CCB_NOTITLE_FORMATS);
$noexcerpt_formats = explode(",",CCB_NOEXCERPT_FORMATS);
$sharer_formats = explode(",",CCB_SHARER_FORMATS);

/* get category and tag list */
$category_list = '<span class="cat_list"><span>' . get_the_category_list(__( ', ', 'ccb')) . '</span></span>';
/* Get category name of first category */
$cat = get_the_category();
if ( ! empty( $cat ) ) 	$cat_name = $cat [0]->cat_name;
else $cat_name = __("General", "ccb");

$tag_list = get_the_tag_list( '<span class="tags_list"><span>', ' ', '</span></span>' );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("entry listing"); ?> >

	<div class="entry-border">

		<?php if (!has_post_format( $notitle_formats )) : ?>
			<?php ccb_posted_on("",false);	?>
			<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
			<h5 class="post-title"><span><?php the_title(); ?></span></h5>
			</a>
		<?php endif; ?>
		<?php if (has_post_format( $sharer_formats )) : ?>
			<div class="entry-content">
			<?php echo the_excerpt();		//display excerpt of quote of aside ?>
			</div>
		<?php endif;?><!--/ notitle formats-->
			<div class="cat_name"><?php echo $cat_name; ?></div>
		<?php if (!has_post_format( $noimg_formats )) : ?>
		<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
			<?php $thumburl = ccb_thumburl($post->blog_id, $post->ID, "medium"); 
			if ("" != $thumburl) : 	?>
			<div class='thumbcrop'>
				<img src="<?php echo $thumburl; ?>" onerror="this.style.display='none'"></img>
			</div><!--/.thumbcrop-->
			<?php endif; ?>
		</a>		
		<?php endif;?><!--/ noimg formats-->	
	
		<div class="details">
			<?php if (!has_post_format( $nofooter_formats )) : ?>
			<div class="entry-footer">
				<?php echo $tag_list;	?>
			</div><!-- /.entry-footer -->
			<?php endif; ?><!-- / nofooter_formats -->
		</div><!-- /.details -->
		</a>		
	</div><!--/.entry-border-->
</article><!--/.entry-->