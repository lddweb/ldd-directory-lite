<?php
/**
 * Filters related to our custom post type.
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
 * Intercept the template_include string and replace it with our own built in templates of the same (or similar) name.
 *
 * @since 0.7
 *
 * @param $template The original located template
 *
 * @return string The new template location if found, original if not
 */
function ldl_template_include($template) {
    global $wp_query;

    if (LDDLITE_POST_TYPE == get_post_type() or (isset($_GET["post_type"]) and $_GET["post_type"] == LDDLITE_POST_TYPE)) {
        $templates = array();

        if (is_single()) {
            $templates[] = 'single.php';
        } else if (is_search()) {
            $templates[] = 'search.php';
        }
		else if (is_tax( 'listing_tag' ) ) {
            $templates[] = 'tag.php';
        }
		
		 else if (is_archive() && !is_tax( 'tag' ) ) {
            $templates[] = 'category.php';
        }
		

        $located = ldl_locate_template($templates, false, false);

        if ($located)
            return $located;

    }

    return $template;
}
add_filter('template_include', 'ldl_template_include');


/**
 * This outputs custom CSS to the <head> if and only if there are changes to the defaults.
 */
function ldl_customize_appearance() {

    $css = '';

    $primary_defaults = array(
        'normal'     => '#3bafda',
        'hover'      => '#3071a9',
        'foreground' => '#ffffff',
    );

    $primary_custom = array(
        'normal'     => ldl()->get_option('appearance_primary_normal', '#3bafda'),
        'hover'      => ldl()->get_option('appearance_primary_hover', '#3071a9'),
        'foreground' => ldl()->get_option('appearance_primary_foreground', '#ffffff'),
    );

    if (array_diff($primary_defaults, $primary_custom)) {
        $css .= <<<CSS
    .btn-primary {
        background: {$primary_custom['normal']};
        background-color: {$primary_custom['normal']};
        border-color: {$primary_custom['normal']};
        color: {$primary_custom['foreground']};
    }
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.btn-primary.dropdown-toggle {
        background: {$primary_custom['hover']};
        background-color: {$primary_custom['hover']};
        border-color: {$primary_custom['hover']};
        color: {$primary_custom['foreground']};
    }
    .label-primary {
        background-color: {$primary_custom['normal']};
    }
CSS;
    }

    if ($css) {
        echo '<style media="all">' . $css . '</style>';
    }

}
add_action('wp_head', 'ldl_customize_appearance', 20);


/**
 * Add some of our own post classes when viewing a single listing.
 *
 * @param $classes An array of post classes
 *
 * @return array Modified array
 */
function ldl_filter_post_class($classes) {

    if (is_single() && LDDLITE_POST_TYPE == get_post_type() && in_array('directory_listings', $classes)) {
        $classes[] = 'listing-single';
    }

    return $classes;
}
add_filter('post_class', 'ldl_filter_post_class');


function ldl_count_pending() {
    global $menu;

    $label = __('Directory', 'ldd-directory-lite');
    $listing_count = wp_count_posts(LDDLITE_POST_TYPE, 'readable');

    if (!empty($menu) && is_array($menu)) {
        foreach ($menu as $key => $menu_item) {
            if (0 === strpos($menu_item[0], $label)) {
                $menu[$key][0] .= ' <span class="awaiting-mod update-plugins count-' . $listing_count->pending . '"><span class="pending-count">' . number_format_i18n($listing_count->pending) . '</span></span>';
                break;
            }
        }
    }
}
add_filter('admin_head', 'ldl_count_pending');