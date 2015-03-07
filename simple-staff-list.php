<?php
/*
Plugin Name: Simple Staff List
Plugin URI: 
Description: A simple plugin to build and display a staff listing for your website.
Version: 1.16
Author: Brett Shumaker
Author URI: http://www.brettshumaker.com
Text Domain: simple-staff-list
Domain Path: /_lang
*/





/*
// Include some files and setup our plugin dir url
//////////////////////////////*/

define( 'STAFFLIST_PATH', plugin_dir_url(__FILE__) );
include_once('_inc/admin-install-uninstall.php');
include_once('_inc/admin-views.php');
include_once('_inc/admin-save-data.php');
include_once('_inc/admin-utilities.php');
include_once('_inc/user-view-show-staff-list.php');





/*
// Add post-thumbnails support for our custom post type
//////////////////////////////*/

add_theme_support( 'post-thumbnails', array( 'staff-member' ));





/*
// Register Activation/Deactivation Hooks
//////////////////////////////*/

// function location: /_inc/admin-install-uninstall.php

register_activation_hook( __FILE__, 'sslp_staff_member_activate' );
register_deactivation_hook( __FILE__, 'sslp_staff_member_deactivate' );
register_uninstall_hook( __FILE__, 'sslp_staff_member_uninstall' );

// Need to check plugin version here and run sslp_staff_member_plugin_update()
// function location: /_inc/admin-install-uninstall.php
if ( ! function_exists( 'get_plugins' ) )
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
$plugin_file = basename( ( __FILE__ ) );
$plugin_version = $plugin_folder[$plugin_file]['Version'];    
$sslp_ver_option = get_option('_simple_staff_list_version');
if ($sslp_ver_option == "" || $sslp_ver_option <= $plugin_version){
	sslp_staff_member_plugin_update($sslp_ver_option, $plugin_version);
}

// End plugin version check





/*
// Enqueue Plugin Scripts and Styles
//////////////////////////////*/

/*
 *  Admin js action added on line 270 of this file (simple-staff-list.php)
 */

function sslp_staff_member_admin_print_scripts() {
	//** Admin Scripts
	wp_register_script( 'staff-member-admin-scripts', STAFFLIST_PATH . '_js/staff-member-admin-scripts.js', array('jquery', 'jquery-ui-sortable' ), '1.0', false );
	wp_enqueue_script( 'staff-member-admin-scripts' );
}

add_action( 'admin_enqueue_scripts', 'sslp_staff_member_admin_enqueue_styles' );

function sslp_staff_member_admin_enqueue_styles() {
	//** Admin Styles
	wp_register_style( 'staff-list-css', STAFFLIST_PATH . '_css/admin-staff-list.css' );
	wp_enqueue_style ( 'staff-list-css' );
}

add_action( 'wp_enqueue_scripts', 'sslp_staff_member_enqueue_styles');

function sslp_staff_member_enqueue_styles(){
	//** Front-end Style
	if (get_option('_staff_listing_write_external_css') == "yes") {
		wp_register_style( 'staff-list-custom-css', get_stylesheet_directory_uri() . '/simple-staff-list-custom.css' );
		wp_enqueue_style ( 'staff-list-custom-css' );
	}
}



/*
// Load the plugin text domain for translation.
//////////////////////////////*/

add_action( 'init', 'load_sslp_textdomain' );

function load_sslp_textdomain() {

	$domain = 'simple-staff-list';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/_lang/' );
}



/*
// Setup Our Staff Member CPT
//////////////////////////////*/

add_action( 'init', 'sslp_staff_member_init' );

