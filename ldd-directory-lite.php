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
 * Version:           0.5.3-beta
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Text Domain:       lddlite
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) die;


define( 'LDDLITE_VERSION',      '0.5.3-beta' );

define( 'LDDLITE_PATH',         WP_PLUGIN_DIR.'/'.basename( dirname( __FILE__ ) ) );
define( 'LDDLITE_URL',          plugins_url().'/'.basename( dirname( __FILE__ ) ) );

define( 'LDDLITE_POST_TYPE',    'directory_listings' );
define( 'LDDLITE_TAX_CAT',      'listing_category' );
define( 'LDDLITE_TAX_TAG',      'listing_tag' );

define( 'LDDLITE_PFX',          '_lddlite_' );


register_activation_hook( __FILE__, array( 'LDD_Directory_Lite', 'flush_rewrite' ) );
register_deactivation_hook( __FILE__, array( 'LDD_Directory_Lite', 'flush_rewrite' ) );



class LDD_Directory_Lite {

    /**
     * @var $_instance An instance of ones own instance
     */
    private static $_instance = null;

    /**
     * @var array The plugins main settings array
     */
    private $settings = array();

    /**
     * @var object This is a temporary storage facility for listings, transporting information between actions
     */
    public $listing;


    /**
     * Singleton pattern, returns an instance of the class responsible for setting up the plugin
     * and lording over it's configuration settings.
     *
     * @since 0.5.0
     * @return LDD_Directory_Lite An instance of the LDD_Directory_Lite class
     */
    public static function get_instance() {
        if ( null === self::$_instance ) {
	        require_once( LDDLITE_PATH . '/includes/functions.php' );

            self::$_instance = new self;
            self::$_instance->populate_options(); // This should always happen before anything else
            self::$_instance->include_files();
            self::$_instance->setup_plugin();
        }
        return self::$_instance;
    }


    /**
     * Include all the files we'll need to function.
     *
     * @since 0.5.0
     */
    public function include_files() {
	    require_once( LDDLITE_PATH . '/includes/class.ldl-tracking.php' );
        require_once( LDDLITE_PATH . '/includes/post-types.php' );
        require_once( LDDLITE_PATH . '/includes/setup.php' );

        if ( is_admin() ) {
            require_once( LDDLITE_PATH . '/includes/admin/metaboxes.php' );
            require_once( LDDLITE_PATH . '/includes/admin/pointers.php' );
            require_once( LDDLITE_PATH . '/includes/admin/settings.php' );
            require_once( LDDLITE_PATH . '/includes/admin/help.php' );
        }
    }


    /**
     * Populate the options property based on a set of defaults and information pulled from
     * the database. This will also check for and fire an upgrade if necessary.
     *
     * @since 0.5.0
     */
    public function populate_options() {

        $settings = wp_parse_args(
            get_option( 'lddlite_settings' ),
            ldl_get_default_settings() );

        $version = get_option( 'lddlite_version' );

//      require_once( LDDLITE_PATH . '/uninstall.php' );
        if ( !$version ) {
            $dir = dirname( __FILE__ );
            $old_plugin = substr( $dir, 0, strrpos( $dir, '/' ) ) . '/ldd-business-directory/lddbd_core.php';
            if ( file_exists( $old_plugin ) ) {
                require_once( LDDLITE_PATH . '/upgrade.php' );
                add_action( 'init', 'ldl_upgrade', 20 ); // This has to fire later, so we know our CPT's are registered
                add_action( 'admin_init', 'ldl_disable_old' );
            }
        }

        $this->settings = $settings;
        $this->version = $version;

    }


    /**
     *
     *
     * @since 0.5.0
     */
    public function setup_plugin() {
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    }


    /**
     * Load the related i18n files into the appropriate domain.
     *
     * @since 0.5.0
     */
    public function load_plugin_textdomain() {

        $lang_dir = LDDLITE_PATH . '/languages/';
        $lang_dir = apply_filters( 'lddlite_languages_directory', $lang_dir );

        $locale = apply_filters( 'plugin_locale', get_locale(), 'lddlite' );
        $mofile = $lang_dir . 'lddlite' . $locale . '.mo';

        if ( file_exists( $mofile ) )
            load_textdomain( 'lddlite', $mofile );
        else
            load_plugin_textdomain( 'lddlite', false, $lang_dir );

    }

    public function get_setting( $key ) {

        if ( empty( $this->settings ) )
	        $this->populate_options();

        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : '';
    }


	public function update_setting( $key, $value = '' ) {

		if ( empty( $key ) || !isset( $this->settings[ $key ] ) )
			return;

		$this->settings[ $key ] = $value;
	}


	public function add_setting( $key, $value = '' ) {
		$this->update_setting( $key, $value );
	}


	public function save_settings() {
		if ( !empty( $this->settings ) )
			update_option( 'lddlite_settings', $this->settings );
	}



}

function ldl_load() {
	return LDD_Directory_Lite::get_instance();
}

class ldl {


    public static function attach( $listing ) {
        $l = ldl_load();
        $l->listing = $listing;
    }

    public static function pull() {
        $l = ldl_load();
        return $l->listing;
    }

}

/**
 * Start everything.
 */
ldl_load();


