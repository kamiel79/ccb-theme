<?php
/**
 * creative choice blog functions and definitions
 * @since 1.0
 * @package creative choice blog
 * 
 * Contents
 * ccb_rewrite_endpoint()
 * ccb_mobile_class
 * ccb_page_size
 * ccb_pagefile_filter
 * ccb_script
 * ccb_setup
 * ccb_highlighter
 * ccb_widgets_init
 * ccb_custom_excerpt_length
 * ccb_get_permalink
 * ccb_thumbnail
 * ccb_smart_search
 * ccb_trim_excerpt
 * ccb_trim_words
 * ccb_featured_image_classes
 * ccb_add_sri
 * final_words
 */

/* Helper functions
 * Returns an array of the constants, that can be passed to Javascript
 */
	function returnConstants ($prefix) {
	    foreach (get_defined_constants() as $key=>$value)
	        if (substr($key,0,strlen($prefix))==$prefix)  $dump[$key] = $value;
	    if(empty($dump)) { return; }
	    else { return $dump; }
	}

/* Include Multisite class for site aggregation */
if (is_multisite()) {
	require_once get_template_directory() . '/inc/multisite.php';

	$blogs = get_theme_mod ('ccb_multisite_blogs');

	$multi = new multisite($blogs);
	
	/* use /mp/999/ for pagination */
	function ccb_rewrite_endpoint() {
		add_rewrite_endpoint( 'mp', EP_PAGES );
	}
	add_action('init', 'ccb_rewrite_endpoint');

} 

/* include main settings file; in case of child theme, first include parent theme settings files */
/* When we are using a child theme, load its definitions first */
if (is_child_theme() and file_exists (get_stylesheet_directory() . "/ccb-settings.php")) {
	include get_stylesheet_directory() . "/ccb-settings.php";
}
/* Load the base theme's definitions (define is not overriden) */
include get_template_directory() . "/ccb-settings.php";


/** Don't serve minified files **/
	$min = "";

/* Include ccb Widgets */
	require_once get_template_directory() . '/inc/ccb_widgets.php';


/* Dates */
if (isset($_GET['from'])) $from	= $_GET['from']; else $from = "01-01-1970";	
if (isset($_GET['until'])) $until = $_GET['until']; else $until = date('d-m-Y');
//if ($until =="") $until= date('d-m-Y');

/* Add class for mobile 
if ( !function_exists( 'ccb_mobile_class' ) ) {
	function ccb_mobile_class() {
		if ( wp_is_mobile() ) echo "mobile"; else echo "nomobile";
	}
}*/


/* Function to register style sheets 
 * Supports WP_Less plugin
 */
if ( !function_exists( 'ccb_register_style' ) ) {
	function ccb_register_style($stylesheet, $deps='',$ver='1.0') {
		global $min;
		if (class_exists('WPLessPlugin'))		
			wp_register_style( $stylesheet, get_template_directory_uri()."/css/{$stylesheet}.less", $deps);
		else
			wp_register_style( $stylesheet, get_template_directory_uri()."/css/{$stylesheet}{$min}.css", $deps);	
	}
}
	
	
/* Include localize-js */
if ( !function_exists( 'localize-js' ) )
	require_once(ABSPATH . 'wp-admin/includes/file.php');			// load WP Filesystem functions
	require_once get_template_directory() . '/inc/localize-js.php';

/* Include breadcrumbs */
if ( !function_exists( 'breadcrumb_trail' ) )
	require_once( 'inc/breadcrumbs.php' );

	
