<?php
/*
Plugin Name: LDD Business Directory
Plugin URI: http://www.LDDWebDesign.com
Description: Creates a Business Directory for your site
Version: 1.0
Author: LDD Web Design
Author URI: http://www.LDDWebDesign.com
License: LDDBD
*/

add_action( 'admin_init', 'lddbd_admin_init' );

function lddbd_admin_init() {
   /* Register our stylesheet. */
   wp_register_style( 'lddbd_stylesheet', plugins_url('style.css', __FILE__) );
}

global $lddbd_db_version;
$lddbd_db_version = "1.0";

global $wpdb;
global $main_table_name, $doc_table_name, $cat_table_name;
$main_table_name = $wpdb->prefix . "lddbusinessdirectory";
$doc_table_name = $wpdb->prefix . "lddbusinessdirectory_docs";
$cat_table_name = $wpdb->prefix . "lddbusinessdirectory_cats";

global $lddbd_state_dropdown;
$lddbd_state_dropdown = "<select id='lddbd_address_state' name='lddbd_address_state'>
						<option value='AK'>AK</option>
						<option value='AL'>AL</option>
						<option value='AR'>AR</option>
						<option value='AZ'>AZ</option>
						<option value='CA'>CA</option>
						<option value='CO'>CO</option>
						<option value='CT'>CT</option>
						<option value='DE'>DE</option>
						<option value='FL'>FL</option>
						<option value='GA'>GA</option>
						<option value='HI'>HI</option>
						<option value='IA'>IA</option>
						<option value='ID'>ID</option>
						<option value='IL'>IL</option>
						<option value='IN'>IN</option>
						<option value='KS'>KS</option>
						<option value='KY'>KY</option>
						<option value='LA'>LA</option>
						<option value='MA'>MA</option>
						<option value='MD'>MD</option>
						<option value='ME'>ME</option>
						<option value='MI'>MI</option>
						<option value='MN'>MN</option>
						<option value='MO'>MO</option>
						<option value='MS'>MS</option>
						<option value='MT'>MT</option>
						<option value='NC'>NC</option>
						<option value='ND'>ND</option>
						<option value='NE'>NE</option>
						<option value='NH'>NH</option>
						<option value='NJ'>NJ</option>
						<option value='NM'>NM</option>
						<option value='NV'>NV</option>
						<option value='NY'>NY</option>
						<option value='OH'>OH</option>
						<option value='OK'>OK</option>
						<option value='OR'>OR</option>
						<option value='PA'>PA</option>
						<option value='RI'>RI</option>
						<option value='SC'>SC</option>
						<option value='SD'>SD</option>
						<option value='TN'>TN</option>
						<option value='TX'>TX</option>
						<option value='UT'>UT</option>
						<option value='VA'>VA</option>
						<option value='VT'>VT</option>
						<option value='WA'>WA</option>
						<option value='WI'>WI</option>
						<option value='WV'>WV</option>
						<option value='WY'>WY</option>
					</select>";

function lddbd_install() {
	global $wpdb;
	global $lddbd_db_version;
	global $main_table_name, $doc_table_name, $cat_table_name;
	
	$main_table = "CREATE TABLE $main_table_name (
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	createDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	updateDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	expiresDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	name TINYTEXT NOT NULL,
	description TEXT NOT NULL,
	categories TEXT NOT NULL,
	address_street TEXT NOT NULL,
	address_city TEXT NOT NULL,
	address_state TEXT NOT NULL,
	address_zip CHAR(10) NOT NULL,
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
	
	$doc_table = "CREATE TABLE $doc_table_name (
	doc_id BIGINT(20) NOT NULL AUTO_INCREMENT,
	bus_id BIGINT(20) NOT NULL,
	doc_path VARCHAR(256) NOT NULL,
	doc_name TINYTEXT NOT NULL,
	doc_description LONGTEXT,
	PRIMARY KEY  (doc_id),
	FOREIGN KEY (bus_id) REFERENCES $main_table_name(id)
	);";
	
	$cat_table = "CREATE TABLE $cat_table_name(
	id BIGINT(20) NOT NULL AUTO_INCREMENT,
	name TINYTEXT NOT NULL,
	count BIGINT(20) NOT NULL,
	PRIMARY KEY  (id)
	);";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($main_table);
   dbDelta($doc_table);
   dbDelta($cat_table);
 
   add_option("lddbd_db_version", $lddbd_db_version);
}

function lddbd_install_data() {
   global $wpdb;
   global $main_table_name, $doc_table_name, $cat_table_name;
   $welcome_name = "Test Business";
   $welcome_text = "We sell widgets!";
   $welcome_login = "test_business";
   $welcome_password = "1234";
   

   //$rows_affected = $wpdb->insert( $main_table_name, array( 'createDate' => current_time('mysql'), 'name' => $welcome_name, 'description' => $welcome_text, 'login'=>$welcome_login, 'password'=>$welcome_password ) );
}

register_activation_hook(__FILE__,'lddbd_install');
register_activation_hook(__FILE__,'lddbd_install_data');





/* ------------------Settings Menu-------------------- */


/* Call the html code */
add_action('admin_menu', 'lddbd_admin_menu');

function lddbd_admin_menu() {
	$lddbd_menu_page = add_menu_page( 'Business Directory', 'Businesses', 'manage_options', 'business_directory', 'lddbd_html_page');
	$lddbd_settings_page = add_submenu_page('business_directory', 'Business Directory Settings', 'Settings', 'manage_options', 'business_directory_settings', 'lddbd_settings_page');
	$lddbd_add_business_page = add_submenu_page('business_directory', 'Add Business to Directory', 'Add Business', 'manage_options', 'add_business_to_directory', 'lddbd_add_business_page');
	$lddbd_edit_business_page = add_submenu_page('business_directory', 'Edit Business', 'Edit Business', 'manage_options', 'edit_business_in_directory', 'lddbd_edit_business_page');
	$lddbd_business_categories_page = add_submenu_page('business_directory', 'Business Directory Categories', 'Categories', 'manage_options', 'business_categories', 'lddbd_business_categories_page');
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
	//register our settings
	register_setting( 'lddbd_settings_group', 'lddbd_options', 'lddbd_options_validate');
	add_settings_section('lddbd_main', 'Business Directory Settings', 'lddbd_section_text', 'business_directory_settings');
	add_settings_field('lddbd_setting_one', 'Display "Submit Listing" Button', 'lddbd_setting_submit_button', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_two', 'Display "Login" Button', 'lddbd_setting_login_field', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_three', 'Welcome Message', 'lddbd_setting_welcome_message', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_four', 'Additional Information Sections', 'lddbd_setting_information_sections', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_five', 'Categorize Entries', 'lddbd_setting_categorization', 'business_directory_settings', 'lddbd_main');
	add_settings_field('lddbd_setting_six', 'Allow User Categorization', 'lddbd_setting_user_categorization', 'business_directory_settings', 'lddbd_main');
}

function lddbd_section_text() {
	echo '<p>Main description of this section here.</p>';
}

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

