<?php
/**
 * The template for displaying the single staff member email.
 * 
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-email.php
 * 
 * @since 2.1
 * 
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public/templates
 * @version    1.0
 */

$email = get_post_meta( $post->ID, '_staff_member_email', true );
if ( '' !== $email ) {
    echo '<span class="email"><a class="staff-member-email" href="mailto:' . esc_attr( antispambot( $email ) ) . '" title="Email ' . esc_attr( get_the_title() ) . '">' . file_get_contents( STAFFLIST_URI . 'public/svg/envelope.svg?v=' . date('U') ) . '</a></span>';
}