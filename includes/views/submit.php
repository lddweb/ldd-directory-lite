<?php

/**
 *
 */

require_once( LDDLITE_PATH . '/includes/views/submit-functions.php' );
require_once( LDDLITE_PATH . '/includes/views/submit-process.php' );


function ld_view_submit( $term = false ) {
    global $post;

    $valid = false;

    if ( isset( $_POST['nonce_field'] ) && !empty( $_POST['nonce_field'] ) ) {

        if ( !wp_verify_nonce( $_POST['nonce_field'], 'submit-listing-nonce' ) )
            die( 'No, kitty! That\'s a bad kitty!' );

        $data = ld_submit_process_post( $_POST );
        // @TODO REMOVE
        $data['street'] = '450 Michelle Cir';
        $valid = ld_submit_validate_form( $data );

    }

    if ( $valid == true && is_array( $data ) ) {

        // Create the user and insert a post for this listing
        $user_id = ld_submit_create_user( $data['username'], $data['email'] );
        $post_id = ld_submit_create_listing( $data['name'], $data['description'], $data['category'], $user_id );

        // Add all the post meta fields
        ld_submit_create_meta( $data, $post_id );

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

    $selected = isset( $data['category'] ) ? $data['category'] : 0;

    $category_args = array(
        'hide_empty'         => 0,
        'echo'               => 0,
        'selected'           => $selected,
        'hierarchical'       => 1,
        'name'               => 'ld_s_category',
        'id'                 => 'category',
        'tab_index'          => 2,
        'taxonomy'           => LDDLITE_TAX_CAT,
    );

    $template_vars = array(
        'url'          => get_permalink( $post->ID ),
        'action'            => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'nonce'             => wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ),
        'category_dropdown' => wp_dropdown_categories( $category_args ),
        'country_dropdown'  => ld_dropdown_country(),
    );

    if ( is_wp_error( $valid ) ) {

        $template_vars['errors'] = array();

        $codes = $valid->get_error_codes();

        foreach ( $codes as $code ) {
            $key = substr( $code, 0, strrpos( $code, '_' ) );
            $template_vars['errors'][ $key ] = '<span class="submit-error">' . $valid->get_error_message( $code ) . '</span>';
        }

        $template_vars['data'] = array_map( 'htmlentities', $data );

    }

    return ld_parse_template( 'display/submit', $template_vars );

}
