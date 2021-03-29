<?php
/**
 * Creative Choice Blog PHP settings
 * Includes all hard coded settings plus settings from the option framework
 * @since 1.0 - 22 Dec 2015
 * @updated 14 Jan 2017
 * @package Creative Choice Blog
 */

define ( 'DEVELOPMENT',false);

define ( 'IN_POST_THUMBNAILS', of_get_option('inpostthumbnails') );
define ( 'FLICKR_IMG', of_get_option('flickr'));
define ( 'FLICKR_API_KEY', of_get_option('flickr_api_key'));
define ( 'CCB_PERSISTENT_THUMBS', of_get_option ('ccb_persistent_thumbs'));		// thumbnail urls saved in cookies
define ( 'CCB_COOKIE_EXPIRE', 1800); 											// half an hour
define ( 'CCB_COLS', max(1,of_get_option('cols')));
define ( 'CCB_SQUARES', of_get_option('ccb_squares'));
define ( 'CCB_LONG_EXCERPT', 55);  				  //35 + 6*( 2-CCB_COLS)); excerpt adapted to # of cols
define ( 'CCB_SHORT_EXCERPT', of_get_option('short_excerpt'));
define ( 'CCB_ASIDE_EXCERPT', 125);
define ( 'CCB_EXCERPT_TAGS',"<i><b><br><br/><strong><code>");					// tags allowed in excerpt
define ( 'SEARCH_HIGHLIGHTING', of_get_option('search_highlighting'));
define ( 'CCB_FIX_NAV', of_get_option('ccb_fix_nav'));
define ( 'CCB_MENU_CONTEXT', of_get_option('ccb_menu_context'));
define ( 'CCB_MENU_TAGS', of_get_option('ccb_menu_tags'));
define ( 'CCB_MENU_INFOCUS', of_get_option('ccb_menu_infocus'));
define ( 'CCB_INFOCUS_MIN', of_get_option('ccb_menu_infocusnumber'));			// # of posts to count for infocus
define ( 'CCB_AJAX_SEARCH', of_get_option('ccb_ajax_search'));
define ( 'CCB_NUM_SLIDES', of_get_option('ccb_num_slides'));
define ( 'CCB_SLIDER_HEIGHT', of_get_option('ccb_slider_height'));
define ( 'CCB_AJAX_LOAD', of_get_option('ccb_ajax_load'));
define ( 'CCB_THROBBER_URI', get_template_directory_uri() . "/img/load.gif");
define ( 'CCB_MULTISITE', of_get_option('multisite_on'));
define ( 'CCB_MULTIPAGE', '');
define ( 'CCB_GOOGLEFONTS', of_get_option('ccb_googlefonts'));					// googlefont string
define ( 'CCB_GRID', of_get_option('ccb_grid'));
define ( 'CCB_GRIDFRONT', of_get_option('ccb_gridfront'));
define ( 'CCB_POETRY_CAT', "gedichten, poetry, Gedichte");
define ( 'CCB_SHARER_FORMATS', "quote, aside, audio, video, status");			// shared straight from the grid
define ( 'CCB_TEMPLATE_ISOTOPE', "ccb-portfolio.php");
define ( 'CCB_THUMB_MIN_HEIGHT',100);
define ( 'CCB_THUMB_MIN_WIDTH',100);
define ( 'has_sidebar', true);
define ( 'CCB_NOIMG_FORMATS', "quote, link, aside, audio, video");				// PHP 5.6+ const array
define ( 'CCB_NOFOOTER_FORMATS', "image, aside, quote");
define ( 'CCB_NOTITLE_FORMATS', "quote");
define ( 'CCB_NOEXCERPT_FORMATS', "image");
define ( 'CCB_SIDEBARINITIAL',true);
define ( 'CCB_LARGE_INTEGER', 986235083472938503325);
define ( 'CCB_FULLWIDTH', 1200);
define ( 'CCB_PORTFOLIOCATEGORY', 'category');									//the category used for portfolio
define ( 'CCB_BREADCRUMB', of_get_option('ccb_breadcrumb'));
define ( 'CCB_MASONRY', of_get_option('ccb_masonry'));

/* Get the names of Blogs in a Multisite */
if (is_multisite()) {
	$mu_blogs = of_get_option('mu_blogs');
	multisite::mu_setblogs(of_get_option("mu_blogs"));
}
/***************
 *   Plugins:
 
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