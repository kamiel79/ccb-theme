<?php
/**
 * The Template for displaying a single post.
 *
 * Shows fullwidth thumbnail if thumbnail image is large enough
 * @package Creative Choice Blog
 */

get_header(); ?>

	<?php 	
		/* Show fullwidth media if shared in meta field. Interprets as image if ends with image filetype
			Using the plugin "Fluid Video Embed"
		*/
		$media = get_post_meta($post->ID, 'ccb_headmedia', true);
		if ('' != $media) {
			echo "<DIV class='ccb_fullwidth'>";

			if (!preg_match('/\.(gif|png|bmp|jpe?g)$/i', $media) && shortcode_exists( 'fve' ) ) {
				echo do_shortcode('[fve]'.$media.'[/fve]'); 
			}
			else {
				echo "<IMG SRC='$media' />";
			}
			echo "</DIV>";
		}
	
		/* Show fullwidth thumbnail if large enough */
		if ( ccb_has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
			$large_image_dimension = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full');
			// check for fullwidth thumbnail
			if ( $large_image_dimension && $large_image_dimension[1] >= CCB_FULLWIDTH ) {
				echo "<DIV class='ccb_fullwidth'>";
				if (wp_is_mobile()) 
					the_post_thumbnail("thumbnail", array( 'class' => 'fullwidth' ));	
				else
					the_post_thumbnail("full", array( 'class' => 'fullwidth' ));
				/** custom_field for title on fullwidth image **/
				$ccb_titleonimage = get_post_meta($post->ID, 'ccb_titleonimage', $single = true);
				if ($ccb_titleonimage) {?>
					<a href="<?php the_permalink($post->ID);?>" rel="bookmark">
					<h1 class="entry-title">
					<?php the_title(); ?>
					</h1>
					</a>
					<?php 
				}
				echo "</DIV>";
			}
		} 

			
	?>
	
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php get_template_part( 'content', 'single' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) :
					comments_template();
				endif;
			?>
			</article>
			<?php ccb_post_nav(); ?>
			
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>