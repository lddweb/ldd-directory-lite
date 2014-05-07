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
        $lddlite = lddlite();
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
    $tpl = ld_get_tpl();
    $tpl->assign( 'placeholder', __( 'Search the directory...', lddslug() ) );
    $tpl->assign( 'search_text', __( 'Search', lddslug() ) );
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

    $subdivision_file = LDDLITE_PATH . '/includes/views/select/subdivision.' . $subdivision . '.inc';

    return ld_split_file_into_array( $subdivision_file );
}


function ld_get_country_array() {

    $country_file = LDDLITE_PATH . '/includes/views/select/countries.inc';

    return ld_split_file_into_array( $country_file );
}
