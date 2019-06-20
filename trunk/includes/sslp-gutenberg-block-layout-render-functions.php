<?php

if ( ! function_exists( 'sslp_layout_callback_layout_1' ) ) {
function sslp_layout_callback_layout_1( $content, $staff_data ) {
    $return = '';
    /**
     * image
     * --- staff-member-content
     *  title
     *  position
     *  bio
     *  --- staff-member-meta
     *    phone
     *    email
     *    fb
     *    tw
     *  ---
     * ---
     */
    $last_field = '';
    $started_meta = false;

    foreach( $content as $index => $data ) {
        // $return .= '<!-- ' . $data['name'] . var_export( $data, true ) . ' -->';
        // Did we do the image last? If so, start our staff-member-content wrapper
        if ( 'image' === $last_field )
            $return .= '<div class="staff-member-content">';
        
        // Have we started our meta? And do we need to?
        if ( ! $started_meta && in_array( $data['name'], array( 'phone', 'email', 'fb', 'tw' ) ) ) {
            $return .= '<div class="staff-member-meta">';
            $started_meta = true;
        }
        
        // Make sure this block has this value turned before attempting to display.
        if ( $data['value'] ) {
            $value = $staff_data->staffData[ $data['name'] ] && $staff_data->staffData[ $data['name'] ][0] ? $staff_data->staffData[ $data['name'] ][0] : '';
            $return .= call_user_func_array( 'sslp_gutenberg_render_staff_' . $data['name'], array( $value, $staff_data->ID ) );
        }

        if ( $index === count( $content ) - 1 ) {
            $return .= '</div>';

            if ( $started_meta ) {
                $return .= '</div>';
            }
        }

        $last_field = $data['name'];
    }
    return $return;
}
}

if ( ! function_exists( 'sslp_layout_callback_layout_2' ) ) {
function sslp_layout_callback_layout_2( $content, $staff_data ) {
    // Layout 2 uses the same html as Layout 1 - just with different styles. Just call the Layout 1 render function
    return sslp_layout_callback_layout_1( $content, $staff_data );
}
}

if ( ! function_exists( 'sslp_layout_callback_layout_3' ) ) {
function sslp_layout_callback_layout_3( $content, $staff_data ) {
    $return = '';
    /**
     * image
     * title
     * position
     * bio
     * phone
     * email
     * fb
     * tw
     */
    foreach( $content as $index => $data ) {
        // Make sure this block has this value turned before attempting to display.
        if ( $data['value'] ) {
            $value = $staff_data->staffData[ $data['name'] ] && $staff_data->staffData[ $data['name'] ][0] ? $staff_data->staffData[ $data['name'] ][0] : '';
            $return .= call_user_func_array( 'sslp_gutenberg_render_staff_' . $data['name'], array( $value, $staff_data->ID ) );
        }
    }
    return $return;
}
}