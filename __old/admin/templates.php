<?php
// this only handles the admin template page
// the template functions can be found in staff-listing/functions.php
function staff_listing_templates() {

	global $wpdb;
	$staff_listing_templates = $wpdb->prefix . 'staff_listing_templates';
	$output = "";
	
	if(isset($_POST['staff-index-html'])){
		$sql = "UPDATE  $staff_listing_templates SET  `template_code` =  '" . $_POST['staff-index-html'] . "' WHERE  `template_name` = 'staff_index_html'";
		$wpdb->get_results($sql);
		
		$sql = "UPDATE  $staff_listing_templates SET  `template_code` =  '" . $_POST['staff-index-css'] . "' WHERE  `template_name` = 'staff_index_css'";
		$wpdb->get_results($sql);
		
		$output .= "<div id=\"message\" class=\"update fade\"><p>Settings Update</p></div>";
	}
	
	$staff_index_template = $wpdb->get_var("SELECT template_code FROM $staff_listing_templates WHERE template_name = 'staff_index_html';");
	$staff_index_css = $wpdb->get_var("SELECT template_code FROM $staff_listing_templates WHERE template_name = 'staff_index_css';");

    $output .= "<h2>Staff Listing Templates</h2>";
    
    $output .= "<div style=\"padding:15px;\">";
    $output .= "<p>Accepted Shortcodes - These <strong>MUST</strong> be used inside the '[staff_loop]' shortcodes:</p>";
    $output .= "<p><xmp>[name] , [photo_url] , [position] , [email] , [phone] , [bio] , [category]</xmp></p>";
    $output .= "<p>These will only return string values. If you would like to return pre-formatted headers (using &lt;h3&gt; tags), links, and paragraphs, use these:</p>";
    $output .= "<p><xmp>[name_header] , [position_h4] , [photo] , [email_link] , [bio_paragraph]</xmp></p>";
    $output .= "<form method=\"post\">";
    $output .= "<h3>Staff Page HTML</h3>";
    $output .= "<textarea name=\"staff-index-html\" cols=\"120\" rows=\"16\">$staff_index_template</textarea>";
    $output .= "<h3>Staff Page CSS</h3>";
    $output .= "<textarea name=\"staff-index-css\" cols=\"120\" rows=\"16\">$staff_index_css</textarea>";
    
    $output .= "<p><input type=\"submit\" value=\"Save Changes\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\"></p>";
    $output .= "</form>";
    $output .= "</div>";
        
    echo $output;
}

?>