if ( ! function_exists( 'ccb_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ccb_setup() {

	// Use load_theme_textdomain instead. This provides fallback translation for plugins too
	load_theme_textdomain( 'ccb', get_template_directory() . '/languages' );
	load_theme_textdomain( 'breadcrumb-trail', get_template_directory() . '/languages' );
	

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
		
	/*
	 * Add automatic menu items for tags and context set in options()
	 * May 2015. Added random tag to engage visitors
	 * April 2021. Moved option to Customizer. Removed wp_is_mobile()
	 */		
	function ccb_nav_menu_items($items, $args) {

		$link=""; $infocus="";$context="";$taglink="";
		if( $args->theme_location == 'primary' and CCB_MENU_TAGS ){
			$taglink = '<li id="menu-item-tags" class="toggletags1 menu-item"><a>' . __('Tags', 'ccb') . '</a></li>';
		}
		if( $args->theme_location == 'primary' and CCB_MENU_CONTEXT){
			$context_link_text = (CCB_SIDEBARINITIAL) ? __('No context','ccb') : __('Context','ccb');
	  		$context = '<li id="menu-item-context" class="menu-item show_sidebar refresh"><a>' . $context_link_text . '</a></li>';
		}
		if( $args->theme_location == 'primary' and CCB_MENU_INFOCUS) {
			/* If !infocus pick random tag with > CCB_MENU_INFOCUS_MIN posts */
			$the_tags = get_tags();
			$ft= array();
			foreach ($the_tags as $t) {
				if ($t->count > CCB_MENU_INFOCUS_MIN) $ft[] = $t;
			}
			/* get a fixed random number for this week from our random array */
			if (sizeof($ft)>1) {
				$tag = $ft[abs(CCB_LARGE_INTEGER * date("W") % sizeof($ft))];
				$link = get_tag_link($tag->term_id); 
				$infocus  = '<li id="menu-item-featured-tag" class="menu-item"><a href="'.$link.'">' . __('In focus: ', 'ccb')
				. $tag->name.'</a></li>';
			}
		}
		$items = $taglink . $items . $infocus . $context;
		return $items;
	}
	add_filter( 'wp_nav_menu_items', 'ccb_nav_menu_items', 10, 2 );

	/* 
	 * Register nav menus
	 */
	function ccb_nav_menu() {
		register_nav_menu( 'primary', __('Primary Menu', 'ccb') );
		register_nav_menu( 'top-menu', __('Top Menu', 'ccb') );
	}
	add_action("init", "ccb_nav_menu");

	
	/*
	 * Add theme support for custom background
	 *
	 */
	 $args = array(
	'default-color' => 'FFFFFF',
	'default-image' => get_template_directory_uri().'/img/background.png',
	);
	add_theme_support( 'custom-background', $args );

	/* Add theme support for title tag */
	add_theme_support( 'title-tag' );
	
	/*
	 * Switch default core markup for comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'image', 'quote', 'link', 'aside', 'gallery', 'status', 'video', 'audio'
	) );

	/* Add support for shortcodes in Widgets */
	add_filter('widget_text', 'do_shortcode');
	
	

	/* Add theme support for content_width (for scaling oEmbeds) */
	if ( ! isset( $content_width ) ) {
		$content_width = 600;
	}
	
	
 
	/**  Define where masonry is used:
	  *  Everywhere if AJAX search is on
	  *  Else except 
	  **
	function is_masonry() {
		if (CCB_MASONRY && (!is_single() && !is_404())
			|| CCB_AJAX_LOAD
			) 
			{ return TRUE; }
		else 
			{ return FALSE; }
	}
	*/
}
endif; // ccb_setup
add_action( 'after_setup_theme', 'ccb_setup' );

/**
 * Customizer CSS
 * @Since October 2022
 * See: https://www.cssigniter.com/how-to-create-a-custom-color-scheme-for-your-wordpress-theme-using-the-customizer/
 */
function ccb_get_customizer_css() {
    ob_start();

    $ccb_color1 = get_theme_mod( 'ccb_color1', '' );
    if ( ! empty( $ccb_color1) ) {
      ?>
      .site-branding {
			background-color:  <?php echo sanitize_hex_color($ccb_color1); ?>;
      }
      <?php
    }

    $css = ob_get_clean();
    return $css;
}

/**
 * Register widget areas.
 * ccb has a headbar for tag clouds etc, and 3 dynamic sidebars for the front page and custom templates
 * This can be easily extended :)
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function ccb_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'ccb' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Headbar', 'ccb' ),
		'id'            => 'headbar-1',
		'description'   => 'Bar in header for sliding out',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Entrymeta Widgets', 'ccb' ),
		'id'            => 'entrymetawidgets',
		'description'   => 'Widgets in meta info column of single post (eg sharing buttons)',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar(array(
		'name'=> 'Dynamic Widgetarea 1',
		'id' => 'dynamic1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Dynamic Widgetarea 2',
		'id' => 'dynamic2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Dynamic Widgetarea 3',
		'id' => 'dynamic3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
		register_sidebar(array(
		'name'=> 'Dynamic Widgetarea 4',
		'id' => 'dynamic4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Dynamic Widgetarea 5',
		'id' => 'dynamic5',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Footer Area 1',
		'id' => 'footer1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Footer Area 2',
		'id' => 'footer2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name'=> 'Footer Area 3',
		'id' => 'footer3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
}
add_action( 'widgets_init', 'ccb_widgets_init' );

/* Shortcode for dynamic widget areas */
add_shortcode('dynamic-sidebar', 'ccb_generate_widget_area');
if (! function_exists('ccb_generate_widget_area')) :

	function ccb_generate_widget_area($atts,$content=''){
        extract(shortcode_atts(array('id'=>'', 'before'=>'', 'after'=>''), $atts ) );
       if( is_active_sidebar( $id ) ){
           ob_start();   //start buffer      
           echo $before;
           dynamic_sidebar($id);
           echo $after;
           $content=ob_get_clean();//get it all and clean buffer
       }
       return $content;
    }
endif;

//apply_filters( 'widget_archives_args', array $args, array $instance );
apply_filters ( 'widget_archives_args', function( $title ) { return '<strong>' . $title . '</strong>'; } );

/** 
 *  allow html in category and taxonomy descriptions
 */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'pre_link_description', 'wp_filter_kses' );
remove_filter( 'pre_link_notes', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );
 

/** ccb_pagesize
 *  Returns the pagesize < posts_per_page filling up all the CCB_COLS columns
 *  @returns 
 */
