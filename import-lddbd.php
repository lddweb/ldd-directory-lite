<?php
/**
 * The ldd_directory_lite_import_from_plugin class is for the purpose of upgrading from LDD Business Directory
 * to the new LDD Directory Lite plugin. The general outline of this class came from studying WP_Import to learn how
 * it handled itself when encountering large amounts of data... my original attempt was running out of memory far
 * too easily. Lots'o'fun this one was.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

if (!defined('WPINC'))
    die;

global $wpdb;

define('UPFROM_MAIN_TABLE', $wpdb->prefix . 'lddbusinessdirectory');
define('UPFROM_DOC_TABLE', $wpdb->prefix . 'lddbusinessdirectory_docs');
define('UPFROM_CAT_TABLE', $wpdb->prefix . 'lddbusinessdirectory_cats');


/**
 * The class responsible for setting up and running an upgrade. Imports all data and regenerates it as
 * custom post types and taxonomies. No data should be removed by this process, there will be a clean up tool
 * introduced at a later time.
 *
 * @since 0.5.4
 */
class ldd_directory_lite_import_from_plugin {

    // The data collected for the import
    public $posts = array();
    public $authors = array();
    public $terms = array();

    // ID maps for use between methods
    public $term_map = array();
    public $author_map = array();
    public $post_map = array();
    public $document_map = array();


    /**
     * Main controller class, when our object is created this directs the procession.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_head', array($this, 'hide_page'));
    }


    /**
     * Register the page that we'll use to display the whole process
     */
    public function add_page() {
        add_dashboard_page(__('Running Import for LDD Business Directory', 'ldd-directory-lite'), __('LDD Import', 'ldd-directory-lite'), 'manage_options', 'lddlite-import', array(
            $this,
            'import'
        ));
    }

    /**
     * Don't show the registered page on the menu
     */
    public function hide_page() {
        remove_submenu_page('index.php', 'lddlite-import');
    }


    /**
     * Handles the sequence of events for upgrading/importing from the LDD Business Directory plugin
     */
    public function import() {

        ?>
        <div class="wrap">
            <h2><?php _e('Importing content from LDD Business Directory', 'ldd-directory-lite'); ?></h2>
            <?php

            set_time_limit(0);

            $this->start();

            $this->import_terms();
            $this->import_authors();
            $this->import_posts();
            $this->import_meta();
            $this->import_logo();
            $this->import_files();

            $this->end();

            ?>
        </div>
    <?php
    }


    /**
     * Set up our environment for the upgrade, and collect all information we'll need to get going.
     */
    public function start() {
        global $wpdb;

        wp_defer_term_counting(true);

        echo '<p>' . __('Collecting data from original plugin...', 'ldd-directory-lite');
        $this->_get_terms();
        $this->_get_posts();
        echo ' ' . __('done.', 'ldd-directory-lite') . '<p>';

        do_action('ldl_import_start');
    }

