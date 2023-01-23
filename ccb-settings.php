<?php
/**
* Creative Choice Blog PHP settings
* Includes all hard coded settings plus settings from the option framework
* @since 1.0 - 22 Dec 2015
* @updated 14 Jan 2017
* @updated 1 April 2021 Dumber than ever but alive; same thing in October 2022!
* @package Creative Choice Blog
*/

define ( 'DEVELOPMENT', false);
define ( 'CCB_LAYOUT_STYLES', array('listing'=>'Listing', 'squares'=>'Squares', 'grid2'=>'Grid 2', 'shapes'=>'Shapes'));
define ( 'CCB_CLIP_SHAPES', array(''=>'None', 'heart'=>'Heart', 'star'=>'Star'));

define ( 'CCB_FALLBACK_THUMBNAIL', get_theme_mod('ccb_fallback_thumbnail') );
define ( 'IN_POST_THUMBNAILS', get_theme_mod('ccb_inpostthumbnails') );
define ( 'CCB_FLICKR_IMG', get_theme_mod('ccb_flickr'));
define ( 'CCB_FLICKR_API_KEY', get_theme_mod('ccb_flickr_api_key'));
define ( 'CCB_PERSISTENT_THUMBS', get_theme_mod ('ccb_persistent_thumbs'));		// thumbnail urls saved in cookies
define ( 'CCB_COOKIE_EXPIRE', 1800); 											// half an hour

define ( 'CCB_ADDED_JS', array("ccb_map_script"));
define ( 'CCB_LONG_EXCERPT', 55);  				  //35 + 6*( 2-CCB_COLS)); excerpt adapted to # of cols
define ( 'CCB_SHORT_EXCERPT', 25);
define ( 'CCB_ASIDE_EXCERPT', 125);
define ( 'CCB_EXCERPT_TAGS',"<i><b><br><br/><strong><code>");					// tags allowed in excerpt
define ( 'SEARCH_HIGHLIGHTING', get_theme_mod('search_highlighting'));
define ( 'CCB_FIX_NAV', get_theme_mod ('ccb_fix_nav'));
define ( 'CCB_MENU_CONTEXT', get_theme_mod('ccb_menu_context'));
define ( 'CCB_MENU_TAGS', get_theme_mod('ccb_menu_tags'));
define ( 'CCB_MENU_INFOCUS', get_theme_mod('ccb_menu_infocus'));
define ( 'CCB_MENU_INFOCUS_MIN', get_theme_mod('ccb_menu_infocus_min'));		// # of posts to count for infocus

define ( 'CCB_AJAX_LOAD', get_theme_mod('ccb_ajax_load'));
define ( 'CCB_THROBBER_URI', get_template_directory_uri() . "/img/load.gif");

/* Multisite */
define ( 'CCB_MULTISITE', get_theme_mod('ccb_multisite_on'));
define ( 'CCB_MULTISITEBLOGS', get_theme_mod('ccb_multisite_blogs'));
define ( 'CCB_MULTITAG_PAGE', get_permalink(get_page_by_path('multisite-tags'))); //get_theme_mod('ccb_multitag_page'));

define ( 'CCB_HEADER_IMAGE', get_theme_mod('ccb_header_image'));

/* Layout grid */
define ( 'CCB_GRID', get_theme_mod('ccb_grid', 'grid2'));
define ( 'CCB_GRIDFRONT', get_theme_mod('ccb_gridfront', 'list'));
define ( 'CCB_COLS', max(1,(int)get_theme_mod('ccb_cols')));			
define ( 'CCB_CLIP_SHAPE', get_theme_mod('ccb_clip_shape'));
define ( 'CCB_POETRY_CAT', "gedichten, poetry, Gedichte");
define ( 'CCB_SHARER_FORMATS', "quote, aside, audio, video, status");			// shared straight from the grid
define ( 'CCB_THUMB_MIN_HEIGHT',100);
define ( 'CCB_THUMB_MIN_WIDTH',100);
define ( 'CCB_HAS_SIDEBAR', true);
define ( 'CCB_SIDEBARINITIAL', get_theme_mod('ccb_sidebarinitial'));
define ( 'CCB_SIDEBARDELAY', get_theme_mod('ccb_sidebardelay',500));
define ( 'CCB_NOIMG_FORMATS', "quote, link, aside");				// PHP 5.6+ const array
define ( 'CCB_METACOLUMNFLOAT', get_theme_mod('ccb_metacolumnfloat'));
define ( 'CCB_NOFOOTER_FORMATS', "image, aside, quote");
define ( 'CCB_NOTITLE_FORMATS', "quote");
define ( 'CCB_NOEXCERPT_FORMATS', "image");
define ( 'CCB_LARGE_INTEGER', 412013);
define ( 'CCB_FULLWIDTH', 1200);
define ( 'CCB_BREADCRUMB', get_theme_mod('ccb_breadcrumb'));
define ( 'CCB_MASONRY', false);


/***************
The following custom fields are available for blog posts:
ccb_featured_image_classes
ccb_headerimage


/***************
*   Tested with these Plugins:

add_from_server
breadcrumb_trail(integrated)
disqus
Flickr auto thumbnails (integrated)
geo mashup
google xmp sitemaps
jetpack
localize_js (integrated)
markdown
menu items visibility control
redirection
shortcodes ultimate
tag with #
wordpress crosspost
wordpress pdf templates
wp_less
wp session manager
wp_super cache
*/
?>