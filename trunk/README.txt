=== Simple Staff List ===
Contributors: brettshumaker
Tags: staff list, staff directory, employee list, staff, employee, employees
Requires at least: 3.0
Tested up to: 5.7
Stable tag: 2.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin to build and display a staff listing for your website.

== Description ==

The Simple Staff List plugin allows you to build a staff directory for your website. You get an easy-to-use interface
which allows you to edit the following fields for each staff member: Name, Photo, Position, Email, Phone Number, and Bio.
There's also a drag-and-drop interface to set the order of your staff members.

You'll use the `[simple-staff-list]` shortcode within a page or post to display the full staff listing in the order set
on the "Order" page. You'll be able to customize the information shown for each staff member on your website by editing a simple template. You can add your own custom CSS to style your staff list as well.

Use the [Simple Staff List support section](http://wordpress.org/support/plugin/simple-staff-list "Simple Staff List support") to post any problems/comments!


== Installation ==

1. Upload the `simple-staff-list` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[simple-staff-list]` in a page or post

== Frequently Asked Questions ==

= My Staff List won't show up on my site! =
Alright, here's a few things to try:
* Be sure to make sure you've included the `[simple-staff-list]` shortcode somewhere in a page or post.
* Double check the "Staff Loop Template" on the plugin's "Templates" page. Make sure all of your tags are located between `[staff_loop]` and `[/staff_loop]`.

[Visit the Simple Staff List support section to ask a question!](http://wordpress.org/support/plugin/simple-staff-list "Simple Staff List support")

== Screenshots ==

1. Add New Staff Member screen
2. Add/Edit Staff Member screen
3. Sort Staff Members screen
4. Templates screen 1
5. Templates screen 2

== Changelog ==

= 2.2.1 =
* ADDED: Use the filter `sslp_staff_member_bio_kses_allowed_html` to change which HTML tags are allowed in the Staff Member bio field - it currently defaults to the `post` context. [Learn more](https://developer.wordpress.org/reference/functions/wp_kses/).
* FIXED: Added some data sanitization and escaping
* FIXED: Removed extra spacing on default values
* CLEANED UP: Removed some old debugging code

= 2.2.0 =
* FEATURE: Use the `id` shortcode parameter to show a single Staff Member block
* FEATURE: Use the `sslp_enable_staff_member_archive` filter to disable the `/staff-member` archive page. This filter defaults to `true` - pass `false` to turn off the archive page. You will need to [flush the permalinks](https://typerocket.com/flushing-permalinks-in-wordpress/) for this to take effect.
* FEATURE: Use the `sslp_query_args` filter to change the WP_Query arguments used to query the Staff Members
* ADDED: Staff Group data to the CSV export
* FIXED: Previously, the `[simple-staff-list]` shortcode would request all staff members from the database, this could lead to poor performance on sites with large numbers of staff members, so now the default is 100. This should not affect most users.

= 2.1.1 =
* FIXED: post-thumbnail support for staff-member custom post type.

= 2.1.0 =
* FEATURE: Single Staff Member Templates - Learn more in this [blog post](https://brettshumaker.com/simple-staff-list-single-staff-member-templates "Simple Staff List - Single Staff Member Templates)
* FIXED: Bug with staff member export.
* CLEANED UP: A bit more code formatting

= 2.0.3 =
* FIXED: I got a little carried away with my code clean up and broke some admin pages. Sorry about that. :)

= 2.0.2 =
* CLEANED UP: A bunch of code formatting

= 2.0.1 =
* FIXED: PHP 5.2 incompatibility introduced in v2.0
* FIXED: A few images in admin didn't make it into the last commit somehow

= 2.0 =
* FEATURE: Export Staff Members to CSV
* FEATURE: Specify which image size to use in the loop by using the "image_size" attribute in the "[simple-staff-list]" shortcode: [simple-staff-list image_size=thumbnail]
* Fixed: Rewrite rules are now flushed correctly

= 1.19 =
* BUGFIX: External Custom CSS now loading properly.
* BUGFIX: Multiple shortcodes on page with "groups" attribute now working properly.
* BUGFIX: PHP 5.2 white screen. One of our files (not being used yet, but will be in a future update) had an incompatibility with PHP 5.2. We're now checking the PHP version before loading this file and will address it's PHP 5.2 compatibility in a future release.

= 1.18 =
* Things are happening! Hey guys, I'll be rewriting this plugin with help from some other developers in the [WPNashville Community](http://wpnashville.com). Want to be involved and give some input on the direction of Simple Staff List? Fill out our [survey](http://brettshumaker.com/simple-staff-list-survey/)!
* Fixed: Javascript incompatibility with Yoast SEO plugin
* UPDATED: Moved plugin to class structure based on [The WordPress Plugin Boilerplate](http://wppb.io)

= 1.17 =
* Sorry about that! Pushed this version too soon!

= 1.16 =
* UPDATED: Added translation support. (Thanks to grappler on GitHub)
* UPDATED: Added some template tags to make it easier to access staff member data on a single staff member page (single-staff-member.php) - github pull request #2 from patrickhempel
* UPDATED: Added option to set a custom URL slug and taxonomy name - github pull request #4 from kaymmm
* BUGFIX: Fixed a ton of PHP Notices - github issue #2 from asacolips
* BUGFIX: Menu Icon in WordPress 3.8+

= 1.15 =
* UPDATED: Added Facebook and Twitter fields for Staff Members
* UPDATED: Added support for WYSIWYG editor for Staff Bio.
* UPDATED: Added support for shortcodes within Staff Loop Template and/or Staff Bio field.
* UPDATED: Removed HTML comments that caused some themes to add an extra <p> tag.
* BUGFIX: Added post-thumbnail support for 'staff-member' custom post type. Themes that only added post-thumbnail support for 'posts' weren't able to save featured images.
* BUGFIX: Fixed a rare bug that caused an error on some hosting setups ("Can't Resolve Host" error).

= 1.14 =
* UPDATED: Now I check to make sure the theme supports post thumbnails and display a warning message on the Add/Edit screen
* UPDATED: Minor CSS fix for admin screens

= 1.13 =
* NOTE: If you like using my plugin and want to make sure I can still devote some time to updating it with new features, why not head over to [my website](http://brettshumaker.com) and make a donation? I'd really appreciate it!!
* FEATURE: Users now have the option to write their custom CSS to an external file (on by default). This allows Multisite users to write their custom styles inline.
* UPDATED: You can now use either the group name (i.e. "My Cool Group") OR the group slug (i.e. "my-cool-group") in the Simple Staff List shortcode.
* UPDATED: Passing an empty or non-existent group name no longer results in returning all staff members
* BUGFIX: Fixed bug where the Staff Loop Template and the Staff Page CSS boxes were blank on initial install...for real this time, guys.

= 1.12 =
* UPDATED: Staff Loop Template tags now include `[staff-name-slug]` returns the slug of the staff member to allow specific targeting of staff members.
* BUGFIX: Fixed bug where the Staff Loop Template and the Staff Page CSS boxes were blank on initial install.

= 1.11 =
* BUGFIX: Fixed minor bug which caused groups to not function properly. My bad.

= 1.10 =
* FEATURE: You can now sort your Staff Members into Groups! To display different groups just add a Staff Group in Staff Members > Groups then add `group='YOUR-GROUP-NAME'` to the `[simple-staff-list]` shortcode.
* FEATURE: You can now add a class to the staff-member container! To add a class just add `wrap_class='YOUR-CLASS-NAME'` to the `[simple-staff-list]` shortcode.
* FEATURE: The plugin now saves your custom CSS to an external file in your theme's directory so you can edit it on the "Templates" page OR in your favorite code editor.
* UPDATED: Updated default Staff Loop Template and default styling to make your Staff Directory look nicer out-of-the-box.
* BUGFIX: Trashing a Staff Member no longer causes member information to be lost.
* BUGFIX: Removed the automatic phone number formatting. My apologies to any non-U.S. users.
* Other minor tweaks/performance enhances.

= 1.02 =
* BUGFIX: Corrects a plugin option naming inconsistency which resulted in empty boxes on the Templates page

= 1.01 =
* I incorrectly tagged release version 1.0 and, as a result, not all files were included in version 1.0 of Simple Staff List.
* This version adds those required files back into the package.

= 1.0 =
* Initial Plugin Launch