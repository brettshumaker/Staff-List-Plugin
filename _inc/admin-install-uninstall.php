<?php 

/*
// Setup default options
//////////////////////////////*/


/**
 * Runs on plugin activation
 *
 * Sets up initial plugin option contents.
 * 
 *
 * @param    string    $default_template    			stores initial template for staff loop
 * @param	 string    $default_css         			stores initial css content
 * @param    array     $default_tags	   			 	stores accepted UNFORMATTED tags for staff loop
 * @param    array     $default_tag_string  			stores imploded, comma delimited $default_tags
 * @param    array     $default_formatted_tags	    	stores accepted FORMATTED tags for staff loop
 * @param    array     $default_formatted_tag_string  	stores imploded, comma delimited $default_formatted_tags
 * 
 */
function sslp_staff_member_activate(){
	
	$default_template = '
[staff_loop]
	<img src="[staff-photo-url]" alt="[staff-name] : [staff-title]">
	[staff-email-link]
	[staff-name-formatted]
	[staff-position-formatted]
	[staff-bio-formatted]
[/staff_loop]';
	
	$default_css = '
/*  div wrapped around entire staff list  */
div.staff-member-listing {

}

/*  div wrapped around each staff member  */
div.staff-member {

}

/*  "Even" staff member  */
div.staff-member.even {

}

/*  "Odd" staff member  */
div.staff-member.odd {

}

/*  [staff-bio-formatted]  */
div.staff-member-bio {

}

/*  p tags within [staff-bio-formatted]  */
div.staff-member-bio p {

}

/*  [staff-photo]  */
img.staff-member-photo {

}

/*  [staff-email-link]  */
.staff-member-email {

}

/*  [staff-name-formatted]  */
h3.staff-member-name {

}

/*  [staff-position-formatted]  */
h4.staff-member-position {

}';
	
	$default_tags = array('[staff-name]', '[staff-photo-url]', '[staff-position]', '[staff-email]', '[staff-phone]', '[staff-bio]');
	$default_tag_string = implode(", ", $default_tags);
	
	$default_formatted_tags = array('[staff-name-formatted]', '[staff-position-formatted]', '[staff-photo]', '[staff-email-link]', '[staff-bio-formatted]');
	$default_formatted_tag_string = implode(", ", $default_formatted_tags);
	
	if (!get_option('_staff_listing_default_tags')){
		update_option('_staff_listing_default_tags', $default_tags );
	}
	
	if (!get_option('_staff_listing_default_tag_string')){
		update_option('_staff_listing_default_tag_string', $default_tag_string);
	}
	
	if (!get_option('_staff_listing_default_formatted_tags')){
		update_option('_staff_listing_default_formatted_tags', $default_formatted_tags );
	}
	
	if (!get_option('_staff_listing_default_formatted_tag_string')){
		update_option('_staff_listing_default_formatted_tag_string', $default_formatted_tag_string);
	}
	
	if (!get_option('_staff_listing_default_html')){
		update_option('_staff_listing_default_html', $default_template);
		update_option('_staff_listing_custom_html', $default_template);
	}
	
	if (!get_option('_staff_listing_default_css')){
		update_option('_staff_listing_default_css', $default_css);
		update_option('_staff_listing_custom_css', $default_css);
	}
	
	flush_rewrite_rules();
}


/**
 * Runs on plugin deactivation
 * 
 * Nothing but flushing rewrite rules right now
 *
 */
function sslp_staff_member_deactivate(){
	flush_rewrite_rules();
}

/**
 * Runs on plugin UNINSTALL
 * 
 * Remove our options from the database.
 *
 */
function sslp_staff_member_uninstall(){
	delete_option('_staff_listing_default_tags');
	delete_option('_staff_listing_default_tag_string');
	delete_option('_staff_listing_default_formatted_tags');
	delete_option('_staff_listing_default_formatted_tag_string');
	delete_option('_staff_listing_default_html');
	delete_option('_staff_listing_default_css');
	delete_option('_staff_listing_custom_html');
	delete_option('_staff_listing_custom_css');

}

?>