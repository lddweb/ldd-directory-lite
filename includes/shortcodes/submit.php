<?php
/**
 * Submit a listing view controller and other functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/** The following are template functions relevant only to this shortcode **/

/**
 * Alias for wp_dropdown_categories() that uses our settings array to determine the output.
 */
function ldl_submit_categories_dropdown($selected = 0, $id = 'category', $classes = array('form-control')) {

    if (is_string($classes))
        $classes = explode(' ', $classes);

    $classes = apply_filters('lddlite_categories_dropdown_class', $classes);

    $pfx = ldd_directory_lite_processor::DATA_PREFIX;
    $name = $pfx . $id;

    $category_args = array(
        'hide_empty'   => 0,
        'echo'         => 0,
        'selected'     => $selected,
        'hierarchical' => 1,
        'name'         => $name,
        'id'           => $id,
        'class'        => implode(' ', $classes),
        'tab_index'    => 2,
        'taxonomy'     => LDDLITE_TAX_CAT,
    );

    echo wp_dropdown_categories($category_args);
}


function ldl_get_value($key) {
    global $lddlite_submit_processor;

    return $lddlite_submit_processor->get_value($key);
}

function ldl_get_error($key) {
    global $lddlite_submit_processor;

    return $lddlite_submit_processor->get_error($key);
}


function ldl_has_errors() {
    global $lddlite_submit_processor;

    return $lddlite_submit_processor->has_errors();
}


function ldl_has_global_errors() {
    global $lddlite_submit_processor;

    return $lddlite_submit_processor->has_global_errors();
}


function ldl_get_global_errors() {
    global $lddlite_submit_processor;

    return $lddlite_submit_processor->get_global_errors();
}

/** End of template functions **/


/**
 * Create a new user during the submission process. If successful, send them an internal email providing them
 * with their login information.
 *
 * @param string $username The username (this should have been checked during processing)
 * @param string $email    Same as above, this should already be valid
 *
 * @return int|object Returns the user_id if successful, WP_Error if not.
 */
function ldl_submit_create_user($username, $email) {

    $password = wp_generate_password(14, true);
    $user_id = wp_create_user($username, $password, $email);

    if (!is_wp_error($user_id)) {
        wp_new_user_notification($user_id, $password);
    }

    return $user_id;
}


/**
 * Create the post associated with the new listing. This takes information from the submit form, and a $user_id
 * that was generated previously in ldl_submit_create_user()
 *
 * @param string $name The listing/post title
 * @param string $description The description, which may contain markdown
 * @param int $cat_id The taxonomy ID for this listing
 * @param int $user_id The author of this listing
 *
 * @return int|WP_Error A valid $post_id on success, and the WP_Error object on failure
 */
function ldl_submit_create_post($name, $description, $cat_id, $user_id) {

    $args = array(
        'post_content' => $description,
        'post_title'   => $name,
        'post_status'  => 'pending',
        'post_type'    => LDDLITE_POST_TYPE,
        'post_author'  => $user_id,
        'post_date'    => date('Y-m-d H:i:s'),
    );

    $post_id = wp_insert_post($args);

    if (!is_wp_error($post_id)) {
        wp_set_object_terms($post_id, (int) $cat_id, LDDLITE_TAX_CAT);
    }

    return $post_id;
}


function ldl_submit_create_meta($data, $post_id) {

    $remove_fields = array('title', 'category', 'description', 'summary', 'username', 'email',);

    $data = array_diff_key($data, array_flip($remove_fields));

    foreach ($data as $key => $value) {
        add_post_meta($post_id, LDDLITE_PFX . $key, $value);
    }

}


function ldl_submit_notify_admin($data, $post_id) {

    $to = ldl_get_setting('email_notifications');
    $subject = ldl_get_setting('email_toadmin_subject');
    $message = ldl_get_setting('email_toadmin_body');

    $message = str_replace('{aprove_link}', admin_url('post.php?post=' . $post_id . '&action=edit'), $message);
    $message = str_replace('{title}', $data[ 'title' ], $message);
    $message = str_replace('{description}', $data[ 'description' ], $message);

    ldl_mail($to, $subject, $message);
}


