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


function _ldup_get_tables() {
    global $wpdb;

    return array(
        'main'  => $wpdb->prefix . 'lddbusinessdirectory',
        'doc'   => $wpdb->prefix . 'lddbusinessdirectory_docs',
        'cat'   => $wpdb->prefix . 'lddbusinessdirectory_cats'
    );
}


function _ldup_get_listings() {
    global $wpdb;

    // Tables used prior to version 2.0.0
    $tables = _ldup_get_tables();

    // Pull everything from the categories table
    $query = sprintf("
                SELECT id, name
                FROM `%s`
            ", $tables['cat'] );
    $results = $wpdb->get_results( $query );

    $categories = array();

    foreach ( $results as $cat ) {
        $categories[ $cat->id ] = $cat->name;
    }

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


function _ldup_assign_categories( $listing_cats, $category_map ) {
    global $wpdb;

    if ( !empty( $listing_cats ) ) {
        $listing_cats = str_replace( 'x', '', ltrim( $listing_cats, ',' ) );
        $listing_cats = explode( ',', $listing_cats );

        foreach ( $listing_cats as $cat_id ) {
            $term = get_term_by( 'name', $category_map[ $cat_id ], LDDLITE_TAX_CAT );
            $term_ids[] = $term->term_id;
        }

        return $term_ids;
    }

    return array();
}


function _ldup_drop_tables() {
    global $wpdb;

    $tables = _ldup_get_tables();
    $query = sprintf("
        DROP TABLE `%s`
    ", implode( '`, `', $tables ) );
    $wpdb->query( $query );

}


function ld_upgrade__go() {

    $wp_upload_dir = wp_upload_dir();
    list( $category_map, $listings ) = _ldup_get_listings();

    // Using the old category names, add them to the new taxonomy
    foreach ( $category_map as $category ) {
        // There was never any error checking before, don't repeat categories
        if ( !term_exists( $category, LDDLITE_TAX_CAT ) )
            wp_insert_term( $category, LDDLITE_TAX_CAT );

    }


    // Upgrade our listings to the new custom post type format
    foreach ( $listings as $listing ) {

        $term_ids =_ldup_assign_categories( $listing->categories, $category_map );

        $user_id = false;

        if ( !empty( $listing->login ) ) {

            $user_id = username_exists( $listing->login );
            if ( !$user_id && !empty( $listing->email ) && email_exists( $listing->email ) == false )
                $user_id = wp_create_user( $listing->login, $listing->password, $listing->email );

        }

        // Failsafe
        if ( !get_user_by( 'id', $user_id ) )
            $user_id = get_current_user_id();


        $post_status = ( 'true' == $listing->approved ) ? 'publish' : 'pending';

        // This only runs once, we can be a little pedantic.
        $date = date_parse( sprintf( '%s', $listing->createDate ) );
        $date = checkdate( $date['month'], $date['day'], $date['year'] ) ? $listing->createDate : date( 'Y-m-d H:i:s' );


        // Create a post for this listing.
        $new = array(
            'post_content'  => sprintf( '%s', wp_unslash( $listing->description ) ),
            'post_title'    => sprintf( '%s', wp_unslash( $listing->name ) ),
            'post_status'   => $post_status,
            'post_type'     => LDDLITE_POST_TYPE,
            'post_author'   => $user_id,
            'post_date'     => $date,
            'tax_input'     => array( LDDLITE_TAX_CAT => $term_ids ),
        );

        $post_id = wp_insert_post( $new );


        if ( $post_id ) {

            $post_meta = array(
                'address_one'   => $listing->address_street,
                'address_two'   => '',
                'country'       => $listing->address_country,
                'subdivision'   => $listing->address_state,
                'city'          => $listing->address_city,
                'post_code'     => $listing->address_zip,
                'contact_email' => $listing->email,
                'contact_phone' => $listing->phone,
                'contact_fax'   => $listing->fax,
                'promotion'     => sprintf( '%s', $listing->promoDescription ),
                'other'         => $listing->other_info,
            );

            $urls = array(
                'url_website'   => esc_url_raw( $listing->url ),
                'url_facebook'  => esc_url_raw( $listing->facebook ),
                'url_twitter'   => esc_url_raw( $listing->twitter ),
                'url_linkedin'  => esc_url_raw( $listing->linkedin ),
            );

            if ( !function_exists( 'ld_submit_sanitize_urls' ) )
                require_once( LDDLITE_PATH . '/includes/actions/submit/process.php' );

            $urls = ld_submit_sanitize_urls( $urls );

            $post_meta = array_merge( $post_meta, $urls );

            foreach ( $post_meta as $key => $value ) {
                add_post_meta( $post_id, LDDLITE_PFX . $key, $value );
            }


            if ( !empty( $listing->logo ) && file_exists( $wp_upload_dir['basedir'] . '/' . $listing->logo ) ) {

                $filename = $wp_upload_dir['path'] . basename( $listing->logo );
                copy( $wp_upload_dir['basedir'] . '/' . $listing->logo, $filename );

                $filetype = wp_check_filetype( $filename, null );

                $attachment = array(
                    'guid'              => $filename,
                    'post_mime_type'    => $filetype['type'],
                    'post_title'        => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                    'post_content'      => '',
                    'post_status'       => 'inherit'
                );

                $attached = wp_insert_attachment( $attachment, $filename, $post_id );

                if ( !function_exists( 'wp_generate_attachment_metadata' ) )
                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                $attach_data = wp_generate_attachment_metadata( $attached, $filename );
                wp_update_attachment_metadata( $attached, $attach_data );

                set_post_thumbnail( $post_id, $attached );
            }

        } // if

    } // foreach

    // @TODO Let's add this back in a few months. We'll leave it out just in case there's any unforeseen upgrade problems.
    // @TODO Then add it back in to clean up left over tables when we have enough feedback.
    //_ldup_drop_tables();
    // @TODO Don't forget to delete old file directories

    $options = get_option( 'lddlite_settings', array() );
    $options['version'] = LDDLITE_VERSION;
    update_option( 'lddlite_settings', $options );

    $old_plugin = '/ldd-business-directory/lddbd_core.php';

    // Last but not least, out with the old
    deactivate_plugins( $old_plugin );

}

