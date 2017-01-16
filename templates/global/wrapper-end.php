<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/lddlite_templates/global/wrapper-end.php.
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
		echo '</div>';
		get_sidebar();
		echo '</div>';
		break;
	case 'twentytwelve' :
		echo '</div></div>';
		break;
	case 'twentythirteen' :
		echo '</div></div>';
		break;
	case 'twentyfourteen' :
		echo '</div></div></div>';
		get_sidebar( 'content' );
		break;
	case 'twentyfifteen' :
		echo '</div></div>';
		break;
	case 'twentysixteen' :
		echo '</main></div>';
		break;
	case 'twentyseventeen' :
		echo '</main></div>';
		get_sidebar();
		echo '</div>';
		break;
	default :
		echo '</section></div>';
		break;
}
