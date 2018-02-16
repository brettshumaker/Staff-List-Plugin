<?php
/**
 * Provide a admin area view for the export page
 *
 * This file is used to markup the admin-facing export page.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.20
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/admin/partials
 */

$output          = '<div class="wrap sslp-template">';
	$output     .= '<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div><h2>' . __( 'Simple Staff List', 'simple-staff-list' ) . '</h2>';
	$output     .= '<div class="sslp-content sslp-column">';
		$output .= '<h2>' . __( 'Export', 'simple-staff-list' ) . '</h2>';
		$output .= '<p>' . __( 'Click the export button below to generate a CSV download of your staff member data.', 'simple-staff-list' ) . '</p>';

		// Check for file access.
		$access_type = get_filesystem_method();
if ( 'direct' !== $access_type ) {
	$output .= '<p>' . __( "After clicking 'Export Staff Members' a Download button will appear.", 'simple-staff-list' ) . '</p>';
}

		$output .= '<a href="#" class="button button-primary export-button">' . __( 'Export Staff Members', 'simple-staff-list' ) . '</a>';
	$output     .= '</div>';
	$output     .= '<div class="sslp-sidebar sslp-column last">';
		// Get the sidebar.
		ob_start();
		require_once 'simple-staff-list-admin-sidebar.php';
		$output .= ob_get_clean();
	$output     .= '</div>';
$output         .= '</div>';

// @codingStandardsIgnoreLine
echo $output;
