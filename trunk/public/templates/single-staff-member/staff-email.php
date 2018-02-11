<?php
$email = get_post_meta( $post->ID, '_staff_member_email', true );
if ( '' !== $email ) {
    echo '<span class="email"><a class="staff-member-email" href="mailto:' . esc_attr( antispambot( $email ) ) . '" title="Email ' . esc_attr( get_the_title() ) . '">' . file_get_contents( STAFFLIST_URI . 'public/svg/envelope.svg?v=' . date('U') ) . '</a></span>';
}