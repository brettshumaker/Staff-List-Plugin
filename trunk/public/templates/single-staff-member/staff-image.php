<?php
/**
 * The template for displaying the single staff member image.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-image.php
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

// Staff members without a photo get no image, rather than an empty <img src="">.
if ( ! has_post_thumbnail() ) {
	return;
}

// the_post_thumbnail() adds width, height and srcset, so browsers can reserve space and pick a suitable file.
the_post_thumbnail(
	'medium',
	array(
		'class' => 'staff-member-photo',
		'alt'   => get_the_title(),
	)
);
