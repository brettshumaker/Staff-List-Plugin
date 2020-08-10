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
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$image_obj = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium', false );
$src       = $image_obj[0];
?>
<img class="staff-member-photo" src="<?php echo esc_url( $src ); ?>" alt = "<?php echo esc_attr( get_the_title() ); ?>">
