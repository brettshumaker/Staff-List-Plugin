<?php
/**
 * Fired during plugin activation
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.17
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/includes
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */
class Simple_Staff_List_Activator {

	/**
	 * Runs on plugin activation.
	 *
	 * Sets up initial plugin option contents.
	 *
	 * @since    1.17
	 *
	 * @param    bool $is_forced Whether or not the "activation" function was forced to run.
	 */
	public static function activate( $is_forced = false ) {
		// Every earlier activation stored the default template, so its absence means a new install.
		$is_new_install = false === get_option( '_staff_listing_default_html' );

		$default_template = "[staff_loop]\n    <img class=\"staff-member-photo\" src=\"[staff-photo-url]\" alt=\"[staff-name] : [staff-position]\">\n    <div class=\"staff-member-info-wrap\">\n        [staff-name-formatted]\n        [staff-position-formatted]\n        [staff-bio-formatted]\n        [staff-email-link]\n    </div>\n[/staff_loop]";

		$default_css = "/*  div wrapped around entire staff list  */\n    div.staff-member-listing {\n}\n/*  div wrapped around each staff member  */\ndiv.staff-member {\n    padding-bottom: 2em;\n    border-bottom: thin dotted #aaa;\n}\n/*  Even staff member  */\ndiv.staff-member.even {\n}\n/*  Odd staff member  */\ndiv.staff-member.odd {\n    margin-top: 2em;\n}\n/*  Last staff member  */\ndiv.staff-member.last {\n    padding-bottom: 0;\n    border: none;\n}\n/*  Wrap around staff info  */\n.staff-member-info-wrap {\n    float: left;\n    width: 70%;\n    margin-left: 3%;\n}\n/*  [staff-bio-formatted]  */\ndiv.staff-member-bio {\n}\n/*  p tags within [staff-bio-formatted]  */\ndiv.staff-member-bio p {\n}\n/*  [staff-photo]  */\nimg.staff-member-photo {\n    float: left;\n}\n/*  [staff-email-link]  */\n.staff-member-email {\n}\n/*  [staff-name-formatted]  */\ndiv.staff-member-listing h3.staff-member-name {\n    margin: 0;\n}\n/*  [staff-position-formatted]  */\ndiv.staff-member-listing h4.staff-member-position {\n    margin: 0;\n    font-style: italic;\n}\n/* Clearfix for div.staff-member */\ndiv.staff-member:after {\n    content: \"\";\n    display: block;\n    clear: both;\n}\n/* Clearfix for <= IE7 */\n* html div.staff-member { height: 1%; }\ndiv.staff-member { display: block; }\n";

		$default_tags       = array(
			'[staff-name]',
			'[staff-name-slug]',
			'[staff-photo-url]',
			'[staff-position]',
			'[staff-email]',
			'[staff-phone]',
			'[staff-bio]',
			'[staff-facebook]',
			'[staff-twitter]',
		);
		$default_tag_string = implode( ', ', $default_tags );

		$default_formatted_tags       = array(
			'[staff-name-formatted]',
			'[staff-position-formatted]',
			'[staff-photo]',
			'[staff-email-link]',
			'[staff-bio-formatted]',
		);
		$default_formatted_tag_string = implode( ', ', $default_formatted_tags );

		$default_slug          = 'staff-members';
		$default_name_singular = _x( 'Staff Member', 'post type singular name', 'simple-staff-list' );
		$default_name_plural   = _x( 'Staff Members', 'post type general name', 'simple-staff-list' );
		update_option( '_staff_listing_default_tags', $default_tags );
		update_option( '_staff_listing_default_tag_string', $default_tag_string );
		update_option( '_staff_listing_default_formatted_tags', $default_formatted_tags );
		update_option( '_staff_listing_default_formatted_tag_string', $default_formatted_tag_string );
		update_option( '_staff_listing_default_html', $default_template );
		update_option( '_staff_listing_default_css', $default_css );
		update_option( '_staff_listing_default_slug', $default_slug );
		update_option( '_staff_listing_default_name_singular', $default_name_singular );
		update_option( '_staff_listing_default_name_plural', $default_name_plural );

		if ( ! get_option( '_staff_listing_custom_html' ) ) {
			update_option( '_staff_listing_custom_html', $default_template );
		}

		$filename         = get_stylesheet_directory() . '/simple-staff-list-custom.css';
		$saved_css        = get_option( '_staff_listing_custom_css' );
		$use_external_css = 'yes' === get_option( '_staff_listing_write_external_css' );

		// With external CSS on, the site loads the theme file, so the option follows the file.
		// Otherwise the file is only used to fill an empty option; it never replaces saved CSS.
		if ( file_exists( $filename ) && ( $use_external_css || ! $saved_css ) ) {
			$file_css = file_get_contents( $filename ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
			if ( false !== $file_css ) {
				// Stored slashed, the same way the Templates page saves it, because it's read with stripslashes_deep().
				update_option( '_staff_listing_custom_css', addslashes( $file_css ) );
			}
		} elseif ( ! $saved_css ) {
			update_option( '_staff_listing_custom_css', get_option( '_staff_listing_default_css' ) );
		}

		// New installs output the list CSS inline. Existing sites keep their setting: one that was
		// never saved has meant "no list CSS" for years, and turning it on would change their layout.
		if ( $is_new_install ) {
			add_option( '_staff_listing_write_external_css', 'no' );
		}

		if ( ! get_option( '_staff_listing_custom_slug' ) ) {
			update_option( '_staff_listing_custom_slug', $default_slug );
		}
		if ( ! get_option( '_staff_listing_custom_name_singular' ) ) {
			update_option( '_staff_listing_custom_name_singular', $default_name_singular );
		}
		if ( ! get_option( '_staff_listing_custom_name_plural' ) ) {
			update_option( '_staff_listing_custom_name_plural', $default_name_plural );
		}

		// Maybe add flag to signal the need to flush the rewrite rules.
		if ( ! get_option( '_staff_listing_flush_rewrite_rules_flag' ) ) {

			add_option( '_staff_listing_flush_rewrite_rules_flag', true );

		}
	}

}
