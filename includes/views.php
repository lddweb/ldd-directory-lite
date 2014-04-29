<?php

add_filter( 'term_link', 'lddlite_category_links' );
function lddlite_category_links( $termlink ) {
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

    $termlink = $current_url . '?show=category&t=' . $link[LDDLITE_TAX_CAT];

    return $termlink;
}


function ld_get_search_form() {
    return ld_parse_template( 'display/search_form', array() );
}


function lddlite_display_directory()
{
    global $post;

    $action = 'home';
    $term   = '';

    $allowed_actions = array(
        'submit',
        'category',
        'business',
        'search',
    );

    if ( isset( $_GET['show'] ) && in_array( $_GET['show'], $allowed_actions ) && isset( $_GET['t'] ) )
    {
        $action = $_GET['show'];
        $term = esc_attr( $_GET['t'] );

        if ( 'category' == $action )
        {

            $term = term_exists( $term, LDDLITE_TAX_CAT );
            if ( !$term ) {
                $action = 'home';
            } else {
                $term = $term->term_id;
            }

        }
        else if ( 'business' == $action )
        {

            $listing = get_posts( array(
                'name'              => $term,
                'post_type'         => LDDLITE_POST_TYPE,
                'post_status'       => 'publish',
                'posts_per_page'    => 1,
            ) );

            if ( empty( $listing ) ) {
                $action = 'home';
            } else {
                $term = $listing[0];
            }

        }

    }


    require_once( LDDLITE_PATH . '/includes/views/' . $action . '.php' );

    $func = 'lddlite_display_view_' . $action;

    return $func( $term );

}
