<?php

/**
 *
 */


function ldl_get_thumbnail( $type, $listing ) {

    if ( 'new' != $type )
        $type = 'featured';

    if ( !is_object( $listing ) )
        return;

    $tpl = ldl_get_template_object();

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
        $thumbnail = sprintf( $link_mask, '<img src="' . LDDLITE_URL . 'public/images/noimage.png" class="img-rounded">' );

    $tpl->assign( 'thumbnail', $thumbnail );
    $tpl->assign( 'title',     $title );
    $tpl->assign( 'summary',   $summary );
    $tpl->assign( 'link',      $link );

    return $tpl->draw( 'thumbnail-' . $type, 1 );
}

function ldl_action__home( $term = false ) {
    global $post;

    // Retrieve all featured listings
    $featured_output = '';

    $featured_args = array(
        'posts_per_page'    => 3,
        'no_found_rows'     => true,
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

        foreach ( $rand_keys as $key ) {
            $featured_output .= ldl_get_thumbnail( 'featured', $featured[ $key ] );
        }

    }


    // Retrieve all new listings
    $new_output = '';

    if ( ldl_get_setting( 'appearance_display_new' ) ) {
        $new_args = array(
            'posts_per_page'    => 3,
            'no_found_rows'     => true,
            'post_type'         => LDDLITE_POST_TYPE,

        );
        $new_listings = get_posts( $new_args );

        if ( $new_listings ) {
            foreach ( $new_listings as $listing ) {
                $new_output .= ldl_get_thumbnail( 'new', $listing );
            }
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

    $tpl = ldl_get_template_object();

    $tpl->assign( 'header',     ldl_get_header( 1 ) );
    $tpl->assign( 'loading',    ldl_get_loading_gif() );
    $tpl->assign( 'featured',   $featured_output );
    $tpl->assign( 'new',        $new_output );
    $tpl->assign( 'categories', $categories );

    return $tpl->draw( 'home', 1 );
}

