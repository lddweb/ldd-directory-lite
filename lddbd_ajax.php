<?php

// Calls on the file that allows for WordPress functionality to be used.
require('../../../wp-blog-header.php');
header("HTTP/1.1 200 OK");
global $wpdb;
$main_table_name = $wpdb->prefix.'lddbusinessdirectory';
$cat_table_name = $wpdb->prefix.'lddbusinessdirectory_cats';
$doc_table_name = $wpdb->prefix.'lddbusinessdirectory_docs';

/* 
* For older versions of the plugin images, documents, and other files were stored inside the plugin directory.
* These are being moved into the uploads directory for standardization purposes. For this reason the old
* directories need to have their contents copied to the new locations and then the old directories and files
* need to be removed.
*/
if ( file_exists( '../lddbd-logos' ) || file_exists( '../lddbd-files' ) ) {
	// MOVE LOGOS
	// Copy all logos from the existing LDD BD logo directory, move them into the new one, and then remove the old logos.
	$lddbd_logos_olddir = '../lddbd-logos/';
	$lddbd_logos_newdir = '../../uploads/lddbd-logos/';
	$lddbd_logos = scandir( $lddbd_logos_olddir ); // Scan the directory then create an array of any files contained within.	
	
	foreach( $lddbd_logos as $lddbd_logo ) {
		// Check the array for . (current) and .. (previous), skip them, then continue to copy files over.
		if( in_array( $lddbd_logo, array( '.', '..' ) ) ) continue;
		if( copy( $lddbd_logos_olddir.$lddbd_logo, $lddbd_logos_newdir.$lddbd_logo) ) {
			$lddbd_delete_logos[] = $lddbd_logos_olddir.$lddbd_logo;
		}
	}
	if( is_array( $lddbd_delete_logos ) ) {
		foreach( $lddbd_delete_logos as $lddbd_del_logo ) {
			unlink( $lddbd_del_logo );
		}
	}
	
	// MOVE FILES
	// Copy all files from the existing LDD BD file directory, move them into the new one, and then remove the old files.
	$lddbd_files_olddir = '../lddbd-files/';
	$lddbd_files_newdir = '../../uploads/lddbd-files/';
	$lddbd_files = scandir( $lddbd_files_olddir ); // Scan the directory then create an array of any files contained within.
	
	foreach( $lddbd_files as $lddbd_file ) {
		// Check the array for . (current) and .. (previous), skip them, then continue to copy files over.
		if( in_array( $lddbd_file, array( '.', '..' ) ) ) continue;
		if( copy( $lddbd_files_olddir.$lddbd_file, $lddbd_files_newdir.$lddbd_file) ) {
			$lddbd_delete_files[] = $lddbd_files_olddir.$lddbd_file;
		}
	}
	if( is_array( $lddbd_delete_files ) ) {
		foreach( $lddbd_delete_files as $lddbd_del_file ) {
			unlink( $lddbd_del_file );
		}
	}
	
	// REMOVE OLD DIRECTORIES
	// If only 2 items remain in either directory ( . and .. ) remove the directories
	if( count( $lddbd_logos ) <= 2 || count( $lddbd_files ) <= 2 ) {
		rmdir( $lddbd_logos_olddir );
		rmdir( $lddbd_files_olddir );
	}
}

// If the LDD BD logo or file directories do not exist then create them and grant them the appropriate privileges.
// This should only be executed in the event that this is the first time using this plugin (and not upgrading).
if ( !file_exists( '../../uploads/lddbd-logos' ) || !file_exists( '../../uploads/lddbd-files' ) ) {
	mkdir( '../../uploads/lddbd-logos', 0755, true );
	mkdir( '../../uploads/lddbd-files', 0755, true );
}

// The basis for all the AJAX actions that control facets of the back end and the front end.
$action = $_POST['action'];

