<?php
/**
 * Setup all necessary functionality for the Directory
 * This includes any necessary calls that exist prior to or in the `init` hook. Everything
 * that occurs after `init` can be found in actionfilters.php.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


add_image_size('directory-listing', 300, 300);


/**
 * Register an autoloader for the plugins helper classes
 *
 * @since 0.6.0
 *
 * @param string $class The class being instantiated
 */
function ldl_autoload($class) {

    $file_bit = str_replace('ldd_directory_lite_', '', $class);
    $class_file = LDDLITE_PATH . '/includes/class.' . $file_bit . '.php';

    if (file_exists($class_file))
        require_once($class_file);

}

spl_autoload_register('ldl_autoload');


/**
 * Registers our custom taxonomies and post types.
 *
 * @since 0.5.0
 */
function ldl_register_post_type() {

    $taxonomy_slug = ldl()->get_option('directory_taxonomy_slug', 'listings');
    $post_type_slug = ldl()->get_option('directory_post_type_slug', 'listing');
//mdd(array($taxonomy_slug,$post_type_slug));
    register_taxonomy(LDDLITE_TAX_CAT, LDDLITE_POST_TYPE, array(
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug'         => $taxonomy_slug,
            'heirarchical' => true,
        ),
    ));

    register_taxonomy(LDDLITE_TAX_TAG, LDDLITE_POST_TYPE, array(
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => false,
    ));

    $supports = array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions');
    $supports = apply_filters('lddlite_post_type_supports', $supports);

    $args = array(
        'labels'              => array(
            'name'               => __('Directory Listings', 'ldd-directory-lite'),
            'singular_name'      => __('Directory Listing', 'ldd-directory-lite'),
            'add_new'            => __('Add Listing', 'ldd-directory-lite'),
            'add_new_item'       => __('Add New Listing', 'ldd-directory-lite'),
            'edit_item'          => __('Edit Listing', 'ldd-directory-lite'),
            'new_item'           => __('New Listing', 'ldd-directory-lite'),
            'view_item'          => __('View Listing', 'ldd-directory-lite'),
            'search_items'       => __('Search Listings', 'ldd-directory-lite'),
            'not_found'          => __('No listings found', 'ldd-directory-lite'),
            'not_found_in_trash' => __('No listings found in Trash', 'ldd-directory-lite'),
            'parent_item_colon'  => __('Parent Listing', 'ldd-directory-lite'),
            'menu_name'          => __('Directory', 'ldd-directory-lite'),
        ),
        'hierarchical'        => false,
        'supports'            => $supports,
        'taxonomies'          => array(LDDLITE_TAX_CAT, LDDLITE_TAX_TAG),
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => '27.3',
        'menu_icon'           => '',
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => false,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => array(
            'slug'  => $post_type_slug,
            'feeds' => false,
            'pages' => false,
        ),
        'capability_type'     => 'post'
    );

    register_post_type(LDDLITE_POST_TYPE, $args);

}
add_action('init', 'ldl_register_post_type', 5);


/**
 * Register all global styles and scripts.
 */
function ldl_register_scripts() {

    wp_register_style('lddlite', LDDLITE_URL . '/public/css/directory.min.css', false, LDDLITE_VERSION);
    wp_register_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', false, '4.1.0');
    wp_register_style('lddlite-admin', LDDLITE_URL . '/public/css/admin.css', false, LDDLITE_VERSION);

    wp_register_script('lddlite-happy', LDDLITE_URL . '/public/js/happy.js', array('jquery'), LDDLITE_VERSION, true);
    wp_register_script('lddlite-contact', LDDLITE_URL . '/public/js/contact.js', array('jquery'), LDDLITE_VERSION, true);
    wp_register_script('lddlite-admin', LDDLITE_URL . '/public/js/admin.js', array('jquery-ui-dialog'), LDDLITE_VERSION, 1);

}

add_action('init', 'ldl_register_scripts', 5);


/**
 * Attempt to enqueue bootstrap stylesheet as early as possible, so that any conflicts with the theme
 * will cascade in favor of the theme.
 */
function ldl_enqueue_bootstrap() {

    if (ldl()->get_option('disable_bootstrap') || is_admin())
        return;

    wp_enqueue_style('lddlite-bootstrap', LDDLITE_URL . '/public/css/bootstrap.min.css', array(), LDDLITE_VERSION);
    wp_enqueue_script('lddlite-bootstrap', LDDLITE_URL . '/public/js/bootstrap.min.js', array('jquery'), '3.2.0', true);

}

add_action('init', 'ldl_enqueue_bootstrap', 1);


/**
 * Enqueue scripts
 */
function ldl_enqueue($force = false) {

    if (is_admin())
        return;

    if (LDDLITE_POST_TYPE == get_post_type() || $force) {
        wp_enqueue_style('lddlite');
        wp_enqueue_style('font-awesome');

        wp_enqueue_script('lddlite-happy');
    }

}

add_action('wp_head', 'ldl_enqueue');
