<?php

function ld_submit_validate_form( $form ) {

    $description = $form['ld_s_description'];
    unset( $form['ld_s_description'] );

    $email = sanitize_email( $form['ld_s_email'] );
    unset( $form['ld_s_email'] );

    foreach ( $form as $key => $value ) {

        if ( false !== strpos( $key, 'ld_s_' ) ) {
            $var = substr( $key, 5 );
            $$var = sanitize_text_field( $value );
        }

    }

    $user = ld_submit_validate_user( $username, $email );

    if ( is_wp_error( $user ) ) {
        return $user;
    }

}


function ld_submit_validate_user( $username, $email ) {

    $errors = new WP_Error;

    $errors->add( 'username_exists', ld_submit_get_error_message( 'username_exists' ) );

    if ( $username != sanitize_user( $username, true ) )
        $errors->add( 'username_invalid', ld_submit_get_error_message( 'username_invalid' ) );

    if ( username_exists( $username ) )
        $errors->add( 'username_exists', ld_submit_get_error_message( 'username_exists' ) );

    if ( email_exists( $email ) )
        $errors->add( 'email_exists', ld_submit_get_error_message( 'email_exists' ) );

    if ( !empty( $errors->get_error_codes() ) )
        return $errors;

    $user_data = array(
        'user_login' => $username,
        'user_email' => $email,
    );

    return $user_data;
}


function ld_submit_get_error_message( $error_slug ) {

    $error_messages = array(
        'username_invalid'  => __( 'That is not a valid username', lddslug() ),
        'username_exists'   => __( 'That username already exists', lddslug() ),
        'email_exists'      => __( 'Another member is already using that email address', lddslug() ),
    );

    if ( array_key_exists( $error_slug, $error_messages ) )
        return $error_messages[ $error_slug ];

    return false;
}
