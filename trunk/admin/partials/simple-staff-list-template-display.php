<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/admin/partials
 */

// Get options for default HTML CSS.
	$default_html                 = get_option( '_staff_listing_default_html' );
	$default_css                  = get_option( '_staff_listing_default_css' );
	$default_tag_string           = get_option( '_staff_listing_default_tag_string' );
	$default_formatted_tag_string = get_option( '_staff_listing_default_formatted_tag_string' );
	$default_tags                 = get_option( '_staff_listing_default_tags' );
	$default_formatted_tags       = get_option( '_staff_listing_default_formatted_tags' );
	$write_external_css           = get_option( '_staff_listing_write_external_css' );

	$default_tag_ul = '<ul class="sslp-tag-list">';

foreach ( $default_tags as $tag ) {
	$default_tag_ul .= '<li>' . $tag . '</li>';
}

	$default_tag_ul .= '</ul>';

	$default_formatted_tag_ul = '<ul class="sslp-tag-list">';

foreach ( $default_formatted_tags as $tag ) {
	$default_formatted_tag_ul .= '<li>' . $tag . '</li>';
}

	$default_formatted_tag_ul .= '</ul>';


	// Check Nonce and then update options.
if ( ! empty( $_POST ) && check_admin_referer( 'staff-member-template', 'staff-list-template' ) ) {
	update_option( '_staff_listing_custom_html', $_POST['staff-listing-html'] );
	update_option( '_staff_listing_custom_css', $_POST['staff-listing-css'] );

	$custom_html = stripslashes_deep( get_option( '_staff_listing_custom_html' ) );
	$custom_css  = stripslashes_deep( get_option( '_staff_listing_custom_css' ) );

	if ( ! isset( $_POST['write-external-css'] ) ) {
		update_option( '_staff_listing_write_external_css', 'no' );
		$write_external_css = 'no';
	} elseif ( isset( $_POST['write-external-css'] ) ) {
		update_option( '_staff_listing_write_external_css', $_POST['write-external-css'] );
		$write_external_css = 'yes';

		// User wants to write to external CSS file, do it.
		$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';
		file_put_contents( $filename, $custom_css );
	}
} else {
	$custom_html = stripslashes_deep( get_option( '_staff_listing_custom_html' ) );

	if ( 'yes' === $write_external_css ) {

		$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';

		if ( file_exists( $filename ) ) {
			$custom_css_response = wp_remote_get( $filename );
			$custom_css          = wp_remote_retrieve_body( $custom_css_response );
			update_option( '_staff_listing_custom_css', $custom_css );
		} else {
			$custom_css = stripslashes_deep( get_option( '_staff_listing_default_css' ) );
			update_option( '_staff_listing_custom_css', $custom_css );
			file_put_contents( $filename, $custom_css );
		}
	} else {
		$custom_css = stripslashes_deep( get_option( '_staff_listing_custom_css' ) );
	}
}

if ( 'yes' === $write_external_css ) {
	$ext_css_check = 'checked';
} else {
	$ext_css_check = '';
}

	$output          = '<div class="wrap sslp-template">';
		$output     .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div><h2>' . __( 'Simple Staff List', 'simple-staff-list' ) . '</h2>';
		$output     .= '<div class="sslp-content sslp-column">';
			$output .= '<h2>Templates</h2>';
			$output .= '<h4>' . __( 'Accepted Template Tags', 'simple-staff-list' ) . ' <strong>(' . __( 'UNFORMATTED', 'simple-staff-list' ) . ')</strong></h4>';
			$output .= $default_tag_ul;

			$output .= '<br />';

			$output .= '<h4>' . __( 'Accepted Template Tags', 'simple-staff-list' ) . ' <strong>(' . __( 'FORMATTED', 'simple-staff-list' ) . ')</strong></h4>';
			$output .= $default_formatted_tag_ul;

			$output .= '<br />';

			$output .= '<p>' . __( 'These <strong>MUST</strong> be used inside the <code>[staff_loop]</code> wrapper. The unformatted tags will return plain strings so you will want to wrap them in your own HTML. The <code>[staff_loop]</code> can accept any HTML so be careful when adding your own HTML code. The formatted tags will return data wrapped in HTML elements. For example, <code>[staff-name-formatted]</code> returns <code>&lt;h3&gt;STAFF-NAME&lt;/h3&gt;</code>, and <code>[staff-email-link]</code> returns <code>&lt;a href="mailto:STAFF-EMAIL" title="Email STAFF-NAME"&gt;STAFF-EMAIL&lt;/a&gt;</code>.', 'simple-staff-list' ) . '</p>';
			$output .= '<p>' . __( '**Note: All emails are obfuscated using the <a href="http://codex.wordpress.org/Function_Reference/antispambot" target="_blank" title="WordPress email obfuscation function: antispambot()">antispambot() WordPress function</a>.', 'simple-staff-list' ) . '</p>';
			$output .= '<br />';

			$output .= '<form method="post" action="">';
			$output .= '<h3>' . __( 'Staff Loop Template', 'simple-staff-list' ) . '</h3>';

			$output .= '<div class="default-html">
		    				<h4 class="heading button-secondary">' . __( 'View Default Template', 'simple-staff-list' ) . '</h4>
		    				<div class="content">
		    					<pre>' . htmlspecialchars( stripslashes_deep( $default_html ) ) . '</pre>
		    				</div>
		    			</div><br />';

			$output .= '<textarea name="staff-listing-html" cols="120" rows="16">' . $custom_html . '</textarea>';
			$output .= '<p><input type="submit" value="' . __( 'Save ALL Changes', 'simple-staff-list' ) . '" class="button button-primary button-large"></p><br /><br />';

			$output .= '<h3>' . __( 'Staff Page CSS', 'simple-staff-list' ) . '</h3>';

			$output .= '<p><input type="checkbox" name="write-external-css" id="write-external-css" value="yes" ' . $ext_css_check . ' /><label for="write-external-css"> ' . __( 'Write to external CSS file? (Leave unchecked for WP Multisite.)', 'simple-staff-list' ) . '</label>';

			$output .= '<div class="default-css">
		    				<h4 class="heading button-secondary">' . __( 'View Default CSS', 'simple-staff-list' ) . '</h4>
		    				<div class="content">
		    					<pre>' . htmlspecialchars( stripslashes_deep( $default_css ) ) . '</pre>
		    				</div>
		    			</div><br />';

			$output .= '<p style="margin-top:0;">' . __( 'Add your custom CSS below to style the output of your staff list. I\'ve included selectors for everything output by the plugin.', 'simple-staff-list' ) . '</p>';
			$output .= '<textarea name="staff-listing-css" cols="120" rows="16">' . $custom_css . '</textarea>';

			$output .= '<p><input type="submit" value="' . __( 'Save ALL Changes', 'simple-staff-list' ) . '" class="button button-primary button-large"></p>';
			$output .= wp_nonce_field( 'staff-member-template', 'staff-list-template' );
			$output .= '</form>';
		$output     .= '</div>';
		$output     .= '<div class="sslp-sidebar sslp-column last">';
			// Get the sidebar.
			ob_start();
			require_once 'simple-staff-list-admin-sidebar.php';
			$output .= ob_get_clean();
		$output     .= '</div>';
	$output         .= '</div>';

	echo $output;
