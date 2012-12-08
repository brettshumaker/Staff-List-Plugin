<?php

// Check to see if Staff Listing is already installed.
// If not, create the table. Assume it's a new install

$new_install = true;

$staff_listing_table = $wpdb->prefix . 'staff_listing';
$staff_listing_categories = $wpdb->prefix . 'staff_listing_categories';
$staff_listing_options = $wpdb->prefix . 'staff_listing_options';
$staff_listing_templates = $wpdb->prefix . 'staff_listing_templates';

$tables = $wpdb->get_results('show tables;');

//foreach($tables as $tables){

    if( $wpdb->get_var( "SHOW TABLES LIKE '" . STAFF_LISTING_TABLE . "'") != STAFF_LISTING_TABLE ){

    	// install the main staff listing table
    	$sql = "CREATE TABLE " . STAFF_LISTING_TABLE . " (
                                staff_id INT(11) NOT NULL AUTO_INCREMENT ,
                                name VARCHAR(50) NOT NULL ,
                                position VARCHAR(50) NOT NULL ,
                                email_address VARCHAR(50) NOT NULL ,
                                phone_number VARCHAR(30) NOT NULL ,
								order_number INT(2) NOT NULL , 
                                photo VARCHAR(60) NOT NULL ,
                                bio TEXT NOT NULL ,
                                category varchar(3),
                                image varchar(100),
                                PRIMARY KEY (staff_id)
                        )";
   		$wpdb->get_results($sql);	
    	
    }else{
    /*
    	$sql = "ALTER TABLE  `" . STAFF_LISTING_TABLE . "` CHANGE `thumbnail` `photo` VARCHAR( 60 )";
    	$wpdb->get_results($sql);
    	$sql = "ALTER TABLE `" . STAFF_LISTING_TABLE . "` DROP `image`";
    	$wpdb->get_results($sql);
    */
    }
            
    // Check and install categories table
    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_listing_categories'" ) != $staff_listing_categories ){

    	$sql = "CREATE TABLE " . $staff_listing_categories . " (
                                cat_id INT(11) NOT NULL AUTO_INCREMENT ,
                                name VARCHAR(30) NOT NULL ,
                                PRIMARY KEY (cat_id)
                        )";
   		$wpdb->get_results($sql);
   	
   	
   		$sql = "INSERT INTO " . $staff_listing_categories . " (
					`cat_id` ,
					`name`
					)
					VALUES (
					NULL ,  'Uncategorized'
					);";
					
   		$wpdb->get_results($sql);

    }
        
    /*if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_listing_table'" ) == $staff_listing_table ){
    	
    	// Check for 'show_image' field and add if doesn't exist
    	$sql = "SHOW COLUMNS FROM $staff_listing_categories WHERE FIELD = 'show_image'";
       	$fields = $wpdb->query($sql);
    	if($fields == ''){
    		$sql = "ALTER TABLE  `$staff_listing_categories` ADD  `show_image` VARCHAR( 3 ) NOT NULL AFTER  `name` ;";
    		$wpdb->get_results($sql);
    	}
    	// end 'show_image' field
    	
    	
    }*/
    
    
    // Check and install options table
    /*if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_listing_options'" ) != $staff_listing_options ){

   		// 
    	$sql = "CREATE TABLE " . $staff_listing_options . " (
    							option_id INT(11) NOT NULL AUTO_INCREMENT ,
                                option_name VARCHAR(50) NOT NULL ,
                                option_value VARCHAR(50) NOT NULL ,
                                PRIMARY KEY (option_id)
                        )";
   		$wpdb->get_results($sql);
   		   		
   		$sql = "INSERT INTO  `" . $staff_listing_options . "` (
					`option_id` ,
					`option_name` ,
					`option_value`
					)
					VALUES (
					NULL ,  'enable_single_pages',  'no'
					);";
					
   		$wpdb->get_results($sql);
   		
    }*/
    
     // Check and install templates table
    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_listing_templates'" ) != $staff_listing_templates ){

    	$sql = "CREATE TABLE " . $staff_listing_templates . " (
    							template_id INT(11) NOT NULL AUTO_INCREMENT ,
                                template_name VARCHAR(50) NOT NULL ,
                                template_code TEXT NOT NULL ,
                                PRIMARY KEY (template_id)
                        )";
   		$wpdb->get_results($sql);
   		
   		$html = "[staff_loop]
	
    <img src=\"[photo_url]\" alt=\"[name] : [position]\">
	[name]
    [position]
    [email_link]
    [bio_paragraph]
					
    <div class=\"staff-listing-divider\">
    </div>					
[/staff_loop]";
					
   		$sql = "INSERT INTO  `" . $staff_listing_templates . "` (
					`template_id` ,
					`template_name` ,
					`template_code`
					)
					VALUES (
					NULL ,  'staff_index_html',  '$html'
					);";
					
   		$wpdb->get_results($sql);
   		
   		$css = ".staff-listing-divider{
    border-top: solid black thin;
    width: 90%;
    margin:15px 0;
}

.wr-staff-clear {
   float:none;
   clear:both;
}";
							
   		$sql = "INSERT INTO  `" . $staff_listing_templates . "` (
					`template_id` ,
					`template_name` ,
					`template_code`
					)
					VALUES (
					NULL ,  'staff_index_css',  '$css'
					);";
					
   		$wpdb->get_results($sql);
    }
//}

?>