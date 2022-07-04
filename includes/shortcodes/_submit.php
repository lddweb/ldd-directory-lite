<?php
/**
 * Submit a listing view controller and other functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Alias for wp_dropdown_categories() that uses our settings array to determine the output.
 */
function ldl_submit_categories_dropdown($selected = 0, $id = 'category', $classes = array('form-control')) {

	if (is_string($classes))
		$classes = explode(' ', $classes);

	$classes = apply_filters('lddlite_categories_dropdown_class', $classes);

	$pfx = ldd_directory_lite_processor::DATA_PREFIX;
	$name = $pfx . $id;

	$category_args = array(
		'hide_empty'   => 0,
		'echo'         => 0,
		'selected'     => $selected,
		'hierarchical' => 1,
		'name'         => $name,
		'id'           => 'f_' . $id,
		'class'        => implode(' ', $classes),
		'tab_index'    => 2,
		'taxonomy'     => LDDLITE_TAX_CAT,
	);

	echo wp_dropdown_categories($category_args);
}

/**
 * Alias for wp_dropdown_categories() that uses our settings array to determine the output.
 * Multiple categories selection.
 */
function ldl_submit_multi_categories_dropdown($selected = 0, $id = 'category', $classes = array('form-control')) {

	$result = "";

	if (is_string($classes)):
		$classes = explode(' ', $classes);
	endif;

	$classes = apply_filters('lddlite_categories_dropdown_class', $classes);

	$pfx = ldd_directory_lite_processor::DATA_PREFIX;
	$name = $pfx . $id."[]";

	$args_arr = array(
		'orderby'	    => 'name',
		'order'         => 'ASC',
		'hide_empty'    => false,
		'hierarchical'  => 1,
		'parent'        => 0
	);
	$categories =  get_terms(LDDLITE_TAX_CAT,$args_arr);

	$result = "<select name='$name' id='f_$id' class='".implode(' ', $classes)." multi_select_chosen' required multiple>";

	foreach ($categories as $key => $cat) {
		$result .= "<option ".get_selected($selected,$cat->term_id)." value='".$cat->term_id."'>".$cat->name."</option>";
		$result .= get_child_categories($cat->term_id,LDDLITE_TAX_CAT);
	}

	$result .= "</select>";

	echo $result;
}

/**
 * get_selected()
 * @param mixed $row
 * @param mixed $value
 * @return string
 */
function get_selected($row, $value) {
	if(is_array($row)):
		if(in_array($value,$row)):
			return "selected=\"selected\"";
		endif;
	else:
		if ($row == $value):
			return "selected=\"selected\"";
		endif;
	endif;
}

/**
 * get_child_categories()
 * @param int $parent_id
 * @param string $tax
 * @return string
 */
function get_child_categories($parent_id,$tax,$indent = 1){
	$result = "";

	$args_arr = array(
		'orderby'	    => 'name',
		'order'         => 'ASC',
		'hide_empty'    => false,
		'hierarchical'  => 1,
		'parent'        => $parent_id
	);
	$child_terms = get_terms($tax, $args_arr);
	if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ){
		foreach ( $child_terms as $child_term ) {
			$child_cat_name = str_repeat("&nbsp&nbsp&nbsp", $indent).$child_term->name;
			$result .= '<option value="' . $child_term->term_id . '">' . $child_cat_name . '</option>';
			$result .= get_child_categories($child_term->term_id,$tax,$indent+1);
		}
	}
	return $result;
}

/**
 * Template alias for ldd_directory_lite_processor::get_value()
 *
 * @param string $field Identifies the value we're asking for
 *
 * @return mixed The value, empty if not a valid key
 */
function ldl_get_value($field) {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->get_value($field);
}


/**
 * Template alias for ldd_directory_lite_processor::get_value()
 *
 * @param string $field Identifies the error we're asking for
 *
 * @return mixed The error message or empty if none was found
 */
function ldl_get_error($field) {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->get_error($field);
}


/**
 * Template alias for ldd_directory_lite_processor::has_errors()
 *
 * @return bool True if errors exist, false otherwise
 */
function ldl_has_errors() {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->has_errors();
}


/**
 * Template alias for ldd_directory_lite_processor::has_global_errors()
 *
 * @return bool True if global errors exist, false otherwise
 */
function ldl_has_global_errors() {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->has_global_errors();
}


/**
 * Template alias for ldd_directory_lite_processor::get_global_errors()
 *
 * @return array Returns the current key value pair from the global errors array and advances the pointer
 */
function ldl_get_global_errors() {
	global $lddlite_submit_processor;

	return $lddlite_submit_processor->get_global_errors();
}

