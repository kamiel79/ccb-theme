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
<meta property="og:image" content="<?php echo of_get_option('ccb_header');?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page<?php echo CCB_SIDEBARINITIAL; ?>" class="hfeed site <?php ccb_mobile_class(); if (!wp_is_mobile() && (CCB_SIDEBARINITIAL || is_archive() || (is_page() && get_post_meta(get_the_ID(), 'ccb_sidebar') !=''))) echo "withsidebar" ?>">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'ccb' ); ?></a>
	
	<header id="masthead" class="site-header" role="banner">
		
		<nav id="top-navigation" class="top-navigation" role="navigation">
			<?php if ( has_nav_menu( 'top-menu' ) ) : ?>
				<?php 
				wp_nav_menu( array( 'theme_location' => 'top-menu' ) ); 
				?>
			
			<?php endif; #has_nav menu ?>
		</nav><!-- #top-navigation -->
		
		<div class="site-branding" style="background-image:url('<?php echo of_get_option('ccb_header');?>')">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<div id="ccb-logo">
					<span class="site-title"><?php ccb_logo(); ?></span><br/>
					<span class="site-description"><?php bloginfo( 'description' ); ?></span>
				</div>
			</a>
			<div class="site-branding-bg"></div>
		</div>
		
		<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<!-- Mobile Menu Buttons -->
				<button class="menu-toggle"></button>
				<button class="menu-ccb-context show_sidebar refresh"></button>
				<div class="ccb_navsearch">
					<?php echo get_search_form(false) ?>
				</div>
				<?php 
				wp_nav_menu( array( 'theme_location' => 'primary' ) ); 
				?>
			<?php endif; #has_nav menu ?>
		</nav>
		
	</header>
	<?php /* Display the headbar for switchable widgets */
		ccb_headbar(); 
		/* Where to place this? Think! In a hook in functions, find out which one */
		if(CCB_CLIP_SHAPE == "heart") {
			?>
			<svg width="0" height="0" viewBox="0 0 100 100">
		    	<clipPath id="clip_heart" clipPathUnits="objectBoundingBox" transform="scale(0.03 0.03)">
		        <path d="M23.6,0c-3.4,0-6.3,2.7-7.6,5.6C14.7,2.7,11.8,0,8.4,0C3.8,0,0,3.8,0,8.4c0,9.4,9.5,11.9,16,21.2c6.1-9.3,16-12.1,16-21.2C32,3.8,28.2,0,23.6,0z"></path>
		  		</clipPath>
			<?php
		}
		?>
	
	<div id="content" class="site-content">
	<?php if (CCB_BREADCRUMB) breadcrumb_trail(); ?>
	
	<?php 
	/* Get the current page value */
	$current			= get_query_var('paged');			// Paging
	$s					= get_query_var('s');				// Search var
	
	?>
