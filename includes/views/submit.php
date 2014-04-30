<?php

/**
 *
 */

require_once( LDDLITE_PATH . '/includes/views/submit-functions.php' );
require_once( LDDLITE_PATH . '/includes/views/submit-process.php' );


function lddlite_display_view_submit( $term = false ) {
    global $post;

    $valid = false;

    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) )
            die( 'No, kitty! That\'s a bad kitty!' );

        $data = ld_submit_process_post( $_POST );
        $valid = ld_submit_validate_form( $data );

    }


    if ( $valid == false || is_wp_error( $valid ) ) {

        $template_vars = array(
            'url'          => get_permalink( $post->ID ),
            'action'            => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'nonce'             => wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ),
            'country_dropdown'  => ld_dropdown_country(),
        );

        if ( is_wp_error( $valid ) ) {
            $template_vars['errors'] = array();

            $codes = $valid->get_error_codes();

            foreach ( $codes as $code ) {
                $key = substr( $code, 0, strrpos( $code, '_' ) );
                $template_vars['errors'][ $key ] = '<span class="submit-error">' . $valid->get_error_message( $code ) . '</span>';
            }

        }

        return ld_parse_template( 'display/submit', $template_vars );

    } else {

        foreach ( $data as $key => $value ) {

            if ( 'url' == $key )
                $data['url'] = esc_url( $value['website'] );
            else
                $data[ $key ] = htmlentities( $value );

        }

        $template_vars = array(
            'url'       => get_permalink( $post->ID ),
            'listing'   => $data,
        );

        return ld_parse_template( 'display/submit-success', $template_vars );

    }

}
