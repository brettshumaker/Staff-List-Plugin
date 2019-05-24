<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/admin/partials
 */

?>
<div class="wrap sslp-usage">
	<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div>
	<h2><?php esc_html_e( 'Simple Staff List', 'simple-staff-list' ); ?></h2>

	<h2><?php esc_html_e( 'Usage', 'simple-staff-list' ); ?></h2>
	<div class="sslp-content sslp-column">
		<?php
		$template_url = 'edit.php?post_type=staff-member&page=staff-member-template';
		$usage_url    = 'edit.php?post_type=staff-member&page=staff-member-usage';
		?>

		<p>
			<?php
			printf(
				// Translators: The placeholders below are for links.
				esc_html__( 'The Simple Staff List plugin makes it easy to create and display a staff directory on your website. You can create your own %1$s for displaying staff information as well as %2$s styling to make your staff directory look great.', 'simple-staff-list' ),
				sprintf(
					'<a href="%s" title="%s">%s</a>',
					esc_url( $template_url ),
					esc_attr__( 'Edit the Simple Staff List template.', 'simple-staff-list' ),
					esc_html__( 'templates', 'simple-staff-list' )
				),
				sprintf(
					'<a href="%s" title="%s">%s</a>',
					esc_url( $usage_url ),
					esc_attr__( 'Edit Custom CSS for Simple Staff List', 'simple-staff-list' ),
					esc_html__( 'add custom css', 'simple-staff-list' )
				)
			);
			?>
		</p>
		<h3><?php esc_html_e( 'Shortcode', 'simple-staff-list' ); ?></h3>
		<h4><code>[simple-staff-list]</code></h4>
		<p><?php esc_html_e( 'This is the most basic usage of Simple Staff List. Displays all Staff Members on post or page.', 'simple-staff-list' ); ?></p>
		<h4><code>[simple-staff-list group="Robots"]</code></h4>
		<p><?php esc_html_e( 'This displays all Staff Members from the group "Robots" sorted by order on the "Order" page. This will also add a class of "Robots" to the outer Staff List container for styling purposes.', 'simple-staff-list' ); ?></p>
		<h4><code>[simple-staff-list id=12]</code></h4>
		<p><?php esc_html_e( 'This will display the Staff Member ID "12". The Staff Member ID can be found on the "All Staff Members" page.', 'simple-staff-list' ); ?></p>
		<h4><code>[simple-staff-list wrap_class="clearfix"]</code></h4>
		<p><?php esc_html_e( 'This adds a class to the inner Staff Member wrap.', 'simple-staff-list' ); ?></p>
		<h4><code>[simple-staff-list order="ASC"]</code></h4>
		<p><?php esc_html_e( 'This displays Staff Members sorted by ascending or descending order according to the "Order" page. You may use "ASC" or "DESC" but the default is "ASC"', 'simple-staff-list' ); ?></p>
		<h4><code>[simple-staff-list image_size=thumbnail]</code></h4>
		<p><?php esc_html_e( 'This displays the Staff Members\' "thumbnail" size image instead of the "full" size image. You can use any image size registered with WordPress in place of "thumbnail."', 'simple-staff-list' ); ?></p>
		<p>
			<?php
			printf(
				// Translators: The placeholders below are for links.
				esc_html__( 'To display your Staff List just use the shortcode <code>[simple-staff-list]</code> in any page or post. This will output all staff members according to the template options set %1$s.', 'simple-staff-list' ),
				sprintf(
					'<a href="%s" title="%s">%s</a>',
					esc_url( $template_url ),
					esc_attr__( 'Edit the Simple Staff List template.', 'simple-staff-list' ),
					esc_html__( 'here', 'simple-staff-list' )
				),
				''
			);
			?>
		</p>
	</div>
	<div class="sslp-sidebar sslp-column last">
		<?php require_once 'simple-staff-list-admin-sidebar.php'; ?>
	</div>
</div>
