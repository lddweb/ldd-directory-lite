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


define( 'LDDLITE_VERSION',      '0.1' );

define( 'LDDLITE_PATH',         WP_PLUGIN_DIR.'/'.basename( dirname( __FILE__ ) ) );
define( 'LDDLITE_URL',          plugins_url().'/'.basename( dirname( __FILE__ ) ) );

define( 'LDDLITE_AJAX',         LDDLITE_URL . '/includes/ajax.php' );

define( 'LDDLITE_POST_TYPE',    'directory_listings' );
define( 'LDDLITE_TAX_CAT',      'listing_category' );
define( 'LDDLITE_TAX_TAG',      'listing_tag' );

define( 'LDDLITE_TEMPLATES',    LDDLITE_PATH . '/templates' );
define( 'LDDLITE_TPL_EXT',      'tpl' );





final class _LDD_Directory_Lite {

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

        if ( !isset( self::$_instance ) && !( self::$_instance instanceof _LDD_Business_Directory ) ) {
            self::$_instance = new self;
            self::$_instance->include_files();
            self::$_instance->populate_options();
            self::$_instance->action_filters();
        }

        return self::$_instance;

    }



    public function include_files() {
        require_once( LDDLITE_PATH . '/includes/post-types.php' );
        require_once( LDDLITE_PATH . '/includes/functions.php' );
        require_once( LDDLITE_PATH . '/includes/metaboxes.php' );
        require_once( LDDLITE_PATH . '/includes/email.php' );
        require_once( LDDLITE_PATH . '/includes/views.php' );

        if ( is_admin() )
            require_once( LDDLITE_PATH . '/includes/admin.php' );
    }



    public function populate_options() {

        $defaults = apply_filters( 'lddlite_default_options', array(
         // 'version'           => LDDLITE_VERSION,
            'public_or_private' => 1,
            'google_maps'       => 1,
            'email_onsubmit'    => 'Your directory listing was successfully submitted!',
            'email_onapprove'   => 'Your directory listing was approved!'
        ) );

        $options = wp_parse_args(
            get_option( 'lddlite-options' ),
            $defaults );

        if ( !isset( $options['version'] ) || version_compare( LDDLITE_VERSION, $options['version'], '>' ) )
            require_once( LDDLITE_PATH . '/upgrade.php' );

        $this->options = $options;

    }


    public function action_filters() {

        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        $basename = plugin_basename( __FILE__ );
        add_filter( 'plugin_action_links_' . $basename, array( $this, 'add_action_links' ) );

        add_shortcode( 'business_directory', 'lddlite_display_directory' );

        { // These all relate to our custom post types and dashboard UI
            add_action( 'init', 'lddlite_register__cpt_tax' );


            add_filter( 'post_type_link', 'lddlite_filter_post_type_link', 10, 2 );

            add_filter( 'enter_title_here', 'lddlite_filter_enter_title_here' );
            add_filter( 'admin_post_thumbnail_html', 'lddlite_filter_admin_post_thumbnail_html' );

            add_action( 'admin_head', 'lddlite_action_directory_icon' );
            add_action( '_admin_menu', 'lddlite_action_submenu_name' );
        }

        add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_scripts_global' ) );
        add_action( 'admin_enqueue_scripts', array( $this, '_enqueue_scripts_global' ) );
        add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_scripts' ) );

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


    public function _enqueue_scripts_global() {
        wp_enqueue_script( 'lddlite-js', LDDLITE_URL . '/public/js/lite.js', array( 'jquery' ), LDDLITE_VERSION, true );
        wp_enqueue_style( 'lddlite-styles', LDDLITE_URL . '/public/css/style.css', false, LDDLITE_VERSION );
	    wp_enqueue_style( 'yui-pure', '//yui.yahooapis.com/pure/0.4.2/pure-min.css', false, '0.4.2' );
    }

    public function _enqueue_scripts() {
        if ( isset( $_GET['show'] ) && 'submit' == $_GET['show'] ) {
            wp_enqueue_script( 'lddlite-submit-form-js', LDDLITE_URL . '/public/js/responsiveslides.js', array( 'jquery' ), '1.54' );
            //wp_enqueue_script( 'jquery-form-js', LDDLITE_URL . '/public/js/jquery.form.min.js', array( 'jquery' ), '20140218', 1 );
        }
    }



    public function slug() {
        return self::$_slug;
    }


}


function lddlite() {
    return _LDD_Directory_Lite::get_in();
}

lddlite();


function lddslug() {
    static $slug;

    if ( !isset( $slug ) ) {
        $lddlite = lddlite();
        $slug = $lddlite->slug();
    }

    return $slug;
}


/**
 * * @ignore
 */
function lddlite_update_meta() {

    $posts = get_posts( array(
        'posts_per_page'    => -1,
        'post_type'         => LDDLITE_POST_TYPE,
    ) );

    foreach ( $posts as $post ) {

        $id = $post->ID;

        $address = get_post_meta( $id, '_lddlite_address', 1 );
        $contact = get_post_meta( $id, '_lddlite_contact', 1 );
        $urls = get_post_meta( $id, '_lddlite_urls', 1 );

        if ( !empty( $address ) && is_array( $address ) ) {
            add_post_meta( $id, '_lddlite_address_one', ( isset( $address['address_one'] ) ? $address['address_one'] : '' ) );
            add_post_meta( $id, '_lddlite_address_two', ( isset( $address['address_two'] ) ? $address['address_two'] : '' ) );
            add_post_meta( $id, '_lddlite_address_country', ( isset( $address['country'] ) ? $address['country'] : '' ) );
            add_post_meta( $id, '_lddlite_address_subdivision', ( isset( $address['subdivision'] ) ? $address['subdivision'] : '' ) );
            add_post_meta( $id, '_lddlite_address_city', ( isset( $address['city'] ) ? $address['city'] : '' ) );
            add_post_meta( $id, '_lddlite_address_post_code', ( isset( $address['post_code'] ) ? $address['post_code'] : '' ) );

            delete_post_meta( $id, '_lddlite_address' );
        }

        if ( !empty( $contact ) && is_array( $contact ) ) {
            add_post_meta( $id, '_lddlite_contact_primary', ( isset( $contact['primary'] ) ? $contact['primary'] : '' ) );
            add_post_meta( $id, '_lddlite_contact_email', ( isset( $contact['email'] ) ? $contact['email'] : '' ) );
            add_post_meta( $id, '_lddlite_contact_phone', ( isset( $contact['phone'] ) ? $contact['phone'] : '' ) );
            add_post_meta( $id, '_lddlite_contact_fax', ( isset( $contact['fax'] ) ? $contact['fax'] : '' ) );

            delete_post_meta( $id, '_lddlite_contact' );
        }

        if ( !empty( $urls ) && is_array( $urls ) ) {
            add_post_meta( $id, '_lddlite_urls_website', ( isset( $urls['website'] ) ? $urls['website'] : '' ) );
            add_post_meta( $id, '_lddlite_urls_social_facebook', ( isset( $urls['social']['facebook'] ) ? $urls['social']['facebook'] : '' ) );
            add_post_meta( $id, '_lddlite_urls_social_twitter', ( isset( $urls['social']['twitter'] ) ? $urls['social']['twitter'] : '' ) );
            add_post_meta( $id, '_lddlite_urls_social_linkedin', ( isset( $urls['social']['linkedin'] ) ? $urls['social']['linkedin'] : '' ) );

            delete_post_meta( $id, '_lddlite_urls' );
        }
    }

}

