<?php
/**
 * The template for displaying the single staff member meta.
 *
 * This template can be overridden by copying it to yourtheme/sslp-templates/single-staff-member/staff-meta.php
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
?>
<div class="entry-meta staff-meta">

	<?php
	/**
	 * Hook sslp_single_staff_member_meta
	 *
	 * @hooked sslp_get_single_staff_member_position - 10
	 * @hooked sslp_get_single_staff_member_email - 15
	 * @hooked sslp_get_single_staff_member_phone - 20
	 * @hooked sslp_get_single_staff_member_facebook - 25
	 * @hooked sslp_get_single_staff_member_twitter - 30
	 */
	do_action( 'sslp_single_staff_member_meta' );
	?>

</div>
