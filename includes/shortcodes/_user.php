<?php
/**
 * View and manage listings.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/*
 * @todo ADD ERROR CHECKING TO MAKE SURE USER IS ALLOWED TO EDIT THIS POST
 */




function ldl_edit_update_post($post_id, $title, $description, $summary, $cat_id) {

    $args = array(
        'ID'           => $post_id,
        'post_title'   => $title,
        'post_content' => $description,
        'post_excerpt' => $summary,
    );

    wp_update_post($args);
    wp_set_object_terms($post_id, (int) $cat_id, LDDLITE_TAX_CAT);

    return true;
}

function ldl_edit_listing_nonces($nonce_action) {

    if (!isset($_GET['edit']))
        return;

    return 'edit-' . $_GET['edit'];
}
add_filter('lddlite_processor_nonce_action', 'ldl_edit_listing_nonces');


function ldl_process_edit_form() {
    global $lddlite_submit_processor;

    $lddlite_submit_processor = new ldd_directory_lite_processor;

    if ($lddlite_submit_processor->is_processing() && !$lddlite_submit_processor->has_errors()) {

        $post_id = $_GET['id'];
        $data = $lddlite_submit_processor->get_data();

        switch($_GET['edit']) {
            case 'details':
                ldl_edit_update_post($post_id, $data['title'], $data['description'], $data['summary'], $data['category']);
                break;

            case 'contact':
                update_post_meta($post_id, ldl_pfx('contact_email'), $data['contact_email']);
                update_post_meta($post_id, ldl_pfx('contact_phone'), $data['contact_phone']);
                update_post_meta($post_id, ldl_pfx('contact_fax'), $data['contact_fax']);
                break;
        }
        $location = add_query_arg(array('msg' => 'updated'), remove_query_arg(array('id', 'edit')));
        wp_safe_redirect($location);
    }
}
add_action('init', 'ldl_process_edit_form');


function ldl_shortcode_directory_user() {
    global $lddlite_submit_processor;

    ldl_enqueue(1);

    if (!is_user_logged_in()) {
        ldl_get_template_part('login');
        return;
    }

    $listing = isset($_GET['id']) ? get_post($_GET['id']) : false;

    if (isset($_GET['edit']) && $listing) {

        switch($_GET['edit']) {
            case 'details':

                if (!$lddlite_submit_processor->is_processing()) {
                    $cat_id = wp_get_post_terms($listing->ID,LDDLITE_TAX_CAT, array('fields' => 'ids'));
                    $data = array(
                        'title'       => $listing->post_title,
                        'category'    => $cat_id[0],
                        'description' => $listing->post_content,
                        'summary'     => $listing->post_excerpt,
                    );
                    $lddlite_submit_processor->push_data($data);
                }
                break;

            case 'contact':

                if (!$lddlite_submit_processor->is_processing()) {
                    $data = array(
                        'title'         => get_the_title($listing->ID),
                        'contact_email' => get_metadata('post', $listing->ID, ldl_pfx('contact_email'), true),
                        'contact_phone' => get_metadata('post', $listing->ID, ldl_pfx('contact_phone'), true),
                        'contact_fax'   => get_metadata('post', $listing->ID, ldl_pfx('contact_fax'), true),
                    );
                    $lddlite_submit_processor->push_data($data);
                }
                break;

            case 'social':

                if (!$lddlite_submit_processor->is_processing()) {
                    $data = array(
                        'title'        => get_the_title($listing->ID),
                        'url_website'  => get_metadata('post', $listing->ID, ldl_pfx('url_website'), true),
                        'url_facebook' => get_metadata('post', $listing->ID, ldl_pfx('url_facebook'), true),
                        'url_twitter'  => get_metadata('post', $listing->ID, ldl_pfx('url_twitter'), true),
                        'url_linkedin' => get_metadata('post', $listing->ID, ldl_pfx('url_linkedin'), true),
                    );
                    $lddlite_submit_processor->push_data($data);
                }
                break;

        }

        ldl_get_template_part('edit', $_GET['edit']);

    } else {
        ldl_get_template_part('user');
    }

}
add_shortcode('directory_user', 'ldl_shortcode_directory_user');