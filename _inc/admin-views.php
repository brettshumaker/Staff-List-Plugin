<?php


/*
// Member Info Meta Box
//////////////////////////////*/

function staff_member_info_meta_box(){
	global $post;
	$custom = get_post_custom($post->ID);
	$_staff_member_title = $custom["_staff_member_title"][0];
	$_staff_member_email = $custom["_staff_member_email"][0];
	$_staff_member_phone = $custom["_staff_member_phone"][0];
	?>
	
	<label for="_staff-member-title">Title:</label>
	<input type="text" name="_staff_member_title" id="_staff_member_title" placeholder="<?php if ($_staff_member_title == '') echo ('Staff Member\'s Title'); ?>" value="<?php if ($_staff_member_title != '') echo $_staff_member_title; ?>" />
	<label for="_staff-member-email">Email:</label>
	<input type="text" name="_staff_member_email" id="_staff_member_email" placeholder="<?php if ($_staff_member_email == '') echo ('Staff Member\'s Email'); ?>" value="<?php if ($_staff_member_email != '') echo $_staff_member_email; ?>" />
	<label for="_staff-member-title">Phone:</label>
	<input type="text" name="_staff_member_phone" id="_staff_member_phone" placeholder="<?php if ($_staff_member_phone == '') echo ('Staff Member\'s Phone'); ?>" value="<?php if ($_staff_member_phone != '') echo $_staff_member_phone; ?>" />
	
<?php	
}




/*
// Bio Meta Box
//////////////////////////////*/

function staff_member_bio_meta_box(){
	global $post;
	$custom = get_post_custom($post->ID);
	$_staff_member_bio = $custom["_staff_member_bio"][0];
	
	wp_editor( $_staff_member_bio, '_staff_member_bio', $settings = array(
												textarea_rows => 8,
												media_buttons => false,
												tinymce => false, // Disables actual TinyMCE buttons // This makes the rich content editor
												quicktags => true // Use QuickTags for formatting    // work within a metabox.
												) );
}




/*
// Staff List Custom Column View
//////////////////////////////*/

add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );

function custom_columns( $column, $post_id ) {
  global $post;
  $custom = get_post_custom();
  $_staff_member_title = $custom["_staff_member_title"][0];
  $_staff_member_email = $custom["_staff_member_email"][0];
  $_staff_member_phone = $custom["_staff_member_phone"][0];
  $_staff_member_bio   = $custom["_staff_member_bio"][0];
  switch ( $column ) {
    case "photo":
      echo get_the_post_thumbnail( $post->ID );
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

function staff_member_order_page() {
?>
	<div class="wrap">
		<h2>Sort Staff</h2>
		<p>Simply drag the staff member up or down and they will be saved in that order.</p>
	<?php $staff = new WP_Query( array( 'post_type' => 'staff-member', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) );
		  if( $staff->have_posts() ) : ?>

		<table class="wp-list-table widefat fixed posts" id="sortable-table">
			<thead>
				<tr>
					<th class="column-order">Order</th>
					<th class="column-photo">Photo</th>
					<th class="column-name">Name</th>
					<th class="column-title">Title</th>
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
					<td class="column-photo"><?php echo get_the_post_thumbnail( $post->ID ); ?></td>
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
					<th class="column-title">Title</th>
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
// Build the 'Templates' Page
//////////////////////////////*/

function staff_member_display_template(){ 

	// Get options for default HTML CSS
	$default_html = get_option('staff_listing_default_html');
	$default_css = get_option('staff_listing_default_css');
	
	// Check Nonce and then update options
	if ( !empty($_POST) && check_admin_referer( 'staff-member-template', 'staff-list-template' ) ) {
		//echo("<script>alert('Test passed');</script>");
		update_option('staff_listing_custom_html', $_POST[ "staff-listing-html"]);
		update_option('staff_listing_custom_css', $_POST[ "staff-listing-css"]);		
		$custom_html = stripslashes_deep(get_option('staff_listing_custom_html'));
		$custom_css = stripslashes_deep(get_option('staff_listing_custom_css'));
	} else {		
		//echo("<script>alert('Test FAILED');</script>");
		$custom_html = stripslashes_deep(get_option('staff_listing_custom_html'));
		$custom_css = stripslashes_deep(get_option('staff_listing_custom_css'));
	}
	
	$output .= '<div class="wrap">';
	$output .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div><h2>Staff Member</h2>';
	$output .= '<h2>Staff Listing Templates</h2>';
    
    $output .= '<div style="padding:15px;">';
    $output .= '<p>Accepted Shortcodes - These <strong>MUST</strong> be used inside the "[staff_loop]" shortcodes:</p>';
    $output .= '<p><xmp>[name] , [photo_url] , [title] , [email] , [phone] , [bio]</xmp></p>';
    $output .= '<p>These will only return string values. If you would like to return pre-formatted headers (using &lt;h3&gt; tags), links, and paragraphs, use these:</p>';
    $output .= '<p><xmp>[name_header] , [title_formatted] , [photo] , [email_link] , [bio_paragraph]</xmp></p>';
    $output .= '<form method="post" action="">';
    $output .= '<h3>Staff Page HTML</h3>';
    $output .= '<textarea name="staff-listing-html" cols="120" rows="16">'.$custom_html.'</textarea>';
    $output .= '<p><input type="submit" value="Save ALL Changes" class="button button-primary button-large"></p><br /><br />';
    $output .= '<h3>Staff Page CSS</h3>';
    $output .= '<textarea name="staff-listing-css" cols="120" rows="16">'.$custom_css.'</textarea>';
    
    $output .= '<p><input type="submit" value="Save ALL Changes" class="button button-primary button-large"></p>';
    $output .= wp_nonce_field('staff-member-template', 'staff-list-template');
    $output .= '</form>';
    $output .= '</div>';
        
    echo $output;

}


?>