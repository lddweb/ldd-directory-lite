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


function ld_ajax__search_directory() {
    global $post;

    $args = array(
        'post_type'     => LDDLITE_POST_TYPE,
        'post_status'   => 'publish',
        's'             => sanitize_text_field( $_POST['s'] ),
    );

    $search = new WP_Query( $args );

    $output = '';
    $nth = 0;

    // @todo NEARLY IDENTICAL TO CATEGORY RESULTS
    $tpl = ldl::tpl();

    if ( $search->have_posts() ) {

        while ( $search->have_posts() ) {
            $search->the_post();

            $nth_class = ( $nth % 2 ) ? 'odd' : 'even';
            $nth++;

            $id         = $post->ID;
            $title      = $post->post_title;
            $summary    = $post->post_excerpt;
            $meta = ld_get_listing_meta( $id );
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


function ld_ajax__contact_form() {

    if ( !wp_verify_nonce( $_POST['nonce'], 'contact-form-nonce' ) )
        die( 'You shall not pass!' );

    /*    $hpt_field = 'summary';

        if ( !empty( $_POST[ $hpt_field ] ) ) {
            echo json_encode( array(
                'success' => 1,
            ) );
            die;
        }*/

    $answers = array(
        '14',
        'fourteen'
    );

    $name = sanitize_text_field( $_POST['first_name'] );
    $email = sanitize_text_field( $_POST['email'] );
    $subject = sanitize_text_field( $_POST['subject'] );
    $message = esc_html( sanitize_text_field( $_POST['message'] ) );

    $answer = sanitize_text_field( strtolower( $_POST['last_name'] ) );

    $errors = array();

    if ( empty( $name ) || strlen( $name ) < 3 )
        $errors['name'] = 'You must enter your name';

    if ( empty( $email ) || !is_email( $email ) )
        $errors['email'] = 'Please enter a valid email address';

    if ( empty( $subject ) || strlen( $subject ) < 3 )
        $errors['subject'] = 'You must enter a subject';

    if ( empty( $message ) || strlen( $message ) < 20 )
        $errors['message'] = 'Please enter a longer message';

    if ( empty( $math ) || '11' != $math || 'eleven' != strtolower( $math ) )
        $errors['math'] = 'Your math is wrong';

    if ( !empty( $errors ) ) {
        echo json_encode( array(
            'errors'    => $errors,
            'success'   => false,
        ) );
        die;
    }

    $post_id = intval( $_POST['business_id'] );
    $contact_meta = get_post_meta( $post_id, '_lddlite_contact', 1 );
    $email = $contact_meta['email'];

    $headers = sprintf( "From: %1$s <%2$s>\r\n", $name, $email );

    echo json_encode( array(
        'success'   => wp_mail( 'mark@watero.us', $subject, $message, $headers ),
    ) );

    die;

}


function ld_ajax__dropdown_change() {

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

    $output = ld_dropdown_subdivision( $subdivision, '', 9 );
    echo json_encode( array(
        'subdivision' => $subdivision,
        'input' => $output,
        'sub'   => $sub,
        'code'  => $code,
    ) );

    die;
}