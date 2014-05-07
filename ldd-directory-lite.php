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
 * Text Domain:       ldd-bd
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /lang

 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
    die;


define( 'LDDLITE_VERSION',      '2.0.0' );

define( 'LDDLITE_PATH',         WP_PLUGIN_DIR.'/'.basename( dirname( __FILE__ ) ) );
define( 'LDDLITE_URL',          plugins_url().'/'.basename( dirname( __FILE__ ) ) );

define( 'LDDLITE_AJAX',         LDDLITE_URL . '/includes/ajax.php' );

define( 'LDDLITE_POST_TYPE',    'directory_listings' );
define( 'LDDLITE_TAX_CAT',      'listing_category' );
define( 'LDDLITE_TAX_TAG',      'listing_tag' );

define( 'LDDLITE_TEMPLATES',    LDDLITE_PATH . '/templates' );
define( 'LDDLITE_TPL_EXT',      'tpl' );

define( 'LDDLITE_PFX',          '_lddlite_' );




final class LDD_Directory_Lite {

    /**
     * @var $_instance An instance of ones own instance
     */
    private static $_instance;

    /**
     * @var string Static-ly accessible and globally similar
     */
    private static $_slug = 'ldd-lite';

    /**
     * @var array Options, everybody has them.
     */
    public $options = array();

    /**
     * @var int The post->ID of the page where the [business_directory] shortcode is located.
     */
    public $directory_home_ID;


    public static function get_in() {

        if ( !isset( self::$_instance ) && !( self::$_instance instanceof LDD_Directory_Lite ) ) {
            self::$_instance = new self;
            self::$_instance->include_files();
            self::$_instance->populate_options();
            self::$_instance->action_filters();
        }

        return self::$_instance;

    }



    public function include_files() {
        require_once( LDDLITE_PATH . '/includes/post-types.php' );
        require_once( LDDLITE_PATH . '/includes/setup.php' );
        require_once( LDDLITE_PATH . '/includes/functions.php' );
        require_once( LDDLITE_PATH . '/includes/metaboxes.php' );
        require_once( LDDLITE_PATH . '/includes/email.php' );
        require_once( LDDLITE_PATH . '/includes/views.php' );

        if ( is_admin() )
            require_once( LDDLITE_PATH . '/includes/admin.php' );
    }



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
            add_action( 'init', 'ld_upgrade_path', 20 );
        }

        $this->options = $options;

    }


    public function action_filters() {

        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        $basename = plugin_basename( __FILE__ );
        add_filter( 'plugin_action_links_' . $basename, array( $this, 'add_action_links' ) );

        add_shortcode( 'business_directory', 'ld_display_the_directory' );

        { // These all relate to our custom post types and dashboard UI
            add_action( 'init', 'lddlite_register__cpt_tax' );


            add_filter( 'post_type_link', 'lddlite_filter_post_type_link', 10, 2 );

            add_filter( 'enter_title_here', 'lddlite_filter_enter_title_here' );
            add_filter( 'admin_post_thumbnail_html', 'lddlite_filter_admin_post_thumbnail_html' );

            add_action( 'admin_head', 'lddlite_action_directory_icon' );
            add_action( '_admin_menu', 'lddlite_action_submenu_name' );
        }

        add_action( 'init', array( $this, 'register_scripts' ) );

//        add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_scripts_global' ) );
//        add_action( 'admin_enqueue_scripts', array( $this, '_enqueue_scripts_global' ) );
//        add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_scripts' ) );

        add_action( 'wp_ajax_search_directory', 'ld_ajax_search_directory' );
        add_action( 'wp_ajax_nopriv_search_directory', 'ld_ajax_search_directory' );

        // Process AJAX for the contact Form
        add_action( 'wp_ajax_contact_form', 'lddlite_ajax_contact_form' );
        add_action( 'wp_ajax_nopriv_contact_form', 'lddlite_ajax_contact_form' );

        // Process AJAX for the submission form
        add_action( 'wp_ajax_contact_form', 'lddlite_ajax_contact_form' );
        add_action( 'wp_ajax_nopriv_contact_form', 'lddlite_ajax_contact_form' );

    }


    public function load_plugin_textdomain() {

        $lang_dir = LDDLITE_PATH . '/languages/';
        $lang_dir = apply_filters( 'lddlite_languages_directory', $lang_dir );

        $locale = apply_filters( 'plugin_locale', get_locale(), self::$_slug );
        $mofile = $lang_dir . self::$_slug . $locale . '.mo';

        if ( file_exists( $mofile ) )
            load_textdomain( self::$_slug, $mofile );
        else
            load_plugin_textdomain( self::$_slug, false, $lang_dir );

    }


    public function add_action_links( $links ) {

        return array_merge(
            array(
                'settings' => '<a href="' . admin_url( 'options-writing.php' ) . '">' . __( 'Settings', 'wp-bitly' ) . '</a>'
            ),
            $links
        );

    }


    public function register_scripts() {
        wp_register_script( 'ldd-lite', LDDLITE_URL . '/public/js/lite.js', array( 'jquery' ), LDDLITE_VERSION, true );
        wp_register_script( 'ldd-lite-responsiveslides', LDDLITE_URL . '/public/js/responsiveslides.js', array( 'jquery' ), '1.54', true );
        wp_register_script( 'ldd-lite-search', LDDLITE_URL . '/public/js/search.js', array( 'jquery' ), LDDLITE_VERSION, true );

        wp_register_style( 'ldd-lite', LDDLITE_URL . '/public/css/style.css', false, LDDLITE_VERSION );
        wp_register_style( 'yui-pure', '//yui.yahooapis.com/pure/0.4.2/pure-min.css', false, '0.4.2' );
    }


    public function slug() {
        return self::$_slug;
    }


}


function lddlite() {
    return LDD_Directory_Lite::get_in();
}

lddlite();
