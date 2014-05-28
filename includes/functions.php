<?php
/**
 * General functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


function ldl_get_default_settings() {
    $site_title = get_bloginfo( 'name' );
    $admin_email = get_bloginfo( 'admin_email' );

    $signature = <<<SIG


*****************************************
This is an automated message from {$site_title}
Please do not respond directly to this email
SIG;

    $email = array();

    $email['to_admin']   = <<<EM
<p><strong>A new listing is pending review!</strong></p>

<p>This submission is awaiting approval. Please visit the link to view and approve the new listing:</p>

<p>{approve_link}</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
    $email['on_submit']  = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing is pending approval.</p>
<p>Please review the following information for accuracy, as this is what will appear on our web site. If you see any errors, please contact us immediately at {directory_email}.</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
    $email['on_approve'] = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing has been approved! You can now view it online:</p>
<p>{link}</p>
EM;

    foreach ( $email as $key => $msg )
        $email[ $key ] = $msg . $signature;

    $defaults = apply_filters( 'lddlite_default_options', array(
        'directory_label'           => get_bloginfo( 'name' ),
        'directory_description'     => '',
        'directory_page'            => '',
        'disable_bootstrap'         => 0,
        'public_or_private'         => 1,
        'google_maps'               => 1,
        'email_admin'             => get_bloginfo( 'admin_email' ),
        'email_toadmin_subject'     => 'A new listing has been submitted for review!',
        'email_toadmin_body'        => $email['to_admin'],
        'email_onsubmit_subject'    => 'Your listing on ' . $site_title . ' is pending review!',
        'email_onsubmit_body'       => $email['on_submit'],
        'email_onapprove_subject'   => 'Your listing on ' . $site_title . ' was approved!',
        'email_onapprove_body'      => $email['on_approve'],
        'submit_use_tos'            => 0,
        'submit_tos'                => '',
        'submit_use_locale'         => 0,
        'submit_locale'             => 'US',
    ) );

    return $defaults;
}


function ldl_get_loading_gif() {
    $img = '<img src="' . LDDLITE_URL . '/public/images/loading.gif" width="32" height="32">';
    return $img;
}

function ldl_use_tos() {
    return ldl::setting( 'submit_use_tos' );
}


function ldl_use_locale() {
    return ldl::setting( 'submit_use_locale' );
}


function ldl_get_locale() {
    return ldl_use_locale() ? ldl::setting( 'submit_locale' ) : 'US';
}


function ldl_is_public() {
    return ldl::setting( 'public_or_private' );
}


function ldl_use_google_maps() {
    return ldl::setting( 'google_maps' );
}


function ldl_get_page_haz_shortcode( $force = false) {

    if ( ldl::setting( 'directory_page' ) )
        return ldl::setting( 'directory_page' );

    $shortcode_id = get_transient( 'ldd_shortcode_id' );

    if ( false !== $shortcode_id )
        return $shortcode_id;

    $posts = get_posts( array(
        'posts_per_page'    => -1,
        'post_type'         => 'page',
    ) );

    global $shortcode_tags;

    // Store this, we don't want to permanently change it
    $old_shortcode_tags = $shortcode_tags;

    // Remove everything but our shortcodes
    $shortcode_tags = array_intersect_key( $shortcode_tags, array(
        'directory' => '',
        'business_directory' => '',
    ) );

    $pattern = get_shortcode_regex();
    foreach ( $posts as $post ) {
        if ( preg_match( "/$pattern/s", $post->post_content ) ) {
            $shortcode_id = $post->ID;
            break;
        }
    }

    // Reset the global array
    $shortcode_tags = $old_shortcode_tags;

    if ( false !== $shortcode_id )
        set_transient( 'ldd_shortcode_id', $shortcode_id, 3600 );

    return $shortcode_id;
}


function  ldl_get_header( $show_label = 0 ) {

    wp_enqueue_script( 'lddlite-search' );

    $tpl = ldl::tpl();

    $tpl->assign( 'show_label', $show_label );
    $tpl->assign( 'directory_label', ldl::setting( 'directory_label' ) );
    $tpl->assign( 'directory_description', ldl::setting( 'directory_description' ) );

    $tpl->assign( 'public', ldl_is_public() );
    $tpl->assign( 'submit_link', add_query_arg( array( 'show' => 'submit', 't' => 'listing' ) ) );

    $tpl->assign( 'form_action', admin_url( 'admin-ajax.php' ) );
    $tpl->assign( 'nonce', wp_create_nonce( 'search-form-nonce' ) );
    $tpl->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );

    $tpl->assign( 'is_logged_in', (int) is_user_logged_in() );

    return $tpl->draw( 'header', 1 );
}


function ldl_get_term_name( $term_id ) {
    $term_id = (int) $term_id;
    $term = get_term_by( 'term_id', $term_id, LDDLITE_TAX_CAT );
    if ( !$term || is_wp_error( $term ) )
        return '';
    return $term->name;
}


function ldl_split_file_into_array( $arrfile ) {

    if ( !file_exists( $arrfile ) )
        return false;

    $lines = file( $arrfile );
    $data = array();

    foreach ( $lines as $line ) {
        $kv = explode( ',', $line );
        $data[ $kv[0] ] = str_replace( array( "\r", "\n" ), '', $kv[1] );
    }

    return $data;
}


function ldl_get_subdivision_array( $subdivision ) {

    $subdivision_file = LDDLITE_PATH . '/includes/actions/select/subdivision.' . strtolower( $subdivision ) . '.inc';

    if ( !file_exists( $subdivision_file ) )
        return false;

    return ldl_split_file_into_array( $subdivision_file );
}


function ldl_get_country_array() {

    $country_file = LDDLITE_PATH . '/includes/actions/select/countries.inc';

    if ( !file_exists( $country_file ) )
        return false;

    return ldl_split_file_into_array( $country_file );
}


function ldl_format_phone( $phone, $locale = 'US' ) {

    if ( 'US' == $locale ) {
        $phone = preg_replace( '/[^[:digit:]]/', '', $phone );
        if ( 10 == strlen( $phone ) ) {
            preg_match( '/(\d{3})(\d{3})(\d{4})/', $phone, $match );
            return "({$match[1]}) {$match[2]}-{$match[3]}";
        }
    }

    return $phone; // because I lost it
}


function ldl_get_listing_meta( $id ) {

    if ( !is_int( $id ) || LDDLITE_POST_TYPE != get_post_type( $id ) )
        return false;

    $defaults = array(
        'address_one' => '',
        'address_two' => '',
        'city' => '',
        'subdivision' => '',
        'post_code' => '',
        'address' => '',
        'geocode' => '',
        'website' => '',
        'email' => '',
        'phone' => '',
    );

    $meta['address_one'] = get_post_meta( $id, '_lddlite_address_one', 1 );
    $meta['address_two'] = get_post_meta( $id, '_lddlite_address_two', 1 );
    $meta['city']        = get_post_meta( $id, '_lddlite_city', 1 );
    $meta['subdivision'] = get_post_meta( $id, '_lddlite_subdivision', 1 );
    $meta['post_code']   = get_post_meta( $id, '_lddlite_post_code', 1 );

    $address = '';
    $geocode = '';

    foreach ( $meta as $key => $value ) {
        if ( 'address_two' != $key && empty( $value ) ) {
            $address = false;
            break;
        }
    }

    if ( false !== $address ) {

        $address = $meta['address_one'];
        if ( !empty( $meta['address_two'] ) )
            $address .= '<br>' . $meta['address_two'];
        $address .= ',<br>' . $meta['city'] . ', ' . $meta['subdivision'] . ' ' . $meta['post_code'];

        $geocode = urlencode( str_replace( '<br>', ' ', $address ) );

    } else {
        $address = '';
    }

    $meta['address'] = $address;
    $meta['geocode'] = $geocode;

    $website = get_post_meta( $id, '_lddlite_url_website', 1 );
    if ( $website )
        $meta['website'] = apply_filters( 'lddlite_listing_website', sprintf( '<a href="%1$s"><i class="fa fa-external-link"></i>  %1$s</a>', esc_url( $website ) ) );

    $meta['email'] = get_post_meta( $id, '_lddlite_contact_email', 1 );

    $phone = get_post_meta( $id, '_lddlite_contact_phone', 1 );
    if ( $phone )
        $meta['phone'] = ldl_format_phone( $phone );

    $meta = wp_parse_args( $meta, $defaults );

    return $meta;

}


function ldl_get_social( $id ) {

    if ( !is_int( $id ) )
        return false;

    $titles = array(
        'facebook-square' => 'Visit %1$s on Facebook',
        'linkedin'        => 'Connect with %1$s on LinkedIn',
        'twitter'         => 'Follow %1$s on Twitter',
        'default'         => 'Visit %1$s on %2$s',
    );

    $output = '';
    $email = get_post_meta( $id, '_lddlite_contact_email', 1 );
    $name = get_the_title( $id );


    if ( $email )
        $output = '    <a href="" class="btn btn-success" data-toggle="modal" data-target="#contact-listing-owner"><i class="fa fa-envelope"></i></a>';

    $social = array(
        'facebook-square' =>  'http://', //get_post_meta( $id, '_lddlite_url_facebook', 1 ),
        'linkedin'        =>  'http://', //get_post_meta( $id, '_lddlite_url_linkedin', 1 ),
        'twitter'         =>  'http://', //get_post_meta( $id, '_lddlite_url_twitter', 1 ),
    );

    foreach ( $social as $key => $url ) {
        if ( !empty( $url ) ) {
            $title_key = array_key_exists( $key, $titles ) ? $titles[ $key ] : $titles['default'];
            $title = sprintf( $title_key, $name, $key );

            $output .= '<a href="' . esc_url( $url ) . '" title="' . $title . '" class="btn btn-success">';
            $output .= '<i class="fa fa-' . $key . '"></i></a>';
        }
    }

    return $output;
}


function ldl_dropdown_subdivision( $subdivision, $data, $tabindex = 0 ) {

    $selected = isset( $data['subdivision'] ) ? $data['subdivision'] : '';
    $lines = '';

    if ( !empty( $subdivision ) )
        $lines = ldl_get_subdivision_array( $subdivision );

    $tabindex = $tabindex ? 'tabindex="' . $tabindex . '"' : '';

    if ( !$lines )
        return '<input id="subdivision" class="form-control" name="ld_s_subdivision" type="text" value="' . $selected . '" ' . $tabindex . ' required>';

    $output = '<select id="subdivision" class="form-control" name="ld_s_subdivision" ' . $tabindex . ' required>';

    foreach ( $lines as $key => $value ) {
        $output .= '<option value="' . $key . '"';
        if ( $selected == $key ) $output .= ' selected';
        $output .= '>' . $value . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ldl_dropdown_country( $name, $data = '', $tabindex = 0 ) {

    $selected = '';
    if ( !is_array( $data )  )
        $selected = $data;
    else if ( isset( $data['country'] ) )
        $selected = $data['country'];

    $tabindex = $tabindex ? 'tabindex="' . $tabindex . '"' : '';

    $countries = ldl_get_country_array();

    if ( !$countries )
        return '<input id="country" class="form-control" name="' . $name . '" type="text" ' . $tabindex . ' required>';

    $output  = '<select id="country" class="form-control" name="' . $name . '" ' . $tabindex . ' required>';
    //$output .= '<option value="">Select a Country...</option>';

    foreach ( $countries as $code => $name ) {
        $output .= '<option value="' . $code . '"';
        if ( $selected == $code ) $output .= ' selected';
        $output .= '>' . $name . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ldl_sanitize_phone( $number ) {
    return preg_replace( '/[^0-9+]/', '', $number );
}


function ldl_get_listing_email( $id ) {
    return get_post_meta( $id, '_lddlite_contact_email', 1 );
}


function ldl_sanitize_twitter( $input ) {

    $output = preg_replace( '/[^A-Za-z0-9\/:.]/', '', $input );

    if ( strpos( $output, '/' ) !== false )
        $output = substr( $input, strrpos( $output, '/' ) + 1 );

    $output = 'https://twitter.com/' . $output;

    return $output;
}

function ldl_sanitize_https( $url ) {

    if ( strpos( $url, 'http') !== 0 )
        $url = esc_url_raw( $url );

    return preg_replace( '~http:~', 'https:', $url );
}


/**
 * Shamelessly taken from http://james.cridland.net/code/format_uk_phonenumbers.html
 */
