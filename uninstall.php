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

if (!defined('WP_UNINSTALL_PLUGIN'))
    die;

require_once(dirname(__FILE__) . '/ldd-directory-lite.php');

/**
 * Collect and then delete all attachments
 */
function ldl_uninstall_attachments() {
    global $wpdb;

    $query = sprintf("
					SELECT ID
					FROM `%s`
					WHERE post_type = '%s'
						AND post_status NOT IN ( 'auto-draft', 'inherit' )
				", $wpdb->posts, LDDLITE_POST_TYPE);

    $post_ids = $wpdb->get_col($query);

    if (!$post_ids)
        return;

    $post_ids = implode(',', $post_ids);

    $attachments = get_posts(array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'post_parent_in' => $post_ids,
        'no_found_rows'  => true,
    ));

    if ($attachments) {
        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID);
        }
    }

}


/**
 * Delete everything with as little of a footprint as we can muster, to try and ensure this process succeeds.
 */
function ldl_uninstall_posts() {
    global $wpdb;

    // Delete postmeta and posts
    $wpdb->query(sprintf("DELETE FROM %s WHERE post_id IN ( SELECT id FROM %s WHERE post_type = '%s' ) ", $wpdb->postmeta, $wpdb->posts, LDDLITE_POST_TYPE));
    $wpdb->query(sprintf("DELETE FROM %s WHERE post_type = '%s'", $wpdb->posts, LDDLITE_POST_TYPE));

}


/**
 * Deletes all taxonomy data
 */
function ldl_uninstall_taxonomies() {
    global $wpdb;

    // Loop through our taxonomies and destroy
    foreach (array(LDDLITE_TAX_CAT, LDDLITE_TAX_TAG) as $taxonomy) {

        $results = $wpdb->get_results(sprintf("
            SELECT t.*, tt.*
            FROM $wpdb->terms AS t
                INNER JOIN $wpdb->term_taxonomy AS tt
                ON t.term_id = tt.term_id
            WHERE tt.taxonomy IN ('%s')
            ORDER BY t.name ASC
        ", $taxonomy));

        if (!$results)
            continue;

        foreach ($results as $term) {
            $wpdb->delete($wpdb->term_taxonomy, array('term_taxonomy_id' => $term->term_taxonomy_id));
            $wpdb->delete($wpdb->terms, array('term_id' => $term->term_id));
        }

        $wpdb->delete($wpdb->term_taxonomy, array('taxonomy' => $taxonomy), array('%s'));
    }

}


/**
 * Uninstall
 */
ldl_uninstall_attachments();
ldl_uninstall_posts();
ldl_uninstall_taxonomies();

delete_option('lddlite_settings');
delete_option('lddlite_version');
delete_option('lddlite_imported_from_original');

// mdd?\s?\(
// test: md() mdd()
// Never ship a release with either of those two commands anywhere but here.