// If the item is approved then make the business listing viewable on the front end.
if($action == 'approve'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$id = $_POST['id'];
	$wpdb->update(
		$main_table_name,
		array(
			'approved'=>'true'
		),
		array('id'=>$id),
		array('%s'),
		array('%d')
	);
}
// If the item is presently viewable on the front end then remove its approval so that it is hidden.
else if($action == 'revoke'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$id = $_POST['id'];
	$wpdb->update(
		$main_table_name,
		array(
			'approved'=>'false'
		),
		array('id'=>$id),
		array('%s'),
		array('%d')
	);

}
// Remove the business from the table in the database.
else if($action == 'delete'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$id = $_POST['id'];
	$wpdb->query(
		"
		DELETE FROM $main_table_name
		WHERE id = $id
		"
	);
}
// Add the business and all the corresponding elements assigned to it into the database.
else if($action == 'add'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$options = get_option('lddbd_options');
	$section_array = unserialize($options['information_sections']);
	$save_additional_sections = array();
	if( is_array( $section_array ) ) {
		foreach($section_array as $section){
			$name = $section['name'];
			$value = stripslashes($_POST[$name]);
			$save_additional_sections[$name]=$value;
		}
	}
	$name = stripslashes($_POST['name']);
	$description = stripslashes($_POST['description']);
	$address_street = $_POST['address_street'];
	$address_city = $_POST['address_city'];
	$address_state = $_POST['address_state'];
	$address_zip = $_POST['address_zip'];
	$address_country = $_POST['address_country'];
	$categories = stripslashes($_POST['categories']);
	$phone = $_POST['phone'];
	$fax = $_POST['fax'];
	$email = $_POST['email'];
	$contact = $_POST['contact'];
	$url = $_POST['url'];
	$facebook = $_POST['facebook'];
	$twitter = $_POST['twitter'];
	$linkedin = $_POST['linkedin'];
	$promo = $_POST['promo'];
		if($promo=='true'){$promo='true';}
		else{$promo='false';}
	$promoDescription = stripslashes($_POST['promo_description']);
	$logo = $_FILES['logo'];
	$login = $_POST['login'];
	$password = $_POST['password'];
	$approved = $_POST['approved'];
		if($approved=='true'){$approved='true';}
		else{$approved='false';}

	$allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'xls', 'xslx', 'doc', 'docx', 'pdf');
	preg_match('/\.('.implode($allowedExtensions, '|').')$/', $_FILES['logo']['name'], $fileExt);
	$logo_path = 'lddbd-logos/'.$login.'_logo.'.$fileExt[1];
	while (file_exists($logo_path)) {
		$modifier = rand(0, 1000);
		$logo_path = 'lddbd-logos/'.$login.'_logo'.$modifier.'.'.$fileExt[1];
	}

	if(move_uploaded_file($_FILES['logo']['tmp_name'], "../../uploads/" . $logo_path)) {
    	// echo 'file uploaded';
   	}
   	
	$row_added = $wpdb->insert(
		$main_table_name,
		array(
			'createDate' => current_time('mysql'),
			'name' => $name,
			'description' => $description,
			'address_street' => $address_street,
			'address_city' => $address_city,
			'address_state' => $address_state,
			'address_zip' => $address_zip,
			'address_country' => $address_country,
			'categories' => $categories,
			'phone' => $phone,
			'fax' => $fax,
			'email' => $email,
			'contact' => $contact,
			'url' => $url,
			'facebook' => $facebook,
			'twitter' => $twitter,
			'linkedin' => $linkedin,
			'promo' => $promo,
			'promoDescription' => $promoDescription,
			'logo' => $logo_path,
			'login' => $login,
			'password' => $password,
			'approved' => $approved,
			'other_info' => serialize($save_additional_sections)
		)
	);
	
	header('Location: '.get_bloginfo('url').'/wp-admin/admin.php?page=business_directory');
}
else if($action == 'edit'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$options = get_option('lddbd_options');
	$section_array = unserialize($options['information_sections']);
	$save_additional_sections = array();
	if( is_array( $section_array ) ) {
		foreach($section_array as $section){
			$name = $section['name'];
			$value = stripslashes($_POST[$name]);
			$save_additional_sections[$name]=$value;
		}
	}
	$update_array = array();
	if(!empty($_POST['name'])){$update_array['name'] = stripslashes($_POST['name']);}
	if(!empty($_POST['description'])){$update_array['description'] = stripslashes($_POST['description']);}
	if(!empty($_POST['address_street'])){$update_array['address_street'] = $_POST['address_street'];}
	if(!empty($_POST['address_city'])){$update_array['address_city'] = $_POST['address_city'];}
	if(!empty($_POST['address_state'])){$update_array['address_state'] = $_POST['address_state'];}
	if(!empty($_POST['address_zip'])){$update_array['address_zip'] = $_POST['address_zip'];}
	if(!empty($_POST['address_country'])){$update_array['address_country'] = $_POST['address_country'];}
	if(!empty($_POST['phone'])){$update_array['phone'] = $_POST['phone'];}
	if(!empty($_POST['fax'])){$update_array['fax'] = $_POST['fax'];}
	if(!empty($_POST['email'])){$update_array['email'] = $_POST['email'];}
	if(!empty($_POST['contact'])){$update_array['contact'] = $_POST['contact'];}
	if(!empty($_POST['url'])){$update_array['url'] = $_POST['url'];}
	if(!empty($_POST['facebook'])){$update_array['facebook'] = $_POST['facebook'];}
	if(!empty($_POST['twitter'])){$update_array['twitter'] = $_POST['twitter'];}
	if(!empty($_POST['linkedin'])){$update_array['linkedin'] = $_POST['linkedin'];}
	if(isset($_POST['promo']) && $_POST['promo']=='true'){
		$update_array['promo'] ='true';
	}
	else{$update_array['promo'] = 'false';}
	if(!empty($_POST['promo_description'])){$update_array['promoDescription'] = stripslashes($_POST['promo_description']);}
	if(!empty($_POST['current_logo'])){$update_array['logo'] = $_POST['current_logo'];}
	if(!empty($_POST['login'])){$update_array['login'] = $_POST['login'];}
	if(!empty($_POST['password'])){$update_array['password'] = $_POST['password'];}
	if(!empty($_POST['approved'])){
		if($_POST['approved']=='true'){$update_array['approved']='true';}
		else{$update_array['approved']='false';}
	}
	if(!empty($save_additional_sections)){$update_array['other_info'] = serialize($save_additional_sections);}
	if(!empty($_POST['categories'])){$update_array['categories'] = stripslashes($_POST['categories']);}

	if(!empty($_FILES['logo']['name'])){
		$allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'xls', 'xslx', 'doc', 'docx', 'pdf');
		preg_match('/\.('.implode($allowedExtensions, '|').')$/', $_FILES['logo']['name'], $fileExt);
		$logo_path = 'lddbd-logos/'.$_POST['login'].'_logo.'.$fileExt[1];
		while (file_exists($logo_path)) {
			$modifier = rand(0, 1000);
			$logo_path = 'lddbd-logos/'.$_POST['login'].'_logo'.$modifier.'.'.$fileExt[1];
		}

		if(move_uploaded_file($_FILES['logo']['tmp_name'], "../../uploads/" . $logo_path)) {
	    	$update_array['logo'] = $logo_path;
	   	}
	}

	for($i=1; $i<8; $i++){
		if(!empty($_FILES['file'.$i]['name'])){
			$allowedExtensions = array('jpg', 'jpeg', 'pdf', 'xls', 'xslx', 'doc', 'docx', 'txt');
			preg_match('/\.('.implode($allowedExtensions, '|').')$/', $_FILES['file'.$i]['name'], $fileExt);
			$file_path = 'lddbd-files/'.$_POST['login'].'_'.$i.'.'.$fileExt[1];
			while (file_exists($file_path)) {
				$modifier = rand(0, 1000);
				$file_path = 'lddbd-files/'.$_POST['login'].'_'.$i.'_'.$modifier.'.'.$fileExt[1];
			}

			if(move_uploaded_file($_FILES['file'.$i]['tmp_name'], "../../uploads/" . $file_path)) {
		    	// echo 'file uploaded';
		   	}

		   	$row_added = $wpdb->insert(
				$doc_table_name,
				array(
					'bus_id' => $_POST['id'],
					'doc_path' => $file_path,
					'doc_description' => $_POST['file'.$i.'_description']
				)
			);
		}
		
	}

	$row_updated = $wpdb->update(
		$main_table_name,
		$update_array,
		array('id'=>$_POST['id']),
		array('%s'),
		array('%d')
	);

	if($_POST['from']=='frontend'){
		echo "Your information has been updated.";
	} else {
		header('Location: '.get_bloginfo('url').'/wp-admin/admin.php?page=business_directory');
	}
}
else if($action == 'quick_edit'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$update_array = array();
	if(!empty($_POST['name'])){$update_array['name'] = stripslashes($_POST['name']);}
	if(!empty($_POST['description'])){$update_array['description'] = stripslashes($_POST['description']);}
	if(!empty($_POST['phone'])){$update_array['phone'] = $_POST['phone'];}
	if(!empty($_POST['fax'])){$update_array['fax'] = $_POST['fax'];}
	if(!empty($_POST['email'])){$update_array['email'] = $_POST['email'];}
	if(!empty($_POST['contact'])){$update_array['contact'] = $_POST['contact'];}
	if(!empty($_POST['url'])){$update_array['url'] = $_POST['url'];}
	if(!empty($_POST['facebook'])){$update_array['facebook'] = $_POST['facebook'];}
	if(!empty($_POST['twitter'])){$update_array['twitter'] = $_POST['twitter'];}
	if(!empty($_POST['linkedin'])){$update_array['linkedin'] = $_POST['linkedin'];}
	if(isset($_POST['promo']) && $_POST['promo']=='true'){
		$update_array['promo'] ='true';
	}
	else{$update_array['promo'] = 'false';}	
	if(!empty($_POST['promo_description'])){$update_array['promoDescription'] = stripslashes($_POST['promo_description']);}
	if(!empty($_POST['logo'])){$update_array['logo'] = $_FILES['logo'];}
	else if(!empty($_POST['current_logo'])){$update_array['logo'] = $_POST['current_logo'];}
	if(!empty($_POST['login'])){$update_array['login'] = $_POST['login'];}
	if(!empty($_POST['approved'])){
		if($_POST['approved']=='true'){$update_array['approved']='true';}
		else{$update_array['approved']='false';}
	}

	if(!empty($_FILES['logo']['name'])){
		$allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'xls', 'xslx', 'doc', 'docx', 'pdf');
		preg_match('/\.('.implode($allowedExtensions, '|').')$/', $_FILES['logo']['name'], $fileExt);
		$logo_path = 'lddbd-logos/'.$login.'_logo.'.$fileExt[1];
		while (file_exists($logo_path)) {
			$modifier = rand(0, 1000);
			$logo_path = 'lddbd-logos/'.$login.'_logo'.$modifier.'.'.$fileExt[1];
		}

		$update_array['logo'] = $logo_path;
		if(move_uploaded_file($_FILES['logo']['tmp_name'], "../" . $logo_path)) { 
	    	// echo 'file uploaded';
	   	}
	} else if(!empty($_POST['current_logo'])){
		$update_array['logo'] = $_POST['current_logo'];
	}

	$row_updated = $wpdb->update(
		$main_table_name,
		$update_array,
		array('id'=>$_POST['id']),
		array('%s'),
		array('%d')
	);
}
else if($action == 'search'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$search = $_POST['query'];
	$promo = '';
	if(isset($_POST['promo_filter']) && $_POST['promo_filter']=='promo'){
		$promo = "AND (promo = true)";
	}
	$search_results = $wpdb->get_results(
		"
		SELECT *
		FROM $main_table_name
		WHERE (approved = true)
		{$promo}
		AND (id like '%{$search}%'
		OR name like '%{$search}%'
		OR description like '%{$search}%'
		OR phone like '%{$search}%'
		OR fax like '%{$search}%'
		OR email like '%{$search}%'
		OR contact like '%{$search}%'
		OR url like '%{$search}%'
		OR facebook like '%{$search}%'
		OR twitter like '%{$search}%'
		OR linkedin like '%{$search}%'
		OR promoDescription like '%{$search}%'
		OR login like '%{$search}%')	
		ORDER BY name ASC
		"
	);
	echo "<h3><a href='javascript:void(0);' id='lddbd_back_to_categories' onclick='javascript: backToCategories();'>&larr; Categories</a>Search Results</h3>";
	if($search_results){
		foreach($search_results as $business){
			$contact = '';
			$logo_html = '';
			$contact_right = '';

			if(!empty($business->phone)){ $contact.="<li>Phone: {$business->phone}</li>"; }
			if(!empty($business->fax)){ $contact.="<li>Fax: {$business->fax}</li>"; }

			if(!empty($business->url)){
				if(strstr($business->url, 'http://')){$business_url = $business->url;}
				else{$business_url = 'http://'.$business->url;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_url}'><img src='".plugins_url( 'ldd-business-directory/images/website.png' )."' /></a>"; 
			}
			if(!empty($business->facebook)){
				if(strstr($business->facebook, 'http://')){$business_facebook = $business->facebook;}
				else{$business_facebook = 'http://'.$business->facebook;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_facebook}'><img src='".plugins_url( 'ldd-business-directory/images/facebook.png' )."' /></a>"; 
			}
			if(!empty($business->twitter)){ 
				if(strstr($business->twitter, 'http://')){$business_twitter = $business->twitter;}
				else{$business_twitter = 'http://'.$business->twitter;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_twitter}'><img src='".plugins_url( 'ldd-business-directory/images/twitter.png' )."' /></a>"; 
			}
			if(!empty($business->linkedin)){ 
				if(strstr($business->linkedin, 'http://')){$business_linkedin = $business->linkedin;}
				else{$business_linkedin = 'http://'.$business->linkedin;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_linkedin}'><img src='".plugins_url( 'ldd-business-directory/images/linkedin.png' )."' /></a>"; 
			}
			if(!empty($business->email)){
				$bizname_esc = addslashes($business->name); // In the event that our business has a single or double quote in it
				$contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:mailToBusiness('{$business->email}'), this, '{$bizname_esc}';\"><img src='".plugins_url( 'ldd-business-directory/images/email.png' )."' /></a>"; }
			if($business->promo=='true'){
				$contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:singleBusinessListing({$business->id});\"><img src='".plugins_url( 'ldd-business-directory/images/special-offer.png' )."' /></a>"; }
			if(!empty($business->logo)){
				$logo_html = "<div class='lddbd_logo_holder' onclick='javascript:singleBusinessListing({$business->id});'><img src='".site_url('/wp-content/uploads/')."{$business->logo}' /></div>"; }

		$biz_name = stripslashes($business->name);

			echo "<div class='lddbd_business_listing'>
					{$logo_html}
					<a href='javascript:void(0);' id='{$business->id}_business_detail' class='business_detail_link' onclick='javascript:singleBusinessListing({$business->id});'>{$biz_name}</a>
					<ul class='lddbd_business_contact'>
						{$contact}
					</ul>
					<div class='lddbd_business_contact'>
						{$contact_right}
					</div>
				</div>";
		}
	}
}
else if($action == 'categories_list'){
	global $main_table_name, $doc_table_name, $cat_table_name;
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

	$category_number = $wpdb->get_var( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE id = %d", $id ) );

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

			$cat_name = stripslashes($category->name);
			
			$row_updated = $wpdb->update(
				$cat_table_name,
				array('count'=>$cat_count),
				array('id'=>$category->id),
				array('%d'),
				array('%d')
			);
			$categories.="<a class='category_link' href='javascript:void(0);' onclick='javascript:categoryListing({$category->id});'>{$cat_name} ({$cat_count}) </a>";
			if($i >= $category_number/2){
				$categories.="</div><div id='lddbd_categories_right'>";
			}
			$i++;
		}
	}
	echo "<div id='lddbd_categories_left'>
 			{$categories}
 		</div>";
}
else if($action == 'category_filter'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$category_results = $wpdb->get_results(
		"
		SELECT *
		FROM $main_table_name
		WHERE categories like '%x".$_POST['cat_id']."x%'
		AND approved = true
		ORDER BY name ASC
		"
	);
	$category = $wpdb->get_row(
		"
		SELECT *
		FROM $cat_table_name
		WHERE id = '{$_POST['cat_id']}'
		"
	);

	$cat_name = stripslashes($category->name);

	echo "<h3><a href='javascript:void(0);' id='lddbd_back_to_categories' onclick='javascript: backToCategories();'>&larr; Categories</a>{$cat_name}</h3>";
	if($category_results){
		foreach($category_results as $business){
			$contact = '';
			$logo_html = '';
			$contact_right = '';

			if(!empty($business->phone)){ $contact.="<li>Phone: {$business->phone}</li>"; }
			if(!empty($business->fax)){ $contact.="<li>Fax: {$business->fax}</li>"; }
			
			if(!empty($business->url)){
				if(strstr($business->url, 'http://')){$business_url = $business->url;}
				else{$business_url = 'http://'.$business->url;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_url}'><img src='".plugins_url( 'ldd-business-directory/images/website.png' )."' /></a>"; 
			}
			if(!empty($business->facebook)){ 
				if(strstr($business->facebook, 'http://')){$business_facebook = $business->facebook;}
				else{$business_facebook = 'http://'.$business->facebook;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_facebook}'><img src='".plugins_url( 'ldd-business-directory/images/facebook.png' )."' /></a>"; 
			}
			if(!empty($business->twitter)){ 
				if(strstr($business->twitter, 'http://')){$business_twitter = $business->twitter;}
				else{$business_twitter = 'http://'.$business->twitter;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_twitter}'><img src='".plugins_url( 'ldd-business-directory/images/twitter.png' )."' /></a>"; 
			}
			if(!empty($business->linkedin)){ 
				if(strstr($business->linkedin, 'http://')){$business_linkedin = $business->linkedin;}
				else{$business_linkedin = 'http://'.$business->linkedin;}
				$contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_linkedin}'><img src='".plugins_url( 'ldd-business-directory/images/linkedin.png' )."' /></a>"; 
			}
			if(!empty($business->email)){
				$bizname_esc = addslashes($business->name); // In the event that our business has a single or double quote in it
				$contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:mailToBusiness('{$business->email}'), this, '{$bizname_esc}';\"><img src='".plugins_url( 'ldd-business-directory/images/email.png' )."' /></a>"; }
			if($business->promo=='true'){
				$contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:singleBusinessListing({$business->id});\"><img src='".plugins_url( 'ldd-business-directory/images/special-offer.png' )."' /></a>"; }
			if(!empty($business->logo)){
				$logo_html = "<div class='lddbd_logo_holder' onclick='javascript:singleBusinessListing({$business->id});'><img src='".site_url('/wp-content/uploads/')."{$business->logo}'/></div>"; }

			echo "<div class='lddbd_business_listing'>
					{$logo_html}
					<a href='javascript:void(0);' id='{$business->id}_business_detail' class='business_detail_link' onclick='javascript:singleBusinessListing({$business->id});'>{$business->name}</a>
					<ul class='lddbd_business_contact'>
						{$contact}
					</ul>
					<div class='lddbd_business_contact'>
						{$contact_right}
					</div>
				</div>";
		}
	} else { echo "<div class='lddbd_business_listing'>Sorry, this category is empty.</div>"; }
}
else if($action == 'edit_category'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$id = $_POST['id'];
	// $wpdb->show_errors();
	$wpdb->update(
		$cat_table_name,
		array(
			'name'=>$_POST['name']
		),
		array('id'=>$id),
		array('%s'),
		array('%d')
	);

}
else if($action == 'add_category'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$row_added = $wpdb->insert(
		$cat_table_name,
		array(
			'name' => $_POST['name'],
			'count' => 0
		)
	);
	
echo "<table><tr id='cat-{$wpdb->insert_id}'>".
		"<td>".
			"<strong>{$_POST['name']}</strong>".
			"<div class='row-actions'>".
				"<a class='delete_category' href='javascript:void(0)'>Delete</a>".
				"<a class='edit_category open' href='javascript:void(0);'>Edit</a>".
			"</div>".
		"</td>".
		"<td>0</td>".
		"<td>{$wpdb->insert_id}</td>".
	"</tr>".
	"<tr class='lddbd_edit_category_row'>".
		"<td colspan='3'>".
			"<form class='lddbd_edit_category_form' method='post' action='".plugins_url( 'ldd-business-directory/lddbd_ajax.php' )."'>".
				"<input type='text' name='cat_name' value='{$_POST['name']}'>".
				"<input type='hidden' name='action' value='edit_category'/>".
				"<input type='hidden' name='id' value='{$wpdb->insert_id}'/>".
	   			"<p class='submit'>".
				    "<input type='submit' class='button-secondary' value='Save Changes' />".
			    "</p>".
	   		"</form>".
   		"</td>".
	"</tr></table>";

echo "
<script type='text/javascript'>
jQuery('.delete_category').click(function(){
	jQuery(this).closest('tr').fadeOut(400, function() {
		jQuery(this).remove();
	});
	var cat_id = jQuery(this).closest('tr').attr('id');
	cat_id = cat_id.substring(4);
	jQuery.post('" . plugins_url( 'ldd-business-directory/lddbd_ajax.php' ) . "', {id:cat_id, action:'delete_category'});
});

jQuery('.edit_category').click(function(){
	if(jQuery(this).hasClass('open')){
		jQuery(this).html('Done Editing').removeClass('open').addClass('close');
		jQuery(this).closest('tr').next('tr.lddbd_edit_category_row').fadeIn(400);
	} else if(jQuery(this).hasClass('close')){
		jQuery(this).html('Edit').removeClass('close').addClass('open');
		jQuery(this).closest('tr').next('tr.lddbd_edit_category_row').fadeOut(400);
	}	
});

jQuery('.lddbd_edit_category_form').submit(function(){
	var this_row = jQuery(this).closest('tr.lddbd_edit_category_row');
	var action = jQuery(this).attr('action');
	var cat_id = jQuery(this).find('input[name=\"id\"]').val();
	var new_name = jQuery(this).find('input[name=\"cat_name\"]').val();
	
	var quick_data = {
		name: new_name,
		id: cat_id,
		action: 'edit_category'
	};
	
	jQuery.ajax({
		type: 'POST',
		url: action, 
		data: quick_data,
		success: function(data){
			this_row.fadeOut(400);
			jQuery('#cat-'+cat_id+' td strong').html(new_name);
			jQuery('#cat-'+cat_id+' td div.row-actions a.edit_category').html('Edit').removeClass('close').addClass('open');
		}	
	});
	return false;
});
</script>
";
}
else if($action == 'delete_category'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$id = $_POST['id'];
	$wpdb->query(
		"
		DELETE FROM $cat_table_name
		WHERE id = $id
		"
	);
}
else if($action == 'login'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$login = $_POST['login'];
	$business = $wpdb->get_row("SELECT * FROM $main_table_name WHERE login = '{$login}'");

	if($business){
		if($business->password == $_POST['password']){

		$files = $wpdb->get_results("SELECT * FROM $doc_table_name WHERE bus_id = '{$business->id}'");
		$files_list = '';

		foreach($files as $file){
			$files_list .="<li><em>{$file->doc_description}</em><input type='button' value='delete' class='file_delete' id='{$file->doc_id}_delete'/></li>";
		}

		$promo = '';
		if($business->promo == 'true'){
			$promo = 'checked';
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

			$categories_array = explode(',', str_replace('x', '', substr($business->categories, 1)));
			if(empty($categories_array)){$categories_array=array();}

			$business_categories = "<div class='lddbd_input_holder'>";
			$business_categories .= "<strong>Categories</strong>";

			foreach($categories_list as $category){
				$cat_name = stripslashes($category->name);
				$checked = '';
				if(in_array($category->id, $categories_array)){$checked = 'checked';}
				$business_categories .= "<div class='lddbd_category_block'>";
				$business_categories .= "<input type='checkbox' class='category_box' name='category_{$category->id}' value='x{$category->id}x' {$checked}/>";
				$business_categories .= "<label for='category_{$category->id}'>{$cat_name}</label>";
				$business_categories .= "</div>";
			}

			$business_categories .= "<input id='lddbd_categories' type='hidden' name='categories' value='{$business->categories}'/>";
			$business_categories .= "</div>";
		}

		$section_array = unserialize($options['information_sections']);
		if(!empty($section_array)){
			$other_sections = '';
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

				$other_sections.= "<div class='lddbd_input_holder'>
						<label for='{$attributes['name']}'>{$attributes['title']}</label>
						{$input}
					</div>
				";

			}
		}

$lddbd_countryTxtFile = plugin_dir_path( __FILE__ ) .  'scripts/countries.txt';

	// Text file containing list of supported countries
	$countryList = fopen( $lddbd_countryTxtFile, "r" );

$optionLine = "";
// Open the text file and build our select list of countries
while( !feof ( $countryList ) ) {
	$textLine = fgets( $countryList );
	$textLine = trim( $textLine );
	
	$optionLine .= "<option>$textLine</option>\n";
	
	if( $business->address_country == $textLine ) {
		$optionLine .= "<option selected='selected'>$textLine</option>\n";
	}
}
fclose( $countryList );

// Call the script file with the jQuery controls for displaying form elements for specific countries
ob_start();
include( 'scripts/countryEditor.php' );
$countryEditor = ob_get_clean();

	if( !empty ( $options['directory_label'] ) ) {
		$directory_label = $options['directory_label'];
	} else {
		$directory_label = 'Business';
	}

echo "<form id='lddbd_edit_business_form' action='".plugins_url( 'ldd-business-directory/lddbd_ajax.php' )."' method='POST' enctype='multipart/form-data' target='lddbd_edit_submission_target'>
	<div class='lddbd_input_holder'>
		<label for='name'>{$directory_label} Name</label>
		<input class='required' type='text' id='lddbd_name' name='name' value='{$business->name}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='description'>{$directory_label} Description</label>
		<textarea id='lddbd_description' name='description'>{$business->description}</textarea>
	</div>

	<div class='lddbd_input_holder'>
		<label for='address_street'>Street</label>
		<input type='text' id='lddbd_address_street' name='address_street' value='{$business->address_street}'>
	</div>
	
	<div class='lddbd_input_holder'>
	<label for='address_country'>Country</label>

	<select id='lddbd_address_country' name='address_country'>
		{$optionLine}
	</select>
	
	</div>
	<div id='selectedCountryForm'></div>

	<div class='lddbd_input_holder'>
		<label for='phone'>Contact Phone</label>
		<input class='' type='text' id='lddbd_phone' name='phone' value='{$business->phone}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='fax'>Contact Fax</label>
		<input type='text' id='lddbd_fax' name='fax' value='{$business->fax}'>
	</div>

	<div class='lddbd_input_holder'>
		<label for='email'>Contact Email</label>
		<input class='' type='text' id='lddbd_email' name='email' value='{$business->email}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='contact'>Contact Name</label>
		<input class='' type='text' id='lddbd_contact' name='contact' value='{$business->contact}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='url'>Website</label>
		<input class='' type='text' id='lddbd_url' name='url' value='{$business->url}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='facebook'>Facebook Page</label>
		<input type='text' id='lddbd_facebook' name='facebook' value='{$business->facebook}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='twitter'>Twitter Handle</label>
		<input type='text' id='lddbd_twitter' name='twitter' value='{$business->twitter}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='linkedin'>Linked In Profile</label>
		<input type='text' id='lddbd_linkedin' name='linkedin' value='{$business->linkedin}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='promo'>Special Offer</label>
		<input type='checkbox' id='lddbd_promo' name='promo' value='true' {$promo}/>
	</div>
	
	<div class='lddbd_input_holder'>
		<label for='promo_description'>Special Offer Description</label>
		<input type='text' id='lddbd_promo_description' name='promo_description' value='{$business->promoDescription}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='current_logo'>Current Logo</label>
		<input type='hidden' id='lddbd_current_logo' name='current_logo' value='{$business->logo}'/>
	</div>

	<div class='lddbd_input_holder'>
		<img src='wp-content/uploads/{$business->logo}'/>
	</div>

	<div class='lddbd_input_holder'>
		<label for='logo'>Upload New Logo</label>
		<input class='' type='file' id='lddbd_logo' name='logo'/>
	</div>

	{$other_sections}
	
	{$business_categories}

	<div class='lddbd_input_holder'>
		<label for='password'>Password</label>
		<input class='required' type='text' id='lddbd_password' name='password' value='{$business->password}'/>
	</div>
	
	<div class='lddbd_input_holder'>
		<strong>Files</strong>
		<ul>
		{$files_list}
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
	
	<input type='hidden' id='lddbd_id' name='id' value='{$business->id}'/>
	<input type='hidden' id='lddbd_action' name='action' value='edit'/>
	<input type='hidden' id='lddbd_from' name='from' value='frontend'/>
	
	<p class='submit'>
		<input type='button' id='lddbd_login_cancel' value='Cancel' />
	    <input type='submit' class='button-primary' value='Submit Changes' />
   	 </p>
	</form>
	{$countryEditor}
			<iframe id='lddbd_edit_submission_target' name='lddbd_edit_submission_target' src='".plugins_url( 'ldd-business-directory/lddbd_ajax.php' )."' style='width:0px;height:0px;border:0px solid #fff;'></iframe>
			
			<script type='text/javascript'>
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

				jQuery('#lddbd_edit_business_form').submit(function(){
					// jQuery('#lddbd_edit_business_form').contents().fadeTo(200, 0.1);
					jQuery('.button-primary').attr('disabled', 'disabled');
					jQuery('#lddbd_edit_submission_target').load(function(){
						jQuery('#lddbd_edit_business_form').html('Your information has been updated.');
						alert('Your information has been updated.');
	 					var page = window.location.href.split('?')[0];
	 					window.location.href = page+'?business={$business->id}';
	 				});
 				});

 				var file_input_count = 1;
 				jQuery('#lddbd_add_file_upload').click(function(){
 					if(file_input_count<5){
	 					file_input_count++;
	 					jQuery('.file_input_holder').last().after('<div class=\'lddbd_input_holder file_input_holder\'><input type=\'file\' id=\'lddbd_file'+file_input_count+'\' name=\'file'+file_input_count+'\'/><input type=\'text\' id=\'lddbd_file'+file_input_count+'_description\' name=\'file'+file_input_count+'_description\'/></div>');
	 				}	
 				});

 				jQuery('input.file_delete').click(function(){
 					var this_placeholder = jQuery(this);
 					var doc_id = jQuery(this).attr('id');
 					doc_id = parseInt(doc_id);
 					jQuery.ajax({
						type: 'POST',
						url: '".plugins_url( 'ldd-business-directory/lddbd_ajax.php' )."',
						data: {doc_id: doc_id, action: 'delete_doc'},
						success: function(data){
							this_placeholder.parent().slideUp('200');
						}
					});
 				});

 				jQuery('#lddbd_login_cancel').click(function(){
 					window.location.reload();
 				});
 			});
			</script>";
		}
		else {
			echo "Sorry, the password you entered was incorrect, please try again.";
		}
	}else{
		echo "Sorry, the login you entered was not on file, please try again.";
	}
}
else if($action == 'delete_doc'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$id = $_POST['doc_id'];
	$wpdb->query(
		"
		DELETE FROM $doc_table_name
		WHERE doc_id = $id
		"
	);
}
else if($action == 'email'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$email = $_POST['email'];
	$from = $_POST['from'];
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$message = $_POST['message'];
	$subject = 'Business Directory Email';
	
	if(!empty($name)){$subject.=' From '.$name;}
	if(!empty($phone)){$message.='\r\n Phone:'.$phone;}
	$headers = 'From: '.$from."\r\n".'X-Mailer: PHP/'.phpversion();
	
	$mail = mail($email, $subject, $message, $headers);
}
else if($action == 'recover_password'){
	global $main_table_name, $doc_table_name, $cat_table_name;
	$login = $_POST['login'];
	$business = $wpdb->get_row(
		"
		SELECT *
		FROM $main_table_name
		WHERE login = '{$login}'
		"
	);
	
	if(empty($business)){
		echo 'no login';
	} else {
		$charArray = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
		$password = '';
		for($i=0; $i<10; $i++){
			$password.=$charArray[rand(0, (count($charArray)-1))];
		}
		
		$id = $business->id;
		$wpdb->update(
			$main_table_name,
			array(
				'password'=>$password
			),
			array('id'=>$id),
			array('%s'),
			array('%d')
		);
		$message = "Your new business directory password is $password";
		$mail = mail($business->email, 'Business Directory Password', $message);
		echo 'success';
	}
}
?>