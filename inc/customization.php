<?php

/**
 * simple Theme Modification API
 *
 * Sets up some helper functions for WP's theme-mod api, which then would be used by child themes 
 *
 * @package WordPress
 * @subpackage simple
 */

/**
 * A global array to which child themes can add values.  These values will be parsed by the WP Theme Mod API
 *
 * In wp-admin -> appearance -> customize, you have a GUI for altering various theme values.  This array creates those values.
 * Here, we have commented-out examples.  Child themes would use this format for adding nodes to the customization screen.
 *
 * * label - The heading for the section in wp-admin 
 * * slug - Should be a unique value for referring to this section
 * * fields - An array of customization nodes grouped into the current section
 * * * fields[label] - The heading for this node in wp-admin
 * * * fields[slug] - Should be a unique value for referring to this node
 * * * fields[type] - Tells WP which kind of input to use (text, color, select, etc)
 * * * fields[default] - The default value for this node
 * * * fields[is_style] - Set to '1' to tell WP that this is a style rule.  WP will then output it between <style> tags at wp_head()
 * * * fields[is_media_style] - Used instead of is_style to target smaller devices.  Example:  Set to 700 to tell WP to apply this to devices NARROWER THAN 700px.
 * * * fields[selector] - Used with is_style or is_media_style to designate a CSS selector (p, div, .post-content a)
 * * * fields[property] - Used with is_style or is_media_style to designate a CSS property (color, border-top-width, etc)
 * * * fields[is_bg] - Used with is_style or is_media_style to tell WP to output the value inside url().  Expects to be a valid url pointing to an image source.
 * * * fields[choices] - Used with <select> elements to create <option>'s.  Expects an associative array of key=>value pairs.
 *
 * @var array
 */

$simple_base_customization_options = array(

	'Layout' => array(
		'label' => 'Layout',
		'slug' => 'layout',
		'fields' => array(
			array(
				'label' => 'min-width',
				'slug' => 'min-width',
				'type' => 'text',
				'default' => "",
				'is_style'=> 1,
				'selector'=> '.outer-wrapper',
				'property' => 'min-width'
			),
			array(
				'label' => 'max-width',
				'slug' => 'max-width',
				'type' => 'text',
				'default' => "",
				'is_style'=> 1,
				'selector'=> '.inner-wrapper',
				'property' => 'max-width'
			),	
		)
	),

);

	/*
	'Universals' => array(
		'label' => 'Universals',
		'slug' => 'universals',
		'fields' => array(
			array('label' => 'Link Color',							'slug' => 'link_color', 					'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'a', 			'property' => 'color'),
			array('label' => 'Link Hover Color',					'slug' => 'link_hover_color',				'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'a:hover',		'property' => 'color'),
			array('label' => 'Text Color', 							'slug' => 'text_color', 					'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'body', 		'property' => 'color'),
			array('label' => 'Content Header Color', 				'slug' => 'content_header_color', 					'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.main h1, .main h2, .main h3, .main h4, .main h5, .main h6, .main dt', 		'property' => 'color'),
			array('label' => 'Firm Logo', 			'slug' => 'firm_logo', 			'type' => 'image',		'default' => "",	'is_style'=> 0 ),
			
			
			array('label' => 'Body Font Family', 	'slug' => 'body_font_family', 		'type' => 'select',		'default' => "",	'is_style'=> 1,		'selector'=> 'body, [class^="icon-"] a, [class*=" icon-"] a', 	'property' => 'font-family',
				'choices' => array(
					'"futura-pt", Arial, Helvetica, sans-serif' => 'Futura PT (Typekit), Arial, Helvetica, sans-serif',
					'Georgia, "Times New Roman", Times, serif' => 'Georgia, "Times New Roman", Times, serif',
					'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS, Helvetica, sans-serif'
				)
			),
			
			array('label' => 'Header Font Family', 	'slug' => 'header_font_family', 		'type' => 'select',		'default' => "",	'is_style'=> 1,		'selector'=> 'h1, h2, h3', 	'property' => 'font-family',
				'choices' => array(
					'"futura-pt", Arial, Helvetica, sans-serif' => 'Futura PT (Typekit), Arial, Helvetica, sans-serif',
					'Georgia, "Times New Roman", Times, serif' => 'Georgia, "Times New Roman", Times, serif',
					'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS, Helvetica, sans-serif'
				)
			),
			
			array('label' => 'Body Background',		'slug' => 'body_background', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'body', 		'property' => 'background-color'),
			array('label' => 'Body Tile', 			'slug' => 'body_tile', 			'type' => 'image',		'default' => "",	'is_style'=> 1, 'selector'=> 'body', 'property' => 'background-image', 'is_bg' => '1'),
				
				
		)
	),
	
	'Header'=> array(
		'label' => 'Header',
		'slug' => 'header',
		'fields' => array(
			
			array('label' => 'Header BG Image', 			'slug' => 'header_bg_image', 			'type' => 'image',		'default' => "",	'is_style'=> 1, 'selector'=> '.blog-header', 'property' => 'background-image', 'is_bg' => '1'),
			
			array('label' => 'Blog Name Color',				'slug' => 'blog_name_color', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'h1.blog-title, h1.blog-title a, .blog-header .blog-title a:hover span.word_2, .blog-header .blog-title a:hover span.word_4, .blog-header .blog-title a:hover span.word_6', 									'property' => 'color'),
			array('label' => 'Alt Blog Name Color',				'slug' => 'alt_blog_name_color', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'h1.blog-title:hover, h1.blog-title a:hover, .blog-header .blog-title a span.word_2, .blog-header .blog-title a span.word_4, .blog-header .blog-title a span.word_6', 									'property' => 'color'),
		
			array('label' => 'Blog Description Color',				'slug' => 'blog_description_color', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> 'h2.blog-description', 							'property' => 'color'),
		
			array('label' => 'Menu Toggle Background Color',				'slug' => 'menu_toggle_background_color', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.menu-toggle', 							'property' => 'background-color'),
			array('label' => 'Menu Toggle Color',				'slug' => 'menu_toggle_color', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.menu-toggle', 							'property' => 'color'),
		
		
		)
	),
	
	'Sidebar' => array(
		'label' => 'Sidebar',
		'slug' => 'sidebar',
		'fields' => array(
		
			array('label' => 'Sidebar Background',					'slug' => 'sidebar_background', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.main-wrapper .sidebar', 		'property' => 'background-color'),
			array('label' => 'Links Widget Border Color',					'slug' => 'link_widget_border_color', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.links li', 		'property' => 'border-color'),
				
			array('label' => 'Links Widget Border Style', 	'slug' => 'links_widget_border_style', 		'type' => 'select',		'default' => "",	'is_style'=> 1,		'selector'=> '.links li', 	'property' => 'border-style',
				'choices' => array(
					'Dashed' => 'dashed',
					'Dotted' => 'dotted',
					'Solid' => 'solid'
				)
			),			
		)
	),
	
	'Post Content' => array(
		'label' => 'Post Content',
		'slug' => 'post-content',
		'fields' => array(
			array('label' => 'Author Gravatars',					'slug' => 'author_gravatars', 			'type' => 'checkbox',		'default' => "",	'is_style'=> 0),
		)
	),
	
	'Footer' => array(
		'label' => 'Footer',
		'slug' => 'footer',
		'fields' => array(
		
			array('label' => 'Footer Background Color',					'slug' => 'footer_background', 			'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.blog-footer', 		'property' => 'background-color'),
			array('label' => 'Footer Text Color',						'slug' => 'footer_text_color', 						'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.blog-footer', 		'property' => 'color'),
			array('label' => 'Footer Link Color',						'slug' => 'footer_link_color', 						'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.blog-footer a', 		'property' => 'color'),
			
			array('label' => 'Footer Top Right BG Color',						'slug' => 'footer_top_right_bg_color', 						'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.footer-right-top', 		'property' => 'background-color'),
			array('label' => 'Footer Top Right Text Color',						'slug' => 'footer_top_right_text_color', 						'type' => 'color',		'default' => "",	'is_style'=> 1,		'selector'=> '.footer-right-top, .footer-right-top a', 		'property' => 'color'),
		
		)
	)
	*/


