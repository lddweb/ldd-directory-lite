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


function ld_bootstrap() {
    if ( !ldd::opt( 'disable_bootstrap' ) ) {
        wp_enqueue_style( 'bootstrap' );
        wp_enqueue_script( 'bootstrap' );
    }
}


function ld_use_locale() {
    return ldd::opt( 'directory_use_locale' );
}


function ld_get_locale() {
    return ldd::opt( 'directory_locale' );
}


function ld_is_public() {
    return ldd::opt( 'public_or_private' );
}


function ld_use_google_maps() {
    return ldd::opt( 'google_maps' );
}


function lddslug() {
    static $slug;

    if ( !isset( $slug ) ) {
        $lddlite = ldd::load();
        $slug = $lddlite->slug();
    }

    return $slug;
}


function ld_get_shortcode_id( $force = false) {
    global $shortcode_tags;

    $shortcode_id = get_transient( 'ldd_shortcode_id' );

    if ( false !== $shortcode_id )
        return $shortcode_id;

    $posts = get_posts( array(
        'posts_per_page'    => -1,
        'post_type'         => 'page',
    ) );

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


function ld_get_tpl() {

    require_once( LDDLITE_PATH . '/includes/class.raintpl.php' );

    raintpl::configure( 'tpl_ext',      'tpl' );
    raintpl::configure( 'tpl_dir',      LDDLITE_PATH . '/templates/' );
    raintpl::configure( 'cache_dir',    LDDLITE_PATH . '/cache/' );

    return new raintpl;
}


function  ld_get_page_header( $show_label = 0 ) {

    wp_enqueue_script( 'ldd-lite-search' );

    $header_template = ldd::tpl();

    $header_template->assign( 'show_label', $show_label );
    $header_template->assign( 'directory_label', ldd::opt( 'directory_label' ) );
    $header_template->assign( 'directory_description', ldd::opt( 'directory_description' ) );

    $header_template->assign( 'public', ld_is_public() );
    $header_template->assign( 'submit_link', add_query_arg( array( 'show' => 'submit', 't' => 'listing' ) ) );

    $header_template->assign( 'form_action', admin_url( 'admin-ajax.php' ) );
    $header_template->assign( 'nonce', wp_create_nonce( 'search-form-nonce' ) );
    $header_template->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );

    return $header_template->draw( 'header', 1 );

}


function ld_get_term_name( $term_id ) {
    $term_id = (int) $term_id;
    $term = get_term_by( 'term_id', $term_id, LDDLITE_TAX_CAT );
    if ( !$term || is_wp_error( $term ) )
        return '';
    return $term->name;
}


/**
 * @deprecated use ld_get_page_header()
 */
function ld_get_search_form() {
    $tpl = ldd::tpl();
    $tpl->assign( 'placeholder', __( 'Search the directory...', ldd::$slug ) );
    $tpl->assign( 'search_text', __( 'Search', ldd::$slug ) );
    $tpl->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    return $tpl->draw( 'search-form', 1 );
}



function ld_get_social_icon( $label, $url = '', $title = '', $ext = 'png' ) {

    $icon = LDDLITE_URL . '/public/images/social/' . $label . '.' . $ext;

    if ( !file_exists( $icon ) )
        return false;

    $output = '<img src="' . $icon . '">';

    if ( !empty( $url ) ) {
        $title = ( empty( $title ) ) ? '' : sprintf( ' title="%s" ', htmlspecialchars( $title ) );
        $output = sprintf( '<a href="%1$s" %2$s class="social-icon">%3$s</a>', esc_url( $url ), $title, $output );
    }

    return $output;
}


function ld_split_file_into_array( $arrfile ) {

    if ( !file_exists( $arrfile ) )
        return false;

    $lines = file( $arrfile );
    $data = array();

    foreach ( $lines as $line ) {
        $kv = explode( ',', $line );
        $data[ $kv[0] ] = $kv[1];
    }

    return $data;
}


function ld_get_subdivision_array( $subdivision ) {

    $subdivision_file = LDDLITE_PATH . '/includes/actions/select/subdivision.' . $subdivision . '.inc';

    if ( !file_exists( $subdivision_file ) )
        return false;

    return ld_split_file_into_array( $subdivision_file );
}


function ld_get_country_array() {

    $country_file = LDDLITE_PATH . '/includes/actions/select/countries.inc';

    if ( !file_exists( $country_file ) )
        return false;

    return ld_split_file_into_array( $country_file );
}


function ld_format_phone( $phone, $locale = 'US' ) {

    if ( 'US' == $locale ) {
        $phone = preg_replace( '/[^[:digit:]]/', '', $phone );
        if ( 10 == strlen( $phone ) ) {
            preg_match( '/(\d{3})(\d{3})(\d{4})/', $phone, $match );
            return "({$match[1]}) {$match[2]}-{$match[3]}";
        }
    }

    return $phone; // because I lost it
}


function ld_get_listing_meta( $id ) {

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
        $meta['phone'] = ld_format_phone( $phone );

    $meta = wp_parse_args( $meta, $defaults );

    return $meta;

}


function ld_get_social( $id ) {

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
        'facebook-square' =>  get_post_meta( $id, '_lddlite_url_facebook', 1 ),
        'linkedin'        =>  get_post_meta( $id, '_lddlite_url_linkedin', 1 ),
        'twitter'         =>  get_post_meta( $id, '_lddlite_url_twitter', 1 ),
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


function ld_append_login_form() {

    $modal = ldd::tpl();
    $modal->assign( 'login_url', ldd::$modal['url'] );
    $modal->assign( 'login_form', wp_login_form( array( 'echo' => false ) ) );
    $modal->draw( 'modal-login' );

}


function ld_dropdown_subdivision( $subdivision, $data, $tabindex = 0 ) {

    $selected = '';
    $lines = '';

    if ( !empty( $subdivision ) ) {
        $selected = isset( $data['subdivision'] ) ? $data['subdivision'] : '';
        $lines = ld_get_subdivision_array( $subdivision );
    }

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


function ld_sanitize_phone( $number ) {
    return preg_replace( '/[^0-9+]/', '', $number );
}


function ld_get_listing_email( $id ) {
    return get_post_meta( $id, '_lddlite_contact_email', 1 );
}