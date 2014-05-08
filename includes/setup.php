<?php
/**
 * Setup all necessary functionality for the Directory
 *
 * This includes any necessary calls that exist prior to or in the `init` hook. Everything
 * that occurs after `init` can be found in actionfilters.php.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

require_once( LDDLITE_PATH . '/includes/shortcode.php' );

add_shortcode( 'directory',             'ld_shortcode__display' );
/**
 * @deprecated since version 2.0.0, please use [directory] instead
 */
add_shortcode( 'business_directory',    'ld_shortcode__display' );

add_image_size( 'directory-listing', 300, 300 );
add_image_size( 'directory-listing-compact', 105, 300 );
add_image_size( 'directory-listing-search', 100, 100 );



/**
 * Registers our custom taxonomies and post types.
 *
 * @since 2.0.0
 * @todo (low priority) Can we use the internal rewrites effectively?
 */
function ld_setup__register_custom() {

    register_taxonomy( LDDLITE_TAX_CAT, LDDLITE_POST_TYPE, array(
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => false, // We are handling this internally until there's time to explore it fully.
    ));

    register_taxonomy( LDDLITE_TAX_TAG, LDDLITE_POST_TYPE, array(
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => false,
    ));

    $labels =

    $args = array(
        'labels'        => array(
            'name'                  => 'Directory Listings',
            'singular_name'         => 'Directory Listing',
            'add_new'               => 'Add Listing',
            'add_new_item'          => 'Add New Listing',
            'edit_item'             => 'Edit Listing',
            'new_item'              => 'New Directory Listing',
            'view_item'             => 'View Directory Listing',
            'search_items'          => 'Search Directory Listings',
            'not_found'             => 'No directory listings found',
            'not_found_in_trash'    => 'No directory listings found in Trash',
            'parent_item_colon'     => 'Parent Directory Listing',
            'menu_name'             => 'Directory [lite]'
        ),

        'hierarchical'  => false,

        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
        'taxonomies'    => array( LDDLITE_TAX_CAT, LDDLITE_TAX_TAG ),
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 25,
        'menu_icon'     => '',

        'show_in_nav_menus'     => false,
        'publicly_queryable'    => true,
        'exclude_from_search'   => false, // @todo (high priority) Should it be?
        'has_archive'           => true,
        'query_var'             => true,
        'can_export'            => true,
        'rewrite'               => true,
        'capability_type'       => 'post'
    );

    register_post_type( LDDLITE_POST_TYPE, $args );

}


function ld_setup__register_scripts() {
    wp_register_script( ldd::$slug,                       LDDLITE_URL . '/public/js/lite.js', array( 'jquery' ), LDDLITE_VERSION, true );
    wp_register_script( ldd::$slug . '-responsiveslides', LDDLITE_URL . '/public/js/responsiveslides.js', array( 'jquery' ), '1.54', true );
    wp_register_script( ldd::$slug . '-search',           LDDLITE_URL . '/public/js/search.js', array( 'jquery' ), LDDLITE_VERSION, true );

    wp_register_style( ldd::$slug, LDDLITE_URL . '/public/css/style.css', false, LDDLITE_VERSION );
    wp_register_style( 'yui-pure', '//yui.yahooapis.com/pure/0.4.2/pure-min.css', false, '0.4.2' );

    // Admin
    wp_register_script( ldd::$slug . '-admin', LDDLITE_URL . '/public/js/admin.js', array( 'jquery-ui-dialog' ), LDDLITE_VERSION, 1 );
    wp_register_style(  ldd::$slug . '-admin', LDDLITE_URL . '/public/css/admin.css', false, LDDLITE_VERSION );

}


add_action( 'init', 'ld_setup__register_custom' );
add_action( 'init', 'ld_setup__register_scripts' );


/**
 * Initialize all Ajax hooks
 */
require_once( LDDLITE_PATH . '/includes/ajax.php' );

add_action( 'wp_ajax_search_directory', 'ld_ajax__search_directory' );
add_action( 'wp_ajax_nopriv_search_directory', 'ld_ajax__search_directory' );

add_action( 'wp_ajax_contact_form', 'ld_ajax__contact_form' );
add_action( 'wp_ajax_nopriv_contact_form', 'ld_ajax__contact_form' );
