<?php
/**
 * Twenty Thirteen functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/*
 * Set up the content width value based on the theme's design.
 *
 * @see icicle_content_width() for template-specific adjustments.
 */
if ( ! isset( $content_width ) )
	$content_width = 604;

/**
 * Twenty Thirteen setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Thirteen supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function icicle_setup() {

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'icicle' ) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 604, 270, true );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'icicle_setup' );


/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function icicle_scripts_styles() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Loads our main stylesheet.
	wp_enqueue_style( 'icicle-style', get_stylesheet_uri(), array(), '2013-07-18' );
	wp_enqueue_script( 'jquery' );

}
add_action( 'wp_enqueue_scripts', 'icicle_scripts_styles' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function icicle_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'icicle' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'icicle_wp_title', 10, 2 );

/**
 * Register two widget areas.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function icicle_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Widget Area', 'icicle' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the footer section of the site.', 'icicle' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Secondary Widget Area', 'icicle' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'icicle' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'icicle_widgets_init' );

/**
 * Display navigation to next/previous set of posts when applicable.
 */
if ( ! function_exists( 'icicle_paging_nav' ) ) {
	function icicle_paging_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) { return false; }

		$out = "";

		if( get_next_posts_link() ) {
			$out .= "<span class='next next-posts'>".get_next_posts_link( "<span class='arrow'>&larr;</span> ".esc_html__( 'Older Posts', 'icicle' ) )."</span>";
		}

		if( get_previous_posts_link() ) {
			$out .= "<span class='prev previous-posts'>".get_previous_posts_link( esc_html__( 'Newer Posts', 'icicle' )." <span class='arrow'>&rarr;</span>" )."</span>";
		}


		if( empty( $out ) ) { return false; }

		$out = "
			<nav class='accent-font paging-navigation posts-navigation clear' role='navigation'>
				<h1 class='screen-reader-text'>".esc_html__( 'Posts navigation', 'icicle' )."</h1>
				$out
			</nav>
		";

		return $out;

	}
}

/**
 * Display navigation to next/previous post when applicable.
 */
function icicle_post_nav() {
	global $post;

	if( get_next_post_link() ) {
		$out .= "<span class='next next-post'>".get_next_post_link( "%link", "<span class='arrow'>&larr;</span> %title" )."</span>";
	}

	if( get_previous_post_link() ) {
		$out .= "<span class='prev previous-post'>".get_previous_post_link( "%link", "%title <span class='arrow'>&rarr;</span>" )."</span>";
	}

	if( empty( $out ) ) { return false; }

	$out = "
		<nav class='accent-font paging-navigation post-navigation clear' role='navigation'>
			<h1 class='screen-reader-text'>".esc_html__( 'Post navigation', 'icicle' )."</h1>
			$out
		</nav>
	";
	return $out;	
}

/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own icicle_entry_meta() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function icicle_entry_byline() {
	
	$date = icicle_entry_date();

	$out = "
		<div class='entry-byline'>
			&mdash; <address class='vcard'><a href='".get_bloginfo('url')."' class='fn url'>By ".get_the_author()."</a></address>,
			$date
		</div>
	";
	return $out;
}



function icicle_entry_cats(){

	$out = '';
	$categories_list = get_the_category_list( esc_html__( ', ', 'icicle' ) );
	if ( $categories_list ) {
		$out = "<div class='category-links'>&mdash; $categories_list &mdash;</div>";
	}

	return $out;

}





function icicle_entry_tags(){

	$out = '';
	$tags_list = get_the_tag_list( '', esc_html__( ', ', 'icicle' ), '' );
	if ( $tags_list ) {
		$out = "
			<div class='tag-links'>
				<span class='tag-label'>".esc_html__( 'Tags:', 'icicle' )."</span>
				$tags_list
			</div>";
	}

	return $out;

}



