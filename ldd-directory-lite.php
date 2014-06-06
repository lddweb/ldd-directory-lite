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
 * Version:           0.5.2-beta
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Author:            LDD Web Design
 * Author URI:        http://www.lddwebdesign.com
 * Text Domain:       lddlite
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) die;


define( 'LDDLITE_VERSION',      '0.5.2-beta' );

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
     * @var array Options, everybody has them.
     */
    public $settings = array();

    /**
     * @var
     */
    public $listing;


    /**
     * Singleton pattern, returns an instance of the class responsible for setting up the plugin
     * and lording over it's configuration options.
     *
     * @since 0.5.0
     * @return LDD_Directory_Lite An instance of the LDD_Directory_Lite class
     */
    public static function get_in() {

        if ( null === self::$_instance ) {
            self::$_instance = new self;
            self::$_instance->populate_options();
            self::$_instance->include_files();
            self::$_instance->setup_plugin();
        }

        return self::$_instance;

    }


    /**
     * Include all the files we'll need to function
     *
     * @since 0.5.0
     */
    public function include_files() {
	    require_once( LDDLITE_PATH . '/includes/class.ldl-tracking.php' );
        require_once( LDDLITE_PATH . '/includes/post-types.php' );
        require_once( LDDLITE_PATH . '/includes/setup.php' );
        require_once( LDDLITE_PATH . '/includes/functions.php' );
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

//       require_once( LDDLITE_PATH . '/uninstall.php' );

        if ( !$version ) {
            $dir = dirname( __FILE__ );
            $old_plugin = substr( $dir, 0, strrpos( $dir, '/' ) ) . '/ldd-business-directory/lddbd_core.php';
            if ( file_exists( $old_plugin ) ) {
                require_once( LDDLITE_PATH . '/upgrade.php' );
                add_action( 'init', 'ldl_upgrade', 20 ); // This has to fire later, so we know our CPT's are registered
                add_action( 'admin_init', 'ldl_disable_old' );
            }
        }

/*	    unset( $settings['allow_tracking'] );
	    unset( $settings['allow_tracking_popup_done'] );
	    update_option( 'lddlite_settings', $settings );*/
        $this->settings = $settings; //mdd( $settings, 'd' );
        $this->version = $version;

    }


    /**
     * Minor setup. Major setup of internal funtionality is handled in setup.php
     *
     * @since 0.5.0
     */
    public function setup_plugin() {
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        $basename = plugin_basename( __FILE__ );
        add_filter( 'plugin_action_links_' . $basename, array( $this, 'add_action_links' ) );
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


    /**
     * Add a 'Settings' link on the Plugins page for easier access.
     *
     * @since 0.5.0
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
     * Flush rewrite rules on activation or deactivation of the plugin.
     *
     * @since 0.5.0
     */
    public static function flush_rewrite() {
        flush_rewrite_rules( false );
    }


    public function get_setting( $key ) {
        if ( empty( $this->settings ) )
            $this->populate_options();
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : '';
    }


	public function update_setting( $key, $value = '' ) {
		$this->settings[ $key ] = $value;
	}


	public function save_settings() {
		update_option( 'lddlite_settings', $this->settings );
	}

}

class ldl {

    public static function load() {
        return LDD_Directory_Lite::get_in();
    }

    public static function tpl() {
        require_once( LDDLITE_PATH . '/includes/class.raintpl.php' );

        raintpl::configure( 'tpl_ext',      'tpl' );
        raintpl::configure( 'tpl_dir',      LDDLITE_PATH . '/templates/' );
        raintpl::configure( 'cache_dir',    LDDLITE_PATH . '/cache/' );
        raintpl::configure( 'path_replace', false );

        return new raintpl;
    }

    public static function setting( $key, $esc = false ) {
        $l = self::load();
        $option = $l->get_setting( $key );
        if ( $esc ) $option = esc_attr( $option );
        return $option;
    }

    public static function attach( $listing ) {
        $l = self::load();
        $l->listing = $listing;
    }

    public static function pull() {
        $l = self::load();
        return $l->listing;
    }

}

/**
 * Start everything.
 */
ldl::load();


function ldl_get_default_settings() {
    $site_title = get_bloginfo( 'name' );
    $admin_email = get_bloginfo( 'admin_email' );

    $signature = <<<SIG


*****************************************
This is an automated message from {$site_title}
Please do not respond directly to this email
SIG;

    $email = array();

    $email['to_admin']   = <<<EM
<p><strong>A new listing is pending review!</strong></p>

<p>This submission is awaiting approval. Please visit the link to view and approve the new listing:</p>

<p>{approve_link}</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
    $email['on_submit']  = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing is pending approval.</p>
<p>Please review the following information for accuracy, as this is what will appear on our web site. If you see any errors, please contact us immediately at {directory_email}.</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
    $email['on_approve'] = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing has been approved! You can now view it online:</p>
<p>{link}</p>
EM;

    foreach ( $email as $key => $msg )
        $email[ $key ] = $msg . $signature;

    $defaults = apply_filters( 'lddlite_default_options', array(
        'directory_label'           => get_bloginfo( 'name' ),
        'directory_description'     => '',
        'directory_page'            => '',
        'disable_bootstrap'         => 0,
        'public_or_private'         => 1,
        'google_maps'               => 1,
        'email_admin'             => get_bloginfo( 'admin_email' ),
        'email_toadmin_subject'     => 'A new listing has been submitted for review!',
        'email_toadmin_body'        => $email['to_admin'],
        'email_onsubmit_subject'    => 'Your listing on ' . $site_title . ' is pending review!',
        'email_onsubmit_body'       => $email['on_submit'],
        'email_onapprove_subject'   => 'Your listing on ' . $site_title . ' was approved!',
        'email_onapprove_body'      => $email['on_approve'],
        'submit_use_tos'            => 0,
        'submit_tos'                => '',
        'submit_use_locale'         => 0,
        'submit_locale'             => 'US',
        'submit_require_address'    => 1,
        'allow_tracking_popup_done' => 0,
        'allow_tracking'            => 0,
    ) );

    return $defaults;
}