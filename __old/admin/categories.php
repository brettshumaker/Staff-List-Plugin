<?php

// Categories Page
function staff_listing_categories() {
	global $wpdb;
	
	$staff_listing_categories_table = $wpdb->prefix . 'staff_listing_categories';
	$categories = $wpdb->get_results("SELECT * FROM $staff_listing_categories_table");
	
   
    
    if(!isset($_GET['action'])){
     	$output = "<h2>Staff Categories</h2>";
    	$addNewURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-listing-categories&action=addCategory";
		$output .= "<div style=\"padding:15px;\">";
		$output .= "<p><a href=\"" . $addNewURL . "\">+ Add New Category</a></p>";
    	$output .= "<div style=\"width:400px;\">";
    	$output .= "<table class=\"widefat\">
    				<thead>
    					<tr>
    						<th>ID</th>
    						<th>Category Name</th>
    						<th>Actions</th>
    					</tr>
    				</thead>";
    	foreach($categories as $category){
    		$output .= "<tr>";
    		$output .= "<td>" . $category->cat_id . "</td>";
    		$output .= "<td>" . $category->name . "</td>";
    		
    		$editURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-listing-categories&action=edit&id=" . $category->cat_id;
			$deleteURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-listing-categories&action=delete&id=" . $category->cat_id;
			$output .= "<td><a href=\"" . $editURL . "\">Edit</a> | <a href=\"" . $deleteURL . "\">Delete</a></td>";
				
    		$output .= "</tr>";
    	}
    	
    	$output .= "</table>";
    	
    }
    
    // setup the add new category page
    if(isset($_GET['action']) && $_GET['action'] == 'addCategory'){
    	$output .= "<h2>Add Category</h2>";
    	$output .= "<div style=\"width:400px; padding:15px\">";
    		
    	if(!isset($_POST['category-name'])){
    		
    		$output .= "<form method=\"post\">
    					<table class=\"widefat\">
    						<thead>
    							<tr>
    								<th>Enter Category Details</th>
    								<th></th>
    							</tr>
    						</thead>
    						<tr>
    							<td>Name:</td>
    							<td><input name=\"category-name\"></td>
    						</tr>
    					</table>
    					<input type=\"submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
    					</form>
    		";
    	}
    	
    	if(isset($_POST['category-name'])){
    		$category_name = $_POST['category-name'];
    		$sql = "INSERT INTO $staff_listing_categories_table (cat_id, name) VALUES (NULL, '$category_name');";
    		$wpdb->get_results($sql);
    	
    		$output .= "<p>" . $category_name . " was added</p>";
    		$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-listing-categories\">Back To Categories</a></p>";

    	
    	}
    
    }
    
    // setup the edit categories page
    if(isset($_GET['action']) && $_GET['action'] == 'edit'){
    	global $wpdb;
    	$id = $_GET['id'];
		$staff_listing_categories_table = $wpdb->prefix . 'staff_listing_categories';
		$sql = "SELECT * FROM " . $staff_listing_categories_table . " WHERE cat_id = ". $id . ";";
		$category = $wpdb->get_row($sql);

    	if(!isset($_POST['cat_name'])){
    		$output .= "<h2>Edit Category</h2>";
    		$output .= "<div style=\"width:400px; padding:15px\">
    					<form method=\"post\">
      					<table class=\"widefat\">
    					<thead>
    						<tr>
    							<th>Category Name</th>
    						</tr>
    					</thead>
    					<tr>
    						<td><input name=\"cat_name\" value=\"" . $category->name . "\"></td>
    					</tr>
    					</table>
    					<input type=\"submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
    					</form>
    					";
    	}
    	
    	if(isset($_POST['cat_name'])){
    		$sql = "UPDATE  " . $staff_listing_categories_table . " SET  `name` =  '" . $_POST['cat_name'] . "' WHERE  `cat_id` =  " . $id . ";";
    		$wpdb->get_results($sql);
    		$output .= "<h2>Edit Category</h2>";
    		$output .= "<div style=\"width:400px; padding:15px\">";
    		$output .= "<p>". $_POST['cat_name'] . " was updated</p>
    					<form method=\"post\">
      					<table class=\"widefat\">
    					<thead>
    						<tr>
    							<th>Category Name</th>
    						</tr>
    					</thead>
    					<tr>
    						<td><input name=\"cat_name\" value=\"" . $_POST['cat_name'] . "\"></td>
    					</tr>
    					</table>
    					<input type=\"submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
    					</form>
    					";    		
    	
    	}
    
    }
    
    // setup the delete page
    if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    	global $wpdb;
    	$id = $_GET['id'];
    	$category = $wpdb->get_row("SELECT * FROM $staff_listing_categories_table WHERE cat_id = '$id'");
    	
    	
    	if(!isset($_POST['confirm-category-delete'])){
    		$output .= "<h2>Delete Category</h2>";
    		$output .= "Are you sure you want to delete " . $category->name . "? <br>This cannot be undone!";
			$output .= "<form method=\"post\">
						<input name=\"confirm-category-delete\" value=\"yes\" style=\"display:none\">
						<input name=\"category-to-delete-nice-name\" value=\"" . $category->name . "\" style=\"display:none\">
						<input type=\"submit\" value=\"Yes\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
						</form>
						";
    	}
    	if(isset($_POST['confirm-category-delete']) && $_POST['confirm-category-delete'] == 'yes'){
    		$sql = "DELETE FROM `" . $staff_listing_categories_table . "` WHERE `cat_id` = " . $id . ";";
    		$wpdb->get_results($sql);
    		
    		$output .= "<h2>Delete Category</h2>";
    		$output .= "<p>" . $_POST['category-to-delete-nice-name'] . " was deleted</p>";
    		$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-listing\">Back to Staff</a></p>";
    
    	}
    
    }
    
    
    $output .= "</div>";
    			
    echo $output;
}

?>