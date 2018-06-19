<?php
/**
 * The template for displaying the single staff member title/position.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-position.php
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

$title = get_post_meta( $post->ID, '_staff_member_title', true );
if ( '' !== $title ) {
	echo '<span class="title">' . esc_html( $title ) . '</span>';
}
