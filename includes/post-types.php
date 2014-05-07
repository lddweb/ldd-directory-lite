<?php
/**
 * Filters related to our custom post type.
 *
 * Post types are registered in setup.php, all actions and filters in this file are related
 * to customizing the way WordPress handles our custom post types and taxonomies.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


function ld_filter__term_link( $termlink ) {
    global $post;

    $link = explode( '?', $termlink);

    if ( count( $link ) < 2 )
        return $termlink;

    parse_str( $link[1], $link );

    $permalink = get_permalink( $post->ID );

    if ( $permalink && isset( $link[LDDLITE_TAX_CAT] ) )
        $termlink = $permalink . '?show=category&t=' . $link[LDDLITE_TAX_CAT];

    return $termlink;
}


function ld_filter__post_type_link( $post_link, $post ) {

    if ( LDDLITE_POST_TYPE != get_post_type( $post->ID ) )
        return $post_link;

    $shortcode_id = ld_get_shortcode_id();

    $permalink = get_permalink( $shortcode_id );

    return ( $permalink . '?show=listing&t=' . $post->post_name );
}


function ld_filter__enter_title_here ( $title ) {
    if ( get_post_type() == LDDLITE_POST_TYPE )
        $title = __( 'Business Name', lddslug() );

    return $title;
}


function ld_filter__admin_post_thumbnail_html( $content ) {
    if ( LDDLITE_POST_TYPE == get_post_type() )
        $content = str_replace( __( 'Set featured image' ), __( 'Upload A Logo', lddslug() ), $content);

    return $content;
}


function ld_action__admin_menu_icon() {
    echo "\n\t<style>";
    echo '#adminmenu .menu-icon-' . LDDLITE_POST_TYPE . ' div.wp-menu-image:before { content: \'\\f307\'; }';
    echo '</style>';
}


function ld_action__submenu_title() {
    global $submenu;
    $submenu['edit.php?post_type=' . LDDLITE_POST_TYPE][5][0] = 'All Listings';
}


add_filter( 'term_link', 'ld_filter__term_link' );
add_filter( 'post_type_link', 'ld_filter__post_type_link', 10, 2 );
add_filter( 'enter_title_here', 'ld_filter__enter_title_here' );
add_filter( 'admin_post_thumbnail_html', 'ld_filter__admin_post_thumbnail_html' );

add_action( 'admin_head', 'ld_action__admin_menu_icon' );
add_action( '_admin_menu', 'ld_action__submenu_title' );