/** End of template functions **/


/**
 * Create a new user during the submission process. If successful, send them an internal email providing them
 * with their login information.
 *
 * @param string $username The username (this should have been checked during processing)
 * @param string $email    Same as above, this should already be valid
 *
 * @return int|object Returns the user_id if successful, WP_Error if not.
 */
function ldl_submit_create_user($username, $email) {

	$password = wp_generate_password(14, true);
	$user_id = wp_create_user($username, $password, $email);

	if (!is_wp_error($user_id)) {
		wp_new_user_notification($user_id, $password);
	}

	return $user_id;
}


/**
 * Create the post associated with the new listing. This takes information from the submit form, and a $user_id
 * that was generated previously in ldl_submit_create_user()
 *
 * @param string $name        The listing/post title
 * @param string $description The description, which may contain markdown
 * @param int    $cat_id      The taxonomy ID for this listing
 * @param int    $user_id     The author of this listing
 *
 * @return int|WP_Error A valid $post_id on success, and the WP_Error object on failure
 */
function ldl_submit_create_post($name, $description, $summary, $cat_id, $user_id) {

	if(is_array($cat_id)):
		$cat_id = array_map( 'intval', $cat_id );
		$cat_id = array_unique( $cat_id );
	else:
		$cat_id = (int) $cat_id;
	endif;

	$args = array(
		'post_content' => $description,
		'post_excerpt' => $summary,
		'post_title'   => $name,
		'post_status'  => 'pending',
		'post_type'    => LDDLITE_POST_TYPE,
		'post_author'  => $user_id,
		'post_date'    => date('Y-m-d H:i:s'),
	);

	if(ldl()->get_option('submit_auto_approve_submission') === "yes"){
		$args["post_status"] = "publish";

	}
	if(ldl()->get_option('submit_auto_approve_submission') != "yes") {
		$subject = ldl()->get_option( 'email_toadmin_subject' );
		$message = ldl()->get_option( 'email_toadmin_body' );
	}

	$post_id = wp_insert_post($args);

	if (!is_wp_error($post_id)) {
		//ldl_mail(get_bloginfo('admin_email'), $subject, $message, $headers = '');
		wp_set_object_terms($post_id, $cat_id, LDDLITE_TAX_CAT);

	}

	return $post_id;
}


/**
 * Removes non-meta fields from the processed data array and inserts the remaining values as post meta.
 *
 * @param array $data    The processed data provided by the $lddlite_submit_processor object
 * @param int   $post_id The post ID returned by ldl_submit_create_post()
 */
function ldl_submit_create_meta($data, $post_id) {

	$remove_fields = array('title', 'category', 'description', 'summary', 'username', 'email',);

	$data = array_diff_key($data, array_flip($remove_fields));

	foreach ($data as $key => $value) {
		add_post_meta($post_id, ldl_pfx($key), $value);
	}

}


/**
 * Send an email notification to the email specified in the directory settings.
 *
 * @param array $data    The processed data provided by the $lddlite_submit_processor object
 * @param int   $post_id The post ID returned by ldl_submit_create_post()
 */
