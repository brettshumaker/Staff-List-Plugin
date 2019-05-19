<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// @TODO: Need to account for multisite and other things in the comments above.
delete_option( '_staff_listing_default_tags' );
delete_option( '_staff_listing_default_tag_string' );
delete_option( '_staff_listing_default_formatted_tags' );
delete_option( '_staff_listing_default_formatted_tag_string' );
delete_option( '_staff_listing_default_html' );
delete_option( '_staff_listing_default_css' );
delete_option( '_staff_listing_custom_html' );
delete_option( '_staff_listing_custom_css' );
delete_option( '_staff_listing_custom_name_plural' );
delete_option( '_staff_listing_custom_name_singular' );
delete_option( '_staff_listing_custom_slug' );
delete_option( '_staff_listing_default_name_plural' );
delete_option( '_staff_listing_default_name_singular' );
delete_option( '_staff_listing_default_slug' );
delete_option( '_staff_listing_write_external_css' );
delete_option( '_simple_staff_list_version' );
