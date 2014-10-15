<?php

/**
 * anchorage Theme Modification API
 *
 * Sets up some helper functions for WP's theme-mod api, which then would be used by child themes 
 *
 * @package WordPress
 * @subpackage anchorage
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

$anchorage_base_customization_options = array(

	'Layout' => array(
		'label' 	=> 'Layout',
		'slug' 		=> 'layout',
		'fields' 	=> array(
			array(
				'label' 	=> 'min-width',
				'slug' 		=> 'min-width',
				'type' 		=> 'text',
				'default' 	=> "",
				'is_style'	=> 1,
				'selector'	=> '.outer-wrapper, body',
				'property' 	=> 'min-width',
			),
			array(
				'label' 	=> 'max-width',
				'slug' 		=> 'max-width',
				'type' 		=> 'text',
				'default' 	=> "",
				'is_style'	=> 1,
				'selector'	=> '.inner-wrapper',
				'property' 	=> 'max-width',
			),	
		)
	),

);

/**
 * add various types of inputs to the customization panel, add all registered sections and nodes to the panel, flush the transient cache for theme mods
 */
function anchorage_base_themedemo_customize($wp_customize) {
		
	// the parent theme creates this empty array, child themes add nodes to it
	global $anchorage_base_customization_options;
	
	// for each registered section, create a UI for it 
	foreach ($anchorage_base_customization_options as $l){
		
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
add_action('customize_register', 'anchorage_base_themedemo_customize');

/**
 * Grab the custom styles from the transient cache
 * 
 * @return string The theme mod styles for this blog, between <style> tags
 */
function anchorage_base_get_custom_styles(){
	
	$out='';

 	// all of the custom options registered by the child theme
 	global $anchorage_base_customization_options;

 	// for each setting section
	foreach ( $anchorage_base_customization_options as $l ) {
 		
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
			<style>'.$out.'</style>
		';
	}
	
	return $out;
}

/**
 * Echoes the theme mod styles in wp_head
 */
function anchorage_base_the_custom_styles(){
	echo anchorage_base_get_custom_styles();
}
add_action('wp_head', 'anchorage_base_the_custom_styles');