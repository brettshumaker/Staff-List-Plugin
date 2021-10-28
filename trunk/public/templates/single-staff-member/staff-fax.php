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

$fax = get_post_meta( $post->ID, '_staff_member_fax', true );
if ( '' !== $fax ) {
	
	echo '<span class="fax">Fax: ' . esc_html( $fax ) . '</span>';

}
