<?php

/*
// Staff Member Bio Excerpt Function
//////////////////////////////*/

//// This function is used to create an "excerpt" for the _staff_member_bio
//// It takes 2 parameters: $text (string) and $excerpt_length (integer)

		function staff_bio_excerpt($text, $excerpt_length) {
			global $post;
			if (!$excerpt_length || !is_int($excerpt_length))$excerpt_length = 20;
			if ( '' != $text ) {
				$text = strip_shortcodes( $text );
				$text = apply_filters('the_content', $text);
				$text = str_replace(']]>', ']]>', $text);
				$excerpt_more = " ...";
				$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
			}
			return apply_filters('the_excerpt', $text);
		}




/*
// Staff Member Phone Number Format
//////////////////////////////*/

//// This function is used to consistently format all staff member phone numbers
//// It takes 1 parameter: $number (string)

function format_phone($number)
 {
    $number = preg_replace('/[^\d]/', '', $number); //Remove anything that is not a number
    if(strlen($number) < 10)
     {
    	return false;
     }
    return substr($number, 0, 3) . '.' . substr($number, 3, 3) . '.' . substr($number, 6);
 }
 




/*
// Get all Taxonomies for staff-member
//////////////////////////////*/
 
function get_sslp_terms($tax) {
    $terms = get_terms($tax);
    $names = array();
    
    // Loop through terms to get the names
    foreach($terms as $term) {
	    array_push($names, strtolower($term->name));
    }
    
    return $names;
}

/*
	Added this code from a plugin (http://aarontgrogg.com/2012/02/15/wordpress-plugin-add-custom-post-type-to-admin-body-class/)
	It does: Add additional `body` classes to reflect which Admin editorial page you are viewing and which Post type you are editing.
	It's Version: 1.1
	It's Author: Aaron T. Grogg
	It's License: GPLv2 or later
*/


//	Add deconstructed URI as <body> classes
	function add_to_admin_body_class($classes) {
		// get the global post variable
		global $post;
		// instantiate, should be overwritten
		$mode = '';
		// get the current page's URI (the part /after/ your domain name)
		$uri = $_SERVER["REQUEST_URI"];
		// get the post type from WP
		$post_type = get_post_type($post->ID);
		// set the $mode variable to reflect the editorial /list/ page...
		if (strstr($uri,'edit.php')) {
			$mode = 'edit-list-';
		}
		// or the actual editor page
		if (strstr($uri,'post.php')) {
			$mode = 'edit-page-';
		}
		// append our new mode/post_type class to any existing classes
		$classes .= $mode . $post_type;
		// and send them back to WP
		return $classes;
	}
	// add this filter to the admin_body_class hook
	add_filter('admin_body_class', 'add_to_admin_body_class');
 
?>