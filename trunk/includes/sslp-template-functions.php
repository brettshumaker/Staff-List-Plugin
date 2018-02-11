<?php

add_action( 'sslp_before_main_content', 'sslp_output_content_wrapper', 10 );
if ( ! function_exists( 'sslp_output_content_wrapper' ) ) {
    
    /**
     * Output the start of the page wrapper.
	 */
    function sslp_output_content_wrapper() {
        sslp_get_template_part( 'global/wrapper-start' );
	}
}

add_action( 'sslp_after_main_content', 'sslp_output_content_wrapper_end', 10 );
if ( ! function_exists( 'sslp_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 */
	function sslp_output_content_wrapper_end() {
		sslp_get_template_part( 'global/wrapper-end' );
	}
}