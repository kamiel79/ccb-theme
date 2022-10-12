<?php
/**
 * Template Name: Mu-archive
 *
 * Archive for multisite
 *
 * @package Creative Choice Blog
 * @since 1.0
 */

get_header();
global $current;
global $s;
if (array_key_exists('paged', $_GET)) $current = max($_GET['paged'],1);
global $from;
global $until;

?>
<section id="primary" class="content-area">
		<main id="main" class="site-main archive" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'page' ); ?>
				<?php endwhile; // end of the loop. ?>
		
				<?php	/* Show most recent post in network if multisite exists */ 
				if (is_multisite() and class_exists('multisite')) : 
				?>
				<div class='grid-sizer col col<?php echo CCB_COLS ?>'></div>
				<?php
				$posts = multisite::mu_posts(intval(get_query_var( 'posts_per_page' )), $current, $s, $from, $until); 
				if ($posts) : ?>
					<div id="container" class="<?php echo CCB_GRID; ?>">
						
						<?php
						foreach ($posts as $post) :
							setup_postdata($post);
							$post->guid = multisite::mu_permalink($post->ID, $post->blog_id);
							switch_to_blog( $post->blog_id );
							get_template_part( 'content', CCB_GRID );
							restore_current_blog();
						endforeach;
						
						?>
					</div><!-- .container -->
					<?php multisite::mu_paging_nav($current); ?>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>

				<?php endif; ?>
				<?php endif; ?>
		</main><!-- #main -->
</section><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>