<?php

/**
 *
 */

add_filter( 'wp_list_categories', 'ld_filter_categories_count');
function ld_filter_categories_count( $links ) {
    $links = preg_replace( '/\(([0-9]{0,5})\)/', '<span class="category-count">($1)</span>', $links );
    return $links;
}

function ld_view_home( $term = false ) {
    global $post;

    wp_enqueue_script( 'ldd-lite-search' );

    $categories = wp_list_categories( array(
        'echo'          => 0,
        'hide_empty'    => 0,
        'title_li'      => '',
        'taxonomy'      => LDDLITE_TAX_CAT,
        'pad_counts'    => 1,
        'show_count'    => 1,
    ) );

    $template_vars = array(
        'url'      => get_permalink( $post->ID ),
        'search_form'   => ld_get_search_form(),
        'categories'    => $categories,
    );

    return ld_parse_template( 'display/home', $template_vars );
}