function lddbd_setting_welcome_message() {
	$options = get_option('lddbd_options');
	$option_value = $options['welcome_message'];
	
	echo "<textarea name='lddbd_options[welcome_message]' rows='5' cols='50'>{$options['welcome_message']}</textarea>";
}

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
			if($attributes['type']=='file'){$file_selected = 'selected';}
			
			echo "<div id='lddbd_information_section_{$i}' class='lddbd_information_section'>
				<h3>Section $i</h3>
				<label for='lddbd_options[section{$i}_title]'>Title</label>
				<input type='text' name='lddbd_options[section{$i}_title]' value='{$attributes['title']}'/>
				<label for='lddbd_options[section{$i}_type]'>Type</label>
				<select name='lddbd_options[section{$i}_type]'>
					<option value='text' {$text_selected}>Single Line Text</option>
					<option value='textarea' {$textarea_selected}>Text Area</option>
					<option value='bool' {$bool_selected}>Yes or No</option>
					<option value='file' {$file_selected}>File Upload</option>
				</select>
				<input type='button' value='Remove Section' class='lddbd_remove_info_section' onclick='javascript:removeInfoSection(this);'/>
				</div>
				";
		}
	}	
	
	echo "<input type='button' value='Add Section' id='lddbd_add_info_section'/>";
	
	echo "<script type='text/javascript'>
			jQuery(document).ready(function(){
				
				jQuery('#lddbd_add_info_section').click(function(){
					var section_count = jQuery('.lddbd_information_section').length;
					jQuery(this).before('<div id=\'lddbd_information_section_'+(section_count+1)+'\' class=\'lddbd_information_section\'><h3>Section '+(section_count+1)+'</h3><label for=\'lddbd_options[section'+(section_count+1)+'_title]\'>Title</label><input type=\'text\' name=\'lddbd_options[section'+(section_count+1)+'_title]\'/><label for=\'lddbd_options[section'+(section_count+1)+'_type]\'>Type</label><select name=\'lddbd_options[section'+(section_count+1)+'_type]\'><option value=\'text\'>Single Line Text</option><option value=\'textarea\'>Text Area</option><option value=\'bool\'>Yes or No</option><option value=\'file\'>File Upload</option></select><input type=\'button\' value=\'Remove Section\' class=\'lddbd_remove_info_section\' onclick=\'javascript:removeInfoSection(this);\'/></div>');
				});
				
				
			});
		</script>";
	echo "<script type='text/javascript' src='".plugins_url()."/ldd-business-directory/scripts.js'></script>";	
	
}

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
	
	echo "<script type='text/javascript' src='".plugins_url()."/ldd-business-directory/scripts.js'></script>";
}

/*
function lddbd_setting_categories(){
	$options = get_option('lddbd_options');
	$option_value = unserialize($options['categories']);
	$i = 0;
	echo "<div id='lddbd_categories_list'>";
	if(!empty($option_value)){
		foreach($option_value as $value){
			$i++;
			echo "<div id='lddbd_category_holder_{$i}' class='lddbd_category_holder'>";
			echo "<input type='text' readonly name='lddbd_options[category_{$i}]' value='{$value}'/>";
			echo "<input type='button' value='Remove Category' class='lddbd_remove_category' onclick='javascript:removeCategory(this);'/>";
			echo "</div>";
		}
	}
	echo "<script type='text/javascript'>
			jQuery(document).ready(function(){
				
				jQuery('#lddbd_add_category').click(function(){
					var category_count = jQuery('.lddbd_category_holder').length;
					jQuery(this).before('<div id=\'lddbd_category_holder_'+(category_count+1)+'\' class=\'lddbd_category_holder\'><input type=\'text\' name=\'lddbd_options[category_'+(category_count+1)+']\'/><input type=\'button\' value=\'Remove Category\' class=\'lddbd_remove_category\' onclick=\'javascript:removeCategory(this);\'/></div>');
				});
				
				
			});
		</script>";
	echo "<input type='button' value='Add Category' id='lddbd_add_category'/>";
	echo "</div>";
}
*/

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


// validate our options
function lddbd_options_validate($input) {
	$newinput['display_button'] = trim($input['display_button']);
	$newinput['display_login'] = trim($input['display_login']);
	$newinput['welcome_message'] = trim($input['welcome_message']);
	
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

function lddbd_html_page(){ 
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}?>
	<div class="wrap">
		<h2>Business Directory List</h2>
		
		<table id="lddbd_business_table" class="wp-list-table widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name sortable">Name</th>
					<th scope="col" id="email" class="manage-column column-email sortable">Email</th>
					<th scope="col" id="approved" class="manage-column column-approved">Approved</th>
					<th scope="col" id="id" class="manage-column column-id sortable">ID</th>
				</tr>
			</thead>
			<tbody>
				<?php
					global $wpdb;
					global $main_table_name, $doc_table_name, $cat_table_name;
					$business_list = $wpdb->get_results(
						"
						SELECT *
						FROM $main_table_name
						ORDER BY name ASC
						"
					);
					
					if($business_list){
						foreach($business_list as $business){ ?>
							<tr id="business-<?php echo $business->id; ?>">
								<td>
									<strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=edit_business_in_directory&id=<?php echo $business->id; ?>"><?php echo $business->name; ?></a></strong>
									<div class="row-actions">
										<a class="delete_business" href="javascript:void(0);">Delete</a>
										<?php if($business->approved == 'false') { ?>
											<a class="approve_business business_approval" href="javascript:void(0);">Approve</a>
										<?php } else { ?>
											<a class="revoke_business business_approval" href="javascript:void(0);">Revoke Approval</a>
										<?php } ?>
										<a class="edit_business open" href="javascript:void(0);">Quick Edit</a>
									</div>
								</td>
								<td>
									<?php echo $business->email; ?>
								</td>
								<th class="approval" scope="row">
									<input type="checkbox" disabled <?php if($business->approved == 'true') {echo 'checked="checked"';}?>>
								</th>
								<td>
									<?php echo $business->id; ?>
								</td>
							</tr>
							<tr class="lddbd_edit_business_row">
								<td colspan="4">
								<form class="lddbd_edit_business_form" method="post" action="<?php echo plugins_url(); ?>/ldd-business-directory/lddbd_ajax.php" enctype='multipart/form-data'>
									<div class="lddbd_input_holder">
										<label for="name">Business Name</label>
										<input class="name required" type="text" name="name" value="<?php echo $business->name;?>"/>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="description">Business Description</label>
										<textarea class="description" name="description"><?php echo $business->description; ?></textarea>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="phone">Contact Phone</label>
										<input class="phone " type="text" name="phone" value="<?php echo $business->phone;?>"/>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="fax">Contact Fax</label>
										<input type="text" class="fax" name="fax" value="<?php echo $business->fax;?>"/>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="email">Contact Email</label>
										<input class="email" type="text" name="email" value="<?php echo $business->email;?>"/>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="contact">Contact Name</label>
										<input class="contact" type="text" name="contact" value="<?php echo $business->contact;?>"/>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="url">Website</label>
										<input class="url" type="text" name="url" value="<?php echo $business->url;?>"/>
									</div>
									
									<div class="lddbd_input_holder">
										<label for="login">Login</label>
										<input class="login" type="text" name="login" value="<?php echo $business->login;?>"/>
									</div>
									
									<input type="hidden" class="action" name="action" value="quick_edit"/>
									<input type="hidden" class="id" name="id" value="<?php echo $business->id; ?>"/>
									
						   			<p class="submit">
									    <input type="submit" class="button-primary" value="<?php _e('Update Business') ?>" />
								    </p>
						   		</form>
					   		</td>
							</tr>
					<?php	
						}		
					} else {echo "<tr><td colspan='4'>Sorry, there are no businesses listed in the directory.</td></tr>";}
				?>
			</tbody>
		</table>

	</div>
<?php

echo '
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".delete_business").click(function(){
			jQuery(this).closest("tr").fadeOut(400);
			var business_id = jQuery(this).closest("tr").attr("id");
			business_id = business_id.substring(9);
			jQuery.post("'.plugins_url().'/ldd-business-directory/lddbd_ajax.php", {id:business_id, action:"delete"});
		});
		
		jQuery(".business_approval").click(function(){
			if(jQuery(this).hasClass("approve_business")){
				jQuery(this).html("Revoke Approval").removeClass("approve_business").addClass("revoke_business");
				jQuery(this).closest("td").siblings("th.approval").children(\'input[type="checkbox"]\').attr("checked", "checked");
				var business_id = jQuery(this).closest("tr").attr("id");
				business_id = business_id.substring(9);
				jQuery.post("'.plugins_url().'/ldd-business-directory/lddbd_ajax.php", {id:business_id, action:"approve"});
			} else if(jQuery(this).hasClass("revoke_business")){
				jQuery(this).html("Approve").removeClass("revoke_business").addClass("approve_business");
				jQuery(this).closest("td").siblings("th.approval").children(\'input[type="checkbox"]\').removeAttr("checked");
				var business_id = jQuery(this).closest("tr").attr("id");
				business_id = business_id.substring(9);
				jQuery.post("'.plugins_url().'/ldd-business-directory/lddbd_ajax.php", {id:business_id, action:"revoke"});
			}
		});
		
		jQuery(".edit_business").click(function(){
			if(jQuery(this).hasClass("open")){
				jQuery(this).html("Done Editing").removeClass("open").addClass("close");
				jQuery(this).closest("tr").next("tr.lddbd_edit_business_row").fadeIn(400);
			} else if (jQuery(this).hasClass("close")){
				jQuery(this).html("Quick Edit").removeClass("close").addClass("open");
				jQuery(this).closest("tr").next("tr.lddbd_edit_business_row").fadeOut(400);
			}	
		});
		
		jQuery(".lddbd_edit_business_form").submit(function(){
			var this_row = jQuery(this).closest("tr.lddbd_edit_business_row");
			var action = jQuery(this).attr("action");
			var bus_id = jQuery(this).find(".id").val();
			var quick_data = {
				name: jQuery(this).find(".name").val(),
				description: jQuery(this).find(".description").val(),
				phone: jQuery(this).find(".phone").val(),
				fax: jQuery(this).find(".fax").val(),
				email: jQuery(this).find(".email").val(),
				contact: jQuery(this).find(".contact").val(),
				url: jQuery(this).find(".url").val(),
				login: jQuery(this).find(".login").val(),
				id: jQuery(this).find(".id").val(),
				action: jQuery(this).find(".action").val()
			};
			jQuery.ajax({
				type: "POST",
				url: action, 
				data: quick_data,
				complete: function(data){
					this_row.fadeOut(400);
					jQuery("#business-"+bus_id+" td div.row-actions a.edit_business").html("Quick Edit").removeClass("close").addClass("open");
				}	
			});
			
			return false;
		});
	});
