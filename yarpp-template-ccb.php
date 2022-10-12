<?php 
/*
YARPP Template: CCB
Author		: kamiel79
Description	: A YARPP template with thumbnails for CCB.
Date		: December 2015
*/
?>
<?php if (have_posts()):?>
<div class="yarpp-container">
<h3><?php _e("Related Posts", "ccb"); ?></h3>
<?php 
/* Show amount of found posts or limit set in plugin, whatever is less */
$cnt = min($related_query->query["showposts"], $related_query->found_posts); 
?>
<ol>
	<?php while (have_posts()) : the_post(); ?>
	<a href="<?php the_permalink() ?>" rel="bookmark">
	<li class="col col<?php echo $cnt ?>"><h2><?php the_title(); ?></h2>
		<?php 
		$size = (3-floor($cnt/3))*100;	/* Calculate size of thumbnail */
		if (has_post_thumbnail()) : 
			echo "<div style='position:relative'class='thumbcrop yarpp-image'>";
			the_post_thumbnail( array($size, $size) );
			echo "</div>";
		else :
			$thumburl = ccb_thumburl($post->blog_id, $post->ID, "medium");
			if ("" != $thumburl) : 	?>
			<div style="position:relative" class="thumbcrop yarpp-image"><img src="<?php echo $thumburl; ?>" height="<?php echo $size;?>" width="<?php echo $size;?>"></img></div>
			<?php endif;
		endif; ?>
		<p><?php //echo sanitize_text_field(get_the_excerpt()); ?></p>
	</li>
	</a>
	<?php endwhile; ?>
</ol>
</div>
<?php else: ?>

<?php endif; ?>
