<?php
/**
 * Handles setup of the [directory_category slug='' list_order_by='' list_order='' view="" limit=''] shortcode.
 *
 * Used to display listings based on provided category(s) with different attributes.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * The [directory_category] shortcode.
 */
function ldl_shortcode_cat_directory($atts)
{
    $paged 			= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $posts_per_page = get_option('posts_per_page');

    $atts = shortcode_atts(array(
        'slug'          => '',
        'list_order_by' => 'title',
        'list_order'    => 'ASC',
        'limit'         => $posts_per_page,
        'view'          => ldl()->get_option('directory_view_type', 'compact')
    ), $atts);

    ldl_enqueue(1);

    $args = array(
        'orderby'        => $atts["list_order_by"],
        'order'          => $atts["list_order"],
        'post_type'      => LDDLITE_POST_TYPE,
        'posts_per_page' => $atts["limit"],
        'paged' 		 => $paged
    );

    if(!empty($atts["slug"])) {
        $slug = explode(",",$atts["slug"]);
        $args["tax_query"] = array(
                                array(
                                    'taxonomy' => LDDLITE_TAX_CAT,
                                    'field'    => 'slug',
                                    'terms'    => $slug
                                )
                            );
    }

    $query1 = new WP_Query($args);
    if ( $query1->have_posts() ) {
        if ($atts["view"] == "grid") {
            echo "<div class='grid js-isotope' data-isotope-options='{ \"itemSelector\": \".grid-item\", \"layoutMode\": \"fitRows\" }'>";
        }
        while ($query1->have_posts()) { $query1->the_post();
            ldl_get_template_part('listing', $atts["view"]);
        }
        if ($atts["view"] == "grid") {
            echo "</div>";
            wp_enqueue_script('isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js');
        }
        echo "<div class='clearfix'></div>";
        wp_reset_postdata();
    }
}

add_shortcode('directory_category', 'ldl_shortcode_cat_directory');