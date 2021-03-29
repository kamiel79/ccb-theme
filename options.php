<?php
/**
 * Options for ccb theme
 * @since 1.0 - 12/22/2015
 *
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	return 'ccb';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {
	$options = array();

	$options[] = array(
		'name' => __('Basic Settings', 'ccb'),
		'type' => 'heading');
		
	/* Define Options */
	
	$options[] = array(
		'name' => __('Upload a logo', 'ccb'),
		'desc' => __('Upload a custom logo.', 'ccb'),
		'id' => 'ccb_logo',
		'type' => 'upload');
	$options[] = array(
		'name' => __('Upload a header image', 'ccb'),
		'desc' => __('Upload a custom <b>header</b>.', 'ccb'),
		'id' => 'ccb_header',
		'type' => 'upload');
	$options[] = array(
		'name' => __('Breadcrumb trail', 'ccb'),
		'desc' => __('Display a breadcrumb trail for your site', 'ccb'),
		'id' => 'ccb_breadcrumb',
		'type' => 'checkbox');
	$options[] = array(
		'name' => __('Fix nav on scroll', 'ccb'),
		'desc' => __('Fix the navigation bar when the user scrolls the page down.', 'ccb'),
		'id' => 'ccb_fix_nav',
		'type' => 'checkbox');
	$options[] = array(
		'name' => __('Float metacolumn (left sidebar) on scroll', 'ccb'),
		'desc' => __('Float the metacolumn when the user scrolls the page down.', 'ccb'),
		'id' => 'ccb_metacolumnfloat',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Add tags to menu', 'ccb'),
		'desc' => __('Add a menu item for showing tags.', 'ccb'),
		'id' => 'ccb_menu_tags',
		'type' => 'checkbox');
	$options[] = array(
		'name' => __('Add context to menu', 'ccb'),
		'desc' => __('Add a menu item for showing context (sidebar).', 'ccb'),
		'id' => 'ccb_menu_context',
		'type' => 'checkbox');
	$options[] = array(
		'name' => __('Add InFocus to menu', 'ccb'),
		'desc' => __('Add a menu item for a tag in focus.', 'ccb'),
		'id' => 'ccb_menu_infocus',
		'type' => 'checkbox');
	$options[] = array(
		'name' => __('Number of posts per tag', 'ccb'),
		'desc' => __('Minimal number of posts for a tag to be eligible for in-focus.', 'ccb'),
		'id' => 'ccb_menu_infocusnumber',
		'std' => '5',
		'class'=> 'tiny',
		'type' => 'text');		
	$options[] = array(
		'name' => __('Short excerpt length', 'ccb'),
		'desc' => __('In addition to the Wordpress excerpt length, ccb features a short excerpt for mobile devices and crowded grids.', 'ccb'),
		'id' => 'short_excerpt',
		'std' => '15',
		'class'=> 'tiny',
		'type' => 'text');
	$options[] = array(
		'name' => __('Highlight search terms', 'ccb'),
		'desc' => __('Highlight the search term on the search results page', 'ccb'),
		'id' => 'search_highlighting',
		'type' => 'checkbox',
		'class'=> 'floatleft col4');
	$options[] = array(
		'name' => __('AJAX search', 'ccb'),
		'desc' => __('Use AJAX for search', 'ccb'),
		'id' => 'ccb_ajax_search',
		'type' => 'checkbox',
		'class'=> 'floatleft col4');
	/* Grid settings */
	$options[] = array(
		'name' => __('Grid Settings', 'ccb'),
		'type' => 'heading');
	$cols = array(
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
		7 => 7,
		8 => 8,
		9 => 9,
		10 => 10		
	);
	$options[] = array( 'name' => __('Number of columns', 'ccb'),
		'desc' => __('Number of columns in the grid. You can change this on individual pages using the templates {mu-archive} and putting the shortcode [ccb-page cols=XXX] in the content', 'ccb'),
		'id' => 'cols',
		'std' => 4,
		'type' => 'select',
		'class'=> 'mini',
		'options'=>$cols);
	
	$grids = array(
		'squares' => 'Lay out in squares',
		'grid2' => 'Lay out with images and text',
		'list' => 'Lay out in a chronological list',
		'custom' => 'Create your own layout'
		);
	$options[] = array(
		'name' => __('Grid style layout', 'ccb'),
		'desc' => __('Configure the layout of the grid','ccb'),
		'id' => 'ccb_grid',
		'std' => 'grid2',
		'type' => 'select',
		'options'=>$grids);
	$options[] = array(
		'name' => __('Grid style layout for Front page', 'ccb'),
		'desc' => __('Grid style for front page (main blog page)','ccb'),
		'id' => 'ccb_gridfront',
		'std' => 'list',
		'type' => 'select',
		'options'=>$grids);			
	$options[] = array(
		'name' => __('In Post Thumbnails', 'ccb'),
		'desc' => __('Generate thumbnail from post body if no thumbnail is set', 'ccb'),
		'id' => 'inpostthumbnails',
		'type' => 'checkbox');	
	$options[] = array(
		'name' => __('Flickr', 'ccb'),
		'desc' => __('Show images from Flickr for posts without images', 'ccb'),
		'id' => 'flickr',
		'type' => 'checkbox',
		'class' => 'toggle');
	$options[] = array(
		'name' => __('Flickr API key', 'ccb'),
		'desc' => __('You can request one <a href="https://www.flickr.com/services/api/misc.api_keys.html">here.</a>', 'ccb'),
		'id' => 'flickr_api_key',
		'std' => '',
		'type' => 'text',
		'class'=> 'hidden');
	$options[] = array(
		'name' => __('Persistent Thumbnails', 'ccb'),
		'desc' => __('Make thumbnails persistent using cookies (requires this <a href"https://wordpress.org/plugins/wp-session-manager/">plugin</a>)', 'ccb'),
		'id' => 'ccb_persistent_thumbs',
		'type' => 'checkbox',
		'std' => '');
	$options[] = array(
		'name' => __('Use Masonry', 'ccb'),
		'desc' => __('Use masonry to fill in the gaps on the layout.', 'ccb'),
		'id' => 'ccb_masonry',
		'type' => 'checkbox');	
	$options[] = array(
		'name' => __('Upload a fallback thumbnail image', 'ccb'),
		'id' => 'ccb_fallback_thumbnail',
		'type' => 'upload');
	/* Advanced settings notice */	
	$options[] = array(
		'id' => 'advancedsettings',
		'name' => __('Advanced settings', 'options_framework_theme'),
		'type' => 'heading');
	$options[] = array(
		'id' => 'introtextadvancedsettings',
		'type' => 'text',
		'desc' => __('This theme can be fully adapted. It is recommended that you create a child theme first and edit the file @settings.less.
		<ul>
		<li> You can change the colors, backgrounds and spacings</li>
		<li> To change fonts for this theme, you have to work with the .LESS file. The google font is imported there. See <a href="https://developers.google.com/fonts/docs/getting_started#Quick_Start">Google Fonts Documentation</a> for details.</li></ul>', 'ccb')
		. __('You can use these POST-META values: <ul><li>guest-author</li><li>guest-author-link</li></ul>')
		);
	/* Options for multisite blog aggregation if enabled */
	if (is_multisite()) {
		$options[] = array(
		'name' => __('Multisite Settings', 'options_framework_theme'),
		'type' => 'heading');
	
		$options[] = array(
		'name' => __('Multisite', 'ccb'),
		'desc' => __('Enable to aggregate several sites (blogs)', 'ccb'),
		'id' => 'multisite_on',
		'type' => 'checkbox',
		'class' => 'toggle');

		$options[] = array(
			'name' => __('Multisite: Select Sites to aggregate', 'ccb'),
			'desc' => __('Select the sites to use', 'ccb'),
			'id' => 'mu_blogs',
			'type' => 'multicheck',
			'std' => array (0=>'1'),
			'class' => 'hidden',
			'options' => multisite::mu_blog_names()	);
	}
	
	return $options;
}

/* Load custom Javascript for option framework */
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.toggle').click(function() {
			jQuery(this).next().fadeToggle(400);
		});
		//Show items that were revealed previously
		jQuery('.toggle input:checked').parents(".section").next().show();
		if (jQuery('.toggle input:checked').val() !== undefined) {
			jQuery('.toggle input:checked').parent(".section").next().show();
		}
	});
	</script>
<?php } 
?>