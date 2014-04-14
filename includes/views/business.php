<?php

/**
 *
 */


function lddlite_display_view_business()
{
    global $post;


    $template_vars = array(
        'search'    => lddlite_get_search_form(),
        'url'       => get_permalink( $post->ID ),

    );

    return lddlite_parse_template( 'display/business', $template_vars );

}
