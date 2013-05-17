<?php

function sslp_staff_member_listing_shortcode_func($atts) {
	extract(shortcode_atts(array(
	  'single' => 'no',
	  'group' => '',
	  'wrap_class' => '',
	  'order' => 'ASC',
	), $atts));
	
	// Get Template and CSS
	
	$custom_html 				= stripslashes_deep(get_option('_staff_listing_custom_html'));
	$custom_css 				= stripslashes_deep(get_option('_staff_listing_custom_css'));
	$default_tags 				= get_option('_staff_listing_default_tags');
	$default_formatted_tags 	= get_option('_staff_listing_default_formatted_tags');
	$output						= '';
	$group						= strtolower($group);
	$order						= strtoupper($order);
	$staff = '';
	
	$use_external_css			= get_option('_staff_listing_write_external_css');
	
	/**
	  * Set up our WP_Query
	  */
	
	$args = array( 'post_type' => 'staff-member', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'post_status' => 'publish' );
	
	// Check user's 'order' value
	if ($order != 'ASC' && $order != 'DESC') {
		$order = 'ASC';
	}
	
	// Set 'order' in our query args
	$args['order'] = $order;
	$args['staff-member-group'] = $group;
	
	$staff = new WP_Query( $args );
	
	
	/**
	  * Set up our loop_markup
	  */
	
	$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($custom_html, strpos($custom_html, "[staff_loop]"), strpos($custom_html, "[/staff_loop]") - strpos($custom_html, "[staff_loop]")));
	
	
	// Doing this so I can concatenate class names for current and possibly future use.
	$staff_member_classes = $wrap_class;
	
	// Prepare to output styles if not using external style sheet
	if ( $use_external_css == "no" ) {
		$style_output = '<style>'.$custom_css.'</style>';
	}
	
	$i = 0;
	
	if( $staff->have_posts() ) {
	
		$output .= '<div class="staff-member-listing '.$group.'">';
		
	while( $staff->have_posts() ) : $staff->the_post();
		
		if ($i == ($staff->found_posts)-1) {
			$staff_member_classes .= " last";
		}
		
		if ($i % 2) {
			$output .= '<div class="staff-member odd '.$staff_member_classes.'">';
		} else {
			$output .= '<div class="staff-member even '.$staff_member_classes.'">';
		}
		
		global $post;
		
		$custom 	= get_post_custom();
		$name 		= get_the_title();
		$name_slug	= basename(get_permalink());
		$title 		= $custom["_staff_member_title"][0];
		$email 		= $custom["_staff_member_email"][0];
		$phone 		= $custom["_staff_member_phone"][0];
		$bio 		= $custom["_staff_member_bio"][0];
		$fb_url		= $custom["_staff_member_fb"][0];
		$tw_url		= 'http://www.twitter.com/' . $custom["_staff_member_tw"][0];
		
		
		
		if(has_post_thumbnail()){
			
			$photo_url = wp_get_attachment_url( get_post_thumbnail_id() );
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
		$replace_single_values = array($name, $name_slug, $photo_url, $title, $email_nolink, $phone, $bio, $fb_url, $tw_url);
	
		$accepted_formatted_tags = $default_formatted_tags;
		$replace_formatted_values = array('<h3 class="staff-member-name">'.$name.'</h3>', '<h4 class="staff-member-position">'.$title.'</h4>', $photo, $email_mailto, $bio_format );
	
		$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
		$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);
	
		$output .= $loop_markup;
	
		$loop_markup = $loop_markup_reset;
		
		
		
		$output .= '</div> <!-- Close staff-member -->';
		$i += 1;
	
		
	endwhile;
	
	$output .= "</div> <!-- Close staff-member-listing -->";
	}
	
	wp_reset_query();
	
	$output = $style_output.$output;
	
	return do_shortcode($output);
}
add_shortcode('simple-staff-list', 'sslp_staff_member_listing_shortcode_func');

?>