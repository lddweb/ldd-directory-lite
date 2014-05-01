<?php

/**
 *
 */

function lddlite_format_phone( $phone, $locale = 'US' ) {

    if ( 'US' == $locale ) {
        $phone = preg_replace( '/[^[:digit:]]/', '', $phone );
        if ( 10 == strlen( $phone ) ) {
            preg_match( '/(\d{3})(\d{3})(\d{4})/', $phone, $match );
            return "({$match[1]}) {$match[2]}-{$match[3]}";
        }
    }

    return $phone; // because I lost it
}


function ld_get_listing_meta( $id ) {

    if ( !is_int( $id ) || LDDLITE_POST_TYPE != get_post_type( $id ) )
        return false;

    $meta = array();

    $website = get_post_meta( $id, '_lddlite_urls_website', 1 );
    if ( $website )
        $meta['website'] = apply_filters( 'lddlite_listing_website', sprintf( '<a href="%1$s">%1$s</a>', esc_url( $website ) ) );

    $phone = get_post_meta( $id, '_lddlite_contact_phone', 1 );
    if ( $phone )
        $meta['phone'] = lddlite_format_phone( $phone );

    $meta['address_one'] = get_post_meta( $id, '_lddlite_address_one', 1 );
    $meta['address_two'] = get_post_meta( $id, '_lddlite_address_two', 1 );
    $meta['subdivision'] = get_post_meta( $id, '_lddlite_address_subdivision', 1 );
    $meta['city']        = get_post_meta( $id, '_lddlite_address_city', 1 );
    $meta['post_code']   = get_post_meta( $id, '_lddlite_address_post_code', 1 );

    return $meta;

}









function _lddlite_esc_twitter( $username )
{
    if ( empty( $username ) )
        return;

    $twitter = esc_url( $username );

    $pos = strrpos( $twitter, '/' );
    if ( $pos !== false )
    {
        $pos += 1;
        $twitter = substr( $twitter, $pos );
    }

    return 'https://twitter.com/' . $twitter;
}

function lddlite_build_social( $id )
{

    if ( !is_int( $id ) )
        return false;

    $titles = array(
        'facebook'  => 'Visit %1$s on Facebook',
        'twitter'   => 'Follow %1$s on Twitter',
        'linkedin'  => 'Connect with %1$s on LinkedIn',
        'default'   => 'Visit %1$s on %2$s',
    );

    $social = array(
        'facebook'  =>  get_post_meta( $id, '_lddlite_urls_social_facebook', 1 ),
        'linkedin'  =>  get_post_meta( $id, '_lddlite_urls_social_linkedin', 1 ),
        'twitter'   =>  get_post_meta( $id, '_lddlite_urls_social_twitter', 1 ),
    );

    $social['twitter'] = _lddlite_esc_twitter( $social['twitter'] );

    $output = '';

    foreach ( $social as $key => $url )
    {
        if ( !empty( $url ) )
        {
            $title_key = array_key_exists( $key, $titles ) ? $titles[$key] : $titles['default'];
            $title = sprintf( $title_key, $name, $key );

            $output .= '<a href="' . esc_url( $url ) . '" title="' . $title . '">';
            $output .= '<img src="' . LDDLITE_URL . '/public/icons/24/' . $key . '.png" /></a>';
        }
    }

    return $output;
}


function ld_view_category( $cat_id )
{
    global $post;

    $lddlite = lddlite();
    $permalink = get_permalink( $post->ID );


    $listings = get_posts( array(
        'posts_per_page'   => 10,
        'category'         => $cat_id,
        'orderby'          => 'title',
        'order'            => 'DESC',
        'post_type'        => LDDLITE_POST_TYPE,
        'post_status'      => 'publish',
    ) );

    $output = '';
    $nth = 0;

    if ( !empty( $listings ) )
    {

        foreach ( $listings as $listing )
        {

            $id = $listing->ID;
            $status = $listing->post_status;

            // determine our classes;
            $nth_class = ( $nth % 2 ) ? 'odd' : 'even';
            $nth++;

            // the following is used to build our title, and the logo
            $link = '<a href="' . $permalink . '?show=business&t=' . $listing->post_name . '" title="' . esc_attr( $listing->post_title ) . '" %2$s>%1$s</a>';

            // the logo
            if ( has_post_thumbnail( $listing->ID ) )
                $featured = sprintf( $link, get_the_post_thumbnail( $listing->ID, 'thumbnail' ), 'class="post-thumbnail"' );
            else
                $featured = sprintf( $link, '<img src="' . LDDLITE_URL . '/public/icons/avatar_default.png" />', 'class="post-thumbnail"' );


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

            $template_vars = array(
                'id'            => $id,
                'status'        => $status,
                'nth'           => $nth_class,
                'featured'      => $featured,
                'title'         => sprintf( $link, $listing->post_title, '' ),
                'meta'          => $meta,
                'summary'       => $summary,
                'social'        => $social,
            );

            $output .= ld_parse_template( 'display/listing-compact', $template_vars );

        } // foreach

    } // if


    $template_vars = array(
        'search'    => ld_get_search_form(),
        'url'       => get_permalink( $post->ID ),
        'listings'  => $output,
    );

    return ld_parse_template( 'display/category', $template_vars );

}
