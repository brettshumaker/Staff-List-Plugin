<?php

require_once( dirname (__FILE__).'/admin-functions.php' );
require_once( dirname (__FILE__).'/categories.php' );
require_once( dirname (__FILE__).'/templates.php' );
require_once( dirname (__FILE__).'/about.php' );



// Hook for adding admin menus
add_action('admin_menu', 'staff_listing_add_pages');

// action function for above hook
function staff_listing_add_pages() {
	$can_view_options = current_user_can('wr_staff_list_view_options');

    // Add a new top-level menu (ill-advised):
    add_menu_page('Staff Listing', 'Staff Listing', 'edit_pages', 'staff-listing', 'staff_listing_main_admin');

    // Categories Page
    if ($can_view_options)
    add_submenu_page('staff-listing', 'Categories', 'Categories', 'edit_pages', 'staff-listing-categories', 'staff_listing_categories');
    
    // Templates Page
    if ($can_view_options)
    add_submenu_page('staff-listing', 'Templates', 'Templates', 'edit_pages', 'staff-listing-templates', 'staff_listing_templates');
	
	// About Page
    if ($can_view_options)
    add_submenu_page('staff-listing', 'About', 'About', 'administrator', 'about-staff-listing', 'about_staff_listing');

	// Options Page
    //add_submenu_page('staff-listing', 'Options', 'Options', 'administrator', 'staff-listing-options', 'staff_listing_options');
        
     // Uninstall Page
    //add_submenu_page('staff-listing', 'Uninstall Staff Listing', 'Uninstall', 'administrator', 'uninstall-staff-listing', 'uninstall_staff_listing');

}



// Main Admin Page - this handles add, edit and delete
// These four functions can be found in admin-functions.php
function staff_listing_main_admin() {

	// setup the main admin page with a table
	if(!isset($_GET['action'])){
		staff_listing_main_admin_page();
	}
	
	
	
	
	
	// setup the add new page
	if(isset($_GET['action']) && $_GET['action'] == 'addStaffMember'){
		add_new_staff_member();
	}
	
	
	
	
	// setup the edit page
	if(isset($_GET['action']) && $_GET['action'] == 'edit'){
		edit_staff_member();
	}
	
	
	
	
	
	// setup the delete page
	if(isset($_GET['action']) && $_GET['action'] == 'delete'){
		delete_staff_member();
	}
}

?>
