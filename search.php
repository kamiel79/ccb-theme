<?php

get_header(); 
global $from;
global $until;
global $current;
?>

	<section id="primary" class="content-area <?php ccb_classes('showsidebar'); ?>">
		<main id="main" class="site-main search" role="main">

		<header class="page-header"><div class="triangle"></div>
			<h1 class="page-title"><?php printf( __('Search Results for: %s', 'ccb' ), '<span>' . get_search_query() . '</span>' ); ?>
			<?php 	

				$from = date("Y-m-d", strtotime($from));
				$until = date("Y-m-d", strtotime($until)); 
				if ($current > 1) echo "(" . $current . ")";	
				if ($from !='undefined' and $from !="" and $from !='1970-01-01') echo "<br/>" . __("From: ", "ccb") . date("d-m-Y", strtotime($from) );
				if ($until !='undefined' and $from !="" and $until !="" and $until !=date('Y-m-d') and $from !='1970-01-01') echo " - "; else echo "<br/>";
				// Show until when earlier than current date
				if ($until !="" and $until!= date('Y-m-d')) echo __("Until: ", "ccb") . date("d-m-Y", strtotime($until) );;
			?>
			</h1>
			
		</header><!-- .page-header -->
		<?php 
			/* Multisite? */
			if (CCB_MULTISITE) :
			
				/* Get the multisite grid */
				get_template_part ("mu-grid");
			
			else :
			if ( have_posts() ) : ?>
				<?php
				/* If fewer posts than CCB_GRID, make them larger to fill up the screen */
				if ($GLOBALS['wp_query']->post_count < CCB_COLS)
					$cols = $GLOBALS['wp_query']->post_count . " no-grid";
				else $cols = CCB_COLS;
				
				?>
				<div id="container" class="col <?php echo CCB_GRID, " col", $cols; ?>"><?php 
							while ( have_posts() ) : the_post(); 
								get_template_part( 'content', CCB_GRID);
							endwhile; 
						?></div>
				<?php ccb_paging_nav(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; 
		endif ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php 
get_sidebar();
get_footer(); 
?>