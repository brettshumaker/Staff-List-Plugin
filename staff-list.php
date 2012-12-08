<?php
/*
Plugin Name: Staff List
Plugin URI: 
Description: A simple staff listing plugin using WordPress Custom Post Types.
Version: 0.1
Author: Brett Shumaker
Author URI: http://www.brettshumaker.com
*/





/*
// Include some files and setup our plugin dir url
//////////////////////////////*/

define( 'STAFFLIST_PATH', plugin_dir_url(__FILE__) );
include_once('_inc/admin-views.php');
include_once('_inc/admin-save-data.php');
include_once('_inc/admin-utilities.php');
include_once('_inc/user-view-show-staff-list.php');





/*
// Setup default options
//////////////////////////////*/

$default_template = '
[staff_loop]		
	<img src="[photo_url]" alt="[name] : [title]">
	[name]
	[title]
	[email_link]
	[bio_paragraph]					
[/staff_loop]';

$default_css = '/* Enter your valid CSS here */';

update_option('staff_listing_default_html', $default_template);
update_option('staff_listing_default_css', $default_css);
if (get_option('staff_listing_custom_html')=='')
	update_option('staff_listing_custom_html', $default_template);
if (get_option('staff_listing_custom_css')=='')
	update_option('staff_listing_custom_css', $default_css);




/*
// Enqueue Plugin Scripts and Styles
//////////////////////////////*/

add_action( 'admin_enqueue_scripts', 'staff_member_admin_enqueue_scripts' );

function staff_member_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'staff-member-admin-scripts', STAFFLIST_PATH . '_js/staff-member-admin-scripts.js' );
	
	wp_enqueue_style ( 'staff-list-css', STAFFLIST_PATH . '_css/staff-list.css' );
}





/*
// Setup Our Staff Member CPT
//////////////////////////////*/

add_action( 'init', 'staff_member_init' );

function staff_member_init() {
    $labels = array(
        'name' => _x('Staff Member', 'post type general name'),
        'singular_name' => _x('Staff Members', 'post type singular name'),
        'add_new' => _x('Add New', 'staff member'),
        'add_new_item' => __('Add New Staff Member'),
        'edit_item' => __('Edit Staff Member'),
        'new_item' => __('New Staff Member'),
        'view_item' => __('View Staff Member'),
        'search_items' => __('Search Staff Members'),
        'not_found' =>  __('No staff members found'),
        'not_found_in_trash' => __('No staff members found in Trash'),
        'parent_item_colon' => '',
        'all_items' => 'All Staff Members',
        'menu_name' => 'Staff Members'
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
        //'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' )
        'supports' => array( 'title', 'thumbnail', 'excerpt' )
    );

    register_post_type( 'staff-member', $args );
}





/*
// Change Title Text
//////////////////////////////*/

add_filter( 'enter_title_here', 'staff_member_change_title' );

function staff_member_change_title( $title ){
    $screen = get_current_screen();
    if ( 'staff-member' == $screen->post_type ) {
        $title = 'Staff Name';
    }
    return $title;
}





/*
// Add MetaBoxes
//////////////////////////////*/

add_action('do_meta_boxes', 'staff_member_add_meta_boxes');
function staff_member_add_meta_boxes()
{
	// All meta_box functions located in _inc/admin-views.php
    
    add_meta_box('staff-member-info', __('Staff Member Info'), 'staff_member_info_meta_box', 'staff-member', 'normal', 'high');
    
    add_meta_box('staff-member-bio', __('Staff Member Bio'), 'staff_member_bio_meta_box', 'staff-member', 'normal', 'high');
}

/**
 * Change Featured Image text
 */
add_action('do_meta_boxes', 'staff_member_featured_image_text');

function staff_member_featured_image_text() {
    remove_meta_box( 'postimagediv', 'staff-member', 'side' );
    add_meta_box('postimagediv', __('Staff Photo'), 'post_thumbnail_meta_box', 'staff-member', 'normal', 'high');
}






/*
// Create Custom Columns
//////////////////////////////*/

add_filter( "manage_staff-member_posts_columns", "staff_member_custom_columns" );

function staff_member_custom_columns( $cols ) {
  $cols = array(
    'cb'				  =>     '<input type="checkbox" />',
    'title'				  => __( 'Name' ),
    'photo'				  => __( 'Photo' ),
    '_staff_member_title' => __( 'Title' ),
    '_staff_member_email' => __( 'Email' ),
    '_staff_member_phone' => __( 'Phone' ),
    '_staff_member_bio'   => __( 'Bio' ),
  );
  return $cols;
}





/*
// Add SubPage for Ordering function
//////////////////////////////*/

add_action( 'admin_menu', 'staff_member_register_menu' );

function staff_member_register_menu() {
	add_submenu_page(
		'edit.php?post_type=staff-member',
		'Order Staff Members',
		'Order',
		'edit_pages', 'staff-member-order',
		'staff_member_order_page'
	);
	
	add_submenu_page(
		'edit.php?post_type=staff-member',
		'Display Templates',
		'Templates',
		'edit_pages', 'staff-member-template',
		'staff_member_display_template'
	);
}

?>