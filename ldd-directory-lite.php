<?php
/**
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 *
 * @wordpress-plugin
 * Plugin Name:       LDD Directory Lite
 * Plugin URI:        http://wordpress.org/plugins/ldd-directory-lite
 * Description:       Powerful and simple to use, add a directory of business or other organizations to your web site.
 * Version:           0.7-beta
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Text Domain:       lddlite
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('WPINC'))
    die;

/**
 * Define constants
 */
define('LDDLITE_VERSION', '0.7-beta');

define('LDDLITE_PATH', trailingslashit(dirname(__FILE__)));
define('LDDLITE_URL', plugin_dir_url(__FILE__));

define('LDDLITE_POST_TYPE', 'directory_listings');
define('LDDLITE_TAX_CAT', 'listing_category');
define('LDDLITE_TAX_TAG', 'listing_tag');

define('LDDLITE_PFX', 'lddlite');
define('LDDLITE_NOLOGO', plugin_dir_url(__FILE__).'public/images/noimage.png');


/**
 * Flush the rewrites for custom post types
 */
register_activation_hook(__FILE__, 'flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');


/**
 * Primary controller class, this handles set up for the entire plugin.
 *
 * @since the_beginning
 */
class ldd_directory_lite {

    private static $_instance = null;
    private $settings = array();


    /**
     * Singleton pattern, returns an instance of the class responsible for setting up the plugin
     * and lording over it's configuration settings.
     *
     * @since 0.5.0
     * @return ldd_directory_lite An instance of the ldd_directory_lite class
     */
    public static function get_instance() {
        if (null === self::$_instance) {
            require_once(LDDLITE_PATH . 'includes/functions.php');

            self::$_instance = new self;
            self::$_instance->init();
            self::$_instance->include_files();
        }

        return self::$_instance;
    }


    /**
     * Populate the settings property based on a set of defaults and information pulled from
     * the database. This will also check for and fire an upgrade if necessary.
     *
     * @since 0.5.0
     */
    public function init() {

        // ldd business directory import
        $plugin = 'ldd-business-directory/lddbd_core.php';
        $dir = dirname(__FILE__);
        $plugin_path = substr($dir, 0, strrpos($dir, '/')) . '/' . $plugin;

        if (file_exists($plugin_path) && false == get_option('lddlite_imported_from_original')) {
            require_once(LDDLITE_PATH . 'import-lddbd.php');
        }


        $this->settings = wp_parse_args(get_option('lddlite_settings'), ldl_get_default_settings());

        $version = get_option('lddlite_version');

        if ($version && LDDLITE_VERSION != $version) {
            global $upgrades;

            $upgrades = array(
                '0.6.0-beta' => false,
            );

            foreach ($upgrades as $upgrade => $trigger) {
                if (version_compare($version, $upgrade, '<')) {
                    $upgrade_available = true;
                    $upgrades[$upgrade] = true;
                }
            }

            if (isset($upgrade_available)) {
                require_once(LDDLITE_PATH . 'upgrade.php');
            }

            update_option('lddlite_version', LDDLITE_VERSION);

        } else if (!$version) {
            add_action('admin_notices', array($this, 'install_pages'));
            update_option('lddlite_version', LDDLITE_VERSION);
        }


        add_action('init', array($this, 'load_plugin_textdomain'));
        //add_action('init', array('ldd_directory_lite_tracking', 'get_instance'));

    }


    /**
     * Include all the files we'll need to function.
     *
     * @since 0.5.0
     */
    public function include_files() {

        // functions.php is included via the constructor
        require_once(LDDLITE_PATH . 'includes/setup.php');
        require_once(LDDLITE_PATH . 'includes/listings.php');
        require_once(LDDLITE_PATH . 'includes/ajax.php');
        require_once(LDDLITE_PATH . 'includes/template-functions.php');
        require_once(LDDLITE_PATH . 'includes/shortcodes/directory.php');
        require_once(LDDLITE_PATH . 'includes/shortcodes/submit.php');

        if (is_admin()) {
            require_once(LDDLITE_PATH . 'includes/admin/metaboxes.php');
            require_once(LDDLITE_PATH . 'includes/admin/settings.php');
            require_once(LDDLITE_PATH . 'includes/admin/sanitize.php');
            require_once(LDDLITE_PATH . 'includes/admin/help.php');
        }

    }


