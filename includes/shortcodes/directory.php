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
function ldl_shortcode_directory($atts) {

	$atts = shortcode_atts( array(
				'cat_order_by' 	=> '',
				'cat_order'    	=> '',
				'fl_order_by' 	=> '',
				'fl_order'    	=> '',
				'list_order_by' => '',
				'list_order'    => ''							
			), $atts );
	
    ldl_enqueue(1);
    $home_template_path = ldl_get_template_part('home',null,false);
	include($home_template_path);
	
}
add_shortcode('directory', 'ldl_shortcode_directory');