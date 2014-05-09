<?php
/**
 * LDD Business Directory Email Functions
 *
 * This file is the home for all email related functions necessary
 * to the operation of the Business Directory. Where possible email
 * tasks are delegated to WP Core, otherwise updated methods are included
 * here.
 *
 * @package lddbusiness
 */


/**
 * Alias for wp_mail that sets headers for us.
 *
 * @since 1.3.13
 * @param string $to Email address this message is going to
 * @param string $subject Email subject
 * @param string $message Email contents
 * @param string $headers Optional, default is managed internally.
 */
function ld_mail($to, $subject, $message, $headers = '' ) {

    // If we're not passing any headers, default to our internal from address
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: LDD Business Directory <' . get_option('admin_email') . '>' . "\r\n";
    }

    ob_start();
    wp_mail($to, $subject, $message, $headers);
    ob_end_clean();

}


