<?php

/**
 *
 */

function lddlite_display_view_submit( $term = false ) {
	global $post;

	$template_vars = array(
		'url' => get_permalink( $post->ID ),
	);

	return lddlite_parse_template( 'display/submit', $template_vars );
}



function lddlite_process_page()
{
    if ( !isset( $_POST['current_page'] ) || !isset( $_POST['ldd'] ) ) {
        return false;
    }

    if ( !isset( $_SESSION['ldd'] ) ) {
        $_SESSION['ldd'] = $_POST['ldd'];
    } else {
        $_SESSION['ldd'] = array_merge( $_SESSION['ldd'], $_POST['ldd'] );
    }

    $current_page   = $_POST['current_page'];
    $input          = $_POST['ldd'];

    $errors = array();

    if ( '1' == $current_page )
    {

        foreach ( $input as $key => $value )
        {
            if ( empty( $value ) )
            {
                $errors[$key] = 'Field is required.';
            }
            else
            {
                switch ( $key )
                {
                    case 'name':
                    case 'description':
                    case 'contact':
                        $input[$key] = esc_html( $value );
                        break;
                    case 'email':
                        if ( !filter_var( $value, FILTER_VALIDATE_EMAIL ) )
                            $errors[$key] = 'Invalid email address.';
                        break;
                }
            }
        }

    }
    else if ( '2' == $current_page )
    {
        foreach ( $input as $key => $value )
        {
            if ( empty( $value ) )
            {
                $errors[$key] = 'Field is required.';
            }
            else
            {
                switch ( $key )
                {
                    case 'street':
                    case 'city':
                    case 'state':
                    case 'zip':
                        $input[$key] = esc_html( $value );
                        break;

                }
            }
        }
    }

    $_SESSION['ldd'] = array_merge( $_SESSION['ldd'], $input );

    if ( !empty( $errors ) )
    {
        $_SESSION['errors'] = $errors;
        lddlite_submit_last_page_url( 1 );
    }


}


function lddlite_dropdown_subdivision( $subdivision )
{

    $parse = LDDLITE_PATH . '/includes/views/select/subdivision.' . $subdivision . '.inc';

    if ( !file_exists( $parse ) )
        return  '<input id="subdivision" type="text" name="ldd[subdivision]" value="{{subdivision}}" />';

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

    $_countries = array(
        "US" => "United States",
        "CA" => "Canada",
        "GB" => "United Kingdom",
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua And Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia And Herzegowina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "IO" => "British Indian Ocean Territory",
        "BN" => "Brunei Darussalam",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos (Keeling) Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo",
        "CD" => "Congo, The Democratic Republic Of The",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "CI" => "Cote D'Ivoire",
        "HR" => "Croatia (Local Name: Hrvatska)",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "TP" => "East Timor",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands (Malvinas)",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "FX" => "France, Metropolitan",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard And Mc Donald Islands",
        "VA" => "Holy See (Vatican City State)",
        "HN" => "Honduras",
        "HK" => "Hong Kong",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran (Islamic Republic Of)",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KP" => "Korea, Democratic People's Republic Of",
        "KR" => "Korea, Republic Of",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Lao People's Democratic Republic",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libyan Arab Jamahiriya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macau",
        "MK" => "Macedonia, Former Yugoslav Republic Of",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "MX" => "Mexico",
        "FM" => "Micronesia, Federated States Of",
        "MD" => "Moldova, Republic Of",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PA" => "Panama",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RE" => "Reunion",
        "RO" => "Romania",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "KN" => "Saint Kitts And Nevis",
        "LC" => "Saint Lucia",
        "VC" => "Saint Vincent And The Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "ST" => "Sao Tome And Principe",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia (Slovak Republic)",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia, South Sandwich Islands",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SH" => "St. Helena",
        "PM" => "St. Pierre And Miquelon",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard And Jan Mayen Islands",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syrian Arab Republic",
        "TW" => "Taiwan",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania, United Republic Of",
        "TH" => "Thailand",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad And Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks And Caicos Islands",
        "TV" => "Tuvalu",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "AE" => "United Arab Emirates",
        "UM" => "United States Minor Outlying Islands",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Viet Nam",
        "VG" => "Virgin Islands (British)",
        "VI" => "Virgin Islands (U.S.)",
        "WF" => "Wallis And Futuna Islands",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "YU" => "Yugoslavia",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe"
    );

    $output = '<select name="ldd[country]">';

    foreach ( $_countries as $key => $value )
    {
        $output .= '<option value="' . $key . '"';
        if ( isset( $_SESSION['ldd']['country'] ) && $key == $_SESSION['ldd']['country'] ) {
            $output .= ' selected ';
        }
        $output .= '>' . $value . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function lddlite_submit_last_page_url( $steps = 2 )
{
    // Get our current location and parse it.
    $url = parse_url( esc_url( $_SERVER['HTTP_HOST'] ) . $_SERVER['REQUEST_URI'] );
    parse_str( $url['query'], $query );

    // How many steps do we need to go back?
    $query['segment'] -= intval( $steps );

    // If we're at page one, just take the query segment off.
    if ( $query['segment'] == 1 )
        unset( $query['segment'] );

    // Rebuild and relocate.
    $url = 'http://' . $url['host'] . $url['path'] . '?' . http_build_query( $query );

    header( 'Location: ' . $url );

}
/*
         { // Builds the category list for the submission form.
            $categories_list = $wpdb->get_results(
                "
			SELECT *
			FROM {$tables['cat']}
			"
            );

            $business_categories = "<div class='lddbd_input_holder'>";
            $business_categories .= "<label for='categories_multiselect'>Categories</label>";
            $business_categories .= "<select id='lddbd_categories_multiselect' name='categories_multiselect' multiple='multiple'>";

            foreach($categories_list as $category){
                $cat_name = stripslashes($category->name);
                $business_categories .= "<option value='x{$category->id}x'>{$cat_name}</option>";
            }

            $business_categories .= "</select>";
            $business_categories .= "<input id='lddbd_categories' type='hidden' name='categories'/>";
            $business_categories .= "</div>";

        }

        $template_vars = array(
            'form_action'           => LDDLITE_AJAX,
            'country_select'        => '<option value="USA">United States of America</option>',
            'display_categories'    => $business_categories,
        );

        echo lddlite_parse_template( 'display/submit', $template_vars );

 */