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


function ld_get_country_array() {

    $country_file = LDDLITE_PATH . '/includes/views/select/countries.inc';

    if ( !file_exists( $country_file ) )
        return array();

    $countries = file( $country_file );
    $data = array();

    foreach ( $countries as $country ) {
        $line = explode( ',', $country );
        $data[ $line[0] ] = $line[1];
    }

    return $data;
}


function ld_dropdown_country()
{

    $countries = ld_get_country_array();

    if ( empty( $countries ) )
        return  '<input id="country" name="ld_s_country" type="text" tabindex="7" required>';


    $output = '<select id="country" name="ld_s_country" tabindex="7" required>';

    foreach ( $countries as $code => $name ) {
        $output .= '<option value="' . $code . '"';
        $output .= '>' . $name . '</option>';
    }

    $output .= '</select>';

    return $output;

}

