<?php

/* -------------- Settings Menu Backend Display | HTML/jQuery -------------- */

// Load up jQuery to get certain functions working.
if( is_admin() ) {
	wp_enqueue_script('jquery');
}

// Generates the Business Directory List HTML page, populates it with businesses registered in the database, and formats how they're displayed.
function lddbd_html_page(){ 
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'lddbd-text') );
	}
	
	$options = get_option('lddbd_options');
	if( !empty ( $options['directory_label'] ) ) {
		$directory_label = $options['directory_label'];
	} else {
		$directory_label = 'Business';
	}
	?>
	<div class="wrap">
		<h2><?php printf( __('%s Directory Listings', 'lddbd-text'), $directory_label ); ?></h2>
		
		<table id="lddbd_business_table" class="wp-list-table widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" id="name" class="manage-column column-name sortable"><?php _e('Name', 'lddbd-text'); ?></th>
				<th scope="col" id="email" class="manage-column column-email sortable"><?php _e('Email', 'lddbd-text'); ?></th>
				<th scope="col" id="approved" class="manage-column column-approved"><?php _e('Approved', 'lddbd-text'); ?></th>
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
		
		// Checks if any business has been selected to edit. If not then it generates the
		// list containing all the businesses stored in the database.
		if($business_list){
			foreach($business_list as $business){ ?>
			<tr id="business-<?php echo $business->id; ?>">
			<td>
			<strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=edit_business_in_directory&id=<?php echo $business->id; ?>"><?php echo stripslashes($business->name); ?></a></strong>
				<div class="row-actions">
					<a class="delete_business" href="javascript:void(0);">Delete</a>
					<?php if($business->approved == 'false') { ?>
						<a class="approve_business business_approval" href="javascript:void(0);"><?php _e('Approve', 'lddbd-text'); ?></a>
					<?php } else { ?>
						<a class="revoke_business business_approval" href="javascript:void(0);"><?php _e('Revoke Approval', 'lddbd-text'); ?></a>
					<?php } ?>
					<a class="edit_business open" href="javascript:void(0);"><?php _e('Quick Edit', 'lddbd-text'); ?></a>
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
			<form class="lddbd_edit_business_form" method="post" action="<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>" enctype='multipart/form-data'>
			<div class="lddbd_input_holder">
				<label for="name"><?php printf( __('%s Name', 'lddbd-text'), $directory_label ); ?></label>
				<input class="name required" type="text" name="name" value="<?php echo stripslashes($business->name); ?>"/>
			</div>

			<div class="lddbd_input_holder">
				<label for="description"><?php printf( __('%s Description', 'lddbd-text'), $directory_label ); ?></label>
				<textarea class="description" name="description"><?php echo $business->description; ?></textarea>
			</div>

			<div class="lddbd_input_holder">
				<label for="phone"><?php _e('Contact Phone', 'lddbd-text'); ?></label>
				<input class="phone " type="text" name="phone" value="<?php echo $business->phone; ?>"/>
			</div>

			<div class="lddbd_input_holder">
				<label for="fax"><?php _e('Contact Fax', 'lddbd-text'); ?></label>
				<input type="text" class="fax" name="fax" value="<?php echo $business->fax; ?>"/>
			</div>

			<div class="lddbd_input_holder">
				<label for="email"><?php _e('Contact Email', 'lddbd-text'); ?></label>
				<input class="email" type="text" name="email" value="<?php echo $business->email; ?>"/>
			</div>

			<div class="lddbd_input_holder">
				<label for="contact"><?php _e('Contact Name', 'lddbd-text'); ?></label>
				<input class="contact" type="text" name="contact" value="<?php echo $business->contact; ?>"/>
			</div>

			<div class="lddbd_input_holder">
				<label for="url"><?php _e('Website', 'lddbd-text'); ?></label>
				<input class="url" type="text" name="url" value="<?php echo $business->url; ?>"/>
			</div>

			<div class="lddbd_input_holder">
				<label for="login"><?php _e('Login', 'lddbd-text'); ?></label>
				<input class="login" type="text" name="login" value="<?php echo $business->login; ?>"/>
			</div>

			<input type="hidden" class="action" name="action" value="quick_edit"/>
			<input type="hidden" class="id" name="id" value="<?php echo $business->id; ?>"/>

   			<p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Update Listing', 'lddbd-text') ?>" />
			</p>
	   		</form>
	   		</td>
			</tr>
		<?php	
			}		
		} else { echo "<tr><td colspan='4'>" . __('Sorry, there are no businesses listed in the directory.', 'lddbd-text') . "</td></tr>"; }
		?>
		</tbody>
		</table>

	</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".delete_business").click(function(){
			jQuery(this).closest("tr").fadeOut(400);
			var business_id = jQuery(this).closest("tr").attr("id");
			business_id = business_id.substring(9);
			jQuery.post("<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>", {id:business_id, action:"delete"});
		});

		jQuery(".business_approval").click(function(){
			if(jQuery(this).hasClass("approve_business")){
				jQuery(this).html("Revoke Approval").removeClass("approve_business").addClass("revoke_business");
				jQuery(this).closest("td").siblings("th.approval").children('input[type="checkbox"]').attr("checked", "checked");
				var business_id = jQuery(this).closest("tr").attr("id");
				business_id = business_id.substring(9);
				jQuery.post("<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>", {id:business_id, action:"approve"});
			} else if(jQuery(this).hasClass("revoke_business")){
				jQuery(this).html("Approve").removeClass("revoke_business").addClass("approve_business");
				jQuery(this).closest("td").siblings("th.approval").children('input[type="checkbox"]').removeAttr("checked");
				var business_id = jQuery(this).closest("tr").attr("id");
				business_id = business_id.substring(9);
				jQuery.post("<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>", {id:business_id, action:"revoke"});
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

<?php
}

// Checks if the user has administrator privileges before generating the Settings page in the backend for them to see.
// Also contains submit button for Settings.
function lddbd_settings_page(){ 
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'lddbd-text') );
	}
