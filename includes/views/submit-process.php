<?php

function ld_submit_process_post( $post_data ) {

    $required_fields = apply_filters( 'lddlite_required_fields', array(
        'ld_s_name'         => '',
        'ld_s_description'  => '',
        'ld_s_username'     => '',
        'ld_s_email'        => '',
        'ld_s_phone'        => '',
        'ld_s_street'       => '',
        'ld_s_city'         => '',
        'ld_s_subdivision'  => '',
        'ld_s_post_code'    => '',
        'ld_s_country'      => '',
    ) );

    $post_data = wp_parse_args( $post_data, $required_fields );

    $data['description'] = $post_data['ld_s_description'];
    unset( $post_data['ld_s_description'] );

    $data['email'] = sanitize_email( $post_data['ld_s_email'] );
    unset( $post_data['ld_s_email'] );

    foreach ( $post_data as $key => $value ) {

        if ( false !== strpos( $key, 'ld_s_' ) ) {
            $var = substr( $key, 5 );
            $data[ $var ] = is_array( $value ) ? $value : sanitize_text_field( wp_unslash( $value ) );
        }

    }

    return $data;
}


function ld_submit_validate_form( $data) {
    global $submit_errors;

    $submit_errors = new WP_Error;

    ld_submit_validate_user( $data['username'], $data['email'] );
    $data = ld_submit_validate_phone( $data );

    if ( empty( $data['street'] ) )
        ld_submit_add_errors( 'street_required' );

    if ( empty( $data['city'] ) )
        ld_submit_add_errors( 'city_required' );

    if ( empty( $data['subdivision'] ) )
        ld_submit_add_errors( 'subdivision_required' );

    if ( empty( $data['post_code'] ) )
        ld_submit_add_errors( 'post_code_required' );

    if ( !is_array( $data['url'] ) )
        $data['url'] = array();
    else
        $data['url'] = ld_submit_sanitize_urls( $data['url'] );

    if ( !empty( $submit_errors->get_error_codes() ) )
        return $submit_errors;

    if ( isset( $_FILES['ld_s_logo'] ) ) {
        // These files need to be included as dependencies when on the front end.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload( 'ld_s_logo', 0 );
    }

    // Create a new user account for this listing.
    // Multiple listings per user should be handled via their dashboard, which is a @todo
    $password = wp_generate_password( 14, true );
    $uid = wp_create_user( $data['username'], $password, $data['email'] );
    wp_new_user_notification( $uid, $password );

    // @TODO CREATE POST

    return true;
}


function ld_submit_validate_user( $username, $email ) {
    global $submit_errors;

    if ( $username != sanitize_user( $username, true ) )
        ld_submit_add_errors( 'username_invalid', $username );

    if ( username_exists( $username ) )
        ld_submit_add_errors( 'username_exists', $username );

    if ( email_exists( $email ) )
        ld_submit_add_errors( 'email_exists', $email );

}


function ld_submit_validate_phone( $data ) {
    global $submit_errors;

    $phone = preg_replace( '/[^0-9+]/', '', $data['phone'] );
    $fax = ( isset( $data['fax'] ) && !empty( $data['fax'] ) ) ? preg_replace( '/[^0-9+]/', '', $data['fax'] ) : '';

    if ( empty( $phone ) )
        ld_submit_add_errors( 'phone_required' );

    if ( strlen( $phone ) < 7 || strlen( $phone ) > 16 )
        ld_submit_add_errors( 'phone_invalid' );

    if ( !empty( $fax ) && ( strlen( $fax ) < 7 || strlen( $fax ) > 16 ) )
        ld_submit_add_errors( 'fax_invalid' );

    $data['phone'] = $phone;
    $data['fax']   = $fax;

    return $data;
}


function ld_submit_sanitize_urls( $urls ) {

    $hosts = array(
        'facebook' => 'www.facebook.com',
        'linkedin' => 'www.linkedin.com',
        'twitter' => 'twitter.com',
    );

    foreach( $urls as $type => $url ) {

        if ( empty( $url ) )
            break;

        // This helps parse i18n TLDs, but still forgives people entering just their twitter username
        if ( 'twitter' != $type )
            $url = esc_url( $url );

        $parsed = parse_url( $url ); md( $parsed );

        $scheme = ( 'website' == $type ) ? 'http' : 'https';
        $host = ( 'website' == $type ) ? $parsed['host'] : $hosts[ $type ];

        $path = '/' . sanitize_text_field( ltrim( $parsed['path'], '/' ) );

        $urls[ $type ] = $scheme . '://' . $host . $path;
    }

    return $urls;
}


function ld_submit_add_errors( $code, $data = null ) {
    global $submit_errors;

    if ( !is_wp_error( $submit_errors ) )
        $submit_errors = new WP_Error;

    $message = ld_submit_get_error_message( $code );

    if ( $message )
        $submit_errors->add( $code, $message, $data );

}


function ld_submit_get_error_message( $error_slug ) {

    $error_messages = array(
        'username_invalid'      => __( 'That is not a valid username', lddslug() ),
        'username_exists'       => __( 'That username already exists', lddslug() ),
        'email_exists'          => __( 'That email is already in use', lddslug() ),
        'phone_required'        => __( 'Please enter a phone number', lddslug() ),
        'phone_invalid'         => __( 'That is not a valid phone number', lddslug() ),
        'fax_invalid'           => __( 'That is not a valid fax number', lddslug() ),
        'street_required'       => __( 'Please enter your street address', lddslug() ),
        'city_required'         => __( 'Please enter a city', lddslug() ),
        'subdivision_required'  => __( 'Please enter a state', lddslug() ),
        'post_code_required'    => __( 'Please enter your zip', lddslug() ),
    );

    if ( array_key_exists( $error_slug, $error_messages ) )
        return $error_messages[ $error_slug ];

    return false;
}
