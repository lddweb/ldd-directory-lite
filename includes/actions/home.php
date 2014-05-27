<?php

/**
 *
 */


function ldl_action__home( $term = false ) {
    global $post;

    // Retrieve all featured listings
    $featured_output = '';

    $featured_args = array(
        'posts_per_page'    => 3,
        'post_type'         => LDDLITE_POST_TYPE,
        'tax_query' => array(
            array(
                'taxonomy'  => LDDLITE_TAX_TAG,
                'field'     => 'slug',
                'terms'     => 'featured',
            ),
        ),
    );
    $featured = get_posts( $featured_args );

    if ( $featured ) {
        $rand_keys = array_rand( $featured, 3 );
        shuffle( $rand_keys );

        // @todo can we filter guid once and use that as our url?
        foreach ( $rand_keys as $key ) {
            $listing = $featured[ $key ];
            $featured_tpl = ldl::tpl();

            $id = $listing->ID;
            $summary = $listing->post_excerpt;
            $slug = $listing->post_name;
            $title = $listing->post_title;
            $link = add_query_arg( array(
                'show'  => 'listing',
                't'     => $slug,
            ) );

            if ( empty( $summary ) ) {
                $summary = $listing->post_content;

                $summary = strip_shortcodes( $summary );

                $summary = apply_filters( 'lddlite_the_content', $summary );
                $summary = str_replace( ']]>', ']]&gt;', $summary );

                $excerpt_length = apply_filters( 'lddlite_featured_excerpt_length', 25 );
                $summary = wp_trim_words( $summary, $excerpt_length, '&hellip;' );
            }

            $link_mask = '<a href="' . $link . '" title="' . esc_attr( $title ) . '">%1$s</a>';

            if ( has_post_thumbnail( $id ) )
                $thumbnail = sprintf( $link_mask, get_the_post_thumbnail( $id, 'directory-listing-featured', array( 'class' => 'img-rounded' ) ) );
            else
                $thumbnail = sprintf( $link_mask, '<img src="' . LDDLITE_URL . '/public/images/noimage.png" class="img-rounded">' );

            $featured_tpl->assign( 'thumbnail', $thumbnail );
            $featured_tpl->assign( 'title',     $title );
            $featured_tpl->assign( 'summary',   $summary );
            $featured_tpl->assign( 'link',      $link );

            $featured_output .= $featured_tpl->draw( 'featured', 1 );
        }

    }


    $directory_terms = get_terms( LDDLITE_TAX_CAT, array(
        'parent'         => 0,
    ) );

    $categories = '';
    foreach ( $directory_terms as $category ) {
        $term_link = add_query_arg( array(
            'show'  => 'category',
            't'     => $category->slug,
        ) );
        $categories .= sprintf( '<a href="%1$s" class="list-group-item"><span class="badge badge-default">%3$d</span>%2$s</a>', $term_link, $category->name, $category->count );
    }

    $tpl = ldl::tpl();

    $tpl->assign( 'header', ldl_get_header( 1 ) );
    $tpl->assign( 'loading', ldl_get_loading_gif() );
    $tpl->assign( 'featured', $featured_output );
    $tpl->assign( 'categories', $categories );

    return $tpl->draw( 'home', 1 );
}

