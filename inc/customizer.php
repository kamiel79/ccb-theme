<?php
/**
 * Creative Choice Blog Theme Customizer
 *
 * @package Creative Choice Blog
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
/* Setting up the Customizer
 * Using https://codex.wordpress.org/Theme_Customization_API
 * April 2021, dumb as ever & October 2022
 */
 
 



function ccb_customize_register( $wp_customize ) {
	
	/** Custom controls and sanitize functions */
	include "ccb_customizer_controls.php";
	
	/**
	 * Add our Header & Navigation Panel
	 */
	 $wp_customize->add_panel( 'ccb_settings',
	   array(
		  'title' => __( 'CCB Settings' ),
		  'description' => esc_html__( 'Adjust settings for CCB.' )
		));
 
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->add_section( 'ccb_navigation', array (
    	'title' => 'Navigation',
		'panel' => 'ccb_settings',
    	'priority' => 30,
    ));
	 $wp_customize->add_section( 'ccb_extras', array (
    	'title' => 'Extras',
		'panel' => 'ccb_settings',
    	'priority' => 30,
    ));
	
	//Color
	$wp_customize->add_setting( 'ccb_color1',
	   array(
		  'default' => '#dd9933',
		  'transport' => 'refresh',
		  'sanitize_callback' => 'sanitize_hex_color'
	   )
	);
	 
	$wp_customize->add_control( 'ccb_color1',
	   array(
		  'label' => __( 'Default Color Control' ),
		  'description' => esc_html__( 'The theme primary color' ),
		  'section' => 'ccb_extras',
		  'type' => 'color',
		  'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
	   )
	);
	
	
	
    // Sidebar Initial
	$wp_customize->add_setting( 'ccb_sidebarinitial' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_sidebarinitial',
		array('label' => __( 'Context/Sidebar visible on start?', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_sidebarinitial', 'type' => 'checkbox') ));
	// Sidebar delay
	$wp_customize->add_setting( 'ccb_sidebardelay' , array('default'   => 1, 'transport' => 'refresh',
			'sanitize_callback'=> 'absint'));
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_sidebardelay',
		array('label' => __( 'Delay for sidebar slide in ms', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_sidebardelay', 'type' => 'text') ));
			
	// Breadcrumb Trail
	$wp_customize->add_setting( 'ccb_breadcrumb' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_breadcrumb',
		array('label' => __( 'Show breadcrumb trail', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_breadcrumb', 'type' => 'checkbox') ));
			
	// Search Highlighting
	$wp_customize->add_setting( '' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_searchighlighting',
		array('label' => __( 'Highlight term in search results', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_searchighlighting', 'type' => 'checkbox') ));

    // Add menu tags
	$wp_customize->add_setting( 'ccb_menu_tags' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_tags',
		array('label' => __( 'Tags in Menu', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_menu_tags', 'type' => 'checkbox') ));

	// Add context to menu
	$wp_customize->add_setting( 'ccb_menu_context' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_context',
		array('label' => __( 'Add context to menu', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_menu_context', 'type' => 'checkbox') ));

	//Add Infocus to menu
	$wp_customize->add_setting( 'ccb_menu_infocus' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_infocus',
		array('label' => __( 'Add In focus to menu', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_menu_infocus', 'type' => 'checkbox') ));
	$wp_customize->add_setting( 'ccb_menu_infocus_min' , array('default'   => 1, 'transport' => 'refresh',
			'sanitize_callback'=> 'absint'));
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_infocus_min',
		array('label' => __( 'Minimal # of posts for Infocus', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_menu_infocus_min', 'type' => 'text') ));
	
	// Fix navigation to top of screen
	$wp_customize->add_setting( 'ccb_fix_nav' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_fix_nav',
		array('label' => __( 'Fix navigation on scroll', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_fix_nav', 'type' => 'checkbox') ));

	// Float metacolumn
	$wp_customize->add_setting( 'ccb_metacolumnfloat' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_metacolumnfloat',
		array('label' => __( 'Float metacolumn', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_metacolumnfloat', 'type' => 'checkbox') ));
	
	// AJAX load
	$wp_customize->add_setting( 'ccb_ajax_load' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_ajax_load',
		array('label' => __( 'Use AJAX to load', 'ccb'), 'section'  => 'ccb_navigation',
			'settings' => 'ccb_ajax_load', 'type' => 'checkbox') ));

	
	//Stylesheet for Front and Archive pages
	$wp_customize->add_setting( 'ccb_grid' , array('default' => 'grid2', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_grid',
		array('label' => __( 'Style for Archives', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_grid', 'type' => 'select', 'choices' => CCB_LAYOUT_STYLES ) ));
	$wp_customize->add_setting( 'ccb_gridfront' , array('default'   => 'list', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_gridfront',
		array('label' => __( 'Style for Front Page', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_gridfront', 'type' => 'select', 'choices' => CCB_LAYOUT_STYLES ) ));
	$wp_customize->add_setting( 'ccb_cols' , array('default'  => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_cols',
		array('label' => __( 'Standard number of Columns', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_cols', 'type' => 'text') ));

	// Flickr images
	$wp_customize->add_setting( 'ccb_flickr' , array('default' => 0, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_flickr',
		array('label' => __( 'Use Flickr to find images', 'ccb'), 'section' => 'ccb_extras',
			'settings' => 'ccb_flickr', 'type' => 'checkbox') ));
	$wp_customize->add_setting( 'ccb_flickr_api_key' , array('default' => '', 'transport' => 'refresh',
		'sanitize_callback' => 'ccb_sanitize_api_key') );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_flickr_api_key',
		array('label' => __( 'Flickr API key', 'ccb'), 'section' => 'ccb_extras',
			'settings' => 'ccb_flickr_api_key', 'type' => 'text') ));
			
			
	//SVG clip for square grid
	$wp_customize->add_setting( 'ccb_clip_shape' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_clip_shape',
		array('label' => __( 'Shape in the Grid', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_clip_shape', 'type' => 'select', 'choices' => CCB_CLIP_SHAPES ) ));
	
	// In-Post Thumbnails
	$wp_customize->add_setting( 'ccb_inpostthumbnails' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_inpostthumbnails',
		array('label' => __( 'Generate thumbnails dynamically from posts', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_inpostthumbnails', 'type' => 'checkbox') ));
	 
	//Persistent Thumbnails
	$wp_customize->add_setting( 'ccb_persistent_thumbs' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_persistent_thumbs',
		array('label' => __( 'Make dynamic thumbnails persistent', 'ccb'), 'section'  => 'ccb_extras',
			'settings' => 'ccb_persistent_thumbs', 'type' => 'checkbox') ));
	
	//HEADER image
	 $wp_customize->add_setting( 'ccb_header_image', array(
        'default' => get_theme_file_uri('img/thumb/bkg.jpg'), // Add Default Image URL 
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ccb_header_image_control', array(
        'label' => 'Upload Header Background',
        'priority' => 20,
        'section' => 'ccb_extras',
        'settings' => 'ccb_header_image',
        'button_labels' => array(// All These labels are optional
                    'select' => 'Select Image',
                    'remove' => 'Remove Image',
                    'change' => 'Change Image',
                    )
    )));
    //Fallback Thumbnail image
	  $wp_customize->add_setting( 'ccb_fallback_thumbnail', array(
	  	'default' => get_theme_file_uri('img/noimg.png'), // Add Default Image URL 
        'sanitize_callback' => 'esc_url_raw'
    ));
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ccb_fallback_thumbnail', array(
        'label' => 'Upload Fallback Thumbnail',
        'priority' => 20,
        'section' => 'ccb_extras',
        'settings' => 'ccb_fallback_thumbnail',
        'button_labels' => array(// All These labels are optional
                    'select' => 'Select Image',
                    'remove' => 'Remove Image',
                    'change' => 'Change Image',
                    )
    )));
	
	/* Multisite Aggregation */
	$wp_customize->add_section( 'ccb_multisite', array (
    	'title' => 'Multisite Settings',
		'panel' => 'ccb_settings',
    	'priority' => 100,
    ));
	
	$wp_customize->add_setting( 'ccb_multisite_on' , array('default'   => 0, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_multisite_on',
		array('label' => __( 'Multisite Aggregation', 'ccb'), 'section'  => 'ccb_multisite',
			'settings' => 'ccb_multisite_on', 'type' => 'checkbox') ));
	$wp_customize->add_setting( 'ccb_multisite_blogs' , array(
    'default' => array(), 'transport' => 'refresh' ) );

	$wp_customize->add_control( new CCB_Multiple_Select ($wp_customize, 'ccb_multisite_blogs',
		array('label' => __( 'Which Blogs', 'ccb'), 'section'  => 'ccb_multisite',
			'settings' => 'ccb_multisite_blogs', 'type' => 'multiple-select', 'choices' => multisite::mu_blog_names() )) );


}
add_action( 'customize_register', 'ccb_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ccb_customize_preview_js() {
	wp_enqueue_script( 'ccb_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'ccb_customize_preview_js' );




