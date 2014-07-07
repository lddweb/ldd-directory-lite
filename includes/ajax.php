<?php
/**
 * Front End AJAX
 *
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
 * @todo
 */
function ldl_ajax__contact_form() {

    if (!wp_verify_nonce($_POST['nonce'], 'contact-form-nonce'))
        die('You shall not pass!');

    $hpt_field = 'last_name';

    if (!empty($_POST[$hpt_field])) {
        echo json_encode(array(
            'success' => 1,
            'msg'     => '<p>' . __('Your message has been successfully sent to the email address we have on file!', 'lddlite') . '</p>',
        ));
        die;
    }

    $answers = array(
        '14',
        'fourteen'
    );

    $name = sanitize_text_field($_POST['first_name']);
    $email = sanitize_text_field($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = esc_html(sanitize_text_field($_POST['message']));

    $answer = sanitize_text_field(strtolower($_POST['other_name']));
    if (!is_numeric($answer))
        $answer = strtolower($answer); else
        $answer = intval($answer);

    $errors = array();

    if (empty($name) || strlen($name) < 3)
        $errors['name'] = 'You must enter your name';

    if (empty($email) || !is_email($email))
        $errors['email'] = 'Please enter a valid email address';

    if (empty($subject) || strlen($subject) < 3)
        $errors['subject'] = 'You must enter a subject';

    if (empty($message) || strlen($message) < 20)
        $errors['message'] = 'Please enter a longer message';

    if (empty($answer) || !in_array($answer, array('14', 'fourteen')))
        $errors['math'] = 'Your math is wrong';

    if (!empty($errors)) {
        echo json_encode(array(
            'success' => false,
            'errors'  => serialize($errors),
            'msg'     => '<p>There were errors with your form submission. Please back up and try again.</p>',
        ));
        die;
    }

    $post_id = intval($_POST['post_id']);
    $contact_email = get_post_meta($post_id, '_lddlite_contact_email', 1);
    $listing_title = get_the_title($post_id);

    $headers = sprintf("From: %s <%s>\r\n", $name, $email);

    $result = wp_mail('mark@watero.us', $subject, $message, $headers);
    //    $result = wp_mail( $contact_email, $subject, $message, $headers );

    if ($result) {
        $response = array(
            'success' => 1,
            'msg'     => '<p>Your message has been successfully sent to the email address we have on file for <strong style="font-style: italic;">' . $listing_title . '</strong>!</p><p>The listing owner is responsible for getting back to you. Please do not contact us directly if you have not heard back from <strong style="font-style: italic;">' . $listing_title . '</strong> in response to your message. We apologize for any inconvenience this may cause.</p>',
        );
    } else {
        $response = array(
            'success' => 0,
            'msg'     => '<p>There were unknown errors with your form submission.</p><p>Please wait a while and then try again.</p>',
        );
    }

    echo json_encode($response);
    die;

}


function ldl_store_tracking_response() {

    if (!wp_verify_nonce($_POST['nonce'], 'lite_allow_tracking_nonce'))
        die();

    $ldl = ldl_get_instance();

    $ldl->update_setting('allow_tracking_popup_done', true);

    if ($_POST['allow_tracking'] == 'yes') {
        $ldl->update_setting('allow_tracking', true);
    } else {
        $ldl->update_setting('allow_tracking', false);
    }

    $ldl->save_settings();
}


function ldl_hide_import_notice() {
    if (wp_verify_nonce($_POST['nonce'], 'directory-import-nononce')) {
        if (update_option('lddlite_imported_from_original', true))
            die('1'); else die('0');
    }
}


add_action('wp_ajax_contact_form', 'ldl_ajax__contact_form');
add_action('wp_ajax_nopriv_contact_form', 'ldl_ajax__contact_form');

add_action('wp_ajax_dropdown_change', 'ldl_ajax__dropdown_change');
add_action('wp_ajax_nopriv_dropdown_change', 'ldl_ajax__dropdown_change');

add_action('wp_ajax_lite_allow_tracking', 'ldl_store_tracking_response');
add_action('wp_ajax_hide_import_notice', 'ldl_hide_import_notice');
