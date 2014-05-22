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

if ( ! defined( 'WPINC' ) ) die;

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


function ldl_upgrade() {

    function _maintenance( $enable = false ) {

        $file = ABSPATH . '.maintenance';
        if ( $enable ) {

            $fh = fopen( $file, 'w' );
            if ( $fh ) {
                fwrite( $fh, '<?php $upgrading = ' . time() . '; ?>' );
                fclose( $fh );
                chmod( $file, 0644 );
            }

        } else if ( !$enable && file_exists( $file ) ) {
            unlink( $file );
        }
    }

    _maintenance( true );

    //trigger_error( 'Upgrade Fired: ' . print_r( debug_backtrace(), 1 ), E_USER_NOTICE );

    if ( !function_exists( 'ld_use_locale' ) )
        require_once( LDDLITE_PATH . '/includes/functions.php' );

    if ( !function_exists( 'wp_generate_attachment_metadata' ) )
        require_once(ABSPATH . 'wp-admin/includes/image.php');

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

            if ( 'United Kingdom' == $listing->address_country ) {
                $country = 'GB';
                $address_one = $listing->address_street;

                if ( strpos( $listing->address_city, ',' ) !== false ) {
                    $pos = strrpos( $listing->address_city, ',' );
                    $city = trim( substr( $listing->address_city, 0, $pos ) );
                    $subdivision = trim( substr( $listing->address_city, $pos + 1 ) );
                } else {
                    $city = '';
                    $subdivision = $listing->address_city;
                }

            } else {
                $countries = ld_get_country_array();
                $country = array_search( $listing->address_country, $countries );
                $address_one = $listing->address_street;
                $city = $listing->address_city;
                $subdivision = $listing->address_state;
            }

            $post_meta = array(
                'country'       => $country,
                'address_one'   => $address_one,
                'address_two'   => '',
                'city'          => $city,
                'subdivision'   => $subdivision,
                'post_code'     => $listing->address_zip,
                'contact_email' => $listing->email,
                'contact_phone' => $listing->phone,
                'contact_fax'   => $listing->fax,
                'promotion'     => sprintf( '%s', $listing->promoDescription ),
                'other'         => $listing->other_info,
                'url_website'   => empty( $listing->url ) ? '' : esc_url_raw( $listing->url ),
                'url_facebook'  => empty( $listing->facebook ) ? '' : ldl_sanitize_https( $listing->facebook ),
                'url_twitter'   => empty( $listing->twitter ) ? '' : ldl_sanitize_twitter( $listing->twitter ),
                'url_linkedin'  => empty( $listing->linkedin ) ? '' : ldl_sanitize_https( $listing->linkedin ),
            );

            foreach ( $post_meta as $key => $value ) {
                add_post_meta( $post_id, LDDLITE_PFX . $key, $value );
            }

            if ( !empty( $listing->logo ) && file_exists( $wp_upload_dir['basedir'] . '/' . $listing->logo ) ) {

                $old = $wp_upload_dir['basedir'] . '/' . $listing->logo;
                $new = $wp_upload_dir['path'] . '/' . basename( $listing->logo );

                copy( $old, $new ); // We're not renaming/moving them until we feel comfortable this upgrade is flawless

                $filetype = wp_check_filetype( $new );
                $attachment = array(
                    'guid'              => $wp_upload_dir['url'] . '/' . basename( $new ),
                    'post_mime_type'    => $filetype['type'],
                    'post_title'        => sanitize_title( substr( basename( $new ), 0, -4 ) ),
                    'post_content'      => '',
                    'post_status'       => 'inherit'
                );

                $attached = wp_insert_attachment( $attachment, $new, $post_id );

                $attach_data = wp_generate_attachment_metadata( $attached, $new );
                wp_update_attachment_metadata( $attached, $attach_data );

                set_post_thumbnail( $post_id, $attached );
            }

        } // if

    } // foreach

    // @TODO Let's add this back in a few months. We'll leave it out just in case there's any unforeseen upgrade problems.
    // @TODO Then add it back in to clean up left over tables when we have enough feedback.
    // @TODO Don't forget to delete old file directories
    //_ldup_drop_tables();

    update_option( 'lddlite_settings', array() );
    update_option( 'lddlite_version',  LDDLITE_VERSION );

    _maintenance( false );

}


function ldl_disable_old() {
    $old_plugin = '/ldd-business-directory/lddbd_core.php';

    if ( !function_exists( 'deactivate_plugins' ) )
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    // Last but not least, out with the old
    deactivate_plugins( $old_plugin );
}