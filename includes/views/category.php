<?php

/**
 *
 */

function lddlite_format_phone( $phone, $locale = 'US' )
{
    //@TODO add functionality to format for i18n
    if ( 'US' == $locale )
    {
        $phone = preg_replace( '/[^[:digit:]]/', '', $phone );
        if ( 10 == strlen( $phone ) )
        {
            preg_match( '/(\d{3})(\d{3})(\d{4})/', $phone, $match );
            return "({$match[1]}) {$match[2]}-{$match[3]}";
        }
    }

    return $phone;
}


function lddlite_build_meta( $id )
{

    if ( !is_int( $id ) )
        return false;

    $meta = '';

    if ( $website = get_post_meta( $id, '_lddlite_urls_website', 1 ) ) {
        $meta .= sprintf( '<p class="website"><a href="%1$s">%1$s</a></p>', esc_url( $website ) );
    }

    if ( $phone = get_post_meta( $id, '_lddlite_contact_phone', 1 ) ) {
        $meta .= '<p class="phone">' . lddlite_format_phone( $phone ) . '</p>';
    }

    $address = array(
        'one'           => get_post_meta( $id, '_lddlite_address_one', 1 ),
        'two'           => get_post_meta( $id, '_lddlite_address_two', 1 ),
        'subdivision'   => get_post_meta( $id, '_lddlite_address_subdivision', 1 ),
        'city'          => get_post_meta( $id, '_lddlite_address_city', 1 ),
        'post_code'     => get_post_meta( $id, '_lddlite_address_post_code', 1 ),
    );

    $meta .= '<p class="address">' . $address['one'];
    if ( !empty( $address['two'] ) ) {
        $meta .= '<br />' . $address['two'];
    }
    $meta .= ',<br />' . $address['city'] . ', ' . $address['subdivision'] . ' ' . $address['post_code'];

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


function lddlite_display_view_category( $cat_id )
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

    if ( !empty( $listings ) )
    {

        foreach ( $listings as $listing )
        {

            $id = $listing->ID;

            $link = '<a href="' . $permalink . '?show=business&term=' . $listing->post_name . '" title="' . esc_attr( $listing->post_title ) . '">%1$s</a>';

            if ( has_post_thumbnail( $listing->ID ) ) {
                $featured = sprintf( $link, get_the_post_thumbnail( $listing->ID, 'thumbnail' ) );
            } else {
                $featured = sprintf( $link, '<img src="' . LDDLITE_URL . '/public/icons/avatar_default.png" />' );
            }

            $meta = lddlite_build_meta( $id );
            $social = lddlite_build_social( $id );

            $template_vars = array(
                'featured'      => $featured,
                'title'         => sprintf( $link, $listing->post_title ),
                'meta'          => $meta,
                'description'   => '',
                'social'        => $social,
            );

            $output .= lddlite_parse_template( 'display/category_listing', $template_vars );

        } // foreach

    } // if


    $template_vars = array(
        'search'    => lddlite_get_search_form(),
        'url'       => get_permalink( $post->ID ),
        'listings'  => $output,
    );

    return lddlite_parse_template( 'display/category', $template_vars );

}
