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


function lddlite_build_meta( $address, $website = '', $phone = '' )
{
    if ( empty( $address ) || !is_array( $address ) )
        return false;

    $meta = '';

    if ( !empty( $website ) ) {
        $meta .= sprintf( '<p class="website"><a href="%1$s">%1$s</a></p>', esc_url( $website ) );
    }

    if ( !empty( $phone ) ) {
        $meta .= sprintf( '<p class="phone">%s</p>', lddlite_format_phone( $phone ) );
    }

    $meta .= '<p class="address">' . $address['address_one'];
    if ( !empty( $address['address_two'] ) ) {
        $meta .= '<br />' . $address['address_two'];
    }
    $meta .= ',<br />' . $address['city'] . ', ' . $address['subdivision'] . ' ' . $address['post_code'];

    return $meta;
}


function lddlite_build_social( $social, $name )
{

    if ( !is_array( $social ) )
        return '';

    $titles = array(
        'facebook'  => 'Visit %1$s on Facebook',
        'twitter'   => 'Follow %1$s on Twitter',
        'linkedin'  => 'Connect with %1$s on LinkedIn',
        'default'   => 'Visit %1$s on %2$s',
    );

    $output = '';
    ksort( $social );

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


function lddlite_display_view_category()
{
    global $post;

    $category = 0;
    if ( isset( $_GET['category'] ) )
    {
        $term = term_exists( $_GET['category'], LDDLITE_TAX_CAT );
        $category = $term->term_id;
    }

    $listings = get_posts( array(
        'posts_per_page'   => 10,
        'category'         => $category,
        'orderby'          => 'title',
        'order'            => 'DESC',
        'post_type'        => LDDLITE_POST_TYPE,
        'post_status'      => 'publish',
        'suppress_filters' => true
    ) );

    $output = '';


    if ( !empty( $listings ) )
    {
        // @TODO Starting to think we should store this in a class property?
        $listing_url = get_permalink( $post->ID );

        foreach ( $listings as $listing )
        {

            $name = esc_attr( $listing->post_title );

            if ( has_post_thumbnail( $listing->ID ) )
            {
                $featured = '<a href="' . $listing_url . '" title="' . esc_attr( $listing->post_title ) . '">'
                          . get_the_post_thumbnail( $listing->ID, 'thumbnail' )
                          . '</a>';
            }
            else
            {
                $featured = '<a href="' . $listing_url . '" title="' . esc_attr( $listing->post_title ) . '">'
                          . '<img src="' . LDDLITE_URL . '/public/icons/avatar_default.png" />'
                          . '</a>';
            }

            $address = get_post_meta( $listing->ID, '_lddlite_address', 1 );
            $contact = get_post_meta( $listing->ID, '_lddlite_contact', 1 );
            $urls = get_post_meta( $listing->ID, '_lddlite_urls', 1 );

            $website = ( is_array( $urls ) && isset( $urls['website'] ) ) ? $urls['website'] : '';
            $phone = ( is_array( $contact ) && isset( $contact['phone'] ) ) ? $contact['phone'] : '';
            $social = ( is_array( $urls ) && isset( $urls['social'] ) ) ? $urls['social'] : '';
md( $social );
            $meta = lddlite_build_meta( $address, $website, $phone );
            $social = lddlite_build_social( $social, $name );

            $template_vars = array(
                'featured'      => $featured,
                'title'         => $name,
                'meta'          => $meta,
                'description'   => get_the_excerpt(),
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
