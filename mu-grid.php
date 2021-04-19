<?php
/* Mu-grid 
 * Grid for multisite results
 * Included on search page if multisite is used
 */
global $current;
global $paged;
$current= $_GET['paged'];
if ($current<1) $current = 1;		//set current page
global $s;
global $from;
global $until;
?>
	<?php
	$posts = multisite::mu_posts(intval(get_query_var( 'posts_per_page' )), $current, $s, date("d-m-Y", strtotime($from)), date("d-m-Y", strtotime($until) ));

	if ($posts) : 		?>
		<div id="container" class="<?php echo CCB_GRID; ?>">
		<div class='grid-sizer col col<?php echo CCB_COLS; ?>'></div>
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
	<?php 
	else : 
		get_template_part( 'content', 'none' );

	endif; ?>

<?php multisite::mu_paging_nav($current); ?>