<?php
/**
 * Front End AJAX
 *
 * AJAX calls from the front end are hooked during setup.php; all the functionality for those hooks
 * resides here.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */



/**
 * This responds to the AJAX live search request. Results returned by this function, at least at present, should
 * only consist of the listings themselves. The results are displayed inside the <section> markup of the content.
 *
 * @since 0.5.3
 * @todo Code duplication exists between this, the category view, and the physical search results view. Should be reduced.
 */
function ldl_ajax__search_directory() {
    global $post;

    $args = array(
        'post_type'     => LDDLITE_POST_TYPE,
        'post_status'   => 'publish',
        's'             => sanitize_text_field( $_POST['s'] ),
    );

    $search = new WP_Query( $args );

    $output = '';
    $nth = 0;

    $tpl = ldl_get_template_object();

    if ( $search->have_posts() ) {

        while ( $search->have_posts() ) {
            $search->the_post();

            $nth_class = ( $nth % 2 ) ? 'odd' : 'even';
            $nth++;

            $id         = $post->ID;
            $title      = $post->post_title;
            $summary    = $post->post_excerpt;
            $meta = ldl_get_listing_meta( $id );
            $address = $meta['address'];
            $website = $meta['website'];
            $email   = $meta['email'];
            $phone   = $meta['phone'];

            $link       = add_query_arg( array(
                'show'  => 'listing',
                't'     => $post->post_name,
            ) );

            // @todo BAD HACK!
            $link = explode( '?', $link );

            $link = $_SERVER['HTTP_REFERER'] . '?' . $link[1];

            // the following is used to build our title, and the logo
            $link_mask = '<a href="' . $link . '" title="' . esc_attr( $title ) . '">%1$s</a>';

            // the logo
            if ( has_post_thumbnail( $id ) )
                $thumbnail = sprintf( $link_mask, get_the_post_thumbnail( $id, 'directory-listing', array( 'class' => 'img-rounded' ) ) );
            else
                $thumbnail = sprintf( $link_mask, '<img src="' . LDDLITE_URL . '/public/images/noimage.png" class="img-rounded">' );

            if ( empty( $summary ) ) {
                $summary = $post->post_content;

                $summary = strip_shortcodes( $summary );

                $summary = apply_filters( 'lddlite_the_content', $summary );
                $summary = str_replace( ']]>', ']]&gt;', $summary );

                $excerpt_length = apply_filters( 'lddlite_excerpt_length', 35 );
                $excerpt_more = apply_filters( 'lddlite_excerpt_more', '&hellip;' );

                $summary = wp_trim_words( $summary, $excerpt_length, $excerpt_more );
            }

            $tpl->assign( 'id',         $id );
            $tpl->assign( 'nth',        $nth_class );
            $tpl->assign( 'thumbnail',  $thumbnail );
            $tpl->assign( 'title',      sprintf( $link_mask, $title ) );

            $tpl->assign( 'address',    $meta['address'] );
            $tpl->assign( 'website',    $meta['website'] );
            $tpl->assign( 'email',      $meta['email'] );
            $tpl->assign( 'phone',      $meta['phone'] );

            $tpl->assign( 'summary',    $summary );

            $output .= $tpl->draw( 'listing-compact', 1 );

        }

    } else { // Nothing found

        $output = $tpl->draw( 'search-notfound', 1 );

    }

    echo $output;
    die;
}


/**
 * This function responds to the "contact_form" AJAX action. All data is sanitized and double checked for validity
 * before being sent to the email on file for the listing. There's a honeypot and a math question to combat spam and
 * attempt to avoid abuse of this functionality. Listing owners can opt out of receiving contacts by excluding a
 * contact email address in their listing details.
 *
 * @since 5.3.0
 * @todo
 */
