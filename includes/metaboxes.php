<?php


add_action( 'add_meta_boxes', 'lddlite_move_metabox', 0 );
add_action( 'init', 'lddlite_init_metaboxes' );

add_filter( 'cmb_meta_boxes', 'lddlite_define_metaboxes' );

function lddlite_move_metabox()
{
    global $wp_meta_boxes;

    unset( $wp_meta_boxes[LDDLITE_POST_TYPE]['side']['low']['postimagediv'] );
    add_meta_box('postimagediv', __('Business Logo'), 'post_thumbnail_meta_box', null, 'side', 'low');

    unset( $wp_meta_boxes[LDDLITE_POST_TYPE]['normal']['core']['authordiv'] );
    add_meta_box( 'authordiv', __( 'Author' ), 'post_author_meta_box', LDDLITE_POST_TYPE, 'side', 'default' );

}

function lddlite_init_metaboxes()
{
    if ( !class_exists( 'cmb_Meta_Box' ) )
        require_once( LDDLITE_PATH . '/includes/cmb/init.php' );
}


function lddlite_define_metaboxes( array $meta_boxes )
{
    $prefix = '_lddlite_';

    $state_names = array(
        __( 'Alabama', lddslug() ),
        __( 'Alaska', lddslug() ),
        __( 'Arizona', lddslug() ),
        __( 'Arkansas', lddslug() ),
        __( 'California', lddslug() ),
        __( 'Colorado', lddslug() ),
        __( 'Connecticut', lddslug() ),
        __( 'Delaware', lddslug() ),
        __( 'District Of Columbia', lddslug() ),
        __( 'Florida', lddslug() ),
        __( 'Georgia', lddslug() ),
        __( 'Hawaii', lddslug() ),
        __( 'Idaho', lddslug() ),
        __( 'Illinois', lddslug() ),
        __( 'Indiana', lddslug() ),
        __( 'Iowa', lddslug() ),
        __( 'Kansas', lddslug() ),
        __( 'Kentucky', lddslug() ),
        __( 'Louisiana', lddslug() ),
        __( 'Maine', lddslug() ),
        __( 'Maryland', lddslug() ),
        __( 'Massachusetts', lddslug() ),
        __( 'Michigan', lddslug() ),
        __( 'Minnesota', lddslug() ),
        __( 'Mississippi', lddslug() ),
        __( 'Missouri', lddslug() ),
        __( 'Montana', lddslug() ),
        __( 'Nebraska', lddslug() ),
        __( 'Nevada', lddslug() ),
        __( 'New Hampshire', lddslug() ),
        __( 'New Jersey', lddslug() ),
        __( 'New Mexico', lddslug() ),
        __( 'New York', lddslug() ),
        __( 'North Carolina', lddslug() ),
        __( 'North Dakota', lddslug() ),
        __( 'Ohio', lddslug() ),
        __( 'Oklahoma', lddslug() ),
        __( 'Oregon', lddslug() ),
        __( 'Pennsylvania', lddslug() ),
        __( 'Rhode Island', lddslug() ),
        __( 'South Carolina', lddslug() ),
        __( 'South Dakota', lddslug() ),
        __( 'Tennessee', lddslug() ),
        __( 'Texas', lddslug() ),
        __( 'Utah', lddslug() ),
        __( 'Vermont', lddslug() ),
        __( 'Virginia', lddslug() ),
        __( 'Washington', lddslug() ),
        __( 'West Virginia', lddslug() ),
        __( 'Wisconsin', lddslug() ),
        __( 'Wyoming', lddslug() ),
    );

    $states = array_combine( $state_names, array(
        'AL',
        'AK',
        'AZ',
        'AR',
        'CA',
        'CO',
        'CT',
        'DE',
        'DC',
        'FL',
        'GA',
        'HI',
        'ID',
        'IL',
        'IN',
        'IA',
        'KS',
        'KY',
        'LA',
        'ME',
        'MD',
        'MA',
        'MI',
        'MN',
        'MS',
        'MO',
        'MT',
        'NE',
        'NV',
        'NH',
        'NJ',
        'NM',
        'NY',
        'NC',
        'ND',
        'OH',
        'OK',
        'OR',
        'PA',
        'RI',
        'SC',
        'SD',
        'TN',
        'TX',
        'UT',
        'VT',
        'VA',
        'WA',
        'WV',
        'WI',
        'WY',
    ) );

    $meta_boxes['listings_address'] = array(
        'id'         => 'listings_address',
        'title'      => __( 'Business Address', lddslug() ),
        'pages'      => array( LDDLITE_POST_TYPE ),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __( 'Address 1', lddslug() ),
                'id'   => $prefix . 'address_one',
                'type' => 'text',
            ),
            array(
                'name' => __( 'Address 2', lddslug() ),
                'id'   => $prefix . 'address_two',
                'type' => 'text',
            ),
            array(
                'name' => __( 'City', lddslug() ),
                'id'   => $prefix . 'city',
                'type' => 'text_medium',
            ),
            array(
                'name'      => __( 'State', lddslug() ),
                'id'        => $prefix . 'subdivision',
                'type'      => 'select',
                'options'   => $states,
            ),
            array(
                'name' => __( 'Zip Code', lddslug() ),
                'id'   => $prefix . 'post_code',
                'type' => 'text_small',
            ),
        ),
    );

    $meta_boxes['listings_web'] = array(
        'id'         => 'listings_web',
        'title'      => __( 'Web Addresses', lddslug() ),
        'pages'      => array( LDDLITE_POST_TYPE ),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name'          => __( 'Website', lddslug() ),
                'placeholder'   => 'http://...',
                'id'            => $prefix . 'urls_website',
                'type'          => 'text',
            ),
            array(
                'name'          => __( 'Facebook', lddslug() ),
                'placeholder'   => 'http://facebook.com/...',
                'id'            => $prefix . 'urls_social_facebook',
                'type'          => 'text',
            ),
            array(
                'name'          => __( 'Twitter', lddslug() ),
                'placeholder'   => 'http://twitter.com/...',
                'id'            => $prefix . 'urls_social_twitter',
                'type'          => 'text',
            ),
            array(
                'name'          => __( 'LinkedIn', lddslug() ),
                'placeholder'   => 'http://www.linkedin.com/in/...',
                'id'            => $prefix . 'urls_social_linkedin',
                'type'          => 'text',
            ),
        ),
    );

    $meta_boxes['listings_contact'] = array(
        'id'         => 'listings_contact',
        'title'      => __( 'Contact Information', lddslug() ),
        'pages'      => array( LDDLITE_POST_TYPE ),
        'context'    => 'side',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __( 'Email', lddslug() ),
                'id'   => $prefix . 'contact_emai]',
                'type' => 'text_medium',
            ),
            array(
                'name' => __( 'Phone', lddslug() ),
                'id'   => $prefix . 'contact_phone',
                'type' => 'text_medium',
            ),
            array(
                'name' => __( 'Fax', lddslug() ),
                'id'   => $prefix . 'contact_fax',
                'type' => 'text_medium',
            ),
        ),
    );


    return $meta_boxes;

}



