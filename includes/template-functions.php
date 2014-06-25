<?php

/**
 * Template Functions
 *
 * Most of the following functions are modified core functionality to help render plugin templates in a fashion
 * that's intuitive to the way WordPress handles templates.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */



/**
 * This is the trailing name for the directory containing all directory templates.
 *
 * @since 0.5.5
 * @return string
 */
function ldl_get_template_dir_name() {
    return trailingslashit( apply_filters( 'lddlite_template_dir_name', 'lddlite_templates' ) );
}


/**
 * Nearly identical to WordPress own get_template_part(), we're mainly duplicating this to swap out
 * locate_template() with our own helper function.
 *
 * @since 0.5.5
 * @uses ldl_locate_template()
 * @param string $slug The parent template we're looking for
 * @param string $name The specific type of a particular parent template, if any
 */
function ldl_get_template_part( $slug, $name = null ) {

    do_action( 'get_template_part_' . $slug, $slug, $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
        $templates[] = "{$slug}-{$name}.php";

    $templates[] = "{$slug}.php";

    // Using the array, locate a template and return it
    return ldl_locate_template( $templates, true, false );
}


/**
 * Again, almost identical to the core functionality for locate_template(), this version creates an array of
 * path names to search in, starting with the child theme, parent theme and ending with our default plugin templates.
 *
 * @todo How are we going to let developers know when there's major updates to a core template?
 *
 * @since 0.5.5
 * @param array $templates The array of templates to look for
 * @param bool $load Whether to return the path, or to load the template
 * @param bool $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function ldl_locate_template( $templates, $load = false, $require_once = true ) {

    // No template found yet
    $located = false;

    // Build an array of locations to search
    $template_trailing = ldl_get_template_dir_name();

    $template_paths = array(
        trailingslashit( get_stylesheet_directory() ) . $template_trailing,
        trailingslashit( get_template_directory() ) . $template_trailing,
        trailingslashit( LDDLITE_PATH . 'templates' ),
    );

    foreach ( (array) $templates as $template ) {

        // Continue if template is empty
        if ( empty( $template ) )
            continue;

        // Trim off any slashes from the template name
        $template = ltrim( $template, '/' );

        // try locating this template file by looping through the template paths
        foreach( $template_paths as $path ) {

            if( file_exists( trailingslashit( $path ) . $template ) ) {
                $located = trailingslashit( $path ) . $template;
                break;
            }

        }

    }

    if ( true == $load && false != $located )
        load_template( $located, $require_once );

    return $located;
}


/**
 * Get the link to the submit form
 *
 * @since 0.5.5
 * @todo This will have to be updated once the submit is fully transitioned to its own shortcode/page
 */
function ldl_get_submit_form_link() {
    return add_query_arg( array( 'show' => 'submit', 't' => 'listing' ) );
}


/**
 * Similar to WordPress core home_url(), this uses the directory_page setting to return a permalink
 * to the directory home page.
 *
 * @param string $path Optional path relative to the home url.
 * @param string $scheme Optional scheme to use
 * @return string Full permalink to the home page of our directory
 */
function ldl_get_home_url( $path = '', $scheme = null ) {

    $url = get_permalink( ldl_get_setting( 'directory_page' ) );

    if ( !in_array( $scheme, array( 'http', 'https', 'relative' ) ) )
        $scheme = is_ssl() ? 'https' : parse_url( $url, PHP_URL_SCHEME );

    $url = set_url_scheme( $url, $scheme );

    if ( $path && is_string( $path ) )
        $url .= '/' . ltrim( $path, '/' );

    return apply_filters( 'ldl_home_url', $url, $path );
}


function  ldl_get_header() {
    ldl_get_template_part( 'header' );
}

function ldl_get_thumbnail( $post_id ) {

    $link_mask = '<a href="' . $link . '" title="' . esc_attr( $title ) . '">%1$s</a>';

    if ( has_post_thumbnail( $post_id ) )
        $thumbnail = sprintf( $link_mask, get_the_post_thumbnail( $post_id, 'directory-listing', array( 'class' => 'img-rounded' ) ) );
    else
        $thumbnail = sprintf( $link_mask, '<img src="' . LDDLITE_URL . 'public/images/noimage.png" class="img-rounded">' );

    return $thumbnail;
}

function ldl_get_title() {
    $post_id = get_the_ID();

    if ( !is_int( $post_id ) )
        return false;

    $link_mask = '<a href="%1$s" title="%2$s">%2$s</a>';

    return sprintf( $link_mask, get_permalink( $post_id ), get_the_title( $title ) );
}


function ldl_get_address( $key = 'formatted' ) {
    $post_id = get_the_ID();

    if ( !is_int( $post_id ) )
        return false;

    $geo = get_post_meta( $post_id, '_lddlite_geo', true );

    if ( array_key_exists( $key, $geo ) )
        return $geo[ $key ];

    return false;
}