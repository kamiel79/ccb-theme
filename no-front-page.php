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

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php 
			if (have_posts() ) : ?>

			<div id="container" class="grid-<?php echo $the_grid; ?>">
				<?php /* The grid-sizer has the same classes for column width as the entries. This is useful for targeting elements */ ?>
				<div class='grid-sizer col col<?php echo CCB_COLS; ?>'></div>
				<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', $the_grid);
				?>
			
				<?php endwhile; ?>
			</div>
				<?php ccb_paging_nav(); ?>		

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
