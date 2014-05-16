<?php
/**
 * Handles setup of the [directory] shortcode.
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


function ld_get_allowed_actions() {

    $allowed_actions = array(
        'submit',
        'category',
        'listing',
        'search',
    );

    return apply_filters( 'lddlite_allowed_actions', $allowed_actions );
}


function ld_is_action_requested() {

    $allowed_actions = ld_get_allowed_actions();

    if ( !isset( $_GET['show'] ) )
        return false;

    return ( in_array( $_GET['show'], $allowed_actions ) ? true : false );
}


function ld_shortcode__display() {

    wp_enqueue_script( ldd::$slug . '-search' );
    wp_enqueue_script( 'ldd-lite-js' );

    wp_enqueue_style( 'ldd-lite' );

/*    ldd::$modal['url'] = $_SERVER['REQUEST_URI'];
    add_action( 'wp_footer', 'ld_append_login_form' );*/


    $action = 'home';
    $term   = '';

    if ( ld_is_action_requested() ) {

        $action = $_GET['show'];
        $t = isset( $_GET['t'] ) ? esc_attr( $_GET['t'] ) : '';

        if ( 'category' == $action ) {

            $exists = term_exists( $t, LDDLITE_TAX_CAT );
            if ( is_array( $exists ) )
                $term = $exists['term_id'];

        } else if ( 'listing' == $action ) {

            $listing = get_posts( array(
                'name'              => $t,
                'post_type'         => LDDLITE_POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'    => 1,
            ) );

            if ( !empty( $listing ) ) {
                $term = $listing[0];
                ldd::attach( $listing[0] );
            }

        } else if ( 'submit' == $action ) {

            if ( 'listing' == $t )
                $term = $t;

        }

        if ( empty( $term ) )
            $action = 'home';

    }


    require_once( LDDLITE_PATH . '/includes/actions/' . $action . '.php' );

    $func = 'ld_action__' . $action;

    return $func( $term );

}


/**
 * This is an alias of ld_shortcode__display() if anyone wants to embed the directory via PHP
 *
 * @param bool $echo Whether to echo or return
 * @return mixed The output of ld_shortcode__display() if $echo is false
 */
function ld_display_the_directory( $echo = true ) {

    if ( $echo )
        echo ld_shortcode__display();
    else
        return ld_shortcode__display();

}
