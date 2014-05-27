<?php
global $submit_errors;

function ldl_submit__create_user( $username, $email ) {

    $password = wp_generate_password( 14, true );
    $user_id = wp_create_user( $username, $password, $email );

    if ( $user_id )
        wp_new_user_notification( $user_id, $password );

    return $user_id;
}


function ldl_submit__create_listing( $name, $description, $cat_id, $user_id ) {

    $listing = array(
        'post_content'  => $description,
        'post_title'    => $name,
        'post_status'   => 'pending',
        'post_type'     => LDDLITE_POST_TYPE,
        'post_author'   => $user_id,
        'post_date'     => date( 'Y-m-d H:i:s' ),
        'tax_input'     => array( LDDLITE_TAX_CAT => $cat_id ),
    );

    return wp_insert_post( $listing );
}


function ldl_submit__create_meta( $data, $post_id ) {

    $remove = array(
        'name',
        'description',
        'username', // These two should already be gone, but let's pretend
        'email',
    );

    $data = array_diff_key( $data, array_flip( $remove ) );

    foreach ( $data['url'] as $key => $value ) {
        $data[ 'url_' . $key ] = $value;
    }
    unset( $data['url'] );

    foreach ( $data as $key => $value ) {
        add_post_meta( $post_id, LDDLITE_PFX . $key, $value );
    }

}


function ldl_sanitize__post( $data ) {

    $output['description'] = $data['ld_s_description'];
    $output['contact_email'] = sanitize_email( $data['ld_s_contact_email'] );
    $output['email'] = sanitize_email( $data['ld_s_email'] );
    unset( $data['ld_s_description'] );
    unset( $data['ld_s_contact_email'] );
    unset( $data['ld_s_email'] );

    foreach ( $data as $key => $value ) {
        if ( false !== strpos( $key, 'ld_s_' ) ) {
            $var = substr( $key, 5 );
            $output[ $var ] = is_array( $value ) ? $value : sanitize_text_field( wp_unslash( $value ) );
        }
    }

    if ( is_array( $output['url'] ) ) {
        foreach ( $output['url'] as $key => $value ) {
            if ( in_array( $key, array( 'facebook', 'linkedin' ) ) )
                $output['url'][ $key ] = ldl_sanitize_https( $value );
            else if ( 'twitter' == $key )
                $output['url'][ $key ] = ldl_sanitize_twitter( $value );
            else if ( strpos( $url, 'http') !== 0 )
                $output['url'][ $key ] = esc_url_raw( $value );
        }
    }

    return $output;
}


function ldl_submit_validate_form( $data) {
    global $submit_errors;

    $submit_errors = new WP_Error;

    if ( empty( $data['title'] ) )
        ldl_submit_add_errors( 'title_required' );

    ldl_submit_validate_category( $data['category'] );

    if ( empty( $data['description'] ) )
        ldl_submit_add_errors( 'description_required' );

    if ( !empty( $data['contact_email'] ) && !is_email( $data['contact_email'] ) )
        ldl_submit_add_errors( 'contact_email_invalid' );

    if ( !empty( $data['contact_phone'] ) )
        ldl_submit_validate_phone( $data['contact_phone'] );

    ldl_submit_validate_user( $data['username'], $data['email'] );


    if ( empty( $data['address_one'] ) )
        ldl_submit_add_errors( 'address_one_required' );

    if ( empty( $data['city'] ) )
        ldl_submit_add_errors( 'city_required' );

    if ( empty( $data['subdivision'] ) )
        ldl_submit_add_errors( 'subdivision_required' );

    if ( empty( $data['post_code'] ) )
        ldl_submit_add_errors( 'post_code_required' );

    if ( !is_array( $data['url'] ) )
        $data['url'] = array();
    else
        $data['url'] = ldl_submit_sanitize_urls( $data['url'] );

    $codes = $submit_errors->get_error_codes();

    if ( !empty( $codes ) )
        return $submit_errors;

    return true;
}


function ldl_submit_validate_category( $cat_id ) {

    $ids = get_terms( LDDLITE_TAX_CAT, array('fields' => 'ids', 'get' => 'all') );

    if ( !in_array( $cat_id, $ids ) )
        ldl_submit_add_errors( 'category_invalid' );

}


function ldl_submit_validate_user( $username, $email ) {

    $r = false;

    if ( empty( $username ) ) {
        ldl_submit_add_errors( 'username_required' );
        $r = true;
    }

    if ( empty( $email ) || !is_email( $email ) ) {
        ldl_submit_add_errors( 'email_required' );
        $r = true;
    }

    if ( $r )
        return;

    if ( $username != sanitize_user( $username, true ) )
        ldl_submit_add_errors( 'username_invalid', $username );

    if ( username_exists( $username ) )
        ldl_submit_add_errors( 'username_exists', $username );

    if ( email_exists( $email ) )
        ldl_submit_add_errors( 'email_exists', $email );

}


function ldl_submit_validate_phone( $number, $key = 'contact_phone', $required = true ) {

    $number = ldl_sanitize_phone( $number );

    if ( $required && empty( $number ) )
        ldl_submit_add_errors( $key . '_required' );

    if ( strlen( $number ) < 7 || strlen( $number ) > 16 )
        ldl_submit_add_errors( $key . '_invalid' );

}


function ldl_submit_sanitize_urls( $urls ) {

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

        $parsed = parse_url( $url );

        $scheme = ( 'website' == $type ) ? 'http' : 'https';
        $host = ( 'website' == $type ) ? $parsed['host'] : $hosts[ $type ];

        $path = '/' . sanitize_text_field( ltrim( $parsed['path'], '/' ) );

        $urls[ $type ] = $scheme . '://' . $host . $path;
    }

    return $urls;
}


function ldl_submit_add_errors( $code, $data = null ) {
    global $submit_errors;

    if ( !is_wp_error( $submit_errors ) )
        $submit_errors = new WP_Error;

    $message = ldl_submit_get_error_message( $code );

    if ( $message )
        $submit_errors->add( $code, $message, $data );

}


function ldl_submit_get_error_message( $error_slug ) {

    $error_messages = array(
        'title_required'            => __( 'You need a title for your listing', 'lddlite' ),
        'category_invalid'          => __( 'Please select a category', 'lddlite' ),
        'description_required'      => __( 'Please add a description for your listing', 'lddlite' ),
        'contact_email_invalid'     => __( 'Please enter a valid email address', 'lddlite' ),
        'contact_phone_required'    => __( 'Please enter a phone number', 'lddlite' ),
        'contact_phone_invalid'     => __( 'That is not a valid phone number', 'lddlite' ),
        'username_required'         => __( 'A username is required', 'lddlite' ),
        'username_invalid'          => __( 'That is not a valid username', 'lddlite' ),
        'username_exists'           => __( 'That username already exists', 'lddlite' ),
        'email_required'            => __( 'An email address is required', 'lddlite' ),
        'email_exists'              => __( 'That email is already in use', 'lddlite' ),
        'address_one_required'      => __( 'Please enter your street address', 'lddlite' ),
        'city_required'             => __( 'Please enter a city', 'lddlite' ),
        'subdivision_required'      => __( 'Please enter a state', 'lddlite' ),
        'post_code_required'        => __( 'Please enter your zip', 'lddlite' ),
    );

    if ( array_key_exists( $error_slug, $error_messages ) )
        return $error_messages[ $error_slug ];

    return false;
}
