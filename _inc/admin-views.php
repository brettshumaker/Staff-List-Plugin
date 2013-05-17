<?php


/**
 * Displays Staff Member INFO Meta Box
 * 
 * Callback function to display meta box
 *
 * @param    object		$post    				Contains the global $post object
 * @param    array		$custom    				Contains the post's custom meta
 * @param    string		$_staff_member_title    Staff member job title
 * @param    string		$_staff_member_email    Staff member email address
 * @param    string		$_staff_member_phone    Staff member phone number
 *
 */

function sslp_staff_member_info_meta_box(){
	global $post;
	$custom = get_post_custom($post->ID);
	$_staff_member_title = $custom["_staff_member_title"][0];
	$_staff_member_email = $custom["_staff_member_email"][0];
	$_staff_member_phone = $custom["_staff_member_phone"][0];
	$_staff_member_fb	 = $custom["_staff_member_fb"][0];
	$_staff_member_tw	 = $custom["_staff_member_tw"][0];
	?>
	<div class="sslp_admin_wrap">
		<label for="_staff-member-title">Position: <input type="text" name="_staff_member_title" id="_staff_member_title" placeholder="<?php if ($_staff_member_title == '') echo ('Staff Member\'s Position'); ?>" value="<?php if ($_staff_member_title != '') echo $_staff_member_title; ?>" /></label>
		<label for="_staff-member-email">Email: <input type="text" name="_staff_member_email" id="_staff_member_email" placeholder="<?php if ($_staff_member_email == '') echo ('Staff Member\'s Email'); ?>" value="<?php if ($_staff_member_email != '') echo $_staff_member_email; ?>" /></label>
		<label for="_staff-member-title">Phone: <input type="text" name="_staff_member_phone" id="_staff_member_phone" placeholder="<?php if ($_staff_member_phone == '') echo ('Staff Member\'s Phone'); ?>" value="<?php if ($_staff_member_phone != '') echo $_staff_member_phone; ?>" /></label>
		<label for="_staff-member-fb">Facebook URL: <input type="text" name="_staff_member_fb" id="_staff_member_fb" placeholder="<?php if ($_staff_member_fb == '') echo ('Staff Member\'s Facebook URL'); ?>" value="<?php if ($_staff_member_fb != '') echo $_staff_member_fb; ?>" /></label>
		<label for="_staff-member-tw">Twitter Username: <input type="text" name="_staff_member_tw" id="_staff_member_tw" placeholder="<?php if ($_staff_member_tw == '') echo ('Staff Member\'s Twitter Name'); ?>" value="<?php if ($_staff_member_tw != '') echo $_staff_member_tw; ?>" /></label>
	</div>
<?php	
}




/*
// Warning Meta Box
//////////////////////////////*/

function sslp_staff_member_warning_meta_box() {
	echo '<p><strong>Your current theme does not support post thumbnails. Unfortunately, you will not be able to add photos for your Staff Members</strong></p>';
}




/*
// Bio Meta Box
//////////////////////////////*/

function sslp_staff_member_bio_meta_box(){
	global $post;
	$custom = get_post_custom($post->ID);
	$_staff_member_bio = $custom["_staff_member_bio"][0];
	
	wp_editor( $_staff_member_bio, '_staff_member_bio', $settings = array(
												textarea_rows => 8,
												media_buttons => false,
												tinymce => true, // Disables actual TinyMCE buttons // This makes the rich content editor
												quicktags => true // Use QuickTags for formatting    // work within a metabox.
												) );
	?>
	
	<p class="sslp-note">**Note: HTML is allowed.</p>
	
	<?php wp_nonce_field('sslp_post_nonce', 'sslp_add_edit_staff_member_noncename') ?>
	
	<?php
}




/*
// Staff List Custom Column View
//////////////////////////////*/

add_action( "manage_posts_custom_column", "sslp_staff_member_display_custom_columns");

