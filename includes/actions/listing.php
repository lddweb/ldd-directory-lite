<?php

/**
 *
 */


function ld_action__listing( $listing ) {
    global $post;

    wp_enqueue_style( 'font-awesome' );

    $tpl = ldd::tpl();

    $permalink = get_permalink( $post->ID );
    $id = $listing->ID;

    $top = is_admin_bar_showing() ? 32 : 0;


    if ( has_post_thumbnail( $id ) )
        $logo = get_the_post_thumbnail( $id, 'directory-listing' );
    else
        $logo = '<img src="' . LDDLITE_URL . '/public/images/avatar_default.png" />';

    $meta = ld_get_listing_meta( $id );

    $get_address = 'http://maps.google.com/maps/api/geocode/json?address=' . $meta['geocode'] . '&sensor=false';
    $geocode = wp_remote_get( $get_address );

    $output = json_decode( $geocode['body'] );

    $geocode = array(
        'lat' => $output->results[0]->geometry->location->lat,
        'lng' => $output->results[0]->geometry->location->lng,
    );

    $tpl->assign( 'search', ld_get_search_form() );
    $tpl->assign( 'url', $permalink );
    $tpl->assign( 'title', $listing->post_title );

    $tpl->assign( 'id', $id );
    $tpl->assign( 'top', $top );
    $tpl->assign( 'logo', $logo );
    $tpl->assign( 'meta',       $meta );
    $tpl->assign( 'address',    $meta['address'] );
    $tpl->assign( 'social', ld_get_social( $id ) );
    $tpl->assign( 'geo', $geocode );
    $tpl->assign( 'description', wpautop( $listing->post_content ) );

    // Contact Form
    // @todo we're going to let people override this with Ninja Forms

    $contact_tpl = ldd::tpl();
    $contact_tpl->assign( 'id', $id );
    $contact_tpl->assign( 'form_action', admin_url( 'admin-ajax.php' ) );
    $contact_tpl->assign( 'nonce', wp_create_nonce( 'contact-form-nonce' ) );
    $tpl->assign( 'contact_form', $contact_tpl->draw( 'display/listing-contact', 1 ) );

    return $tpl->draw( 'display/listing', 1 );
}
