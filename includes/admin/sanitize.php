<?php
/**
 * Filter callbacks for the various administrative tabs
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * "General" tab
 *
 * @param array $input Array of input values to be processed
 *
 * @return array The sanitized $input array
 */
function lddlite_settings_general_sanitize($input) {

    if (isset($input['debug_uninstall'])) {
        define('WP_UNINSTALL_PLUGIN', true);
        require_once(LDDLITE_PATH . '/uninstall.php');
    }

    $input['directory_archive_slug'] = sanitize_title($input['directory_archive_slug']);
    $input['directory_listing_slug'] = sanitize_title($input['directory_listing_slug']);
    $input['directory_label'] = wp_filter_nohtml_kses($input['directory_label']);
    $input['disable_bootstrap'] = '1' == $input['disable_bootstrap'] ? 1 : 0;
    $input['google_maps'] = '0' == $input['google_maps'] ? 0 : 1;

    flush_rewrite_rules();

    return $input;
}
add_filter('lddlite_settings_general_sanitize', 'lddlite_settings_general_sanitize');


/**
 * "Email" tab
 *
 * @param array $input Array of input values to be processed
 *
 * @return array The sanitized $input array
 */
function lddlite_settings_email_sanitize($input) {

    $input['email_toadmin_subject'] = wp_filter_nohtml_kses($input['email_toadmin_subject']);
    $input['email_onsubmit_subject'] = wp_filter_nohtml_kses($input['email_onsubmit_subject']);
    $input['email_onapprove_subject'] = wp_filter_nohtml_kses($input['email_onapprove_subject']);

    return $input;
}
add_filter('lddlite_settings_email_sanitize', 'lddlite_settings_email_sanitize');


/**
 * "Submit Form" tab
 *
 * @param array $input Array of input values to be processed
 *
 * @return array The sanitized $input array
 */
function lddlite_settings_submit_sanitize($input) {


    $input['submit_use_tos'] = '1' == $input['submit_use_tos'] ? 1 : 0;
    $input['submit_tos'] = wp_filter_nohtml_kses($input['submit_tos']);
    $input['submit_use_locale'] = '1' == $input['submit_use_locale'] ? 1 : 0;
    $input['submit_require_address'] = '1' == $input['submit_require_address'] ? 1 : 0;

    return $input;
}
add_filter('lddlite_settings_submit_sanitize', 'lddlite_settings_submit_sanitize');


/**
 * "Appearance" tab
 *
 * @param array $input Array of input values to be processed
 *
 * @return array The sanitized $input array
 */
function lddlite_settings_appearance_sanitize($input) {

    $input['disable_bootstrap'] = '1' == $input['disable_bootstrap'] ? 1 : 0;
    $input['appearance_display_featured'] = '1' == $input['appearance_display_featured'] ? 1 : 0;

    $input['appearance_display_new'] = '1' == $input['appearance_display_new'] ? 1 : 0;

    if (!preg_match('~#([a-fA-F0-9]){3}(([a-fA-F0-9]){3})?\b~', $input['appearance_panel_background']))
        $input['appearance_primary'] = '#c0ffee';
    if (!preg_match('~#([a-fA-F0-9]){3}(([a-fA-F0-9]){3})?\b~', $input['appearance_panel_foreground']))
        $input['appearance_primary'] = '#fff';

    return $input;
}
add_filter('lddlite_settings_appearance_sanitize', 'lddlite_settings_appearance_sanitize');
