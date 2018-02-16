<?php
/**
 * Simple Staff List Template Functions
 *
 * Templating functions.
 *
 * @since      2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */

if ( ! function_exists( 'sslp_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 * @since    2.1
	 */
	function sslp_output_content_wrapper() {
		sslp_get_template_part( 'global/wrapper-start' );
	}
}

if ( ! function_exists( 'sslp_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 * @since    2.1
	 */
	function sslp_output_content_wrapper_end() {
		sslp_get_template_part( 'global/wrapper-end' );
	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_bio' ) ) {

	/**
	 * Get the single staff member's bio.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_bio() {

		sslp_get_template_part( 'single-staff-member/staff-bio' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_image' ) ) {

	/**
	 * Get the single staff member's image.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_image() {

		sslp_get_template_part( 'single-staff-member/staff-image' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_name' ) ) {

	/**
	 * Get the single staff member's name.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_name() {

		sslp_get_template_part( 'single-staff-member/staff-name' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_meta' ) ) {

	/**
	 * Get the single staff member's name.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_meta() {

		sslp_get_template_part( 'single-staff-member/staff-meta' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_position' ) ) {

	/**
	 * Get the single staff member's position.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_position() {

		sslp_get_template_part( 'single-staff-member/staff-position' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_email' ) ) {

	/**
	 * Get the single staff member's email.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_email() {

		sslp_get_template_part( 'single-staff-member/staff-email' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_phone' ) ) {

	/**
	 * Get the single staff member's phone.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_phone() {

		sslp_get_template_part( 'single-staff-member/staff-phone' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_facebook' ) ) {

	/**
	 * Get the single staff member's facebook.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_facebook() {

		sslp_get_template_part( 'single-staff-member/staff-facebook' );

	}
}

if ( ! function_exists( 'sslp_get_single_staff_member_twitter' ) ) {

	/**
	 * Get the single staff member's twitter.
	 *
	 * @since    2.1
	 */
	function sslp_get_single_staff_member_twitter() {

		sslp_get_template_part( 'single-staff-member/staff-twitter' );

	}
}
