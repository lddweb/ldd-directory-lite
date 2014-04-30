<?php

/**
 *
 */

require_once( LDDLITE_PATH . '/includes/views/submit-functions.php' );
require_once( LDDLITE_PATH . '/includes/views/submit-process.php' );


function lddlite_display_view_submit( $term = false ) {
    global $post;

    if ( isset( $_POST['__T__action'] ) )
        $valid = ld_submit_validate_form( $_POST );

    $template_vars = array(
        'base_url'          => get_permalink( $post->ID ),
        'action'            => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'nonce'             => wp_create_nonce( 'submit-listing-nonce' ),
        'country_dropdown'  => lddlite_dropdown_country(),
    );

    if ( is_wp_error( $valid ) ) {
        $template_vars['errors'] = array();

        $codes = $valid->get_error_codes();

        foreach ( $codes as $code ) {
            $key = substr( $code, 0, strpos( $code, '_' ) );
            $template_vars['errors'][ $key ] = '<span class="submit-error">' . $valid->get_error_message( $code ) . '</span>';
        }

    }


    return ld_parse_template( 'display/submit', $template_vars );
}