function sslp_staff_member_display_custom_columns( $column ) {
  global $post;
  $custom = get_post_custom();
  
  $_staff_member_title = $custom["_staff_member_title"][0];
  $_staff_member_email = $custom["_staff_member_email"][0];
  $_staff_member_phone = $custom["_staff_member_phone"][0];
  $_staff_member_bio   = $custom["_staff_member_bio"][0];
  switch ( $column ) {
    case "photo":
      if(has_post_thumbnail()){
	      echo get_the_post_thumbnail( $post->ID, array( 75, 75 ) );
      }
      break;
  	case "_staff_member_title":
  	  echo $_staff_member_title;
  	  break;
    case "_staff_member_email":
      echo '<a href="mailto:' . $_staff_member_email . '">' . $_staff_member_email . '</a>';
      break;
    case "_staff_member_phone":
      echo $_staff_member_phone;
      break;
    case "_staff_member_bio":
      echo staff_bio_excerpt($_staff_member_bio, 10);
      break;
  }
}





/*
// Build the 'Ordering' Page
//////////////////////////////*/

function sslp_staff_member_order_page() {
?>
	<div class="wrap">
		<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div><h2>Simple Staff List</h2>
		<h2>Order Staff</h2>
		<p>Simply drag the staff member up or down and they will be saved in that order.</p>
	<?php $staff = new WP_Query( array( 'post_type' => 'staff-member', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) );
		  if( $staff->have_posts() ) : ?>

		<table class="wp-list-table widefat fixed posts" id="sortable-table">
			<thead>
				<tr>
					<th class="column-order">Order</th>
					<th class="column-photo">Photo</th>
					<th class="column-name">Name</th>
					<th class="column-title">Position</th>
					<th class="column-email">Email</th>
					<th class="column-phone">Phone</th>
					<th class="column-bio">Bio</th>
				</tr>
			</thead>
			<tbody data-post-type="product">
			<?php while( $staff->have_posts() ) : $staff->the_post();
				  $custom = get_post_custom();
			?>
				<tr id="post-<?php the_ID(); ?>">
					<td class="column-order"><img src="<?php echo STAFFLIST_PATH . '_images/move-icon.png'; ?>" title="" alt="Move Icon" width="24" height="24" class="" /></td>
					<td class="column-photo"><?php echo get_the_post_thumbnail( $post->ID, array( 75, 75 ) ); ?></td>
					<td class="column-name"><strong><?php the_title(); ?></strong></td>
					<td class="column-title"><?php echo $custom["_staff_member_title"][0]; ?></td>
					<td class="column-email"><?php echo $custom["_staff_member_email"][0]; ?></td>
					<td class="column-phone"><?php echo $custom["_staff_member_phone"][0]; ?></td>
					<td class="column-bio"><?php   echo staff_bio_excerpt($custom["_staff_member_bio"][0], 10); ?></td>
				</tr>
			<?php endwhile; ?>
			</tbody>
			<tfoot>
				<tr>
					<th class="column-order">Order</th>
					<th class="column-photo">Photo</th>
					<th class="column-name">Name</th>
					<th class="column-title">Position</th>
					<th class="column-email">Email</th>
					<th class="column-phone">Phone</th>
					<th class="column-bio">Bio</th>
				</tr>
			</tfoot>

		</table>

	<?php else: ?>

		<p>No staff members found, why not <a href="post-new.php?post_type=staff-member">create one?</a></p>

	<?php endif; ?>
	<?php wp_reset_postdata(); // Don't forget to reset again! ?>
	</div><!-- .wrap -->

<?php

}





/*
// Build the 'Usage' Page
//////////////////////////////*/

