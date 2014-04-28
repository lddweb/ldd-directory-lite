<?php

/**
 *
 */


function lddlite_display_view_business( $listing )
{
    global $post;

    $lddlite = lddlite();

    $permalink = get_permalink( $post->ID );
    $post_id = $listing->ID;

    $top = is_admin_bar_showing() ? 32 : 0;


    if ( has_post_thumbnail( $post_id ) ) {
        $logo = get_the_post_thumbnail( $post_id, 'thumbnail' );
    } else {
        $logo = '<img src="' . LDDLITE_URL . '/public/icons/avatar_default.png" />';
    }


    $template_vars = array(
        'search'        => lddlite_get_search_form(),
        'base_url'      => $permalink,
        'title'         => $listing->post_title,
        'form_action'   => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'contact-form-nonce' ),
        'id'            => $post->ID,
        'top'           => $top,
        'logo'          => $logo,
    );

    return lddlite_parse_template( 'display/business', $template_vars );

}