</script>
';

}

function lddbd_settings_page(){ 
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}?>
	<div class="wrap">
		<h2>Business Directory Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'lddbd_settings_group' ); ?>
   			<?php do_settings_sections( 'business_directory_settings' ); ?>
   			<p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		    </p>
   		</form>	
	</div>
<?php
}

function lddbd_add_business_page(){
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	global $wpdb;
	global $lddbd_state_dropdown;
	global $main_table_name, $doc_table_name, $cat_table_name;
	$full_business_list = $wpdb->get_results(
		"
		SELECT login
		FROM $main_table_name
		"
	);
	$logins = '';

	if($full_business_list){
		foreach($full_business_list as $business){
			$logins.=", '{$business->login}'";
		}
	}
	$categories_list = $wpdb->get_results(
		"
		SELECT *
		FROM $cat_table_name
		"
	);
	
	$options = get_option('lddbd_options');
	$section_array = unserialize($options['information_sections']);
		
	
	?>
	<div class="wrap">
		<h2>Add Business to Directory</h2>
		
		<form id="lddbd_add_business_form" method="post" action="<?php echo plugins_url(); ?>/ldd-business-directory/lddbd_ajax.php" enctype='multipart/form-data'>
			<div class="lddbd_input_holder">
				<label for="name">Business Name</label>
				<input class="required" type="text" id="lddbd_name" name="name"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="description">Business Description</label>
				<textarea id="lddbd_description" name="description"></textarea>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="address_street">Street</label>
				<input type="text" id="lddbd_address_street" name="address_street">
			</div>
			
			<div class="lddbd_input_holder">
				<label for="address_city">City</label>
				<input type="text" id="lddbd_address_city" name="address_city">
			</div>
			
			<div class="lddbd_input_holder">
				<label for="lddbd_address_state">State</label>
				<?php echo $lddbd_state_dropdown; ?>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="address_zip">Zip</label>
				<input type="text" id="lddbd_address_zip" name="address_zip">
			</div>
			
			<div class="lddbd_input_holder">
				<label for="phone">Contact Phone</label>
				<input class="" type="text" id="lddbd_phone" name="phone"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="fax">Contact Fax</label>
				<input type="text" id="lddbd_fax" name="fax">
			</div>
			
			<div class="lddbd_input_holder">
				<label for="email">Contact Email</label>
				<input class="required" type="text" id="lddbd_email" name="email"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="contact">Contact Name</label>
				<input class="" type="text" id="lddbd_contact" name="contact"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="url">Website</label>
				<input type="text" id="lddbd_url" name="url"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="facebook">Facebook Page</label>
				<input type="text" id="lddbd_facebook" name="facebook"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="twitter">Twitter Handle</label>
				<input type="text" id="lddbd_twitter" name="twitter"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="linkedin">Linked In Profile</label>
				<input type="text" id="lddbd_linkedin" name="linkedin"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="promo">Special Offer</label>
				<div class="lddbd_radio_holder">
					<input type="radio" id="lddbd_promo" name="promo" value="true"/>Yes&nbsp;<input type="radio" id="lddbd_promo" name="promo" value="false"/>No
				</div>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="promo_description">Special Offer Description</label>
				<input type="text" id="lddbd_promo_description" name="promo_description"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="logo">Logo Image</label>
				<input class="" type="file" id="lddbd_logo" name="logo"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="login">Login</label>
				<input class="" type="text" id="lddbd_login" name="login"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="password">Password</label>
				<input class="" type="text" id="lddbd_password" name="password"/>
			</div>
			
			<?php
				if(!empty($section_array)){
					foreach($section_array as $number=>$attributes){
						$type = $attributes['type'];
						if($type=='bool'){
							$input = "<div class='lddbd_radio_holder'><input type='radio' name='{$attributes['name']}' value='Yes'/>Yes&nbsp;<input type='radio' name='{$attributes['name']}' value='No'/>No</div>";
						} else {
							$input = "<input type='{$type}' name='{$attributes['name']}' />";
						}
						
						
						echo "<div class='lddbd_input_holder'>
								<label for='{$attributes['name']}'>{$attributes['title']}</label>
								{$input}
							</div>
						";
						
					}
				}
			?>
			
			<?php if(!empty($categories_list)){ ?>
			<div class="lddbd_input_holder">
				<label><strong>Categories</strong></label><br/>
				<?php foreach($categories_list as $category){ ?>
				
				<div class="lddbd_category_block">
					<input class="category_box" type="checkbox" name="category_<?php echo $category->id; ?>" value="x<?php echo $category->id; ?>x" />
					<label for="category_<?php echo $category->id; ?>"><?php echo $category->name; ?></label>
				</div>
				
				<?php } ?>
			</div>
			<?php } ?>
			
			<input id="lddbd_categories" type="hidden" name="categories"/>
			
			<div class="lddbd_input_holder">
				<label for="approved">Approved</label>
				<input type="checkbox" id="lddbd_approved" name="approved" checked="checked" value="true"/>
			</div>
			
			<input type="hidden" id="lddbd_action" name="action" value="add"/>
			
   			<p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Add Business') ?>" />
		    </p>
   		</form>
   		
   		<script type="text/javascript">
   			jQuery(document).ready(function(){
	 			var business_logins = new Array(<?php echo substr($logins, 2); ?>);
	 			
	 			jQuery('.category_box').click(function(){
	 				var category_string = '';
	 				jQuery('.category_box').each(function(){
	 					if(jQuery(this).is(':checked')){
	 						category_string += ','+jQuery(this).val();
	 					}
	 				});
	 				jQuery('#lddbd_categories').val(category_string);
	 			});
	 			
	 			jQuery('#lddbd_add_business_form').submit(function(){
	 				var error = false;
	 				
	 				jQuery('#lddbd_add_business_form').find('input.required').each(function(){
	 					if(jQuery(this).val()==''){
	 						if(!jQuery(this).hasClass('add_business_error')){
		 						jQuery(this).addClass('add_business_error').parent().append('<div class=\'add_business_error\'>This field is required.</div>');
		 					}	
	 						error=true;
	 					}
	 				});
	 				
	 				if(jQuery.inArray(jQuery('#lddbd_add_business_form').find('#login').val(), business_logins)!=-1){
	 					jQuery('#lddbd_add_business_form').find('#login').addClass('add_business_error').parent().append('<div class=\'add_business_error\'>This login is taken.</div>');
	 					error=true;
	 				}
	 				
	 				jQuery('input.add_business_error').focus(function(){
						jQuery(this).removeClass('add_business_error');
					}).blur(function(){
						if(jQuery(this).val()==''){
							jQuery(this).addClass('add_business_error');
						} else {
							jQuery(this).siblings('div.add_business_error').remove();
						}
					});
	 				
	 				if(!error){
		 				return true;
		 			}
		 			else{
		 				return false;
		 			}
	 			});
	 			
	 		});
   		</script>
	</div>

<?php
	
}

