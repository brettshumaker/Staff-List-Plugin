<?php
/**
 * Simple Staff List Template Hooks
 *
 * These are the template hooks you are looking for.
 *
 * @since      2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */

/**
 * Content wrappers.
 */
add_action( 'sslp_before_single_staff_member', 'sslp_output_content_wrapper', 10 );
add_action( 'sslp_after_single_staff_member', 'sslp_output_content_wrapper_end', 10 );

/**
 * Single staff member header
 */
add_action( 'sslp_single_staff_member_header', 'sslp_get_single_staff_member_image', 10 );
add_action( 'sslp_single_staff_member_header', 'sslp_get_single_staff_member_name', 15 );
add_action( 'sslp_single_staff_member_header', 'sslp_get_single_staff_member_meta', 20 );

/**
 * Single staff member meta
 */
add_action( 'sslp_single_staff_member_meta', 'sslp_get_single_staff_member_position', 10 );
add_action( 'sslp_single_staff_member_meta', 'sslp_get_single_staff_member_email', 15 );
add_action( 'sslp_single_staff_member_meta', 'sslp_get_single_staff_member_phone', 20 );
add_action( 'sslp_single_staff_member_meta', 'sslp_get_single_staff_member_facebook', 25 );
add_action( 'sslp_single_staff_member_meta', 'sslp_get_single_staff_member_twitter', 30 );

/**
 * Single staff member content area
 */
add_action( 'sslp_single_staff_member_content', 'sslp_get_single_staff_member_bio', 10 );