function icicle_author_bio(){
	
	//global $post;

	$desc = wp_kses_post( get_the_author_meta('description') );
	if( !empty ( $desc ) ) {
		$desc = "<div class='content-holder author-description'>$desc</div>";	
	}
		
	$author_email = sanitize_email( get_the_author_meta( 'user_email' ) );
	$display_name_attr = esc_attr( get_the_author_meta( 'display_name' ) );
	$avatar = get_avatar( $author_email, 250, '', $display_name_attr );

	//the fail image from gravatar has the string 'blank.gif' and we don't want to show the fail image
	if( empty( $avatar ) || stristr( $avatar, 'blank.gif' ) ) {
		$avatar = "";
	} else {
		$avatar ="
			<div class='vcard avatar-wrap'>
				<span class='fn'>
					$avatar
				</span>
			</div>
		";
	}

	$out = $avatar.$desc;

	if( !empty( $out ) ){
		$out = "
			<div class='content-holder author-bio'>
				$avatar
				$desc
			</div>
			<hr class='break break-minor'>
		";
	}

	return $out;
}


// Get src URL from avatar <img> tag (add to functions.php)
function get_avatar_url($author_id, $size){
    $get_avatar = get_avatar( $author_id, $size );
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return ( $matches[1] );
}


if ( ! function_exists( 'icicle_entry_date' ) ) :
/**
 * Print HTML with date information for current post.
 *
 * Create your own icicle_entry_date() to override in a child theme.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function icicle_entry_date( ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'icicle' );
	else
		$format_prefix = '%2$s';

	$out = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'icicle' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	return $out;
}
endif;

if ( ! function_exists( 'icicle_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function icicle_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Twenty thirteen 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'icicle_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string The Link format URL.
 */
function icicle_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function icicle_body_class( $classes ) {
	if ( ! is_multi_author() ) {$classes[] = 'single-author';}

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() ){$classes[] = 'sidebar';}

	if ( ! get_option( 'show_avatars' ) ){$classes[] = 'no-avatars';}

	return $classes;
}
add_filter( 'body_class', 'icicle_body_class' );

function icicle_post_class( $classes ) {

	$classes[] = 'inner-wrapper';

	return $classes;
}
add_filter( 'post_class', 'icicle_post_class' );








function icicle_sticky_style(){
	?>
	<style>
		.sticky .entry-title a:before{content: "<?php echo __('Sticky:', 'icicle'); ?> ";}
	</style>
	<?php
}
add_action('wp_head','icicle_sticky_style');











