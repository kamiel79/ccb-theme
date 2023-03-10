<?php
/*
	Single post template
	@package Creative Choice Blog
	@since 1.0
*/

/* get category and tag list */
$category_list = "<span class='cat_list'>".get_the_category_list(' ')."</span>";
$tags_list = get_the_tag_list('<span class="tags_list">',', ', '</span>');
?>

	<header class="entry-header">
		<?php
		/** Show thumbnail here if not shown above (no fullwidth) and no ccb_headmedia **/
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.		
			
			$large_image_dimension = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full');

			$thumburl = ccb_thumburl($post->blog_id, $post->ID, "large");

			if ( ( $large_image_dimension[1] ) < CCB_FULLWIDTH && '' == get_post_meta($post->ID, 'ccb_headmedia', true)) { ?>
				
				<div><img src='<?php echo $thumburl; ?>' class='<?php echo ccb_featured_image_classes($post->ID) ?>' />
					<?php 
						$ccb_byline = get_post_meta($post->ID, 'ccb_byline', $single = true);
						if ($ccb_byline) { echo "<div class='ccb_byline'>" . $ccb_byline . "</div>"; }
					?>			
				</div>
			<?php }
		}
		/** Show Post Title if not shown above **/
		$ccb_titleonimage = get_post_meta($post->ID, 'ccb_titleonimage', $single = true);
		if (!$ccb_titleonimage) { 
			?>
			<a href="<?php the_permalink();?>" rel="bookmark">
			<?php 
				/** Show title kicker if defined */
				if ($kicker = get_post_meta(get_the_ID(),'title-kicker', true)) {
					echo "<span class='kicker'>". $kicker . "</span>";
				} 
			 ?>
				<h1 class="entry-title">			 
			 <?php the_title(); ?>
			</h1>
			</a>
		<?php } ?> 	
		<?php if ( !wp_is_mobile()): ?>
		<div id="metacolumn" class="metacolumn entry-metas">
				<div class="entry-meta avatar">
					<?php if (get_avatar(get_the_author())) echo get_avatar (get_the_author(), 40); ?>
				</div>		
				<div class="entry-meta posted_by">
					<?php ccb_posted_by(__("", "ccb"));	?>
				</div>
				<div class="entry-meta posted_on">
					<?php ccb_posted_on(__("", 'ccb'), __("Updated", 'ccb'), false);	?>
				</div>
				
				<?php if ($tags_list) echo "<div class=\"entry-meta \">". $tags_list . "</div>";	?>
				<?php if ($category_list) echo "<div class=\"entry-meta \">". $category_list . "</div>";	?>
		
				<?php if ( ! dynamic_sidebar( 'entrymetawidgets' ) ) : ?>
				<?php endif; ?>
		</div>
		<?php else: ?>
			<div id="metacolumn" class="mobile_metacolumn entry-metas">
				<div class="entry-meta avatar">
					<?php if (get_avatar(get_the_author())) echo get_avatar (get_the_author(), 64); ?>
				</div>

				<div class="entry-meta posted_by">
					<?php ccb_posted_by();	?>
				</div>
				<div class="entry-meta posted_on">
					<?php ccb_posted_on(__("", 'ccb'), __("updated", 'ccb'), false);	?>
				</div>
				<?php if ($category_list) echo "<div class=\"entry-meta \">". __("", 'ccb') . $category_list . "</div>";	?>
		
			</div>
		<?php endif; ?>	
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php 
		the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ccb' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
		
	<footer class="entry-footer">		

		<?php edit_post_link( __( 'Edit', 'ccb' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
