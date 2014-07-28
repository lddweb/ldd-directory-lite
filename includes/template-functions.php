<?php
/**
 * Template Functions
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * This defines where the plugin should look for custom templates under a parent or child theme directory.
 *
 * @since 0.6.0
 * @return string
 */
function ldl_get_template_dir_name() {
    return trailingslashit(apply_filters('lddlite_template_dir_name', 'lddlite_templates'));
}


/**
 * This is a modified version of the core locate_template() function, providing for multiple paths to search
 * in before returning a template for use in presentation.
 *
 * @todo  How are we going to let developers know when there's major updates to a core template?
 * @since 0.6.0
 *
 * @param array $templates    The array of templates to look for
 * @param bool  $load         Whether to return the path, or to load the template
 * @param bool  $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 *
 * @return string The template filename if one is located.
 */
function ldl_locate_template($templates, $load = false, $require_once = true) {

    // No template found yet
    $located = false;

    $custom_path = ldl_get_template_dir_name();

    // Build an array of locations to search
    $template_paths = array(
        trailingslashit(get_stylesheet_directory()) . $custom_path,
        trailingslashit(get_template_directory()) . $custom_path,
        trailingslashit(LDDLITE_PATH . '/templates'), // Default
    );

    foreach ((array) $templates as $template) {

        // Continue if template is empty
        if (empty($template))
            continue;

        // Trim off any slashes from the template name
        $template = ltrim($template, '/');

        // try locating this template file by looping through the template paths
        foreach ($template_paths as $path) {

            if (file_exists(trailingslashit($path) . $template)) {
                $located = trailingslashit($path) . $template;
                break;
            }

        }

        if ($located) {
            break;
        }
    }

    if (true == $load && false != $located)
        load_template($located, $require_once);

    return $located;
}


/**
 * This duplicates get_template_part() verbatim, with the single exception of using our ldl_locate_template()
 * instead of the core locate_template()
 *
 * @since 0.6.0
 * @uses  ldl_locate_template()
 *
 * @param string $slug The parent template we're looking for
 * @param string $name The specific type of a particular parent template, if any
 */
function ldl_get_template_part($slug, $name = null) {

    do_action('get_template_part_' . $slug, $slug, $name);

    $templates = array();
    $name = (string) $name;
    if ('' !== $name)
        $templates[] = "{$slug}-{$name}.php";

    $templates[] = "{$slug}.php";

    // Using the array, locate a template and return it
    return ldl_locate_template($templates, true, false);
}


/** URL HELPERS */

/**
 * Get the link to the submit form
 *
 * @since 0.6.0
 */
function ldl_get_submit_link() {
    $post_id = ldl_get_setting('directory_submit_page');

    return ($post_id) ? get_permalink($post_id) : '';
}


/**
 * Get the link to the management page
 *
 * @since 0.7.2
 * @TODO Code repetitititition, single function in the future? url helper class?
 */
function ldl_get_manage_link() {
    $post_id = ldl_get_setting('directory_manage_page');

    return ($post_id) ? get_permalink($post_id) : '';
}


/**
 * Similar to WordPress core home_url(), this uses the directory_page setting to return a permalink
 * to the directory home page.
 *
 * @param string $path   Optional path relative to the home url.
 * @param string $scheme Optional scheme to use
 *
 * @return string Full permalink to the home page of our directory
 */
function ldl_get_home_url($path = '', $scheme = null) {

    $url = get_permalink(ldl_get_setting('directory_page'));

    if (!in_array($scheme, array('http', 'https', 'relative')))
        $scheme = is_ssl() ? 'https' : parse_url($url, PHP_URL_SCHEME);

    $url = set_url_scheme($url, $scheme);

    if ($path && is_string($path))
        $url .= '/' . ltrim($path, '/');

    return apply_filters('ldl_home_url', $url, $path);
}


/** CONDITIONALS */

/**
 * Are google maps turned on?
 *
 * @return bool True or false
 */
function ldl_use_google_maps() {

    if (is_single()) {
        global $geo;

        if (!isset($geo) || !is_array($geo) || in_array('', $geo) || !ldl_get_setting('google_maps'))
            return false;

        return true;
    }

    return ldl_get_setting('google_maps');
}


/** TEMPLATE UTILITIES */

/**
 * An alias for returning the header template (the header template has our navbar)
 */
function ldl_get_header() {
    $show_header = apply_filters('lddlite_filter_presentation_header', true);
    if ($show_header)
        ldl_get_template_part('header');
}


/**
 * This will check if we need a contact form, and if so enqueues the scripts and retrieves the appropriate template.
 */
function ldl_get_contact_form() {
    $post_id = get_the_ID();

    if (!$post_id)
        return;

    if (!get_post_meta($post_id, ldl_pfx('contact_email'), 1))
        return;

    echo '<script>var ajaxurl = "' . admin_url('admin-ajax.php') . '"</script>';

    wp_enqueue_script('lddlite-contact');
    ldl_get_template_part('contact', 'sidebar');
}


/**
 * An alias for ldl_get_categories that defaults to the top level categories.
 */
function ldl_get_parent_categories() {
    return ldl_get_categories(0);
}

/**
 * Obtain a list of categories based on the provided parent ID, and return a formatted list for display
 * via one of the plugin templates.
 *
 * @param int $parent The term ID to retrieve categories from
 *
 * @return string Return a formatted string containing all category elements
 */