$content_width = 700;












	add_action('admin_footer', 'add_indent');
	
	function add_indent(){

	?>
	<script language="JavaScript" type="text/javascript"><!--
		eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(l($,D,W,1o){4 c;4 T;4 B=\'\\n\';$.1i.1s=l(){c=U;T=c[0];6(!$.v.12)c.1f(A);a c.1l(A);6($.v.19||$.v.12)B=\'\\r\\n\';H U};$.1i.1q=l(){c=U;6(!$.v.12)c.1j(\'1f\',A);a c.1j(\'1l\',A);c=k;T=k;H U};l A(e){6(e.1g==13){4 5=d().5;4 F=T.2.7(0,5).11(\'\\n\');F=(F==-1?0:F+1);4 y=T.2.7(F,5).f(/^\\t+/g);6(y!=k){e.1h();4 N=16();4 C=B;1u(4 i=0;i<y[0].b;i++)C+=\'\\t\';T.2=T.2.7(0,5)+C+T.2.7(5);z(5+C.b,5+C.b);14(N)}}a 6(e.1g==9){e.1h();4 N=16();4 3=d();6(3.5!=3.8&&T.2.Y(3.5,1)==\'\\n\')3.5++;4 y=T.2.7(3.5,3.8).f(/\\n/g);6(y!=k){4 q=T.2.7(0,3.5).11(B);4 u=(q!=-1?q:0);6(!e.1k){4 p=T.2.7(u,3.8).S(/\\n/g,\'\\n\\t\');T.2=(q==-1?\'\\t\':\'\')+T.2.7(0,u)+p+T.2.7(3.8);z(3.5+1,3.8+y.b+1)}a{4 i=(T.2.Y((q!=-1?q+B.b:0),1)==\'\\t\'?1:0);4 X=T.2.7(u,3.8).f(/\\n\\t/g,\'\\n\');6(q==-1&&T.2.Y(0,1)==\'\\t\'){T.2=T.2.Y(1);X.1n(0)}4 p=T.2.7(u,3.8).S(/\\n\\t/g,\'\\n\');T.2=T.2.7(0,u)+p+T.2.7(3.8);z(3.5-i,3.8-(X!=k?X.b:0))}}a{6(!e.1k){T.2=T.2.7(0,3.5)+\'\\t\'+T.2.7(3.5);z(3.5+1,3.5+1)}a{4 w=T.2.7(0,3.5).11(\'\\n\');4 o=(w==-1?0:w);4 h=T.2.7(o+1).1H(\'\\n\');6(h==-1)h=T.2.b;a h+=o+1;6(w==-1){4 f=T.2.7(o,h).f(/^\\t/);4 p=T.2.7(o,h).S(/^\\t/,\'\')}a{4 f=T.2.7(o,h).f(/\\n\\t/);4 p=T.2.7(o,h).S(/\\n\\t/,\'\\n\')}T.2=T.2.7(0,o)+p+T.2.7(h);6(f!=k)z(3.5-(3.5-1>w?1:0),3.8-((3.5-1>w||3.5!=3.8)?1:0))}}14(N)}}l 16(){H{O:c.O(),Q:T.Q}}l 14(15){c.O(15.O+T.Q-15.Q)}l z(5,8){6(!$.v.19){T.1z(5,8);c.1w()}a{4 G=T.2.7(0,5).f(/\\r/g);G=(G!=k?G.b:0);4 E=T.2.7(5,8).f(/\\r/g);E=(E!=k?E.b:0);4 3=T.1a();3.1E(s);3.1D(\'M\',5-G);3.V(\'M\',8-5-E);3.1B()}};l d(){6(!$.v.19){H{5:T.1A,8:T.1C}}a{4 d=D.1I.1F().1v();4 j=D.1d.1a();j.1m(T);j.1e("1r",d);4 m=D.1d.1a();m.1m(T);m.1e("P",d);4 J=1b,L=1b,I=1b;4 1c,K,18,R,Z,10;1c=K=j.x;18=R=d.x;Z=10=m.x;1G{6(!J){6(j.17("P",j)==0){J=s}a{j.V("M",-1);6(j.x==1c){K+="\\r\\n"}a{J=s}}}6(!L){6(d.17("P",d)==0){L=s}a{d.V("M",-1);6(d.x==18){R+="\\r\\n"}a{L=s}}}6(!I){6(m.17("P",m)==0){I=s}a{m.V("M",-1);6(m.x==Z){10+="\\r\\n"}a{I=s}}}}1x((!J||!L||!I));H{5:K.b,8:K.b+R.b}}}})(1t,1p,1y);',62,107,'||value|range|var|start|if|substring|end||else|length|textarea|selection_range||match||i_e||before_range|null|function|after_range||i_s|tab|index||true||start_tab|browser|i_o|text|matches|set_focus|key_handler|lb|tabs||m_e|line|m_s|return|after_finished|before_finished|untrimmed_before_text|selection_finished|character|scroll_fix|scrollTop|StartToEnd|scrollHeight|untrimmed_selection_text|replace||this|moveEnd||removed|substr|after_text|untrimmed_after_text|lastIndexOf|opera||fix_scroll|obj|fix_scroll_pre|compareEndPoints|selection_text|msie|createTextRange|false|before_text|body|setEndPoint|keydown|keyCode|preventDefault|fn|unbind|shiftKey|keypress|moveToElementText|push|undefined|document|unindent|EndToStart|indent|jQuery|for|duplicate|focus|while|window|setSelectionRange|selectionStart|select|selectionEnd|moveStart|collapse|createRange|do|indexOf|selection'.split('|'),0,{}))


		function toggle_editor_tabs(e){
			if(jQuery(e).data('disabled') != 1){
				jQuery(e).data('disabled',1);
				jQuery(e).attr('value','Enable Tabs');
				jQuery("textarea#content").unindent();
			}else{
				jQuery(e).data('disabled',0);
				jQuery(e).attr('value','Disable Tabs');
				jQuery("textarea#content").indent();
			}

		}

		jQuery(document).ready(function () {
			jQuery("#ed_toolbar").append('<input id="ed_tabs" class="ed_button" type="button" value="Disable Tabs" onclick="toggle_editor_tabs(this)";/>');
			jQuery("textarea#content").indent();

		});
	//--></script>

	<?php

}





