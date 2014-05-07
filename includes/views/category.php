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

    $meta['address_one'] = get_post_meta( $id, '_lddlite_address_one', 1 );
    $meta['address_two'] = get_post_meta( $id, '_lddlite_address_two', 1 );
    $meta['city']        = get_post_meta( $id, '_lddlite_city', 1 );
    $meta['subdivision'] = get_post_meta( $id, '_lddlite_subdivision', 1 );
    $meta['post_code']   = get_post_meta( $id, '_lddlite_post_code', 1 );

    $address = '';

    foreach ( $meta as $key => $value ) {
        if ( 'address_two' != $key && empty( $value ) ) {
            $address = false;
            break;
        }
    }

    if ( false !== $address ) {
        $address = $meta['address_one'];
        if ( !empty( $meta['address_two'] ) )
            $address .= '<br>' . $meta['address_two'];
        $address .= ',<br>' . $meta['city'] . ', ' . $meta['subdivision'] . ' ' . $meta['post_code'];
    } else {
        $address = '';
    }

    $meta['address'] = $address;

    $website = get_post_meta( $id, '_lddlite_urls_website', 1 );
    if ( $website )
        $meta['website'] = apply_filters( 'lddlite_listing_website', sprintf( '<a href="%1$s">%1$s</a>', esc_url( $website ) ) );

    $phone = get_post_meta( $id, '_lddlite_contact_phone', 1 );
    if ( $phone )
        $meta['phone'] = lddlite_format_phone( $phone );

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
            $output .= '<img src="' . LDDLITE_URL . '/public/images/24/' . $key . '.png" /></a>';
        }
    }

    return $output;
}


function ld_view_category( $cat_id ) {
    global $post;

    wp_enqueue_script( 'ldd-lite-search' );

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

    if ( !empty( $listings ) ) {

        $tpl = ld_new_template();

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

    $tpl = ld_new_template();

    $tpl->assign( 'url', get_permalink( $post->ID ) );
    $tpl->assign( 'search_form', ld_get_search_form() );
    $tpl->assign( 'listings', $output );

    return $tpl->draw( 'display/category', 1 );

}
