<?php
/**
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 * @wordpress-plugin
 * Plugin Name:       LDD Directory Lite
 * Plugin URI:        http://wordpress.org/plugins/ldd-directory-lite
 * Description:       Powerful and simple to use, add a directory of business or other organizations to your web site.
 * Version:           0.8.1-beta
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Text Domain:       ldd-directory-lite
 * Domain Path:       /languages/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('WPINC'))
    die;

/**
 * Define constants
 */
define('LDDLITE_VERSION', '0.8.1-beta');

define('LDDLITE_PATH', dirname(__FILE__));
define('LDDLITE_URL', rtrim(plugin_dir_url(__FILE__), '/'));

define('LDDLITE_POST_TYPE', 'directory_listings');
define('LDDLITE_TAX_CAT', 'listing_category');
define('LDDLITE_TAX_TAG', 'listing_tag');

define('LDDLITE_PFX', 'lddlite');
define('LDDLITE_NOLOGO', plugin_dir_url(__FILE__) . 'public/images/noimage.png');


/**
 * Flush the rewrites for custom post types
 */
register_activation_hook(__FILE__, 'install_ldd_directory_lite');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');


function install_ldd_directory_lite() {

    flush_rewrite_rules();

    $ldl_settings = get_option('lddlite_settings', array());

    if (!isset($settings['directory_front_page'])) {
        $directory = wp_insert_post(array(
            'post_title'     => __('Directory', 'ldd-directory-lite'),
            'post_name'      => 'directory',
            'post_content'   => '[directory]',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
        ));

        $submit = wp_insert_post(array(
            'post_title'    => __('Submit a Listing', 'ldd-directory-lite'),
            'post_name'     => 'submit-listing',
            'post_content'  => '[directory_submit]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_parent'    => $directory,
            'comment_status' => 'closed',
        ));

        $manage = wp_insert_post(array(
            'post_title'    => __('Manage Listings', 'ldd-directory-lite'),
            'post_name'     => 'manage-listings',
            'post_content'  => '[directory_manage]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_parent'    => $directory,
            'comment_status' => 'closed',
        ));

        $ldl_settings['directory_front_page'] = $directory;
        $ldl_settings['directory_submit_page'] = $submit;
        $ldl_settings['directory_manage_page'] = $manage;
    }

    foreach (ldl_get_default_settings() as $tab => $settings) {
        foreach ($settings as $option) {

            if ('checkbox' == $option['type'] && !empty($option['std'])) {
                $ldl_settings[ $option['id'] ] = '1';
            }

        }
    }

    update_option('lddlite_settings', $ldl_settings);
}


/**
 * Primary controller class, this handles set up for the entire plugin.
 *
 * @since the_beginning
 */
class ldd_directory_lite {

    private static $_instance = null;
    private $settings = array();


    /**
     * Return a single instance of the class responsible for setting up the plugin (also include functions.php here
     * so that we know some functionality is available prior to full init).
     *
     * @since 0.5.0
     * @return ldd_directory_lite An instance of the ldd_directory_lite class
     */
    public static function get_instance() {
        if (null === self::$_instance) {
            self::$_instance = new self;
            self::$_instance->load_plugin_textdomain();
            self::$_instance->include_files();
            self::$_instance->init();
        }

        return self::$_instance;
    }


