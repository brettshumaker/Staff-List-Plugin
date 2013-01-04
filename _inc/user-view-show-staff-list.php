<?php

function sslp_staff_member_listing_shortcode_func($atts) {
     extract(shortcode_atts(array(
	      'single' => 'no',
     ), $atts));
     
     // Get Template and CSS
     
     $custom_html 				= stripslashes_deep(get_option('staff_listing_custom_html'));
     $custom_css 				= stripslashes_deep(get_option('staff_listing_custom_css'));
     $default_tags 				= get_option('_staff_listing_default_tags');
     $default_formatted_tags 	= get_option('_staff_listing_default_formatted_tags');
     
     
     // Check to see if we have custom css
     if ($custom_css != get_option('staff_listing_default_css')){
	     $output .= '<style type="text/css">'.$custom_css.'</style>';
     }
     
     
     // Go ahead and remove [staff_loop] [/staff_loop] from our html template
     $loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($custom_html, strpos($custom_html, "[staff_loop]"), strpos($custom_html, "[/staff_loop]") - strpos($custom_html, "[staff_loop]")));
     
     
     $staff = new WP_Query( array( 'post_type' => 'staff-member', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) );
     
     $i = 0;
     if( $staff->have_posts() ) { 
     	
     	$output .= '<div class="staff-member-listing">';
     	
		while( $staff->have_posts() ) : $staff->the_post();
			
			if ($i % 2) {
				$output .= '<div class="staff-member odd">';
			} else {
				$output .= '<div class="staff-member even">';
			}
			
			$custom 	= get_post_custom();
			$name 		= get_the_title();
			$title 		= $custom["_staff_member_title"][0];
			$email 		= $custom["_staff_member_email"][0];
			$phone 		= $custom["_staff_member_phone"][0];
			$bio 		= $custom["_staff_member_bio"][0];
			
			
			
			if(has_post_thumbnail()){
				
				$photo_url = wp_get_attachment_url( get_post_thumbnail_id($staff->ID) );
				$photo = '<img class="staff-member-photo" src="'.$photo_url.'" alt = "'.$title.'">';
			}else{
				$photo_url = '';
				$photo = '';
			}
			
			
			if (function_exists('wpautop')){
				$bio_format = '<div class="staff-member-bio">'.wpautop($bio).'</div>';
			}
			
			
			$email_mailto = '<a class="staff-member-email" href="mailto:'.antispambot( $email ).'" title="Email '.$name.'">'.antispambot( $email ).'</a>';
			$email_nolink = antispambot( $email );
			
			$accepted_single_tags = $default_tags;
			$replace_single_values = array($name, $photo_url, $title, $email_nolink, $phone, $bio);
	
			$accepted_formatted_tags = $default_formatted_tags;
			$replace_formatted_values = array('<h3 class="staff-member-name">'.$name.'</h3>', '<h4 class="staff-member-position">'.$title.'</h4>', $photo, $email_mailto, $bio_format);
	
			$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
			$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);
	
			$output .= $loop_markup;
	
			$loop_markup = $loop_markup_reset;
			
			
			
			$output .= '</div> <!-- Close staff-member -->';
			$i += 1;
		
			
		endwhile;
		
		$output .= "</div> <!-- Close staff-member-listing -->";
	}
     return $output;
}
add_shortcode('simple-staff-list', 'sslp_staff_member_listing_shortcode_func');

?>