<?php
/**
 * Primary class for the plugin settings administrative interface.
 *
 * This
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


class ldd_directory_lite_admin {

    protected static $_instance = null;


    public static function get_instance() {

        if (null === self::$_instance) {
            self::$_instance = new self;
            self::$_instance->action_filters();
        }

        return self::$_instance;
    }


    public function action_filters() {
        $basename = plugin_basename(__FILE__);
        add_filter('plugin_action_links_' . $basename, array($this, 'add_action_links'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_settings_menu'));

        /*
        if (true !== ldl_get_setting('allow_tracking_popup_done'))
            add_action('admin_enqueue_scripts', array('ldd_directory_lite_pointers', 'get_instance'));
        if (true === ldl_get_setting('allow_tracking'))
            add_action('directory_lite_tracking', array('ldd_directory_lite_tracking', 'get_instance'));
        */

    }


    function enqueue_scripts($hook_suffix) {

        if ('directory_listings_page_lddlite-settings' != $hook_suffix)
            return;

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('lddlite-bootstrap', LDDLITE_URL . '/public/css/bootstrap.css', array(), LDDLITE_VERSION);

        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('lddlite-admin', LDDLITE_URL . '/public/js/admin.js', array('wp-color-picker'), false, true);

    }


    /**
     * Add a 'Settings' link on the Plugins page for easier access.
     *
     * @since 0.5.0
     *
     * @param $links array Passed by the filter
     *
     * @return array The modified $links array
     */
    public function add_action_links($links) {

        return array_merge(array(
                'settings'   => '<a href="' . admin_url('edit.php?post_type=' . LDDLITE_POST_TYPE . '&page=lddlite-settings') . '">' . __('Settings', 'ldd-directory-lite') . '</a>',
                'addlisting' => '<a href="' . admin_url('post-new.php?post_type=' . LDDLITE_POST_TYPE) . '">' . __('Add Listing', 'ldd-directory-lite') . '</a>',
            ), $links);

    }


    public function register_settings() {

        add_settings_section('lddlite_settings_general', __return_null(), '__return_false', 'lddlite_settings_general');

        add_settings_field('lddlite_settings[directory_front_page]', '<label for="lite-directory_front_page">' . __('Front Page', 'ldd-directory-lite') . '</label>', '_f_directory_front_page', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[directory_submit_page]', '<label for="lite-directory_submit_page">' . __('Submit Page', 'ldd-directory-lite') . '</label>', '_f_directory_submit_page', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[directory_manage_page]', '<label for="lite-directory_manage_page">' . __('Management Page', 'ldd-directory-lite') . '</label>', '_f_directory_manage_page', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[directory_taxonomy_slug]', '<label for="lite-directory_taxonomy_slug">' . __('Taxonomy Slug', 'ldd-directory-lite') . '</label>', '_f_directory_taxonomy_slug', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[directory_post_type_slug]', '<label for="lite-directory_post_type_slug">' . __('Post Type Slug', 'ldd-directory-lite') . '</label>', '_f_directory_post_type_slug', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings_information_separator', '<span style="font-size: 18px">' . __('Directory Information', 'ldd-directory-lite') . '</span>', '__return_false', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[directory_label]', '<label for="lite-directory_label">' . __('Directory Label', 'ldd-directory-lite') . '</label>', '_f_directory_label', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[directory_description]', '<label for="lite-directory_description">' . __('Directory Description', 'ldd-directory-lite') . '</label>', '_f_directory_description', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings_other_separator', '<span style="font-size: 18px">' . __('Other Settings', 'ldd-directory-lite') . '</span>', '__return_false', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[allow_tracking]', __('Allow Tracking', 'ldd-directory-lite'), '_f_allow_tracking', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings[google_maps]', __('Use Google Maps', 'ldd-directory-lite'), '_f_google_maps', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('lddlite_settings_debug_separator]', '<span style="font-size: 18px">' . __('Debug Tools', 'ldd-directory-lite') . '</span>', '__return_false', 'lddlite_settings_general', 'lddlite_settings_general');
        add_settings_field('debug_uninstall', '<span>Uninstall Data</span>', '_f_debug_uninstall', 'lddlite_settings_general', 'lddlite_settings_general');

        function _f_directory_front_page() {
            $args = array(
                'name'              => 'lddlite_settings[directory_front_page]',
                'id'                => 'lite-directory_front_page',
                'selected'          => ldl_get_setting('directory_front_page'),
                'show_option_none'  => 'Select a page...',
                'option_none_value' => '',
            );
            wp_dropdown_pages($args);
            echo '<p class="description">' . __('This is the page where the <code>[directory]</code> shortcode has been placed.', 'ldd-directory-lite') . '</p>';
        }

        function _f_directory_submit_page() {
            $args = array(
                'name'              => 'lddlite_settings[directory_submit_page]',
                'id'                => 'lite-directory_submit_page',
                'selected'          => ldl_get_setting('directory_submit_page'),
                'show_option_none'  => 'Select a page...',
                'option_none_value' => '',
            );
            wp_dropdown_pages($args);
            echo '<p class="description">' . __('This is the page where the <code>[directory_submit]</code> shortcode has been placed.', 'ldd-directory-lite') . '</p>';
        }

        function _f_directory_manage_page() {
            $args = array(
                'name'              => 'lddlite_settings[directory_manage_page]',
                'id'                => 'lite-directory_manage_page',
                'selected'          => ldl_get_setting('directory_manage_page'),
                'show_option_none'  => 'Select a page...',
                'option_none_value' => '',
            );
            wp_dropdown_pages($args);
            echo '<p class="description">' . __('This is the page where the <code>[directory_manage]</code> shortcode has been placed.', 'ldd-directory-lite') . '</p>';
        }

        function _f_directory_taxonomy_slug() {
            echo '<input id="lite-directory_taxonomy_slug" type="text" size="20" name="lddlite_settings[directory_taxonomy_slug]" value="' . ldl_get_setting('directory_taxonomy_slug', 1) . '">';
            echo '<p class="description">' . __('This is the first part of the URL for category display pages.', 'ldd-directory-lite') . '</p>';
        }

        function _f_directory_post_type_slug() {
            echo '<input id="lite-directory_taxonomy_slug" type="text" size="20" name="lddlite_settings[directory_post_type_slug]" value="' . ldl_get_setting('directory_post_type_slug', 1) . '">';
            echo '<p class="description">' . __('Same as above, but for the listing pages.', 'ldd-directory-lite') . '</p>';
        }

        function _f_directory_label() {
            echo '<input id="lite-directory_label" type="text" size="80" name="lddlite_settings[directory_label]" value="' . ldl_get_setting('directory_label', 1) . '">';
            echo '<p class="description">' . __('Name your directory; "My Business Directory", "Local Restaurant Feed", "John\'s List of Links", etc.', 'ldd-directory-lite') . '</p>';
        }

        function _f_directory_description() {
            wp_editor(ldl_get_setting('directory_description'), 'lite-directory_description', array(
                    'textarea_name' => 'lddlite_settings[directory_description]',
                    'textarea_rows' => 5
                ));
        }

        function _f_allow_tracking() {
            echo '<label for="lite-allow_tracking"><input id="lite-allow_tracking" type="checkbox" name="lddlite_settings[allow_tracking]" value="1" ' . checked(ldl_get_setting('allow_tracking'), 1, 0) . '> <span>Allow anonymous usage tracking</span></label>';
        }

        function _f_google_maps() {
            echo '<label for="lite-google_maps-yes" title="Enable Google Maps"><input id="lite-google_maps-yes" type="radio" name="lddlite_settings[google_maps]" value="1" ' . checked(ldl_get_setting('google_maps'), 1, 0) . '> <span>Yes</span></label><br />';
            echo '<label for="lite-google_maps-no" title="Disable Google Maps"><input id="lite-google_maps-no" type="radio" name="lddlite_settings[google_maps]" value="0" ' . checked(ldl_get_setting('google_maps'), 0, 0) . '> <span>No</span></label><br />';
            echo '<p class="description">' . __('This toggles the display of Google Maps for listings that have an address set.', 'ldd-directory-lite') . '</p>';
        }

        function _f_debug_uninstall() {
            echo '<label for="lite-debug_uninstall"><input id="lite-debug_uninstall" type="checkbox" name="lddlite_settings[debug_uninstall]" value="1"> <span>Confirm</span></label>';
            echo '<p class="description warning">Only select this option if you know what you are doing! This will remove ALL of your Directory Lite posts and taxonomies.</p>';
        }


        add_settings_section('lddlite_settings_email', __return_null(), '__return_false', 'lddlite_settings_email');

        add_settings_field('lddlite_settings[email_from_name]', '<label for="email_from_name">' . __('From Name', 'ldd-directory-lite') . '</label>', '_f_email_from_name', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_from_address]', '<label for="email_from_address">' . __('From Email Address', 'ldd-directory-lite') . '</label>', '_f_email_from_Address', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_notification_address]', '<label for="email_notification_address">' . __('Notify', 'ldd-directory-lite') . '</label>', '_f_email_notification_address', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings_other_separator', '<span style="font-size: 18px">' . __('Message Contents', 'ldd-directory-lite') . '</span>', '__return_false', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_toadmin_subject]', '<label for="email_toadmin_subject">' . __('New Listing Notification', 'ldd-directory-lite') . '</label>', '_f_email_toadmin_subject', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_toadmin_body]', '<label for="email_toadmin_body" class="screen-reader-text">' . __('Email Body', 'ldd-directory-lite') . '</label>', '_f_email_toadmin_body', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_onsubmit_subject]', '<label for="email_onsubmit_subject">' . __('Author Receipt', 'ldd-directory-lite') . '</label>', '_f_email_onsubmit_subject', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_onsubmit_body]', '<label for="email_onsubmit_body" class="screen-reader-text">' . __('Email Body', 'ldd-directory-lite') . '</label>', '_f_email_onsubmit_body', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_onapprove_subject]', '<label for="email_onapprove_subject">' . __('Listing Approved', 'ldd-directory-lite') . '</label>', '_f_email_onapprove_subject', 'lddlite_settings_email', 'lddlite_settings_email');
        add_settings_field('lddlite_settings[email_onapprove_body]', '<label for="email_onapprove_body" class="screen-reader-text">' . __('Email Body', 'ldd-directory-lite') . '</label>', '_f_email_onapprove_body', 'lddlite_settings_email', 'lddlite_settings_email');

        function _f_email_from_name() {
            echo '<input id="email_from_name" type="text" size="80" name="lddlite_settings[email_from_name]" value="' . ldl_get_setting('email_from_name', 1) . '">';
            echo '<p class="description">' . __('This forms the first part of outgoing messages, ', 'ldd-directory-lite') . sprintf(' From: <strong>%s</strong> &lt;%s&gt;', ldl_get_setting('email_from_name'), ldl_get_setting('email_from_address')) . '</p>';
        }

        function _f_email_from_address() {
            echo '<input id="email_from_address" type="text" size="80" name="lddlite_settings[email_from_address]" value="' . ldl_get_setting('email_from_address', 1) . '">';
            echo '<p class="description">' . __('This forms the second part of outgoing messages, ', 'ldd-directory-lite') . sprintf(' From: %s &lt;<strong>%s</strong>&gt;', ldl_get_setting('email_from_name'), ldl_get_setting('email_from_address')) . '</p>';
        }

        function _f_email_notification_address() {
            echo '<input id="email_notification_address" type="text" size="80" data-role="tagsinput" name="lddlite_settings[email_notification_address]" value="' . ldl_get_setting('email_notification_address', 1) . '">';
            echo '<p class="description">' . __('This is the email where you want notifications sent. Use a comma to separate multipl email addresses.', 'ldd-directory-lite') . '</p>';
        }

        function _f_email_toadmin_subject() {
            echo '<input id="email_toadmin_subject" type="text" size="80" name="lddlite_settings[email_toadmin_subject]" value="' . ldl_get_setting('email_toadmin_subject', 1) . '">';
            echo '<p class="description">' . __('Sent to the email(s) listed above when a listing is awaiting approval.', 'ldd-directory-lite') . '</p>';
        }

        function _f_email_toadmin_body() {
            wp_editor(ldl_get_setting('email_toadmin_body'), 'lite-email_toadmin_body', array(
                    'textarea_name' => 'lddlite_settings[email_toadmin_body]',
                    'textarea_rows' => 5
                ));
        }

        function _f_email_onsubmit_subject() {
            echo '<input id="email_onsubmit_subject" type="text" size="80" name="lddlite_settings[email_onsubmit_subject]" value="' . ldl_get_setting('email_onsubmit_subject', 1) . '">';
            echo '<p class="description">' . __('Sent to the author after they submit a new listing. Use this to remind them of your terms, inform them of average wait times or other important information. ', 'ldd-directory-lite') . '</p>';
        }

        function _f_email_onsubmit_body() {
            wp_editor(ldl_get_setting('email_onsubmit_body'), 'lite-email_onsubmit_body', array(
                    'textarea_name' => 'lddlite_settings[email_onsubmit_body]',
                    'textarea_rows' => 5
                ));
        }

        function _f_email_onapprove_subject() {
            echo '<input id="email_onapprove_subject" type="text" size="80" name="lddlite_settings[email_onapprove_subject]" value="' . ldl_get_setting('email_onapprove_subject', 1) . '">';
            echo '<p class="description">' . __('Sent to the author when their listing has been approved and is available publicly..', 'ldd-directory-lite') . '</p>';
        }

        function _f_email_onapprove_body() {
            wp_editor(ldl_get_setting('email_onapprove_body'), 'ld_email_onapprove_body', array(
                    'textarea_name' => 'lddlite_settings[email_onapprove_body]',
                    'textarea_rows' => 5
                ));
        }


        add_settings_section('lddlite_settings_submit', __return_null(), '__return_false', 'lddlite_settings_submit');

        add_settings_field('lddlite_settings[submit_use_tos]', __('Include Terms', 'ldd-directory-lite'), '_f_submit_use_tos', 'lddlite_settings_submit', 'lddlite_settings_submit');
        add_settings_field('lddlite_settings[submit_tos]', '<label for="submit_tos">' . __('Terms of Service', 'ldd-directory-lite') . '</label>', '_f_submit_tos', 'lddlite_settings_submit', 'lddlite_settings_submit');
        add_settings_field('lddlite_settings[submit_intro]', '<label for="submit_intro">' . __('Submit Introduction', 'ldd-directory-lite') . '</label>', '_f_submit_intro', 'lddlite_settings_submit', 'lddlite_settings_submit');
        add_settings_field('lddlite_settings[submit_success]', '<label for="submit_success">' . __('Submit Success', 'ldd-directory-lite') . '</label>', '_f_submit_success', 'lddlite_settings_submit', 'lddlite_settings_submit');

        function _f_submit_use_tos() {
            echo '<label for="lite-submit_use_tos"><input id="lite-submit_use_tos" type="checkbox" name="lddlite_settings[submit_use_tos]" value="1" ' . checked(ldl_get_setting('submit_use_tos'), 1, 0) . '> ';
            echo '<span>' . __('Check this to require users agree to your terms of service (defined below) before submitting a listing.', 'ldd-directory-lite') . '</span></label>';
        }

        function _f_submit_tos() {
            wp_editor(ldl_get_setting('submit_tos'), 'ldl_submit_tos', array(
                'textarea_name' => 'lddlite_settings[submit_tos]',
                'textarea_rows' => 5
            ));
        }

        function _f_submit_intro() {
            wp_editor(ldl_get_setting('submit_intro'), 'ldl_submit_intro', array(
                'textarea_name' => 'lddlite_settings[submit_intro]',
                'textarea_rows' => 5
            ));
            echo '<p class="description">' . __('This will be displayed at the top of the submit listing form.', 'ldd-directory-lite') . '</p>';
        }

        function _f_submit_success() {
            wp_editor(ldl_get_setting('submit_success'), 'ldl_submit_success', array(
                'textarea_name' => 'lddlite_settings[submit_success]',
                'textarea_rows' => 5
            ));
            echo '<p class="description">' . __('Displayed following a successful listing submission.', 'ldd-directory-lite') . '</p>';
        }


        add_settings_section('lddlite_settings_appearance', __return_null(), '_s_settings_appearance', 'lddlite_settings_appearance');

        add_settings_field('lddlite_settings[disable_bootstrap]', __('Disable Bootstrap', 'ldd-directory-lite'), '_f_disable_bootstrap', 'lddlite_settings_appearance', 'lddlite_settings_appearance');
        add_settings_field('lddlite_settings[appearance_display_featured]', __('Display Featured Listings', 'ldd-directory-lite'), '_f_appearance_display_featured', 'lddlite_settings_appearance', 'lddlite_settings_appearance');
        add_settings_field('lddlite_settings[appearance_primary]', '<label for="appearance_primary">' . __('Primary Set', 'ldd-directory-lite') . '</label>', '_f_appearance_primary_normal', 'lddlite_settings_appearance', 'lddlite_settings_appearance');

        function _s_settings_appearance() {
            echo '<p>' . __('This section is small at the moment, but will grow with the plugin to accommodate ease of integration with your chosen theme. If you have any suggestions for visual elements you would like to see configurable here, please use one of the links above to let us know.', 'ldd-directory-lite') . '</p>';
        }

        function _f_disable_bootstrap() {
            echo '<label for="lite-disable_bootstrap"><input id="lite-disable_bootstrap" type="checkbox" name="lddlite_settings[disable_bootstrap]" value="1" ' . checked(ldl_get_setting('disable_bootstrap'), 1, 0) . '> <span>Disable</span></label>';
            echo '<p class="description">' . __('You can disable the Bootstrap CSS library if your theme already loads a copy, or if you want to use entirely custom CSS.', 'ldd-directory-lite') . '</p>';
        }

        function _f_appearance_display_featured() {
            echo '<label for="lite-appearance_display_featured"><input type="checkbox" name="lddlite_settings[appearance_display_featured]" value="1" ' . checked(ldl_get_setting('appearance_display_featured'), 1, 0) . '> <span>' . __('If checked, listings tagged with <code>featured</code> will be shown on your directory home page', 'ldd-directory-lite') . '</span></label>';
        }

        function _f_appearance_primary_normal() {
            echo '<input id="appearance_primary_normal" type="text" name="lddlite_settings[appearance_primary_normal]" value="' . ldl_get_setting('appearance_primary_normal') . '" class="my-color-field" data-default-color="#3bafda">';
            echo '<input id="appearance_primary_hover" type="text" name="lddlite_settings[appearance_primary_hover]" value="' . ldl_get_setting('appearance_primary_hover') . '" class="my-color-field" data-default-color="#3071a9">';
            echo '<input id="appearance_primary_foreground" type="text" name="lddlite_settings[appearance_primary_foreground]" value="' . ldl_get_setting('appearance_primary_foreground') . '" class="my-color-field" data-default-color="#ffffff">';
            echo '<p class="description">' . __('Set the <strong>normal / hover / foreground</strong> state for primary elements, including various buttons, labels and badges.', 'ldd-directory-lite') . '</p>';
        }

        register_setting('lddlite_settings', 'lddlite_settings', array($this, 'validate_settings'));
    }


    public function validate_settings($input) {

        if (empty($_POST['_wp_http_referer']))
            return $input;

        $settings = wp_parse_args(get_option('lddlite_settings'), ldl_get_default_settings());

        parse_str($_POST['_wp_http_referer'], $referrer);
        $tab = isset($referrer['tab']) ? $referrer['tab'] : 'general';

        $input = $input ? $input : array();
        $input = apply_filters('lddlite_settings_' . $tab . '_sanitize', $input);

        $output = array_merge($settings, $input);

        add_settings_error('lddlite_settings', '', __('Settings updated.', 'ldd-directory-lite'), 'updated');

        return $output;
    }


    public function add_settings_menu() {
        add_submenu_page('edit.php?post_type=' . LDDLITE_POST_TYPE, 'Directory Lite Configuration', 'Settings', 'manage_options', 'lddlite-settings', array(
                $this,
                'settings_page'
            ));
    }


    public function settings_page() {

        wp_enqueue_style('font-awesome');
        wp_enqueue_style('lddlite-admin');

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        ?>
        <div class="wrap directory-lite">
            <h2 class="heading"><?php _e('Directory Settings', 'lddlite'); ?></h2>

            <div class="sub-heading">
                <p><?php _e('Customize your Directory using the settings found on the following pages. If you require support or would like to make a suggestion for improving this plugin, please refer to the following links.', 'lddlite'); ?></p>
                <ul id="directory-links">
                    <li><a href="https://github.com/mwaterous/ldd-directory-lite/issues"
                           title="Submit a bug or feature request on GitHub" class="bold-link"><i
                                class="fa fa-exclamation-triangle fa-fw"></i> <?php _e('Submit an Issue', 'lddlite'); ?>
                        </a></li>
                    <li class="right"><i class="fa fa-wordpress fa-fw"></i> Visit us on <a
                            href="http://wordpress.org/support/plugin/ldd-directory-lite"
                            title="Come visit the plugin homepage on WordPress.org"><?php _e('WordPress.org', 'lddlite'); ?></a>
                    </li>
                    <li><a href="http://wordpress.org/support/plugin/ldd-directory-lite"
                           title="Visit the LDD Directory Lite Support Forums on WordPress.org" class="bold-link"><i
                                class="fa fa-comments fa-fw"></i> <?php _e('Support Forums', 'lddlite'); ?></a></li>
                    <li class="right"><i class="fa fa-github-alt fa-fw"></i> Visit us on <a
                            href="https://github.com/mwaterous/ldd-directory-lite"
                            title="We do most of our development from GitHub, come join us!"><?php _e('GitHub.com', 'lddlite'); ?></a>
                    </li>
                    <li><a href="https://wordpress.org/support/plugin/ldd-directory-lite/reviews/#new-topic-0"
                           title="Rate this plugin on WordPress.org" class="bold-link"><i
                                class="fa fa-star fa-fw"></i> <?php _e('Rate this Plugin', 'lddlite'); ?>
                        </a></li>
                </ul>
            </div>

            <?php settings_errors('lddlite_settings') ?>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo add_query_arg('tab', 'general', remove_query_arg('settings-updated')); ?>"
                   class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'lddlite'); ?></a>
                <a href="<?php echo add_query_arg('tab', 'email', remove_query_arg('settings-updated')); ?>"
                   class="nav-tab <?php echo $active_tab == 'email' ? 'nav-tab-active' : ''; ?>"><?php _e('Email', 'lddlite'); ?></a>
                <a href="<?php echo add_query_arg('tab', 'submit', remove_query_arg('settings-updated')); ?>"
                    class="nav-tab <?php echo $active_tab == 'submit' ? 'nav-tab-active' : ''; ?>"><?php _e('Submit Form', 'lddlite'); ?></a>
                <a href="<?php echo add_query_arg('tab', 'appearance', remove_query_arg('settings-updated')); ?>"
                   class="nav-tab <?php echo $active_tab == 'appearance' ? 'nav-tab-active' : ''; ?>"><?php _e('Appearance', 'lddlite'); ?></a>
            </h2>

            <div id="tab_container">

                <form method="post" action="options.php">
                    <?php
                    settings_fields('lddlite_settings');

                    if ($active_tab == 'general') {
                        do_settings_sections('lddlite_settings_general');
                    } elseif ($active_tab == 'email') {
                        do_settings_sections('lddlite_settings_email');
                    } elseif ($active_tab == 'submit') {
                        do_settings_sections('lddlite_settings_submit');
                    } elseif ($active_tab == 'appearance') {
                        do_settings_sections('lddlite_settings_appearance');
                    }

                    submit_button();
                    ?>

                </form>
            </div>
            <!-- #tab_container-->
        </div><!-- .wrap -->
    <?php

    }


}

/** Blow things up! */
ldd_directory_lite_admin::get_instance();
