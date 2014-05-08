<?php
/**
 * Submit a listing view controller and other functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

require_once( LDDLITE_PATH . '/includes/actions/submit/functions.php' );
require_once( LDDLITE_PATH . '/includes/actions/submit/process.php' );


function ld_action__submit( $term = false ) {
    global $post;

    wp_enqueue_script( 'ldd-lite-responsiveslides' );

    $valid = false;
    $data = array();
    $tpl = ldd::tpl();

    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) || !empty( $_POST['ld_s_summary'] ) )
            die( "No, kitty! That's a bad kitty!" );

        $data = ld_sanitize__post( $_POST );
        $valid = ld_submit_validate_form( $data );

    }

    if ( false !== $valid && !is_wp_error( $valid ) ) {

        // Create the user and insert a post for this listing
        $user_id = ld_submit__create_user( $data['username'], $data['email'] );
        $post_id = ld_submit__create_listing( $data['name'], $data['description'], $data['category'], $user_id );

        // Add all the post meta fields
        ld_submit__create_meta( $data, $post_id );

        // Upload their logo if one was submitted
        if ( isset( $_FILES['ld_s_logo'] ) ) {
            // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $attachment_id = media_handle_upload( 'ld_s_logo', 0 );
            set_post_thumbnail( $post_id, $attachment_id );
        }

        // Set these up so that they can be used on the success page as {{listing.field}}
        foreach ( $data as $key => $value ) {

            if ( 'url' == $key )
                $data['url'] = esc_url( $value['website'] );
            else
                $data[ $key ] = htmlentities( $value );

        }

        $template_vars = array(
            'url'       => get_permalink( $post->ID ),
            'listing'   => $data,
        );

        return ld_parse_template( 'display/submit-success', $template_vars );

    }

    $category_args = array(
        'hide_empty'         => 0,
        'echo'               => 0,
        'selected'           => isset( $data['category'] ) ? $data['category'] : 0,
        'hierarchical'       => 1,
        'name'               => 'ld_s_category',
        'id'                 => 'category',
        'tab_index'          => 2,
        'taxonomy'           => LDDLITE_TAX_CAT,
    );

    $tpl->assign( 'url', get_permalink( $post->ID ) );
    $tpl->assign( 'form_action', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    $tpl->assign( 'nonce', wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ) );
    $tpl->assign( 'category_dropdown', wp_dropdown_categories( $category_args ) );
    $tpl->assign( 'country_dropdown', ld_dropdown_country() );
    $tpl->assign( 'subdivision_dropdown', ld_dropdown_subdivision( 'us' ) );

    if ( is_wp_error( $valid ) ) {

        $errors = array();

        $codes = $valid->get_error_codes();

        foreach ( $codes as $code ) {
            $key = substr( $code, 0, strrpos( $code, '_' ) );
            $errors[ $key ] = '<span class="submit-error">' . $valid->get_error_message( $code ) . '</span>';
        }

        $urls = $data['urls'];
        unset( $data['urls'] );

        $data = array_map( 'htmlentities', $data );

        $data['urls'] = array_map( 'htmlentities', $urls );

        $tpl->assign( 'errors', $errors );
    }

    $tpl->assign( 'data', $data );

    return $tpl->draw( 'display/submit', 1 );

}
