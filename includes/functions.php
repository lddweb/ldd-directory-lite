<?php
/**
 * General functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Pull an array of directory post IDs for use in updating all the information.
 */
function ldl_get_all_IDs() {
    global $wpdb;

    $query = sprintf("
					SELECT ID
					FROM `%s`
					WHERE post_type = '%s'
						AND post_status NOT IN ( 'auto-draft', 'inherit' )
				", $wpdb->posts, LDDLITE_POST_TYPE);

    return $wpdb->get_col($query);
}


/**
 * Returns an array of default settings for initial use by the plugin. Also allows for the addition of
 * new settings without running any additional upgrade methods.
 *
 * @since yore
 * @return array The default settings
 */
function ldl_get_default_settings() {

    $email = array();
    $site_title = get_bloginfo('name');

    $signature = <<<SIG
*****************************************
This is an automated message from {$site_title}
Please do not respond directly to this email
SIG;

    $email['to_admin'] = <<<EM
<p><strong>A new listing is pending review!</strong></p>

<p>This submission is awaiting approval. Please visit the link to view and approve the new listing:</p>

<p>{approve_link}</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
    $email['on_submit'] = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing is pending approval.</p>
<p>Please review the following information for accuracy, as this is what will appear on our web site. If you see any errors, please contact us immediately at {directory_email}.</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
    $email['on_approve'] = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing has been approved! You can now view it online:</p>
<p>{link}</p>
EM;

    foreach ($email as $key => $msg) {
        $email[ $key ] = $msg . $signature;
    }

    $defaults = apply_filters('lddlite_default_settings_array', array(
        'directory_front_page'          => '',
        'directory_submit_page'         => '',
        'directory_taxonomy_slug'       => 'listings',
        'directory_post_type_slug'      => 'listing',
        'directory_label'               => get_bloginfo('name'),
        'directory_description'         => '',
        'disable_bootstrap'             => 0,
        'google_maps'                   => 1,
        'email_from_name'               => get_bloginfo('name'),
        'email_from_address'            => get_bloginfo('admin_email'),
        'email_notification_address'    => get_bloginfo('admin_email'),
        'email_toadmin_subject'         => 'A new listing has been submitted for review!',
        'email_toadmin_body'            => $email['to_admin'],
        'email_onsubmit_subject'        => 'Your listing on ' . $site_title . ' is pending review!',
        'email_onsubmit_body'           => $email['on_submit'],
        'email_onapprove_subject'       => 'Your listing on ' . $site_title . ' was approved!',
        'email_onapprove_body'          => $email['on_approve'],
        'submit_use_tos'                => 0,
        'submit_tos'                    => '',
        'submit_intro'                  => '<p>' . __('Please tell us a little bit about the organization you would like to see listed in our directory. Try to include as much information as you can, and be as descriptive as possible where asked.', 'ldd-directory-lite') . '</p>',
        'submit_success'                => '<h3>' . __('Congratulations!', 'ldd-directory-lite') . '</h3><p>' . __('Your listing has been successfully submitted for review. Please allow us sufficient time to review the listing and approve it for public display in our directory.', 'ldd-directory-lite') . '</p>',
        'allow_tracking_popup_done'     => 0,
        'allow_tracking'                => 0,
        'appearance_display_featured'   => 1,
        'appearance_primary_normal'     => '#3bafda',
        'appearance_primary_hover'      => '#3071a9',
        'appearance_primary_foreground' => '#ffffff',
    ));

    return $defaults;
}


/**
 * Wrapper to collect post meta for a listing
 *
 * @param $id
 * @param $field
 */
function ldl_has_meta($key) {
    return ldl_get_meta($key) ? true : false;
}


/**
 * Wrapper to collect post meta for a listing
 *
 * @param $id
 * @param $field
 */
function ldl_get_meta($key) {

    $post_id = get_the_ID();

    if (!$post_id) {
        return false;
    }

    return get_metadata('post', $post_id, ldl_pfx($key), true);
}


/**
 * Appends the plugin prefix to key/field identifiers to maintain readability in other functions
 *
 * @param string $key The field identifier to be prefixed
 *
 * @return string A prefixed string
 */
function ldl_pfx($key) {
    $prefixed = '_' . trim(LDDLITE_PFX, '_') . '_';

    return $prefixed . $key;
}


function ldl_get_listing_meta($id) {

    if (!is_int($id) || LDDLITE_POST_TYPE != get_post_type($id))
        return false;

    $defaults = array(
        'address_one' => '',
        'address_two' => '',
        'city'        => '',
        'subdivision' => '',
        'post_code'   => '',
        'address'     => '',
        'geocode'     => '',
        'website'     => '',
        'email'       => '',
        'phone'       => '',
    );

    $meta['address_one'] = get_post_meta($id, ldl_pfx('address_one'), 1);
    $meta['address_two'] = get_post_meta($id, ldl_pfx('address_two'), 1);
    $meta['city'] = get_post_meta($id, ldl_pfx('city'), 1);
    $meta['subdivision'] = get_post_meta($id, ldl_pfx('subdivision'), 1);
    $meta['post_code'] = get_post_meta($id, ldl_pfx('post_code'), 1);

    $address = '';
    $geocode = '';

    foreach ($meta as $key => $value) {
        if ('address_two' != $key && empty($value)) {
            $address = false;
            break;
        }
    }

    if (false !== $address) {

        $address = '<i class="fa fa-map-marker"></i>  ' . $meta['address_one'];
        if (!empty($meta['address_two']))
            $address .= '<br>' . $meta['address_two'];
        $address .= ',<br>' . $meta['city'] . ', ' . $meta['subdivision'] . ' ' . $meta['post_code'];

        $geocode = urlencode(str_replace('<br>', ' ', $address));

    } else {
        $address = '';
    }

    $meta['address'] = $address;
    $meta['geocode'] = $geocode;

    $website = get_post_meta($id, ldl_pfx('url_website'), 1);
    if ($website)
        $meta['website'] = apply_filters('lddlite_listing_website', sprintf('<a href="%1$s"><i class="fa fa-link"></i>  %1$s</a>', esc_url($website)));

    $meta['email'] = get_post_meta($id, ldl_pfx('contact_email'), 1);
    $meta['phone'] = get_post_meta($id, ldl_pfx('contact_phone'), 1);

    $meta = wp_parse_args($meta, $defaults);

    return $meta;

}


function ldl_sanitize_twitter($url) {

    if (empty($url))
        return;

    $url = preg_replace('/[^A-Za-z0-9\/:.]/', '', $url);

    if (strpos($url, '/') !== false)
        $url = substr($url, strrpos($url, '/') + 1);

    $url = 'https://twitter.com/' . $url;

    return $url;
}


/**
 * Alias for wp_mail that sets headers for us.
 *
 * @since 1.3.13
 *
 * @param string $to      Email address this message is going to
 * @param string $subject Email subject
 * @param string $message Email contents
 * @param string $headers Optional, default is managed internally.
 */
function ldl_mail($to, $subject, $message, $headers = '') {

    $from_name = ldl()->get_option('email_from_name', get_bloginfo('name'));
    $from_email = ldl()->get_option('email_from_address', get_bloginfo('admin_email'));

    // If we're not passing any headers, default to our internal from address
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= sprintf('From: %s <%s>', $from_name, $from_email) . "\r\n";
    }

    ob_start();
    wp_mail($to, $subject, $message, $headers);
    ob_end_clean();

}

/**
 * Output Functions
 * =====================================================================================================================
 */

/**
 * Replaces the protocol with HTTPS
 *
 * @since 0.5.3
 *
 * @param string $url The URL
 *
 * @return string The modified URL
 */
function ldl_force_scheme($url, $scheme = 'https') {

    if (0 !== strpos($url, 'http'))
        $url = esc_url_raw($url);

    return set_url_scheme($url, $scheme);
}


