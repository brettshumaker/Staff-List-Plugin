<?php

/**
 * sslp_gutenberg_render_staff_photo
 * 
 * Pluggable function that renders the Staff Member's photo.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_image' ) ) {
    function sslp_gutenberg_render_staff_image( $value, $staff_id ) {
        $return = '';

        // If $value (the thumbnail ID) is set, use that - otherwise, provide an filter so a fallback attachment ID can be provided. 
        $value = $value ? $value : apply_filters( 'sslp_staff_member_image_fallback', false, $staff_id );

        if ( $value ) {
            $image_size = apply_filters( 'sslp_set_staff_image_size', 'medium', $staff_id );

            $image_obj = wp_get_attachment_image_src( $value, $image_size, false );

            // Make sure we have an image object before trying to get anything else
            if ( $image_obj ) {
                $src       = $image_obj[0];
                $photo_url = $src;
                $srcset    = wp_get_attachment_image_srcset( $value, $image_size );
                $return    = '<img class="staff-member-photo" src="' . $photo_url . '" srcset="' . $srcset . '" alt = "' . get_the_title( $staff_id ) . '">';
            }
        }

        return apply_filters( 'sslp_gutenberg_render_staff_photo', $return, $value, $staff_id );
    }    
}

/**
 * sslp_gutenberg_render_staff_name
 * 
 * Pluggable function that renders the Staff Member's name.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_name' ) ) {
    function sslp_gutenberg_render_staff_name( $value, $staff_id ) {
        $return = '<h2>' . $value . '</h2>';
        return apply_filters( 'sslp_gutenberg_render_staff_name', $return, $value, $staff_id );
    }
}

/**
 * sslp_gutenberg_render_staff_position
 * 
 * Pluggable function that renders the Staff Member's position.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_position' ) ) {
    function sslp_gutenberg_render_staff_position( $value, $staff_id ) {
        $return = $value ? "<h3>$value</h3>" : '';
        return apply_filters( 'sslp_gutenberg_render_staff_position', $return, $value, $staff_id );
    }
}

/**
 * sslp_gutenberg_render_staff_bio
 * 
 * Pluggable function that renders the Staff Member's bio.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_bio' ) ) {
    function sslp_gutenberg_render_staff_bio( $value, $staff_id ) {
        $return = $value ? "<p>$value</p>" : '';
        return apply_filters( 'sslp_gutenberg_render_staff_bio', $return, $value, $staff_id );
    }
}

/**
 * sslp_gutenberg_render_staff_email
 * 
 * Pluggable function that renders the Staff Member's email address.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_email' ) ) {
    function sslp_gutenberg_render_staff_email( $value, $staff_id ) {
        $return = $value ? '<span class="contact-link"><a href="mailto:' . antispambot( $value ) . '" alt="Email ' . get_the_title( $staff_id ) .'">' . antispambot( $value ) . '</a></span>' : '';
        return apply_filters( 'sslp_gutenberg_render_staff_email', $return, $value, $staff_id );
    }
}

/**
 * sslp_gutenberg_render_staff_phone
 * 
 * Pluggable function that renders the Staff Member's phone number.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_phone' ) ) {
    function sslp_gutenberg_render_staff_phone( $value, $staff_id ) {
        $return = $value ? '<span class="contact-link"><a href="tel:' . $value . '">' . $value . '</a></span>' : '';
        return apply_filters( 'sslp_gutenberg_render_staff_phone', $return, $value, $staff_id );
    }
}

/**
 * sslp_gutenberg_render_staff_fb
 * 
 * Pluggable function that renders the Staff Member's Facebook link.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_fb' ) ) {
    function sslp_gutenberg_render_staff_fb( $value, $staff_id ) {
        $return = $value ? '<a class="social-icon" href="' . $value . '"><span class="dashicons dashicons-facebook"></span></a>' : '';
        return apply_filters( 'sslp_gutenberg_render_staff_fb', $return, $value, $staff_id );
    }
}

/**
 * sslp_gutenberg_render_staff_tw
 * 
 * Pluggable function that renders the Staff Member's Twitter link.
 * 
 * @param $value string The value to be rendered.
 * @param $staff_id int The ID of the Staff Member being rendered.
 * @since 2.3.0
 */
if ( ! function_exists( 'sslp_gutenberg_render_staff_tw' ) ) {
    function sslp_gutenberg_render_staff_tw( $value, $staff_id ) {
        $return = $value ? '<a class="social-icon" href="https://twitter.com/' . $value . '"><span class="dashicons dashicons-twitter"></span></a>' : '';
        return apply_filters( 'sslp_gutenberg_render_staff_tw', $return, $value, $staff_id );
    }
}