function sslp_staff_member_usage_page() {
	
	$output .= '<div class="wrap sslp-usage">';
	$output .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div><h2>Simple Staff List</h2>';
	$output .= '<h2>Usage</h2>';
	$output .= '<p>The Simple Staff List plugin makes it easy to create and display a staff directory on your website. You can create your own <a href="edit.php?post_type=staff-member&page=staff-member-template" title="Edit the Simple Staff List template.">template</a> for displaying staff information as well as <a href="edit.php?post_type=staff-member&page=staff-member-usage" title="Edit Custom CSS for Simple Staff List">add custom css</a> styling to make your staff directory look great.</p>';
	
	$output .= '<h3>Shortcode</h3>';
	$output .= '<table><tbody>';
	$output .= '<tr><td width="280px"><code>[simple-staff-list]</code></td><td>This is the most basic usage of Simple Staff List. Displays all Staff Members on post or page.</td></tr>';
	$output .= '<tr><td><code>[simple-staff-list <strong>group="Robots"</strong>]</code></td><td>This displays all Staff Members from the group "Robots" sorted by order on the "Order" page. This will also add a class of "Robots" to the outer Staff List container for styling purposes.</td></tr>';
	$output .= '<tr><td><code>[simple-staff-list <strong>wrap_class="clearfix"</strong>]</code></td><td>This adds a class to the inner Staff Member wrap.</td></tr>';
	$output .= '<tr><td><code>[simple-staff-list <strong>order="ASC"</strong>]</code></td><td>This displays Staff Members sorted by ascending or descending order according to the "Order" page. You may use "ASC" or "DESC" but the default is "ASC"</td></tr>';
	
	$output .= '</tbody></table>';
	
	$output .= '<p>To display your Staff List just use the shortcode <code>[simple-staff-list]</code> in any page or post. This will output all staff members according to the template options set <a href="edit.php?post_type=staff-member&page=staff-member-template" title="Edit the Simple Staff List template.">here</a>.</p>';
	
	$output .= '<p></p>';

	$output .= '</div>';
	echo $output;
}





/*
// Build the 'Templates' Page
//////////////////////////////*/

