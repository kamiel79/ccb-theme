<?php
/**
 * The Header.
 * Displays all of the <head> section and everything up till <div id="content">
 * Displays an optional breadcrumb trail
 * @package Creative Choice Blog
 */
 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta property="og:image" content="<?php echo get_theme_mod('ccb_header_image');?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page<?php echo CCB_SIDEBARINITIAL; ?>" class="hfeed site <?php if ((CCB_SIDEBARINITIAL || is_archive() || (is_page() && get_post_meta(get_the_ID(), 'ccb_sidebar') !=''))) echo " withsidebar"; if (is_archive() || is_home() || is_search()) echo ' do-grid'; ?>">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'ccb' ); ?></a>
	
	<header id="masthead" class="site-header" role="banner">
		<?php if (!(wp_is_mobile())) : /* top navigation bar */ ?>
			<nav id="top-navigation" class="top-navigation" role="navigation">
				<?php if ( has_nav_menu( 'top-menu' ) ) : ?>
					<?php 
					wp_nav_menu( array( 'theme_location' => 'top-menu' ) ); 
					?>
				
				<?php endif; #has_nav menu ?>
			</nav>
		<?php endif; ?>
		<div class="site-branding" <?php if (CCB_HEADER_IMAGE) echo "style='background-image:url(".CCB_HEADER_IMAGE . ")'"; ?>>
								
						<?php if ( has_custom_logo())  {
						   the_custom_logo('site-logo');
						} ?>
	
					<span class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a></span>
					<span class="site-description"><?php bloginfo( 'description' ); ?></span>
		
			<div class="site-branding-bg"></div>
		</div>
		<nav id="mobile-navigation" class="mobile-navigation">
			<!-- Mobile Menu Buttons -->
					<?php if (wp_is_mobile()): ?>
					<button class="menu-toggle"></button>
					<button class="menu-ccb-context show_sidebar refresh"></button>
					<button class="toggletags1 menu-ccb-tags"></button>
					<button class="toggletags2 menu-ccb-search"></button>
					<?php endif; ?>
		</nav>
		<nav id="site-navigation" class="main-navigation movingparts" role="navigation">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
				
				<?php 
				wp_nav_menu( array( 'theme_location' => 'primary' ) ); 
				?>
			<?php endif; #has_nav menu ?>
		</nav>
		
	</header>
	<?php /* Display the headbar for switchable widgets */
		ccb_headbar(); 
		
		?>
	
	<div id="content" class="site-content">
	<?php if (CCB_BREADCRUMB) breadcrumb_trail(); ?>
	
	<?php 
	/* Get the current page value */
	$current			= get_query_var('paged');			// Paging
	$s					= get_query_var('s');				// Search var
	
	?>
