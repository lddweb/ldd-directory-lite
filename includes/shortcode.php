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

function ldl_enqueue() {

    if ( !ldl::setting( 'disable_bootstrap' ) ) {
        wp_enqueue_style( 'lddlite-bootstrap' );
        wp_enqueue_script( 'lddlite-bootstrap' );
    }

    wp_enqueue_style( 'lddlite' );
    wp_enqueue_style( 'lddlite-bootflat' );
    wp_enqueue_style( 'font-awesome' );

}


function ldl_get_template( $slug, $name = '' ) {

    do_action( "lddlite_get_template_part_{$slug}", $slug, $name );

    if ( '' !== $name )
        $_template = "{$slug}-{$name}.php";
    else
        $_template = "{$slug}.php";

    $located = '';

    $locations = array(
        'child'  => STYLESHEETPATH . '/directory/' . $_template,
        'parent' => TEMPLATEPATH . '/directory/' . $_template,
        'plugin' => LDDLITE_PATH . '/templates/' . $_template,
    );

    foreach ( $locations as $path ) {
        if ( file_exists( $path ) ) {
            $located = $path;
            break;
        }
    }

    if ( '' != $located ) {
        ob_start();
        require( $located );
        $located = ob_get_contents();
        ob_end_clean;
    }

    return $located;
}


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

    ldl_enqueue();

    $action = isset( $_GET['show'] ) ? $_GET['show'] : 'home';
    $term   = '';

    if ( ld_is_action_requested() ) {

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
                ldl::attach( $listing[0] );
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
