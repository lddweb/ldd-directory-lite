<?php
/**
 * Template Hooks
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @version   1.1.0
 * @copyright 2017 LDD Consulting, Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Content Wrappers.
 *
 * @see ldd_output_content_wrapper()
 * @see ldd_output_content_wrapper_end()
 */
add_action( 'ldd_before_main_content', 'ldd_output_content_wrapper', 10 );
add_action( 'ldd_after_main_content', 'ldd_output_content_wrapper_end', 10 );

/**
 * Pagination after directory loops.
 *
 * @see ldd_default_pagination()
 */
add_action( 'ldd_after_directory_loop', 'ldd_default_pagination', 10 );