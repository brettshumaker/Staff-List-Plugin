<?php

function get_all_staff($orderby = null, $order = null, $filter = null){
	global $wpdb;
	$staff_listing_table = $wpdb->prefix . 'staff_listing';
	$staff_listing_categories = $wpdb->prefix . 'staff_listing_categories';
	
	if((isset($orderby) AND $orderby != '') AND (isset($order) AND $order != '') AND (isset($filter) AND $filter != '')){
	
		if($orderby == 'name'){
			
			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE `category` = $filter ORDER BY `name` $order");
			
		}
		
		if($orderby == 'category'){
			
			$categories = $wpdb->get_results("SELECT * FROM $staff_listing_categories WHERE `cat_id` = $filter ORDER BY name $order");
			
			foreach($categories as $category){
				$cat_id = $category->cat_id;
				//echo $cat_id;
				$staff_by_cat = $wpdb->get_results("SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE `category` = $cat_id");
				foreach($staff_by_cat as $staff){
					$all_staff[] = $staff;
				}
			}
		}
		
		return $all_staff;
		
	
	}elseif((isset($orderby) AND $orderby != '') AND (isset($order) AND $order != '')){
		
		if($orderby == 'name'){
			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_LISTING_TABLE . " ORDER BY `name` $order");
			
		}
		
		if($orderby == 'category'){
			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_LISTING_TABLE . " ORDER BY category $order");
			
		}

		
		return $all_staff;
		
		
	}elseif(isset($filter) AND $filter != ''){
		
		$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE `category` = $filter");
		if(isset($all_staff)){
			return $all_staff;
		}
	
	}else{
	
		return $wpdb->get_results("SELECT * FROM " . STAFF_LISTING_TABLE);
		
	}
}

function get_single_staff_member($id){
	global $wpdb;
	$staff_listing_table = $wpdb->prefix . 'staff_listing';
	return $wpdb->get_row("SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE staff_id = '$id'");
}

function get_staff_member_name_by_id($id){
	global $wpdb;
	$staff_listing_table = $wpdb->prefix . 'staff_listing';
	$staff = $wpdb->get_row("SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE staff_id = '$id'");
	return $staff->name;
}

function get_staff_category($cat){
	global $wpdb;
	$staff_listing_table = $wpdb->prefix . 'staff_listing';
	return $wpdb->get_row("SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE category = '$cat'");
}


