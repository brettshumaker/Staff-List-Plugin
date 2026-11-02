<?php
/**
 * The template for displaying the single staff member phone number.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-phone.php
 *
 * @since 2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public/templates
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$phone = get_post_meta( $post->ID, '_staff_member_phone', true );
if ( '' !== $phone ) {

	$icon = '';
	$svg_file = STAFFLIST_PATH . 'public/svg/phone.svg';
	if ( is_readable( $svg_file ) ) {
		$icon = file_get_contents( $svg_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local plugin file, not a remote URL.
	}

	echo '<span class="phone"><a class="staff-member-phone" href="tel:' . esc_attr( $phone ) . '">' . $icon . '</a></span>';

}
