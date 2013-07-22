<?php

/* -------------- Settings Menu -------------- */


/* Call the html code */
add_action('admin_menu', 'lddbd_admin_menu');

//Build our administration menu in the backend.
function lddbd_admin_menu() {
	$lddbd_menu_page = add_menu_page( 'Business Directory', 'Directory Listings', 'manage_options', 'business_directory', 'lddbd_html_page');
	$lddbd_business_categories_page = add_submenu_page('business_directory', 'Business Directory Categories', 'Categories', 'manage_options', 'business_categories', 'lddbd_business_categories_page');
	$lddbd_add_business_page = add_submenu_page('business_directory', 'Add Business to Directory', 'Add Listing', 'manage_options', 'add_business_to_directory', 'lddbd_add_business_page');
	$lddbd_edit_business_page = add_submenu_page('business_directory', 'Edit Business', 'Edit Listing', 'manage_options', 'edit_business_in_directory', 'lddbd_edit_business_page');
	$lddbd_settings_page = add_submenu_page('business_directory', 'Business Directory Settings', 'Settings', 'manage_options', 'business_directory_settings', 'lddbd_settings_page');
	//add_options_page('Business Directory', 'Business Directory', 'manage_options', 'business_directory', 'lddbd_html_page');
	
	add_action( 'admin_init', 'register_mysettings' );
	add_action( 'admin_print_styles-' . $lddbd_menu_page, 'lddbd_styles' );
	add_action( 'admin_print_styles-' . $lddbd_settings_page, 'lddbd_styles' );
	add_action( 'admin_print_styles-' . $lddbd_add_business_page, 'lddbd_styles' );
	add_action( 'admin_print_styles-' . $lddbd_edit_business_page, 'lddbd_styles' );
	add_action( 'admin_print_styles-' . $lddbd_business_categories_page, 'lddbd_styles' );
}

function lddbd_styles(){
	wp_enqueue_style( 'lddbd_stylesheet' );
}

function register_mysettings() {
	//Register our settings
	register_setting( 'lddbd_settings_group', 'lddbd_options', 'lddbd_options_validate');
	add_settings_section('lddbd_main', '', 'lddbd_section_text', 'business_directory_settings');
	add_settings_field('lddbd_setting_one', 'Display "Submit Listing" Button', 'lddbd_setting_submit_button', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_two', 'Display "Login" Button', 'lddbd_setting_login_field', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_three', 'Display "Google Maps" Map', 'lddbd_setting_google_maps', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_four', 'Directory Title', 'lddbd_setting_directory_title', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_five', 'Welcome Message', 'lddbd_setting_welcome_message', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_six', 'Directory Label', 'lddbd_setting_directory_label', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_seven', 'Additional Information Sections', 'lddbd_setting_information_sections', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_eight', 'Categorize Entries', 'lddbd_setting_categorization', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_nine', 'Allow User Categorization', 'lddbd_setting_user_categorization', 'business_directory_settings', 'lddbd_main');
}

function lddbd_section_text() {
	echo '<p>Edit the settings for your directory.</p>';
}

// Controls the display of the "Submit Listing" buttons on the front end.
function lddbd_setting_submit_button() {
	$options = get_option('lddbd_options');
	$option_value = $options['display_button'];
	if($option_value=='Yes'){
		$yesChecked = 'checked';
		$noChecked = '';
	} else {
		$yesChecked = '';
		$noChecked = 'checked';
	}
	echo "<input name='lddbd_options[display_button]' type='radio' value='Yes' {$yesChecked} />Yes&nbsp;<input name='lddbd_options[display_button]' type='radio' value='No' {$noChecked} />No";
}

// Controls the display of the "Login" buttons on the front end.
function lddbd_setting_login_field() {
	$options = get_option('lddbd_options');
	$option_value = $options['display_login'];
	if($option_value=='Yes'){
		$yesChecked = 'checked';
		$noChecked = '';
	} else {
		$yesChecked = '';
		$noChecked = 'checked';
	}
	echo "<input name='lddbd_options[display_login]' type='radio' value='Yes' {$yesChecked} />Yes&nbsp;<input name='lddbd_options[display_login]' type='radio' value='No' {$noChecked} />No";
}

// Controls the display of the "Google Maps" map on the front end.
function lddbd_setting_google_maps() {
	$options = get_option('lddbd_options');
	$option_value = $options['google_map'];
	if($option_value=='Yes'){
		$yesChecked = 'checked';
		$noChecked = '';
	} else {
		$yesChecked = '';
		$noChecked = 'checked';
	}
	echo "<input name='lddbd_options[google_map]' type='radio' value='Yes' {$yesChecked} />Yes&nbsp;<input name='lddbd_options[google_map]' type='radio' value='No' {$noChecked} />No";
}

// Controls the display of the Directory Title on the front end. If left empty, the front end will display "Business Directory".
function lddbd_setting_directory_title() {
	$options = get_option('lddbd_options');
	$option_value = $options['directory_title'];
	
	echo "<input name='lddbd_options[directory_title]' value='{$options['directory_title']}'/>";
}

// Controls the display of the Welcome Message on the front end.
function lddbd_setting_welcome_message() {
	$options = get_option('lddbd_options');
	$option_value = $options['welcome_message'];
	
	echo "<textarea name='lddbd_options[welcome_message]' rows='5' cols='50'>{$options['welcome_message']}</textarea>";
}

// Controls the display of the word "Business" through the plugin. If someone wants to change it to another label such
// as "Organization" or some other term they will be able to.
function lddbd_setting_directory_label() {
	$options = get_option('lddbd_options');
	$option_value = $options['directory_label'];
	
	echo "<input name='lddbd_options[directory_label]' value='{$options['directory_label']}'/>";
}

