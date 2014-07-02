<?php
/**
 * Contextual help for the settings screen on the WordPress dashboard
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

function ldl_contextual_help() {
    $screen = get_current_screen();

    if ($screen->id != LDDLITE_POST_TYPE . '_page_lddlite-settings')
        return;

    $screen->set_help_sidebar('<p><strong>' . __('Helpful Links:', 'lddlite') . '</strong></p>' . '<p>' . sprintf(__('Please visit us on <a href="%s">WordPress.org</a> for help, faqs, and other documentation.', 'lddlite'), esc_url('http://wordpress.org/plugins/ldd-directory-lite/')) . '</p>' . '<p><strong>' . __('Found a bug?', 'lddlite') . '</strong></p>' . '<p>' . sprintf(__('<a href="%s">Submit issues</a> on <a href="%s">GitHub</a>.', 'lddlite'), esc_url('https://github.com/mwaterous/ldd-directory-lite/issues'), esc_url('https://github.com/mwaterous/ldd-directory-lite')) . '</p>');

    $content = '<p>' . __("The general settings tab is where you'll configure most of the primary operations for your directory. This includes identifying pages where the shortcodes have been embedded, defining permalink slugs, and more.", 'lddlite') . '</p>' . '<p><strong>' . __('Page Settings', 'lddlite') . '</strong>' . '<br>' . __("The first two settings on this tab tell the plugin where you've placed the shortcodes <code>[directory]</code> and <code>[directory_submit]</code>. Don't forget to set these, as a few functions rely on them to return proper permalinks.", 'lddlite') . '</p>' . '<p><strong>' . __('Custom Slugs', 'lddlite') . '</strong>' . '<br>' . __('The taxonomy and post type slugs determine how a URL will appear when viewing either the category display pages or an individual listing. Remember that you probably want these to be different from the page slugs where you placed the shortcodes.', 'lddlite') . '</p>' . '<p><strong>' . __('Directory Information', 'lddlite') . '</strong>' . '<br>' . __('Tell us a little about your directory here. The information you provide can be displyed by using the <code>[directory_info]</code> shortcode, or via macros in email notifications.', 'lddlite') . '</p>' . '<p><strong>' . __('Other Settings', 'lddlite') . '</strong>' . '<br>' . __('Most of the settings found under the "Other" heading are set once and forget type features. From here you can enable or disable anonymous usage tracking (we recommend enabling it, this information helps us improve future versions of the plugin), determine whether your directory accepts public submissions and more.', 'lddlite') . '</p>';
    $screen->add_help_tab(array(
        'id'      => 'lddlite-settings-general',
        'title'   => __('General Settings', 'lddlite'),
        'content' => $content,
    ));


    $content = '<p>' . __('The settings available on the Email tab of your directory plugin settings allows you to control how email notifications appear.', 'lddlite') . '</p>' .
        '<p><strong>' . __('New Listing Notification', 'lddlite') . '</strong><br>' . __('This is sent to the administrative email (will default to WordPress settings if not configured) and is to notify site owners that a new listing is pending their review. Available macros include:', 'lddlite') . '</p>' .
        '<p><code>{approve_link}</code> ' . __('This will provide a clickable link straight to the dashboard for reviewing and approving listings.', 'lddlite') . '<br>' .
        '<code>{title}</code> ' . __('This is the title of the newly submitted listing.', 'lddlite') . '<br>' .
        '<code>{description}</code> ' . __('This is an excerpt of the the listing description.', 'lddlite') . '</p>' .
        '<p><strong>' . __('Author Receipt', 'lddlite') . '</strong><br>' . __('This is a receipt sent to the author of a new listing, and a good place to keep them informed of your terms, and approval times. Available macros include:', 'lddlite') . '</p>' .
        '<p><code>{site_title}</code> ' . __('The site title as defined in your WordPress settings.', 'lddlite') . '<br>' .
        '<code>{directory_title}</code> ' . __('The directory label, set on the General tab.', 'lddlite') . '<br>' .
        '<code>{directory_email}</code> ' . __('The from email address set on the Email tab.', 'lddlite') . '<br>' .
        '<code>{title}</code> ' . __('The title of the listing they submitted.', 'lddlite') . '</p>' .
        '<p><strong>' . __('Listing Approved', 'lddlite') . '</strong><br>' . __('Sent to the listing author when their listing status is updated from Pending Review to Approved. This will only be sent once, so if a listing status is changed and later set back to Approved a second email will not be sent. Available macros include:', 'lddlite') . '</p>' .
        '<p><code>{site_title}</code> ' . __('The site title as defined in your WordPress settings.', 'lddlite') . '<br>' .
        '<code>{directory_title}</code> ' . __('The directory label, set on the General tab.', 'lddlite') . '<br>' .
        '<code>{title}</code> ' . __('The title of the listing they submitted.', 'lddlite') . '<br>' .
        '<code>{link}</code> ' . __('A clickable link to view the newly approved listing.', 'lddlite') . '</p>';
    $screen->add_help_tab(array(
        'id'      => 'lddlite-settings-email',
        'title'   => __('Email Settings', 'lddlite'),
        'content' => $content,
    ));


    $content = '<p>' . __('In most directories the submission process will play an integral role in developing relationships and generating quality content.', 'lddlite') . '</p>' . '<p>' . __("As mentioned in the help section for the Appearance tab, there is a template that you can modify to more closely match the Submit Form with your sites look and feel. This template is <code>/templates/submit.php</code> and can be placed in your theme directory under <code>/lddlite_templates/submit.php</code> to ensure your edits aren't overwritten.", 'lddlite') . '</p>' . '<p><strong>' . __('Terms of Service', 'lddlite') . '</strong>' . '<br>' . __("A default terms of service agreement isn't included with the plugin since it would be too difficult to craft one that covered every individual situation. If you wish to include an agreement that your users must accept prior to submitting new listings, configure it here.", 'lddlite') . '</p>';
    $screen->add_help_tab(array(
        'id'      => 'lddlite-settings-submit',
        'title'   => __('Submit Form Settings', 'lddlite'),
        'content' => $content,
    ));

    $content = '<p>' . __('The Appearance tab allows you to customize some basic presentation features to help match the look and feel of the directory with your WordPress theme.', 'lddlite') . '</p>' . '<p>' . __('We tried to design the plugin in such a way that it would fit in with most themes straight out of the box, but given the sheer number of available themes or the fact that you may be using this plugin within a completely bespoke design, it is impossible to anticipate every situation.', 'lddlite') . '</p>' . '<p><strong>' . __('Colors', 'lddlite') . '</strong>' . '<br>' . __('Unless otherwise noted, these will always be in the order of normal state, hover state, and foreground. In some cases these will affect multiple elements with and without hover states, so always be sure to check after making changes.', 'lddlite') . '</p>' . '<p><strong>' . __('Template Files', 'lddlite') . '</strong>' . '<br>' . __('Most of the presentation is generated through the use of template files, just like those used in a WordPress theme.', 'lddlite') . '</p>' . '<p>' . __('These are all located in the <code>/templates</code> sub-directory of your directory plugin files. To avoid losing any customizations the next time the plugin updates, you can copy these (in whole or part) to a <code>/lddlite_templates</code> sub-directory of your theme. Only copy the files you need to customize!', 'lddlite') . '</p>' . '<p>' . __("Any presentation not controlled by a template should have a WordPress filter attached to it. These will always be in the format of 'lddlite_filter_presentation_{name}' and developer documentation will be available on our web site soon.", 'lddlite') . '</p>';
    $screen->add_help_tab(array(
        'id'      => 'lddlite-settings-appearance',
        'title'   => __('Appearance Settings', 'lddlite'),
        'content' => $content,

    ));

}
add_action('load-' . LDDLITE_POST_TYPE . '_page_lddlite-settings', 'ldl_contextual_help');
