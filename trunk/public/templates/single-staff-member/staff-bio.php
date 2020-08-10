<?php
/**
 * The template for displaying the single staff member bio.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-bio.php
 *
 * @since 2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public/templates
 * @version    1.0
 */

$bio = get_post_meta( $post->ID, '_staff_member_bio', true );

echo wp_kses( wpautop( $bio ), apply_filters( 'sslp_staff_member_bio_kses_allowed_html', 'post' ) );
