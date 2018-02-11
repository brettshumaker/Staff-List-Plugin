<?php
$twitter = get_post_meta( $post->ID, '_staff_member_tw', true );
if ( '' !== $twitter ) {
    echo '<span class="twitter"><a class="staff-member-twitter" href="https://twitter.com/' . esc_attr( $twitter ) . '" title="Follow ' . esc_attr( get_the_title() ) . ' on Twitter">' . file_get_contents( STAFFLIST_URI . 'public/svg/twitter.svg?v=' . date('U') ) . '</a></span>';
}