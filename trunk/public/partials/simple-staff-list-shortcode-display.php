<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public/partials
 */

	global $sslp_sc_output;

	$atts       = $this->simple_staff_list_shortcode_atts;
	$single     = $atts['single'];
	$group      = $atts['group'];
	$wrap_class = $atts['wrap_class'];
	$order      = $atts['order'];

	// Get Template and CSS.
	$custom_html            = stripslashes_deep( get_option( '_staff_listing_custom_html' ) );
	$custom_css             = stripslashes_deep( get_option( '_staff_listing_custom_css' ) );
	$default_tags           = get_option( '_staff_listing_default_tags' );
	$default_formatted_tags = get_option( '_staff_listing_default_formatted_tags' );
	$output                 = '';
	$group                  = strtolower( $group );
	$order                  = strtoupper( $order );
	$staff                  = '';

	$use_external_css = get_option( '_staff_listing_write_external_css' );

	/**
	 * Set up our WP_Query
	 */

	$args = array(
		'post_type'      => 'staff-member',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'post_status'    => 'publish',
	);

	// Check user's 'order' value.
	if ( 'ASC' !== $order && 'DESC' !== $order ) {
		$order = 'ASC';
	}

	// Set 'order' in our query args.
	$args['order']              = $order;
	$args['staff-member-group'] = $group;

	$staff = new WP_Query( $args );

	/**
	 * Set up our loop_markup
	 */

	$loop_markup_reset = str_replace( '[staff_loop]', '', substr( $custom_html, strpos( $custom_html, '[staff_loop]' ), strpos( $custom_html, '[/staff_loop]' ) - strpos( $custom_html, '[staff_loop]' ) ) );
	$loop_markup       = $loop_markup_reset;


	// Doing this so I can concatenate class names for current and possibly future use.
	$staff_member_classes = $wrap_class;

	// Prepare to output styles if not using external style sheet.
	if ( 'no' === $use_external_css ) {
		$style_output = '<style>' . $custom_css . '</style>';
	} else {
		$style_output = ''; }

	$i = 0;

	if ( $staff->have_posts() ) :

		$output .= '<div class="staff-member-listing ' . $group . '">';

		while ( $staff->have_posts() ) :
			$staff->the_post();

			global $post;

			if ( ( $staff->found_posts ) - 1 === $i ) {
				$staff_member_classes .= ' last';
			}

			if ( $i % 2 ) {
				$output .= '<div class="staff-member odd ' . $staff_member_classes . '">';
			} else {
				$output .= '<div class="staff-member even ' . $staff_member_classes . '">';
			}

			global $post;

			$custom          = get_post_custom();
			$name            = get_the_title();
			$name_formatted  = '<h3 class="staff-member-name">' . $name . '</h3>';
			$name_slug       = basename( get_permalink() );
			$title           = isset( $custom['_staff_member_title'][0] ) ? $custom['_staff_member_title'][0] : '';
			$title_formatted = '' !== $title ? '<h4 class="staff-member-position">' . $title . '</h4>' : '';
			$email           = isset( $custom['_staff_member_email'][0] ) ? $custom['_staff_member_email'][0] : '';
			$phone           = isset( $custom['_staff_member_phone'][0] ) ? $custom['_staff_member_phone'][0] : '';
			$bio             = isset( $custom['_staff_member_bio'][0] ) ? $custom['_staff_member_bio'][0] : '';
			$fb_url          = isset( $custom['_staff_member_fb'][0] ) ? $custom['_staff_member_fb'][0] : '';
			$tw_url          = isset( $custom['_staff_member_tw'][0] ) ? 'http://www.twitter.com/' . $custom['_staff_member_tw'][0] : '';
			$email_mailto    = '' !== $email ? '<a class="staff-member-email" href="mailto:' . antispambot( $email ) . '" title="Email ' . $name . '">' . antispambot( $email ) . '</a>' : '';
			$email_nolink    = '' !== $email ? antispambot( $email ) : '';

			if ( has_post_thumbnail() ) {

				$image_size = apply_filters( 'sslp_set_staff_image_size', $atts['image_size'], $post->ID );

				$image_obj = wp_get_attachment_image_src( get_post_thumbnail_id(), $image_size, false );
				$src       = $image_obj[0];

				$photo_url = $src;
				$photo     = '<img class="staff-member-photo" src="' . $photo_url . '" alt = "' . $title . '">';

			} else {

				$photo_url = '';
				$photo     = '';

			}

			if ( function_exists( 'wpautop' ) ) {

				$bio_format = '' !== $bio ? '<div class="staff-member-bio">' . wpautop( $bio ) . '</div>' : '';

			} else {

				$bio_format = $bio;

			}

			$accepted_single_tags  = $default_tags;
			$replace_single_values = apply_filters( 'sslp_replace_single_values_filter', array( $name, $name_slug, $photo_url, $title, $email_nolink, $phone, $bio, $fb_url, $tw_url ), $post->ID );

			$accepted_formatted_tags  = $default_formatted_tags;
			$replace_formatted_values = apply_filters( 'sslp_replace_formatted_values_filter', array( $name_formatted, $title_formatted, $photo, $email_mailto, $bio_format ), $post->ID );

			$loop_markup = str_replace( $accepted_single_tags, $replace_single_values, $loop_markup );
			$loop_markup = str_replace( $accepted_formatted_tags, $replace_formatted_values, $loop_markup );

			$output .= apply_filters( 'sslp_single_loop_markup_filter', $loop_markup, $post->ID );

			$loop_markup = $loop_markup_reset;



			$output .= '</div> <!-- Close staff-member -->';
			$i++;


		endwhile;

		$output .= '</div> <!-- Close staff-member-listing -->';

		wp_reset_postdata();

	endif;

	$output = $style_output . $output;

	$sslp_sc_output = do_shortcode( $output );
