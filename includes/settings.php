<?php



// Build our administration menu in the backend.
add_action('admin_menu', 'lddbd_admin_menu');
function lddbd_admin_menu() {
	$lddbd_menu_page = add_menu_page( __('LDD Business Directory', 'lddbd'), __('Directory', 'lddbd'), 'manage_options', 'business_directory', 'lddbd_html_page' );
	$lddbd_business_directory_page = add_submenu_page('business_directory', __('Directory Listings', 'lddbd'), __('Directory Listings', 'lddbd'), 'manage_options', 'business_directory' );
	$lddbd_business_categories_page = add_submenu_page('business_directory', __('Directory Categories', 'lddbd'), __('Directory Categories'), 'manage_options', 'business_categories', 'lddbd_business_categories_page');
	$lddbd_add_business_page = add_submenu_page('business_directory', __('Add Listing to Directory', 'lddbd'), __('Add Listing', 'lddbd'), 'manage_options', 'add_business_to_directory', 'lddbd_add_business_page');
	$lddbd_edit_business_page = add_submenu_page('business_directory', __('Edit Listing in Directory', 'lddbd'), __('Edit Listing', 'lddbd'), 'manage_options', 'edit_business_in_directory', 'lddbd_edit_business_page');
	$lddbd_settings_page = add_submenu_page('business_directory', __('Directory Settings', 'lddbd'), __('Settings', 'lddbd'), 'manage_options', 'business_directory_settings', 'lddbd_settings_page');
	
	add_action( 'admin_init', 'register_mysettings' );

}


// Register our settings
function register_mysettings() {
	register_setting( 'lddbd_settings_group', 'lddbd_options', 'lddbd_options_validate' );
	add_settings_section( 'lddbd_main', '', 'lddbd_section_text', 'business_directory_settings' );
	add_settings_field( 'lddbd_setting_one', __('Display "Submit Listing" Button', 'lddbd'), 'lddbd_setting_submit_button', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_two', __('Display "Login" Button', 'lddbd'), 'lddbd_setting_login_field', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_three', __('Display "Google Maps" Map', 'lddbd'), 'lddbd_setting_google_maps', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_four', __('Default Directory View', 'lddbd'), 'lddbd_setting_default_view', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_five', __('Directory Title', 'lddbd'), 'lddbd_setting_directory_title', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_six', __('Welcome Message', 'lddbd'), 'lddbd_setting_welcome_message', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_seven', __('Directory Label', 'lddbd'), 'lddbd_setting_directory_label', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_eight', __('Additional Information Sections', 'lddbd'), 'lddbd_setting_information_sections', 'business_directory_settings', 'lddbd_main' );
//	add_settings_field( 'lddbd_setting_nine', __('Categorize Entries', 'lddbd'), 'lddbd_setting_categorization', 'business_directory_settings', 'lddbd_main' );
	add_settings_field( 'lddbd_setting_ten', __('Allow User Categorization', 'lddbd'), 'lddbd_setting_user_categorization', 'business_directory_settings', 'lddbd_main' );
}

function lddbd_section_text() {
	//echo '<p>Edit the settings for your directory.</p>';
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
	echo "<input name='lddbd_options[display_button]' type='radio' value='Yes' {$yesChecked} />&nbsp;Yes&nbsp;<input name='lddbd_options[display_button]' type='radio' value='No' {$noChecked} />&nbsp;No";
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
	echo "<input name='lddbd_options[display_login]' type='radio' value='Yes' {$yesChecked} />&nbsp;Yes&nbsp;<input name='lddbd_options[display_login]' type='radio' value='No' {$noChecked} />&nbsp;No";
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
	echo "<input name='lddbd_options[google_map]' type='radio' value='Yes' {$yesChecked} />&nbsp;Yes&nbsp;<input name='lddbd_options[google_map]' type='radio' value='No' {$noChecked} />&nbsp;No";
}

// Controls what the default display of the front end: Categories or Listings.
function lddbd_setting_default_view() {
	$options = get_option('lddbd_options');
	$option_value = $options['default_view'];
	if($option_value=='Listings'){
		$listChecked = 'checked';
		$catChecked = '';
	} else {
		$listChecked = '';
		$catChecked = 'checked';
	}
	echo "<input name='lddbd_options[default_view]' type='radio' value='Listings' {$listChecked} />&nbsp;Listings&nbsp;<br /><input name='lddbd_options[default_view]' type='radio' value='Categories' {$catChecked} />&nbsp;Categories";
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

// Controls the display of the word "Business" throughout the plugin. If someone wants to change it to another label such
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
	echo '<script src="' . DIRL_JS_URL . '/lite.js"></script>';
	
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
	echo "<input class='lddbd_categorization_bool' name='lddbd_options[categorization]' type='radio' value='Yes' {$yesChecked} />&nbsp;Yes&nbsp;<input class='lddbd_categorization_bool' name='lddbd_options[categorization]' type='radio' value='No' {$noChecked} />&nbsp;No";

    echo '<script src="' . DIRL_JS_URL . '/lite.js"></script>';
}

// Controls whether users are able to categorize their own entries.
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
	echo "<input name='lddbd_options[user_categorization]' type='radio' value='Yes' {$yesChecked} />&nbsp;Yes&nbsp;<input name='lddbd_options[user_categorization]' type='radio' value='No' {$noChecked} />&nbsp;No";
	
}

// Validates and sanitizes all input options before they are submitted.
function lddbd_options_validate($input) {
	$newinput['display_button'] = trim($input['display_button']);
	$newinput['display_login'] = trim($input['display_login']);
	$newinput['google_map'] = trim($input['google_map']);
	$newinput['default_view'] = trim($input['default_view']);
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

