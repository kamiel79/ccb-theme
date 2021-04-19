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
 * April 2021, dumb as ever
 */
function ccb_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->add_section( 'ccb_additions', array (
    	'title' => 'CCB additions',
    	'priority' => 30,
    ));
    // Sidebar Initial
	$wp_customize->add_setting( 'ccb_sidebarinitial' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_sidebarinitial',
		array('label' => __( 'Context/Sidebar visible on start?', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_sidebarinitial', 'type' => 'checkbox') ));

    // Add menu tags
	$wp_customize->add_setting( 'ccb_menu_tags' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_tags',
		array('label' => __( 'Tags in Menu', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_menu_tags', 'type' => 'checkbox') ));

	// Add context to menu
	$wp_customize->add_setting( 'ccb_menu_context' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_context',
		array('label' => __( 'Add context to menu', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_menu_context', 'type' => 'checkbox') ));

	//Add Infocus to menu
	$wp_customize->add_setting( 'ccb_menu_infocus' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_infocus',
		array('label' => __( 'Add In focus to menu', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_menu_infocus', 'type' => 'checkbox') ));
	$wp_customize->add_setting( 'ccb_menu_infocus_min' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_menu_infocus_min',
		array('label' => __( 'Minimal # of posts for Infocus', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_menu_infocus_min', 'type' => 'text') ));

	//Stylesheet for Front and Archive pages
	$wp_customize->add_setting( 'ccb_grid' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_grid',
		array('label' => __( 'Style for Archives', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_grid', 'type' => 'select', 'choices' => CCB_LAYOUT_STYLES ) ));
	$wp_customize->add_setting( 'ccb_gridfront' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_gridfront',
		array('label' => __( 'Style for Front Page', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_gridfront', 'type' => 'select', 'choices' => CCB_LAYOUT_STYLES ) ));
	$wp_customize->add_setting( 'ccb_cols' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_cols',
		array('label' => __( 'Standard number of Columns', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_cols', 'type' => 'text') ));

	//SVG clip for square grid
	$wp_customize->add_setting( 'ccb_clip_shape' , array('default'   => 1, 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Control ($wp_customize, 'ccb_clip_shape',
		array('label' => __( 'Shape in the Grid', 'ccb'), 'section'  => 'ccb_additions',
			'settings' => 'ccb_clip_shape', 'type' => 'select', 'choices' => CCB_CLIP_SHAPES ) ));
}
add_action( 'customize_register', 'ccb_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ccb_customize_preview_js() {
	wp_enqueue_script( 'ccb_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'ccb_customize_preview_js' );
