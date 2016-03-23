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
 * Version:           0.8.60
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
define('LDDLITE_VERSION', '0.8.60');

define('LDDLITE_PATH', dirname(__FILE__));
define('LDDLITE_URL', rtrim(plugin_dir_url(__FILE__), '/'));

define('LDDLITE_POST_TYPE', 'directory_listings');
define('LDDLITE_TAX_CAT', 'listing_category');
define('LDDLITE_TAX_TAG', 'listing_tag');

define('LDDLITE_PFX', 'lddlite');
define('LDDLITE_NOLOGO', plugin_dir_url(__FILE__) . 'public/images/noimage.png');

if(!(function_exists('get_user_to_edit'))){
	require_once(ABSPATH.'/wp-admin/includes/user.php');
}

if(!(function_exists('_wp_get_user_contactmethods'))){
	require_once(ABSPATH.'/wp-includes/registration.php');
}

/**
 * Flush the rewrites for custom post types
 */
register_activation_hook(__FILE__, 'install_ldd_directory_lite');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');


function install_ldd_directory_lite() {
	global $wp_rewrite;

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
                                     'post_title'     => __('Submit a Listing', 'ldd-directory-lite'),
                                     'post_name'      => 'submit-listing',
                                     'post_content'   => '[directory_submit]',
                                     'post_status'    => 'publish',
                                     'post_type'      => 'page',
                                     'post_parent'    => $directory,
                                     'comment_status' => 'closed',
                                 ));

        $manage = wp_insert_post(array(
                                     'post_title'     => __('Manage Listings', 'ldd-directory-lite'),
                                     'post_name'      => 'manage-listings',
                                     'post_content'   => '[directory_manage]',
                                     'post_status'    => 'publish',
                                     'post_type'      => 'page',
                                     'post_parent'    => $directory,
                                     'comment_status' => 'closed',
                                 ));

        $ldl_settings['directory_front_page']  = $directory;
        $ldl_settings['directory_submit_page'] = $submit;
        $ldl_settings['directory_manage_page'] = $manage;
    }

    foreach (ldl_get_registered_settings() as $tab => $settings) {
        foreach ($settings as $option) {

            if ('checkbox' == $option['type'] && !empty($option['std'])) {
                $ldl_settings[ $option['id'] ] = '1';
            }

        }
    }

    update_option('lddlite_settings', $ldl_settings);

	flush_rewrite_rules(true);
	$wp_rewrite->flush_rules( false );
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

        add_action('init', array($this, 'load_plugin_textdomain'));

        // ldd business directory import
        $plugin = 'ldd-business-directory/lddbd_core.php';
		
		$dir = dirname(__FILE__);
        $plugin_path = WP_PLUGIN_DIR  . '/' . $plugin;

        if (file_exists($plugin_path) && false == get_option('lddlite_imported_from_original'))
            require_once(LDDLITE_PATH . '/import-lddbd.php');

        $this->settings = get_option('lddlite_settings');

        $version = get_option('lddlite_version');

        if (!$version) {
            update_option('lddlite_version', LDDLITE_VERSION);
        }
        else if ($version && LDDLITE_VERSION != $version) {
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

        if (ldl()->get_option('allow_tracking')) {
            add_action('init', array('ldd_directory_lite_tracking', 'get_instance'));
        }

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

class USER_EDIT_FONT_PROFILE{
	
	var $wp_error;
		
	function __construct(){
		
		register_activation_hook(__FILE__, array($this,'default_settings'));
		add_action('admin_init', array($this,'settings_init'));
		add_shortcode('EDITPROFILE',array($this,'shortcode'));
		add_shortcode('LOGIN',array($this,'shortcode'));

		add_action('admin_menu',array($this,'admin_menu'));	
		add_action('wp_print_styles',array($this,'form_style'));
		add_action('wp_print_scripts', array($this,'form_script'));
		add_action('init',array($this,'process_login_form'));	
		add_action('fep_loginform', array($this,'login_form'));
		
		add_filter('fep_contact_methods', array($this,'contact_methods'));
		add_filter('logout_url', array($this,'logout_url'));
		add_filter('login_url', array($this,'login_url'));
		add_filter('lostpassword_url', array($this,'lostpassword_url'));
		add_filter('user_contactmethods', array($this,'add_contact_methods'));

	}

	function plugin_url(){
		$currentpath = dirname(__FILE__);
		$siteurl = get_option('siteurl').'/';
		$plugin_url = str_replace(ABSPATH,$siteurl,$currentpath);
		
		return $plugin_url;
	}
	
	
	function admin_menu(){
		$mypage = add_options_page('Frontend Edit Profile','Frontend Edit Profile','administrator','fep',array($this,'options_page'));
		
		add_action('admin_print_styles-'.$mypage,array($this,'admin_style'));
		add_action('admin_print_scripts-'.$mypage,array($this,'admin_script'));
	}
	
	function default_settings(){
		
		$siteurl = get_option('siteurl');
		
		$logout_url = $siteurl.'?action=logout&redirect_to='.$siteurl;
		$login_url = wp_login_url();
		
		$login_text = "You need <a href=\"%LOGIN_URL%\">login</a> to access this page";
		
		add_option('fep_pass_hint','off','','','yes');
		add_option('fep_custom_pass_hint','off','','yes');
		add_option('fep_text_pass_hint','','','yes');
		add_option('fep_pass_indicator','on','','yes');
		add_option('fep_biographical','off','','yes');
		add_option('fep_notlogin',$login_text,'','yes');
		add_option('fep_contact_methods','','','yes');
		add_option('fep_loginform','off','','yes');
		add_option('fep_logouturl',$logout_url,'','yes');
		add_option('fep_loginurl','','','yes');
		add_option('fep_lostpasswordurl','','','yes');
	}
	
	function settings_init(){
		register_setting('fep_options','fep_pass_hint','');
		register_setting('fep_options','fep_custom_pass_hint','');
		register_setting('fep_options','fep_text_pass_hint','');
		register_setting('fep_options','fep_pass_indicator','');
		register_setting('fep_options','fep_biographical','');
		register_setting('fep_options','fep_notlogin','');
		register_setting('fep_options','fep_contact_methods','');
		register_setting('fep_options','fep_loginform','');
		register_setting('fep_options','fep_logouturl','');
		register_setting('fep_options','fep_loginurl','');
		register_setting('fep_options','fep_lostpasswordurl','');
		register_setting('fep_options','fep_registerurl','');
	}
	
	function add_contact_methods()
	{
		$user_contact['skype'] = __( 'Skype' ); 
		$user_contact['twitter'] = __( 'Twitter' );
		$user_contact['yahoo'] = __( 'Yahoo' );
		$user_contact['aim'] = __( 'AIM' ); 
		
		return $user_contact;
	}

	function login_url( $url ){
		$fep_url = get_option('fep_loginurl');
		
		if(!empty($fep_url)){
			$url = $fep_url;
		}
		
		return $url;
	}
	
	function logout_url( $url ){
		
		if(is_admin()) return $url;
		
		$fep_url = get_option('fep_logouturl');
		
		if(!empty($fep_url)){
			$url = $fep_url;
		}
		
		return $url;
	}
	
	function lostpassword_url( $url ){
		$fep_url = get_option('fep_lostpasswordurl');
		
		if(!empty($fep_url)){
			$url = $fep_url;
		}
		
		return $url;
	}
	
	function contact_methods(){
		
		$contact_methods = _wp_get_user_contactmethods();
		$fep_contact_methods = get_option('fep_contact_methods');
		
					if(!(is_array($fep_contact_methods))){
                                            $fep_contact_methods = array();
                                         }

		$new_contact_methods = array();
	
		foreach($contact_methods as $name => $desc){
			
			if(!in_array(strtolower($name),$fep_contact_methods)) continue;
			
			$new_contact_methods[] = $name;
		}
		
		return $new_contact_methods;
	}
	
	//
	// http://www.webcheatsheet.com/PHP/get_current_page_url.php
	//
	
	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
	function options_page(){
		
		$pass_hint 			 = (get_option('fep_pass_hint')=="on")? " checked=\"checked\"" : " ";
		$show_text_pass_hint = (get_option('fep_custom_pass_hint')=="on")? true : false;
		$custom_pass_hint    = (get_option('fep_custom_pass_hint')=="on")? " checked=\"checked\"" : " ";
		$pass_indicator 	 = (get_option('fep_pass_indicator')=="on")? " checked=\"checked\"" : " ";
		$biographical 		 = (get_option('fep_biographical')=="on")? " checked=\"checked\"" : " ";
		$login_form 		 = (get_option('fep_loginform')=="on") ? " checked=\"checked\"" : " ";
		$contact_methods 	 = get_option("fep_contact_methods");
		
		if(!(is_array($contact_methods))){
			$contact_methods = array();
		}
		
		include_once ( realpath ( dirname(__FILE__) )."/admin_form.php" );
	}
	
	function admin_style(){
					/* Add Styles to Admin*/
	}
	
	function admin_script(){
			/* Add Scripts to Admin*/
	}
	
	function form_style() {

		$style = get_option('fep_style');
		$passmeter = get_option('fep_passmeter_style');
		
		if(!$style) {
			$src = FEP_URL .'fep.css';
			wp_register_style('fep-forms-style',$src,'',FEP_VERSION);
			wp_enqueue_style('fep-forms-style');
		} else {
			$src = $style;
			wp_register_style('fep-forms-custom-style',$src,'',FEP_VERSION);
			wp_enqueue_style('fep-forms-custom-style');
		}
	
	}
	
	function form_script(){
		
		$plugin_url = self::plugin_url();
		
		//$src = $plugin_url.'/fep.js';
	
		wp_enqueue_script( 'password-strength-meter' );
		//wp_enqueue_script('fep-forms-script',$src,'','1.0');
	}
	
	function process_form( $atts ){
		
		global $wpdb;
		
		error_reporting(0);
		
		$errors = new WP_ERROR();
		
		$current_user = wp_get_current_user();
		
		$user_id = $current_user->ID;
		
		do_action('personal_options_update', $user_id);
		
		$user = get_userdata( $user_id );
		
		// Update the email address in signups, if present.
		if ( $user->user_login && isset( $_POST[ 'email' ] ) && is_email( $_POST[ 'email' ] ) && $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s", $user->user_login ) ) )
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s", $_POST[ 'email' ], $user_login ) );

		// WPMU must delete the user from the current blog if WP added him after editing.
		$delete_role = false;
		$blog_prefix = $wpdb->get_blog_prefix();
		if ( $user_id != $current_user->ID ) {
			$cap = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = '{$user_id}' AND meta_key = '{$blog_prefix}capabilities' AND meta_value = 'a:0:{}'" );
			if ( null == $cap && $_POST[ 'role' ] == '' ) {
				$_POST[ 'role' ] = 'contributor';
				$delete_role = true;
			}
		}
		if ( !isset( $errors ) || ( isset( $errors ) && is_object( $errors ) && false == $errors->get_error_codes() ) )
			$errors = edit_user($user_id);
		if ( $delete_role ) // stops users being added to current blog when they are edited
			delete_user_meta( $user_id, $blog_prefix . 'capabilities' );
		
		if(is_wp_error( $errors ) ) {
			$message = $errors->get_error_message();
			$style = "error";
		}else{
			$message = __("<strong>Success</strong>: Profile updated");
			$style = "success";
		}
			$output  = "<div id=\"fep-message\" class=\"fep-message-".$style."\">".$message.'</div>';
			$output .= $this->build_form();
			
			return $output; 
	}
	
	function build_form( $data="" ){
		
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$profileuser = get_user_to_edit($user_id);
		$show_pass_hint = (get_option('fep_pass_hint')=="on")? true:false;
		$show_pass_indicator = (get_option('fep_pass_indicator')=="on")? true:false;
		$show_biographical = (get_option('fep_biographical')=="on")? true:false;
		
		ob_start();
		include_once(realpath(dirname(__FILE__))."/_form.php");
		$form = ob_get_contents();
		ob_end_clean();
		
		return $form;
	}
	
	function process_login_form(){
		
		if(isset($_GET['action'])){
			$action = strtoupper($_GET['action']);
			switch($action){
				case "LOGOUT":
					if(is_user_logged_in()){
						wp_logout();
						$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : get_bloginfo('url').'/wp-login.php?loggedout=true';
						wp_safe_redirect( $redirect_to );
						exit();
					}else{
						$url = get_option('siteurl');
						wp_safe_redirect($url);
					}	
					
				break;
			}
		}
		
		if(!isset($_POST['fep_login'])) return;
		
		$userlogin = $_POST['log'];
		$userpass = $_POST['pwd'];
		$remember = $_POST['rememberme'];
		$creds = array();
		$creds['user_login'] = $userlogin;
		$creds['user_password'] = $userpass;
		$creds['remember'] = $remember;
		
		if(empty($userlogin)){
			$this->wp_error = new WP_ERROR("invalid_username",__('<strong>ERROR</strong>: Empty username'));
			return;
		}
		
		if(empty($userpass)){
			$this->wp_error = new WP_ERROR("incorrect_password",__('<strong>ERROR</strong>: Empty password'));
			return;
		}
		
		$user = wp_signon( $creds, false );
		
		if ( is_wp_error($user) ){
			$error_code = $user->get_error_code();
			switch(strtoupper($error_code)){
				case "INVALID_USERNAME":
				$this->wp_error = new WP_ERROR("invalid_username", __('<strong>ERROR</strong>: Invalid username'));
				break;
				case "INCORRECT_PASSWORD":
				$this->wp_error = new WP_ERROR("incorret_password", __('<strong>ERROR</strong>: Incorrect password'));
				break;
				default:
					$this->wp_error = $user;
				break;
			}
			
			return;
		}else{	
		 	$redirect = $this->curPageURL();
			wp_redirect($redirect);
			exit;
		}
	
	}
	
	function login_form( $url="" ){
		
		$wp_error = $this->wp_error;
			
		if( is_wp_error($wp_error)){
			echo "<div class=\"fep-message-error\">".$wp_error->get_error_message()."</div>";
		}
		
		include_once( realpath ( dirname(__FILE__) ). "/login_form.php" );
	}
	
	function basic_form( $atts ){
		
		$text = get_option("fep_notlogin");
		$show_loginform = (get_option('fep_loginform') == "on")? true : false;	
			
		if( !(is_user_logged_in()) ){
			
			$login_url = wp_login_url();
			$lostpassword_url = wp_lostpassword_url();
			$text = str_replace("%LOGIN_URL%",$login_url,$text);
			$text = str_replace("%LOSTPASSWORD_URL%",$lostpassword_url,$text);
			
			_e($text);
			if($show_loginform){
				echo "<br /><br />";
				do_action('fep_loginform');
			}
			return;
		}
		
		if(isset($_POST['user_id'])) {
			$output = self::process_form($atts);	
			return $output;
		} else {
			$data = array();
			$form = self::build_form( $data );
			return $form;		
		}
		

	}
	
	function shortcode( $atts ){
		$function = self::basic_form( $atts );
		return $function;
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


/*
 * =====================================
 * Custom Pagination for inner listings
 * =====================================
 * */

function ldd_pagination($pages = '', $range = 4) {
	global $paged;

	$showitems = ($range * 2)+1;

	if(empty($paged)) $paged = 1;

	if($pages == '')  {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if(!$pages)  {
			$pages = 1;
		}
	}

	if(1 != $pages)  {
		echo "<div class=\" ldd_listing_pagination clearfix \"><span>Page ".$paged." of ".$pages."</span>";
		if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
		if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";

		for ($i=1; $i <= $pages; $i++) {
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
				echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
			}
		}

		if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
		echo "</div>\n";
	}
}

function validate_dyn_slugs() {
	
	 $taxonomy_slug = ldl()->get_option('directory_taxonomy_slug', 'listings');
   	 $post_type_slug = ldl()->get_option('directory_post_type_slug', 'listing');
	 if(strtolower($taxonomy_slug) == strtolower($post_type_slug)):
		add_action( 'admin_notices', 'slugs_error_notice' ); 
	 endif;
}
function slugs_error_notice() {
	$class = "error";
	$message = "Error: Taxonomy and Post Type Slugs cannot be same. Please go to <a href='".admin_url()."edit.php?post_type=directory_listings&page=lddlite-settings'>settings</a> and update the slugs.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}

/** Das boot */
if (!defined('WP_UNINSTALL_PLUGIN'))
    ldl();
	//$fep = new USER_EDIT_FONT_PROFILE;
validate_dyn_slugs();

/*
 *====================================================
 * Update defualt search query for adding meta search
 *====================================================
 */
function ldd_meta_search_join ($join){
    global $wpdb;
	
		if( is_search() and $_REQUEST["post_type"] == "directory_listings") {
        	$join .=' LEFT JOIN '.$wpdb->postmeta.' ON '.$wpdb->posts.'.ID = '.$wpdb->postmeta.'.post_id ';
		}
	return $join;
}

function ldd_meta_search_where( $where ){
    global $wpdb;
		if( is_search() and $_REQUEST["post_type"] == "directory_listings") {
		  $where = preg_replace( "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
		   						 "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
		}
	return $where;
}

function ldd_meta_search_groupby($groupby) {
  global $wpdb;

  if( !is_search() or $_REQUEST["post_type"] != "directory_listings") { return $groupby; }

  $customgroupby = "{$wpdb->posts}.ID";

  if( preg_match( "/$customgroupby/", $groupby )) { return $groupby; }

  if( !strlen(trim($groupby))) {
    return $customgroupby;
  }

  return $groupby . ", " . $customgroupby;
}

add_filter('posts_join', 'ldd_meta_search_join' );
add_filter('posts_where', 'ldd_meta_search_where' );
add_filter('posts_groupby', 'ldd_meta_search_groupby' );

/*
 * ==============================================================
 * Login , Registration, Forgot Password and Account Activation
 * Shortcodes
 * ==============================================================
 * */

// user registration login form
function ldd_registration_form() {
	// only show the registration form to non-logged-in members
	if(!is_user_logged_in()) {
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = ldd_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
		return $output;
	}
}
add_shortcode('ldd_register_form', 'ldd_registration_form');

// user login form
function ldd_login_form() {

	if(!is_user_logged_in()) {
		$output = ldd_login_form_fields();
	} else {
		// could show some logged in user info here
		// $output = 'user info here';
	}
	return $output;
}
add_shortcode('ldd_login_form', 'ldd_login_form');


// registration form fields
function ldd_registration_form_fields() {

	ob_start(); ?>
	<h3 class="pippin_header"><?php _e('Register New Account'); ?></h3>
	<?php
	// show any error messages after form submission
	pippin_show_error_messages(); ?>

	<form id="pippin_registration_form" class="pippin_form" action="" method="POST">
		<fieldset>
			<p>
				<label for="pippin_user_Login"><?php _e('Username'); ?></label>
				<input name="pippin_user_login" id="pippin_user_login" class="required" type="text"/>
			</p>
			<p>
				<label for="pippin_user_email"><?php _e('Email'); ?></label>
				<input name="pippin_user_email" id="pippin_user_email" class="required" type="email"/>
			</p>
			<p>
				<label for="pippin_user_first"><?php _e('First Name'); ?></label>
				<input name="pippin_user_first" id="pippin_user_first" type="text"/>
			</p>
			<p>
				<label for="pippin_user_last"><?php _e('Last Name'); ?></label>
				<input name="pippin_user_last" id="pippin_user_last" type="text"/>
			</p>
			<p>
				<label for="password"><?php _e('Password'); ?></label>
				<input name="pippin_user_pass" id="password" class="required" type="password"/>
			</p>
			<p>
				<label for="password_again"><?php _e('Password Again'); ?></label>
				<input name="pippin_user_pass_confirm" id="password_again" class="required" type="password"/>
			</p>
			<p>
				<input type="hidden" name="pippin_register_nonce" value="<?php echo wp_create_nonce('pippin-register-nonce'); ?>"/>
				<input type="submit" value="<?php _e('Register Your Account'); ?>"/>
			</p>
		</fieldset>
	</form>
	<?php
	return ob_get_clean();
}

// login form fields
function ldd_login_form_fields() {

	ob_start(); ?>
	<h3 class="pippin_header"><?php _e('Login'); ?></h3>

	<?php
	// show any error messages after form submission
	pippin_show_error_messages(); ?>

	<form id="pippin_login_form"  class="pippin_form"action="" method="post">
		<fieldset>
			<p>
				<label for="pippin_user_Login">Username</label>
				<input name="pippin_user_login" id="pippin_user_login" class="required" type="text"/>
			</p>
			<p>
				<label for="pippin_user_pass">Password</label>
				<input name="pippin_user_pass" id="pippin_user_pass" class="required" type="password"/>
			</p>
			<p>
				<input type="hidden" name="pippin_login_nonce" value="<?php echo wp_create_nonce('pippin-login-nonce'); ?>"/>
				<input id="pippin_login_submit" type="submit" value="Login"/>
			</p>
		</fieldset>
	</form>
	<?php
	return ob_get_clean();
}

// logs a member in after submitting a form
function ldd_login_member() {

	if(isset($_POST['pippin_user_login']) && wp_verify_nonce($_POST['pippin_login_nonce'], 'pippin-login-nonce')) {

		// this returns the user ID and other info from the user name
		$user = get_user_by( 'login', $_POST['pippin_user_login'] );

		if(!$user) {
			// if the user name doesn't exist
			pippin_errors()->add('empty_username', __('Invalid username'));
		}

		if(!isset($_POST['pippin_user_pass']) || $_POST['pippin_user_pass'] == '') {
			// if no password was entered
			pippin_errors()->add('empty_password', __('Please enter a password'));
		}

		// check the user's login with their password
		if(!wp_check_password($_POST['pippin_user_pass'], $user->user_pass, $user->ID)) {
			// if the password is incorrect for the specified user
			pippin_errors()->add('empty_password', __('Incorrect password'));
		}

		// retrieve all error messages
		$errors = pippin_errors()->get_error_messages();

		// only log the user in if there are no errors
		if(empty($errors)) {
			wp_set_auth_cookie($user->ID,true);
			wp_set_current_user($user->ID, $_POST['pippin_user_login']);
			do_action('wp_login', $_POST['pippin_user_login']);

			wp_redirect(home_url()); exit;
		}
	}
}
add_action('init', 'ldd_login_member');

// register a new user
function ldd_add_new_member() {
	if (isset( $_POST["pippin_user_login"] ) && wp_verify_nonce($_POST['pippin_register_nonce'], 'pippin-register-nonce')) {
		$user_login		= $_POST["pippin_user_login"];
		$user_email		= $_POST["pippin_user_email"];
		$user_first 	= $_POST["pippin_user_first"];
		$user_last	 	= $_POST["pippin_user_last"];
		$user_pass		= $_POST["pippin_user_pass"];
		$pass_confirm 	= $_POST["pippin_user_pass_confirm"];

		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');

		if(username_exists($user_login)) {
			// Username already registered
			pippin_errors()->add('username_unavailable', __('Username already taken'));
		}
		if(!validate_username($user_login)) {
			// invalid username
			pippin_errors()->add('username_invalid', __('Invalid username'));
		}
		if($user_login == '') {
			// empty username
			pippin_errors()->add('username_empty', __('Please enter a username'));
		}
		if(!is_email($user_email)) {
			//invalid email
			pippin_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($user_email)) {
			//Email address already registered
			pippin_errors()->add('email_used', __('Email already registered'));
		}
		if($user_pass == '') {
			// passwords do not match
			pippin_errors()->add('password_empty', __('Please enter a password'));
		}
		if($user_pass != $pass_confirm) {
			// passwords do not match
			pippin_errors()->add('password_mismatch', __('Passwords do not match'));
		}

		$errors = pippin_errors()->get_error_messages();

		// only create the user in if there are no errors
		if(empty($errors)) {

			$new_user_id = wp_insert_user(array(
							'user_login'		=> $user_login,
							'user_pass'	 		=> $user_pass,
							'user_email'		=> $user_email,
							'first_name'		=> $user_first,
							'last_name'			=> $user_last,
							'user_registered'	=> date('Y-m-d H:i:s'),
							'role'				=> 'subscriber'
					)
			);
			if($new_user_id) {
				// send an email to the admin alerting them of the registration
				wp_new_user_notification($new_user_id);

				// log the new user in
				wp_set_auth_cookie($new_user_id,true);
				wp_set_current_user($new_user_id, $user_login);
				do_action('wp_login', $user_login);

				// send the newly created user to the home page after logging them in
				wp_redirect(home_url()); exit;
			}

		}

	}
}
add_action('init', 'ldd_add_new_member');

// used for tracking error messages
function pippin_errors(){
	static $wp_error; // Will hold global variable safely
	return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function pippin_show_error_messages() {
	if($codes = pippin_errors()->get_error_codes()) {
		echo '<div class="pippin_errors">';
		// Loop error codes and display errors
		foreach($codes as $code){
			$message = pippin_errors()->get_error_message($code);
			echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		}
		echo '</div>';
	}
}