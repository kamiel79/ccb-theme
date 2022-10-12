<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 * 
 * @package Creative Choice Blog
 */
 ?>

	</div><!-- #content -->
	<div class="footercontainer col3">
 			<!-- footer widgets -->
			<div class="col">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer1')) : ?>
			<?php endif; ?>
			</div>
			<div class="col">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer2')) : ?>
			<?php endif; ?>
			</div>
			<div class="col">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer3')) : ?>
			<?php endif; ?>
			</div>
	
			<footer id="colophon" class="site-footer" role="contentinfo">
				<a href="http://creativechoice.org">
					<div class="site-info">
						<?php printf( __( '%1$s', 'ccb' ), wp_get_theme()); ?>
						<a href="#" class="tothetop"></a>
					</div><!-- .site-info -->
				</a>
			</footer><!-- #colophon -->

	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

<?php do_action('final_words'); ?>
</body>
</html>