/*PRE===================================================================================*/
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );

add_filter( 'no_texturize_shortcodes', 'shortcodes_to_exempt_from_wptexturize' );
function shortcodes_to_exempt_from_wptexturize($shortcodes){
    $shortcodes[] = 'pre';
    return $shortcodes;
}

function sjf_deh_pre($atts, $content=null){

	//escape any html 
	$content = esc_html($content);
	
	//read the shortcode content into an array at each line break
	$content_array = explode(PHP_EOL, $content);
	
	//given that array, detect lines that are php comments -- like this one!
	$content_array = array_map("sjf_wrap_inline_comments", $content_array);
	
	//add classes for easy cross-browser zebra striping
	$content_array = sjf_odd_even($content_array);
	
	//re-assemble the array into a string
	$content = implode(PHP_EOL, $content_array);
	
	//wrap the output in pre tags
	$content = "<pre>$content</pre>";
	
	//trim any stray whitespace
	$content = trim($content);
	
	return $content;
}
add_shortcode('pre', 'sjf_deh_pre');

function sjf_odd_even($array){
	$i=1;
	$out=array();
	$odd_or_even='odd';
	foreach($array as $a){
		if($i % 2 == 0){
			$odd_or_even='even';	
		}else{
			$odd_or_even='odd';		
		}
		$a_wrapped = "<style>span.line_$i:before{content:'$i';}</style> <span class='line line_$i $odd_or_even'>$a</span>";
		$out[]=$a_wrapped;
		$i++;
	}
	return $out;
}

function sjf_wrap_inline_comments($string){
	$first_two_chars = substr(trim($string), 0, 2);
	if($first_two_chars == '//'){$string = "<span class='comment'>$string</span>";}
	return $string;
}












function sjf_shortcodes_instead_of_menu_items($items){
	$items = str_replace('<a href="#">[', '[', $items);
	$items = str_replace(']</a>', ']', $items);
	return $items;
}
add_filter('wp_nav_menu_items','sjf_shortcodes_instead_of_menu_items');	

function sjf_shortcodes_in_nav_menus ( $items) {
	return do_shortcode($items);
}
add_filter('wp_nav_menu_items','sjf_shortcodes_in_nav_menus');

function hello(){return "world";}
add_shortcode('hello','hello');


	


function sjf_string_with_wraps($atts){
	extract( shortcode_atts( array(
		'string' => get_bloginfo('name'),
		'break_after'=>'',
		'wrap'=>'',
	), $atts ) );

	if($string=='desc'){$string = get_bloginfo('description');}


	$words_array = explode(' ', $string);
	
	$break_array = explode(',', $break_after);
	
	$wrap_array = explode(',', $wrap);
	


	$i=0;
	$out='';
	foreach($words_array as $w){
		$maybe_break='';
		$i++;
		
		if(in_array($i, $break_array)) {$maybe_break='<br>';}

		$maybe_start_wrap='';
		$maybe_end_wrap='';

		if(isset($wrap_array[1])){

			if($i == $wrap_array[0]){$maybe_start_wrap='<span class="string_wrap">';}
			if($i == $wrap_array[1]){$maybe_end_wrap='</span>';}
		}

		$out.="$maybe_start_wrap <span class='word_$i'>$w</span> $maybe_end_wrap$maybe_break";
	}

	return $out;

}
add_shortcode('sjf_string_with_wraps','sjf_string_with_wraps');





/*


function icicle_add_menu_parent_class( $items ) {
	
	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = absint($item->menu_item_parent);
		}
	}
	
	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'lxb-base-menu-parent-item'; 
		}
	}
	
	return $items;    
}
add_filter( 'wp_nav_menu_objects', 'icicle_add_menu_parent_class' );

*/



















function icicle_the_html_classes() {
	?>
	<!--[if IE 7]>
		<html class="ie ie7" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if IE 8]>
		<html class="ie ie8" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if IE 9]>
		<html class="ie ie9" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if !(IE 7) | !(IE 8) | !(IE 9)  ]><!-->
		<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	
	<?php
}