if (! function_exists('ccb_pagesize')) :
	function ccb_pagesize() {
		// Use the greatest page size smaller than post_per_page
		$p = get_option('posts_per_page');
		$r = $p % CCB_COLS;
		if ($p < $r) 
			return $p;
		else 
			return ($p - $r);
	}
endif;

/** ccb_cols
 * Returns number of columns
 */
if (!function_exists('ccb_cols')) :
	function ccb_cols() {
		/* If fewer posts than CCB_GRID, make them larger to fill up the screen */
		//if ($GLOBALS['wp_query']->post_count < CCB_COLS)
			//$cols = $GLOBALS['wp_query']->post_count . " no-grid";
		$cols = CCB_COLS;
		return $cols;
}
endif;

/** ccb_pagesize_filter
 *  Filters current query with ccb_pagesize
 */

if (! function_exists('ccb_pagesize_filter')) :
	function ccb_pagesize_filter( $query ) {
		if ( is_admin() )
			return;
		if (ccb_pagesize())
			$query->set( 'posts_per_page', ccb_pagesize() );
	}
endif;
add_action( 'pre_get_posts', 'ccb_pagesize_filter', 1 );

/**
 * Enqueue styles. 
 */
function ccb_enqueue_styles() {
	/* Register Main custom AND mobile stylesheet, that loads all else:
	/* Main custom theme style */
	ccb_register_style("custom",'','1.1');
	ccb_register_style("mobile",'','1.1');
	/* Pass stylesheet directory on to the scripts */
	$stylesheet_uri = array( 'stylesheet_directory_uri' => get_stylesheet_directory_uri());

	/* Style for the used grid */
	ccb_register_style("grid-".CCB_GRID,'', '1.1');
	if (CCB_GRID != CCB_GRIDFRONT)
		ccb_register_style("grid-".CCB_GRIDFRONT,'', '1.1');	/* Style for the used alternative grid */
		
	/* enqueue dashicons if non admin */
	if (!is_admin()) wp_enqueue_style( 'dashicons' );
	
	wp_enqueue_style( 'custom',  get_stylesheet_uri() ); // This is where you enqueue your theme's main stylesheet
	/* Enqueue mobile stylesheet */
	wp_enqueue_style( 'mobile', "", array('custom'), false, "only screen and (max-width: 599px)");
	
	/* Enqueue grid stylesheets. */
	wp_enqueue_style( "grid-".CCB_GRID);
	if (CCB_GRID != CCB_GRIDFRONT)
		wp_enqueue_style( "grid-".CCB_GRIDFRONT);
		
	if (CCB_HAS_SIDEBAR) wp_enqueue_style( 'content-sidebar' );	/* not necessary */
	else wp_enqueue_style( 'no-sidebar' );
	wp_enqueue_script( 'ccb_navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery','ccb_custom'), '20120206', true );

	/* Load date picker for extended search only on search page */
	wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
	wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	/* Add CSS from customizer */
	$extra_css = ccb_get_customizer_css();
	wp_add_inline_style( 'custom', $extra_css );
}

add_action( 'wp_enqueue_scripts', 'ccb_enqueue_styles', 5 );

/**
 * Enqueue scripts and styles. Google Fonts, conditional styles for mobile
 */