function sslp_staff_member_template_page(){ 

	// Get options for default HTML CSS
	$default_html 					= get_option('_staff_listing_default_html');
	$default_css 					= get_option('_staff_listing_default_css');
	$default_tag_string 			= get_option('_staff_listing_default_tag_string');
	$default_formatted_tag_string 	= get_option('_staff_listing_default_formatted_tag_string');
	$default_tags 					= get_option('_staff_listing_default_tags');
    $default_formatted_tags 		= get_option('_staff_listing_default_formatted_tags');
    $write_external_css				= get_option('_staff_listing_write_external_css');
    
    $default_tag_ul  = '<ul class="sslp-tag-list">';
    
    foreach( $default_tags as $tag ) {
	    $default_tag_ul .= '<li>' . $tag . '</li>';
    }
    
    $default_tag_ul .= '</ul>';
    
    $default_formatted_tag_ul  = '<ul class="sslp-tag-list">';
    
    foreach( $default_formatted_tags as $tag ) {
	    $default_formatted_tag_ul .= '<li>' . $tag . '</li>';
    }
    
    $default_formatted_tag_ul .= '</ul>';
	
	
	// Check Nonce and then update options
	if ( !empty($_POST) && check_admin_referer( 'staff-member-template', 'staff-list-template' ) ) {
		update_option('_staff_listing_custom_html', $_POST[ "staff-listing-html"]);
		update_option('_staff_listing_custom_css', $_POST[ "staff-listing-css"]);
		
		$custom_html = stripslashes_deep(get_option('_staff_listing_custom_html'));
		$custom_css = stripslashes_deep(get_option('_staff_listing_custom_css'));
		
		if ( $_POST[ "write-external-css" ] != "yes" ) {
			update_option('_staff_listing_write_external_css', "no");
			$write_external_css = "no";
		} else {
			update_option('_staff_listing_write_external_css', $_POST[ "write-external-css" ]);
			$write_external_css = "yes";
			
			// User wants to write to external CSS file, do it.
			$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';
			file_put_contents($filename, $custom_css);
		}


	} else {
		$custom_html = stripslashes_deep(get_option('_staff_listing_custom_html'));
		
		if ( $write_external_css == "yes" ) {
		
			$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';
				
			if (file_exists($filename)){
				$custom_css = file_get_contents($filename);
				update_option('_staff_listing_custom_css', $custom_css);
			} else {
				$custom_css  = stripslashes_deep(get_option('_staff_listing_default_css'));
				update_option('_staff_listing_custom_css', $custom_css);
				file_put_contents($filename, $custom_css);
			}
		} else {
			$custom_css = stripslashes_deep(get_option('_staff_listing_custom_css'));
		}
	}
	
	if ( $write_external_css == 'yes' ){
		$ext_css_check = "checked";
	}
	
	$output .= '<div class="wrap sslp-template">';
	$output .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div><h2>Simple Staff List</h2>';
	$output .= '<h2>Templates</h2>';
    
    $output .= '<div>';
    $output .= '<h4>Accepted Template Tags <strong>(UNFORMATTED)</strong></h4>';
    $output .= $default_tag_ul;
    
    $output .= '<br />';
    
    $output .= '<h4>Accepted Template Tags <strong>(FORMATTED)</strong></h4>';
    $output .= $default_formatted_tag_ul;

    $output .= '<br />';
        
    $output .= '<p>These <strong>MUST</strong> be used inside the <code>[staff_loop]</code> wrapper. The unformatted tags will return plain strings so you will want to wrap them in your own HTML. The <code>[staff_loop]</code> can accept any HTML so be careful when adding your own HTML code. The formatted tags will return data wrapped in HTML elements. For example, <code>[staff-name-formatted]</code> returns <code>&lt;h3&gt;STAFF-NAME&lt;/h3&gt;</code>, and <code>[staff-email-link]</code> returns <code>&lt;a href="mailto:STAFF-EMAIL" title="Email STAFF-NAME"&gt;STAFF-EMAIL&lt;/a&gt;</code>.</p>';
    $output .= '<p>**Note: All emails are obfuscated using the <a href="http://codex.wordpress.org/Function_Reference/antispambot" target="_blank" title="WordPress email obfuscation function: antispambot()">antispambot() WordPress function</a>.</p>';
    $output .= '<br />';
    
    $output .= '<form method="post" action="">';
    $output .= '<h3>Staff Loop Template</h3>';
    
    $output .= '<div class="default-html">
    				<h4 class="heading button-secondary">View Default Template</h4>
    				<div class="content">
    					<pre>'.htmlspecialchars(stripslashes_deep($default_html)).'</pre>
    				</div>
    			</div><br />';
    
    $output .= '<textarea name="staff-listing-html" cols="120" rows="16">'.$custom_html.'</textarea>';
    $output .= '<p><input type="submit" value="Save ALL Changes" class="button button-primary button-large"></p><br /><br />';
    
    $output .= '<h3>Staff Page CSS</h3>';
    
    $output .= '<p><input type="checkbox" name="write-external-css" id="write-external-css" value="yes" '.$ext_css_check.' /><label for="write-external-css"> Write to external CSS file? (Leave unchecked for WP Multisite.)</label>';
    
    $output .= '<div class="default-css">
    				<h4 class="heading button-secondary">View Default CSS</h4>
    				<div class="content">
    					<pre>'.htmlspecialchars(stripslashes_deep($default_css)).'</pre>
    				</div>
    			</div><br />';
    			
    $output .= '<p style="margin-top:0;">Add your custom CSS below to style the output of your staff list. I\'ve included selectors for everything output by the plugin.</p>';
    $output .= '<textarea name="staff-listing-css" cols="120" rows="16">'.$custom_css.'</textarea>';
    
    $output .= '<p><input type="submit" value="Save ALL Changes" class="button button-primary button-large"></p>';
    $output .= wp_nonce_field('staff-member-template', 'staff-list-template');
    $output .= '</form>';
    $output .= '</div>';
        
    echo $output;

}
 
?>