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
function sslp_staff_member_activate($is_forced){
	
	$default_template = '
[staff_loop]
	<img class="staff-member-photo" src="[staff-photo-url]" alt="[staff-name] : [staff-position]">
	<div class="staff-member-info-wrap">
		[staff-name-formatted]
		[staff-position-formatted]
		[staff-bio-formatted]
		[staff-email-link]
	</div>
[/staff_loop]';
	
	$default_css = '
/*  div wrapped around entire staff list  */
div.staff-member-listing {

}

/*  div wrapped around each staff member  */
div.staff-member {
	padding-bottom: 2em;
	border-bottom: thin dotted #aaa;
}

/*  "Even" staff member  */
div.staff-member.even {

}

/*  "Odd" staff member  */
div.staff-member.odd {
	margin-top: 2em;
}

/*  Last staff member  */
div.staff-member.last {
	padding-bottom: 0;
	border: none;
}

/*  Wrap around staff info  */
.staff-member-info-wrap {
	float: left;
	width: 70%;
	margin-left: 3%;
}

/*  [staff-bio-formatted]  */
div.staff-member-bio {

}

/*  p tags within [staff-bio-formatted]  */
div.staff-member-bio p {

}

/*  [staff-photo]  */
img.staff-member-photo {
	float: left;
}

/*  [staff-email-link]  */
.staff-member-email {

}

/*  [staff-name-formatted]  */
div.staff-member-listing h3.staff-member-name {
	margin: 0;
}

/*  [staff-position-formatted]  */
div.staff-member-listing h4.staff-member-position {
	margin: 0;
	font-style: italic;
}

/* Clearfix for div.staff-member */
div.staff-member:after {
	content: "";
	display: block;
	clear: both;
}

/* Clearfix for <= IE7 */
* html div.staff-member { height: 1%; }
div.staff-member { display: block; }
';
	
	$default_tags = array('[staff-name]', '[staff-name-slug]', '[staff-photo-url]', '[staff-position]', '[staff-email]', '[staff-phone]', '[staff-bio]', '[staff-facebook]', '[staff-twitter]');
	$default_tag_string = implode(", ", $default_tags);
	
	$default_formatted_tags = array('[staff-name-formatted]', '[staff-position-formatted]', '[staff-photo]', '[staff-email-link]', '[staff-bio-formatted]');
	$default_formatted_tag_string = implode(", ", $default_formatted_tags);
	
	update_option('_staff_listing_default_tags', $default_tags );
	update_option('_staff_listing_default_tag_string', $default_tag_string);
	update_option('_staff_listing_default_formatted_tags', $default_formatted_tags );
	update_option('_staff_listing_default_formatted_tag_string', $default_formatted_tag_string);
	update_option('_staff_listing_default_html', $default_template);
	update_option('_staff_listing_default_css', $default_css);
	
	
	if (!get_option('_staff_listing_custom_html')){
		update_option('_staff_listing_custom_html', $default_template);
	}
	
	$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';
	
	if (!get_option('_staff_listing_custom_css') && !file_exists($filename)){
		update_option('_staff_listing_custom_css', get_option('_staff_listing_default_css'));
		
		// Save custom css to a file in current theme directory
		file_put_contents($filename, get_option('_staff_listing_default_css'));
	} else if (file_exists($filename)) {
		$custom_css = file_get_contents($filename);
		update_option('_staff_listing_custom_css', $custom_css);
	}
	
	if ($is_forced != 'forced activation'){
		flush_rewrite_rules();
	}
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
	delete_option('_simple_staff_list_version');

}

/**
 * Runs on plugin update
 * 
 * Initially created to fix option naming inconsistency in v1.01
 *
 */
function sslp_staff_member_plugin_update($sslp_ver_option, $plugin_version){

	if ($sslp_ver_option == "" || $plugin_version == "1.01") {
	
		$bad_cus_html = get_option('staff_listing_custom_html');
		$good_cus_html = get_option('_staff_listing_custom_html');
		$bad_cus_css = get_option('staff_listing_custom_css');
		$good_cus_css = get_option('_staff_listing_custom_css');
		
		if ($bad_cus_html != $good_cus_html)
			update_option('_staff_listing_custom_html', get_option('staff_listing_custom_html'));
		
		if ($bad_cus_css != $good_cus_css)
			update_option('_staff_listing_custom_css', get_option('staff_listing_custom_css'));
		
		delete_option('staff_listing_custom_html');
		delete_option('staff_listing_custom_css');
	}
	
	// Updating the default CSS and Template
	
	if ($sslp_ver_option == "" || $plugin_version <= "1.10") {
	
		$default_css = '
/*  div wrapped around entire staff list  */
div.staff-member-listing {

}

/*  div wrapped around each staff member  */
div.staff-member {
	padding-bottom: 2em;
	border-bottom: thin dotted #aaa;
}

/*  "Even" staff member  */
div.staff-member.even {

}

/*  "Odd" staff member  */
div.staff-member.odd {
	margin-top: 2em;
}

/*  Last staff member  */
div.staff-member.last {
	padding-bottom: 0;
	border: none;
}

/*  Wrap around staff info  */
.staff-member-info-wrap {
	float: left;
	width: 70%;
	margin-left: 3%;
}

/*  [staff-bio-formatted]  */
div.staff-member-bio {

}

/*  p tags within [staff-bio-formatted]  */
div.staff-member-bio p {

}

/*  [staff-photo]  */
img.staff-member-photo {
	float: left;
}

/*  [staff-email-link]  */
.staff-member-email {

}

/*  [staff-name-formatted]  */
div.staff-member-listing h3.staff-member-name {
	margin: 0;
}

/*  [staff-position-formatted]  */
div.staff-member-listing h4.staff-member-position {
	margin: 0;
	font-style: italic;
}

/* Clearfix for div.staff-member */
div.staff-member:after {
	content: "";
	display: block;
	clear: both;
}

/* Clearfix for <= IE7 */
* html div.staff-member { height: 1%; }
div.staff-member { display: block; }
';

		$default_template = '
[staff_loop]
	<img class="staff-member-photo" src="[staff-photo-url]" alt="[staff-name] : [staff-position]">
	<div class="staff-member-info-wrap">
		[staff-name-formatted]
		[staff-position-formatted]
		[staff-bio-formatted]
		[staff-email-link]
	</div>
[/staff_loop]
';
	
		$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';
		if (!file_exists($filename)){
			// Save custom css to a file in current theme directory
			$custom_css = stripslashes_deep(get_option('_staff_listing_custom_css'));
			file_put_contents($filename, $custom_css);
		}
		
		update_option('_staff_listing_default_html', $default_template);
		update_option('_staff_listing_default_css', $default_css);

	
	}
	
	if ($sslp_ver_option == "" || $plugin_version <= "1.12") {
		update_option('_staff_listing_write_external_css', "yes");
	}
		
	update_option('_simple_staff_list_version', $plugin_version);
	sslp_staff_member_activate('forced activation');
}

?>