<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.0.0
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/admin/partials
 */
?>

<div class="wrap sslp-usage">
	<div id="icon-edit" class="icon32 icon32-posts-staff-member"><br></div>
	<h2><?php _e( 'Simple Staff List', 'simple-staff-list' ); ?></h2>

	<h2><?php _e( 'Usage', 'simple-staff-list' ); ?></h2>

	<p><?php _e( 'The Simple Staff List plugin makes it easy to create and display a staff directory on your website. You can create your own <a href="edit.php?post_type=staff-member&page=staff-member-template" title="Edit the Simple Staff List template.">template</a> for displaying staff information as well as <a href="edit.php?post_type=staff-member&page=staff-member-usage" title="Edit Custom CSS for Simple Staff List">add custom css</a> styling to make your staff directory look great.',
			'simple-staff-list' ); ?></p>

	<h3><?php _e( 'Shortcode', 'simple-staff-list' ); ?></h3>
	<table>
		<tbody>
		<tr>
			<td width="280px"><code>[simple-staff-list]</code></td>
			<td><?php _e( 'This is the most basic usage of Simple Staff List. Displays all Staff Members on post or page.',
					'simple-staff-list' ); ?></td>
		</tr>
		<tr>
			<td><code>[simple-staff-list <strong>group="Robots"</strong>]</code></td>
			<td><?php _e( 'This displays all Staff Members from the group "Robots" sorted by order on the "Order" page. This will also add a class of "Robots" to the outer Staff List container for styling purposes.',
					'simple-staff-list' ); ?></td>
		</tr>
		<tr>
			<td><code>[simple-staff-list <strong>wrap_class="clearfix"</strong>]</code></td>
			<td><?php _e( 'This adds a class to the inner Staff Member wrap.', 'simple-staff-list' ); ?></td>
		</tr>
		<tr>
			<td><code>[simple-staff-list <strong>order="ASC"</strong>]</code></td>
			<td><?php _e( 'This displays Staff Members sorted by ascending or descending order according to the "Order" page. You may use "ASC" or "DESC" but the default is "ASC"',
					'simple-staff-list' ); ?></td>
		</tr>
		</tbody>
	</table>

	<p><?php _e( 'To display your Staff List just use the shortcode <code>[simple-staff-list]</code> in any page or post. This will output all staff members according to the template options set <a href="edit.php?post_type=staff-member&page=staff-member-template" title="Edit the Simple Staff List template.">here',
			'simple-staff-list' ); ?></a>.</p>
</div>
