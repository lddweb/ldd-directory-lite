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

global $wpdb;

define( 'DIRL_VERSION',  '0.1.0' );

define( 'DIRL_PATH',     WP_PLUGIN_DIR.'/'.basename( dirname( __FILE__ ) ) );
define( 'DIRL_URL',      plugins_url().'/'.basename( dirname( __FILE__ ) ) );

define( 'DIRL_CSS',      DIRL_URL . '/public/css' );
define( 'DIRL_JS',       DIRL_PATH . '/public/js' );
define( 'DIRL_JS_URL',   DIRL_URL . '/public/js' );


define( 'DIRL_AJAX',         DIRL_URL . '/public/ajax.php' );
define( 'DIRL_TEMPLATES',    DIRL_PATH . '/includes/tpl' );
define( 'DIRL_TPL_EXT',      'tpl' );


require_once( DIRL_PATH . '/includes/settings.php' );
require_once( DIRL_PATH . '/includes/admin.php' );
require_once( DIRL_PATH . '/includes/email.php' );
require_once( DIRL_PATH . '/public/display.php' );



add_action( 'wp_enqueue_scripts', 'dirl_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'dirl_enqueue_scripts' );

function dirl_enqueue_scripts()
{
    wp_enqueue_script( 'dirl-js', DIRL_JS_URL . '/lite.js', array( 'jquery' ), DIRL_VERSION );
    // @TODO: This should be reduced in scope so that it only enqueues where necessary.
    // @TODO: Right now it's just easier to plug it in.
    wp_enqueue_style( 'dirl-styles', DIRL_CSS . '/styles.css', array(), DIRL_VERSION );
}




add_action( 'admin_init', 'dirl_options_init' );


add_shortcode( 'business_directory', 'dirl_display_directory' );

register_activation_hook( __FILE__, 'dirl_single_activation' );


function dirl_single_activation()
{

}






global $tables;
$tables = array(
    'main'  => $wpdb->prefix . 'lddbusinessdirectory',
    'doc'   => $wpdb->prefix . 'lddbusinessdirectory_docs',
    'cat'   => $wpdb->prefix . 'lddbusinessdirectory_cats'
);








/**
 * Load plugin textdomain
 * @since 1.3.13
 */
add_action('plugins_loaded', 'dirl_load_textdomain');
function dirl_load_textdomain()
{
    load_plugin_textdomain('lddbd', false, dirname(plugin_basename( __FILE__ )));
}


/**
 * Wrapper function to be used as a callback by admin_init action
 * These functions are all self contained at the moment for simplicity's sake. As the
 * plugin grows in size, if more settings are used I recommend wrapping this in a class
 * that can be called from anywhere.
 * @since 1.3.13
 *
 * @return void
 */
function dirl_options_init()
{

    /**
     * Register our plugins options group.
     * We could probably survive without validation at this stage, but
     * it doesn't hurt to be a little careful and have this outlined for
     * future options.
     */
    register_setting( 'writing', 'dirl_options', 'dirl_options_validate' );
    function dirl_options_validate($input)
    {
        global $dirl_options;

        $valid = $dirl_options;

        $valid['email_onsubmit'] = wp_filter_nohtml_kses( $input['email_onsubmit'] );
        $valid['email_onapprove'] = wp_filter_nohtml_kses( $input['email_onapprove'] );

        return $valid;
    }

    /**
     * Add these settings to the WordPress "Writing" page.
     */
    add_settings_section( 'dirl_settings', 'LDD Business Directory Email', 'dirl_callback_settings', 'writing' );
    function dirl_callback_settings()
    {
        echo '<p>'.__( 'Configure Business Directory email settings here.', 'lddbd' ).'</p>';
    }

    /**
     * Add a text field to define the submission email subject line.
     */
    add_settings_field( 'dirl_email_onsubmit', '<label for="email_onsubmit">' . __( 'Submission Subject' , 'lddbd' ) . '</label>', 'dirl_callback_onsubmit', 'writing', 'dirl_settings' );
    function dirl_callback_onsubmit()
    {
        $dirl_options = dirl_get_options();
        echo '<input type="text" size="80" name="dirl_options[email_onsubmit]" value="'.esc_attr( $dirl_options['email_onsubmit'] ).'" />';
    }

    /**
     * Add a text field to define the approval email subject line.
     */
    add_settings_field( 'dirl_email_onapprove', '<label for="email_onapprove">' . __( 'Approved Subject' , 'lddbd' ) . '</label>', 'dirl_callback_onapprove', 'writing', 'dirl_settings' );
    function dirl_callback_onapprove()
    {
        $dirl_options = dirl_get_options();
        echo '<input type="text" size="80" name="dirl_options[email_onapprove]" value="'.esc_attr( $dirl_options['email_onapprove'] ).'" />';
    }

}


/**
 * Return business directory options.
 * Very rudimentary functionality to return an array of options from anywhere
 * within the plugin without using a litter of global declarations. Defines
 * default array of options if none are found.
 * @since 1.3.13
 * @todo move $defaults to plugin initialization
 *
 * @return array
 */
function dirl_get_options()
{

    $defaults = array(
        'email_onsubmit'    => 'Your directory listing was successfully submitted!',
        'email_onapprove'   => 'Your directory listing was approved!'
    );

    $dirl_options = wp_parse_args( get_option( 'dirl_options', array() ), $defaults );

    return $dirl_options;
}










// Installation function for LDD Business Directory plugin. Sets up the tables in the database for main, documents, and categories.
function lddbd_install()
{
	global $tables;
	
	// Creates the table that contains all the primary information regarding each business.
	$main_table = "CREATE TABLE {$tables['main']} (
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	createDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	updateDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	name TINYTEXT NOT NULL,
	description TEXT NOT NULL,
	categories TEXT NOT NULL,
	address_street TEXT NOT NULL,
	address_city TEXT NOT NULL,
	address_state TEXT,
	address_zip CHAR(15),
	address_country TEXT NOT NULL,
	phone CHAR(15) NOT NULL,
	fax CHAR(15),
	email VARCHAR(55) DEFAULT '' NOT NULL,
	contact tinytext NOT NULL,
	url VARCHAR(55) DEFAULT '' NOT NULL,
	facebook VARCHAR(256),
	twitter VARCHAR(256),
	linkedin VARCHAR(256),
	promo ENUM('true', 'false') NOT NULL,
	promoDescription text DEFAULT '',
	logo VARCHAR(256) DEFAULT '' NOT NULL,
	login text NOT NULL,
	password VARCHAR(64) NOT NULL,
	approved ENUM('true', 'false') NOT NULL,
	other_info TEXT,
	UNIQUE KEY id (id)
	);";
	
	// Creates the table that contains documentation/descriptions.
	$doc_table = "CREATE TABLE {$tables['doc']} (
	doc_id BIGINT(20) NOT NULL AUTO_INCREMENT,
	bus_id BIGINT(20) NOT NULL,
	doc_path VARCHAR(256) NOT NULL,
	doc_name TINYTEXT NOT NULL,
	doc_description LONGTEXT,
	PRIMARY KEY  (doc_id),
	FOREIGN KEY (bus_id) REFERENCES {$tables['main']}(id)
	);";
	
	// Creates the table that contains a listing of all the categories.
	$cat_table = "CREATE TABLE {$tables['cat']}(
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	name TINYTEXT NOT NULL,
	count BIGINT(20) NOT NULL,
	PRIMARY KEY  (id)
	);";


   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($main_table);
   dbDelta($doc_table);
   dbDelta($cat_table);

}




if ( !function_exists( 'md' ) )
{
    function md( $var, $print = true, $die = true )
    {
        echo '<pre>';
        if ( $print )
            print_r( $var );
        else
            var_dump( $var );
        echo '</pre>';
        if ( $die ) die;
    }
}