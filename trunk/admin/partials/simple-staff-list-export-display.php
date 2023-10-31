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

		// Output the form and export button.
		$output .= '<form id="sslp-export-form" method="post" action="' . admin_url( 'admin-ajax.php' ) . '">';
			$output .= '<input type="hidden" name="action" value="sslp_export_staff_members">';
			$output .= '<input type="hidden" name="sslp_export_nonce" value="' . wp_create_nonce( 'sslp-export-nonce' ) . '">';
			$output .= '<input type="submit" class="button button-primary" value="' . __( 'Export Staff Members', 'simple-staff-list' ) . '">';
		$output .= '</form>';

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
