<?php
/**
 * The template for displaying Archive pages.
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
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							_e('All about: ', 'ccb');
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'ccb' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'ccb' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'ccb' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ccb' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'ccb' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ccb' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'ccb' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'ccb');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'ccb');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'ccb' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'ccb' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'ccb' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'ccb' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'ccb' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'ccb' );

						else :
							_e( 'Archives', 'ccb' );

						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;

					?>
			</header><!-- .page-header -->

			<div id="container" class="<?php echo CCB_GRID; ?>">

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
		