<?php

/**
 *
 */


function lddlite_display_view_business()
{
    global $post;

    $top = is_admin_bar_showing() ? 32 : 0;

    $template_vars = array(
        'search'        => lddlite_get_search_form(),
        'url'           => get_permalink( $post->ID ),
        'form_action'   => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'contact-form-nonce' ),
        'id'            => $post->ID,
        'top'           => $top,
    );

    return lddlite_parse_template( 'display/business', $template_vars );

}
