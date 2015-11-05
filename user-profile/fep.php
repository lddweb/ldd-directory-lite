<?php
/*
Plugin Name: Frontend Edit Profile
Version: 1.0.3
Description: Add edit profile to your post or page
Author: Abdul Ibad
Author URI: http://www.dulabs.com
Plugin URI: http://www.dulabs.com/wp-frontend-edit-profile
License: GPL
*/

/*Version Check*/
global $wp_version;
$exit_msg = "Dude, upgrade your stinkin Wordpress Installation.";
if(version_compare($wp_version, "2.8", "<")) { exit($exit_msg); }

define ('FEP','frontend-edit-profile');

define('FEP_VERSION', '1.0.2');

define("FEP_URL", WP_PLUGIN_URL . '/frontend-edit-profile/' );

include_once( realpath(dirname(__FILE__))."/widget.php");

if(!(function_exists('get_user_to_edit'))){
	require_once(ABSPATH.'/wp-admin/includes/user.php');
}

if(!(function_exists('_wp_get_user_contactmethods'))){
	require_once(ABSPATH.'/wp-includes/registration.php');
}

class FRONTEND_EDIT_PROFILE{
	
	var $wp_error;
		
	function __construct(){
		
		register_activation_hook(__FILE__, array($this,'default_settings'));
		add_action('admin_init', array($this,'settings_init'));
		add_shortcode('editprofile',array($this,'shortcode'));
		add_shortcode('EDITPROFILE',array($this,'shortcode'));
		add_shortcode('LOGIN',array($this,'shortcode'));

		add_action('widgets_init', array($this,'_widget')); 
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
		// Additing Action hook widgets_init
		


	}

	function _widget() {
		register_widget( 'fep_widget' );
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
		
		$pass_hint = (get_option('fep_pass_hint')=="on")? " checked=\"checked\"" : " ";
		
		$show_text_pass_hint = (get_option('fep_custom_pass_hint')=="on")? true : false;
		
		$custom_pass_hint = (get_option('fep_custom_pass_hint')=="on")? " checked=\"checked\"" : " ";
		
		$pass_indicator = (get_option('fep_pass_indicator')=="on")? " checked=\"checked\"" : " ";
		
		$biographical = (get_option('fep_biographical')=="on")? " checked=\"checked\"" : " ";
		
		$login_form = (get_option('fep_loginform')=="on") ? " checked=\"checked\"" : " ";
	
		$contact_methods = get_option("fep_contact_methods");
		
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
		
		$src = $plugin_url.'/fep.js';
	
		wp_enqueue_script( 'password-strength-meter' );
		wp_enqueue_script('fep-forms-script',$src,'','1.0');
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

$fep = new FRONTEND_EDIT_PROFILE;

?>