function ccb_scripts() {
	global $min;
		
	/* Enqueue main ccb stylesheet  */
	//wp_enqueue_style( 'custom', array('jquery') );

		/*if ( is_masonry()) {
		wp_enqueue_script('masonry');
	}*/
	
		
	/* Load Scripts */
	wp_enqueue_script( 'ccb-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
		
	/* Load main custom js */
	wp_enqueue_script( 'ccb_custom', get_template_directory_uri() . "/js/custom{$min}.js", array('jquery'), '20140624', true );

	/* Load AJAX search script and history.js only if AJAX SEARCH enabled */
	if (CCB_AJAX_LOAD) {
		wp_enqueue_script( 'searchajax', get_template_directory_uri() . "/js/searchajax{$min}.js", array('jquery'),'20170101', true );
		wp_enqueue_script( 'history', get_template_directory_uri() . "/js/jquery.history{$min}.js", array('jquery'), '20170101', true);
		wp_localize_script( 'searchajax', 'ccb_options', array(
			'ccb_uri' => get_template_directory_uri(),
			'search_uri' => home_url(),
			'throbber' => CCB_THROBBER_URI)
		);
	}

	
	/* Load jquery form validation script only on single page open for comments */
	if (is_singular() && comments_open()) {
		wp_enqueue_script( 'form-validate','https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js', array('jquery'),'20170101',true);
		//wp_enqueue_script( 'formvalidation', get_template_directory_uri() . "/js/formvalidation{$min}.js", array('jquery', 'form-validate'),'20210523',true);
		wp_enqueue_script( 'comment-reply' );
	}
	
	/* Make the stylesheet directory available in JS */
	$uri = array( 'stylesheet_directory_uri' => get_template_directory_uri());
	


	/* Make options available in javascript */
	$the_constants = returnConstants('CCB_');
	$the_constants['ccb_uri'] = get_template_directory_uri();
	$the_constants['ccb_postperpage'] = get_option('posts_per_page');
	$the_constants['home_url'] = home_url();
	$the_constants['pagesize'] = ccb_pagesize();
	$the_constants['LARGESCREENWIDTH'] = 1024;
	$the_constants['DEVELOPMENT'] = DEVELOPMENT;
	$the_constants['ADMINBARHEIGHT'] = (is_user_logged_in()?32:0);

	wp_localize_script( 'ccb_custom', 'ccb_custom_options', $the_constants);
	wp_localize_script( 'ccb_navigation', 'ccb_custom_options', $the_constants);
}
add_action( 'wp_enqueue_scripts', 'ccb_scripts', 10 );


/**************************************
 * Custom template tags for this theme.
 **************************************/
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load additional functions.
 */
require get_template_directory() . '/custom.php';

/* Sanitize content */
function ccb_sanitize ($content) {
  return balanceTags($content, true);
}
add_filter( 'the_content', 'ccb_sanitize' );


/***************************************
 *	Replaces the excerpt "more" text by a link
 *
 *	http://codex.wordpress.org/Customizing_the_Read_More
 */
if (! function_exists('ccb_excerpt_more')) :
	function ccb_excerpt_more($more) {
		global $post;
		$blog_id = get_current_blog_id();
		return '<a class="moretag" href="'. ccb_get_permalink($blog_id, $post->ID) . '"> ...</a>';
	}
	add_filter('excerpt_more', 'ccb_excerpt_more');
endif;


if (! function_exists('ccb_highlighter')) :
 /*
  * Highlight searched words in results
  * We include this optionally, so we wrap the filter in another function, that we add to wp_enqueue_scripts
  * Search highlighting is done with a simple preg_replace
  */
function ccb_highlighter (){
 if (SEARCH_HIGHLIGHTING==1 && is_search()==1) : 
	function ccb_highlight_filter($excerpt) {
		global $s;
		$keys 	= explode (" ", $s);
		$excerpt = preg_replace ('/('.implode('|',$keys).')/iu',
				'<span class="highlighted">\0 </span>',$excerpt);			
		return $excerpt;
	}
	add_filter('get_the_excerpt','ccb_highlight_filter', 999);
 endif;
}
endif;
add_action( 'wp_enqueue_scripts', 'ccb_highlighter' );
 
/***************************************
 * Obsolete: Short excerpt for mobile devices
 */
if (! function_exists('ccb_custom_excerpt_length') ) :
function ccb_custom_excerpt_length( ) {
    //set the shorter length once
    $short = CCB_SHORT_EXCERPT;
    //set long length once
    $long = CCB_LONG_EXCERPT;
    //if we can only set short excerpt for phones, else short for all mobile devices
    if (function_exists( 'is_phone')) {
        if ( is_phone() ) {
            return $short;
        }
        else {
            return $long;
        }        
    }
    else {
        if ( wp_is_mobile() ) {
            return $short;
        }
        else {
            return $long;
        }
    }
}
add_filter( 'excerpt_length', 'ccb_custom_excerpt_length', 999 );
endif; // ! ccb_custom_excerpt_length exists 


/***************************************
 *   Custom styling for mobile devices
 */
if ( ! function_exists ( 'ccb_mobile_styles' ) ) :
function ccb_mobile_styles() {
    //set the wide width
    $wide = '25%';
    //set narrow width
    $narrow = '50%';
    /**Determine value for $width**/
    //if we can only set narrow for phones, else narrow for all mobile devices
    if (function_exists( 'is_phone')) {
        if ( is_phone() ) {
            $width = $narrow;
        }
        else {
            $width = $wide;
        }        
    }
    else {
        if ( wp_is_mobile() ) {
            $width = $narrow;
        }
        else {
            $width = $wide;
        }
    }
    /**Output CSS for .masonry-entry with proper width**/
    $custom_css = ".entry {width: {$width};}";
    wp_add_inline_style( 'custom', $custom_css );
}
//add_action( 'wp_enqueue_scripts', 'ccb_mobile_styles' );
endif; // ! ccb_mobile_styles exists



/* Set post classes: filter for post_class
	 * - Set a class for the priority metavalue of a post
	 * - Set a class for effects: # Adjust font size
	 * - Highlight posts with more than average comments
	 * - Highlight sticky posts, or posts with special priority
	 * - Highlight most viewed posts
*/
if ( ! function_exists ( 'ccb_post_class' ) ) :
	function ccb_post_class ($classes) {
		global $post;
		if (is_single()) $classes[] = "single";
		if (get_post_meta($post->ID, "ccb_priority", true))
			$classes[] = "priority-".get_post_meta($post->ID, "ccb_priority", true);
		$count_posts = wp_count_posts();
		$posts = $count_posts->publish;				//all published posts
		$count_comments = get_comment_count();		//total number of comments
		$comments  = $count_comments['approved'];	//all approved comments
		$pop = round($comments/$posts);				//average # of comments
		return $classes;
	}
	add_filter( 'post_class', 'ccb_post_class');
endif;


function ccb_classes($cls) {
	switch ($cls) {
		/* add class for sidebar */
		case 'showsidebar':
			//echo "movingparts";
			if ((CCB_SIDEBARINITIAL || is_archive() || (is_page() && get_post_meta(get_the_ID(), 'ccb_sidebar') !=''))) echo " showsidebar";
			break;	
	}
}
	
/* Logo; fallback is blog title */
function ccb_logo () {
	the_custom_logo();
}
/* Custom Logo */

function config_custom_logo() {
 	 $defaults = array(
        'height'               => 96,
        'width'                => 96,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array( 'site-title', 'site-description' ),
        'unlink-homepage-logo' => true, 
    );
    add_theme_support( 'custom-logo', $defaults );
 }
 add_action( 'after_setup_theme' , 'config_custom_logo' );


/***************************************
 *  Function Extract images
 *  @since 1.0
 *  @param $post, $num
 *  @return array of image urls in current $post larger than 150x75px
 */
	function extract_images($post, $num) {
		$src_pattern = "/<img[^>]+src=(['\"])(.+?)\\1/";
		$src = array();
		$matches = array();
		if(preg_match_all($src_pattern, $post->post_content, $matches)) {
			$i=1;
			while ($i <= count($matches) && $i <= $num) {
				list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($matches[2][$i]);
				if ($img_width > CCB_THUMB_MIN_WIDTH && 
					$img_height > CCB_THUMB_MIN_HEIGHT) {
						$src[] = trim($matches[2][$i]);
				}
				$i++;
			}
		}
	return $src;
	}
	


/***************************************
 *	Function ccb_random_tag
 *
 *	Returns random tag name belonging to $postid
 *
 * @since 1.0
 * @param $post->ID
 * @return tagname if a tag exists, else false
 */
function ccb_random_tag ($postid) {
	$tags = wp_get_post_tags( $postid, array( 'fields' => 'names' ) );
	if (count($tags)==0) return false;
	$r = rand(0, count($tags)-1);
	return $tags[$r];
}

/***************************************
 *  Function ccb_permalink
 *
 * 	wraps the_permalink for multisite
 */
function ccb_permalink($blog_id=null, $post_id=null) {
	if ( is_multisite() ) echo get_blog_permalink($blog_id, $post_id);
	else echo the_permalink();
}

function ccb_get_permalink($blog_id=null, $post_id=null) {
	if ( is_multisite() ) return get_blog_permalink($blog_id, $post_id);
	else return get_permalink();
}

function ccb_has_post_thumbnail() {
	return CCB_PERSISTENT_THUMBS || has_post_thumbnail();
}

/***************************************
 *  Function ccb_thumburl
 *
 *  Determines if session is set for thumbnail and returns that url
 *  @since 1.0
 *  @returns url of thumbnail
 */
function ccb_thumburl ($blog_id, $post_id, $size='thumbnail', $persistent=true) {
	//Check if class exists and option for persistent thumbnails set
	$persistent &=class_exists('WP_Session') && CCB_PERSISTENT_THUMBS;	
	/* return session value if any in case we use persistent thumbnails */
	if ($persistent) {
		/* Save thumburl for next use */
		global $wp_session;
		$wp_session = WP_Session::get_instance();
		if ($wp_session['thumb'.$blog_id."-".$post_id]) 
			return $wp_session['thumb'.$blog_id."-".$post_id];
	}

	/* return normal or multisite thumbnail */
	if (is_multisite()) 
		$thumburl = multisite::mu_thumbnail($post_id, $blog_id, $size, 'url');
	else 
		$thumburl = ccb_thumbnail(get_the_ID(), $size, 'url');


	/* register persistent thumbnail if thumbnail from foreign domain*/
	if ($persistent) {
		if (false === strpos($thumburl, get_site_url()))
			$wp_session['thumb'.$blog_id."-".$post_id] = $thumburl;
	}
	return $thumburl;
}

/* Load Flickr support if needed */
if (CCB_FLICKR_IMG)
	require_once get_template_directory() . '/inc/flickr.php';


/***************************************
 *  Function ccb_thumbnail
 *
 *  Returns thumbnail of $postid, or else the first occurring image
 *  If there is no image in the post, it returns ccb_fallback_thumbnail
 *  For post-formats ["quote",...] it returns "" (false)
 *  @since 1.0
 *  @param $postid, $size, $output
 */
function ccb_thumbnail ($postid, $size='thumbnail', $output='html', $classes='wp-post-image') {
	if (in_array(get_post_format($postid), explode(",", CCB_NOIMG_FORMATS))) return;
	global $post;
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($postid), $size);
	if ($thumb) $src = $thumb[0];
	else $src = "";

	if ($src=="") {
		if (IN_POST_THUMBNAILS) {
			/* get first usable image in post using PHP7 compatible preg_match 
			 * authors can skip images by using data-ccbskipimg
			 */
			$src_pattern = "/<img[^>]+src=(['\"])(.+?)\\1[^>]+>/";
			$matches = array();
			if(preg_match_all($src_pattern, $post->post_content, $matches, PREG_SET_ORDER)) {
				$i = 0;
				/* use first image by default */
				$src = $matches[$i][2];		//url is 2nd match in pattern
				$found = false;
				while ($i<count($matches)&& !$found) {
					if (false==strpos($matches[$i][0],'data-ccbskipimg=')) {
						$found=true; $src = $matches[$i][2];
					}
					$i++;
				}
			}
		}
		$media = get_post_meta($post->ID, 'ccb_headmedia', true);
		if ($src=="" && ''!=$media) return $media;
		if ($src=="" && CCB_FLICKR_IMG) {
			//get flickr file if configured
			//load flickr image asynchronously to make server response faster
			//return the keyword that will display nicely if JS unavailable
			$tag = ccb_random_tag($post->ID);	//get a random tag from the tags
			//return Flickr image
			if ($tag)
				$src = get_flickr_img($tag);
		}
	}
	if ($src == "") return CCB_FALLBACK_THUMBNAIL;
	if ($src!="" && $output=='url') {
		return $src;
	}
	else {
		$arr = @getimagesize($src);		
		if (is_array($arr)) {
			/* Check if portrait of landscape */
			//list($img_width, $img_height, $img_type, $img_attr) = getimagesize($src);
			$classes.=($arr[0] < $arr[1])?" tall":" wide";
			if ($arr[0]>=CCB_THUMB_MIN_HEIGHT &&
						$arr[1]>=CCB_THUMB_MIN_WIDTH) {	
				$img="<img src='$src'";
				if ($arr[0] < $arr[1]) {	//tall
					$s2 = (int)(($arr[1]*100)/$arr[0]);
					$img.=" width='$s' style='height:{$s2}%'";
				}
				else {	//wide
					$s2 = (int)(($arr[0]*100)/$arr[1]);
					$mar = (int)(($s2-100)/2);	//margin to center image
					$img.=" height='$s' style='width:{$s2}%; margin-left:-{$mar}%'";
				}
				$img.=" class='$classes' />";
				return $img;
			}
		}
	}
	return CCB_FALLBACK_THUMBNAIL;
}


