<?php
$title = get_post_meta( $post->ID, '_staff_member_title', true );
if ( '' !== $title ) {
    echo '<span class="title">' . esc_html( $title ) . '</span>';
}