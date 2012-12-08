<?php
/*
P/lugin Name: Staff List
P/lugin URI: 
D/escription: Based on Staff Directory by Adam Tootle (http://www.89designs.net). Modified/Extended by Brett Shumaker.
V/ersion: 0.1
A/uthor: Brett Shumaker
A/uthor URI: http://www.brettshumaker.com
*/

//error_reporting(E_ALL);


global $wpdb;

$staff_listing_table = $wpdb->prefix . 'staff_listing';

define('STAFF_LISTING_TABLE', $wpdb->prefix . 'staff_listing');
define('STAFF_TEMPLATES', $wpdb->prefix . 'staff_listing_templates');
define('STAFF_PHOTOS_DIRECTORY', WP_CONTENT_DIR . "/uploads/staff-photos/");


require_once( dirname (__FILE__).'/install.php' );
require_once( dirname (__FILE__).'/admin/admin.php' );
require_once( dirname (__FILE__).'/functions.php' );



add_shortcode('staff-listing', 'wp_staff_listing_shortcode_funct');


function wp_staff_listing_shortcode_funct($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'cat' => '',
		'orderby' => '',
		'order' => ''
	), $atts));

	$output = '';
	
	// get all staff
	$param = "id=$id&cat=$cat&orderby=$orderby&order=$order";
	return staff_listing($param);
	
	
}

function staff_list_admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/staff-list.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'staff_list_admin_register_head');
?>
