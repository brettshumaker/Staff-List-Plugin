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

// Get existing options.
$default_slug          = get_option( '_staff_listing_default_slug' );
$default_name_singular = get_option( '_staff_listing_default_name_singular' );
$default_name_plural   = get_option( '_staff_listing_default_name_plural' );

// Check Nonce and then update options.
if ( ! empty( $_POST ) && check_admin_referer( 'staff-member-options', 'staff-list-options' ) ) {
	update_option( '_staff_listing_custom_slug', wp_unique_term_slug( $_POST['staff-listing-slug'], 'staff-member' ) );
	update_option( '_staff_listing_custom_name_singular', $_POST['staff-listing-name-singular'] );
	update_option( '_staff_listing_custom_name_plural', $_POST['staff-listing-name-plural'] );

	$custom_slug          = stripslashes_deep( get_option( '_staff_listing_custom_slug' ) );
	$custom_name_singular = stripslashes_deep( get_option( '_staff_listing_custom_name_singular' ) );
	$custom_name_plural   = stripslashes_deep( get_option( '_staff_listing_custom_name_plural' ) );

	// We've updated the options, send off an AJAX request to flush the rewrite rules.
	// TODO# Should move these options to use the Settings API instead of our own custom thing - or maybe just make it all AJAX - no need for a page refresh.
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var data = {
			'action': 'sslp_flush_rewrite_rules',
		}

		$.post( "<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>", data, function(response){});
	});
	</script>
<?php

} else {
	$custom_slug          = stripslashes_deep( get_option( '_staff_listing_custom_slug' ) );
	$custom_name_singular = stripslashes_deep( get_option( '_staff_listing_custom_name_singular' ) );
	$custom_name_plural   = stripslashes_deep( get_option( '_staff_listing_custom_name_plural' ) );
}


$output      = '<div class="wrap sslp-options">';
	$output .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div>';
	$output .= '<h2>' . __( 'Simple Staff List', 'simple-staff-list' ) . '</h2>';
	$output .= '<h2>' . __( 'Options', 'simple-staff-list' ) . '</h2>';

	$output         .= '<div class="sslp-content sslp-column">';
		$output     .= '<form method="post" action="">';
			$output .= '<fieldset id="staff-listing-field-slug" class="sslp-fieldset">';
			$output .= '<legend class="sslp-field-label">' . __( 'Staff Members URL Slug', 'simple-staff-list' ) . '</legend>';
			$output .= '<input type="text" name="staff-listing-slug" value="' . $custom_slug . '"></fieldset>';
			$output .= '<p>' . __( 'The slug used for building the staff members URL. The current URL is: ', 'simple-staff-list' );
			$output .= site_url( $custom_slug ) . '/';
			$output .= '</p>';
			$output .= '<fieldset id="staff-listing-field-name-plural" class="sslp-fieldset">';
			$output .= '<legend class="sslp-field-label">' . __( 'Staff Member title', 'simple-staff-list' ) . '</legend>';
			$output .= '<input type="text" name="staff-listing-name-plural" value="' . $custom_name_plural . '"></fieldset>';
			$output .= '<p>' . __( 'The title that displays on the Staff Member archive page. Default is "Staff Members"', 'simple-staff-list' ) . '</p>';
			$output .= '<fieldset id="staff-listing-field-name-singular" class="sslp-fieldset">';
			$output .= '<legend class="sslp-field-label">' . __( 'Staff Member singular title', 'simple-staff-list' ) . '</legend>';
			$output .= '<input type="text" name="staff-listing-name-singular" value="' . $custom_name_singular . '"></fieldset>';
			$output .= '<p>' . __( 'The Staff Member taxonomy singular name. No need to change this unless you need to use the singular_name field in your theme. Default is "Staff Member"', 'simple-staff-list' ) . '</p>';

			$output .= '<p><input type="submit" value="' . __( 'Save ALL Changes', 'simple-staff-list' ) . '" class="button button-primary button-large"></p><br /><br />';

			$output .= wp_nonce_field( 'staff-member-options', 'staff-list-options' );
		$output     .= '</form>';
	$output         .= '</div>';
	$output         .= '<div class="sslp-sidebar sslp-column last">';
		// Get the sidebar.
		ob_start();
		require_once 'simple-staff-list-admin-sidebar.php';
		$output .= ob_get_clean();
	$output     .= '</div>';
$output         .= '</div>';

// @codingStandardsIgnoreLine
echo $output;
