<?php

/**
 *
 */

function lddlite_icon( $label, $url = '', $title = '' )
{

    $icon = LDDLITE_URL . '/public/icons' . $label . '.png';

    if ( !file_exists( $icon ) )
        return false;

    $output = '<img src="' . $icon . '" />';

    if ( !empty( $url ) )
    {
        $title = ( empty( $title ) ) ? '' : sprintf( ' title="%s" ', htmlspecialchars( $title ) );
        $output = sprintf( '<a href="%1$s" %2$s class="ldd-link ldd-icon">%3$s</a>', esc_url( $url ), $title, $output );
    }

    return $output;

}


/**
 * This function looks for any macros wrapped in a double set of curly braces and defined in
 * an associative array passed to it. All macros are replaced or removed and the template is
 * returned.
 * @since 1.3.13
 *
 * @TODO This could be an object, with a single method, allowing us to build it as we went?
 * @param string $tpl_file Relative file path to the template we're parsing
 * @param array $replace Our find and replace array
 * @return mixed
 */
function ld_parse_template( $tpl_file, $replace )
{

    // Create an absolute path to our template file
    $template = LDDLITE_TEMPLATES . '/' . $tpl_file . '.' . LDDLITE_TPL_EXT;

    // If the template doesn't exist, return false
    if ( !file_exists( $template ) )
        return false;

    // Let's get to work!
    $body = file_get_contents( $template );

    if ( is_array( $replace ) ) {
        foreach ( $replace as $macro => $value ) {
            if ( is_array( $value ) ) {
                foreach ( $value as $m => $v ) {
                    $body = str_replace( '{{' . $macro . '.' . $m . '}}', $v, $body );
                }
            } else {
                $body = str_replace( '{{'.$macro.'}}', $value, $body );
            }
        }
    }

    // Remove all occurrences of macros that have not been replaced
    $body = preg_replace( '/\{{2}.*?\}{2}/', '', $body );

    return $body; // creep.
}


function ld_ajax_search_directory() {
    global $post;

    $args = array(
        'post_type'     => LDDLITE_POST_TYPE,
        'post_status'   => 'publish',
        's'             => $_POST['s'],
    );

    $search = new WP_Query( $args );

    $output = '';
    $nth = 0;

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
                $featured = sprintf( $link, get_the_post_thumbnail( $id, array( 100, 100 ) ), 'class="search-thumbnail"' );
            else
                $featured = sprintf( $link, '<img src="' . LDDLITE_URL . '/public/icons/avatar_default.png" />', 'class="search-thumbnail"' );

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

            $output .= ld_parse_template( 'display/listing-search', $template_vars );



        }
    }

    echo $output; 
    die;
}

function lddlite_ajax_contact_form()
{

    if ( !wp_verify_nonce( $_POST['nonce'], 'contact-form-nonce' ) ) {
        die( 'You shall not pass!' );
    }

    $name = sanitize_text_field( $_POST['name'] );
    $email = sanitize_text_field( $_POST['email'] );
    $subject = sanitize_text_field( $_POST['subject'] );
    $message = esc_html( sanitize_text_field( $_POST['message'] ) );
    $math = sanitize_text_field( $_POST['math'] );

/*    $name = '';
    $email = 'mark@water';
    $subject = '';
    $message = 'message!';
    $math = '2';*/

    $errors = array();

    if ( empty( $name ) || strlen( $name ) < 3 ) {
        $errors['name'] = 'You must enter a name';
    }

    if ( empty( $email ) || !is_email( $email ) ) {
        $errors['email'] = 'Please enter a valid email address';
    }

    if ( empty( $subject ) || strlen( $subject ) < 3 ) {
        $errors['subject'] = 'You must enter a subject';
    }

    if ( empty( $message ) || strlen( $message ) < 20 ) {
        $errors['message'] = 'Please enter a longer message';
    }

    if ( empty( $math ) || '11' != $math ) {
        $errors['math'] = 'Your math is wrong';
    }

    if ( !empty( $errors ) )
    {
        echo json_encode( array(
            'errors'    => $errors,
            'success'   => false,
        ) );
        die;
    }

    $post_id = intval( $_POST['business_id'] );
    $post_id = 30; // FOR TESTING, HAZ EMAIL
    $contact_meta = get_post_meta( $post_id, '_lddlite_contact', 1 );
    $email = $contact_meta['email'];

    $headers = sprintf( "From: %1$s <%2$s>\r\n", $name, $email );

    echo json_encode( array(
        'success'   => wp_mail( 'mark@watero.us', $subject, $message, $headers ),
    ) );
    die;
}