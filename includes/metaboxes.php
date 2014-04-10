<?php

//add_filter( 'cmb_meta_boxes', 'lbd_define_metaboxes' );
//add_action( 'init', 'lbd_init_metaboxes' );

//add_action( 'add_meta_boxes', 'lbd_move_metabox', 0 );

function lbd_move_metabox()
{
    global $wp_meta_boxes;

    unset( $wp_meta_boxes[LBD_POSTTYPE]['side']['low']['postimagediv'] );
    add_meta_box('postimagediv', __('Business Logo'), 'post_thumbnail_meta_box', null, 'side', 'low');

    unset( $wp_meta_boxes[LBD_POSTTYPE]['normal']['core']['authordiv'] );
    add_meta_box( 'authordiv', __( 'Author' ), 'post_author_meta_box', LBD_POSTTYPE, 'side', 'default' );

}

function lbd_init_metaboxes()
{
    if ( !class_exists( 'cmb_Meta_Box' ) )
        require_once( LDDLITE_PATH . '/includes/cmb/init.php' );
}


function lbd_define_metaboxes( array $meta_boxes )
{
    $prefix = '_lbd_';

    $state_names = array(
        __( 'Alabama', 'ldd-bd' ),
        __( 'Alaska', 'ldd-bd' ),
        __( 'Arizona', 'ldd-bd' ),
        __( 'Arkansas', 'ldd-bd' ),
        __( 'California', 'ldd-bd' ),
        __( 'Colorado', 'ldd-bd' ),
        __( 'Connecticut', 'ldd-bd' ),
        __( 'Delaware', 'ldd-bd' ),
        __( 'District Of Columbia', 'ldd-bd' ),
        __( 'Florida', 'ldd-bd' ),
        __( 'Georgia', 'ldd-bd' ),
        __( 'Hawaii', 'ldd-bd' ),
        __( 'Idaho', 'ldd-bd' ),
        __( 'Illinois', 'ldd-bd' ),
        __( 'Indiana', 'ldd-bd' ),
        __( 'Iowa', 'ldd-bd' ),
        __( 'Kansas', 'ldd-bd' ),
        __( 'Kentucky', 'ldd-bd' ),
        __( 'Louisiana', 'ldd-bd' ),
        __( 'Maine', 'ldd-bd' ),
        __( 'Maryland', 'ldd-bd' ),
        __( 'Massachusetts', 'ldd-bd' ),
        __( 'Michigan', 'ldd-bd' ),
        __( 'Minnesota', 'ldd-bd' ),
        __( 'Mississippi', 'ldd-bd' ),
        __( 'Missouri', 'ldd-bd' ),
        __( 'Montana', 'ldd-bd' ),
        __( 'Nebraska', 'ldd-bd' ),
        __( 'Nevada', 'ldd-bd' ),
        __( 'New Hampshire', 'ldd-bd' ),
        __( 'New Jersey', 'ldd-bd' ),
        __( 'New Mexico', 'ldd-bd' ),
        __( 'New York', 'ldd-bd' ),
        __( 'North Carolina', 'ldd-bd' ),
        __( 'North Dakota', 'ldd-bd' ),
        __( 'Ohio', 'ldd-bd' ),
        __( 'Oklahoma', 'ldd-bd' ),
        __( 'Oregon', 'ldd-bd' ),
        __( 'Pennsylvania', 'ldd-bd' ),
        __( 'Rhode Island', 'ldd-bd' ),
        __( 'South Carolina', 'ldd-bd' ),
        __( 'South Dakota', 'ldd-bd' ),
        __( 'Tennessee', 'ldd-bd' ),
        __( 'Texas', 'ldd-bd' ),
        __( 'Utah', 'ldd-bd' ),
        __( 'Vermont', 'ldd-bd' ),
        __( 'Virginia', 'ldd-bd' ),
        __( 'Washington', 'ldd-bd' ),
        __( 'West Virginia', 'ldd-bd' ),
        __( 'Wisconsin', 'ldd-bd' ),
        __( 'Wyoming', 'ldd-bd' ),
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
        'title'      => __( 'Business Address', 'ldd-bd' ),
        'pages'      => array( LBD_POSTTYPE ),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __( 'Address 1', 'ldd-bd' ),
                'id'   => $prefix . 'address_one',
                'type' => 'text',
            ),
            array(
                'name' => __( 'Address 2', 'ldd-bd' ),
                'id'   => $prefix . 'address_two',
                'type' => 'text',
            ),
            array(
                'name' => __( 'City', 'ldd-bd' ),
                'id'   => $prefix . 'city',
                'type' => 'text_medium',
            ),
            array(
                'name'      => __( 'State', 'ldd-bd' ),
                'id'        => $prefix . 'state',
                'type'      => 'select',
                'options'   => $states,
            ),
            array(
                'name' => __( 'Zip Code', 'ldd-bd' ),
                'id'   => $prefix . 'zip',
                'type' => 'text_small',
            ),
        ),
    );

    $meta_boxes['listings_web'] = array(
        'id'         => 'listings_web',
        'title'      => __( 'Web Addresses', 'ldd-bd' ),
        'pages'      => array( LBD_POSTTYPE ),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name'          => __( 'Website', 'ldd-bd' ),
                'placeholder'   => 'http://...',
                'id'            => $prefix . 'url',
                'type'          => 'text',
            ),
            array(
                'name'          => __( 'Facebook', 'ldd-bd' ),
                'placeholder'   => 'http://facebook.com/...',
                'id'            => $prefix . 'facebook',
                'type'          => 'text',
            ),
            array(
                'name'          => __( 'Twitter', 'ldd-bd' ),
                'placeholder'   => 'http://twitter.com/...',
                'id'            => $prefix . 'twitter',
                'type'          => 'text',
            ),
            array(
                'name'          => __( 'LinkedIn', 'ldd-bd' ),
                'placeholder'   => 'http://www.linkedin.com/in/...',
                'id'            => $prefix . 'linkedin',
                'type'          => 'text',
            ),
        ),
    );

    $meta_boxes['listings_contact'] = array(
        'id'         => 'listings_contact',
        'title'      => __( 'Contact Information', 'ldd-bd' ),
        'pages'      => array( LBD_POSTTYPE ),
        'context'    => 'side',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __( 'Email', 'ldd-bd' ),
                'id'   => $prefix . 'email',
                'type' => 'text_medium',
            ),
            array(
                'name' => __( 'Phone', 'ldd-bd' ),
                'id'   => $prefix . 'phone',
                'type' => 'text_medium',
            ),
            array(
                'name' => __( 'Fax', 'ldd-bd' ),
                'id'   => $prefix . 'fax',
                'type' => 'text_medium',
            ),
        ),
    );


    return $meta_boxes;

}