function ldl_notify_admin($data, $post_id) {

	//$to = ldl()->get_option('email_notification_address');
	$auth = get_post($post_ID);
	$user = get_userdata($auth->post_author);
	$to = get_bloginfo('admin_email');
	$subject = ldl()->get_option('email_toadmin_subject');

	$user_email = $user->data->user_email;
	$user_name = $user->data->user_nicename;

	$all_fields = "Listing Title: ".$data['title']."<br>
	Description: ".$data['description']."<br> 
	Address: ".get_post_meta($post_id,"_lddlite_address_one",true)."<br>
	City: ".get_post_meta($post_id,"_lddlite_city",true)."<br>
	Zip Code: ".get_post_meta($post_id,"_lddlite_postal_code",true)."<br>
	State: ".get_post_meta($post_id,"_lddlite_state",true)."<br>
	Country: ".get_post_meta($post_id,"_lddlite_country",true)."<br>
	Contact Name: ".get_post_meta($post_id,"_lddlite_contact_name",true)."<br>
	Contact Email: ".get_post_meta($post_id,"_lddlite_contact_email",true)."<br>
	Contact Phone: ".get_post_meta($post_id,"_lddlite_contact_phone",true)."<br>
	Contact Fax: ".get_post_meta($post_id,"_lddlite_contact_fax",true)."<br>
	Contact Skype: ".get_post_meta($post_id,"_lddlite_contact_skype",true)."<br>
	Website: ".get_post_meta($post_id,"_lddlite_url_website",true)."<br>
	Facebook: ".get_post_meta($post_id,"_lddlite_url_facebook",true)."<br>
	Twitter: ".get_post_meta($post_id,"_lddlite_url_twitter",true)."<br>
	LinkedIn: ".get_post_meta($post_id,"_lddlite_url_linkedin",true)."<br>
	
	
	Instagram: ".get_post_meta($post_id,"_lddlite_url_instagram",true)."<br>
	Youtube: ".get_post_meta($post_id,"_lddlite_url_youtube",true)."<br>
	Custom Link: ".get_post_meta($post_id,"_lddlite_url_custom",true)."<br>
	";


	$message = ldl()->get_option('email_toadmin_body');
	$message = str_replace('{approve_link}', admin_url('post.php?post=' . $post_id . '&action=edit'), $message);
	$message = str_replace('{title}', $data['title'], $message);
	$message = str_replace('{description}', $data['description'], $message);
	$message = str_replace('{site_title}', get_bloginfo('name'), $message);
	$message = str_replace('{site_link}', get_bloginfo('url'), $message);
	$message = str_replace('{author}', $user_nicename, $message);
	$message = str_replace('{author_email}', $user_email, $message);
	$message = str_replace('{directory_link}', get_bloginfo('url')."?page_id=".ldl()->get_option('directory_front_page'), $message);

	$message = str_replace('{contact_email}', get_post_meta($post_id,"_lddlite_contact_name",true), $message);
	$message = str_replace('{contact_name}', get_post_meta($post_id,"_lddlite_contact_email",true), $message);
	
	$terms = wp_get_post_terms( $post_id, 'listing_category');
	$message = str_replace('{listing_category}', $terms[0]->name, $message);
	$message = str_replace('{all_fields}', $all_fields, $message);

	ldl_mail($to, $subject, $message);
}


/**
 * Send an email notification to the author of the listing, an easy way to supply them with a copy of the
 * information they submitted and any helpful advice while waiting for it to be approved.
 *
 * @param array $data    The processed data provided by the $lddlite_submit_processor object
 * @param int   $post_id The post ID returned by ldl_submit_create_post()
 */
function ldl_notify_author($data,$post_id) {
	global $lddlite_submit_processor;



	$all_fields = "Listing Title: ".$data['title']."<br>
	Description: ".$data['description']."<br> 
	Address: ".get_post_meta($post_id,"_lddlite_address_one",true)."<br>
	City: ".get_post_meta($post_id,"_lddlite_city",true)."<br>
	Zip Code: ".get_post_meta($post_id,"_lddlite_postal_code",true)."<br>
	State: ".get_post_meta($post_id,"_lddlite_state",true)."<br>
	Country: ".get_post_meta($post_id,"_lddlite_country",true)."<br>
	Contact Name: ".get_post_meta($post_id,"_lddlite_contact_name",true)."<br>
	Contact Email: ".get_post_meta($post_id,"_lddlite_contact_email",true)."<br>
	Contact Phone: ".get_post_meta($post_id,"_lddlite_contact_phone",true)."<br>
	Contact Fax: ".get_post_meta($post_id,"_lddlite_contact_fax",true)."<br>
	Contact Skype: ".get_post_meta($post_id,"_lddlite_contact_skype",true)."<br>
	Website: ".get_post_meta($post_id,"_lddlite_url_website",true)."<br>
	Facebook: ".get_post_meta($post_id,"_lddlite_url_facebook",true)."<br>
	Twitter: ".get_post_meta($post_id,"_lddlite_url_twitter",true)."<br>
	LinkedIn: ".get_post_meta($post_id,"_lddlite_url_linkedin",true)."<br>
	
	
	Instagram: ".get_post_meta($post_id,"_lddlite_url_instagram",true)."<br>
	Youtube: ".get_post_meta($post_id,"_lddlite_url_youtube",true)."<br>
	Custom Link: ".get_post_meta($post_id,"_lddlite_url_custom",true)."<br>
	";

	$auth = get_post($post_ID);
	$user = get_userdata($auth->post_author);
	
	

	$user_email = $user->data->user_email;
	$user_name = $user->data->user_nicename;

	$to = $lddlite_submit_processor->get_value('contact_email');

	$subject = ldl()->get_option('email_onsubmit_subject');
	

	$message = ldl()->get_option('email_onsubmit_body');
	$message = str_replace('{site_title}', get_bloginfo('name'), $message);
	$message = str_replace('{directory_email}', ldl()->get_option('email_from_address'), $message);
	$message = str_replace('{title}', $data['title'], $message);

	$message = str_replace('{author}', $user_name, $message);
	$message = str_replace('{author_email}', $user_email, $message);
	$message = str_replace('{site_link}', get_bloginfo('url'), $message);
	$message = str_replace('{directory_link}', get_bloginfo('url')."?page_id=".ldl()->get_option('directory_front_page'), $message);

	$message = str_replace('{contact_email}', get_post_meta($post_id,"_lddlite_contact_name",true), $message);
	$message = str_replace('{contact_name}', get_post_meta($post_id,"_lddlite_contact_email",true), $message);
	
	$terms = wp_get_post_terms( $post_id, 'listing_category');
	$message = str_replace('{listing_category}', $terms[0]->name, $message);
	$message = str_replace('{all_fields}', $all_fields, $message);

	ldl_mail($to, $subject, $message);
}