function sslp_staff_member_init() {

	if (!get_option('_staff_listing_custom_slug')){
		$sslp_slug = get_option('_staff_listing_default_slug');
	} else {
		$sslp_slug = get_option('_staff_listing_custom_slug');
	}
	if (!get_option('_staff_listing_custom_name_singular')){
		$sslp_singular = get_option('_staff_listing_default_name_singular');
	} else {
		$sslp_singular = get_option('_staff_listing_custom_name_singular');
	}
	if (!get_option('_staff_listing_custom_name_plural')){
		$sslp_name = get_option('_staff_listing_default_name_plural');
	} else {
		$sslp_name = get_option('_staff_listing_custom_name_plural');
	}

	$labels = array(
		'name'                => $sslp_name,
		'singular_name'       => $sslp_singular,
		'add_new'             => _x( 'Add New', 'staff member', 'simple-staff-list' ),
		'add_new_item'        => __( 'Add New Staff Member', 'simple-staff-list' ),
		'edit_item'           => __( 'Edit Staff Member', 'simple-staff-list' ),
		'new_item'            => __( 'New Staff Member', 'simple-staff-list' ),
		'view_item'           => __( 'View Staff Member', 'simple-staff-list' ),
		'search_items'        => __( 'Search Staff Members', 'simple-staff-list' ),
		'exclude_from_search' => true,
		'not_found'           => __( 'No staff members found', 'simple-staff-list' ),
		'not_found_in_trash'  => __( 'No staff members found in Trash', 'simple-staff-list' ),
		'parent_item_colon'   => '',
		'all_items'           => __( 'All Staff Members', 'simple-staff-list' ),
		'menu_name'           => __( 'Staff Members', 'simple-staff-list' )
);

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'page',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 100,
        'rewrite' => array('slug'=>$sslp_slug,'with_front'=>false),
        'supports' => array( 'title', 'thumbnail', 'excerpt' )
    );
    
    global $wp_version;
    
    if (version_compare($wp_version, '3.8', '>=')) {
	    $args['menu_icon'] = 'dashicons-groups';
    }

    register_post_type( 'staff-member', $args );
}





/*
// Setup Our Staff Group Taxonomy
//////////////////////////////*/

add_action( 'init', 'sslp_custom_tax' );

function sslp_custom_tax() {

	$labels = array(
		'name'              => _x( 'Groups', 'taxonomy general name', 'simple-staff-list' ),
		'singular_name'     => _x( 'Group', 'taxonomy singular name', 'simple-staff-list' ),
		'search_items'      => __( 'Search Groups', 'simple-staff-list' ),
		'all_items'         => __( 'All Groups', 'simple-staff-list' ),
		'parent_item'       => __( 'Parent Group', 'simple-staff-list' ),
		'parent_item_colon' => __( 'Parent Group:', 'simple-staff-list' ),
		'edit_item'         => __( 'Edit Group', 'simple-staff-list' ), 
		'update_item'       => __( 'Update Group', 'simple-staff-list' ),
		'add_new_item'      => __( 'Add New Group', 'simple-staff-list' ),
		'new_item_name'     => __( 'New Group Name', 'simple-staff-list' ),
	);

	register_taxonomy( 'staff-member-group', array( 'staff-member' ), array(
		'hierarchical' => true,
		'labels' => $labels, /* NOTICE: Here is where the $labels variable is used */
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'group' ),
	));
}





/*
// Hide Excerpt Box by default
//////////////////////////////*/

// Change what's hidden by default
add_filter('default_hidden_meta_boxes', 'hide_meta_lock', 10, 2);
function hide_meta_lock($hidden, $screen) {
        if ( $screen->base == 'staff-member' )
                $hidden = array( 'postexcerpt' );
        return $hidden;
}





/*
// Change Title Text
//////////////////////////////*/

/**
 * Change "Enter Title Here" text
 * 
 * Changes title text on post edit screen for staff-member CPT
 *
 * @param    string    $screen    	get_current_screen()
 * @return   string               	returns new placeholder text for "Enter title here" input
 */
 
add_filter( 'enter_title_here', 'sslp_staff_member_change_title' );
function sslp_staff_member_change_title( $title ){
    $screen = get_current_screen();
    if ( $screen->post_type == 'staff-member' ) {
        $title = __( 'Staff Name', 'simple-staff-list' );
    }
    return $title;
}





/*
// Add MetaBoxes
//////////////////////////////*/

/**
 * Change Featured Image title
 *
 * Removes the default featured image box and adds a new one with a new title
 * 
 */
 
add_action('do_meta_boxes', 'sslp_staff_member_featured_image_text');
function sslp_staff_member_featured_image_text() {

    remove_meta_box( 'postimagediv', 'staff-member', 'side' );
    if (current_theme_supports('post-thumbnails')) {
	    add_meta_box('postimagediv', __('Staff Photo', 'simple-staff-list'), 'post_thumbnail_meta_box', 'staff-member', 'normal', 'high');
	} else {
		add_meta_box('staff-member-warning', __('Staff Photo', 'simple-staff-list'), 'sslp_staff_member_warning_meta_box', 'staff-member', 'normal', 'high');
	}
}


