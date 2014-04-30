<?php

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

