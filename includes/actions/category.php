<?php


function ldl_action__category( $cat_id ) {
    global $post;

    $tpl = ldl::tpl();


    $listings = get_posts( array(
        'posts_per_page'    => -1,
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

        $gridview = ( isset( $_GET['f'] ) && 'grid' == $_GET['f'] ) ? true : false;

        if ( $gridview )
            $output .= '<div class="row">';

        foreach ( $listings as $listing ) {

            $nth_class = ( $nth % 2 ) ? 'odd' : 'even';
            $nth++;

            $id         = $listing->ID;
            $title      = $listing->post_title;
            $summary    = $listing->post_excerpt;

            $meta = ldl_get_listing_meta( $id );
                $address = $meta['address'];
                $website = $meta['website'];
                $email   = $meta['email'];
                $phone   = $meta['phone'];
            $social = ldl_get_social( $id, 'default', false );

            $link       = add_query_arg( array(
                'show'  => 'listing',
                't'     => $listing->post_name,
            ) );



            // the following is used to build our title, and the logo
            $link_mask = '<a href="' . $link . '" title="' . esc_attr( $title ) . '">%1$s</a>';

            // the logo
            if ( has_post_thumbnail( $id ) )
                $thumbnail = sprintf( $link_mask, get_the_post_thumbnail( $id, 'directory-listing', array( 'class' => 'img-rounded' ) ) );
            else
                $thumbnail = sprintf( $link_mask, '<img src="' . LDDLITE_URL . '/public/images/noimage.png" class="img-rounded">' );

            if ( empty( $summary ) ) {
                $summary = $listing->post_content;

                $summary = strip_shortcodes( $summary );

                $summary = apply_filters( 'lddlite_the_content', $summary );
                $summary = str_replace( ']]>', ']]&gt;', $summary );

                $excerpt_length = apply_filters( 'lddlite_excerpt_length', 35 );
                $excerpt_more = apply_filters( 'lddlite_excerpt_more', '&hellip;' );

                $summary = wp_trim_words( $summary, $excerpt_length, $excerpt_more );
            }

            $tpl->assign( 'id',         $id );
            $tpl->assign( 'nth',        $nth_class );
            $tpl->assign( 'thumbnail',  $thumbnail );
            $tpl->assign( 'title',      sprintf( $link_mask, $title ) );

            $tpl->assign( 'social', $social );
            $tpl->assign( 'address', $address );
            $tpl->assign( 'website', $website );
            $tpl->assign( 'email',   $email );
            $tpl->assign( 'phone',   $phone );

            $tpl->assign( 'summary',    $summary );

            $draw = ( $gridview ) ? 'listing-grid' : 'listing-compact';
            $output .= $tpl->draw( $draw, 1 );
            if ( $gridview && ( 0 === $nth % 4) )
                $output .= '</div><div class="row">';


        } // foreach

        if ( $gridview )
            $output .= '</row>';
    } // if

    $tpl = ldl::tpl();

    $tpl->assign( 'header', ldl_get_header( 'category' ) );
    $tpl->assign( 'home', remove_query_arg( array(
        'show',
        't',
    ) ) );
    $tpl->assign( 'category_title', ldl_get_term_name( $cat_id ) );
    $tpl->assign( 'list_link', remove_query_arg( array( 'f' ) ) );
    $tpl->assign( 'grid_link', add_query_arg( array(
        'f' => 'grid',
    ) ) );
    $tpl->assign( 'url', get_permalink( $post->ID ) );

    $tpl->assign( 'listings', $output );

    return $tpl->draw( 'category', 1 );

}
