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

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) die;

global $wpdb;

$query = sprintf( "SELECT id FROM %s WHERE post_type = '%s'", $wpdb->posts, LDDLITE_POST_TYPE );
$post_ids = $wpdb->get_col( $query );

if ( $post_ids ) {
    foreach ( $post_ids as $id ) {
        $attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_parent'    => $id,
            'no_found_rows'  => true,
        ) );

        if ( !$attachments )
            continue;

        foreach ( $attachments as $attachment )
            wp_delete_attachment( $attachment->ID );
    }
}

$wpdb->query( sprintf( "DELETE FROM %s WHERE post_id IN ( SELECT id FROM %s WHERE post_type = '%s' ) ", $wpdb->postmeta, $wpdb->posts, LDDLITE_POST_TYPE ) );
$wpdb->query( sprintf( "DELETE FROM %s WHERE post_type = '%s'", $wpdb->posts, LDDLITE_POST_TYPE ) );

foreach ( array( LDDLITE_TAX_CAT, LDDLITE_TAX_TAG ) as $taxonomy ) {

    $results = $wpdb->get_results( sprintf("
            SELECT t.*, tt.*
            FROM $wpdb->terms AS t
                INNER JOIN $wpdb->term_taxonomy AS tt
                ON t.term_id = tt.term_id
            WHERE tt.taxonomy IN ('%s')
            ORDER BY t.name ASC
        ", $taxonomy ) );

    if ( !$results )
        continue;

    foreach ( $results as $term ) {
        $wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $term->term_taxonomy_id ) );
        $wpdb->delete( $wpdb->terms, array( 'term_id' => $term->term_id ) );
    }

    $wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
}

delete_option( 'lddlite_settings' );
delete_option( 'lddlite_version' );
delete_option( 'lddlite_upgraded_from_original' );
