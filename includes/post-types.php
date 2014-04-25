<?php

function lddlite_register__cpt_tax()
{
    global $wp_rewrite;

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

    $labels = array(
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
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports'      => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
        'taxonomies'    => array( LDDLITE_TAX_CAT, LDDLITE_TAX_TAG ),
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_position' => 25,
        'menu_icon'     => '',

        'show_in_nav_menus'     => false,
        'publicly_queryable'    => true,
        'exclude_from_search'   => false,
        'has_archive'           => true,
        'query_var'             => true,
        'can_export'            => true,
        'rewrite'               => true,
        'capability_type'       => 'post'
    );

    register_post_type( LDDLITE_POST_TYPE, $args );
    $wp_rewrite->flush_rules();

}


function lddlite_filter_enter_title_here ( $title )
{

    if ( get_post_type() == LDDLITE_POST_TYPE ) {
        $title = __( 'Business Name', lddslug() );
    }

    return $title;
}


function lddlite_filter_admin_post_thumbnail_html( $content )
{

    if ( LDDLITE_POST_TYPE == get_post_type() ) {
        $content = str_replace( __( 'Set featured image' ), __( 'Upload A Logo', lddslug() ), $content);
    }

    return $content;
}


function lddlite_action_directory_icon()
{

    echo "\n\t<style>";
    echo '#adminmenu .menu-icon-' . LDDLITE_POST_TYPE . ' div.wp-menu-image:before { content: \'\\f307\'; }';
    echo '</style>';

}


function lddlite_action_submenu_name()
{
    global $submenu;

    $submenu['edit.php?post_type=' . LDDLITE_POST_TYPE][5][0] = 'All Listings';
}


function lddlite_filter_post_type_link( $post_link, $post )
{

    if ( LDDLITE_POST_TYPE != get_post_type( $post->ID ) )
        return $post_link;

    _lddlite_set_shortcode_ID();
    $lddlite = lddlite();

    $directory_link = get_permalink( $lddlite->directory_home_ID );

    return ( $directory_link . '?show=business&term=' . $post->post_name );
}


function _lddlite_set_shortcode_ID( $force = false)
{
    $lddlite = lddlite();

    if ( empty( $lddlite->directory_home_ID ) || $force )
    {
        $posts = get_posts( array(
            'posts_per_page'    => -1,
            'post_type'         => 'page',
        ) );

        $pattern = get_shortcode_regex();
        foreach ( $posts as $post )
        {
            if ( preg_match( "/$pattern/s", $post->post_content ) )
            {
                $lddlite->directory_home_ID = $post->ID;
                break;
            }
        }

    }

}
