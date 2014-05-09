<?php


function ld_dropdown_subdivision( $subdivision, $data ) {

    $selected = isset( $data['subdivision'] ) ? $data['subdivision'] : '';
    $lines = ld_get_subdivision_array( $subdivision );

    if ( !$lines )
        return '<input id="subdivision" name="ld_s_subdivision" type="text" value="' . $selected . '" tabindex="9" required>';

    $output = '<select id="subdivision" name="ld_s_subdivision" tabindex="9" required>';

    foreach ( $lines as $key => $value ) {
        $output .= '<option value="' . $key . '"';
        if ( $selected == $key ) $output .= ' selected';
        $output .= '>' . $value . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ld_dropdown_country() {

    $countries = ld_get_country_array();

    if ( !$countries )
        return '<input id="country" name="ld_s_country" type="text" tabindex="7" required>';

    $output = '<select id="country" name="ld_s_country" tabindex="7" required>';

    foreach ( $countries as $code => $name ) {
        $output .= '<option value="' . $code . '"';
        $output .= '>' . $name . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ld_sanitize_phone( $number ) {
    return preg_replace( '/[^0-9+]/', '', $number );
}