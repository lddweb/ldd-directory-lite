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
 * Version:           0.5.5-beta
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Text Domain:       lddlite
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) die;

/**
 * Define constants
 */
define( 'LDDLITE_VERSION',      '0.5.5-beta' );

define( 'LDDLITE_PATH',         trailingslashit( dirname( __FILE__ ) ) );
define( 'LDDLITE_URL',          plugin_dir_url( __FILE__ ) );

define( 'LDDLITE_POST_TYPE',    'directory_listings' );
define( 'LDDLITE_TAX_CAT',      'listing_category' );
define( 'LDDLITE_TAX_TAG',      'listing_tag' );

define( 'LDDLITE_PFX',          '_lddlite' );


/**
 * Flush the rewrites for custom post types
 */
register_activation_hook( __FILE__, array( 'LDD_Directory_Lite', 'flush_rewrite' ) );
register_deactivation_hook( __FILE__, array( 'LDD_Directory_Lite', 'flush_rewrite' ) );


/**
 * Primary controller class, this handles set up for the entire plugin.
 *
 * @since the_beginning
 */
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
    private $listing_ID;


    /**
     * Singleton pattern, returns an instance of the class responsible for setting up the plugin
     * and lording over it's configuration settings.
     *
     * @since 0.5.0
     * @return LDD_Directory_Lite An instance of the LDD_Directory_Lite class
     */
    public static function get_instance() {
        if ( null === self::$_instance ) {
	        require_once( LDDLITE_PATH . 'includes/functions.php' );

            self::$_instance = new self;
            self::$_instance->init();
            self::$_instance->include_files();
            self::$_instance->action_filters();
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

		$this->settings = wp_parse_args(
			get_option( 'lddlite_settings' ),
			ldl_get_default_settings() );

		$version = get_option( 'lddlite_version' );

/*		$urls = array(
			'website'  => get_post_meta( 42, '_lddlite_url_website', true ),
			'linkedin' => get_post_meta( 42, '_lddlite_url_linkedin', true ),
			'facebook' => get_post_meta( 42, '_lddlite_url_facebook', true ),
			'twitter'  => get_post_meta( 42, '_lddlite_url_twitter', true ),
		);
		update_post_meta( 42, '_lddlite_urls', $urls );
		md( get_post_meta( 42, '_lddlite_geo', true ) );
		mdd( $urls );*/
		if ( LDDLITE_VERSION != $version.'v' ) {
			global $upgrades;

			$upgrades = array(
				'0.5.5-beta' => false,
			);

			foreach ( $upgrades as $upgrade => $trigger ) {

				if ( version_compare( $version, $upgrade, '<' ) ) {
					$upgrade_available = true;
					$upgrades[ $upgrade ] = true;
				}

			}

			if ( isset( $upgrade_available ) )
				require_once( LDDLITE_PATH . 'upgrade.php' );

			$version = LDDLITE_VERSION;
			update_option( 'lddlite_version', $version );

		}

		$this->version = $version;

	}


	/**
     * Include all the files we'll need to function.
     *
     * @since 0.5.0
     */
    public function include_files() {

	    { // Check for the existence of the old plugin and offer to import any data
		    $plugin = 'ldd-business-directory/lddbd_core.php';
		    $dir = dirname( __FILE__ );
		    $plugin_path = substr( $dir, 0, strrpos( $dir, '/' ) ) . '/' . $plugin;
		    if ( file_exists( $plugin_path ) && false == get_option( 'lddlite_upgraded_from_original' ) )
			    require_once( LDDLITE_PATH . 'import-lddbd.php' );
	    }

	    require_once( LDDLITE_PATH . 'includes/class.tracking.php' );
        require_once( LDDLITE_PATH . 'includes/post-types.php' );
        require_once( LDDLITE_PATH . 'includes/setup.php' );
        require_once( LDDLITE_PATH . 'includes/ajax.php' );

        if ( is_admin() ) {
            require_once( LDDLITE_PATH . 'includes/admin/metaboxes.php' );
            require_once( LDDLITE_PATH . 'includes/admin/pointers.php' );
            require_once( LDDLITE_PATH . 'includes/admin/settings.php' );
            require_once( LDDLITE_PATH . 'includes/admin/help.php' );
        }
    }


    /**
     * Really all this does at the moment is add the textdomain function, and given
     * that there are no translations for the plugin at the moment, we're pretty much just
     * going through the motions.
     *
     * @since 0.5.0
     */
    public function action_filters() {
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
    }

    /**
     * Load the related i18n files into the appropriate domain.
     *
     * @since 0.5.0
     */
    public function load_plugin_textdomain() {


        $lang_dir = LDDLITE_PATH . 'languages/';
        $lang_dir = apply_filters( 'lddlite_languages_directory', $lang_dir );

        $locale = apply_filters( 'plugin_locale', get_locale(), 'lddlite' );
        $mofile = $lang_dir . 'lddlite' . $locale . '.mo';

        if ( file_exists( $mofile ) )
            load_textdomain( 'lddlite', $mofile );
        else
            load_plugin_textdomain( 'lddlite', false, $lang_dir );

    }


    /**
     * Gets a setting from the private $settings array and returns it. An empty string is returned if the setting
     * is not found in order to avoid triggering a false negative. Settings that may have a true|false value should
     * be explicitly tested.
     *
     * @since 0.5.3
     * @param string $key The configuration setting we need the value of
     * @return mixed An empty string, or the setting value
     */
    public function get_setting( $key ) {

        if ( empty( $this->settings ) )
	        $this->populate_options();

        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : '';
    }


    /**
     * Update a configuration setting stored in the private $settings array
     *
     * @since 0.5.3
     * @param string $key The configuration setting we're updating
     * @param mixed $value The value for the configuration setting, leave empty to initialize
     */
    public function update_setting( $key, $value = '' ) {

		if ( empty( $key ) || !isset( $this->settings[ $key ] ) )
			return;

		$this->settings[ $key ] = $value;
	}


    /**
     * An alias for update_setting() at present, may have further use in the future.
     *
     * @since 0.5.3
     * @param string $key The configuration setting we're updating
     * @param mixed $value The value for the configuration setting, leave empty to initialize
     */
    public function add_setting( $key, $value = '' ) {
		$this->update_setting( $key, $value );
	}


    /**
     * Writes the $settings array to the database.
     *
     * @since 0.5.3
     */
    public function save_settings() {
		if ( !empty( $this->settings ) )
			update_option( 'lddlite_settings', $this->settings );
	}


    /**
     * This is a hack way of memorizing what listing we're viewing, necessary due to the current way we're
     * displaying UI elements. This will most likely deprecate if and when the plugin moves to using internal
     * rewrites provided by the custom post type and taxonomy API.
     *
     * @since 0.5.3
     * @param int $listing_ID The listing/post ID for the currently active listing
     */
    public function set_listing_id( $listing_ID ) {
        $this->listing_ID = $listing_ID;
    }


    /**
     * Get the previously stored listing ID.
     *
     * @since 0.5.3
     * @return int The currently active listing ID
     */
    public function get_listing_id() {
        return $this->listing_ID;
    }

}


/**
 * An alias for the LDD_Directory_Lite get_instance() method.
 *
 * @return LDD_Directory_Lite The controller singleton
 */
function ldl_get_instance() {
	return LDD_Directory_Lite::get_instance();
}

/**
 * Das boot
 */
ldl_get_instance();