function ldl_ajax__contact_form() {

    if ( !wp_verify_nonce( $_POST['nonce'], 'contact-form-nonce' ) )
        die( 'You shall not pass!' );

    $hpt_field = 'last_name';

    if ( !empty( $_POST[ $hpt_field ] ) ) {
        echo json_encode( array(
            'success'   => 1,
            'msg'       => '<p>' . __( 'Your message has been successfully sent to the email address we have on file!', 'lddlite' ) . '</p>',
        ) );
        die;
    }

    $answers = array(
        '14',
        'fourteen'
    );

    $name = sanitize_text_field( $_POST['first_name'] );
    $email = sanitize_text_field( $_POST['email'] );
    $subject = sanitize_text_field( $_POST['subject'] );
    $message = esc_html( sanitize_text_field( $_POST['message'] ) );

    $answer = sanitize_text_field( strtolower( $_POST['other_name'] ) );
    if ( !is_numeric( $answer ) )
        $answer = strtolower( $answer );
    else
        $answer = intval( $answer );

    $errors = array();

    if ( empty( $name ) || strlen( $name ) < 3 )
        $errors['name'] = 'You must enter your name';

    if ( empty( $email ) || !is_email( $email ) )
        $errors['email'] = 'Please enter a valid email address';

    if ( empty( $subject ) || strlen( $subject ) < 3 )
        $errors['subject'] = 'You must enter a subject';

    if ( empty( $message ) || strlen( $message ) < 20 )
        $errors['message'] = 'Please enter a longer message';

    if ( empty( $answer ) || !in_array( $answer, array( '14', 'fourteen' ) ) )
        $errors['math'] = 'Your math is wrong';

    if ( !empty( $errors ) ) {
        echo json_encode( array(
            'success'   => false,
            'errors'    => serialize( $errors ),
            'msg'       => '<p>There were errors with your form submission. Please back up and try again.</p>',
        ) );
        die;
    }

    $post_id = intval( $_POST['post_id'] );
    $contact_email = get_post_meta( $post_id, '_lddlite_contact_email', 1 );
    $listing_title = get_the_title( $post_id );

    $headers = sprintf( "From: %s <%s>\r\n", $name, $email );

    $result = wp_mail( 'mark@watero.us', $subject, $message, $headers );
//    $result = wp_mail( $contact_email, $subject, $message, $headers );

    if ( $result ) {
        $response = array(
            'success'   => 1,
            'msg'       => '<p>Your message has been successfully sent to the email address we have on file for <strong style="font-style: italic;">' . $listing_title . '</strong>!</p><p>The listing owner is responsible for getting back to you. Please do not contact us directly if you have not heard back from <strong style="font-style: italic;">' . $listing_title . '</strong> in response to your message. We apologize for any inconvenience this may cause.</p>',
        );
    } else {
        $response = array(
            'success'   => 0,
            'msg'       => '<p>There were unknown errors with your form submission.</p><p>Please wait a while and then try again.</p>',
        );
    }

    echo json_encode( $response );
    die;

}


function ldl_ajax__dropdown_change() {

    $subdivision = $_POST['subdivision'];

    $labels = array(
        'US' => array(
            'sub'  => 'State',
            'code' => 'Zip'
        ),
        'CA' => array(
            'sub'  => 'Province',
            'code' => 'Postal Code',
        ),
        'GB' => array(
            'sub'  => 'County',
            'code' => 'Post Code',
        ),
    );

    $defaults = array(
        'sub'  => 'State / Province / Locality',
        'code' => 'Zip / Postal Code',
    );

    if ( isset( $labels[ $subdivision ] ) ) {
        $sub  = $labels[ $subdivision ]['sub'];
        $code = $labels[ $subdivision ]['code'];
    } else {
        //$sub  = '"' . $subdivision . '"' . $defaults['sub'];
        $sub  = $defaults['sub'];
        $code = $defaults['code'];
    }

    $output = ldl_dropdown_subdivision( $subdivision, '', 9 );
    echo json_encode( array(
        'subdivision' => $subdivision,
        'input' => $output,
        'sub'   => $sub,
        'code'  => $code,
    ) );

    die;
}


function ldl_store_tracking_response() {

	if ( !wp_verify_nonce( $_POST['nonce'], 'lite_allow_tracking_nonce' ) )
		die();

	$ldl = ldl_get_instance();

	$ldl->update_setting( 'allow_tracking_popup_done', true );

	if ( $_POST['allow_tracking'] == 'yes' ) {
		$ldl->update_setting( 'allow_tracking', true );
	} else {
		$ldl->update_setting( 'allow_tracking', false );
	}

	$ldl->save_settings();
}


function ldl_hide_upgrade_notice() {
    if( wp_verify_nonce( $_POST['nonce'], 'directory-upgrade-nononce' ) ) {
        if( update_option( 'lddlite_upgraded_from_original', true ) ) die( '1' );
        else die( '0' );
    }
}

add_action( 'wp_ajax_search_directory',        'ldl_ajax__search_directory' );
add_action( 'wp_ajax_nopriv_search_directory', 'ldl_ajax__search_directory' );

add_action( 'wp_ajax_contact_form',        'ldl_ajax__contact_form' );
add_action( 'wp_ajax_nopriv_contact_form', 'ldl_ajax__contact_form' );

add_action( 'wp_ajax_dropdown_change',        'ldl_ajax__dropdown_change' );
add_action( 'wp_ajax_nopriv_dropdown_change', 'ldl_ajax__dropdown_change' );

add_action( 'wp_ajax_lite_allow_tracking', 'ldl_store_tracking_response' );
add_action( 'wp_ajax_hide_directoryup_notice', 'ldl_hide_upgrade_notice' );
