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

require_once( LDDLITE_PATH . '/includes/actions/submit/process.php' );


function ld_action__submit( $term = false ) {
    global $post;

    ld_bootstrap();

    wp_enqueue_style( ldd::$slug );
    wp_enqueue_script( ldd::$slug . '-responsiveslides' );
    wp_enqueue_script( 'icheck' );

    wp_enqueue_style( 'bootflat' );
    wp_enqueue_style( 'font-awesome' );


    $tpl = ldd::tpl();

    $valid = false;

    $data = array();
    $urls = array();


    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) || !empty( $_POST['ld_s_summary'] ) )
            die( "No, kitty! That's a bad kitty!" );

        $data = ld_sanitize__post( $_POST );
        $valid = ld_submit_validate_form( $data );

    }

    if ( false !== $valid && !is_wp_error( $valid ) ) {

        // Create the user and insert a post for this listing
        $user_id = ld_submit__create_user( $data['username'], $data['email'] );
        $post_id = ld_submit__create_listing( $data['title'], $data['description'], $data['category'], $user_id );

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

        // Send two emails, one to the site administrator notifying them of the listing
        // and another to the person submitting the listing for reference.
        $to_admin = ldd::tpl();

        $to_admin->assign( 'approve_link', admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
        $to_admin->assign( 'title', $data['title'] );
        $to_admin->assign( 'description', $data['description'] );

        $message = $to_admin->draw( 'email/to_admin', 1 );
        ld_mail( ldd::opt( 'email_replyto' ), __( 'A new listing was submitted for review', ldd::$slug ), $message );


        $to_owner = ldd::tpl();

        $to_owner->assign( 'site_title', get_bloginfo( 'name' ) );
        $to_owner->assign( 'admin_email', ldd::opt( 'email_replyto' ) );
        $to_owner->assign( 'title', $data['title'] );
        $to_owner->assign( 'description', $data['description'] );

        $message = $to_owner->draw( 'email/to_owner', 1 );
        ld_mail( $data['email'], ldd::opt( 'email_onsubmit_subject' ), $message );

        $tpl->assign( 'url', get_permalink( $post->ID ) );
        $tpl->assign( 'listing', $data );

        return $tpl->draw( 'submit-success', 1 );

    }

    $category_args = array(
        'hide_empty'         => 0,
        'echo'               => 0,
        'selected'           => isset( $data['category'] ) ? $data['category'] : 0,
        'hierarchical'       => 1,
        'name'               => 'ld_s_category',
        'id'                 => 'category',
        'class'         => 'form-control',
        'tab_index'          => 2,
        'taxonomy'           => LDDLITE_TAX_CAT,
    );

    $use_locale = ld_use_locale();
    $subdivision = $use_locale ? ld_get_locale() : 'US';

    $tpl->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    $tpl->assign( 'allowed_tags', allowed_tags() );
    $tpl->assign( 'header', ld_get_page_header() );

    $tpl->assign( 'home', remove_query_arg( array( 'show', 't' ) ) );

    $tpl->assign( 'form_action', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    $tpl->assign( 'nonce', wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ) );
    $tpl->assign( 'category_dropdown', wp_dropdown_categories( $category_args ) );

    $tpl->assign( 'use_locale',  $use_locale );
    $tpl->assign( 'country_dropdown', ld_dropdown_country( 'ld_s_country', $data ) );
    $tpl->assign( 'subdivision_dropdown', ld_dropdown_subdivision( $subdivision, $data ) );

    if ( is_wp_error( $valid ) ) {

        $errors = array();

        $codes = $valid->get_error_codes();

        foreach ( $codes as $code ) {
            $key = substr( $code, 0, strrpos( $code, '_' ) );
            $errors[ $key ] = '<span class="fa fa-exclamation form-control-feedback"></span><span class="submit-error">' . $valid->get_error_message( $code ) . '</span>';
        }



        $tpl->assign( 'errors', $errors );
    }


    if ( !empty( $data ) ) {

        if ( isset( $data['url'] ) ) {
            $urls = $data['url'];
            unset( $data['url'] );
            $tpl->assign( 'url', array_map( 'htmlentities', $urls ) );
        }

        $data = array_map( 'htmlentities', $data );

    }

    $tpl->assign( 'data', $data );

    return $tpl->draw( 'submit', 1 );

}
