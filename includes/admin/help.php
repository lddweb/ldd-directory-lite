<?php

/**
 * Settings contextual help.
 *
 * @access      private
 * @since       1.4
 * @return      void
 */
function ld_admin__help() {
    $screen = get_current_screen();

    if ( $screen->id != LDDLITE_POST_TYPE . '_page_lddlite-settings' )
        return;

    $screen->set_help_sidebar(
        '<p><strong>' . __( 'Helpful Links:', ldl::$slug ) . '</strong></p>' .
        '<p>' . sprintf( __( 'Please visit us on <a href="%s">WordPress.org</a> for help, faqs, and other documentation.', ldl::$slug ), esc_url( 'http://wordpress.org/plugins/ldd-directory-lite/' ) ) . '</p>' .
        '<p><strong>' . __( 'Found a bug?', ldl::$slug ) . '</strong></p>' .
        '<p>' . sprintf( __( '<a href="%s">Submit issues</a> on <a href="%s">GitHub</a>.', ldl::$slug ),
            esc_url( 'https://github.com/mwaterous/ldd-directory-lite/issues' ),
            esc_url( 'https://github.com/mwaterous/ldd-directory-lite' )
        ) . '</p>'
    );

    $screen->add_help_tab( array(
        'id'	    => 'lddlite-settings-general',
        'title'	    => __( 'General Help', ldl::$slug ),
        'content'	=> '<p>' . __( 'Directory [lite] is designed to work out of the box with as little effort on your part as possible. Use this page to define any custom configuration options you would like for your web site.', ldl::$slug ) . '</p>'
    ) );

    $screen->add_help_tab( array(
        'id'	    => 'lddlite-settings-email',
        'title'	    => __( 'Email Settings', ldl::$slug ),
        'content'	=>
            '<p>' . __( 'The email settings allow you to customize the notifications sent from your directory installation.', ldl::$slug ) . '</p>' .
            '<p>' . __( 'Variables available in all email templates include;', ldl::$slug ) . '</p>' .
            '<p><ul>' .
            '<li>' . __( '<em>{title}</em> The title of the listing as the owner defined it.', ldl::$slug ) . '</li>' .
            '<li>' . __( '<em>{description}</em> The listing description.', ldl::$slug ) . '</li>' .
            '</ul></p>' .
            '<p>' . __( '<strong>Administrator Notification</strong> - This is the email sent to you, as the site owner, when someone submits a new listing for review. Additional variables include;', ldl::$slug ) . '</p>' .
            '<p><ul>' .
            '<li>' . __( '<em>{link}</em> Direct link to the administrator page where you can review and approve or reject the listing.', ldl::$slug ) . '</li>' .
            '</ul></p>' .
            '<p>' . __( '<strong>Listing Submission</strong> - This email is sent to the listing owner when they submit a new listing, prior to it being approved by the site administrator.', ldl::$slug ) . '</p>' .
            '<p>' . __( '<strong>Listing Approved</strong> - This email is sent to the listing owner after their new listing has been approved and is now availble publicly on the directory. Additional variables include;', ldl::$slug ) . '</p>' .
            '<p><ul>' .
            '<li>' . __( '<em>{link}</em> Direct link to their new listing as it appears on your directory.', ldl::$slug ) . '</li>' .
            '</ul></p>'
    ) );

}

add_action( 'load-' . LDDLITE_POST_TYPE . '_page_lddlite-settings', 'ld_admin__help' );
