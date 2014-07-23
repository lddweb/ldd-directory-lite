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


/**
 * Update the main details for a particular listing.
 *
 * @param int $post_id The post ID for the listing being updated
 * @param string $title The title for the updated listing
 * @param string $description This is the main post_content
 * @param string $summary This is the post_excerpt
 * @param int $cat_id Category ID for the listing to be associated with
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

}


/**
 * Generate a nonce name for the processor based on the edit action taking place.
 *
 * @param string $nonce_action The filter should alway send an empty string
 *
 * @return string The nonce name
 */
function ldl_edit_listing_nonces($nonce_action) {

    if (!isset($_GET['edit']))
        return;

    return 'edit-' . $_GET['edit'];
}
add_filter('lddlite_processor_nonce_action', 'ldl_edit_listing_nonces');


/**
 * Handles all the submitted data once the processor class is done sanitizing and validating it. This is hooked to
 * init in order to send a redirect header on successful updates.
 */
function ldl_process_edit_form() {
    global $lddlite_submit_processor;

    if (!is_user_logged_in())
        return;

    $lddlite_submit_processor = new ldd_directory_lite_processor;

    if (!isset($_GET['id']))
        return;

    $post = get_post($_GET['id']);
    $post_id = $post->ID;
    // Verify that the current user can edit this listing
    // current_user_can() doesn't work here because a subscriber doesn't have edit_post capabilities
    $can_edit = $post->post_author == get_current_user_id();

    if (!$can_edit)
        return;

    if ($lddlite_submit_processor->is_processing() && !$lddlite_submit_processor->has_errors()) {

        $data = $lddlite_submit_processor->get_data();

        // The data is valid, decide where it goes
        switch($_GET['edit']) {
            case 'details':
                ldl_edit_update_post($post_id, $data['title'], $data['description'], $data['summary'], $data['category']);
                break;

            case 'logo':
                // @TODO Repetitious code alert, here and _submit.php
                if (isset($_FILES['n_logo']) && 0 === $_FILES['n_logo']['error']) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');

                    $attachment_id = media_handle_upload('n_logo', 0);
                    if (is_wp_error($attachment_id)) {
                        $lddlite_submit_processor->set_global_error(__('There was a problem uploading your logo. Please try again!', 'lddlite'));
                        return false;
                    } else {
                        set_post_thumbnail($post_id, $attachment_id);
                    }
                }
                break;

            case 'contact':
            case 'social':
            case 'location':
                foreach ($data as $key => $value) {
                    update_post_meta($post_id, ldl_pfx($key), $value);
                }
                break;

        }

        // Redirect back to the manage listings screen with a success message
        $location = add_query_arg(array('msg' => 'updated'), remove_query_arg(array('id', 'edit')));
        wp_safe_redirect($location);
    }
}
add_action('init', 'ldl_process_edit_form');


/**
 * The shortcode callback for [directory_manage], this will display the login/registration form if the user
 * isn't currently logged in. If the user is logged in and has submitted listings, they will appear here in a table
 * with related links to edit the content.
 */
function ldl_shortcode_directory_manage() {
    global $lddlite_submit_processor;

    ldl_enqueue(1);

    if (!is_user_logged_in()) {
        ldl_get_template_part('login');
        return;
    }

    $listing = isset($_GET['id']) ? get_post($_GET['id']) : false;
    $can_edit = ($listing) ? $listing->post_author == get_current_user_id() : false;

    if (isset($_GET['edit']) && $can_edit) {

        if (!$lddlite_submit_processor->is_processing()) {
            switch($_GET['edit']) {
                case 'details':
                    $cat_id = wp_get_post_terms($listing->ID, LDDLITE_TAX_CAT, array('fields' => 'ids'));
                    $data = array(
                        'title'       => $listing->post_title,
                        'category'    => $cat_id[0],
                        'description' => $listing->post_content,
                        'summary'     => $listing->post_excerpt,
                    );
                    $lddlite_submit_processor->push_data($data);
                    break;

                case 'contact':
                    $data = array(
                        'title'         => get_the_title($listing->ID),
                        'contact_email' => get_metadata('post', $listing->ID, ldl_pfx('contact_email'), true),
                        'contact_phone' => get_metadata('post', $listing->ID, ldl_pfx('contact_phone'), true),
                        'contact_fax'   => get_metadata('post', $listing->ID, ldl_pfx('contact_fax'), true),
                    );
                    $lddlite_submit_processor->push_data($data);
                    break;

                case 'social':
                    $data = array(
                        'title'        => get_the_title($listing->ID),
                        'url_website'  => get_metadata('post', $listing->ID, ldl_pfx('url_website'), true),
                        'url_facebook' => get_metadata('post', $listing->ID, ldl_pfx('url_facebook'), true),
                        'url_twitter'  => get_metadata('post', $listing->ID, ldl_pfx('url_twitter'), true),
                        'url_linkedin' => get_metadata('post', $listing->ID, ldl_pfx('url_linkedin'), true),
                    );
                    $lddlite_submit_processor->push_data($data);
                    break;

                case 'logo':
                    $data = array(
                        'title' => get_the_title($listing->ID),
                        'thumb' => ldl_get_thumbnail($listing->ID),
                    );
                    $lddlite_submit_processor->push_data($data);
                    break;

                case 'location':
                    // @TODO Repetitious code alert, here and _submit.php
                    wp_enqueue_script('jquery-ui-autocomplete');
                    wp_enqueue_script('maps-autocomplete', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&ver=3.9.1');
                    wp_enqueue_script('lddlite-submit', ldl_plugin_url('public/js/submit.js'), 'maps-autocomplete', LDDLITE_VERSION);
                    $data = array(
                        'title' => get_the_title($listing->ID),
                        'geo'   => get_metadata('post', $listing->ID, ldl_pfx('geo'), true),
                        'address_one' => get_metadata('post', $listing->ID, ldl_pfx('address_one'), true),
                        'address_two' => get_metadata('post', $listing->ID, ldl_pfx('address_two'), true),
                        'postal_code' => get_metadata('post', $listing->ID, ldl_pfx('postal_code'), true),
                        'country' => get_metadata('post', $listing->ID, ldl_pfx('country'), true),
                    );
                    $lddlite_submit_processor->push_data($data);
                    break;

            }
        }

        ldl_get_template_part('edit', $_GET['edit']);

    } else {
        ldl_get_template_part('manage');
    }

}
add_shortcode('directory_manage', 'ldl_shortcode_directory_manage');
