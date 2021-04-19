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
	<div class="footercontainer"><!-- footer widgets -->
		<div class="footerwrapper">
			<div class="col col3 floatleft">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer1')) : ?>
			<?php endif; ?>
			</div>
			<div class="col col3 floatleft">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer2')) : ?>
			<?php endif; ?>
			</div>
			<div class="col col3 floatleft">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer3')) : ?>
			<?php endif; ?>
			</div>
	
			<footer id="colophon" class="site-footer" role="contentinfo">
				<div class="site-info">
					<?php printf( __( '%1$s', 'ccb' ), wp_get_theme()); ?>
					<a href="#" class="tothetop"></a>
				</div><!-- .site-info -->
			</footer><!-- #colophon -->
		</div>
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
