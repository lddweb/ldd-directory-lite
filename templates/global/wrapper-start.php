<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/lddlite_templates/global/wrapper-start.php.
 *
 * HOWEVER, on occasion LDD Directory Lite will need to update template files and you
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

$template = get_option( 'template' );

switch( $template ) { 
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
		break;
	case 'twentyfifteen' :
		echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
		break;
	case 'twentysixteen' :
		echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
		break;
	case 'twentyseventeen' :
		echo '<div class="wrap"><div id="primary" class="content-area twentyseventeen"><main id="main" class="site-main" role="main">';
		break;
	default :
		echo '<section id="primary" class="page-content directory-lite"><div id="content" role="main">';
		break;
}
