<?php
/**
 * Save [simple-staff-list] output for a set of cases, so two plugin versions can be diffed.
 *
 * Usage (from the test site's folder):
 *     wp eval-file /path/to/tools/snapshot.php <output-dir> [staff-id]
 *
 * Writes one HTML file per case: both CSS modes (inline "no" and external "yes"), two templates
 * (the default and one with every tag), and each shortcode attribute. The saved template and CSS
 * mode are restored afterwards.
 *
 * [staff-id] is used for the id="" case. It defaults to the oldest published staff member.
 *
 * Not part of the plugin; it isn't deployed (only trunk/ is).
 *
 * @package Simple_Staff_List
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

if ( empty( $args[0] ) ) {
	WP_CLI::error( 'Usage: wp eval-file snapshot.php <output-dir> [staff-id]' );
}

$out = rtrim( $args[0], '/' );
wp_mkdir_p( $out );

if ( ! empty( $args[1] ) ) {
	$staff_id = (int) $args[1];
} else {
	$oldest   = get_posts(
		array(
			'post_type'      => 'staff-member',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);
	$staff_id = $oldest ? (int) $oldest[0] : 0;
}

// Group names and slugs match the test site's groups: "Leadership" and "Support Team" (slug "support").
$cases = array(
	'default'       => '[simple-staff-list]',
	'group-name'    => '[simple-staff-list group="Leadership"]',
	'group-name-lc' => '[simple-staff-list group="support team"]',
	'group-slug'    => '[simple-staff-list group="support"]',
	'id'            => '[simple-staff-list id=' . $staff_id . ']',
	'order-desc'    => '[simple-staff-list order="DESC"]',
	'wrap-class'    => '[simple-staff-list wrap_class="my-class"]',
	'image-size'    => '[simple-staff-list image_size="thumbnail"]',
);

$templates = array(
	'tpl-default' => get_option( '_staff_listing_default_html' ),
	'tpl-alltags' => '[staff_loop]<div>[staff-name]|[staff-name-slug]|[staff-photo-url]|[staff-position]|[staff-email]|[staff-phone]|[staff-bio]|[staff-facebook]|[staff-twitter]</div>[staff-name-formatted][staff-position-formatted][staff-photo][staff-email-link][staff-bio-formatted][/staff_loop]',
);

$saved_html = get_option( '_staff_listing_custom_html' );
$saved_ext  = get_option( '_staff_listing_write_external_css' );

foreach ( array( 'no', 'yes' ) as $ext ) {
	update_option( '_staff_listing_write_external_css', $ext );
	foreach ( $templates as $tname => $tpl ) {
		update_option( '_staff_listing_custom_html', $tpl );
		foreach ( $cases as $cname => $shortcode ) {
			file_put_contents( "$out/$ext-$tname-$cname.html", do_shortcode( $shortcode ) );
		}
	}
}

update_option( '_staff_listing_custom_html', $saved_html );
if ( false === $saved_ext ) {
	delete_option( '_staff_listing_write_external_css' );
} else {
	update_option( '_staff_listing_write_external_css', $saved_ext );
}

WP_CLI::success( count( glob( "$out/*.html" ) ) . " snapshots in $out" );
