<?php
/**
 * The template for displaying Author archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Creative Choice Blog
 */

get_header(); ?>

	<section id="primary" class="content-area <?php ccb_classes('showsidebar'); ?>">

		<main id="main" class="site-main archive" role="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php single_cat_title(); ?>
				</h1>
			<div class="category box"> 
			<?php 
			  echo category_description();
			?>
			<span class="clear"></span>
			</div>
			</header><!-- .page-header -->

			<div id="container" class="<?php echo CCB_GRID, " col", ccb_cols(); ?>">

			<?php 
				while ( have_posts() ) : the_post();
					get_template_part( 'content', CCB_GRID);
				endwhile; 
			?>
			</div>

			<?php ccb_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>	