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
 * Description:       Powerful yet simple to use, easily add a business directory to your WordPress site.
 * Version:           2.0.0
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Text Domain:       ldd-lite
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
    die;


define( 'LDDLITE_VERSION',      '2.0.0' );

define( 'LDDLITE_PATH',         WP_PLUGIN_DIR.'/'.basename( dirname( __FILE__ ) ) );
define( 'LDDLITE_URL',          plugins_url().'/'.basename( dirname( __FILE__ ) ) );

define( 'LDDLITE_POST_TYPE',    'directory_listings' );
define( 'LDDLITE_TAX_CAT',      'listing_category' );
define( 'LDDLITE_TAX_TAG',      'listing_tag' );

define( 'LDDLITE_PFX',          '_lddlite_' );


register_activation_hook( __FILE__, array( 'LDD_Directory_Lite', 'flush_rewrite' ) );
register_deactivation_hook( __FILE__, array( 'LDD_Directory_Lite', 'flush_rewrite' ) );


final class LDD_Directory_Lite {

    /**
     * @var $_instance An instance of ones own instance
     */
    private static $_instance;

    /**
     * @var string Slug for our text domain and other similar uses
     */
    private $_slug = 'ldd-lite';

    /**
     * @var array Options, everybody has them.
     */
    public $options = array();

    /**
     * @var int The post->ID of the page where the [business_directory] shortcode is located.
     */
    public $directory_home_ID;


    /**
     * Singleton pattern, returns an instance of the class responsible for setting up the plugin
     * and lording over it's configuration options.
     *
     * @since 2.0.0
     * @return LDD_Directory_Lite An instance of the LDD_Directory_Lite class
     */
    public static function get_in() {

        if ( !isset( self::$_instance ) && !( self::$_instance instanceof LDD_Directory_Lite ) ) {
            self::$_instance = new self;
            self::$_instance->include_files();
            self::$_instance->populate_options();
            self::$_instance->setup_plugin();
        }

        return self::$_instance;

    }


    /**
     * Include all the files we'll need to function
     *
     * @since 2.0.0
     */
    public function include_files() {
        require_once( LDDLITE_PATH . '/includes/post-types.php' );
        require_once( LDDLITE_PATH . '/includes/setup.php' );
        require_once( LDDLITE_PATH . '/includes/functions.php' );
        require_once( LDDLITE_PATH . '/includes/metaboxes.php' );
        require_once( LDDLITE_PATH . '/includes/email.php' );
        if ( is_admin() ) require_once( LDDLITE_PATH . '/includes/admin.php' );
    }


    /**
     * Populate the options property based on a set of defaults and information pulled from
     * the database. This will also check for and fire an upgrade if necessary.
     *
     * @since 2.0.0
     */
    public function populate_options() {

        $defaults = apply_filters( 'lddlite_default_options', array(
            'version'           => 0.1,
            'public_or_private' => 1,
            'google_maps'       => 1,
            'email_onsubmit'    => 'Your directory listing was successfully submitted!',
            'email_onapprove'   => 'Your directory listing was approved!'
        ) );

        $options = wp_parse_args(
            get_option( 'lddlite-options' ),
            $defaults );

        $dir = dirname( __FILE__ );
        $old_plugin = substr( $dir, 0, strrpos( $dir, '/' ) ) . '/ldd-business-directory/lddbd_core.php';

        if ( file_exists( $old_plugin ) && version_compare( LDDLITE_VERSION, $options['version'], '>' ) ) {
            require_once( LDDLITE_PATH . '/upgrade.php' );
            add_action( 'init', 'ld_upgrade__go', 20 ); // This has to fire later, so we know our CPT's are registered
        }

        $this->options = $options;

    }


    /**
     * Minor setup. Major setup of internal funtionality is handled in setup.php
     *
     * @since 2.0.0
     */
    public function setup_plugin() {
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        $basename = plugin_basename( __FILE__ );
        add_filter( 'plugin_action_links_' . $basename, array( $this, 'add_action_links' ) );
    }


    /**
     * Load the related i18n files into the appropriate domain.
     *
     * @since 2.0.0
     */
    public function load_plugin_textdomain() {

        $lang_dir = LDDLITE_PATH . '/languages/';
        $lang_dir = apply_filters( 'lddlite_languages_directory', $lang_dir );

        $locale = apply_filters( 'plugin_locale', get_locale(), $this->_slug );
        $mofile = $lang_dir . $this->_slug . $locale . '.mo';

        if ( file_exists( $mofile ) )
            load_textdomain( $this->_slug, $mofile );
        else
            load_plugin_textdomain( $this->_slug, false, $lang_dir );

    }


    /**
     * Add a 'Settings' link on the Plugins page for easier access.
     *
     * @since 2.0.0
     * @param $links array Passed by the filter
     * @return array The modified $links array
     */
    public function add_action_links( $links ) {

        return array_merge(
            array(
                'settings' => '<a href="' . admin_url( 'options-writing.php' ) . '">' . __( 'Settings', 'wp-bitly' ) . '</a>'
            ),
            $links
        );

    }


    /**
     * Allow our slug to be used outside this class, without being modified
     *
     * @since 2.0.0
     * @return string The slug
     */
    public function slug() {
        return $this->_slug;
    }


    /**
     * Flush rewrite rules on activation or deactivation of the plugin.
     *
     * @since 2.0.0
     */
    public static function flush_rewrite() {
        flush_rewrite_rules( false );
    }


}


/**
 * An alias to calling the LDD_Directory_Lite::get_in() method
 *
 * @return LDD_Directory_Lite
 */
function lddlite() {
    return LDD_Directory_Lite::get_in();
}

// BOOM! I SAID BOOM!
lddlite();
