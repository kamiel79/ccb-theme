<?php
/**
 * Template Name: Mu-front
 *
 * Frontpage. Standard page plus a grid of articles from the multisite
 *
 * @package Creative Choice Blog
 * @since 1.0
 */

get_header();

$current			= 1;	/* start with page 1 */

?>

<section id="primary" class="content-area <?php ccb_classes('showsidebar'); ?>">
		<main id="main" class="site-main archive" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

		<?php endwhile; // end of the loop. ?>
		
			<?php	/* Show most recent post in network if multisite exists */ 
			if (is_multisite()) : 

			  echo "<h1>", __("Latest Articles", "ccb"),"</h2>";
			  $posts = multisite::mu_posts(CCB_COLS * 2, $current, $s); 
			
			  if ($posts) : ?>
				<div id="container" class="<?php echo CCB_GRIDFRONT, " col", ccb_cols(); ?>">	
					<?php
					
					foreach ($posts as $post) :
						setup_postdata($post);
						$post->guid = multisite::mu_permalink($post->ID, $post->blog_id);
						switch_to_blog( $post->blog_id );
						get_template_part( 'content', CCB_GRIDFRONT );
						restore_current_blog();
					endforeach;
					?>

				</div><!-- .container -->
				<?php 
				/* We show the first page here and refer to the Archive template for more  
				multisite::mu_paging_nav($current); */
				?>
			  <?php endif; ?>
		  <?php endif;  /* class_exists multisite */?>
				
	</main><!-- #main -->
</section><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>