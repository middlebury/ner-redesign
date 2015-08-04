<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'agency', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'agency' ) );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'agency_customizer' );
function agency_customizer(){

    require_once( get_stylesheet_directory() . '/lib/customize.php' );

}

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Agency Pro Theme', 'agency' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/agency/' );
define( 'CHILD_THEME_VERSION', '3.1.2' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'agency_load_scripts' );
function agency_load_scripts() {

    wp_enqueue_script( 'agency-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
    wp_enqueue_script( 'main', get_bloginfo( 'stylesheet_directory' ) . '/js/main.js', array( 'jquery' ), '1.0.0', 1 );

    wp_enqueue_style( 'dashicons' );

    // wp_deregister_script( 'jquery-migrate' );
    wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=EB+Garamond|Roboto:300,400,700', array(), CHILD_THEME_VERSION );

}

//* Enqueue Backstretch script and prepare images for loading
add_action( 'wp_enqueue_scripts', 'agency_enqueue_backstretch_scripts' );
function agency_enqueue_backstretch_scripts() {

    $image = get_option( 'agency-backstretch-image', sprintf( '%s/images/bg.jpg', get_stylesheet_directory_uri() ) );

    //* Load scripts only if custom backstretch image is being used
    if ( ! empty( $image ) ) {

        wp_enqueue_script( 'agency-pro-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/js/backstretch.js', array( 'jquery' ), '1.0.0' );
        wp_enqueue_script( 'agency-pro-backstretch-set', get_bloginfo( 'stylesheet_directory' ).'/js/backstretch-set.js' , array( 'jquery', 'agency-pro-backstretch' ), '1.0.0' );

        wp_localize_script( 'agency-pro-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', $image ) ) );

    }

}

//* Add new image sizes
add_image_size( 'home-bottom', 380, 150, TRUE );
add_image_size( 'home-middle', 380, 380, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
    'header_image'    => '',
    'header-selector' => '.site-title a',
    'header-text'     => false,
    'height'          => 60,
    'width'           => 300,
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
    'agency-pro-blue'   => __( 'Agency Pro Blue', 'agency' ),
    'agency-pro-green'  => __( 'Agency Pro Green', 'agency' ),
    'agency-pro-orange' => __( 'Agency Pro Orange', 'agency' ),
    'agency-pro-red'    => __( 'Agency Pro Red', 'agency' ),
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Reposition the header
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
add_action( 'genesis_before', 'genesis_header_markup_open', 5 );
add_action( 'genesis_before', 'genesis_do_header', 10 );
add_action( 'genesis_before', 'genesis_header_markup_close', 15 );

//* Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'agency_secondary_menu_args' );
function agency_secondary_menu_args( $args ){

    if( 'secondary' != $args['theme_location'] )
    return $args;

    $args['depth'] = 1;
    return $args;

}

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'agency_remove_comment_form_allowed_tags' );
function agency_remove_comment_form_allowed_tags( $defaults ) {

    $defaults['comment_notes_after'] = '';
    return $defaults;

}

//* Register widget areas
genesis_register_sidebar( array(
    'id'          => 'home-top',
    'name'        => __( 'Home Top', 'agency' ),
    'description' => __( 'This is the top section of the homepage.', 'agency' ),
) );
genesis_register_sidebar( array(
    'id'          => 'home-middle',
    'name'        => __( 'Home Middle', 'agency' ),
    'description' => __( 'This is the middle section of the homepage.', 'agency' ),
) );
genesis_register_sidebar( array(
    'id'          => 'home-bottom',
    'name'        => __( 'Home Bottom', 'agency' ),
    'description' => __( 'This is the bottom section of the homepage.', 'agency' ),
) );

// Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'bg_entry_meta_header' );
function bg_entry_meta_header($post_info) {

    // add date only if on category page
    $post_info = is_single() ? '' : '[post_date]';

    return $post_info;
}

/* Force full width layout on all archive pages*/
add_filter( 'genesis_pre_get_option_site_layout', 'full_width_layout_archives' );

