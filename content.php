<?php
/*
Template for showing posts in a grid layout.

@Package ccb
@since 1.0
@multisite enabled
*/

if (!isset($col)) $col = "col".CCB_COLS;	//override number of columns  

/* get category and tag list */
$category_list = '<span class="ccb_catlist"><span>' . get_the_category_list(__( ', ', 'ccb')) . '</span></span>';
$tag_list = get_the_tag_list( '<span class="ccb_taglist"><span>', ', ', '</span></span>' );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("entry listing col {$col}"); ?> >

	<div class="entry-border">
		<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
				<h5 class="post-title"><?php the_title(); ?></h5>
		</a>
			
		<?php $thumburl = ccb_thumburl($post->blog_id, $post->ID, "medium"); ?>
		<div class='thumbcrop' style='<?php echo "background-image: url(". $thumburl.")";?>'>
			<img src="<?php echo $thumburl; ?>"></IMG>
		</div><!--/.thumbcrop-->
				
		<div class="details">
				
			<div class='excerpt'>
			<?php 
				echo get_the_excerpt(25);
			 ?>
			</div>
	
			<div class="entry-footer">
				<div class="entry-metas">
					<div class="entry-meta">
						<?php ccb_posted_on("",false);	?>
					</div>
					<div class="entry-meta">
						<?php ccb_posted_by("");	?>
					</div>
					<div class="entry-meta">
						<?php echo $tag_list;	?>
					</div>
					<div class="entry-meta">
						<?php echo $category_list;	?>
					</div>
				</div>
			</div><!-- /.entry-footer -->
		</div><!-- /.details -->
		
		</a>
		
		
	</div><!--/.entry-border-->

</article><!--/.entry-->