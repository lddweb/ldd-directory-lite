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


/**
 * This is a hot mess and is just going to wind up even more fragmented at this rate.
 * Start brainstorming how to rebuild this before it's too late.
 * BEGIN PROCESS.PHP
 **********************************************************************************************************************/


function ldl_submit__create_user( $username, $email ) {

    $password = wp_generate_password( 14, true );
    $user_id = wp_create_user( $username, $password, $email );

    if ( $user_id )
        wp_new_user_notification( $user_id, $password );

    return $user_id;
}


function ldl_submit__create_listing( $name, $description, $cat_ID, $user_id ) {

    $listing = array(
        'post_content'  => $description,
        'post_title'    => $name,
        'post_status'   => 'pending',
        'post_type'     => LDDLITE_POST_TYPE,
        'post_author'   => $user_id,
        'post_date'     => date( 'Y-m-d H:i:s' ),
    );

    $post_ID = wp_insert_post( $listing );

    if ( $post_ID ) {
        wp_set_object_terms( $post_ID, (int) $cat_ID, LDDLITE_TAX_CAT );
        return $post_ID;
    }

    return false;
}


function ldl_submit__create_meta( $data, $post_id ) {

    $remove_fields = array(
        'title',
	    'category',
        'description',
        'summary',
        'username',
	    'email',
    );

    $data = array_diff_key( $data, array_flip( $remove_fields ) );

    foreach ( $data as $key => $value ) {
        add_post_meta( $post_id, LDDLITE_PFX . $key, $value );
    }

}


function ldl_submit__email_admin( array $data, $post_id ) {

    $subject = ldl_get_setting( 'email_toadmin_subject' );
    $message = ldl_get_setting( 'email_toadmin_body' );

    $message = str_replace( '{aprove_link}', admin_url( 'post.php?post=' . $post_id . '&action=edit' ), $message );
    $message = str_replace( '{title}', $data['title'], $message );
    $message = str_replace( '{description}', $data['description'], $message );

    ldl_mail( ldl_get_setting( 'email_notifications' ), $subject, $message );
}


function ldl_submit__email_owner( array $data ) {

    $subject = ldl_get_setting( 'email_onsubmit_subject' );
    $message = ldl_get_setting( 'email_onsubmit_body' );

    $message = str_replace( '{site_title}', get_bloginfo( 'name' ), $message );
    $message = str_replace( '{directory_title}', ldl_get_setting( 'directory_label' ), $message );
    $message = str_replace( '{directory_email}', ldl_get_setting( 'email_from_address' ), $message );
    $message = str_replace( '{title}', $data['title'], $message );
    $message = str_replace( '{description}', $data['description'], $message );

    ldl_mail( $data['email'], $subject, $message );
}


/**
 * Alias for wp_dropdown_categories() that uses our settings array to determine the output.
 */
function ldl_submit_categories_dropdown( $selected = 0, $id = 'category', $classes = array( 'form-control' ) ) {
	global $data;

	if ( is_string( $classes ) )
		$classes = explode( ' ', $classes );

	$classes = apply_filters( 'lddlite_categories_dropdown_class', $classes );

	$pfx = ldd_directory_lite_processor::DATA_PREFIX;
	$name = $pfx . $id;

	$category_args = array(
		'hide_empty'    => 0,
		'echo'          => 0,
		'selected'      => $selected,
		'hierarchical'  => 1,
		'name'          => $name,
		'id'            => $id,
		'class'         => implode( ' ', $classes ),
		'tab_index'     => 2,
		'taxonomy'      => LDDLITE_TAX_CAT,
	);

	echo wp_dropdown_categories( $category_args );
}


function ldl_get_required_fields() {

	$map = array(
		'title',
		'category',
		'description',
		'summary',
	);

	return apply_filters( 'lddlite_submit_required_fields', $map );
}


function ldl_get_value( $key ) {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->get_value( $key );
}

function ldl_get_error( $key ) {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->get_error( $key );
}


function ldl_has_errors() {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->has_errors();
}


function ldl_has_global_errors() {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->has_global_errors();
}


function ldl_get_global_errors() {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->get_global_errors();
}


function ldl_shortcode__submit() {
	global $lddlite_submit_processor;

	// Set up shortcode
    ldl_enqueue();
    //wp_enqueue_script( 'lddlite-submit' );
	//wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places' );

	// Set up the processor
	$lddlite_submit_processor = new ldd_directory_lite_processor;

	if ( $lddlite_submit_processor->is_processing() && !$lddlite_submit_processor->has_errors() ) {
		do_action( 'lddlite_submit_pre_process', $lddlite_submit_processor );

		// Pull this into the local scope
		$data = $lddlite_submit_processor->get_data();

/*		$user_id = is_user_logged_in() ? get_current_user_id() :ldl_submit__create_user( $data['username'], $data['email'] );
		if ( !$user_id ) {
			$lddlite_submit_processor->set_global_error( __( 'There was a problem creating your user account. Please try again later.', 'lddlite' ) );
		}

		$post_id = ldl_submit__create_listing( $data['title'], $data['description'], $data['category'], $user_id );
		if ( !$post_id ) {
			$lddlite_submit_processor->set_global_error( __( 'There was a problem creating your listing. Please try again later.', 'lddlite' ) );
		}*/

		// Add all the post meta fields
		//ldl_submit__create_meta( $data, $post_id );
		ldl_submit__create_meta( $data, 0 );
		mdd( $data );
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

		ldl_get_template_part( 'submit', 'success' );
	} else {
		ldl_get_template_part( 'submit' );
	}

}

add_shortcode( 'directory_submit', 'ldl_shortcode__submit' );