function lddbd_edit_business_page(){
	global $lddbd_state_dropdown;
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	if(!empty($_GET['id'])){
	$id = $_GET['id'];
	
	global $wpdb;
	global $main_table_name, $doc_table_name, $cat_table_name;
	//$wpdb->show_errors();
	$business = $wpdb->get_row("SELECT * FROM $main_table_name WHERE id = $id");
	
	$categories_list = $wpdb->get_results(
		"
		SELECT *
		FROM $cat_table_name
		"
	);
	
	$categories_array = explode(',', str_replace('x', '', substr($business->categories, 1)));
	if(empty($categories_array)){$categories_array=array();}
	$files = $wpdb->get_results("SELECT * FROM $doc_table_name WHERE bus_id = '$id'");
	$files_list = '';
	
	foreach($files as $file){
		$files_list .="<li><em>{$file->doc_description}</em><input type='button' value='delete' class='file_delete' id='{$file->doc_id}_delete'/></li>";
	}

	?>
	<div class="wrap">
		<h2>Edit Business</h2>
		
		<form class="lddbd_edit_business_form" method="post" action="<?php echo plugins_url(); ?>/ldd-business-directory/lddbd_ajax.php" enctype='multipart/form-data'>
			<div class="lddbd_input_holder">
				<label for="name">Business Name</label>
				<input class="required" type="text" id="lddbd_name" name="name" value="<?php echo $business->name;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="description">Business Description</label>
				<textarea id="lddbd_description" name="description"><?php echo $business->description; ?></textarea>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="address_street">Street</label>
				<input type="text" id="lddbd_address_street" name="address_street" value="<?php echo $business->address_street;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="address_city">City</label>
				<input type="text" id="lddbd_address_city" name="address_city" value="<?php echo $business->address_city;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="lddbd_address_state">State</label>
				<?php echo $lddbd_state_dropdown; ?>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="address_zip">Zip</label>
				<input type="text" id="lddbd_address_zip" name="address_zip" value="<?php echo $business->address_zip;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="phone">Contact Phone</label>
				<input class="" type="text" id="lddbd_phone" name="phone" value="<?php echo $business->phone;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="fax">Contact Fax</label>
				<input type="text" id="lddbd_fax" name="fax" value="<?php echo $business->fax;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="email">Contact Email</label>
				<input class="required" type="text" id="lddbd_email" name="email" value="<?php echo $business->email;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="contact">Contact Name</label>
				<input class="" type="text" id="lddbd_contact" name="contact" value="<?php echo $business->contact;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="url">Website</label>
				<input class="" type="text" id="lddbd_url" name="url" value="<?php echo $business->url;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="facebook">Facebook Page</label>
				<input type="text" id="lddbd_facebook" name="facebook" value="<?php echo $business->facebook;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="twitter">Twitter Handle</label>
				<input type="text" id="lddbd_twitter" name="twitter" value="<?php echo $business->twitter;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="linkedin">Linked In Profile</label>
				<input type="text" id="lddbd_linkedin" name="linkedin" value="<?php echo $business->linkedin;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="promo">Special Offer</label>
				<div class="lddbd_radio_holder">
					<input type="radio" id="lddbd_promo" name="promo" value="true" <?php if($business->promo=='true'){echo 'checked="checked"';}?>/>Yes&nbsp;<input type="radio" id="promo" name="promo" value="false" <?php if($business->promo=='false'){echo 'checked="checked"';}?>/>No
				</div>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="promo_description">Special Offer Description</label>
				<input type="text" id="lddbd_promo_description" name="promo_description" value="<?php echo $business->promoDescription;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="current_logo">Current Logo</label>
				<input type="hidden" id="lddbd_current_logo" name="current_logo" value="<?php echo $business->logo; ?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<img src="<?php echo plugins_url().'/ldd-business-directory/'.$business->logo; ?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="logo">Upload New Logo</label>
				<input type="file" id="lddbd_logo" name="logo"/>
			</div>
			
			<div class='lddbd_input_holder'>
				<strong>Files</strong>
				<ul>
				<?php echo $files_list; ?>
				</ul>
			</div>
			
			<div class='lddbd_input_holder file_input_holder'>
				<strong>File</strong>
				<strong>Description</strong>
			</div>
			
			<div class='lddbd_input_holder file_input_holder'>
				<input type='file' id='lddbd_file1' name='file1'/>
				<input type='text' id='lddbd_file1_description' name='file1_description'/>
			</div>
			
			<div class='lddbd_input_holder'>
				<input type='button' id='lddbd_add_file_upload' value='Add File Upload' />
			</div>
			
			<div class="lddbd_input_holder">
				<label for="login">Login</label>
				<input class="" type="text" id="lddbd_login" name="login" value="<?php echo $business->login;?>"/>
			</div>
			
			<div class="lddbd_input_holder">
				<label for="password">Password</label>
				<input class="" type="text" id="lddbd_password" name="password" value="<?php echo $business->password;?>"/>
			</div>
			
			<?php
				$options = get_option('lddbd_options');
				$section_array = unserialize($options['information_sections']);
				if(!empty($section_array)){
					$business_section_array = unserialize($business->other_info);
					foreach($section_array as $number=>$attributes){
						$type = $attributes['type'];
						if($type=='bool'){
							$checked_yes = '';
							$checked_no = '';
							if($business_section_array[$attributes['name']]=='Yes'){
								$checked_yes = 'checked';
							} else {
								$checked_no = 'checked';
							}
							$input = "<div class='lddbd_radio_holder'><input type='radio' name='{$attributes['name']}' value='Yes' {$checked_yes}/>Yes&nbsp;<input type='radio' name='{$attributes['name']}' value='No' {$checked_no}/>No</div>";
						} else {
							$input = "<input type='{$type}' name='{$attributes['name']}' value='{$business_section_array[$attributes['name']]}'/>";
						}
						
						
						echo "<div class='lddbd_input_holder'>
								<label for='{$attributes['name']}'>{$attributes['title']}</label>
								{$input}
							</div>
						";
						
					}
				}
			?>
			
			<?php if(!empty($categories_list)){ ?>
			<div class="lddbd_input_holder">
				<label><strong>Categories</strong></label><br/>
				<?php foreach($categories_list as $category){ ?>
				<div class="lddbd_category_block">
					<input class="category_box" type="checkbox" name="category_<?php echo $category->id; ?>" value="x<?php echo $category->id; ?>x" <?php if(in_array($category->id, $categories_array)){echo 'checked="checked"'; }?>/>
					<label for="category_<?php echo $category->id; ?>"><?php echo $category->name; ?></label>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			
			<input id="lddbd_categories" type="hidden" name="categories" value="<?php echo $business->categories; ?>">
			
			<div class="lddbd_input_holder">
				<label for="approved">Approved</label>
				<input type="checkbox" id="lddbd_approved" name="approved" value="true" <?php if($business->approved=='true'){echo 'checked="checked"';} ?>/>
			</div>
			
			<input type="hidden" id="lddbd_action" name="action" value="edit"/>
			<input type="hidden" id="lddbd_id" name="id" value="<?php echo $business->id; ?>"/>
			
			<p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Update Business') ?>" />
		    </p>
		</form>

	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#lddbd_address_state option[value="<?php echo $business->address_state;?>"]').attr('selected', 'selected');
			
			jQuery('.category_box').click(function(){
 				var category_string = '';
 				jQuery('.category_box').each(function(){
 					if(jQuery(this).is(':checked')){
 						category_string += ','+jQuery(this).val();
 					}
 				});
 				jQuery('#lddbd_categories').val(category_string);
 			});
 			
 			var file_input_count = 1;
			jQuery('#lddbd_add_file_upload').click(function(){
				if(file_input_count<5){
				file_input_count++;
				jQuery('.file_input_holder').last().after('<div class=\'lddbd_input_holder file_input_holder\'><input type=\'file\' id=\'file'+file_input_count+'\' name=\'file'+file_input_count+'\'/><input type=\'text\' id=\'file'+file_input_count+'_description\' name=\'file'+file_input_count+'_description\'/></div>');
				}
			});
 			
 			jQuery('input.file_delete').click(function(){
					var this_placeholder = jQuery(this);
					var doc_id = jQuery(this).attr('id');
					doc_id = parseInt(doc_id);
					jQuery.ajax({
					type: 'POST',
					url: '<?php echo plugins_url(); ?>/ldd-business-directory/lddbd_ajax.php',
					data: {doc_id: doc_id, action: 'delete_doc'},
					success: function(data){
						this_placeholder.parent().slideUp('200');
					}
				});
			});
		});
	</script>
<?php
	} else {
	
		global $wpdb;
		global $main_table_name, $doc_table_name, $cat_table_name;
		$business_list = $wpdb->get_results(
			"
			SELECT *
			FROM $main_table_name
			ORDER BY name ASC
			"
		);
		
		if($business_list){
			
?>	
	<div class="wrap">
		<h2>Choose Business to Edit</h2>
		<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php" method="get">
			<input type="hidden" name="page" id="lddbd_page" value="edit_business_in_directory" />
			<select name="id">
				<?php foreach($business_list as $business){
					echo "<option value='{$business->id}'>{$business->name}</option>";
				} ?>
			</select>
			
			<p class="submit">
		    	<input type="submit" class="button-primary" value="<?php _e('Find Business') ?>" />
	   		</p>
		</form>
		
		
	</div>
<?php	
		}
	}
}
function lddbd_business_categories_page(){

?>
	<h2>Categories</h2>
	
	<table id="lddbd_category_table" class="wp-list-table widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="name" class="manage-column column-name sortable">Name</th>
					<th scope="col" id="count" class="manage-column column-email sortable">Count</th>
					<th scope="col" id="id" class="manage-column column-id sortable">ID</th>
				</tr>
			</thead>
			<tbody>
				<?php
					global $wpdb;
					global $main_table_name, $doc_table_name, $cat_table_name;
					$cat_list = $wpdb->get_results(
						"
						SELECT id, name, count
						FROM $cat_table_name
						"
					);
					$business_cats = $wpdb->get_results(
						"
						SELECT categories
						FROM $main_table_name
						"
					);
					
					if($cat_list){
						foreach($cat_list as $cat){
							$cat_count = 0;
							foreach($business_cats as $business){
								$cat_array = explode(',', str_replace('x', '', substr($business->categories, 1)));
								if(in_array($cat->id, $cat_array)){
									$cat_count++;
								}
							}
							$row_updated = $wpdb->update(
								$cat_table_name,
								array('count'=>$cat_count),
								array('id'=>$cat->id),
								array('%d'),
								array('%d')
							);
						?>
							<tr id="cat-<?php echo $cat->id; ?>">
								<td>
									<strong><?php echo $cat->name; ?></strong>
									<div class="row-actions">
										<a class="delete_category" href="javascript:void(0);">Delete</a>
										<a class="edit_category open" href="javascript:void(0);">Edit</a>
									</div>
								</td>
								<td class="cat_count">
									<?php echo $cat_count; ?>
								</td>
								<td>
									<?php echo $cat->id; ?>
								</td>
							</tr>
							<tr class="lddbd_edit_category_row">
								<td colspan="3">
									<form class="lddbd_edit_category_form" method="post" action="<?php echo plugins_url(); ?>/ldd-business-directory/lddbd_ajax.php">
										<input type="text" name="cat_name" value="<?php echo $cat->name; ?>" />
										<input type="hidden" name="id" value="<?php echo $cat->id; ?>"/>
							   			<p class="submit">
										    <input type="submit" class="button-secondary" value="<?php _e('Save Category') ?>" />
									    </p>
							   		</form>
						   		</td>
							</tr>
					<?php	
						}		
					} else {echo "<tr><td colspan='3'>Sorry, there are no categories listed in the directory.</td></tr>";}
				?>
				<tr id="lddbd_add_category_row">
					<td colspan="3">
						<form id="lddbd_add_category_form" method="post" action="<?php echo plugins_url(); ?>/ldd-business-directory/lddbd_ajax.php">
							<input class="name" type="text" name="name">
							<input class="action" type="hidden" name="action" value="add_category">
				   			<p class="submit">
							    <input type="submit" class="button-secondary" value="<?php _e('Submit') ?>" />
						    </p>
				   		</form>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<input type="button" id="lddbd_add_category_button" class="button-secondary" value="<?php _e('Add Category') ?>" />
					</td>
				</tr>
			</tbody>
		</table>


<?php 

echo '
<script type="text/javascript">
	jQuery(document).ready(function(){
		
		jQuery(".delete_category").click(function(){
			var cat_count = jQuery(this).closest("tr").children("td.cat_count").html();
			if(cat_count > 0){
				alert("There are businesses using this category, please remove it from their information before deleting.");
			}
			else{
				var cat_id = jQuery(this).closest("tr").attr("id");
				cat_id = cat_id.substring(4);
				
				jQuery.post("'.plugins_url().'/ldd-business-directory/lddbd_ajax.php", {id:cat_id, action:"delete_category"});
				jQuery.ajax({
					type: "POST",
					url: "'.plugins_url().'/ldd-business-directory/lddbd_ajax.php",
					data: {id:cat_id, action:"delete_category"},
					success: function(data){
						jQuery(this).closest("tr").fadeOut(400);
					}
				});
			}	
		});
		
		jQuery(".edit_category").click(function(){
			if(jQuery(this).hasClass("open")){
				jQuery(this).html("Done Editing").removeClass("open").addClass("close");
				jQuery(this).closest("tr").next("tr.lddbd_edit_category_row").fadeIn(400);
			} else if (jQuery(this).hasClass("close")){
				jQuery(this).html("Edit").removeClass("close").addClass("open");
				jQuery(this).closest("tr").next("tr.lddbd_edit_category_row").fadeOut(400);
			}	
		});
				
		jQuery(".lddbd_edit_category_form").submit(function(){
			var this_row = jQuery(this).closest("tr.lddbd_edit_category_row");
			var action = jQuery(this).attr("action");
			var cat_id = jQuery(this).find("input[name=\"id\"]").val();
			var new_name = jQuery(this).find("input[name=\"cat_name\"]").val();
			
			var quick_data = {
				name: new_name,
				id: cat_id,
				action: "edit_category"
			};
			
			jQuery.ajax({
				type: "POST",
				url: action, 
				data: quick_data,
				success: function(data){
					this_row.fadeOut(400);
					jQuery("#cat-"+cat_id+" td strong").html(new_name);
					jQuery("#cat-"+cat_id+" td div.row-actions a.edit_category").html("Edit").removeClass("close").addClass("open");
				}	
			});
			
			return false;
		});
		
		jQuery("#lddbd_add_category_button").click(function(){
			jQuery("#lddbd_add_category_row").fadeIn(400);
			jQuery("#lddbd_add_category_form input.name").val('');
		});
		
		jQuery("#lddbd_add_category_form").submit(function(){
			var action = jQuery(this).attr("action");
			var quick_data = {
				name: jQuery(this).find(".name").val(),
				action: jQuery(this).find(".action").val()
			};
			jQuery.ajax({
				type: "POST",
				url: action, 
				data: quick_data,
				complete: function(data){
					var return_data = data.responseText;
					return_data = return_data.replace("<table>", "");
					return_data = return_data.replace("</table>", "");
					jQuery("#lddbd_add_category_row").before(return_data);
					jQuery("#lddbd_add_category_row").fadeOut(400);
				}	
			});
			return false;
		});
		
		
	});
</script>
';
}







