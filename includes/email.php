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
 * Simple wrapper for wp_mail that ensures any problems aren't dumped to the screen. Later on
 * this will allow us to pull {$from} and {$subject} lines from our options page.
 * @since 1.3.13
 *
 * @param string $to Email address this message is going to
 * @param string $subject Email subject
 * @param string $message Email contents
 * @param string $headers Optional, default is managed internally.
 * @return void
 */
function dirl_mail($to, $subject, $message, $headers = "")
{

    // If we're not passing any headers, default to our internal from address
    if (empty($headers))
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: LDD Business Directory <' . get_option('admin_email') . '>' . "\r\n";
    }

    ob_start();
    wp_mail($to, $subject, $message, $headers);
    ob_end_clean();

}


/**
 * This function looks for any macros wrapped in a double set of curly braces and defined in
 * an associative array passed to it. All macros are replaced or removed and the template is
 * returned.
 * @since 1.3.13
 *
 * @param string $tpl_file Relative file path to the template we're parsing
 * @param array $replace Our find and replace array
 * @return bool|string
 */
function dirl_parse( $tpl_file, $replace )
{


    // Create an absolute path to our template file
    $template = DIRL_TEMPLATES . '/' . $tpl_file . '.' . DIRL_TPL_EXT;

    // If the template doesn't exist, return false
    if (!file_exists($template))
        return false;

    // Let's get to work!
    $body = file_get_contents($template);

    if (is_array($replace))
    {
        foreach ($replace as $macro => $value)
        {
            $body = str_replace('{{'.$macro.'}}', $value, $body);
        }
    }

    // Remove all occurrences of macros that have not been replaced
    $body = preg_replace('/\{{2}.*?\}{2}/', "", $body);

    return $body; // Sounds creepy.

}