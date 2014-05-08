<?php
global $submit_errors;

function ld_submit__create_user( $username, $email ) {

    $password = wp_generate_password( 14, true );
    $user_id = wp_create_user( $username, $password, $email );

    if ( $user_id )
        wp_new_user_notification( $user_id, $password );

    return $user_id;
}


function ld_submit__create_listing( $name, $description, $cat_id, $user_id ) {

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


function ld_submit__create_meta( $data, $post_id ) {

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


function ld_sanitize__post( $post_data ) {

    $required_fields = apply_filters( 'lddlite_required_fields', array(
        'ld_s_name' => '',
        'ld_s_description' => '',
        'ld_s_username' => '',
        'ld_s_email' => '',
        'ld_s_contact_email' => '',
        'ld_s_contact_phone' => '',
        'ld_s_address_one' => '',
        'ld_s_city' => '',
        'ld_s_subdivision' => '',
        'ld_s_post_code' => '',
        'ld_s_country' => '',
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

    ld_submit_validate_category( $data['category'] );
    ld_submit_validate_user( $data['username'], $data['email'] );
    ld_submit_validate_phone( $data['contact_phone'] );

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

    $codes = $submit_errors->get_error_codes();

    if ( !empty( $codes ) )
        return $submit_errors;

    return true;
}


function ld_submit_validate_category( $cat_id ) {

    $ids = get_terms( LDDLITE_TAX_CAT, array('fields' => 'ids', 'get' => 'all') );

    if ( !in_array( $cat_id, $ids ) )
        ld_submit_add_errors( 'category_invalid' );

}


function ld_submit_validate_user( $username, $email ) {

    if ( $username != sanitize_user( $username, true ) )
        ld_submit_add_errors( 'username_invalid', $username );

    if ( username_exists( $username ) )
        ld_submit_add_errors( 'username_exists', $username );

    if ( email_exists( $email ) )
        ld_submit_add_errors( 'email_exists', $email );

}


function ld_submit_validate_phone( $number, $key = 'phone', $required = true ) {

    $number = ld_sanitize_phone( $number );

    if ( $required && empty( $number ) )
        ld_submit_add_errors( $key . '_required' );

    if ( strlen( $number ) < 7 || strlen( $number ) > 16 )
        ld_submit_add_errors( $key . '_invalid' );

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

        $parsed = parse_url( $url );

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
        'category_invalid'      => __( 'Please select a category', ldd::$slug ),
        'username_invalid'      => __( 'That is not a valid username', ldd::$slug ),
        'username_exists'       => __( 'That username already exists', ldd::$slug ),
        'email_exists'          => __( 'That email is already in use', ldd::$slug ),
        'phone_required'        => __( 'Please enter a phone number', ldd::$slug ),
        'phone_invalid'         => __( 'That is not a valid phone number', ldd::$slug ),
        'fax_invalid'           => __( 'That is not a valid fax number', ldd::$slug ),
        'street_required'       => __( 'Please enter your street address', ldd::$slug ),
        'city_required'         => __( 'Please enter a city', ldd::$slug ),
        'subdivision_required'  => __( 'Please enter a state', ldd::$slug ),
        'post_code_required'    => __( 'Please enter your zip', ldd::$slug ),
    );

    if ( array_key_exists( $error_slug, $error_messages ) )
        return $error_messages[ $error_slug ];

    return false;
}
