<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Creative Choice Blog
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'ccb' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<center>
					<p>
						<?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ccb' ); ?>

						<?php get_search_form(); ?>
					</p>
					</center>
					
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