    /**
     * Automatically install required pages on initial plugin activation. This feature should ultimately ask, and
     * could have some more interaction from the user, but this will suffice between now and a stable release.
     */
    public function install_pages() {

        $directory_page = array(
            'post_content'  => '[directory]',
            'post_name'     => 'directory',
            'post_title'    => __('Directory', 'lddlite'),
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_date'     => date('Y-m-d H:i:s'),
            'post_date_gmt' => gmdate('Y-m-d H:i:s'),
        );

        $submit_page = array(
            'post_content'  => '[directory_submit]',
            'post_name'     => 'submit-listing',
            'post_title'    => __('Submit a Listing', 'lddlite'),
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_date'     => date('Y-m-d H:i:s'),
            'post_date_gmt' => gmdate('Y-m-d H:i:s'),
        );

        $post_id = wp_insert_post($directory_page);
        if ($post_id)
            $this->settings['directory_front_page'] = $post_id;

        $post_id = wp_insert_post($submit_page);
        if ($post_id)
            $this->settings['directory_submit_page'] = $post_id;

        $this->save_settings();

        $html = '<div class="updated"><p>';
        $html .= '<strong>' . __('[ldd directory lite installation notice]', 'lddlite') . '</strong><br>';
        $html .= sprintf(__('Required directory pages have been installed for you, please visit the <a href="%s">Edit Pages</a> screen to make any necessary adjustments.', 'lddlite'), admin_url('edit.php?post_type=page'));
        $html .= '</p></div>';

        echo $html;

    }


    /**
     * Load the related i18n files into the appropriate domain.
     *
     * @since 0.5.0
     */
    public function load_plugin_textdomain() {

        $lang_dir = LDDLITE_PATH . 'languages/';
        $lang_dir = apply_filters('lddlite_languages_directory', $lang_dir);

        $locale = apply_filters('plugin_locale', get_locale(), 'lddlite');
        $mofile = $lang_dir . 'lddlite' . $locale . '.mo';

        if (file_exists($mofile)) {
            load_textdomain('lddlite', $mofile);
        } else {
            load_plugin_textdomain('lddlite', false, $lang_dir);
        }

    }


    /**
     * Gets a setting from the private $settings array and returns it. An empty string is returned if the setting
     * is not found in order to avoid triggering a false negative. Settings that may have a true|false value should
     * be explicitly tested.
     *
     * @since 0.5.3
     *
     * @param string $key The configuration setting we need the value of
     *
     * @return mixed An empty string, or the setting value
     */
    public function get_setting($key) {
        return isset($this->settings[$key]) ? $this->settings[$key] : '';
    }

    /**
     * An alias for update_setting() at present, may have further use in the future.
     *
     * @since 0.5.3
     *
     * @param string $key   The configuration setting we're updating
     * @param mixed  $value The value for the configuration setting, leave empty to initialize
     */
    public function add_setting($key, $value = '') {
        $this->update_setting($key, $value);
    }

    /**
     * Update a configuration setting stored in the private $settings array
     *
     * @since 0.5.3
     *
     * @param string $key   The configuration setting we're updating
     * @param mixed  $value The value for the configuration setting, leave empty to initialize
     */
    public function update_setting($key, $value = '') {

        if (empty($key) || !isset($this->settings[$key]))
            return;

        $this->settings[$key] = $value;
    }

    /**
     * Writes the $settings array to the database.
     *
     * @since 0.5.3
     */
    public function save_settings() {
        if (!empty($this->settings))
            update_option('lddlite_settings', $this->settings);
    }

}


/**
 * An alias for the ldd_directory_lite get_instance() method.
 *
 * @return ldd_directory_lite The controller singleton
 */
function ldl_get_instance() {
    return ldd_directory_lite::get_instance();
}

/** Das boot */
ldl_get_instance();
