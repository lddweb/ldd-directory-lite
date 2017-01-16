<?php
/**
 * Displayed when no listings are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/lddlite_templates/loop/no-products-found.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		LDD Web Design <info@lddwebdesign.com>
 * @package 	ldd_directory_lite
 * @version     1.1.0
 * @license     GPL-2.0+
 * @link        http://lddwebdesign.com
 * @copyright   2017 LDD Consulting, Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<p class="directory-lite-info"><?php _e( 'No listings were found matching your selection.', 'ldd-directory-lite' ); ?></p>
