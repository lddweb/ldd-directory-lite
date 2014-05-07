<?php

/**
 *
 */


function ld_action__listing( $listing ) {
    global $post;

    $permalink = get_permalink( $post->ID );
    $post_id = $listing->ID;

    $top = is_admin_bar_showing() ? 32 : 0;


    if ( has_post_thumbnail( $post_id ) )
        $logo = get_the_post_thumbnail( $post_id, 'thumbnail' );
    else
        $logo = '<img src="' . LDDLITE_URL . '/public/images/avatar_default.png" />';


    $template_vars = array(
        'search'        => ld_get_search_form(),
        'url'           => $permalink,
        'title'         => $listing->post_title,
        'form_action'   => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'contact-form-nonce' ),
        'id'            => $post->ID,
        'top'           => $top,
        'logo'          => $logo,
    );

    return ld_parse_template( 'display/listing', $template_vars );
}
