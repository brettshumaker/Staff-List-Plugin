<?php
/**
 * The template for displaying the single staff member facebook link.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-facebook.php
 *
 * @since 2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public/templates
 * @version    1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$facebook = get_post_meta( $post->ID, '_staff_member_fb', true );
if ( '' !== $facebook ) {

	$icon = '';
	$svg_file = STAFFLIST_PATH . 'public/svg/facebook.svg';
	if ( is_readable( $svg_file ) ) {
		$icon = file_get_contents( $svg_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local plugin file, not a remote URL.
	}

	echo '<span class="facebook"><a class="staff-member-facebook" href="' . esc_url( sslp_add_url_scheme( $facebook ) ) . '" title="Find ' . esc_attr( get_the_title() ) . ' on Facebook">' . $icon . '</a></span>';

}
