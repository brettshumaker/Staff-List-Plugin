<?php
/**
 * The template for displaying all single staff members.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member.php
 *
 * @since      2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public/templates
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

	<?php
	/**
	 * sslp_before_single_staff_member hook.
	 *
	 * @hooked sslp_output_content_wrapper - 10 (outputs opening divs for the content)
	 */
	do_action( 'sslp_before_single_staff_member' );
	?>
	<?php
	while ( have_posts() ) :
		the_post();
?>

	<?php sslp_get_template_part( 'content-staff-member' ); ?>

	<?php endwhile; ?>

	<?php
	/**
	 * sslp_after_single_staff_member hook.
	 *
	 * @hooked sslp_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'sslp_after_single_staff_member' );
	?>

<?php
get_footer();
