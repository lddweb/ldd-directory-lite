<?php
/**
 * Front End AJAX
 * AJAX calls from the front end are hooked during setup.php; all the functionality for those hooks
 * resides here.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * This function responds to the "contact_form" AJAX action. All data is sanitized and double checked for validity
 * before being sent to the email on file for the listing. There's a honeypot and a math question to combat spam and
 * attempt to avoid abuse of this functionality. Listing owners can opt out of receiving contacts by excluding a
 * contact email address in their listing details.
 *
 * @since 5.3.0
 */
function ldl_ajax_contact_form() {

    if (!wp_verify_nonce($_POST['nonce'], 'contact-form-nonce'))
        die('You shall not pass!');

    $hpt_field = 'last_name';

    if (!empty($_POST[ $hpt_field ])) {
        echo json_encode(array(
            'success' => 1,
            'msg'     => '<p>' . __('Your message has been successfully sent to the email address we have on file!', 'ldd-directory-lite') . '</p>',
        ));
        die;
    }

    $answers = array(
        '14',
        'fourteen'
    );

    $name = array_key_exists('senders_name', $_POST) ? sanitize_text_field($_POST['senders_name']) : '';
    $email = array_key_exists('email', $_POST) ? sanitize_text_field($_POST['email']) : '';
    $subject = array_key_exists('subject', $_POST) ? sanitize_text_field($_POST['subject']) : '';
    $message = array_key_exists('message', $_POST) ? sanitize_text_field($_POST['message']) : '';
    $answer = array_key_exists('math', $_POST) ? sanitize_text_field($_POST['math']) : '';

    if (!is_numeric($answer)) {
        $answer = strtolower($answer);
    } else {
        $answer = intval($answer);
    }


    $errors = array();

    if (empty($name) || strlen($name) < 3)
        $errors['name'] = __('You must enter your name', 'ldd-directory-lite');
    if (empty($email) || !is_email($email))
        $errors['email'] = __('Please enter a valid email address', 'ldd-directory-lite');
    if (empty($subject) || strlen($subject) < 6)
        $errors['subject'] = __('You must enter a subject', 'ldd-directory-lite');
    if (empty($message) || strlen($message) < 10)
        $errors['message'] = __('Please enter a longer message', 'ldd-directory-lite');
    if (empty($answer) || !in_array($answer, $answers))
        $errors['math'] = __('Your math is wrong', 'ldd-directory-lite');

    if (!empty($errors)) {
        echo json_encode(array(
            'success' => 0,
            'errors'  => serialize($errors),
            'msg'     => '<p>' . __('There were errors with your form submission. Please try again.', 'ldd-directory-lite') . '</p>',
        ));
        die;
    }

    $post_id = intval($_POST['post_id']);
    $contact_email = get_post_meta($post_id, ldl_pfx('contact_email'), 1);
    $listing_title = get_the_title($post_id);

    $headers = sprintf("From: %s <%s>\r\n", $name, $email);


    if (wp_mail($contact_email, $subject, $message, $headers)) {
        $response = array(
            'success' => 1,
            'msg'     => '<p>' . sprintf(__('Your message has been successfully sent to <em>%s</em>!', 'ldd-directory-lite'), $listing_title) . '</p>',
        );
    } else {
        $response = array(
            'success' => 0,
            'msg'     => '<p>' . __('There were unknown errors with your form submission.</p><p>Please wait a while and then try again.', 'ldd-directory-lite') . '</p>',
        );
    }

    echo json_encode($response);
    die;

}

add_action('wp_ajax_contact_form', 'ldl_ajax_contact_form');
add_action('wp_ajax_nopriv_contact_form', 'ldl_ajax_contact_form');


/**
 * Stores an option to ensure the allow tracking pointer is only shown once. Also stores their answer, whether tracking
 * is allowed or not, this can also be updated via the settings screen.
 */
function ldl_store_tracking_response() {

    if (!wp_verify_nonce($_POST['nonce'], 'lddlite-allow-tracking-nonce'))
        die();

    ldl()->update_option('allow_tracking_popup_done', true);
    ldl()->update_option('allow_tracking', $_POST['allow_tracking'] == 'yes' ? true : false);

    die;

}

add_action('wp_ajax_lite_allow_tracking', 'ldl_store_tracking_response');


/**
 * Once the notice has been dismissed, don't display it again.
 */
function ldl_hide_import_notice() {

    if (wp_verify_nonce($_POST['nonce'], 'lddlite-import-nonce')) {
        echo update_option('lddlite_imported_from_original', true) ? '1' : '0';
    }

    die;
}

add_action('wp_ajax_hide_import_notice', 'ldl_hide_import_notice');
