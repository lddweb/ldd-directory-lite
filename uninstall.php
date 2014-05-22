<?php
/**
 * This will fire when the plugin is uninstalled, removing all options and post types (hopefully).
 *
 * @package   LDDBD
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 *
 */

//if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) die;


function ldl_uninstall_data() {
    global $wpdb;

    $query = sprintf( "SELECT id FROM %s WHERE post_type = '%s'", $wpdb->posts, LDDLITE_POST_TYPE );
    $post_ids = $wpdb->get_col( $query );

    if ( !empty( $post_ids ) ) {
        foreach ( $post_ids as $id ) {
            $attachments = get_posts( array(
                'post_type'      => 'attachment',
                'posts_per_page' => -1,
                'post_status'    => 'any',
                'post_parent'    => $id
            ) );

            if ( empty( $attachments ) )
                continue;

            foreach ( $attachments as $attachment )
                wp_delete_attachment( $attachment->ID );
        }
    }

    $wpdb->query( sprintf( "DELETE FROM %s WHERE post_id IN ( SELECT id FROM %s WHERE post_type = '%s' ) ", $wpdb->postmeta, $wpdb->posts, LDDLITE_POST_TYPE ) );
    $wpdb->query( sprintf( "DELETE FROM %s WHERE post_type = '%s'", $wpdb->posts, LDDLITE_POST_TYPE ) );


    $categories = get_terms(
        LDDLITE_TAX_CAT,
        array( 'hide_empty' => false, 'fields' => 'ids' )
    );

    if ( $categories ) {
        foreach ( $categories as $term_id ) {
            wp_delete_term( $term_id, LDDLITE_TAX_CAT );
        }
    }

    $tags = get_terms(
        LDDLITE_TAX_TAG,
        array( 'hide_empty' => false, 'fields' => 'ids' )
    );

    if ( $tags ) {
        foreach ( $tags as $term_id ) {
            wp_delete_term( $term_id, LDDLITE_TAX_TAG );
        }
    }

    delete_option( 'lddlite-options' );
    delete_option( 'lddlite_settings' );
    delete_option( 'lddlite_version' );

}


function ldl_uninstall() {
    global $wpdb;

    if ( is_multisite() ) {

        $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
        if ( $blogs ) {

            foreach ( $blogs as $blog ) {
                switch_to_blog( $blog['blog_id'] );
                ldl_uninstall_data();
                restore_current_blog();
            }
        }

    } else {
        ldl_uninstall_data();
    }
}

add_action( 'init', 'ldl_uninstall', 20 );

