<?php
$phone = get_post_meta( $post->ID, '_staff_member_phone', true );
if ( '' !== $phone ) {
    echo '<span class="phone"><a class="staff-member-phone" href="tel:' . esc_attr( $phone ) . '">' . file_get_contents( STAFFLIST_URI . 'public/svg/phone.svg?v=' . date('U') ) . '</a></span>';
}