function new_excerpt_more($more) {
       global $post;
	return '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read the full article...</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');




/**
 * Output some jQuery to auto_empty form fields
 *
 * @return void
 */
if( !function_exists( 'icicle_auto_empty_forms' ) ){
	function icicle_auto_empty_forms() {
		$out = <<<EOT
			<!-- Added by icicle -->
			<script>		
	
			jQuery(document).ready(function() {
			
				jQuery('input[type="text"], input[type="email"], input[type="search"], textarea').focus(function() {
					if (this.value == this.defaultValue){
						this.value = '';
					}
					if(this.value != this.defaultValue){
						this.select();
					}
				});

				jQuery('input[type="text"], input[type="email"], input[type="search"], textarea').blur(function() {
					if (this.value == ''){
						this.value = this.defaultValue;
					}
				});

			});
			</script>
EOT;
		echo $out;
	}
}
add_action('wp_footer','icicle_auto_empty_forms');













function icicle_search_form( $form_class='', $search_input_class='' ) {
	
	$form_class = sanitize_html_class( $form_class );

	$search_input_class = sanitize_html_class ( $search_input_class );

	$out ="
		<form action='".esc_url( home_url( '/' ) )."' class='$form_class search-form' method='get' role='search'>
			<span class='screen-reader-text'>Search for:</span>
			<input type='search' title='Search for:' name='s' value='Search' class='search-field shadowed $search_input_class'>
			<input type='submit' value='Search' class='screen-reader-text search-submit'>
		</form>
	";

	return $out;

}








function icicle_ga(){
	?>
		<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', 'UA-45817814-1', 'scottfennell.com');ga('send', 'pageview');</script>
	<?php
}
add_action('wp_footer', 'icicle_ga');









function icicle_gallery_captions() {
	?>
		<script>
			jQuery( '.gallery-item .gallery-caption' ).hide();
			jQuery( '.gallery-item' ).click( function( event ) {
				event.preventDefault();
				jQuery( this ).find( '.gallery-caption' ).fadeToggle();
			});
		</script>
	<?php
}
add_action('wp_footer', 'icicle_gallery_captions');


















/**
 * Comment template tags, all of which are pluggable
 *
 * @package WordPress
 * @subpackage icicle
 */

/**
 * Returns a link to the comments area for a post
 *
 * @param int $post_id The ID of the post we're grabbing from
 * @param string $icon_slug The slug of a font-awesome icon
 * @return bool Returns false if comments are closed, or returns a link
 */
if (!function_exists('icicle_comments_link')){
	function icicle_comments_link( $post_id = '', $icon_slug = 'comment' ){
		
		// start the output
		$out = "";

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// get the comment count for this post
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count->approved );

		// if comments are closed, bail
		if ( !comments_open( $post_id ) ) { return false; }

		// comments url
		$comments_url = esc_url( get_permalink( $post_id ).'#comments' );

		// comment_slug
		$icon_slug = esc_attr($icon_slug);

		// I18n
		$comments_title = esc_attr__('Join the discussion on this post', 'icicle');

		// the comment link
		$out='<a title="'.$comments_title.'" class="comments_link icon-'.$icon_slug.' comment_count_'.$comments_approved.'" href="'.$comments_url.'">'.' <span class="comment_count">'.$comments_approved.'</span></a>';
	
		return $out;

	}
}	

/**
 * Outputs the comments area for a post
 * 
 * @param  $post_id The ID of the post we're grabbing from
 * @return bool|void Will return false if comments closed or PW-protected.  Otherwise, outputs comments
 */