/***************************************
 * ccb_print_thumb
 * 
 * Prints the thumbnail
 * 
 */
function ccb_print_thumb($id) {
	global $post;
	$thumburl = ccb_thumbnail($post->ID, "large", "url");

	if ("" != $thumburl) :
			list($image_w, $image_h) = @getimagesize($thumburl);
			$imgstyle = "";	//;style='background-image:url(" . $thumburl . ");background-size:" . 
						//0.85 * min($image_w, $image_h) . "px'";
	
			$img_classes = get_post_meta($id, 'ccb_featured_image_classes', $single = true);
				
			if (false !== strpos($img_classes, "pngframe")) {

				$cla = CCB_PNGFRAMES[substr($img_classes, 9)];
				
				/* To do: make it work for all sizes, using the right technique. */
				
				$thumburl = "src='" . get_template_directory_uri() . "/img/" . $cla . "' style='height:	" . $image_h . "px; width:" . $image_w . "px'"; 
			} 
			else {
				$thumburl = "src='". $thumburl . "'";
			} ?>
				<div class='thumbcrop <?php echo $img_classes; ?>'
					<?php echo $imgstyle; ?>>
					<IMG <?php echo $thumburl; ?> onerror="this.style.display='none'"></IMG>
				</div><!--/.thumbcrop-->
					<?php endif;
}