function ldl_format_uk( $number ) {



    // Change the international number format and remove any non-number character
    $number = preg_replace( '/[^0-9]/', '', str_replace( '+', '00', $number ) );
    $arr    = ldl_uk_split( $number, explode( ',', ldl_get_uk_format( $number ) ) );

    // Add brackets around first split of numbers if number starts with 01 or 02
    if ( substr( $number, 0, 2) == '01' || substr( $number, 0, 2) == '02' )
        $arr[0] = '(' . $arr[0] . ')';

    // Convert array back into string, split by spaces
    $formatted = implode( ' ', $arr );

    return $formatted;
}

function ldl_uk_split( $number, $split ) {
    $start = 0;
    $array = array();
    foreach ( $split as $value ) {
        $array[] = substr( $number, $start, $value );
        $start = $start + $value;
    }
    return $array;
}

function ldl_get_uk_format($number) {

    // This uses full codes from http://www.area-codes.org.uk/formatting.shtml
    $formats = array (
        '02'        => '3,4,4',
        '03'        => '4,3,4',
        '05'        => '3,4,4',
        '0500'      => '4,6',
        '07'        => '5,6',
        '070'       => '3,4,4',
        '076'       => '3,4,4',
        '07624'     => '5,6',
        '08'        => '4,3,4',
        '09'        => '4,3,4',
        '01'        => '5,6',
        '011'       => '4,3,4',
        '0121'      => '4,3,4',
        '0131'      => '4,3,4',
        '0141'      => '4,3,4',
        '0151'      => '4,3,4',
        '0161'      => '4,3,4',
        '0191'      => '4,3,4',
        '013873'    => '6,5',
        '015242'    => '6,5',
        '015394'    => '6,5',
        '015395'    => '6,5',
        '015396'    => '6,5',
        '016973'    => '6,5',
        '016974'    => '6,5',
        '016977'    => '6,5',
        '0169772'   => '6,4',
        '0169773'   => '6,4',
        '017683'    => '6,5',
        '017684'    => '6,5',
        '017687'    => '6,5',
        '019467'    => '6,5'
    );

    // uksort, pardon the pun
    uksort( $formats, 'ldl_uk_sort_callback' );

    foreach ( $formats as $k => $v ) {
        if ( substr( $number, 0, strlen( $k ) ) == $k )
            break;
    }

    return $v;
}

function ldl_uk_sort_callback( $a, $b ) {
    return strlen( $b ) - strlen( $a );
}


/**
 * Alias for wp_mail that sets headers for us.
 *
 * @since 1.3.13
 * @param string $to Email address this message is going to
 * @param string $subject Email subject
 * @param string $message Email contents
 * @param string $headers Optional, default is managed internally.
 */
function ldl_mail($to, $subject, $message, $headers = '' ) {

    // If we're not passing any headers, default to our internal from address
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: LDD Business Directory <' . get_option('admin_email') . '>' . "\r\n";
    }

    ob_start();
    wp_mail($to, $subject, $message, $headers);
    ob_end_clean();

}
