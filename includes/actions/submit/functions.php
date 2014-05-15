<?php


function ld_dropdown_subdivision( $subdivision, $data, $tabindex = 0 ) {

    $selected = isset( $data['subdivision'] ) ? $data['subdivision'] : '';
    $lines = ld_get_subdivision_array( $subdivision );

    if ( !$lines )
        return '<input id="subdivision" class="form-control" name="ld_s_subdivision" type="text" value="' . $selected . '" tabindex="9" required>';

    $output = '<select id="subdivision" class="form-control" name="ld_s_subdivision" tabindex="9" required>';

    foreach ( $lines as $key => $value ) {
        $output .= '<option value="' . $key . '"';
        if ( $selected == $key ) $output .= ' selected';
        $output .= '>' . $value . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ld_dropdown_country( $name, $data = '', $tabindex = 0 ) {

    $selected = '';
    if ( !is_array( $data ) && !empty( $data )  )
        $selected = $data;
    else if ( isset( $data['country'] ) && !empty( $data ) )
        $selected = $data['country'];

    $tabindex = $tabindex ? 'tabindex="' . $tabindex . '"' : '';

    $countries = ld_get_country_array();

    if ( !$countries )
        return '<input id="country" class="form-control" name="' . $name . '" type="text" ' . $tabindex . ' required>';

    $output = '<select id="country" class="form-control" name="' . $name . '" ' . $tabindex . ' required>';

    foreach ( $countries as $code => $name ) {
        $output .= '<option value="' . $code . '"';
        if ( $selected == $key ) $output .= ' selected';
        $output .= '>' . $name . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ld_sanitize_phone( $number ) {
    return preg_replace( '/[^0-9+]/', '', $number );
}