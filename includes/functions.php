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


function ld_get_search_form() {
    $tpl = ldd::tpl();
    $tpl->assign( 'placeholder', __( 'Search the directory...', ldd::$slug ) );
    $tpl->assign( 'search_text', __( 'Search', ldd::$slug ) );
    $tpl->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    return $tpl->draw( 'display/search-form', 1 );
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

    return ld_split_file_into_array( $subdivision_file );
}


function ld_get_country_array() {

    $country_file = LDDLITE_PATH . '/includes/actions/select/countries.inc';

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

    $meta = array();

    $meta['address_one'] = get_post_meta( $id, '_lddlite_address_one', 1 );
    $meta['address_two'] = get_post_meta( $id, '_lddlite_address_two', 1 );
    $meta['city']        = get_post_meta( $id, '_lddlite_city', 1 );
    $meta['subdivision'] = get_post_meta( $id, '_lddlite_subdivision', 1 );
    $meta['post_code']   = get_post_meta( $id, '_lddlite_post_code', 1 );

    $address = '';

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

    } else {
        $address = '';
    }

    $meta['address'] = $address;
    $meta['geocode'] = urlencode( str_replace( '<br>', ' ', $address ) );

    $website = get_post_meta( $id, '_lddlite_urls_website', 1 );
    if ( $website )
        $meta['website'] = apply_filters( 'lddlite_listing_website', sprintf( '<a href="%1$s">%1$s</a>', esc_url( $website ) ) );

    $phone = get_post_meta( $id, '_lddlite_contact_phone', 1 );
    if ( $phone )
        $meta['phone'] = ld_format_phone( $phone );

    return $meta;

}


function ld_get_social( $id ) {

    if ( !is_int( $id ) )
        return false;

    $titles = array(
        'facebook'  => 'Visit %1$s on Facebook',
        'twitter'   => 'Follow %1$s on Twitter',
        'linkedin'  => 'Connect with %1$s on LinkedIn',
        'default'   => 'Visit %1$s on %2$s',
    );

    $output = '';
    $email = get_post_meta( $id, '_lddlite_contact_email', 1 );

    if ( $email )
        $output = '<a rel="contact" href="#contact"><img src="' . LDDLITE_URL . '/public/images/social/email.png" width="48" height="48" alt="" /></a>';

    $social = array(
        'facebook'  =>  get_post_meta( $id, '_lddlite_url_facebook', 1 ),
        'linkedin'  =>  get_post_meta( $id, '_lddlite_url_linkedin', 1 ),
        'twitter'   =>  get_post_meta( $id, '_lddlite_url_twitter', 1 ),
    );

    foreach ( $social as $key => $url ) {
        if ( !empty( $url ) ) {
            $title_key = array_key_exists( $key, $titles ) ? $titles[$key] : $titles['default'];
            $title = sprintf( $title_key, $name, $key );

            $output .= '<a href="' . esc_url( $url ) . '" title="' . $title . '">';
            $output .= '<img src="' . LDDLITE_URL . '/public/images/social/' . $key . '.png" width="48" height="48"></a>';
        }
    }

    return $output;
}