/***************************************
 *  Search Functionality
 * 
 *  Display Search box in menu bar
 *
 *  @since 1.0
 */
add_filter('wp_nav_menu_items','add_search_box_to_menu', 10, 2);
function add_search_box_to_menu( $items, $args ) {
    if( !($args->theme_location == 'primary'))
		return $items;
	return $items . '<li class="toggletags2 ccb_navsearch"></li>'; //. get_search_form(false) . 
}


/* Search filter for searching by date if no multisite
 * @since 1.1 */
/*
 function ccb_search_filter($where='') {
  global $wp_query;

  global $from;
  global $until;

  if (!is_admin() && $wp_query->is_main_query() ) {
	  
    if ($wp_query->is_search) {

	$f = date("Y-m-d", strtotime($from)); 
	$u = date("Y-m-d", strtotime($until)); 

//AND (post_date >= STR_TO_DATE('$from','%d-%m-%Y') AND 
		$where = " AND post_date >= '{$f}' AND post_date <= '{$u}'";	
		//remove_all_actions ( '__after_loop');
		 }
  }
 return $where;  
}
add_action('posts_where','ccb_search_filter');
*/
 
/* Smart Search
 * Searches also in tags and comments if options are set
 * Borrowed from http://wordpress.stackexchange.com/questions/62806/search-also-in-taxonomy-tags-and-custom-fields
 * @since 1.0
 */
 function ccb_smart_search( $search, &$wp_query ) {

    global $wpdb;
	global $from;
	global $until;
	if ($from!='undefined' or $until!='undefined') {
		$f = date("Y-m-d", strtotime($from)); 
		$u = date("Y-m-d", strtotime($until));
		$daterange = " AND post_date >= '{$f}' AND post_date <= '{$u}'";
	}
 
    if ( empty( $search ))
        return $search;
 
    $terms = $wp_query->query_vars[ 's' ];
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 )
        $exploded = array( 0 => $terms );
         
    $search = '';
	$wp = $wpdb->prefix;
    foreach( $exploded as $tag ) {
        $search .= " AND (
            ({$wp}posts.post_title LIKE '%$tag%')
            OR ({$wp}posts.post_content LIKE '%$tag%')
            OR EXISTS
            (
                SELECT * FROM {$wp}comments
                WHERE comment_post_ID = {$wp}posts.ID
                    AND comment_content LIKE '%$tag%'
            )
            OR EXISTS
            (
                SELECT * FROM {$wp}terms
                INNER JOIN {$wp}term_taxonomy
                    ON {$wp}term_taxonomy.term_id = {$wp}terms.term_id
                INNER JOIN {$wp}term_relationships
                    ON {$wp}term_relationships.term_taxonomy_id = {$wp}term_taxonomy.term_taxonomy_id
                WHERE taxonomy = 'post_tag'
                    AND object_id = {$wp}posts.ID
                    AND {$wp}terms.name LIKE '%$tag%'
            )
        )" . $daterange;
    }
    return $search;
}
//add_filter( 'posts_search', 'ccb_smart_search', 500, 2 );

