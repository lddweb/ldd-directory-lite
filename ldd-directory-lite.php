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


define( 'LDDLITE_VERSION',  '0.1.0' );

define( 'LDDLITE_PATH',     WP_PLUGIN_DIR.'/'.basename( dirname( __FILE__ ) ) );
define( 'LDDLITE_URL',      plugins_url().'/'.basename( dirname( __FILE__ ) ) );

define( 'LDDLITE_CSS',      LDDLITE_URL . '/public/css' );
define( 'LDDLITE_JS',       LDDLITE_PATH . '/public/js' );
define( 'LDDLITE_JS_URL',   LDDLITE_URL . '/public/js' );

define( 'LDDLITE_AJAX',     LDDLITE_URL . '/includes/ajax.php' );

define( 'LDDLITE_TEMPLATES',    LDDLITE_PATH . '/templates' );
define( 'LDDLITE_TPL_EXT',      'tpl' );





final class _LDD_Directory_Lite
{

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


    public static function get_in()
    {

        if ( !isset( self::$_instance ) && !( self::$_instance instanceof _LDD_Business_Directory ) )
        {
            self::$_instance = new self;
            self::$_instance->populate_options();
            self::$_instance->include_files();
            self::$_instance->check_for_upgrade();
            self::$_instance->action_filters();
            self::$_instance->enqueue_scripts();
        }

        return self::$_instance;

    }


    public function populate_options()
    {

        $defaults = apply_filters( 'lddlite_default_options', array(
            'version'           => LDDLITE_VERSION,
            'email_onsubmit'    => 'Your directory listing was successfully submitted!',
            'email_onapprove'   => 'Your directory listing was approved!'
        ) );

        $this->options = wp_parse_args(
            get_option( 'lddlite-options' ),
            $defaults );

    }


    public function include_files()
    {
        require_once( LDDLITE_PATH . '/includes/functions.php' );
        require_once( LDDLITE_PATH . '/includes/settings.php' );
        require_once( LDDLITE_PATH . '/includes/email.php' );
        require_once( LDDLITE_PATH . '/includes/display.php' );

        if ( is_admin() )
            require_once( LDDLITE_PATH . '/includes/admin.php' );
    }


    public function check_for_upgrade()
    {

    }


    public function action_filters()
    {

        // Yada, yada (nothing to see here folks).
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        $basename = plugin_basename( __FILE__ );
        add_filter( 'plugin_action_links_' . $basename, array( $this, 'add_action_links' ) );

        add_shortcode( 'business_directory', 'lddlite_display_directory' );

        if ( isset( $_GET['submit'] ) )
            add_action( 'init', 'session_start' );

        if ( isset( $_GET['submit'] ) && isset( $_POST['goback'] ) )
        {
            $url = parse_url( esc_url( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) );
            parse_str( $url['query'], $query );
            md( $url );
            $last_page = $_POST['current_page'] - 1;
            $url = substr( esc_url( $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ), 0, -1 ) . $last_page;
            header( 'Location: ' . $url );
        }

    }


        public function load_plugin_textdomain()
        {

            $lang_dir = LDDLITE_PATH . '/languages/';
            $lang_dir = apply_filters( 'lddlite_languages_directory', $lang_dir );

            $locale = apply_filters( 'plugin_locale', get_locale(), self::$_slug );
            $mofile = $lang_dir . self::$_slug . $locale . '.mo';

            if ( file_exists( $mofile ) )
                load_textdomain( self::$_slug, $mofile );
            else
                load_plugin_textdomain( self::$_slug, false, $lang_dir );

        }


        public function add_action_links( $links )
        {

            return array_merge(
                array(
                    'settings' => '<a href="' . admin_url( 'options-writing.php' ) . '">' . __( 'Settings', 'wp-bitly' ) . '</a>'
                ),
                $links
            );

        }


    public function enqueue_scripts()
    {
        add_action( 'wp_enqueue_scripts', 'lddlite_enqueue_scripts' );
        add_action( 'admin_enqueue_scripts', 'lddlite_enqueue_scripts' );

        function lddlite_enqueue_scripts()
        {
            // We want this in the footer so that we can set the
            wp_enqueue_script( 'dirl-js', LDDLITE_JS_URL . '/lite.js', array( 'jquery' ), LDDLITE_VERSION, true );
            // @TODO: This should be reduced in scope so that it only enqueues where necessary.
            // @TODO: Right now it's just easier to plug it in.
            wp_enqueue_style( 'dirl-styles', LDDLITE_CSS . '/styles.css', array(), LDDLITE_VERSION );
        }
    }


    public function slug()
    {
        return self::$_slug;
    }


}


function lddlite()
{
    return _LDD_Directory_Lite::get_in();
}

lddlite();


function lddslug()
{
    $lddlite = lddlite();
    return $lddlite->slug;
}

// @TODO: LEGACY CRAP. GET RID OF ASAP.
global $tables;
$tables = array(
    'main'  => $wpdb->prefix . 'lddbusinessdirectory',
    'doc'   => $wpdb->prefix . 'lddbusinessdirectory_docs',
    'cat'   => $wpdb->prefix . 'lddbusinessdirectory_cats'
);