function staff_listing($param = null){
	parse_str($param);
	global $wpdb;
	$output = '';
	
	// go ahead and load all of our template data for processing
	$index_html = $wpdb->get_var("SELECT template_code FROM " . STAFF_TEMPLATES . " WHERE template_name = 'staff_index_html'");
	$index_css = $wpdb->get_var("SELECT template_code FROM " . STAFF_TEMPLATES . " WHERE template_name = 'staff_index_css'");

	$output .= "<style type=\"text/css\">$index_css</style>";
	$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($index_html, strpos($index_html, "[staff_loop]"), strpos($index_html, "[/staff_loop]") - strpos($index_html, "[staff_loop]")));
	// done with our templates, for now
	
	// make sure we aren't calling both id and cat at the same time
	if(isset($id) && $id!= '' && isset($cat) && $cat != ''){
		return "<strong>ERROR: You cannot set both a single ID and a category ID for your Staff Listing</strong>";
	}
	
	
	// check if it's a single staff member first, since single members won't be ordered	
	if((isset($id) && $id != '') && (!isset($cat) || $cat == '')){
		$sql = "SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE `staff_id` = $id";
		$staff = $wpdb->get_row($sql, OBJECT);
		
		if($staff->photo != ''){
			$photo_url = get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo;
			$photo = "<img src=\"" . get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo . "\" class=\"staff-photo single-staff-photo\">";
		}else{
			$photo_url = '';
			$photo = '';
		}
		$accepted_single_tags = array("[name]", "[photo_url]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
		$replace_single_values = array($staff->name, $photo_url, $staff->position, $staff->email_address, $staff->phone_number, $staff->bio, $staff->category);

		$accepted_formatted_tags = array("[name_header]", "[position_h4]", "[photo]", "[email_link]", "[bio_paragraph]");
		$replace_formatted_values = array("<h3>$staff->name</h3>", "<h4>$staff->position</h4>", $photo, "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");

		$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
		$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);

		$output .= $loop_markup;
		
	}
	// ends single staff
	
	// check if we're returning a staff category
	if((isset($cat) && $cat != '') && (!isset($id) || $id == '')){
		$sql = "SELECT * FROM " . STAFF_LISTING_TABLE . " WHERE `category` = $cat";
		if(isset($orderby) && $orderby != ''){
			$sql .= " ORDER BY `$orderby`";
		}
		if(isset($order) && $order != ''){
			$sql .= " $order";
		}
		$staff = $wpdb->get_results($sql, OBJECT);
		foreach($staff as $staff){
			if($staff->photo != ''){
				$photo_url = get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo;
				$photo = "<img src=\"" . get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo . "\" class=\"staff-photo single-staff-photo\">";
			}else{
				$photo_url = '';
				$photo = '';
			}
			$accepted_single_tags = array("[name]", "[photo_url]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
			$replace_single_values = array($staff->name, $photo_url, $staff->position, $staff->email_address, $staff->phone_number, $staff->bio, $staff->category);

			$accepted_formatted_tags = array("[name_header]", "[position_h4]", "[photo]", "[email_link]", "[bio_paragraph]");
			$replace_formatted_values = array("<h3>$staff->name</h3>", "<h4>$staff->position</h4>", $photo, "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");

			$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
			$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);
			
			$output .= $loop_markup;
			
			$loop_markup = $loop_markup_reset;
		}
	}
	
	// neither cat no id is set - return all staff
	if((!isset($id) || $id == '') && (!isset($cat) || $cat == '')){
			$sql = "SELECT * FROM " . STAFF_LISTING_TABLE;
			if(isset($orderby) && $orderby != ''){
				$sql .= " ORDER BY `$orderby`";
			}
			if(isset($order) && $order != ''){
				$sql .= " $order";
			}
			$staff = $wpdb->get_results($sql, OBJECT);
			
			$i = 0;
			
			function get_bio_excerpt($string, $wordsreturned) {
					/*  Returns the first $wordsreturned out of $string.  If string
					contains fewer words than $wordsreturned, the entire string
					is returned.*/
					//echo($wordsreturned);
					
					$retval = $string;
					
					$array = explode(" ", $string);
					
					if (count($array)<=$wordsreturned) {
						$retval=$string;
					} else {
						array_splice($array, $wordsreturned);
						$retval = implode(" ", $array)." ...";
					}
					//echo("<!-- ".$retval."-->");
					return $retval;
			}
			
			function insert_bio_BR($str){
				
				$order   = array("\r\n", "\r", "\n");
				$replace = '<br /><br />';
				$str = str_replace($order, $replace , $str);
				
				return $str;
				
			}
			
			
			
			$output .= "<script type=\"text/javascript\">
			$(document).ready(function(){				
					$(this).next(\"div\").slideToggle(\"slow\").siblings(\"div:visible\").slideUp(\"slow\");
					$(this).toggleClass(\"active\");
					$(this).siblings(\"h5\").removeClass(\"active\");
				});
			
			});
			</script>";
			
			
			
			
			
			$mikesJSplugin = "
			<script language=\"JavaScript\" type=\"text/JavaScript\">
/*$(function() 
	{
      $('.wr-the-bio').truncate({max_length: 230});
	  
    });*/
	
	jQuery(document).ready(function($) {
		$('.wr-the-bio').truncate({max_length: 230});
	});

</script>";
			
			$output .= "<div class=\"staff-listing\">".$mikesJSplugin;
			
			foreach($staff as $staff){
					
				if ($i % 2) {
					$output .= "<div class=\"staff-member odd\">";
				} else {
					$output .= "<div class=\"staff-member even\">";
				}
				
				
				
				
				$wr_pre_bio = $staff->bio;
				$numWords=50;
				$wr_the_excerpt=get_bio_excerpt($wr_pre_bio, $numWords);
				
				
				//$wr_formatted_bio = nl2br($staff->bio);
				$wr_formatted_bio = insert_bio_BR($staff->bio);
					
				if($staff->photo != ''){
					
					$photo_url = get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo;
					$photo = "<img src=\"" . get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo . "\" class=\"staff-photo single-staff-photo\">";
				}else{
					$photo_url = '';
					$photo = '';
				}
				$accepted_single_tags = array("[name]", "[photo_url]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
				$bio_format = "<!--<h5>Full Bio</h5>--><div class=\"wr-the-bio\">".$wr_formatted_bio."</div>";
				
				$replace_single_values = array($staff->name, $photo_url, $staff->position, $staff->email_address, $staff->phone_number, $bio_format, $staff->category);

				$accepted_formatted_tags = array("[name_header]", "[position_h4]", "[photo]", "[email_link]", "[bio_paragraph]");
				$replace_formatted_values = array("<h3>$staff->name</h3>", "<h4>$staff->position</h4>", $photo, "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");

				$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
				$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);

				$output .= $loop_markup;
				
				$output .= "<div class=\"wr-staff-clear\"></div></div>";

				$loop_markup = $loop_markup_reset;
				
				$i += 1;
			}
	}
	
	return $output;
}
?>