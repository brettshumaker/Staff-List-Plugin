<?php


$image_obj = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium', false );
$src       = $image_obj[0];
?>
<img class="staff-member-photo" src="<?php echo esc_attr( $src ); ?>" alt = "<?php echo esc_attr( get_the_title() ); ?>">