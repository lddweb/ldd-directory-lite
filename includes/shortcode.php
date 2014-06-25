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



function ldl_get_allowed_actions() {

    $allowed_actions = array(
        'submit',
        'category',
        'listing',
        'search',
    );

    return apply_filters( 'lddlite_allowed_actions', $allowed_actions );
}


function ldl_is_action_requested() {

    $allowed_actions = ldl_get_allowed_actions();

    if ( !isset( $_GET['show'] ) )
        return false;

    return ( in_array( $_GET['show'], $allowed_actions ) ? true : false );
}


function ldl_shortcode__display() {

    ldl_enqueue();

    $action = isset( $_GET['show'] ) ? $_GET['show'] : 'home';
    $term   = '';

    if ( ldl_is_action_requested() ) {

        $t = isset( $_GET['t'] ) ? esc_attr( $_GET['t'] ) : '';

        if ( 'category' == $action ) {

            $exists = term_exists( $t, LDDLITE_TAX_CAT );
            if ( is_array( $exists ) )
                $term = $exists['term_id'];

        } else if ( 'listing' == $action || 'search' == $action) {
            $term = $t;
        } else if ( 'submit' == $action && !is_user_logged_in() ) {
            $term = 'listing';
        }

        if ( empty( $term ) )
            $action = 'home';

    }


    require_once( LDDLITE_PATH . 'includes/actions/' . $action . '.php' );

    $func = 'ldl_action__' . $action;

    return $func( $term );

}


function ldl_shortcode__submit() {
    global $post;

    ldl_enqueue();

    wp_enqueue_script( 'lddlite-responsiveslides' );
    wp_enqueue_script( 'lddlite-submit' );

    $valid = false;

    $data = array();
    $urls = array();
    $errors = array();

    $success = false;

    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) || !empty( $_POST['ld_s_summary'] ) )
            die( "No, kitty! That's a bad kitty!" );

        ldl_submit__process( $_POST );

    }

    $category_args = array(
        'hide_empty'    => 0,
        'echo'          => 0,
        'selected'      => isset( $data['category'] ) ? $data['category'] : 0,
        'hierarchical'  => 1,
        'name'          => 'ld_s_category',
        'id'            => 'category',
        'class'         => 'form-control',
        'tab_index'     => 2,
        'taxonomy'      => LDDLITE_TAX_CAT,
    );
    set_query_var( 'category_args', $category_args );


    if ( !empty( $data ) ) {
        if ( isset( $data['url'] ) ) {
            $urls = $data['url'];
            unset( $data['url'] );
        }

        $data = array_map( 'htmlentities', $data );
    }

    set_query_var( 'data', $data );
    set_query_var( 'errors', $errors );
    set_query_var( 'success', $success );

    ldl_get_template_part( 'submit' );

}


add_shortcode( 'lddlite_submit', 'ldl_shortcode__submit' );

add_shortcode( 'directory',          'ldl_shortcode__display' );
/**
 * @deprecated since version 0.5.0, please use [directory] instead
 */
add_shortcode( 'business_directory', 'ldl_shortcode__display' );
add_shortcode( 'directory_lite',     'ldl_shortcode__display' );