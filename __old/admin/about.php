<?php
function about_staff_listing() {
    $output = "<h2>About Staff Listing Settings</h2>";
    $output .= "<div style=\"padding:15px;\">";
    $output .= "<p>Plugin version: 0.1
				<br />
 				Developed by: Brett Shumaker - <a href=\"mailto:brett@latockiteamcreative.com?subject=Staff Listing Support\">brett@latockiteamcreative.com</a>
				<br />
				<span style=\"font-style:italic; font-size:.85em;\">Adapted from Staff Directory plugin by Adam Tootle</span>
				</p>
				<p>
				<h3>Instructions:</h3>
				User the following shortcodes in your post to display your staff listing or staff members:
				<br /><br />
				<b>[staff-listing]</b> will display your full staff listing.
				<br /><br />
				<b>[staff-listing cat=x]</b> will display each staff member in the category with the id specified. Replace x and use like so: [staff-listing cat =1]
				<br /><br />
				<b>[staff-listing id=x]</b> will display a single staff member, specified by the id. Works like the category tag: [staff-listing id=3]
				<br /><br />
				</p>
				<p>
				<h3>Staff Ordering</h3>
				This is the first phase of the ordering that is to come.
				<br />
				I have added two more parameters into the shortcodes.
				<br />
				You can now add 'orderby' and 'order' into the [staff-listing] tag.
				<br /><br />
				<b>Example:</b>
				<br />
				<b>[staff-listing orderby=name order=asc]</b> - this will order your staff by their name, in ascending order.
				<br />
				<b>[staff-listing orderby=name order=desc]</b> - this will order your staff by their name, in descending order.
				<br />
				<b>[staff-listing orderby=order_number]</b> - this will order your staff by the order number set in their details.
				<br /><br />
				You can also use the 'cat' parameter along with these.
				<br /><br />
				<b>Example:</b>
				<br />
				<b>[staff-listing cat=3 orderby=name order=asc]</b> - this will return all of the staff in the given category (ID 3), order by name in ascending order.
				</p>
				<br />";
    $output .= "</div>";
    
    echo $output;
}

?>