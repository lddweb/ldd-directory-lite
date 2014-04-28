<?php

/**
 *
 */
if ( isset( $_POST['__T__action'] ) ) {
    ld_validate_submit_form( $_POST );
}

function ld_validate_submit_form( $form ) {

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

    $user = ld_submit_create_user( $username, $email );

}


function ld_submit_create_user( $username, $email ) {

    $errors = new WP_Error;

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

    // wp_insert_user( $user_data );

    return true;
}


function ld_submit_get_error_message( $error_slug ) {

    $error_messages = array(
        'username_invalid'  => __( 'That is not a valid username', lddslug() ),
        'username_exists'   => __( 'That username already exists', lddslug() ),
    );

    if ( array_key_exists( $error_slug, $error_messages ) )
        return $error_messages[ $error_slug ];

    return false;
}


function lddlite_display_view_submit( $term = false ) {
	global $post;

	md( $_POST );
	$template_vars = array(
		'base_url'          => get_permalink( $post->ID ),
		'action'            => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		'nonce'             => wp_create_nonce( 'submit-listing-nonce' ),
        'country_dropdown'  => lddlite_dropdown_country(),
	);

	return lddlite_parse_template( 'display/submit', $template_vars );
}


function lddlite_dropdown_subdivision( $subdivision )
{

    $parse = LDDLITE_PATH . '/includes/views/select/subdivision.' . $subdivision . '.inc';

    if ( !file_exists( $parse ) )
        return  '<input id="subdivision" name="ld_s_subdivision" type="text" required>';

    $file = file( $parse );

    $output = '<select name="subdivision">';

    foreach ( $file as $line )
    {
        $field = explode( ',', $line );
        $output .= '<option name="' . $field[0] . '"';
        if ( isset( $_SESSION['ldd']['subdivsision'] ) && $field[0] == $_SESSION['ldd']['subdivsision'] ) {
            $output .= ' selected ';
        }
        $output .= '>' . str_replace( array( "\r", "\n" ), '', $field[1] ) . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function lddlite_dropdown_country()
{

    $countries_inc = LDDLITE_PATH . '/includes/views/select/countries.inc';

    if ( !file_exists( $countries_inc ) )
        return  '<input id="country" name="ld_s_country" type="text" tabindex="7" required>';

    $_countries = file( $countries_inc );

    $output = '<select id="country" name="ld_s_country" tabindex="7" required>';

    foreach ( $_countries as $line ) {
        $field = explode( ',', $line );
        $output .= '<option value="' . $field[0] . '"';
        if ( isset( $_SESSION['ldd-country'] ) && $field[0] == $_SESSION['ldd-country'] ) {
            $output .= ' selected ';
        }
        $output .= '>' . $field[1] . '</option>';
    }

    $output .= '</select>';

    return $output;

}
