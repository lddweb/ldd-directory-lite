<?php

function ld_new_template() {

    require_once( LDDLITE_PATH . '/includes/class.raintpl.php' );

    raintpl::configure( 'tpl_ext',      'tpl' );
    raintpl::configure( 'tpl_dir',      LDDLITE_PATH . '/templates/' );
    raintpl::configure( 'cache_dir',    LDDLITE_PATH . '/cache/' );

    return new raintpl;
}


function ld_get_search_form() {
    $tpl = ld_new_template();
    $tpl->assign( 'placeholder', __( 'Search the directory...', lddslug() ) );
    $tpl->assign( 'search_text', __( 'Search', lddslug() ) );
    $tpl->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    return $tpl->draw( 'display/search-form', 1 );
}


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


function ld_display_the_directory() {

    wp_enqueue_script( 'ldd-lite-js' );
    wp_enqueue_script( 'ldd-lite-responsiveslides' );

    wp_enqueue_style( 'ldd-lite' );
    wp_enqueue_style( 'yui-pure' );

    $action = 'home';
    $term   = '';

    $allowed_actions = array(
        'submit',
        'category',
        'listing',
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
        else if ( 'listing' == $action )
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

    $func = 'ld_view_' . $action;

    return $func( $term );
}
