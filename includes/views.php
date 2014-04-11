<?php

add_filter( 'term_link', 'lddlite_category_links' );
function lddlite_category_links( $termlink )
{
    global $post;

    $link = explode( '?', $termlink);

    if ( count( $link ) < 2 ) {
        return $termlink;
    }

    parse_str( $link[1], $link );

    if ( !isset( $link[LDDLITE_TAX_CAT] ) ) {
        return $termlink;
    }

    // @TODO Is there a situation where this won't be available? If so, use $_SERVER
    $current_url = get_permalink( $post->ID );

    $termlink = $current_url . '?show=category&slug=' . $link[LDDLITE_TAX_CAT];

    return $termlink;
}


function lddlite_process_forms()
{

    if ( isset( $_POST['current_page'] ) )
    {

    }
}


function lddlite_display_directory()
{

    $action = 'home';

    $allowed_actions = array(
        'submit',
        'category',
        'business',
        'search',
    );

    if ( isset( $_GET['show'] ) && in_array( $_GET['show'], $allowed_actions ) ) {
        $action = $_GET['show'];
    }

    require_once( LDDLITE_PATH . '/includes/views/' . $action . '.php' );

    $func = 'lddlite_display_view_' . $action;

    return $func();

}
