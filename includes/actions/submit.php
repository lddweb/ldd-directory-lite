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


function ldl_append_tos() {

    if ( !ldl_use_tos() )
        return;

    $tpl = ldl_get_template_object();
    $tpl->assign( 'tos', ldl_get_setting( 'submit_tos' ) );
    $tpl->draw( 'modal-tos' );

}
add_action( 'wp_footer', 'ldl_append_tos' );


function ldl_submit__email_admin( array $data, $post_id ) {

    $subject = ldl_get_setting( 'email_toadmin_subject' );
    $message = ldl_get_setting( 'email_toadmin_body' );

    $message = str_replace( '{aprove_link}', admin_url( 'post.php?post=' . $post_id . '&action=edit' ), $message );
    $message = str_replace( '{title}', $data['title'], $message );
    $message = str_replace( '{description}', $data['description'], $message );

    ldl_mail( ldl_get_setting( 'email_admin' ), $subject, $message );
}


function ldl_submit__email_owner( array $data ) {

    $subject = ldl_get_setting( 'email_onsubmit_subject' );
    $message = ldl_get_setting( 'email_onsubmit_body' );

    $message = str_replace( '{site_title}', get_bloginfo( 'name' ), $message );
    $message = str_replace( '{directory_title}', ldl_get_setting( 'directory_label' ), $message );
    $message = str_replace( '{directory_email}', ldl_get_setting( 'email_admin' ), $message );
    $message = str_replace( '{title}', $data['title'], $message );
    $message = str_replace( '{description}', $data['description'], $message );

    ldl_mail( $data['email'], $subject, $message );
}


function ldl_action__submit( $term = false ) {
    global $post;

    wp_enqueue_script( 'lddlite-responsiveslides' );
    wp_enqueue_script( 'lddlite-submit' );

    $tpl = ldl_get_template_object();
    $tpl->assign( 'header', ldl_get_header( 0, 1 ) );
    $tpl->assign( 'home', remove_query_arg( array( 'show', 't' ) ) );


    $valid = false;

    $data = array();
    $urls = array();
    $errors = array();

    $success = false;

    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) || !empty( $_POST['ld_s_summary'] ) )
            die( "No, kitty! That's a bad kitty!" );

        $data = ldl_sanitize__post( $_POST );
        $valid = ldl_submit_validate_form( $data );

        if ( is_wp_error( $valid ) ) {

            $errors = array();
            $codes = $valid->get_error_codes();

            foreach ( $codes as $code ) {
                $key = substr( $code, 0, strrpos( $code, '_' ) );
                $errors[ $key ] = '<span class="fa fa-exclamation form-control-feedback"></span><span class="submit-error">' . $valid->get_error_message( $code ) . '</span>';
            }

        } else {

            // Create the user and insert a post for this listing
	        if ( is_user_logged_in() ) {
		        $user_id = get_current_user_id();
	        } else {
		        $user_id = ldl_submit__create_user( $data['username'], $data['email'] );
	        }
            $post_id = ldl_submit__create_listing( $data['title'], $data['description'], $data['category'], $user_id );

            // Add all the post meta fields
            ldl_submit__create_meta( $data, $post_id );

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
                $data[ $key ] = htmlentities( $value );
            }

            ldl_submit__email_admin( $data, $post_id );
            ldl_submit__email_owner( $data );

            $success = true;
            $data = array();

        }

    }

    $tpl->assign( 'form_action', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    $tpl->assign( 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    $tpl->assign( 'nonce', wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ) );

	$settings =   array(
		'wpautop' => true,
		'media_buttons' => false,
		'textarea_name' => 'ld_s_description',
		'textarea_rows' => 4,
		'tabindex' => '',
		'editor_class' => 'form-control',
		'teeny' => true,
	);


    $category_args = array(
        'hide_empty'    => 0,
        'echo'          => 0,
        'selected'      => isset( $data['category'] ) ? $data['category'] : 0,
        'hierarchical'  => 1,
        'name'          => 'ld_s_category',
        'id'            => 'category',
        'class'         => 'form-control',
        'tab_index'     => 2,
        'taxonomy'      => LDDLITE_TAX_CAT,
    );

    $tpl->assign( 'allowed_tags', allowed_tags() );
    $tpl->assign( 'category_dropdown', wp_dropdown_categories( $category_args ) );

    if ( isset( $data['country'] ) )
        $subdivision = $data['country'];
    else
        $subdivision = ldl_get_locale();

    $tpl->assign( 'use_locale',  ldl_use_locale() );
    $tpl->assign( 'country_dropdown', ldl_dropdown_country( 'ld_s_country', $data ) );
    $tpl->assign( 'subdivision_dropdown', ldl_dropdown_subdivision( $subdivision, $data, 10 ) );

    $tpl->assign( 'use_tos', ldl_get_setting( 'submit_use_tos' ) );
    $tpl->assign( 'tos', ldl_get_setting( 'submit_tos' ) );


    if ( !empty( $data ) ) {

        if ( isset( $data['url'] ) ) {
            $urls = $data['url'];
            unset( $data['url'] );

        }

        $data = array_map( 'htmlentities', $data );
    }

    $tpl->assign( 'data', $data );
    $tpl->assign( 'errors', $errors );
    $tpl->assign( 'success', $success );

    $panel_general   = $tpl->draw( 'submit-general', 1 );
    $panel_geography = $tpl->draw( 'submit-geography', 1 );
    $panel_urls      = $tpl->draw( 'submit-urls', 1 );
	if ( !is_user_logged_in() )
        $panel_account   = $tpl->draw( 'submit-account', 1 );

    $tpl->assign( 'panel_general',   $panel_general );
    $tpl->assign( 'panel_geography', $panel_geography );
    $tpl->assign( 'panel_urls',      $panel_urls );
    $tpl->assign( 'panel_account',   $panel_account );



	return $tpl->draw( 'submit', 1 );

}