    /**
     * Handles all pre-ignition, including checking for any necessary upgrades and populating the settings property.
     *
     * @todo  Anonymous usage tracking back in before stable
     * @since 0.5.0
     */
    public function init() {

        // ldd business directory import
        $plugin = 'ldd-business-directory/lddbd_core.php';
        $dir = dirname(__FILE__);
        $plugin_path = substr($dir, 0, strrpos($dir, '/')) . '/' . $plugin;

        if (file_exists($plugin_path) && false == get_option('lddlite_imported_from_original'))
            require_once(LDDLITE_PATH . '/import-lddbd.php');

        define('WP_UNINSTALL_PLUGIN',1);
        require()

        $this->settings = get_option('lddlite_settings');

        $version = get_option('lddlite_version');

        if (!$version) {
            update_option('lddlite_version', LDDLITE_VERSION);
        } else if ($version && LDDLITE_VERSION != $version) {
            global $upgrades;

            $upgrades = array(
                '0.6.0-beta' => false,
            );

            foreach ($upgrades as $upgrade => $trigger) {
                if (version_compare($version, $upgrade, '<')) {
                    $upgrade_available = true;
                    $upgrades[ $upgrade ] = true;
                }
            }

            if (isset($upgrade_available))
                require_once(LDDLITE_PATH . '/upgrade.php');

            update_option('lddlite_version', LDDLITE_VERSION);

        }

        //add_action('init', array($this, 'load_plugin_textdomain'));
        //add_action('init', array('ldd_directory_lite_tracking', 'get_instance'));

    }


    /**
     * Include all the files we'll need to function.
     *
     * @since 0.5.0
     */
    public function include_files() {

        require(LDDLITE_PATH . '/includes/admin/register-settings.php');

        require(LDDLITE_PATH . '/includes/functions.php');
        require(LDDLITE_PATH . '/includes/setup.php');

        require(LDDLITE_PATH . '/includes/listings.php');
        require(LDDLITE_PATH . '/includes/ajax.php');
        require(LDDLITE_PATH . '/includes/template-functions.php');
        require(LDDLITE_PATH . '/includes/shortcodes/directory.php');
        require(LDDLITE_PATH . '/includes/shortcodes/_submit.php');
        require(LDDLITE_PATH . '/includes/shortcodes/_manage.php');

        if (is_admin()) {
            require(LDDLITE_PATH . '/includes/admin/setup.php');
            require(LDDLITE_PATH . '/includes/admin/metaboxes.php');
            require(LDDLITE_PATH . '/includes/admin/help.php');
            require(LDDLITE_PATH . '/includes/admin/display.php');
        }

    }


    /**
     * Loads the related i18n files into the appropriate domain.
     *
     * @since 0.5.0
     */
    public function load_plugin_textdomain() {
        $lang_dir = apply_filters('lddlite_languages_path', dirname(plugin_basename(__FILE__)) . '/languages/');
        load_plugin_textdomain('ldd-directory-lite', false, $lang_dir);
    }


    public function has_option($key) {
        return isset($this->settings[ $key ]);
    }


    /**
     * Gets a setting from the private $settings array and returns it. An empty string is returned if the setting
     * is not found in order to avoid triggering a false negative. Settings that may have a true|false value should
     * be explicitly tested.
     *
     * @since 0.5.3
     * @param string $key     Identify what setting is being requested
     * @param mixed  $default Provide a default if the setting is not found
     * @return mixed The value of the setting being requested
     */
    public function get_option($key, $default = '') {
        $value = !empty($this->settings[ $key ]) ? $this->settings[ $key ] : $default;
        $value = apply_filters('lddlite_get_option', $value, $key, $default);
        return apply_filters('lddlite_get_option_' . $key, $value, $key, $default);
    }


    /**
     * Update a setting and save it.
     *
     * @since 0.5.3
     * @param string $key   Identifies the setting being updated
     * @param mixed  $value The new value
     */
    public function update_option($key, $value = '') {

        if (empty($key))
            return;

        $old_value = !empty($$this->settings[ $key ]) ? $this->settings[ $key ] : '';
        $value = apply_filters('lddlite_update_option', $value, $key, $old_value);
        $this->settings[ $value ] = apply_filters('lddlite_update_option_' . $key, $value, $key, $old_value);
        return update_option('lddlite_settings', $this->settings);

    }

}

/**
 * An alias for the ldd_directory_lite get_instance() method.
 *
 * @return ldd_directory_lite The controller singleton
 */
function ldl() {
    return ldd_directory_lite::get_instance();
}

/** Das boot */
if (!defined('WP_UNINSTALL_PLUGIN'))
    ldl();