/** Search redirect for multisite
 *  Forces search.php when on multisite (otherwise WP counts search results and gives out a 404)
 */
add_filter( 'template_include', 'multisite_search', 99 );

function multisite_search( $template ) {
	if (isset($_GET['s'])) {
		$new_template = locate_template( array( 'search.php' ) );
		if ( '' != $new_template ) return $new_template ;
	}
	return $template;
} 

if (is_multisite()) add_filter('template_redirect', 'ccb_404_override' );
function ccb_404_override() {
    global $wp_query;
    if (isset($_GET['s'])) {
        status_header( 200 );
        $wp_query->is_404=false;
    }
}

/***************************************
 *  Function ccb_trim_words
 *  Trim to max number of words and max number of characters
 *	@sincs 1.0
 *  @return chopped $words
 */
function ccb_trim_words($words, $wordlim=-1, $charlim=140, $append="...") {
       $words = explode(' ', $words, $wordlim);
	   $i=0; $s="";
	   while (strlen($s) < $charlim-strlen($append)&$i<$charlim) {
		   $s .= $words[$i]." ";
		   $i++;
	   }
	   $s = substr($s, -(strlen($words[$i])));	//cut off last word	   
       return $s;
}


/***************************************
 *  Function ccb_trim_excpertCustom
 *  Custom excerpt
 *
 * 	- Add newlines to excerpts of poetry
 *  - Post Format dependent excerpt length
 */
function ccb_trim_excerpt($text = '') {
		if ( '' == $text ) {
                $text = get_the_content('');
               
				if (in_category(CCB_POETRY_CAT)) { 
					//$text = preg_replace ('~((.*?\x0A){4}).*~s', '\\1', $text); 
					$text = str_replace(array("\r\n", "\r", "\n"), " <br> ", $text);
				}
				/* Remove shortcodes from excerpts */
				$text = strip_shortcodes( $text );
				
				/* Remove urls (oembeds) from excerpts */
				$text = preg_replace( "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/", '', $text ); // removes URLs
				
                //$text = apply_filters( 'the_content', $text );
				/* Allow certain tags in excerpts */
				$allowed_tags = CCB_EXCERPT_TAGS;
				$text = strip_tags($text, $allowed_tags);
				$pf = get_post_format();
				if ($pf == "aside")	$excerpt_max = CCB_ASIDE_EXCERPT;
				else $excerpt_max = CCB_LONG_EXCERPT;
				$excerpt_length = strlen($text);
				$excerpt_more = apply_filters( 'excerpt_more', ' ' . '...' );
				/* This version of wp_trim_words preserves the new lines */

				$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length, PREG_SPLIT_NO_EMPTY);
				if ( count($words) > $excerpt_max ) {
					$words = array_slice($words, 0, $excerpt_max);	//get words
					$text = implode(' ', $words);					//put together
					$text = $text . $excerpt_more;					//add "..."
				}
				$text = balanceTags( $text, true );		/* Force closing of tags */
		}
		else {
			//$text = apply_filters( 'wp_trim_excerpt', $text, $text );
		}	 
		return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'ccb_trim_excerpt', 1);

/* Filter to show oEmbeds in excerpt. Notice that masonry etc might have to wait until this is loaded */
global $wp_embed;
//add_filter( 'get_the_excerpt', array( $wp_embed, 'autoembed' ), 999 );


