<?php
/**
 * Simple Staff List Core Functions
 *
 * General core functions available everywhere.
 *
 * @since      2.1
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */

/**
 * Add https:// to a URL entered without a scheme, such as "facebook.com/name".
 *
 * esc_url() and esc_url_raw() add http:// to these instead. (WordPress 6.9 can add https://,
 * but the plugin supports older versions.) URLs that already have a scheme, and relative
 * URLs, are returned unchanged.
 *
 * @since 2.2.7
 *
 * @param string $url The URL.
 *
 * @return string
 */
function sslp_add_url_scheme( $url ) {

	if ( ! is_string( $url ) ) {
		return '';
	}

	$url = trim( $url );

	if ( '' === $url || preg_match( '#^[a-z][a-z0-9+.\-]*:#i', $url ) || in_array( $url[0], array( '/', '#', '?' ), true ) ) {
		return $url;
	}

	return 'https://' . $url;

}

function sslp_get_template_part( $slug = '' ) {

	$template = '';

	// Look in yourtheme/sslp-templates/slug.php.
	if ( '' !== $slug ) {
		$template = locate_template( array( "sslp-templates/{$slug}.php" ) );
	}

	// Look in yourtheme/slug.php.
	if ( ! $template && '' !== $slug ) {
		$template = locate_template( array( "{$slug}.php" ) );
	}

	// Get default slug.php.
	if ( ! $template && '' !== $slug && file_exists( STAFFLIST_PATH . "/public/templates/{$slug}.php" ) ) {
		$template = STAFFLIST_PATH . "/public/templates/{$slug}.php";
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'sslp_get_template_part', $template, $slug );

	if ( $template ) {
		load_template( $template, false );
	}

}

/**
 * This is loading the single-staff-member.php template from the plugin's templates folder
 * UNLESS single-staff-member.php is present in the stylesheet directory/sslp-templates.
 * theme root > theme/sslp-templates > plugin/public/templates
 */
function sslp_template_include( $template ) {

	if ( ! is_singular( 'staff-member' ) ) {
		return $template;
	}

	// Check in the folder we've told users to add the template to.
	$template = locate_template( array( '/sslp-templates/single-staff-member.php' ) );

	// If it's not found, look in the theme root.
	if ( ! $template ) {
		$template = locate_template( array( 'single-staff-member.php' ) );
	}

	// Still not found? Just use the one we included in the plugin.
	if ( ! $template ) {
		$template = STAFFLIST_PATH . 'public/templates/single-staff-member.php';
	}

	return $template;

}
add_filter( 'template_include', 'sslp_template_include' );
