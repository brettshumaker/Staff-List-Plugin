<?php
$facebook = get_post_meta( $post->ID, '_staff_member_fb', true );
if ( '' !== $facebook ) {
    echo '<span class="facebook"><a class="staff-member-facebook" href="' . esc_attr( $facebook ) . '" title="Find ' . esc_attr( get_the_title() ) . ' on Facebook">' . file_get_contents( STAFFLIST_URI . 'public/svg/facebook.svg?v=' . date('U') ) . '</a></span>';
}