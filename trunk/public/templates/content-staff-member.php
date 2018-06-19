<?php
/**
 * The template for displaying the single staff member content.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/content-staff-member.php
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
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Hook sslp_before_single_staff_member_header.
	 */
	do_action( 'sslp_before_single_staff_member_header' );
	?>
	<header class="staff-header">
		<?php
		/**
		 * Hook sslp_single_staff_member_header.
		 *
		 * @hooked sslp_get_single_staff_member_image - 10
		 * @hooked sslp_get_single_staff_member_name  - 15
		 * @hooked sslp_get_single_staff_member_meta  - 20
		 */
		do_action( 'sslp_single_staff_member_header' );
		?>
	</header>
	<?php
	/**
	 * Hook sslp_after_single_staff_member_header.
	 */
	do_action( 'sslp_after_single_staff_member_header' );
	?>
	<div class="staff-content">
		<?php
		/**
		 * Hook sslp_single_staff_member_content.
		 *
		 * @hooked sslp_get_single_staff_member_bio - 10
		 */
		do_action( 'sslp_single_staff_member_content' );
		?>
	</div>
	<?php
	/**
	 * Hook sslp_after_single_staff_member_content.
	 */
	do_action( 'sslp_after_single_staff_member_content' );
	?>
</article>
