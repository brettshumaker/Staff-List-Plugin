<?php
$bio = get_post_meta( $post->ID, '_staff_member_bio', true );

echo wpautop( $bio );