/* --------------------- Actual Display Page ---------------------- */





function display_business_directory( $atts ){

global $wpdb;
global $lddbd_state_dropdown;
global $main_table_name, $doc_table_name, $cat_table_name;

echo "<script type='text/javascript'>
		lddbd_file_pathway = '".plugins_url()."/ldd-business-directory/';
	</script>
	<script type='text/javascript' src='".plugins_url()."/ldd-business-directory/scripts.js'></script>
	";

if($_GET['business']){

	$full_business_list = $wpdb->get_results(
		"
		SELECT login
		FROM $main_table_name
		"
	);
	
	$logins = '';
	
	if($full_business_list){
		foreach($full_business_list as $business){
			$logins.=", '{$business->login}'";
		}
	}
	
	$business = $wpdb->get_row(
		"
		SELECT *
		FROM $main_table_name
		WHERE id = '{$_GET['business']}'
		"
	);
	
	$documents = $wpdb->get_results(
		"
		SELECT *
		FROM $doc_table_name
		WHERE bus_id = '{$business->id}'
		"
	);
	
	$doc_list = '';
	if($documents){
		foreach($documents as $document){
			$doc_list.="<li><a target='_blank' href='".plugins_url()."/ldd-business-directory/{$document->doc_path}'>{$document->doc_description}</a></li>";
		}
	}
	
	$business_listing = '';
	
	$description = '';
	$contact_left = '';
	$contact_right = '';
	$logo_html = '';
	$business_address = '';
	
	if(!empty($business->address_street)){ $contact_left.="<strong>Address:</strong><p>{$business->address_street}</p>"; $business_address.=$business->address_street;}
	if(!empty($business->address_city) || !empty($business->address_state) || !empty($business->address_zip)){
		$contact_left.="<p>{$business->address_city}, {$business->address_state} {$business->address_zip}</p>";
		$business_address.=' '.$business->address_city;
		$business_address.=' '.$business->address_state;
		$business_address.=' '.$business->address_zip;
	}
	if(!empty($business->phone)){ $contact_left.="<strong>Phone:</strong><p>{$business->phone}</p>"; }
	if(!empty($business->fax)){ $contact_left.="<strong>Fax:</strong><p>{$business->fax}</p>"; }
	if(!empty($business_address)){ 
		$address = $business_address;
		$address = str_replace(' ', '+', $address);
		$address = str_replace('.', '', $address);
		$address = str_replace(',', '', $address);
		$address = str_replace(chr(13), '+', $address);
		$address = str_replace(chr(10), '+', $address);
		$address = str_replace('++', '+', $address);
		$map="<div id='lddbd_map'><iframe width='500' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://maps.google.com/maps?q={$address}&amp;ie=UTF8&amp;hq=&amp;hnear={$address}&amp;t=m&amp;z=14&amp;iwloc=A&amp;output=embed&amp;typecontrol=0'></iframe>
		<br />
		<small>
			<a target='_blank' href='http://maps.google.com/maps?q={$address}&amp;ie=UTF8&amp;hq=&amp;hnear={$address}&amp;t=m&amp;z=13&amp;source=embed' style='color:#0000FF;text-align:left'>
				View Larger Map
			</a>
		</small></div>
		"; 
	}
	if(!empty($business->name)){$name = "<h4>{$business->name}</h4>";}
	if($business->promo=='true'){ 
		$special_offer_logo="<img id='lddbd_special_offer_logo' src='".plugins_url()."/ldd-business-directory/images/special-offer.png' />";
		
	}
	if(!empty($business->description)){ $description="<p>{$business->description}</p>"; }
	if(!empty($business->promoDescription)){$special_offer_description="<h4>Special Offer:</h4><p>{$business->promoDescription}</p>";}
	if(!empty($business->categories)){
										$business_categories_list = '';
										$categories_array = str_replace('x,x', ', ', $business->categories);
										$categories_array = str_replace(',x', '', $categories_array);
										$categories_array = str_replace('x', '', $categories_array);
										$categories_array = explode(', ', $categories_array);
										foreach($categories_array as $cat_id){
											$listing_category = $wpdb->get_row(
												"
												SELECT *
												FROM $cat_table_name
												WHERE id = '{$cat_id}'
												"
											);
											$business_categories_list .= "{$listing_category->name}, ";
											
										}
										$business_categories_list = substr($business_categories_list, 0, -2);										
									}
	
	
	if(!empty($business->url)){ 
		if(strstr($business->url, 'http://')){$business_url = $business->url;}
		else{$business_url = 'http://'.$business->url;}
		$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_url}'><img src='".plugins_url()."/ldd-business-directory/images/website.png' /</a>"; 
	}
	if(!empty($business->facebook)){ 
		if(strstr($business->facebook, 'http://')){$business_facebook = $business->facebook;}
		else{$business_facebook = 'http://'.$business->facebook;}
		$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_facebook}'><img src='".plugins_url()."/ldd-business-directory/images/facebook.png' /></a>"; 
	}
	if(!empty($business->twitter)){ 
		if(strstr($business->twitter, 'http://')){$business_twitter = $business->twitter;}
		else{$business_twitter = 'http://'.$business->twitter;}
		$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_twitter}'><img src='".plugins_url()."/ldd-business-directory/images/twitter.png' /></a>"; 
	}
	if(!empty($business->linkedin)){ 
		if(strstr($business->linkedin, 'http://')){$business_linkedin = $business->linkedin;}
		else{$business_linkedin = 'http://'.$business->linkedin;}
		$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_linkedin}'><img src='".plugins_url()."/ldd-business-directory/images/linkedin.png' /></a>"; 
	}
	if(!empty($business->email)){ 
		$contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:mailToBusiness('{$business->email}', this, '{$business->name}');\"><img src='".plugins_url()."/ldd-business-directory/images/email.png' /></a>"; 
	}
	
	
	if(!empty($business->logo)){$logo_html = '<img src="'.plugins_url().'/ldd-business-directory/'.$business->logo.'"/>'; }
	
	

	$business_listing = "<div class='single_business_left'>
					{$logo_html}
				<div class='lddbd_business_contact'>
					{$contact_left}
				</div>
			</div>
			<div class='single_business_right'>
				{$special_offer_logo} {$name}
				{$description}
				{$special_offer_description}
				<ul class='business_docs'>
					{$doc_list}
				</ul>
				<div class='lddbd_business_contact'>
					{$contact_right}
				</div>
				
			</div>
			{$map}
			";
			
	$options = get_option('lddbd_options');
	$user_categorization_query = $options['user_categorization'];
	if($user_categorization_query=='Yes'){
		$categories_list = $wpdb->get_results(
			"
			SELECT *
			FROM $cat_table_name
			"
		);

		$business_categories = "<div class='lddbd_input_holder'>";
		$business_categories .= "<label for='categories_multiselect'>Categories</label>";
		$business_categories .= "<select id='lddbd_categories_multiselect' name='categories_multiselect' multiple='multiple'>";
		
		foreach($categories_list as $category){
			$business_categories .= "<option value='x{$category->id}x'>{$category->name}</option>";
		}
		
		$business_categories .= "</select>";
		$business_categories .= "<input id='lddbd_categories' type='hidden' name='categories'/>";
		$business_categories .= "</div>";
	}
	
	$submit_button_query = $options['display_button'];
	
	if($submit_button_query=='Yes'){
	
		$section_array = unserialize($options['information_sections']);
		if(!empty($section_array)){
			$other_sections = '';
			foreach($section_array as $number=>$attributes){
				$type = $attributes['type'];
				if($type=='bool'){
					$input = "<div class='lddbd_radio_holder'><input type='radio' name='{$attributes['name']}' value='Yes' />Yes&nbsp;<input type='radio' name='{$attributes['name']}' value='No' />No</div>";
				} else {
					$input = "<input type='{$type}' name='{$attributes['name']}'/>";
				}
				
				
				$other_sections.= "<div class='lddbd_input_holder'>
						<label for='{$attributes['name']}'>{$attributes['title']}</label>
						{$input}
					</div>
				";
				
			}
		}
		
		$submit_button = "<a href='javascript:void(0);' id='lddbd_add_business_button' class='lddbd_navigation_button'>Submit Listing</a>";
		$add_business_holder = "<div id='lddbd_add_business_holder'>
		 			<form id='add_business_form' action='".plugins_url()."/ldd-business-directory/lddbd_ajax.php' method='POST' enctype='multipart/form-data' target='lddbd_submission_target'>
						<div class='lddbd_input_holder'>
							<label for='name'>Business Name</label>
							<input class='required' type='text' id='lddbd_name' name='name'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='description'>Business Description</label>
							<textarea id='lddbd_description' name='description'></textarea>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='address_street'>Street</label>
							<input type='text' id='lddbd_address_street' name='address_street'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='address'>City</label>
							<input type='text' id='lddbd_address_city' name='address_city'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='lddbd_address_state'>State</label>
							{$lddbd_state_dropdown}
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='address'>Zip</label>
							<input type='text' id='lddbd_address_zip' name='address_zip'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='phone'>Contact Phone</label>
							<input class='' type='text' id='lddbd_phone' name='phone'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='fax'>Contact Fax</label>
							<input type='text' id='lddbd_fax' name='fax'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='email'>Contact Email</label>
							<input class='required' type='text' id='lddbd_email' name='email'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='contact'>Contact Name</label>
							<input class='' type='text' id='lddbd_contact' name='contact'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='url'>Website</label>
							<input type='text' id='lddbd_url' name='url'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='facebook'>Facebook Page</label>
							<input type='text' id='lddbd_facebook' name='facebook'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='twitter'>Twitter Handle</label>
							<input type='text' id='lddbd_twitter' name='twitter'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='linkedin'>Linked In Profile</label>
							<input type='text' id='lddbd_linkedin' name='linkedin'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='promo'>Special Offer</label>
							<div class='lddbd_radio_holder'>
								<input type='checkbox' id='lddbd_promo' name='promo' value='true'/>Yes&nbsp;<input type='checkbox' id='promo' name='promo' value='false'/>No
							</div>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='promo_description'>Special Offer Description</label>
							<input type='text' id='lddbd_promo_description' name='promo_description'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='logo'>Logo Image</label>
							<input class='' type='file' id='lddbd_logo' name='logo'/>
						</div>
						
						{$other_sections}
						
						{$business_categories}
						
						<div class='lddbd_input_holder'>
							<label for='login'>Login</label>
							<input class='' type='text' id='lddbd_login' name='login'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='password'>Password</label>
							<input class='' type='text' id='lddbd_password' name='password'/>
						</div>
						
						<input type='hidden' id='action' name='action' value='add'/>
						
			   			<p class='submit'>
			   				<input id='lddbd_cancel_listing' type='button' class='button-primary' value='Cancel' />
						    <input type='submit' class='button-primary' value='Add Business' />
					    </p>
		 			</form>
		 			<iframe id='lddbd_submission_target' name='lddbd_submission_target' src='".plugins_url()."/ldd-business-directory/lddbd_ajax.php' style='width:0px;height:0px;border:0px solid #fff;'></iframe>
		 		</div>";
	} else {
		$submit_button = '';
		$add_business_holder = '';
	}
	$login_form_query = $options['display_login'];
	if($login_form_query=='Yes'){
		$login_button = "<a href='javascript:void(0);' id='lddbd_business_login_button' class='lddbd_navigation_button'>Login</a>";
	} else {
		$login_button = '';
	}
	
	return "
		<link rel='stylesheet' href='".plugins_url()."/ldd-business-directory/style.css' type='text/css' media='screen' />
		<div id='lddbd_business_directory'>
	 		<div id='lddbd_business_directory_head'>
	 			<h2>Business Directory</h2>
	 			<form id='lddbd_business_search' action='' method='GET'>
	 				<input type='text' id='lddbd_search_directory' name='search_directory' value='Search the Business Directory'/>
	 				<input type='submit' value='search' />
	 				<br/>
	 				<input type='checkbox' id='lddbd_promo_filter' name='promo_filter' value='promo'/>
	 				<label id='lddbd_promo_filter_label' for='promo_filter'>Only Include Businesses with Special Offers</label>
	 			</form>
	 			<p>{$options['welcome_message']}</p>
	 			<div id='lddbd_navigation_holder'>
		 			<a href='javascript:void(0);' id='lddbd_listings_category_button' class='lddbd_navigation_button'>Categories</a>
		 			<a href='javascript:void(0);' id='lddbd_all_listings_button' class='lddbd_navigation_button'>All Listings</a>
		 			{$login_button}
		 			{$submit_button}
		 		</div>	
	 		</div>
	 		<div id='lddbd_business_directory_body'>
	 			<div id='lddbd_business_directory_list'></div>
	 			<div id='lddbd_business_directory_single'>
		 			<h3>
		 				<a href='javascript:void(0);' id='lddbd_back_to_results' onclick='javascript:backToResults();'>&larr; Back</a>
		 				<span>{$business_categories_list}</span>
		 			</h3>
	 			{$business_listing}
	 			</div>
		 		{$add_business_holder}
		 	</div>
	 	</div>
	 	<script type='text/javascript'>
	 		lddbd_business_logins = new Array(".substr($logins, 2).");
	 	</script>
	";
}
else{

	$full_business_list = $wpdb->get_results(
		"
		SELECT login
		FROM $main_table_name
		"
	);
	$business_list = $wpdb->get_results(
		"
		SELECT *
		FROM $main_table_name WHERE approved = true
		"
	);
	$category_list = $wpdb->get_results(
		"
		SELECT * FROM $cat_table_name
		ORDER BY name ASC
		"
	);
	
	$category_number = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $cat_table_name" ) );
	
	$logins = '';
	
	if($full_business_list){
		foreach($full_business_list as $business){
			$logins.=", '{$business->login}'";
		}
	}
	
	if($category_list){
		$i = 0;
		foreach($category_list as $category){
			$cat_count = 0;
			foreach($business_list as $business){
				$cat_array = explode(',', str_replace('x', '', substr($business->categories, 1)));
				if(in_array($category->id, $cat_array)){
					$cat_count++;
				}
			}
			
			
			$row_updated = $wpdb->update(
				$cat_table_name,
				array('count'=>$cat_count),
				array('id'=>$category->id),
				array('%d'),
				array('%d')
			);
			$categories.="<a class='category_link' href='javascript:void(0);' onclick='javascript:categoryListing({$category->id});'>{$category->name} ({$cat_count}) </a>";
			if($i >= $category_number/2){
				$categories.="</div><div id='lddbd_categories_right'>";
			}
			$i++;
		}
	}
	
	$options = get_option('lddbd_options');
	
	$user_categorization_query = $options['user_categorization'];
	if($user_categorization_query=='Yes'){
		$categories_list = $wpdb->get_results(
			"
			SELECT *
			FROM $cat_table_name
			"
		);

		$business_categories = "<div class='lddbd_input_holder'>";
		$business_categories .= "<label for='categories_multiselect'>Categories</label>";
		$business_categories .= "<select id='lddbd_categories_multiselect' name='categories_multiselect' multiple='multiple'>";
		
		foreach($categories_list as $category){
			$business_categories .= "<option value='x{$category->id}x'>{$category->name}</option>";
		}
		
		$business_categories .= "</select>";
		$business_categories .= "<input id='lddbd_categories' type='hidden' name='categories'/>";
		$business_categories .= "</div>";
	}
	
	$submit_button_query = $options['display_button'];
	if($submit_button_query=='Yes'){
	
		$section_array = unserialize($options['information_sections']);
		if(!empty($section_array)){
			$other_sections = '';
			foreach($section_array as $number=>$attributes){
				$type = $attributes['type'];
				if($type=='bool'){
					$input = "<div class='lddbd_radio_holder'><input type='radio' name='{$attributes['name']}' value='Yes' />Yes&nbsp;<input type='radio' name='{$attributes['name']}' value='No' />No</div>";
				} else {
					$input = "<input type='{$type}' name='{$attributes['name']}'/>";
				}
				
				
				$other_sections.= "<div class='lddbd_input_holder'>
						<label for='{$attributes['name']}'>{$attributes['title']}</label>
						{$input}
					</div>
				";
				
			}
		}
	
		$submit_button = "<a href='javascript:void(0);' id='lddbd_add_business_button' class='lddbd_navigation_button'>Submit Listing</a>";
		 $add_business_holder = "<div id='lddbd_add_business_holder'>
		 			<form id='add_business_form' action='".plugins_url()."/ldd-business-directory/lddbd_ajax.php' method='POST' enctype='multipart/form-data' target='lddbd_submission_target'>
						<div class='lddbd_input_holder'>
							<label for='name'>Business Name</label>
							<input class='required' type='text' id='lddbd_name' name='name'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='description'>Business Description</label>
							<textarea id='lddbd_description' name='description'></textarea>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='address_street'>Street</label>
							<input type='text' id='lddbd_address_street' name='address_street'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='address_city'>City</label>
							<input type='text' id='lddbd_address_city' name='address_city'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='lddbd_address_state'>State</label>
							{$lddbd_state_dropdown}
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='address_zip'>Zip</label>
							<input type='text' id='lddbd_address_zip' name='address_zip'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='phone'>Contact Phone</label>
							<input class='' type='text' id='lddbd_phone' name='phone'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='fax'>Contact Fax</label>
							<input type='text' id='lddbd_fax' name='fax'>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='email'>Contact Email</label>
							<input class='required' type='text' id='lddbd_email' name='email'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='contact'>Contact Name</label>
							<input class='' type='text' id='lddbd_contact' name='contact'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='url'>Website</label>
							<input type='text' id='lddbd_url' name='url'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='facebook'>Facebook Page</label>
							<input type='text' id='lddbd_facebook' name='facebook'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='twitter'>Twitter Handle</label>
							<input type='text' id='lddbd_twitter' name='twitter'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='linkedin'>Linked In Profile</label>
							<input type='text' id='lddbd_linkedin' name='linkedin'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='promo'>Special Offer</label>
							<div class='lddbd_radio_holder'>
								<input type='checkbox' id='lddbd_promo' name='promo' value='true'/>Yes&nbsp;<input type='checkbox' id='promo' name='promo' value='false'/>No
							</div>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='promo_description'>Special Offer Description</label>
							<input type='text' id='lddbd_promo_description' name='promo_description'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='logo'>Logo Image</label>
							<input class='' type='file' id='lddbd_logo' name='logo'/>
						</div>
						
						{$other_sections}
						{$business_categories}
						
						<div class='lddbd_input_holder'>
							<label for='login'>Login</label>
							<input class='' type='text' id='lddbd_login' name='login'/>
						</div>
						
						<div class='lddbd_input_holder'>
							<label for='password'>Password</label>
							<input class='' type='text' id='lddbd_password' name='password'/>
						</div>
						
						<input type='hidden' id='lddbd_action' name='action' value='add'/>
						
			   			<p class='submit'>
			   				<input id='lddbd_cancel_listing' type='button' class='button-primary' value='Cancel' />
						    <input type='submit' class='button-primary' value='Add Business' />
					    </p>
		 			</form>
		 			<iframe id='lddbd_submission_target' name='lddbd_submission_target' src='".plugins_url()."/ldd-business-directory/lddbd_ajax.php' style='width:0px;height:0px;border:0px solid #fff;'></iframe>
		 		</div>";
	} else {
		$submit_button = '';
		$add_business_holder = '';
	}
	$login_form_query = $options['display_login'];
	if($login_form_query=='Yes'){
		$login_button = "<a href='javascript:void(0);' id='lddbd_business_login_button' class='lddbd_navigation_button'>Login</a>";
	} else {
		$login_button = '';
	}
	
	 return "
	 	<link rel='stylesheet' href='".plugins_url()."/ldd-business-directory/style.css' type='text/css' media='screen' />
	 	<div id='lddbd_business_directory'>
	 		<div id='lddbd_business_directory_head'>
	 			<h2>Business Directory</h2>
	 			
	 			<form id='lddbd_business_search' action='' method='GET'>
	 				<input type='text' id='lddbd_search_directory' name='search_directory' value='Search the Business Directory'/>
	 				<input type='submit' value='search' />
	 				<br/>
	 				<input type='checkbox' id='lddbd_promo_filter' name='promo_filter' value='promo'/>
	 				<label id='lddbd_promo_filter_label' for='promo_filter'>Only Include Businesses with Special Offers</label>
	 			</form>
	 			<div id='lddbd_navigation_holder'>
		 			<a href='javascript:void(0);' id='lddbd_listings_category_button' class='lddbd_navigation_button'>Categories</a>
		 			<a href='javascript:void(0);' id='lddbd_all_listings_button' class='lddbd_navigation_button'>All Listings</a>
		 			{$login_button}
		 			{$submit_button}
		 		</div>	
	 		</div>
	 		<div id='lddbd_business_directory_body'>
	 			<div id='lddbd_business_directory_list'>
	 				<div id='lddbd_categories_left'>
			 			{$categories}
			 		</div>
			 	</div>
		 		{$add_business_holder}
		 	</div>
	 	</div>
	 	<script type='text/javascript'>
	 		lddbd_business_logins = new Array(".substr($logins, 2).");
	 	</script>
	 ";
	}
}
add_shortcode( 'business_directory', 'display_business_directory' );

?>