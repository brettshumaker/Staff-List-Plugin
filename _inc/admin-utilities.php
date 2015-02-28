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
?>