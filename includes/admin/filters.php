<?php
/**
 * Filters related to our custom post type.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Alter the label for the first sub-menu option underneath the Directory menu.
 */
function ldl_action_submenu_title() {
    global $submenu;
    $submenu['edit.php?post_type=' . LDDLITE_POST_TYPE][5][0] = 'All Listings';
}
add_action('_admin_menu', 'ldl_action_submenu_title');


/**
 * Custom icon for the menu item.
 */
function ldl_action_admin_menu_icon() {
    echo "\n\t<style>";
    echo '#adminmenu .menu-icon-' . LDDLITE_POST_TYPE . ' div.wp-menu-image:before { content: \'\\f307\'; }';
    echo '</style>';
}
add_action('admin_head', 'ldl_action_admin_menu_icon');


/**
 * Changes "Enter title here" to "Listing Name".
 *
 * @todo This doesn't seem that important anymore, is it just overhead?
 *
 * @param $title The title string
 *
 * @return string Altered title string
 */
function ldl_filter_enter_title_here($title) {
    if (get_post_type() == LDDLITE_POST_TYPE)
        $title = __('Listing Name', 'ldd-directory-lite');

    return $title;
}
add_filter('enter_title_here', 'ldl_filter_enter_title_here');


/**
 * Changes the "featured image" terminology to "logo"
 *
 * @param $content HTML content
 *
 * @return mixed Modified HTML content
 */
function ldl_filter_admin_post_thumbnail_html($content) {

    if (LDDLITE_POST_TYPE == get_post_type()) {
        $content = str_replace(__('Set featured image', 'ldd-directory-lite'), __('Upload A Logo', 'ldd-directory-lite'), $content);
        $content = str_replace(__('Remove featured image', 'ldd-directory-lite'), __('Remove Logo', 'ldd-directory-lite'), $content);
    }

    return $content;
}
add_filter('admin_post_thumbnail_html', 'ldl_filter_admin_post_thumbnail_html');