function ldl_submit_notify_author($data) {

    $to = $data[ 'email' ];
    $subject = ldl_get_setting('email_onsubmit_subject');
    $message = ldl_get_setting('email_onsubmit_body');

    $message = str_replace('{site_title}', get_bloginfo('name'), $message);
    $message = str_replace('{directory_title}', ldl_get_setting('directory_label'), $message);
    $message = str_replace('{directory_email}', ldl_get_setting('email_from_address'), $message);
    $message = str_replace('{title}', $data[ 'title' ], $message);
    $message = str_replace('{description}', $data[ 'description' ], $message);

    ldl_mail($to, $subject, $message);
}


/**
 * Used by ldl_submit_generate_listing() to destroy any evidence that we started to generate a listing. This is a
 * stopgap measure to allow the form to fail and be resubmitted. I think this can be better, but I want to get to
 * stable before I spend more time on it.
 *
 * @since 0.5.5
 *
 * @param array $ids An associate array identifying what needs to be deleted
 */
function ldl_submit_rollback($ids) {
    foreach ($ids as $id) {
        switch ($id) {
            case 'user_id':
                wp_delete_user($id);
                break;
            case 'post_id':
                wp_delete_post($id);
                break;
        }
    }
}


/**
 * This is responsible for generating the listing based on the information provided in the form submission. Everything
 * should be sanitized and validated by now via the $lddlite_submit_processor object, however since errors are still
 * possible, I want this to be able to rollback on items that are created, or store their IDs for use on the next pass.
 *
 * @since 0.5.5
 * @return bool True only if successful, false if any errors occur
 */
function ldl_submit_generate_listing() {
    global $lddlite_submit_processor;

    $data = $lddlite_submit_processor->get_data();

    // If anything fails, we need to start over.
    // I considered storing the IDs as they were generated, and simply setting back on whatever failed,
    // but that won't work unless I have a way of disabling form fields on the fly.

    $user_id = is_user_logged_in() ? get_current_user_id() : ldl_submit_create_user($data[ 'username' ], $data[ 'email' ]);
    if (is_wp_error($user_id)) {
        $lddlite_submit_processor->set_global_error(__('There was a problem creating your user account. Please try again later.', 'lddlite'));

        return false;
    }

    $post_id = ldl_submit_create_post($data[ 'title' ], $data[ 'description' ], $data[ 'category' ], $user_id);
    if (!$post_id) {
        $lddlite_submit_processor->set_global_error(__('There was a problem creating your listing. Please try again later.', 'lddlite'));
        ldl_submit_rollback(array(
            'user_id' = $user_id,
        ));

        return false;
    }

    // Add all the post meta fields
    ldl_submit_create_meta($data, $post_id);

    // Upload their logo if one was submitted
    if (isset($_FILES[ 'ld_s_logo' ])) {
        // These files need to be included as dependencies when on the front end.
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('ld_s_logo', 0);
        if (is_wp_error($attachment_id)) {
            $lddlite_submit_processor->set_global_error(__('There was a problem uploading your logo. Please try again!', 'lddlite'));
            ldl_submit_rollback(array(
                'user_id' = $user_id,
                'post_id' = $post_id,
            ));

            return false;
        } else {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    ldl_submit_notify_admin($data, $post_id); // Notification of new listing
    ldl_submit_notify_author($data); // Receipt of submission

    return true;
}


/**
 * This is our shortcode callback for the submit listing form. It handles the display and the processing of the form
 * through delegation to the ldl_submit... functions found in this file.
 *
 * @since 0.5.5
 */
function ldl_shortcode__submit() {
    global $lddlite_submit_processor;

    ldl_enqueue();

    // Set up the processor
    $lddlite_submit_processor = new ldd_directory_lite_processor;

    if ($lddlite_submit_processor->is_processing() && !$lddlite_submit_processor->has_errors()) {
        do_action('lddlite_submit_pre_process', $lddlite_submit_processor);

        if (ldl_submit_generate_listing()) {
            ldl_get_template_part('submit', 'success');
            do_action('lddlite_submit_post_process' $lddlite_submit_processor);

            return;
        }

    }

    ldl_get_template_part('submit');

}

add_shortcode('directory_submit', 'ldl_shortcode__submit');
