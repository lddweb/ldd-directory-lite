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

    if ( !wp_verify_nonce( $_POST['nonce'], 'search-form-nonce' ) )
        die( 'You shall not pass!' );

    $args = array(
        'post_type'     => LDDLITE_POST_TYPE,
        'post_status'   => 'publish',
        's'             => $_POST['s'],
    );

    $search = new WP_Query( $args );

    $output = '';
    $nth = 0;

    $tpl = ldd::tpl();

    if ( $search->have_posts() ) {

        while ( $search->have_posts() ) {
            $search->the_post();

            $id = $post->ID;
            $permalink = get_the_permalink();
            $slug = $post->post_name;
            $title = $post->post_title;

            $url = get_post_meta( $id, '_lddlite_url_website', true );

            // determine our classes;
            $nth_class = ( $nth % 2 ) ? 'odd' : 'even';
            $nth++;

            // the following is used to build our title, and the logo
            $link = '<a href="' . $permalink . '?show=listing&t=' . $title . '" title="' . esc_attr( $title ) . '" %2$s>%1$s</a>';

            // the logo
            if ( has_post_thumbnail( $id ) )
                $featured = sprintf( $link, get_the_post_thumbnail( $id, 'directory-listing-search' ), 'class="search-thumbnail"' );
            else
                $featured = sprintf( $link, '<img src="' . LDDLITE_URL . '/public/images/avatar_default.png" />', 'class="search-thumbnail"' );

            $summary = '';

            if ( !empty( $post->post_excerpt ) )
                $summary = $post->post_excerpt;

            if ( empty( $summary ) ) {
                $summary = $post->post_content;

                $summary = strip_shortcodes( $summary );

                $summary = apply_filters( 'lddlite_the_content', $summary );
                $summary = str_replace( ']]>', ']]&gt;', $summary );

                $excerpt_length = apply_filters( 'lddlite_excerpt_length', 55 );
                $excerpt_more = apply_filters( 'lddlite_excerpt_more', sprintf( '&hellip; (' . $link . ')', 'view listing', '' ) );

                $summary = wp_trim_words( $summary, $excerpt_length, $excerpt_more );
            }


            $template_vars = array(
                'id'        => $id,
                'nth'       => $nth_class,
                'featured'  => $featured,
                'title'     => sprintf( $link, $title, '' ),
                'url'       => $url,
                'summary'   => $summary,
            );
            $tpl->assign( $template_vars );

            $output .= $tpl->draw( 'display/search-listing', 1 );

        }

    } else { // Nothing found

        $output = $tpl->draw( 'display/search-notfound', 1 );

    }

    echo $output;
    die;
}


function ld_ajax__contact_form() {

    if ( !wp_verify_nonce( $_POST['nonce'], 'contact-form-nonce' ) )
        die( 'You shall not pass!' );


    $name = sanitize_text_field( $_POST['name'] );
    $email = sanitize_text_field( $_POST['email'] );
    $subject = sanitize_text_field( $_POST['subject'] );
    $message = esc_html( sanitize_text_field( $_POST['message'] ) );
    $math = sanitize_text_field( $_POST['math'] );

    $errors = array();

    if ( empty( $name ) || strlen( $name ) < 3 )
        $errors['name'] = 'You must enter a name';

    if ( empty( $email ) || !is_email( $email ) )
        $errors['email'] = 'Please enter a valid email address';

    if ( empty( $subject ) || strlen( $subject ) < 3 )
        $errors['subject'] = 'You must enter a subject';

    if ( empty( $message ) || strlen( $message ) < 20 )
        $errors['message'] = 'Please enter a longer message';

    if ( empty( $math ) || '11' != $math )
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