<?php
/*
Template for showing posts in a squared grid layout.

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

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("entry squares col col" . ccb_cols()); ?> >
	<div class="entry-border" >
			<?php $thumburl = ccb_thumburl($post->blog_id, $post->ID, "medium"); ?>
			<div class='thumbcrop'
				<?php if (!has_post_format( $noimg_formats )) : ?>
					style='<?php echo "background-image: url(". $thumburl.")";?>'>
					<img src="<?php echo $thumburl; ?>"></img>
				<?php else : echo ">"; 	
				endif; ?>				
				<div class="details <?php if (has_post_format( $noimg_formats )) echo "textonly" ?>">
						<?php if (!has_post_format( $notitle_formats )) : ?>
							<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
							<h5 class="post-title"><span><?php the_title(); ?></span></h5>
							</a>
						<?php endif;?><!--/ notitle formats-->
					
						<?php if (!has_post_format( $noexcerpt_formats )) : ?>
						<a href="<?php ccb_permalink($post->blog_id, $post->ID) ?>" title="<?php the_title(); ?>">
						<div class='excerpt'>
						<?php 
							echo get_the_excerpt();
						 ?>
						 </a>
						</div>
						<?php endif; ?><!-- / noexcerpt_formats -->
					</a>
				</div><!-- /.details -->
			</div><!--/.thumbcrop-->
		</a>
	</div><!--/.entry-border-->

</article><!--/.entry-->