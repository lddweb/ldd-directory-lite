<?php

/**
 *
 */

require_once( LDDLITE_PATH . '/includes/views/submit-functions.php' );
require_once( LDDLITE_PATH . '/includes/views/submit-process.php' );


function ld_view_submit( $term = false ) {
    global $post;

    $valid = false;

    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) )
            die( 'No, kitty! That\'s a bad kitty!' );

        $data = ld_submit_process_post( $_POST );
        $valid = ld_submit_validate_form( $data );

    }

    if ( $valid == true && is_array( $data ) ) {

        $uid = ld_submit_create_user( $data['username'], $data['email'] );
        $pid = ld_submit_create_listing( $data );

        if ( isset( $_FILES['ld_s_logo'] ) ) {
            // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $attachment_id = media_handle_upload( 'ld_s_logo', 0 );
        }



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

}