/**
 * Send an email to a listing author when their listing has been updateding from pending review to published.
 *
 * @param object $post The WP_Post object
 */
function ldl_notify_when_approved($post) {

	// Don't send an email if this is the wrong post type or it's already been approved before
	if (LDDLITE_POST_TYPE != get_post_type() || 1 == get_post_meta($post->ID, '_approved', true))
		return;



	$user = get_userdata($post->post_author);
	$permalink = get_permalink($post->ID);
	$title = get_the_title($post->ID);
	$description = get_the_content($post->ID);


	
	$all_fields = "Listing Title: ".$title."<br>
	Description: ".$description."<br> 
	Address: ".get_post_meta($post->ID,"_lddlite_address_one",true)."<br>
	City: ".get_post_meta($post->ID,"_lddlite_city",true)."<br>
	Zip Code: ".get_post_meta($post->ID,"_lddlite_postal_code",true)."<br>
	State: ".get_post_meta($post->ID,"_lddlite_state",true)."<br>
	Country: ".get_post_meta($post->ID,"_lddlite_country",true)."<br>
	Contact Name: ".get_post_meta($post->ID,"_lddlite_contact_name",true)."<br>
	Contact Email: ".get_post_meta($post->ID,"_lddlite_contact_email",true)."<br>
	Contact Phone: ".get_post_meta($post->ID,"_lddlite_contact_phone",true)."<br>
	Contact Fax: ".get_post_meta($post->ID,"_lddlite_contact_fax",true)."<br>
	Contact Skype: ".get_post_meta($post->ID,"_lddlite_contact_skype",true)."<br>
	Website: ".get_post_meta($post->ID,"_lddlite_url_website",true)."<br>
	Facebook: ".get_post_meta($post->ID,"_lddlite_url_facebook",true)."<br>
	Twitter: ".get_post_meta($post->ID,"_lddlite_url_twitter",true)."<br>
	LinkedIn: ".get_post_meta($post->ID,"_lddlite_url_linkedin",true)."<br>
	
	Instagram: ".get_post_meta($post->ID,"_lddlite_url_instagram",true)."<br>
	Youtube: ".get_post_meta($post->ID,"_lddlite_url_youtube",true)."<br>
	Custom Link: ".get_post_meta($post->ID,"_lddlite_url_custom",true)."<br>
	";


	$to = $user->data->user_email;
	$subject = ldl()->get_option('email_onapprove_subject');

	$message = ldl()->get_option('email_onapprove_body');
	$message = str_replace('{site_title}', get_bloginfo('name'), $message);
	$message = str_replace('{title}', $title, $message);
	$message = str_replace('{description}', $description, $message);
	$message = str_replace('{link}', $permalink, $message);
	$author_obj = get_user_by('id', $post->ID);
	$message = str_replace('{directory_link}', get_bloginfo('url')."?page_id=".ldl()->get_option('directory_front_page'), $message);
	$message = str_replace('{site_link}', get_bloginfo('url'), $message);
	$message = str_replace('{author}', $user->data->user_nicename, $message);
	$message = str_replace('{author_email}', $user->data->user_email, $message);
	$message = str_replace('{contact_email}', get_post_meta($post->ID,"_lddlite_contact_name",true), $message);
	$message = str_replace('{contact_name}', get_post_meta($post->ID,"_lddlite_contact_email",true), $message);

	$terms = wp_get_post_terms( $post->ID, 'listing_category');
	$message = str_replace('{listing_category}', $terms[0]->name, $message);
	$message = str_replace('{all_fields}', $all_fields, $message);
	


	ldl_mail($to, $subject, $message);
	update_post_meta($post->ID, '_approved', 1);

}
add_action('pending_to_publish', 'ldl_notify_when_approved');