?>
	<div class="wrap">
		<h2><?php _e('Directory Settings', 'lddbd-text'); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'lddbd_settings_group' ); ?>
   			<?php do_settings_sections( 'business_directory_settings' ); ?>
   			<p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'lddbd-text') ?>" />
		    </p>
   		</form>
 					
	</div>
<?php
}

// Checks if the user has administrator privileges before generating the Add Business page.
function lddbd_add_business_page(){
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'lddbd-text') );
	}
	global $wpdb;

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


	if( !empty ( $options['directory_label'] ) ) {
		$directory_label = $options['directory_label'];
	} else {
		$directory_label = 'Business';
	}
?>
<div class="wrap">
	<h2><?php printf( __( 'Add %s to Directory', 'lddbd-text'), $directory_label ); ?></h2>

	<form id="lddbd_add_business_form" method="post" action="<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>" enctype='multipart/form-data'>
		<div class="lddbd_input_holder">
			<label for="name"><?php printf(  __('%s Name', 'lddbd-text'), $directory_label ); ?></label>
			<input class="required" type="text" id="lddbd_name" name="name"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="description"><?php printf( __('%s Description', 'lddbd-text'), $directory_label ); ?></label>
			<textarea id="lddbd_description" name="description"></textarea>
		</div>

		<div class="lddbd_input_holder">
			<label for="address_street"><?php _e('Street', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_address_street" name="address_street">
		</div>

		<div class="lddbd_input_holder">
			<label for="address_country"><?php _e('Country', 'lddbd-text'); ?></label>
			<?php
				$lddbd_countryTxtFile = plugin_dir_path( __FILE__ ) . 'scripts/countries.txt';

				if( file_exists( $lddbd_countryTxtFile ) ) {
					// Text file containing list of supported countries
					$countryList = fopen( $lddbd_countryTxtFile, "r" );
			?>
			<select id="lddbd_address_country" name="address_country">
			<?php		
				while( !feof ( $countryList ) ) {
					$textLine = fgets( $countryList );
					$textLine = trim( $textLine );
			?>

			<option><?php esc_attr_e($textLine, 'lddbd-text'); ?></option>

			<?php	
				}
				fclose( $countryList );
			?>
			</select>
			<?php
				} else {
					echo "<p>" . __('This file does not exist!', 'lddbd-text') . "</p>";
				}
			?>
		</div>
		<div id="selectedCountryForm"></div>

		<div class="lddbd_input_holder">
			<label for="phone"><?php _e('Contact Phone', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_phone" name="phone"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="fax"><?php _e('Contact Fax', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_fax" name="fax">
		</div>

		<div class="lddbd_input_holder">
			<label for="email"><?php _e('Contact Email', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_email" name="email"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="contact"><?php _e('Contact Name', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_contact" name="contact"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="url"><?php _e('Website', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_url" name="url"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="facebook"><?php _e('Facebook Page', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_facebook" name="facebook"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="twitter"><?php _e('Twitter Handle', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_twitter" name="twitter"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="linkedin"><?php _e('Linked In Profile', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_linkedin" name="linkedin"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="promo"><?php _e('Special Offer', 'lddbd-text'); ?></label>
			<div class="lddbd_radio_holder">
				<input type="radio" id="lddbd_promo" name="promo" value="true"/>Yes&nbsp;<input type="radio" id="lddbd_promo" name="promo" value="false"/>No
			</div>
		</div>

		<div class="lddbd_input_holder">
			<label for="promo_description"><?php _e('Special Offer Description', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_promo_description" name="promo_description"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="logo"><?php _e('Logo Image', 'lddbd-text'); ?></label>
			<input class="" type="file" id="lddbd_logo" name="logo"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="login"><?php _e('Login', 'lddbd-text'); ?></label>
			<input class="required" type="text" id="lddbd_login" name="login"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="password"><?php _e('Password', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_password" name="password"/>
		</div>

<?php
if(!empty($section_array)){
	foreach($section_array as $number=>$attributes){
		$type = $attributes['type'];
		if($type=='bool'){
			$input = "<div class='lddbd_radio_holder'><input type='radio' name='{$attributes['name']}' value='Yes'/>Yes&nbsp;<input type='radio' name='{$attributes['name']}' value='No'/>No</div>";
		}
		elseif($type=='textarea') {
			$input = "<textarea name='{$attributes['name']}' value='{$business_section_array[$attributes['name']]}'></textarea>";
		} else {
			$input = "<input type='{$type}' name='{$attributes['name']}' />";
		}

		echo "<div class='lddbd_input_holder'>
				<label for='{$attributes['name']}'>{$attributes['title']}</label>
				{$input}
			</div>";
	}
}
?>

	<?php if(!empty($categories_list)){ ?>
	<div class="lddbd_input_holder">
		<label><strong><?php _e('Categories', 'lddbd-text'); ?></strong></label><br/>
		<?php foreach($categories_list as $category){ ?>

		<div class="lddbd_category_block">
			<input class="category_box" type="checkbox" name="category_<?php echo $category->id; ?>" value="x<?php echo $category->id; ?>x" />
			<label for="category_<?php echo $category->id; ?>"><?php _e(stripslashes($category->name), 'lddbd-text'); ?></label>
		</div>

		<?php } ?>
	</div>
	<?php } ?>
		
		<input id="lddbd_categories" type="hidden" name="categories"/>

		<div class="lddbd_input_holder">
			<label for="approved"><?php _e('Approved', 'lddbd-text'); ?></label>
			<input type="checkbox" id="lddbd_approved" name="approved" checked="checked" value="true"/>
		</div>

		<input type="hidden" id="lddbd_action" name="action" value="add"/>

		<p class="submit">
		    <input type="submit" class="button-primary" value="<?php _e('Add Listing', 'lddbd-text'); ?>" />
	    </p>
	</form>

<?php require( 'scripts/countrySelector.php' ); ?>
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

// Checks if the user has administrator privileges before generating the Edit Business page and requests a specific business to edit.
function lddbd_edit_business_page(){
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.', 'lddbd-text') );
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
	
	// Separates out each element pulled into the variable by removing the comma and assigning them a value in the array.
	$categories_array = explode(',', str_replace('x', '', substr($business->categories, 1)));
	if(empty($categories_array)){$categories_array=array();}
	
	// Performs a SELECT query on $doc_table_name. Used in a loop for the purpose of deleting an item.
	$files = $wpdb->get_results("SELECT * FROM $doc_table_name WHERE bus_id = '$id'");
	$files_list = '';
	
	foreach($files as $file){
		$files_list .="<li><em>{$file->doc_description}</em><input type='button' value='delete' class='file_delete' id='{$file->doc_id}_delete'/></li>";
}

$options = get_option('lddbd_options');
if( !empty ( $options['directory_label'] ) ) {
	$directory_label = $options['directory_label'];
} else {
	$directory_label = 'Business';
}
?>
<div class="wrap">
	<h2><?php printf( __('Edit %s', 'lddbd-text'), $directory_label ); ?></h2>

	<form class="lddbd_edit_business_form" method="post" action="<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>" enctype='multipart/form-data'>
		<div class="lddbd_input_holder">
			<label for="name"><?php printf( __('%s Name', 'lddbd-text'), $directory_label ); ?></label>
			<input class="required" type="text" id="lddbd_name" name="name" value="<?php echo stripslashes($business->name); ?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="description"><?php printf( __('%s Description', 'lddbd-text'), $directory_label ); ?></label>
			<textarea id="lddbd_description" name="description"><?php echo $business->description; ?></textarea>
		</div>

		<div class="lddbd_input_holder">
			<label for="address_street"><?php _e('Street', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_address_street" name="address_street" value="<?php echo $business->address_street;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="address_country"><?php _e('Country', 'lddbd-text'); ?></label>
			<?php
				$lddbd_countryTxtFile = plugin_dir_path( __FILE__ ) . 'scripts/countries.txt';
				
				if( file_exists( $lddbd_countryTxtFile ) ) {
					// Text file containing list of supported countries
					$countryList = fopen( $lddbd_countryTxtFile, "r" );
			?>
			<select id="lddbd_address_country" name="address_country">
			<?php		
				while( !feof ( $countryList ) ) {
					$textLine = fgets( $countryList );
					$textLine = trim( $textLine );
			?>

			<option <?php if( $business->address_country == $textLine ) echo "selected='selected'"; ?> ><?php esc_attr_e($textLine, 'lddbd-text'); ?></option>
			
			<?php	
				}
				fclose( $countryList );
			?>
			</select>
			<?php
				} else {
					echo "<p>" . __('This file does not exist!', 'lddbd-text') . "</p>";
				}
			?>
		</div>
		<div id="selectedCountryForm"></div>

		<div class="lddbd_input_holder">
			<label for="phone"><?php _e('Contact Phone', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_phone" name="phone" value="<?php echo $business->phone;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="fax"><?php _e('Contact Fax', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_fax" name="fax" value="<?php echo $business->fax;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="email"><?php _e('Contact Email', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_email" name="email" value="<?php echo $business->email;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="contact"><?php _e('Contact Name', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_contact" name="contact" value="<?php echo $business->contact;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="url"><?php _e('Website', 'lddbd-text'); ?></label>
			<input class="" type="text" id="lddbd_url" name="url" value="<?php echo $business->url;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="facebook"><?php _e('Facebook Page', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_facebook" name="facebook" value="<?php echo $business->facebook;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="twitter"><?php _e('Twitter Handle', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_twitter" name="twitter" value="<?php echo $business->twitter;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="linkedin"><?php _e('Linked In Profile', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_linkedin" name="linkedin" value="<?php echo $business->linkedin;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="promo"><?php _e('Special Offer', 'lddbd-text'); ?></label>
			<div class="lddbd_radio_holder">
				<input type="radio" id="lddbd_promo" name="promo" value="true" <?php if($business->promo=='true'){echo 'checked="checked"';}?>/>Yes&nbsp;<input type="radio" id="promo" name="promo" value="false" <?php if($business->promo=='false'){echo 'checked="checked"';}?>/>No
			</div>
		</div>

		<div class="lddbd_input_holder">
			<label for="promo_description"><?php _e('Special Offer Description', 'lddbd-text'); ?></label>
			<input type="text" id="lddbd_promo_description" name="promo_description" value="<?php echo $business->promoDescription;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="current_logo"><?php _e('Current Logo', 'lddbd-text'); ?></label>
			<input type="hidden" id="lddbd_current_logo" name="current_logo" value="<?php echo $business->logo; ?>"/>
		</div>

		<div class="lddbd_input_holder">
			<img src="<?php echo site_url('/wp-content/uploads/') . $business->logo; ?>"/><br />
		</div>

		<div class="lddbd_input_holder">
			<label for="logo"><?php _e('Upload New Logo', 'lddbd-text'); ?></label>
			<input type="file" id="lddbd_logo" name="logo"/>
		</div>

		<div class='lddbd_input_holder'>
			<label for="files"><?php _e('Files', 'lddbd-text'); ?></label>
			<ul>
			<?php echo $files_list; ?>
			</ul>
		</div>

		<div class='lddbd_input_holder file_input_holder'>
			<strong><?php _e('File', 'lddbd-text'); ?></strong>
			<strong><?php _e('Description', 'lddbd-text'); ?></strong>
		</div>

		<div class='lddbd_input_holder file_input_holder'>
			<input type='file' id='lddbd_file1' name='file1'/>
			<input type='text' id='lddbd_file1_description' name='file1_description'/>
		</div>

		<div class='lddbd_input_holder'>
			<input type='button' id='lddbd_add_file_upload' value='Add File Upload' />
		</div>

		<div class="lddbd_input_holder">
			<label for="login"><?php _e('Login', 'lddbd-text'); ?></label>
			<input class="required" type="text" id="lddbd_login" name="login" value="<?php echo $business->login;?>"/>
		</div>

		<div class="lddbd_input_holder">
			<label for="password"><?php _e('Password', 'lddbd-text'); ?></label>
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
				}
				elseif($type=='textarea') {
					$input = "<textarea name='{$attributes['name']}' value='{$business_section_array[$attributes['name']]}'></textarea>";
				} else {
					$input = "<input type='{$type}' name='{$attributes['name']}' value='{$business_section_array[$attributes['name']]}'/>";
				}

				echo "<div class='lddbd_input_holder'>
						<label for='{$attributes['name']}'>{$attributes['title']}</label>
						{$input}
					</div>";
			}
		}
	?>

		<?php if(!empty($categories_list)){ ?>
		<div class="lddbd_input_holder">
			<label><strong><?php _e('Categories', 'lddbd-text'); ?></strong></label><br/>
			<?php foreach($categories_list as $category){ ?>
			<div class="lddbd_category_block">
				<input class="category_box" type="checkbox" name="category_<?php echo $category->id; ?>" value="x<?php echo $category->id; ?>x" <?php if(in_array($category->id, $categories_array)){echo 'checked="checked"'; }?>/>
				<label for="category_<?php echo $category->id; ?>"><?php echo stripslashes($category->name); ?></label>
			</div>
			<?php } ?>
		</div>
		<?php } ?>

		<input id="lddbd_categories" type="hidden" name="categories" value="<?php echo $business->categories; ?>">

		<div class="lddbd_input_holder">
			<label for="approved"><?php _e('Approved', 'lddbd-text'); ?></label>
			<input type="checkbox" id="lddbd_approved" name="approved" value="true" <?php if($business->approved=='true'){echo 'checked="checked"';} ?>/>
		</div>

		<input type="hidden" id="lddbd_action" name="action" value="edit"/>
		<input type="hidden" id="lddbd_id" name="id" value="<?php echo $business->id; ?>"/>

		<p class="submit">
		    <input type="submit" class="button-primary" value="<?php _e('Update Listing', 'lddbd-text'); ?>" />
	    </p>
	</form>

</div>

<?php require( 'scripts/countryEditor.php' ); ?>
<script type="text/javascript">
	jQuery(document).ready(function(){

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
				url: '<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>',
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

	$options = get_option('lddbd_options');
	if( !empty ( $options['directory_label'] ) ) {
		$directory_label = $options['directory_label'];
	} else {
		$directory_label = 'Business';
	}

?>	
	<div class="wrap">
		<h2><?php printf( __('Choose %s to Edit', 'lddbd-text'), $directory_label ); ?></h2>
		<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php" method="get">
			<input type="hidden" name="page" id="lddbd_page" value="edit_business_in_directory" />
			<select name="id">
				<?php foreach($business_list as $business){
					echo "<option value='{$business->id}'>{$business->name}</option>";
				} ?>
			</select>

			<p class="submit">
		    	<input type="submit" class="button-primary" value="<?php _e('Find Listing', 'lddbd-text'); ?>" />
	   		</p>
		</form>
	</div>
<?php
		}
	}
}

// Checks the database for all categories available (if there are any) and generates a table listing them.
function lddbd_business_categories_page(){

$options = get_option('lddbd_options');
	if( !empty ( $options['directory_label'] ) ) {
		$directory_label = $options['directory_label'];
	} else {
		$directory_label = 'Business';
	}
?>
<div class="wrap">
<h2><?php printf( __('%s Directory Categories', 'lddbd-text'), $directory_label ); ?></h2>

<table id="lddbd_category_table" class="wp-list-table widefat fixed" cellspacing="0">
<thead>
	<tr>
		<th scope="col" id="name" class="manage-column column-name sortable"><?php _e('Name', 'lddbd-text'); ?></th>
		<th scope="col" id="count" class="manage-column column-email sortable"><?php _e('Count', 'lddbd-text'); ?></th>
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
			<strong><?php echo stripslashes($cat->name); ?></strong>
			<div class="row-actions">
				<a class="delete_category" href="javascript:void(0);"><?php _e('Delete', 'lddbd-text'); ?></a>
				<a class="edit_category open" href="javascript:void(0);"><?php _e('Edit', 'lddbd-text'); ?></a>
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
		<form class="lddbd_edit_category_form" method="post" action="<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>">
			<input type="text" name="cat_name" value="<?php echo stripslashes($cat->name); ?>" />
			<input type="hidden" name="id" value="<?php echo $cat->id; ?>"/>
   			<p class="submit">
			    <input type="submit" class="button-secondary" value="<?php _e('Save Changes', 'lddbd-text'); ?>" />
		    </p>
   		</form>
   		</td>
		</tr>

		<?php	
			}		
		} else { echo "<tr><td colspan='3'>" . __('Sorry, there are no categories listed in this directory.', 'lddbd-text') . "</td></tr>"; }
	?>
	<tr id="lddbd_add_category_row">
	<td colspan="3">
		<form id="lddbd_add_category_form" method="post" action="<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>">
			<input class="name" type="text" name="name">
			<input class="action" type="hidden" name="action" value="add_category">
   			<p class="submit">
			    <input type="submit" id="lddbd_save_category_button" class="button-secondary" value="<?php _e('Save Category', 'lddbd-text'); ?>" />
			</p>
   		</form>
	</td>
	</tr>
	<tr>
	<td colspan="3">
		<input type="button" id="lddbd_add_category_button" class="button-secondary" value="<?php _e('Add Category', 'lddbd-text'); ?>" />
	</td>
	</tr>
</tbody>
</table>

<script type="text/javascript">
// Hide the Add Category button while the Add Category Form is visible. Bring it back up when the category has been added.
jQuery(document).ready(function() {
	var add_cat_button = jQuery("#lddbd_add_category_button");
	var save_cat_button = jQuery("#lddbd_save_category_button");
	var add_cat_form = jQuery("#lddbd_add_category_form");

	add_cat_button.click(function() {
		add_cat_form.fadeIn(300);
		jQuery('input[type="text"]').val('');
		if( add_cat_form.css('visibility', 'visible') ) {
			add_cat_button.css('visibility', 'hidden');
		}
	});
	save_cat_button.click(function() {
		add_cat_button.css('visibility', 'visible');
		setTimeout(function() {
			add_cat_form.fadeOut(300);
		});
	});
});
</script>

<script type="text/javascript">
	jQuery(document).ready(function(){

		jQuery(".delete_category").click(function(){
			var cat_count = jQuery(this).closest("tr").children("td.cat_count").html();
			if(cat_count > 0){
				alert("There are businesses using this category, please remove it from their information before deleting.");
			} else {
				jQuery(this).closest("tr").fadeOut(400);
				var cat_id = jQuery(this).closest("tr").attr("id");
				cat_id = cat_id.substring(4);
				jQuery.post("<?php echo plugins_url( 'ldd-business-directory/lddbd_ajax.php' ); ?>", {id:cat_id, action:"delete_category"});
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
</div>
<?php

} // Closing curly brace for lddbd_business_categories_page()

require_once('lddbd_display.php');
?>