/**
 * add various types of inputs to the customization panel, add all registered sections and nodes to the panel, flush the transient cache for theme mods
 */
function simple_base_themedemo_customize($wp_customize) {
		
	// the parent theme creates this empty array, child themes add nodes to it
	global $simple_base_customization_options;
	
	// for each registered section, create a UI for it 
	foreach ($simple_base_customization_options as $l){
		
		$wp_customize->add_section( $l['slug'], array(
        	'title'          => $l['label'],
        	'priority'       => 35,
    	) );
    	
    	// within that section, create form inputs
    	foreach($l['fields'] as $f){
    		
    		// establish a default value for the input
    		$wp_customize->add_setting( $f['slug'], array(
        		'default'        => $f['default'],
    		) );
    		
    		// establish which type of form input to use
    		if($f['type'] == 'color'){
	    		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $f['slug'], array(
	   	 			'label'   => $f['label'],
	   	     		'section' => $l['slug'],
	   	     		'settings'   => $f['slug'],
	   	 		) ) );
    		
    		} elseif($f['type'] == 'select'){
    		    $wp_customize->add_control( $f['slug'], array(
        			'label'   => $f['label'],
        			'section' => $l['slug'],
        			'type'    => 'select',
        			'choices' => $f['choices']
				) );
        
    		} else {
				$wp_customize->add_control( $f['slug'], array(
        			'label'   => $f['label'],
        			'section' => $l['slug'],
        			'type'    => 'text',
 				) );
    		}
    	}
	}
}
add_action('customize_register', 'simple_base_themedemo_customize');

/**
 * Grab the custom styles from the transient cache
 * 
 * @return string The theme mod styles for this blog, between <style> tags
 */
function simple_base_get_custom_styles(){
	
 	// all of the custom options registered by the child theme
 	global $simple_base_customization_options;

 	// for each setting section
	foreach ( $simple_base_customization_options as $l ) {
 		
		// for each setting within this section 
 		foreach($l['fields'] as $f){
    	
				$out.= $f['selector'].' {'.$f['property'].': '.get_theme_mod( $f['slug'], '' ).';}';
			
		} 
  	}

	// sanitize the output
	//$out = esc_html( $out, '<style>' );
  
  	// if there are styles, wrap them
	if( !empty( $out ) ) {
		$out = '
			<style>'.$out.'</script>
		';
	}
	
	return $out;
}

/**
 * Echoes the theme mod styles in wp_head
 */
function simple_base_the_custom_styles(){
	echo simple_base_get_custom_styles();
}
add_action('wp_head', 'simple_base_the_custom_styles');