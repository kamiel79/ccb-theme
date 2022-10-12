<?php
/**
 * The template for displaying Author archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Creative Choice Blog
 */

get_header(); ?>

	<section id="primary" class="content-area">

		<main id="main" class="site-main archive" role="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						printf( __( 'Author: %s', 'ccb' ), '<span class="vcard">' . get_the_author() . '</span>' );
					?>
				</h1>
			<div class="author box"> 
			<?php 
				echo get_avatar( get_the_author_meta( 'ID' ), 200 ); 
				the_author_meta('description');
			?>
			<span class="clear"></span>
			</div>
			</header><!-- .page-header -->

			<div id="container" class="grid-<?php echo CCB_GRID; ?>">
			
			<?php /* The grid-sizer has the same classes for column width as the entries. This is useful for targeting elements */ ?>
			<div class='grid-sizer col col<?php echo CCB_COLS; ?>'></div>
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