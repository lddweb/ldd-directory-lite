<?php


function ld_action__category( $cat_id ) {
    global $post;

    wp_enqueue_script( 'ldd-lite-search' );

    $permalink = get_permalink( $post->ID );

    $listings = get_posts( array(
        'posts_per_page'    => 10,
        'orderby'           => 'title',
        'order'             => 'DESC',
        'post_type'         => LDDLITE_POST_TYPE,
        'post_status'       => 'publish',
        'tax_query'         => array(
            array(
                'taxonomy' => LDDLITE_TAX_CAT,
                'field' => 'id',
                'terms' => $cat_id,
            ),
        ),
    ) );

    $output = '';
    $nth = 0;

    if ( !empty( $listings ) ) {

        $tpl = ldd::tpl();

        foreach ( $listings as $listing ) {

            $id = $listing->ID;
            $status = $listing->post_status;

            // determine our classes;
            $nth_class = ( $nth % 2 ) ? 'odd' : 'even';
            $nth++;

            // the following is used to build our title, and the logo
            $link = '<a href="' . $permalink . '?show=listing&t=' . $listing->post_name . '" title="' . esc_attr( $listing->post_title ) . '" %2$s>%1$s</a>';

            // the logo
            if ( has_post_thumbnail( $listing->ID ) )
                $featured = sprintf( $link, get_the_post_thumbnail( $listing->ID, 'directory-listing-compact' ), 'class="post-thumbnail"' );
            else
                $featured = sprintf( $link, '<img src="' . LDDLITE_URL . '/public/images/avatar_default.png" />', 'class="post-thumbnail"' );


            $meta = ld_get_listing_meta( $id );

            $summary = '';

            if ( !empty( $listing->post_excerpt ) )
                $summary = $listing->post_excerpt;

            if ( empty( $summary ) ) {
                $summary = $listing->post_content;

                $summary = strip_shortcodes( $summary );

                $summary = apply_filters( 'lddlite_the_content', $summary );
                $summary = str_replace( ']]>', ']]&gt;', $summary );

                $excerpt_length = apply_filters( 'lddlite_excerpt_length', 55 );
                $excerpt_more = apply_filters( 'lddlite_excerpt_more', sprintf( '&hellip; (' . $link . ')', 'view listing', '' ) );

                $summary = wp_trim_words( $summary, $excerpt_length, $excerpt_more );
            }

            $social = lddlite_build_social( $id );

            $tpl->assign( 'id',         $id );
            $tpl->assign( 'status',     $status );
            $tpl->assign( 'nth',        $nth_class );
            $tpl->assign( 'featured',   $featured );
            $tpl->assign( 'title',      sprintf( $link, $listing->post_title, '' ) );
            $tpl->assign( 'meta',       $meta );
            $tpl->assign( 'address',    $meta['address'] );
            $tpl->assign( 'summary',    $summary );
            $tpl->assign( 'social',     $social );

            $output .= $tpl->draw( 'display/listing-compact', 1 );

        } // foreach

        unset( $tpl );

    } // if

    $tpl = ldd::tpl();

    $tpl->assign( 'url', get_permalink( $post->ID ) );
    $tpl->assign( 'search_form', ld_get_search_form() );
    $tpl->assign( 'listings', $output );

    return $tpl->draw( 'display/category', 1 );

}