    /**
     * Pull all the categories out and prep them for import
     */
    private function _get_terms() {
        global $wpdb;

        $query = sprintf("
                SELECT id, name
                FROM `%s`
            ", UPFROM_CAT_TABLE);
        $results = $wpdb->get_results($query);

        if (!empty($results)) {
            foreach ($results as $cat) {
                $this->terms[ $cat->id ] = $cat->name;
            }
        }

    }

    /**
     * Collect our listings and populate the authors array at the same time to save queries.
     */
    private function _get_posts() {
        global $wpdb;

        $query = sprintf("
                SELECT createDate, name, description, categories, address_street, address_city, address_state, address_zip,
                       address_country, phone, fax, email, contact, url, facebook, twitter, linkedin, promoDescription,
                       logo, login, password, approved, other_info
                FROM `%s`
            ", UPFROM_MAIN_TABLE);
        $results = $wpdb->get_results($query);

        foreach ($results as $row) {
            $hash = hash('md5', $row->createDate . $row->name);
            $this->authors[ $hash ] = array(
                'login' => $row->login,
                'email' => $row->email,
            );
        }

        if (!empty($results))
            $this->posts = $results;

    }

    /**
     * Create all custom category terms from the old business directory table.
     * This skips any terms that already exist, but creates a complete map for use elsewhere.
     */
    public function import_terms() {

        $this->terms = apply_filters('ldl_import_terms', $this->terms);

        // Nothing to do
        if (empty($this->terms))
            return;

        foreach ($this->terms as $old_id => $term) {

            $term_id = term_exists($term, LDDLITE_TAX_CAT);
            if (!$term_id) {
                $term_id = wp_insert_term($term, LDDLITE_TAX_CAT);
                // Discard errors, and don't add this to the map
                if (is_wp_error($term_id)) {
                    printf(__('Failed to import category %s', 'ldd-directory-lite'), esc_html($term));
                    echo ': ' . $term_id->get_error_message() . '<br>';
                }
            }

            $this->term_map[ $old_id ] = is_array($term_id) ? $term_id['term_id'] : $term_id;

        }

        printf('<p>' . __('Added %d listing categories.', 'ldd-directory-lite') . '</p>', count($this->term_map));
        unset($this->terms);

    }

    /**
     * Create all the author accounts using built in WordPress logins.
     * Data for this process can't be trusted whatsoever, and passwords aren't reused due to the insecure
     * way they had been previously stored.
     */
    public function import_authors() {

        $this->authors = apply_filters('ldl_import_authors', $this->authors);

        if (empty($this->authors))
            return;

        // Authors are attributed to their posts by the hash, this needs to be recycled in the map
        foreach ($this->authors as $hash => $row) {

            // If their original username was an email, use the local part as their new username
            $has_at = strpos($row['login'], '@');
            if (false === $has_at) {
                $author_login = sanitize_user($row['login'], 1);
            } else if (false !== $has_at && 0 === strpos(strtolower($row['login']), 'admin')) {
                // it's not entirely uncommon for an email to begin with "admin", use the domain part instead
                $author_login = sanitize_user(substr($row['login'], (strpos($row['login'], '@') + 1)), 1);
            } else {
                $author_login = sanitize_user(substr($row['login'], 0, strpos($row['login'], '@')), 1);
            }

            $author_email = (empty($row['email']) && false !== $has_at) ? $row['login'] : $row['email'];

            $author_id = username_exists($author_login);
            if (!$author_id) {
                $author_id = email_exists($author_email);
                if (!$author_id) {
                    // Force users to reset accounts through "lost password"
                    $author_id = wp_create_user($author_login, wp_generate_password(), $author_email);
                    if (is_wp_error($author_id)) {
                        printf(__('Failed to import owner %s', 'ldd-directory-lite'), esc_html($author_login));
                        echo ': ' . $author_id->get_error_message() . '<br>';
                    }
                }
            }

            $this->author_map[ $hash ] = (!$author_id || 1 == $author_id) ? (int) get_current_user_id() : $author_id;

        }

        printf('<p>' . __('Added %d listing owners.', 'ldd-directory-lite') . '</p>', count($this->author_map));
        unset($this->authors);

    }

    /**
     * Create posts from the imported listing data.
     * Checking for the posts existence should hopefully negate the need to put the site into maintenance mode,
     * which seems to be too disruptive.
     */
    public function import_posts() {
        $this->posts = apply_filters('ldl_import_posts', $this->posts);

        if (!function_exists('post_exists'))
            require_once(ABSPATH . 'wp-admin/includes/post.php');

        // Get this outside the loop in case we need it
        $current_user_id = get_current_user_id();

        foreach ($this->posts as $post) {

            $post_status = ('true' == $post->approved) ? 'publish' : 'pending';
            $hash = md5($post->createDate . $post->name);

            $new = array(
                'post_content' => $post->description,
                'post_title'   => $post->name,
                'post_status'  => $post_status,
                'post_type'    => LDDLITE_POST_TYPE,
                'post_date'    => $post->createDate,
            );

            // Don't generate duplicates, please!
            $post_id = post_exists($new['post_title'], '', $new['post_date']);

            if ($post_id) {
                printf('<p><strong>' . __('Listing already exists', 'ldd-directory-lite') . ':</strong> %s</p>', esc_html($new['post_title']));
            } else {

                $author_id = $this->author_map[ $hash ];
                $term_ids = array();

                // Failsafe
                if (!get_user_by('id', $author_id))
                    $author_id = $current_user_id;

                $new['post_author'] = $author_id;

                $post_id = wp_insert_post($new);

                if (is_wp_error($post_id)) {
                    printf(__('Failed to add listing %s', 'ldd-directory-lite'), esc_html($new['post_title']));
                    echo ': ' . $post_id->get_error_message() . '<br>';
                } else {

                    // Get list of associated terms and assign them
                    if (!empty($post->categories)) {
                        $terms = explode(',', str_replace('x', '', $post->categories));

                        foreach ($terms as $old_id) {
                            if (empty($old_id))
                                continue;
                            // We're not checking if the term exists here and trusting that it will only be mapped
                            // if it was successfully created.
                            if (array_key_exists($old_id, $this->term_map))
                                $term_ids[] = $this->term_map[ $old_id ];
                        }

                        wp_set_post_terms($post_id, $term_ids, LDDLITE_TAX_CAT);

                    }

                }

                // Only map newly created posts, so existing posts are skipped during $this->import_meta()
                // This should stay here...
                $this->post_map[ $hash ] = $post_id;
                printf(__('Added listing', 'ldd-directory-lite') . ': <em>%s</em><br>', esc_html($new['post_title']));

            }
            // ...and not be moved here.

        }

        printf('<p>' . __('Added a total of %d new listings.', 'ldd-directory-lite') . '</p>', count($this->post_map));

    }

    /**
     * Using $this->post_map the next stage is to add all the post meta to each listing. Additionally handles
     * renaming the logo file and adding it as the post thumbnail.
     */
    public function import_meta() {

        echo '<p>' . __('Adding listing meta information...', 'ldd-directory-lite');

        foreach ($this->posts as $post) {

            // Do we need to generate meta for this post?
            $hash = md5($post->createDate . $post->name);
            if (!array_key_exists($hash, $this->post_map))
                continue;

            $post_id = $this->post_map[ $hash ];

            $post_meta = array(
                'country'     => $post->address_country,
                'post_code'   => $post->address_zip,
                'address_one' => $post->address_street,
                'address_two' => $post->address_city . (empty($post->address_state) ? '' : ' ' . $post->address_state),
                'geo'         => array(
                    'lat'       => '',
                    'lng'       => '',
                ),
            );

            if (!empty($post->email))
                $post_meta['contact_email'] = $post->email;
            if (!empty($post->phone))
                $post_meta['contact_phone'] = $post->phone;
            if (!empty($post->promoDescription))
                $post_meta['promotion'] = $post->promoDescription;
            if (!empty($post->other_info))
                $post_meta['other'] = $post->other_info;

            if (!empty($post->url))
                $post_meta['url_website'] = esc_url_raw($post->url);
            if (!empty($post->facebook))
                $post_meta['url_facebook'] = ldl_force_scheme($post->facebook);
            if (!empty($post->linkedin))
                $post_meta['url_linkedin'] = ldl_force_scheme($post->linkedin);
            if (!empty($post->twitter))
                $post_meta['url_twitter'] = ldl_sanitize_twitter($post->twitter);

            foreach ($post_meta as $key => $value) {
                add_post_meta($post_id, ldl_pfx($key), $value);
            }

        }

        echo ' ' . __('done.', 'ldd-directory-lite') . '<p>';

    }

    /**
     * Using $this->post_map the next stage is to add all the post meta to each listing. Additionally handles
     * renaming the logo file and adding it as the post thumbnail.
     */
    public function import_logo() {

        echo '<p>' . __("Importing logo's...", 'ldd-directory-lite');

        if (!function_exists('wp_generate_attachment_metadata'))
            require_once(ABSPATH . 'wp-admin/includes/image.php');

        $wp_upload_dir = wp_upload_dir();

        $creds = request_filesystem_credentials('');
        if (WP_Filesystem($creds)) {
            global $wp_filesystem;
        } else {
            return;
        }

        foreach ($this->posts as $post) {

            // Do we need to generate meta for this post?
            $hash = md5($post->createDate . $post->name);
            if (!array_key_exists($hash, $this->post_map))
                continue;

            if ($post->logo && file_exists($wp_upload_dir['basedir'] . '/' . $post->logo)) {

                $post_id = $this->post_map[ $hash ];

                $old = $wp_upload_dir['basedir'] . '/' . $post->logo;
                $new = $wp_upload_dir['path'] . '/' . basename($post->logo);

                // Don't delete, in case users want to roll back
                // @todo When there's a stable release, add a utility to offer clean-up
                if ($wp_filesystem->copy($old, $new)) {

                    $filetype = wp_check_filetype($new);
                    $attachment = array(
                        'guid'           => $wp_upload_dir['url'] . '/' . basename($new),
                        'post_mime_type' => $filetype['type'],
                        'post_title'     => sanitize_title(substr(basename($new), 0, -4)),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

                    $attached = wp_insert_attachment($attachment, $new, $post_id);

                    if ($attached) {
                        $attach_data = wp_generate_attachment_metadata($attached, $new);
                        wp_update_attachment_metadata($attached, $attach_data);

                        set_post_thumbnail($post_id, $attached);
                    }

                }

            }

        }

        echo ' ' . __('done.', 'ldd-directory-lite') . '<p>';

        unset($this->posts);
    }

    /**
     * Bring any attached files over and make sure they're associated with the proper post.
     * It seems the original plugin borked a few uploads and deleted file extensions. I can't justify the extra
     * cycles to try and save these files when this process is already pretty CPU intensive to begin with. Sorry!
     */
    public function import_files() {

        echo '<p>' . __('Updating file attachments...', 'ldd-directory-lite');

        $wp_upload_dir = wp_upload_dir();
        $uploads_base = $wp_upload_dir['basedir'] . '/directory-lite';

        if (!function_exists('request_filesystem_credentials'))
            require_once(ABSPATH . 'wp-admin/includes/file.php');

        $creds = request_filesystem_credentials('');
        if (WP_Filesystem($creds)) {
            global $wp_filesystem;
        } else {
            return; // No use going further if we don't have a working base directory
        }

        if (!file_exists($uploads_base)) {
            if (!$wp_filesystem->mkdir($uploads_base)) {
                return;
            }
        }

        foreach ($this->document_map as $hash => $doc) {

            if (!array_key_exists($hash, $this->post_map))
                continue;

            $old = $wp_upload_dir['basedir'] . '/' . $doc['path'];

            if (!file_exists($old))
                continue;


            $filetype = wp_check_filetype($old);

            // It's not worth the cycles to try and save files that were corrupted by the Business Directory upload
            if (!$filetype['type'])
                continue;


            $new = $uploads_base . '/' . sanitize_file_name(basename($doc['path']));

            // Say it with me, copy, don't move.
            if (!$wp_filesystem->copy($old, $new)) {

                $post_id = $this->post_map[ $hash ];

                $attachment = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . sanitize_file_name(basename($doc['path'])),
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => empty($doc['title']) ? sanitize_title(basename($doc['path'])) : $doc['title'],
                    'post_content'   => empty($doc['desc']) ? '' : $doc['desc'],
                    'post_status'    => 'inherit'
                );

                $attached = wp_insert_attachment($attachment, $new, $post_id);

                if ($attached) {
                    $attach_data = wp_generate_attachment_metadata($attached, $new);
                    wp_update_attachment_metadata($attached, $attach_data);
                }

            }

        }

        echo ' ' . __('done.', 'ldd-directory-lite') . '<p>';

        unset($this->document_map);

    }

    /**
     * Run clean up after the import has completed.
     */
    public function end() {

        wp_cache_flush();
        wp_defer_term_counting(false);

        // Attempt to disable the old plugin
        if (!function_exists('deactivate_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        deactivate_plugins('ldd-business-directory/lddbd_core.php', true);

        do_action('ldl_import_end');

        echo '<p><strong>' . __('All done!', 'ldd-directory-lite') . '</strong><br>' . __('Passwords have been reset for security purposes, please notify your users!', 'ldd-directory-lite') . '</p>';
        echo '<p><a href="' . admin_url() . '">' . __('Return to WordPress Dashboard', 'ldd-directory-lite') . '</a>' . ' | ';
        echo '<a href="' . admin_url('edit.php?post_type=' . LDDLITE_POST_TYPE . '&page=lddlite-settings') . '">' . __('Directory Settings', 'ldd-directory-lite') . '</a></p>';

        update_option('lddlite_imported_from_original', true);

    }

    /**
     * Documents are not currently in use with the Lite plugin, but will be soon. Don't leave them behind.
     */
    private function _get_files_list() {
        global $wpdb;

        // To hash by way of query, or via hash()? Does it matter? Obviously enough to write a comment about it...
        $query = sprintf("
                SELECT docs.doc_path, docs.doc_name, docs.doc_description, md5( CONCAT( post.createDate, post.name ) ) AS hash
                FROM `%s` AS docs
                  LEFT JOIN `%s` AS post
                  ON docs.bus_id = post.id
          ", UPFROM_DOC_TABLE, UPFROM_MAIN_TABLE);
        $results = $wpdb->get_results($query);

        foreach ($results as $row) {
            $this->document_map[ $row->hash ] = array(
                'path'  => $row->doc_path,
                'title' => $row->doc_name,
                'desc'  => $row->description,
            );
        }

    }

}


/**
 * This class is derived wholly or in part or mostly from the ajax-notification github repository. Thanks Tom!
 *
 * @since 0.5.4
 * @link  https://github.com/tommcfarlin/ajax-notification Tom's Ajax-Notification
 */
class ldd_directory_lite_import_from_notice {

    public function __construct() {

        add_action('admin_head', array($this, 'add_scripts'));

        // Don't append this notice on the actual upgrade page
        $curr = isset($_GET['page']) ? $_GET['page'] : '';
        if (false == get_option('lddlite_imported_from_original') && 'lddlite-import' != $curr) {
            add_action('admin_notices', array($this, 'display_notice'));
        }

    }

    public function add_scripts() {
        echo '<script>(function(e){"use strict";e(function(){e("#dismiss-import-notice").length>0&&e("#dismiss-import-notice").click(function(t){t.preventDefault();e.post(ajaxurl,{action:"hide_import_notice",nonce:e.trim(e("#lddlite-import-nonce").text())},function(t){"1"===t?e("#directory-upgrade-notification").fadeOut("slow"):e("#directory-upgrade-notification").removeClass("updated").addClass("error")})})})})(jQuery);</script>';
    }

    public function display_notice() {
        $screen = get_current_screen();

        if (LDDLITE_POST_TYPE != $screen->post_type)
            return;

        $html = '<div id="directory-upgrade-notification" class="updated">';
        $html .= '<p style="font-size:120%;font-weight:700;">' . __('Existing data has been detected!', 'ldd-directory-lite') . '</p>';
        $html .= '<p style="font-weight:700;">' . __('It looks like you have data from the LDD Business Directory plugin! Would you like to import this?', 'ldd-directory-lite');
        $html .= ' &nbsp; <a href="' . admin_url('admin.php?page=lddlite-import') . '" class="button">' . __('Import Data.', 'ldd-directory-lite') . '</a>';
        $html .= '<p>' . __('If you do not wish to import your existing data, you can', 'ldd-directory-lite') . ' <a href="javascript:;" id="dismiss-import-notice">' . __('dismiss', 'ldd-directory-lite') . '</a> ' . __('this notice.', 'ldd-directory-lite') . '</p>';
        $html .= '<span id="lddlite-import-nonce" class="hidden">' . wp_create_nonce('lddlite-import-nonce') . '</span>';
        $html .= '</div>';

        echo $html;
    }

}

new ldd_directory_lite_import_from_plugin;
new ldd_directory_lite_import_from_notice;