if (!function_exists('icicle_the_comments')){
	function icicle_the_comments( $post_id='' ){

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// don't show comments if pw-protected
		if ( post_password_required( $post_id ) ) { return false; }

		// if comments are not open for the post, return false
		if ( ! comments_open( $post_id ) ) {return false;}
			
		// if there are comments, this will contain text for the comments section title
		$comments_title = '';
		
		// if there are comments, this will contain them
		$the_comments = '';
		
		// if there are more comments than the pagination setting, this will contain them
		$comments_pagination = '';
		
		// get the comment count for this post
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count->approved );
		
		// are there comments approved for this post?
		if( !empty ( $comments_approved ) ) {
				
			$comments_title = "<div class='inner-wrapper'>".icicle_comments_title( $post_id )."</div>";

			$the_comments = "<div class='inner-wrapper comment-loop'>".icicle_get_post_comments( $post_id )."</div>";

			$comments_pagination = "<div class='inner-wrapper'>".icicle_comments_pagination( $post_id )."</div>";

		}

		// start the output
		echo '<div id="comments" class="outer-wrapper">'.$comments_title.$the_comments.$comments_pagination;

			// a form for adding a new comment
			echo '<div class="inner-wrapper">';
				comment_form( array(), $post_id );
			echo "</div>";	
		echo "</div>";
	}
}

/**
 * Returns the comments pagination for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments pagination for a post
 */
if(!function_exists('icicle_comments_pagination')){
	function icicle_comments_pagination($post_id=''){			
		
		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// does this blog break comments into pages?
		$page_comments = get_option( 'page_comments' );
		if(empty ( $page_comments ) ) { return false; }

		// how many comments per page?
		$comments_per_page = get_option( 'comments_per_page' );
				
		// get the comment count for this post
		$comments_count = wp_count_comments( $post_id );
		$comments_approved = absint( $comments_count->approved );

		// are there more comments than can fit on one page?  if so, show pagination
		if( $comments_approved <= $comments_per_page ) { return false; }

		// the link for newer comments
		$next_text = "<span class='arrow'>&larr;</span> ".esc_html__( 'Older Comments', 'icicle' );
					
		// the link for older comments
		$prev_text = esc_html__( 'Newer Comments', 'icicle' )." <span class='arrow'>&rarr;</span>";

		// wrap the comments pagination
		$out = "
			<nav class='clear paging-navigation comment-navigation accent-font' role='navigation'>
				<span class='next next-comments'>".get_next_comments_link( $next_text )."</span>
				<span class='prev previous-comments'>".get_previous_comments_link( $prev_text )."</span>
			</nav>
		";
	
		return $out;

	}
}





/**
 * Returns the comments title for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments title for a post
 */
if(!function_exists('icicle_comments_title')){
	function icicle_comments_title( $post_id='' ){

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		// create a title for the comments section depending on how many comments there are
		$out = sprintf(
			_nx(
				'One thought on &ldquo;%2$s&rdquo;',
				'%1$s thoughts on &ldquo;%2$s&rdquo;',
				get_comments_number( $post_id ),
				'comments title',
				'icicle'
			),
			number_format_i18n(
				get_comments_number( $post_id )
			),
			'<em class="comments-title-post-title">' . get_the_title( $post_id ) . '</em>'
		);
			
		// wrap the comments title
		$out = '<h2 class="comments-title">'.$out.'</h2>';

		return $out;

	}
}

/**
 * Returns the comments for a post
 *
 * @param int $post_id The id of the post we're grabbing from
 * @return string The comments for a post
 */
if(!function_exists('icicle_get_post_comments')){
	function icicle_get_post_comments( $post_id='' ){

		// determine which post we're grabbing from
		$post_id = absint( $post_id );
		if(empty($post_id)) {
			global $post;
			$post_id = absint( $post->ID );
		}

		//Gather an array of comment objects for a specific page/post 
		$comments = get_comments( array(
			'post_id' => $post_id,
			'status' => 'approve'
		) );

		// format the comments
		$out = wp_list_comments( array(
			'style'       => 'div',
			'short_ping'  => true,
			'avatar_size' => 150,
			'echo' => false,
			'callback' => 'icicle_comment',
		), $comments );

		// wrap the comments
		
		return $out;

	}
}





function icicle_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	?>
	<div <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
		
		<div class="comment-author vcard">
			<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php printf( __( '<cite class="fn">%s</cite> ' ), get_comment_author_link() ); ?>
		</div>
		
		<?php if ( $comment->comment_approved == '0' ) { ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
			<br />
		<?php } ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
			?>
		</div>

		<div class="comment-text">
		<?php comment_text(); ?>
		</div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>



<?php

}