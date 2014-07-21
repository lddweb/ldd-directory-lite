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

function ldl_shortcode_directory_user() {
    ldl_enqueue(1);

    if (!is_user_logged_in()) {
        ldl_get_template_part('login');
        return;
    }

    ldl_get_template_part('user');
}
add_shortcode('directory_user', 'ldl_shortcode_directory_user');