function full_width_layout_archives( $opt ) {
    if ( is_archive() || is_page() ) {
        $opt = 'content-sidebar';
        return $opt;
    }
}

//* Modify breadcrumb arguments.
add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
function sp_breadcrumb_args( $args ) {
    $args['home'] = 'Home';
    $args['sep'] = ' &rsaquo; ';
    $args['list_sep'] = ', '; // Genesis 1.5 and later
    $args['prefix'] = '<div class="breadcrumb">';
    $args['suffix'] = '</div>';
    $args['heirarchial_attachments'] = true; // Genesis 1.5 and later
    $args['heirarchial_categories'] = true; // Genesis 1.5 and later
    $args['display'] = true;
    $args['labels']['prefix'] = '';
    $args['labels']['author'] = '';
    $args['labels']['category'] = ''; // Genesis 1.6 and later
    $args['labels']['tag'] = '';
    $args['labels']['date'] = '';
    $args['labels']['search'] = 'Search for ';
    $args['labels']['tax'] = '';
    $args['labels']['post_type'] = '';
    $args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later

    return $args;
}

// remove subtitle output after post title
remove_filter( 'genesis_entry_header', 'ahjira_subtitle_after_title', 11 );
// moves subtitle before post title output
add_filter( 'genesis_entry_header', 'ahjira_subtitle_after_title', 9 );

//* Modify the genesis read more link
add_filter( 'get_the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
    return '&hellip;<br><a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

//* Reposition the breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs' );


//* Add category link to single posts
add_action('genesis_after_entry', 'ner_after_entry');
function ner_after_entry() {

    $output = '';

    $category = get_the_category();
    $category_id = $category[0]->term_id;
    $category_name = $category[0]->name;


    // TODO: replace the image urls with appropriate background patterns
    switch (strtolower($category_name)) {
        case 'poetry':
            $bg_url = '//placehold.it/100x100/c0ffee';
            break;

        case 'audio':
            $bg_url = '//placehold.it/100x100/bada55';
            break;

        case 'uncategorized':
            $bg_url = '//placehold.it/100x100/FA7A55';
            break;
        
        default:
            $bg_url = '';
            break;
    }

    if ( is_single() ) {
        $output .= '<a href="' . get_category_link($category_id) . '" class="entry-category-bookmark" style="background-image: url(\'' . $bg_url . '\');">';
        $output .= '<span>Read more</span>';
        $output .= '<span class="entry-category-bookmark-name">' . $category_name . '</span>';
        $output .= '</a>';
        $output .= '</div>';

        echo $output;
    }
}

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_filter' );
function sp_footer_creds_filter( $creds ) {
    $creds = 'Copyright [footer_copyright] &middot; <a href="https://twitter.com/nerweb" target="_blank">Follow us on Twitter</a> &middot; <a href="https://www.facebook.com/pages/New-England-Review/71406219081" target="_blank">Like us on Facebook</a>';
    return $creds;
}

// add notification bar widget
genesis_register_sidebar( array(
    'id'          => 'notification-bar',
    'name'        => __( 'Notification Bar', 'agency' ),
    'description' => __( 'This is the global bar going across the top of the site.', 'agency' ),
) );

remove_action( 'genesis_before', 'genesis_header_markup_open', 5);
add_action( 'genesis_before', 'ner_header_markup_open', 5 );

// Custom build of genesis_header_markup_open
// only custom part is the widget area
function ner_header_markup_open() {

    genesis_markup( array(
        'html5'   => '<header %s>',
        'xhtml'   => '<div id="header">',
        'context' => 'site-header',
    ) );

    $notifBarClasses = array('notification-bar');

    if ( $_COOKIE['ner_notif_bar_shown'] == 'true' ) {
        $notifBarClasses[] = 'notification-bar-closed';
    }

    // custom insertion of widget area before <wrap>
    genesis_widget_area( 'notification-bar', array(
        'before' => '<div id="notification-bar" class="' . join(' ', $notifBarClasses) . '"><button class="notification-bar-close-btn">x</button><div class="wrap">',
        'after'  => '</div></div>',
    ) );

    genesis_structural_wrap( 'header' );

}
