<?php
/**
 * Responsible for bringing all copies of the LDD Business Directory
 * prior to version 2.0.0 up to speed.
 *
 * @package   LDDBD
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 *
 */

// Run our upgrade during WordPress init
add_action( 'init', 'lddlite_upgrade_path' );


function _lddlite_get_old_tables()
{
    global $wpdb;

    return array(
        'main'  => $wpdb->prefix . 'lddbusinessdirectory',
        'doc'   => $wpdb->prefix . 'lddbusinessdirectory_docs',
        'cat'   => $wpdb->prefix . 'lddbusinessdirectory_cats'
    );
}


function _lddlite_get_current_data()
{
    global $wpdb;

    // Tables used prior to version 2.0.0
    $tables = _lddlite_get_old_tables();

    // Pull everything from the categories table
    $query = sprintf("
                SELECT name
                FROM `%s`
            ", $tables['cat'] );
    $categories = $wpdb->get_col( $query );


    // Grab all the directory listings
    $query = sprintf("
                SELECT createDate, name, description, categories, address_street, address_city, address_state, address_zip,
                       address_country, phone, fax, email, contact, url, facebook, twitter, linkedin, promoDescription,
                       logo, login, password, approved, other_info
                FROM `%s`
            ", $tables['main'] );
    $listings = $wpdb->get_results( $query );

    return array( $categories, $listings );
}


function _lddlite_drop_old_tables()
{
    global $wpdb;

    $tables = _lddlite_get_old_tables();
    $query = sprintf("
        DROP TABLE `%s`
    ", implode( '`, `', $tables ) );
    $wpdb->query( $query );

}


function _lddlite_get_category_terms( $categories )
{
    global $wpdb;

    $tables = _lddlite_get_old_tables();

    if ( !empty( $categories ) )
    {
        $categories = str_replace( 'x', '', ltrim( $categories, ',' ) );

        $query = sprintf("
                    SELECT name
                    FROM `%s`
                      WHERE id IN (%s)
                ", $tables['cat'], $categories );
        $results = $wpdb->get_col( $query );

        foreach ( $results as $category )
        {
            $term = get_term_by( 'name', $category, LDDLITE_TAX_CAT );
            $term_ids[] = $term->term_id;
        }

        return $term_ids;

    }

    return array();

}


function lddlite_upgrade_path()
{

    // Initialize these variables once, outside the loop
    $wp_upload_dir = wp_upload_dir();
    list( $categories, $listings ) = _lddlite_get_current_data();

    // Using our old category names, recreate them under our custom taxonomy
    foreach ( $categories as $category )
    {
        // There was never any error checking before, don't repeat categories
        if ( !term_exists( $category, LDDLITE_TAX_CAT ) ) {
            wp_insert_term( $category, LDDLITE_TAX_CAT );
        }
    }


    // Loop through existing listings and create new posts from them.
    foreach ( $listings as $listing )
    {

        $term_ids = _lddlite_get_category_terms( $listing->categories );

        $uid = false;

        if ( !empty( $listing->login ) )
        {
            $uid = username_exists( $listing->login );
            if ( !$uid && !empty( $listing->email ) && email_exists( $listing->email ) == false ) {
                $uid = wp_create_user( $listing->login, $listing->password, $listing->email );
            }
        }

        // Failsafe
        if ( !get_user_by( 'id', $uid ) ) {
            $uid = get_current_user_id();
        }


        $post_status = ( 'true' == $listing->approved ) ? 'publish' : 'pending';

        // This only runs once, we can be a little pedantic.
        $date = date_parse( sprintf( '%s', $listing->createDate ) );
        $date = checkdate( $date['month'], $date['day'], $date['year'] ) ? $listing->createDate : date( 'Y-m-d H:i:s' );


        // Create a post for this listing.
        $post = array(
            'post_content'  => sprintf( '%s', $listing->description ),
            'post_title'    => sprintf( '%s', $listing->name ),
            'post_status'   => $post_status,
            'post_type'     => LDDLITE_POST_TYPE,
            'post_author'   => $uid,
            'post_date'     => $date,
            'tax_input'     => array( LDDLITE_TAX_CAT => $term_ids ),
        );
        $post_id = wp_insert_post( $post );

        // Hopefully this is never false?!
        if ( $post_id )
        {
            $address = array(
                'address_one'   => $listing->address_street,
                'address_two'   => '',
                'country'       => $listing->address_country,
                'subdivision'   => $listing->address_state,
                'city'          => $listing->address_city,
                'post_code'     => $listing->address_zip,
            );

            $contact = array(
                'primary'       => $listing->contact,
                'email'         => $listing->email,
                'phone'         => $listing->phone,
                'fax'           => $listing->fax,
            );

            $urls = array(
                'website'       => esc_url_raw( $listing->url ),
                'social'        => array(
                    'facebook'  => esc_url_raw( $listing->facebook ),
                    'twitter'   => esc_url_raw( $listing->twitter ),
                    'linkedin'  => esc_url_raw( $listing->linkedin ),
                ),
            );

            $other = array(
                'promotion'     => sprintf( '%s', $listing->promoDescription ),
                'other'         => $listing->other_info,
            );

            add_post_meta( $post_id, '_lddlite_address',    $address );
            add_post_meta( $post_id, '_lddlite_contact',    $contact );
            add_post_meta( $post_id, '_lddlite_urls',       $urls );
            add_post_meta( $post_id, '_lddlite_other',      $other );


            if ( !empty( $listing->logo ) && file_exists( $wp_upload_dir['basedir'] . '/' . $listing->logo ) )
            {

                $filename = $wp_upload_dir['path'] . basename( $listing->logo );
                rename( $wp_upload_dir['basedir'] . '/' . $listing->logo, $filename );

                $filetype = wp_check_filetype( $filename, null );

                $attachment = array(
                    'guid'              => $filename,
                    'post_mime_type'    => $filetype['type'],
                    'post_title'        => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                    'post_content'      => '',
                    'post_status'       => 'inherit'
                );

                $attached = wp_insert_attachment( $attachment, $filename, $post_id );

                if ( !function_exists( 'wp_generate_attachment_metadata' ) ) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                }

                $attach_data = wp_generate_attachment_metadata( $attached, $filename );
                wp_update_attachment_metadata( $attached, $attach_data );

                set_post_thumbnail( $post_id, $attached );
            }

        } // if

    } // foreach


    // We're done adding the new taxonomies and posts, get rid of the old
    _lddlite_drop_old_tables();

    $options = get_option( 'lddlite-options', array() );
    $options['version'] = LDDLITE_VERSION;
    update_option( 'lddlite-options', $options );

}

