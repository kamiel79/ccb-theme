<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Creative Choice Blog
 */
 
$the_grid = CCB_GRIDFRONT;
get_header(); 
/* Change settings if there was a search request, because Wordpress still uses this template... */
if( isset($_REQUEST['s'])) :
		$the_grid = CCB_GRID;
endif;
?>

	<section id="primary" class="content-area <?php ccb_classes('showsidebar'); ?>" >
		<main id="main" class="site-main" role="main">
		<?php 
			if (is_main_site()) :
				while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

		<?php endwhile; // end of the loop.
			  wp_reset_postdata();
			  endif; ?>
		<?php 

			// Show a secondary query is set in options
			$paged = (get_query_var('page')?get_query_var('page'):1);
			$args = array(
				'post_type' => 'post',
				'post_status'=>'publish',
				'paged' => $paged,
				'posts_per_page' => 5
			);
			$ccb_posts = new WP_Query($args);
			if ( $ccb_posts->have_posts() ) : ?>

			<div id="container" class="<?php echo $the_grid; ?>">
				<?php /* The grid-sizer has the same classes for column width as the entries. This is useful for targeting elements */ ?>
				<div class='grid-sizer col col<?php echo CCB_COLS; ?>'></div>
				<?php while ( $ccb_posts->have_posts() ) : $ccb_posts->the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', $the_grid);
				?>
			
				<?php endwhile; ?>
			</div>
			<?php if ($ccb_posts->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
			  <nav class="nav-links">
				<div class="nav-previous">
				  <?php echo get_next_posts_link( 'Older Entries', $ccb_posts->max_num_pages ); // display older posts link ?>
				</div>
				<div class="nav-next">
				  <?php echo get_previous_posts_link( 'Newer Entries' ); // display newer posts link ?>
				</div>
			  </nav>
			<?php } ?>			

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