/**
 * Adds MetaBoxes for staff-member
 * 
 * All metabox callback functions are located in _inc/admin-views.php
 *
 */

add_action('do_meta_boxes', 'sslp_staff_member_add_meta_boxes');
function sslp_staff_member_add_meta_boxes() {

    add_meta_box('staff-member-info', __('Staff Member Info', 'simple-staff-list'), 'sslp_staff_member_info_meta_box', 'staff-member', 'normal', 'high');
    
    add_meta_box('staff-member-bio', __('Staff Member Bio', 'simple-staff-list'), 'sslp_staff_member_bio_meta_box', 'staff-member', 'normal', 'high');
}





/*
// Create Custom Columns
//////////////////////////////*/


/**
 * Adds custom columns for staff-member CPT admin display
 *
 * @param    array    $cols    New column titles
 * @return   array             Column titles
 */
 
add_filter( "manage_staff-member_posts_columns", "sslp_staff_member_custom_columns" );
function sslp_staff_member_custom_columns( $cols ) {
	$cols = array(
		'cb'                  =>     '<input type="checkbox" />',
		'title'               => __( 'Name', 'simple-staff-list' ),
		'photo'               => __( 'Photo', 'simple-staff-list' ),
		'_staff_member_title' => __( 'Position', 'simple-staff-list' ),
		'_staff_member_email' => __( 'Email', 'simple-staff-list' ),
		'_staff_member_phone' => __( 'Phone', 'simple-staff-list' ),
		'_staff_member_bio'   => __( 'Bio', 'simple-staff-list' ),
	);
	return $cols;
}





/*
// Add SubPage for Ordering function
//////////////////////////////*/

/**
 * Registers sub pages for staff-member CPT
 * 
 * Adds "Order", "Templates", and "Options" pages to WP nav. 
 * ALSO adds the print scripts action to load our js only on the pages we need it.
 *
 * @param    function    $order_page	    sets up the Order page
 * @param    function    $templates_page    sets up the Templates page
 * @param    function    $options_page    sets up the Options page
 * 
 */
 
add_action( 'admin_menu', 'sslp_staff_member_register_menu' );
function sslp_staff_member_register_menu() {
	$order_page 	= add_submenu_page(
						'edit.php?post_type=staff-member',
						__( 'Order Staff Members', 'simple-staff-list' ),
						__( 'Order', 'simple-staff-list' ),
						'edit_pages', 'staff-member-order',
						'sslp_staff_member_order_page'
					);
	
	$templates_page = add_submenu_page(
						'edit.php?post_type=staff-member',
						__( 'Display Templates', 'simple-staff-list' ),
						__( 'Templates', 'simple-staff-list' ),
						'edit_pages', 'staff-member-template',
						'sslp_staff_member_template_page'
					);
	
	$usage_page 	= add_submenu_page(
						'edit.php?post_type=staff-member',
						__( 'Simple Staff List Usage', 'simple-staff-list' ),
						__( 'Usage', 'simple-staff-list' ),
						'edit_pages', 'staff-member-usage',
						'sslp_staff_member_usage_page'
					);

	$options_page 	= add_submenu_page(
						'edit.php?post_type=staff-member',
						__( 'Simple Staff List Options', 'simple-staff-list' ),
						__( 'Options', 'simple-staff-list' ),
						'edit_pages', 'staff-member-options',
						'sslp_staff_member_options_page'
					);
	
	add_action( 'admin_print_scripts-'.$order_page, 'sslp_staff_member_admin_print_scripts' );
	add_action( 'admin_print_scripts-'.$templates_page, 'sslp_staff_member_admin_print_scripts' );
	add_action( 'admin_print_scripts-'.$options_page, 'sslp_staff_member_admin_print_scripts' );
}





/*
// Make Sure We Add The Custom CSS File on Theme Switch
//////////////////////////////*/

function sslp_staff_member_create_css_on_switch_theme($new_theme) {
    $filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';
    $custom_css = get_option('_staff_listing_custom_css');
    file_put_contents($filename, $custom_css);
}
if ( get_option('_staff_listing_write_external_css') == 'yes' ){
	add_action('switch_theme', 'sslp_staff_member_create_css_on_switch_theme');
}




/*
// Template Tags
//////////////////////////////*/
/**
 * function which is used as the backend for all other template tags
 * @param integer  $staff_id the ID of th staff member.
 * @param $meta_field the name of the meta field
 * @return string | boolean the value of meta field. If nothing could be found false will be returned 
 */
