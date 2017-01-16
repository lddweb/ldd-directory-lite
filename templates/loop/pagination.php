<?php
/**
 * Pagination - Show numbered pagination for category pages
 *
 * This template can be overridden by copying it to yourtheme/lddlite_templates/loop/pagination.php.
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

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<nav class="ldd_listing_pagination clearfix">
	<?php
	echo paginate_links( apply_filters( 'ldd_pagination_args', array(
		//'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
		'format'       => '',
		'add_args'     => false,
		'current'      => max( 1, get_query_var( 'paged' ) ),
		'total'        => $wp_query->max_num_pages,
		'prev_text'    => '&larr;',
		'next_text'    => '&rarr;',
		'type'         => 'list',
		'end_size'     => 3,
		'mid_size'     => 3
	) ) );
	?>
</nav>