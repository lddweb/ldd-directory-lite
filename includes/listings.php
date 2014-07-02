<?php
/**
 * Filters related to our custom post type.
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


function ldl_filter__enter_title_here($title) {
    if (get_post_type() == LDDLITE_POST_TYPE)
        $title = __('Listing Name', 'lddlite');

    return $title;
}


function ldl_filter__admin_post_thumbnail_html($content) {

    if (LDDLITE_POST_TYPE == get_post_type()) {
        $content = str_replace(__('Set featured image'), __('Upload A Logo', 'lddlite'), $content);
        $content = str_replace(__('Remove featured image'), __('Remove Logo', 'lddlite'), $content);
    }

    return $content;
}


function ldl_action__admin_menu_icon() {
    echo "\n\t<style>";
    echo '#adminmenu .menu-icon-' . LDDLITE_POST_TYPE . ' div.wp-menu-image:before { content: \'\\f307\'; }';
    echo '</style>';
}


function ldl_action__submenu_title() {
    global $submenu;
    $submenu['edit.php?post_type=' . LDDLITE_POST_TYPE][5][0] = 'All Listings';
}


function ldl_action__send_approved_email($post) {

    if (LDDLITE_POST_TYPE != get_post_type() || 1 == get_post_meta($post->ID, '_approved', true))
        return;

    $user = get_userdata($post->post_author);

    $user_nicename = $user->data->display_name;
    $user_email = $user->data->user_email;

    $post_slug = $post->post_name;
    $permalink = add_query_arg(array('show' => 'listing', 't' => $post_slug), ldl_get_setting('directory_page'));

    $subject = ldl_get_setting('email_onapprove_subject');
    $message = ldl_get_setting('email_onapprove_body');

    $message = str_replace('{site_title}', get_bloginfo('name'), $message);
    $message = str_replace('{directory_title}', ldl_get_setting('directory_label'), $message);
    $message = str_replace('{link}', $permalink, $message);

    ldl_mail($user_email, $subject, $message);
    update_post_meta($post->ID, '_approved', 1);

}

function ldl_setup__customize_appearance() {

    $panel_background = ldl_get_setting('appearance_panel_background');
    $panel_foreground = ldl_get_setting('appearance_panel_foreground');

    $css = <<<CSS
<style media="all">
    .panel-primary > .panel-heading {
        background-color: {$panel_background};
        border-color: {$panel_background};
        color: {$panel_foreground};
    }
</style>
CSS;

    echo $css;
}


add_action('wp_footer', 'ldl_customize_appearance', 20);

function ldl_template_include($template) {

    if (LDDLITE_POST_TYPE == get_post_type()) {

        $templates = array();

        if (is_single()) {
            $templates[] = 'single.php';
        } else if (is_archive()) {
            $templates[] = 'category.php';
        }

        $located = ldl_locate_template($templates, false, false);

        if ($located)
            return $located;

    }

    return $template;
}

add_filter('template_include', 'ldl_template_include');

add_filter('enter_title_here', 'ldl_filter__enter_title_here');
add_filter('admin_post_thumbnail_html', 'ldl_filter__admin_post_thumbnail_html');

add_action('admin_head', 'ldl_action__admin_menu_icon');
add_action('_admin_menu', 'ldl_action__submenu_title');

add_action('pending_to_publish', 'ldl_action__send_approved_email');


function ldl_filter_post_class($classes) {

    if ( is_single() && LDDLITE_POST_TYPE == get_post_type() && in_array( 'directory_listings', $classes ) ) {
        $classes[] = 'listing-single';
    }

    return $classes;
}
add_filter('post_class', 'ldl_filter_post_class');