/**
 * Used by ldl_submit_generate_listing() to destroy any evidence that we started to generate a listing. This is a
 * stopgap measure to allow the form to fail and be resubmitted. I think this can be better, but I want to get to
 * stable before I spend more time on it.
 *
 * @since 0.6.0
 *
 * @param array $ids An associate array identifying what needs to be deleted
 */
function ldl_submit_rollback($ids) {
	foreach ($ids as $id) {
		switch ($id) {
			case 'user_id':
				wp_delete_user($id);
				break;
			case 'post_id':
				wp_delete_post($id);
				break;
		}
	}
}


/**
 * This is responsible for generating the listing based on the information provided in the form submission. Everything
 * should be sanitized and validated by now via the $lddlite_submit_processor object, however since errors are still
 * possible, I want this to be able to rollback on items that are created, or store their IDs for use on the next pass.
 *
 * @since 0.6.0
 * @return bool True only if successful, false if any errors occur
 */
function ldl_submit_generate_listing() {
	global $lddlite_submit_processor;

	$data = $lddlite_submit_processor->get_data();

	// If anything fails, we need to start over.
	// I considered storing the IDs as they were generated, and simply setting back on whatever failed,
	// but that won't work unless I have a way of disabling form fields on the fly.

	$user_id = is_user_logged_in() ? get_current_user_id() : ldl_submit_create_user($data['username'], $data['email']);
	if (is_wp_error($user_id)) {
		$lddlite_submit_processor->set_global_error(__('There was a problem creating your user account. Please try again later.', 'ldd-directory-lite'));

		return false;
	}

	$post_id = ldl_submit_create_post($data['title'], $data['description'], $data['summary'], $data['category'], $user_id);
	if (!$post_id) {
		$lddlite_submit_processor->set_global_error(__('There was a problem creating your listing. Please try again later.', 'ldd-directory-lite'));
		ldl_submit_rollback(array(
			'user_id' => $user_id,
		));

		return false;
	}

	// Add all the post meta fields
	ldl_submit_create_meta($data, $post_id);

	// Upload their logo if one was submitted
	if (isset($_FILES['n_logo']) && 0 === $_FILES['n_logo']['error']) {

		// These files need to be included as dependencies when on the front end.
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/media.php');

		$attachment_id = media_handle_upload('n_logo', 0);
		if (is_wp_error($attachment_id)) {
			$lddlite_submit_processor->set_global_error(__('There was a problem uploading your logo. Please try again!', 'ldd-directory-lite'));
			ldl_submit_rollback(array(
				'user_id' => $user_id,
				'post_id' => $post_id,
			));

			return false;
		} else {
			set_post_thumbnail($post_id, $attachment_id);
		}
	}

	ldl_notify_admin($data, $post_id); // Notification of new listing
	ldl_notify_author($data,$post_id); // Receipt of submission

	return true;
}


/**
 * This is our shortcode callback for the submit listing form. It handles the display and the processing of the form
 * through delegation to the ldl_submit... functions found in this file.
 *
 * @since 0.6.0
 */
function ldl_shortcode_directory_submit() {
	global $lddlite_submit_processor , $google_api_src;

	if(ldl()->get_option('general_allow_public_submissions','yes') === 'no') {
		return;
	}

	ldl_enqueue(1);

	$terms = get_terms(LDDLITE_TAX_CAT, array('hide_empty' => false));
	if (!$terms) {
		wp_insert_term('Miscellaneous', LDDLITE_TAX_CAT);
	}

	// Set up the processor
	$lddlite_submit_processor = new ldd_directory_lite_processor;

	if (!is_user_logged_in()) {
		ldl_get_template_part('global/login');
		return;
	}
	if (is_user_logged_in()) {
		$user = new WP_User(get_current_user_id());
		if($user->roles[0]=="subscriber"){
		
		ldl_get_template_part('global/notallowed');
		return;
		}
	}
	ob_start();
	if ($lddlite_submit_processor->is_processing() && !$lddlite_submit_processor->has_errors()) {
		ob_start();
		do_action('lddlite_submit_pre_process', $lddlite_submit_processor);

		if (ldl_submit_generate_listing()) {
			ldl_get_template_part('frontend/submit', 'success');
			do_action('lddlite_submit_post_process', $lddlite_submit_processor);

			return ob_get_clean();
		}

	}

	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('maps-autocomplete', $google_api_src);
	wp_enqueue_script('lddlite-submit', LDDLITE_URL . '/public/js/submit.js', 'maps-autocomplete', LDDLITE_VERSION);

	ldl_get_template_part('frontend/submit');
	return ob_get_clean();
}
add_shortcode('directory_submit', 'ldl_shortcode_directory_submit');