/***************************************
 *  Function ccb_headbar
 *  Headbar for widgets that can be toggled 
 *  April 2021. We are customizing the search widget placeholder with a filter
 * @since 1.0
 */
/* Change Search Widget placeholder text */
function ccb_search_form( $html ) {
        $html = str_replace( 'placeholder=Search', 'placeholder="'. __("What are you looking for?", "ccb").'"', $html );
        return $html;
}
function ccb_headbar() {
	echo "<div class='ccb_headbar'>";
	add_filter( 'get_search_form', 'ccb_search_form' );
	dynamic_sidebar('headbar-1');
	remove_filter( 'get_search_form', 'ccb_search_form' );
	echo "</div>";
}

/****************************************
 * Function ccb_featured_image_classes
 * Returns the classes in the post_meta to be used for styling the featured image
 */
function ccb_featured_image_classes($id) {
	$img_classes = get_post_meta($id, 'ccb_featured_image_classes', $single = true);
	//if (str_contains($img_classes, "pngframe")) {
	//	$imgstyle = "<style background-image:url('" +  />'
	//	return "classes='" + $img_classes + "'" + $imgstyle; 
	//}
	return "class='" . $img_classes . "'";
}

//get_template_directory_uri + "/img/" + substr($img_classes,7);


/******************
 *  Template override
 */
function ccb_template_override($template)
{
	/* Force search template for search-URis (including ?)
  if(  strpos($_SERVER['REQUEST_URI'],"?")!==false )
  {
   return locate_template('search.php');
  }*/
  return $template;
}
add_filter('template_include', 'ccb_template_override');


/******************
 *  Shortcodes.
 *  Used for standard layout elements
 */

/* Universal shortcode for passing settings through pages
 * @since 1.4
 * Provides a simple and very naughty (but safe) way to change settings for templates
 */
function ccb_pagesettings( $atts ) {
    global $ccb_pagesettings;
	$ccb_pagesettings = $atts;
}
add_shortcode( 'ccb-page', 'ccb_pagesettings' );


/* Shortcode for highlighted blockquote
 * @Since Jan 2017
 */
function ccb_highlight( $atts, $content="") {
	return "<BLOCKQUOTE class='highlight'>".$content."</BLOCKQUOTE>";
}
add_shortcode ('ccb_hl', 'ccb_highlight');

function ccb_between_link ($atts, $content="") {
	return "<SPAN class='betweenlink'>" .$content."</BLOCKQUOTE>";
}
add_shortcode ('ccb_bli', 'ccb_between_link');


/* SVG Support */
function add_file_types_to_uploads($file_types){
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

/**
* add SRI attributes to registered scripts

* @param string $html
* @param string $handle
* @return string
*/
add_filter( 'style_loader_tag', __NAMESPACE__ . '\add_ccb_sri', 10, 2 );
add_filter( 'script_loader_tag', __NAMESPACE__ . '\add_ccb_sri', 10, 2 );

function add_ccb_sri($html, $handle) : string {
	switch ($handle) {
		case 'leaflet-js':    
        	$html = str_replace('></script>', ' integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>', $html);
        	break;
        case 'leaflet-css':
        	$html = str_replace('></script>', '
        		integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  				  crossorigin=""/>', $html);
        	break;
    }
 
    return $html;
}


/**
 * Register a rewrite endpoint for the API for Multisite Tags.
*/
function prefix__init() {
					 // '([\-\w+]*)/animal/([\w+]*)/?$', 
        flush_rewrite_rules( true );

	add_rewrite_tag( '%keyword%', '([\w+]*)/?$', 'keyword='	);//

	add_rewrite_rule( 'keyword/([\sA-Za-zÄÖÜÀÁÒÓÈÉÙÚäàáéèóòùúçñëöüß]+)[/]?$', 'index.php?keyword=$matches[1]', 'top' );

    // create URL rewrite
    add_rewrite_rule( 'keyword/([\w+]*)/?$', 'http://blog.test/multisite-tags?keyword=$1', 'top' );

    // required once after rules added/changed
 }

add_action( 'init', 'prefix__init');

add_filter('query_vars', function($vars){
   $vars[] = 'keyword';
    return $vars;
});

add_action( 'template_include', function( $template ) {
    if ( get_query_var( 'keyword' ) == false || get_query_var( 'keyword' ) == '' ) {
        return $template;
    }

    return get_template_directory() . '/mu-tags.php';
} );

	
/**************
	Final Words: 
	Write SVG if needed - issues with external SVG-file referencing because ObjectBoundingBox must be specified. Spielerei. April 2021
	Add inline javascript after WP enqueued scripts, eg for leaflet
*/
function ccb_final_words() {
	
	 global $wp_query;
	
	/* Calls all functions defined in the settings */
	foreach (CCB_ADDED_JS as $f) {
		if (function_exists($f))
			$f();
	}
	
	/* Include clippaths */
	include (get_template_directory() . "/img/clippaths.svg");
    
}

add_action('final_words', 'ccb_final_words');



?>