function ldl_get_categories($parent = 0) {

    $terms = get_terms(LDDLITE_TAX_CAT, array(
        'parent' => $parent,
    ));

    $mask = '<a href="%1$s" class="list-group-item"><span class="label label-primary pull-right">%3$d</span>%2$s</a>';

    $categories = array();
    foreach ($terms as $category) {
        $term_link = get_term_link($category);
        $categories[] = sprintf($mask, $term_link, $category->name, $category->count);
    }

    $categories = apply_filters('lddlite_filter_presentation_categories', $categories, $terms, $mask);

    return implode(' ', $categories);
}


/**
 * Returns a post thumbnail/logo for the provided ID. If none is found, a default image is returned.
 *
 * @param int $post_id The post ID
 *
 * @return string
 */
function ldl_get_thumbnail($post_id, $size = 'directory-listing', $class = 'img-rounded') {

    if (has_post_thumbnail($post_id)) {
        $thumbnail = get_the_post_thumbnail($post_id, $size, array('class' => $class));
    } else {
        $thumbnail = '<img src="' . LDDLITE_NOLOGO . '" class="' . $class . '">';
    }

    return apply_filters('lddlite_filter_presentation_thumbnail', $thumbnail, $post_id, $size, $class);
}


/**
 * Single helper function for determining if a terms of service section should be displayed on the submit form. If
 * set to true and content has been provided, get the appropriate template part.
 */
function ldl_the_tos() {
    if (!ldl_get_setting('submit_use_tos') || '' == ldl_get_setting('submit_tos')) {
        return;
    }

    ldl_get_template_part('submit', 'tos');
}

/** LISTING META UTILITES */

/**
 * Return a piece of the geo post meta.
 *
 * @param string $key Should be one of 'formatted', 'lat', or 'lng'
 *
 * @return string|false Returns the value for the requested key if found, false otherwise
 */
function ldl_get_address() {

    $post_id = get_the_ID();

    if (!is_int($post_id))
        return false;

    $address_one = get_post_meta($post_id, ldl_pfx('address_one'), true);
    $address_two = get_post_meta($post_id, ldl_pfx('address_two'), true);
    $postal_code = get_post_meta($post_id, ldl_pfx('postal_code'), true);
    $country = get_post_meta($post_id, ldl_pfx('country'), true);

    $output = '';
    $output .= empty($address_one) ? '' : $address_one;
    $output .= empty($address_two) ? '' : ', ' . $address_two;
    $output .= empty($postal_code) ? '' : ', ' . $postal_code;
    $output .= empty($country) ? '' : ', ' . $country;

    $output = apply_filters('lddlite_presentation_get_address', $output, $post_id, compact('address_one', 'address_two', 'postal_code', 'country'));

    return $output ? $output : false;
}


/**
 * Get an array of social media links for the designated post, and return it as a string to be used
 * in various templates.
 *
 * @param int $post_id The post ID
 *
 * @return string
 */
function ldl_get_social($post_id) {

    if (!is_int($post_id))
        return false;

    // Get the links for this listing
    $social = array(
        'facebook' => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_facebook'), 1)),
        'linkedin' => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_linkedin'), 1)),
        'twitter'  => ldl_sanitize_twitter(get_post_meta($post_id, ldl_pfx('url_twitter'), 1)),
    );

    $titles = array(
        'facebook' => 'Visit %1$s on Facebook',
        'linkedin' => 'Connect with %1$s on LinkedIn',
        'twitter'  => 'Follow %1$s on Twitter',
        'default'  => 'Visit %1$s on %2$s',
    );

    $name = get_the_title($post_id);


    // Start building an array of links
    $output = array();

    foreach ($social as $key => $url) {
        if (!empty($url)) {
            $title_key = array_key_exists($key, $titles) ? $titles[ $key ] : $titles['default'];
            $title = sprintf($title_key, $name, $key);

            $output[] = '<a href="' . $url . '" title="' . $title . '"><i class="fa fa-' . $key . '-square"></i></a>';
        }
    }

    /**
     * Allow developers to filter these links before returning them to the template
     *
     * @param array $output  An array of social links
     * @param int   $post_id The post ID
     */
    $output = apply_filters('lddlite_filter_presentation_social', $output, $post_id);

    return implode(' ', $output);
}

/**
 * Get Featured Posts
 */
function ldl_get_featured_posts($args = null) {

    $defaults = array(
        'post_type'      => LDDLITE_POST_TYPE,
        'tax_query'      => array(
            'taxonomy' => LDDLITE_TAX_TAG,
            'field'    => 'slug',
            'terms'    => 'featured',
        ),
        'orderby'        => 'rand',
        'posts_per_page' => '3'
    );

    $args = wp_parse_args($args, $defaults);

    return new WP_Query($args);
}


/**
 * Get listings by user
 */
function ldl_get_listings_by_current_author() {

    $user_ID = get_current_user_id();

    $args = array(
        'post_type' => LDDLITE_POST_TYPE,
        'author'    => $user_ID,
    );

    return new WP_Query($args);
}


/**
 * Return a link for use on the manage listings page which opens up the listing editor
 */
function ldl_edit_link($post_id, $action) {
    echo add_query_arg(
        array(
            'id'  => $post_id,
            'edit' => $action,
        ),
        remove_query_arg('msg')
    );
}