function _staff_member_meta( $staff_id = null, $meta_field) {
    global $post;
    if( is_null( $staff_id)) $staff_id = $post->ID;
    if( $meta_field == '_staff_member_image') {
	    return wp_get_attachment_url( get_post_thumbnail_id( $staff_id) );
    } else {
		return get_post_meta( $staff_id, $meta_field, true);
    }
}
/**
 * returns or echo the postion of a staff member,
 * @param integer $staff_id the ID of a staff member
 * @param boolean $echo if true, result will be echoed
 * @return string | boolean the position of a staff member or false
 */
function staff_member_position( $staff_id = null, $echo = false) {
    $position = _staff_member_meta( $staff_id, '_staff_member_title');
    if( $echo ) echo $position;
    return $postion;
} 
/**
 * shortcut to echo the positon of a staff member,
 * @param integer $staff_id the ID of a staff member
 * @return void
 */
function the_staff_member_position( $staff_id = null) {
    staff_member_position( $staff_id, true);
}
/**
 * returns or echo the bio of a staff member
 * @param integer $staff_id the ID of a staff member
 * @param boolean $echo if true, result will be echoed
 * @return string | boolean the bio of a staff member or false
 */
function staff_member_bio( $staff_id = null, $echo = false) {
   $bio = _staff_member_meta( $staff_id, '_staff_member_bio', true);
   if( $echo ) echo $bio;
   return $bio;
}
/**
 * shortcut to echo the bio of a staff member
 * @param integer $staff_id the ID of a staff member
 * @return void
 */
function the_staff_member_bio( $staff_id = null) {
    staff_member_bio( $staff_id, true);
}
/**
 * return or echo the email of a staff member
 * @param integer $staff_id the ID of a staff member
 * @param boolean $echo if true, result will be echoed
 * @return string | boolean the email of a staff member of false
 */
function staff_member_email( $staff_id = null, $echo = false) {
    $email = _staff_member_meta( $staff_id, '_staff_member_email');
    
    if( $echo ) echo antispambot($email);
    return antispambot($email); 
}
/**
 * shortcut to echo the email of a staff member
 * @param integer $staff_id the ID of a staff member
 * @return void
 */
function the_staff_member_email( $staff_id = null) {
    staff_member_email( $staff_id, true);
}
/**
 * returns or echo the facebook url of a staff member
 * @param integer $staff_id the ID of a staff member
 * @param boolean $echo if true, result will be echoed
 * @return string | boolean the facebook url of a staff member or false 
 */
function staff_member_facebook( $staff_id = null, $echo = false) {
    $fb = _staff_member_meta( $staff_id, '_staff_member_fb');
    if( $echo ) echo $fb;
    return $fb;
} 
/**
 * shortcut to ecoh the facebook url of a staff member
 * @param integer $staff_id the ID of a staff member
 * @return void
 */
function the_staff_member_facebook( $staff_id = null) {
    staff_member_facebook( $staff_id, true);
}
/**
 * returns or echo the twitter url of a staff member
 * @param integer $staff_id the ID of a staff member
 * @param boolean $echo if true, result will be echoed
 * @return string | boolean the twitter url of a staff member or false
 */
function staff_member_twitter( $staff_id = null, $echo = false) {
    $twitter = _staff_member_meta( $staff_id, '_staff_member_tw');
    if( $echo) echo $twitter;
    return $twitter;
}
/**
 * shortcut to echo the twitter url of a staff member
 * @param integer $staff_id the ID of a staff member
 * @return void
 */
function the_staff_member_twitter( $staff_id = null) {
    staff_member_twitter( $staff_id, true);
}
/**
 * returns or echo the featured image url of a staff member
 * @param integer $staff_id the ID of a staff member
 * @param boolean $echo if true, result will be echoed
 * @return string | boolean the image url of a staff member or false
 */
function staff_member_image_url( $staff_id = null, $echo = false) {
	$photo_url = _staff_member_meta( $staff_id, '_staff_member_image');
	if ( $echo) echo $photo_url;
	return $photo_url;
}
/**
 * shortcut to echo the image url of a staff member
 * @param integer $staff_id the ID of a staff member
 * @return void
 */
function the_staff_member_image_url( $staff_id = null) {
	staff_member_image_url( $staff_id, true);
}
?>