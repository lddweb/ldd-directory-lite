<?php

/**
 *
 */

function lddlite_display_view_home()
{
    global $post;

    $categories = wp_list_categories( array(
        'echo'          => 0,
        'hide_empty'    => 0,
        'title_li'      => '',
        'taxonomy'      => LDDLITE_TAX_CAT,
        'pad_counts'    => 1,
    ) );

    $template_vars = array(
        'url'           => get_permalink( $post->ID ),
        'categories'    => $categories,
    );

    return lddlite_parse_template( 'display/home', $template_vars );
}
