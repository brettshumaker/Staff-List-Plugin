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
		$default_template = '
		[staff_loop]
			<img class="staff-member-photo" src="[staff-photo-url]" alt="[staff-name] : [staff-position]">
			<div class="staff-member-info-wrap">
				[staff-name-formatted]
				[staff-position-formatted]
				[staff-bio-formatted]
				[staff-email-link]
			</div>
		[/staff_loop]';

		$default_css = '
			/*  div wrapped around entire staff list  */
			div.staff-member-listing {
			}
			/*  div wrapped around each staff member  */
			div.staff-member {
				padding-bottom: 2em;
				border-bottom: thin dotted #aaa;
			}
			/*  "Even" staff member  */
			div.staff-member.even {
			}
			/*  "Odd" staff member  */
			div.staff-member.odd {
				margin-top: 2em;
			}
			/*  Last staff member  */
			div.staff-member.last {
				padding-bottom: 0;
				border: none;
			}
			/*  Wrap around staff info  */
			.staff-member-info-wrap {
				float: left;
				width: 70%;
				margin-left: 3%;
			}
			/*  [staff-bio-formatted]  */
			div.staff-member-bio {
			}
			/*  p tags within [staff-bio-formatted]  */
			div.staff-member-bio p {
			}
			/*  [staff-photo]  */
			img.staff-member-photo {
				float: left;
			}
			/*  [staff-email-link]  */
			.staff-member-email {
			}
			/*  [staff-name-formatted]  */
			div.staff-member-listing h3.staff-member-name {
				margin: 0;
			}
			/*  [staff-position-formatted]  */
			div.staff-member-listing h4.staff-member-position {
				margin: 0;
				font-style: italic;
			}
			/* Clearfix for div.staff-member */
			div.staff-member:after {
				content: "";
				display: block;
				clear: both;
			}
			/* Clearfix for <= IE7 */
			* html div.staff-member { height: 1%; }
			div.staff-member { display: block; }
		';

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

		$filename = get_stylesheet_directory() . '/simple-staff-list-custom.css';

		if ( ! get_option( '_staff_listing_custom_css' ) && ! file_exists( $filename ) ) {
			update_option( '_staff_listing_custom_css', get_option( '_staff_listing_default_css' ) );

			// Save custom css to a file in current theme directory.
			file_put_contents( $filename, get_option( '_staff_listing_default_css' ) );
		} elseif ( file_exists( $filename ) ) {
			$custom_css = file_get_contents( $filename );
			update_option( '_staff_listing_custom_css', $custom_css );
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
