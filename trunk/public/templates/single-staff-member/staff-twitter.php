<?php
/**
 * The template for displaying the single staff member twitter link.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-twitter.php
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

$twitter = get_post_meta( $post->ID, '_staff_member_tw', true );
if ( '' !== $twitter ) {

	$icon = '';
	$svg  = wp_remote_get( STAFFLIST_URI . 'public/svg/twitter.svg' );
	if ( '404' !== $svg['response']['code'] ) {
		$icon = $svg['body'];
	}

	echo '<span class="twitter"><a class="staff-member-twitter" href="https://twitter.com/' . esc_attr( $twitter ) . '" title="Follow ' . esc_attr( get_the_title() ) . ' on Twitter">' . $icon . '</a></span>';

}
