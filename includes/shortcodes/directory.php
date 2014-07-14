<?php
/**
 * Handles setup of the [directory] shortcode.
 *
 * Post types are registered in setup.php, all actions and filters in this file are related
 * to customizing the way WordPress handles our custom post types and taxonomies.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * The [directory] shortcode.
 */
function ldl_shortcode_directory() {
    ldl_enqueue(1);

    ldl_get_template_part('home');
}
add_shortcode('directory', 'ldl_shortcode_directory');
