<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

    <?php
		/**
		 * sslp_before_main_content hook.
		 *
		 * @hooked sslp_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
        do_action( 'sslp_before_main_content' );
	?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="staff-header">
            <?php sslp_get_template_part( 'single-staff-member/staff-image' ); ?>
            <?php sslp_get_template_part( 'single-staff-member/staff-name' ); ?>
            <div class="entry-meta staff-meta">
                <?php sslp_get_template_part( 'single-staff-member/staff-position' ); ?>
                <?php sslp_get_template_part( 'single-staff-member/staff-email' ); ?>
                <?php sslp_get_template_part( 'single-staff-member/staff-phone' ); ?>
                <?php sslp_get_template_part( 'single-staff-member/staff-facebook' ); ?>
                <?php sslp_get_template_part( 'single-staff-member/staff-twitter' ); ?>
            </div>
        </header>

        <div class="staff-content">
            <?php sslp_get_template_part( 'single-staff-member/staff-bio' ); ?>
        </div>
    </article>

    <?php
		/**
		 * sslp_after_main_contenthook.
		 *
		 * @hooked sslp_output_content_wrapper_end - 10 (outputs opening divs for the content)
		 */
		do_action( 'sslp_after_main_content' );
	?>

<?php get_footer();