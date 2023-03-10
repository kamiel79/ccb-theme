<?php
/*
Template Name: Search Form
Since: 2.0
*/
?>
<?php

get_header(); 
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php get_search_form(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php 
	get_sidebar();
	get_footer(); 
?>