// Controls the display of the Additional Information Sections.
function lddbd_setting_information_sections(){
	$options = get_option('lddbd_options');
	$section_array = unserialize($options['information_sections']);
	$i = 0;
	if(!empty($section_array)){
		foreach($section_array as $number=>$attributes){
			$i++;
			$text_selected = '';
			$textarea_selected = '';
			$bool_selected = '';
			if($attributes['type']=='text'){$text_selected = 'selected';}
			if($attributes['type']=='textarea'){$textarea_selected = 'selected';}
			if($attributes['type']=='bool'){$bool_selected = 'selected';}
			
			echo "<div id='lddbd_information_section_{$i}' class='lddbd_information_section'>
				<h3>Section $i</h3>
				<label for='lddbd_options[section{$i}_title]'>Title</label>
				<input type='text' name='lddbd_options[section{$i}_title]' value='{$attributes['title']}'/><br />
				<label for='lddbd_options[section{$i}_type]'>Type</label>
				<select name='lddbd_options[section{$i}_type]'>
					<option value='text' {$text_selected}>Single Line Text</option>
					<option value='textarea' {$textarea_selected}>Text Area</option>
					<option value='bool' {$bool_selected}>Yes or No</option>
				</select>
				<input type='button' value='Remove Section' class='lddbd_remove_info_section' onclick='javascript:removeInfoSection(this);'/>
				</div>";
		}
	}	
	
	echo "<input type='button' value='Add Section' id='lddbd_add_info_section'/>";


// jQuery that creates the inputs for each Information Section the user has added and the ability to remove them.	
	echo "<script type='text/javascript'>
			jQuery(document).ready(function(){
				
				jQuery('#lddbd_add_info_section').click(function(){
					var section_count = jQuery('.lddbd_information_section').length;
					jQuery(this).before('<div id=\'lddbd_information_section_'+(section_count+1)+'\' class=\'lddbd_information_section\'><h3>Section '+(section_count+1)+'</h3><label for=\'lddbd_options[section'+(section_count+1)+'_title]\'>Title</label><input type=\'text\' name=\'lddbd_options[section'+(section_count+1)+'_title]\'/><br /><label for=\'lddbd_options[section'+(section_count+1)+'_type]\'>Type</label><select name=\'lddbd_options[section'+(section_count+1)+'_type]\'><option value=\'text\'>Single Line Text</option><option value=\'textarea\'>Text Area</option><option value=\'bool\'>Yes or No</option></select><input type=\'button\' value=\'Remove Section\' class=\'lddbd_remove_info_section\' onclick=\'javascript:removeInfoSection(this);\'/></div>');
				});
			});
		</script>";
	echo "<script type='text/javascript' src='".plugins_url()."/ldd-business-directory/scripts/scripts.js'></script>";	
	
}

// Controls the display of the Categorize Entries radio buttons and allows entries to be categorized.
function lddbd_setting_categorization(){
	$options = get_option('lddbd_options');
	$option_value = $options['categorization'];
	if($option_value=='Yes'){
		$yesChecked = 'checked';
		$noChecked = '';
	} else {
		$yesChecked = '';
		$noChecked = 'checked';
	}
	echo "<input class='lddbd_categorization_bool' name='lddbd_options[categorization]' type='radio' value='Yes' {$yesChecked} />Yes&nbsp;<input class='lddbd_categorization_bool' name='lddbd_options[categorization]' type='radio' value='No' {$noChecked} />No";
	
	echo "<script type='text/javascript' src='".plugins_url()."/ldd-business-directory/scripts/scripts.js'></script>";
}

// Controls the display of the Allow User Categorization radio buttons and allows for user categorization.
function lddbd_setting_user_categorization(){
	$options = get_option('lddbd_options');
	$option_value = $options['user_categorization'];
	if($option_value=='Yes'){
		$yesChecked = 'checked';
		$noChecked = '';
	} else {
		$yesChecked = '';
		$noChecked = 'checked';
	}
	echo "<input name='lddbd_options[user_categorization]' type='radio' value='Yes' {$yesChecked} />Yes&nbsp;<input name='lddbd_options[user_categorization]' type='radio' value='No' {$noChecked} />No";
	
}

// Validates and sanitizes all input options before they are submitted.
function lddbd_options_validate($input) {
	$newinput['display_button'] = trim($input['display_button']);
	$newinput['display_login'] = trim($input['display_login']);
	$newinput['google_map'] = trim($input['google_map']);
	$newinput['directory_title'] = trim($input['directory_title']);
	$newinput['welcome_message'] = trim($input['welcome_message']);
	$newinput['directory_label'] = trim($input['directory_label']);
	
	$section_array = array();
	
	$section_count = 1;
	while(!empty($input["section{$section_count}_title"])){
		$section_title = $input["section{$section_count}_title"];
		$section_type = $input["section{$section_count}_type"];
		$section_name = preg_replace("/[^a-zA-Z 0-9]+/", "", strtolower($section_title));
		$section_name = str_replace(' ', '_', $section_name);
		$section_array[$section_count]=array('title'=>$section_title, 'type'=>$section_type, 'name'=>$section_name);
		$section_count++;
	}
	
	
	$newinput['information_sections'] = serialize($section_array);
	$newinput['categorization'] = trim($input['categorization']);
	$newinput['user_categorization'] = trim($input['user_categorization']);
	
/*
if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
		$newinput['text_string'] = '';
	}
*/
	return $newinput;
}

include('lddbd_backend-display.php');

?>