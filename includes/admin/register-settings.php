<?php
/**
 * Register plugin settings
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Return the settings array
 *
 * @since 0.8
 * @return array
 */
function ldl_get_registered_settings() {

    $settings = array(

        'general' => apply_filters( 'lddlite_settings_general',
            array(
                'directory_front_page' => array(
                    'id' => 'directory_front_page',
                    'name' => __('Front Page', 'ldd-directory-lite' ),
                    'desc' => __('This is the page where the <code>[directory]</code> shortcode has been placed.', 'ldd-directory-lite'),
                    'type' => 'select',
                    'options' => ldl_get_pages()
                ),
                'directory_submit_page' => array(
                    'id' => 'directory_submit_page',
                    'name' => __( 'Submit Listing Page', 'ldd-directory-lite'),
                    'desc' => __( 'This is the page where the <code>[directory_submit]</code> shortcode has been placed.', 'ldd-directory-lite'),
                    'type' => 'select',
                    'options' => ldl_get_pages()
                ),
                'directory_manage_page' => array(
                    'id' => 'directory_manage_page',
                    'name' => __( 'Manage Listings Page', 'ldd-directory-lite'),
                    'desc' => __( 'This is the page where the <code>[directory_manage]</code> shortcode has been placed.', 'ldd-directory-lite'),
                    'type' => 'select',
                    'options' => ldl_get_pages()
                ),
                'directory_taxonomy_slug' => array(
                    'id' => 'directory_taxonomy_slug',
                    'name' => __( 'Taxonomy Slug', 'ldd-directory-lite'),
                    'type' => 'text',
                    'size' => 'medium',
                    'std' => 'listings',
                ),
                'directory_post_type_slug' => array(
                    'id' => 'directory_post_type_slug',
                    'name' => __( 'Post Type Slug', 'ldd-directory-lite'),
                    'type' => 'text',
                    'size' => 'medium',
                    'std' => 'listings',
                ),
                'information_separator' => array(
                    'id' => 'information_separator',
                    'name' => '<strong>' . __( 'Directory Information', 'ldd-directory-lite') . '</strong>',
                    'type' => 'header'
                ),
                'directory_label' => array(
                    'id' => 'directory_label',
                    'name' => __( 'Directory Label', 'ldd-directory-lite'),
                    'desc' => __( 'Name your directory; "My Business Directory", "Local Restaurant Feed", "John\'s List of Links", etc.', 'ldd-directory-lite'),
                    'type' => 'text',
                ),
                'directory_description' => array(
                    'id' => 'directory_description',
                    'name' => __( 'Directory Description', 'ldd-directory-lite'),
                    'type' => 'rich_editor'
                ),
                'other_separator' => array(
                    'id' => 'directory_information',
                    'name' => '<strong>' . __( 'Other', 'ldd-directory-lite') . '</strong>',
                    'type' => 'header'
                ),
                'allow_tracking' => array(
                    'id' => 'allow_tracking',
                    'name' => __( 'Allow Tracking', 'ldd-directory-lite'),
                    'desc' => __( 'Allow anonymous usage tracking', 'ldd-directory-lite'),
                    'type' => 'checkbox'
                ),
                'google_maps' => array(
                    'id' => 'google_maps',
                    'name' => __( 'Google Maps', 'ldd-directory-lite'),
                    'desc' => __( 'This toggles the display of Google Maps for listings that have an address set.', 'ldd-directory-lite'),
                    'type' => 'checkbox'
                ),
            )
        ),

        'emails' => apply_filters('lddlite_settings_emails',
            array(
                'email_from_name' => array(
                    'id' => 'email_from_name',
                    'name' => __( 'From Name', 'ldd-directory-lite'),
                    'desc' => __('This forms the first part of outgoing messages, ', 'ldd-directory-lite') . sprintf(' From: <strong>%s</strong> &lt;%s&gt;', ldl()->get_option('email_from_name'), ldl()->get_option('email_from_address')),
                    'type' => 'text',
                    'std'  => get_bloginfo( 'name' )
                ),
                'email_from_address' => array(
                    'id' => 'email_from_address',
                    'name' => __( 'From Email', 'ldd-directory-lite'),
                    'desc' => __('This forms the second part of outgoing messages, ', 'ldd-directory-lite') . sprintf(' From: %s &lt;<strong>%s</strong>&gt;', ldl()->get_option('email_from_name'), ldl()->get_option('email_from_address')),
                    'type' => 'text',
                    'std'  => get_bloginfo( 'admin_email' )
                ),
                'email_notification_address' => array(
                    'id' => 'email_notification_address',
                    'name' => __( 'From Email', 'ldd-directory-lite'),
                    'desc' => __( 'Email to send purchase receipts from. This will act as the "from" and "reply-to" address.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => get_bloginfo( 'admin_email' )
                ),
                'message_separator' => array(
                    'id' => 'message_separator',
                    'name' => '<strong>' . __( 'Email Templates', 'ldd-directory-lite') . '</strong>',
                    'type' => 'header'
                ),
                'email_toadmin_subject' => array(
                    'id' => 'email_toadmin_subject',
                    'name' => __( 'Admin Notification', 'ldd-directory-lite'),
                    'desc' => __( 'The subject line for email notifications sent to the site administrator.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => __( 'A new listing has been submitted for review!', 'ldd-directory-lite')
                ),
                'email_toadmin_body' => array(
                    'id' => 'email_toadmin_body',
                    'name' => __( 'Notification Message', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => __('A new listing is pending review!', 'ldd-directory-lite') . "\n\n" . __('This submission is awaiting approval. Please visit the link to view and approve the new listing:', 'ldd-directory-lite') . "\n\n{approve_link}\n\n" . __('Listing Name:', 'ldd-directory-lite') . " {title}\n" . __('Listing Description:', 'ldd-directory-lite') . " {description}\n\n*****************************************\n" . __('This is an automated message from', 'ldd-directory-lite') . " {$site_title}\n" . __('Please do not respond directly to this email', 'ldd-directory-lite') . "\n\n",
                ),
                'email_onsubmit_subject' => array(
                    'id' => 'email_onsubmit_subject',
                    'name' => __( 'Admin Notification', 'ldd-directory-lite'),
                    'desc' => __( 'Sent to the author after they submit a new listing. Use this to remind them of your terms, inform them of average wait times or other important information.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => sprintf(__('Your listing on %s is pending review!', 'ldd-directory-lite'), get_bloginfo('name')),
                ),
                'email_onsubmit_body' => array(
                    'id' => 'email_onsubmit_body',
                    'name' => __( 'Notification Message', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => __('Thank you for submitting a listing to', 'ldd-directory-lite') . " {site_title}!\n\n" . __('Your listing is pending approval.', 'ldd-directory-lite') . "\n\n" . __('Please review the following information for accuracy, as this is what will appear on our web site. If you see any errors, please contact us immediately at', 'ldd-directory-lite') . " {directory_email}.\n\n" . __('Listing Name:', 'ldd-directory-lite') . " {title}\n" . __('Listing Description:', 'ldd-directory-lite') . " {description}\n\n*****************************************\n" . __('This is an automated message from', 'ldd-directory-lite') . " {$site_title}\n" . __('Please do not respond directly to this email', 'ldd-directory-lite') . "\n\n",
                ),
                'email_onapprove_subject' => array(
                    'id' => 'email_onapprove_subject',
                    'name' => __( 'Admin Notification', 'ldd-directory-lite'),
                    'desc' => __( 'Sent to the author when their listing has been approved and is available publicly.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => sprintf(__('Your listing on %s has been approved!', 'ldd-directory-lite'), get_bloginfo('name')),
                ),
                'email_onapprove_body' => array(
                    'id' => 'email_onapprove_body',
                    'name' => __( 'Notification Message', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => __('Thank you for submitting a listing to', 'ldd-directory-lite') . " {site_title}!\n\n" . __('Your listing has been approved! You can now view it online:', 'ldd-directory-lite') . "\n\n{link}\n\n*****************************************\n" . __('This is an automated message from', 'ldd-directory-lite') . " {$site_title}\n" . __('Please do not respond directly to this email', 'ldd-directory-lite') . "\n\n",
                ),
            )
        ),

        'submit' => apply_filters('lddlite_settings_submit',
            array(
                'submit_use_tos' => array(
                    'id' => 'submit_use_tos',
                    'name' => __( 'Require TOS', 'ldd-directory-lite'),
                    'desc' => __( 'Check this to require users agree to your terms of service (defined below) before submitting a listing.', 'ldd-directory-lite'),
                    'type' => 'checkbox',
                ),
                'submit_tos' => array(
                    'id' => 'submit_tos',
                    'name' => __( 'Terms of Service', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                ),
                'submit_intro' => array(
                    'id' => 'submit_intro',
                    'name' => __( 'Introduction', 'ldd-directory-lite'),
                    'desc' => __('This will be displayed at the top of the submit listing form.', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std' => '<p>' . __('Please tell us a little bit about the organization you would like to see listed in our directory. Try to include as much information as you can, and be as descriptive as possible where asked.', 'ldd-directory-lite') . '</p>',
                ),
                'submit_success' => array(
                    'id' => 'submit_success',
                    'name' => __( 'Success Message', 'ldd-directory-lite'),
                    'desc' => __('Displayed following a successful listing submission.', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std' => '<h3>' . __('Congratulations!', 'ldd-directory-lite') . '</h3><p>' . __('Your listing has been successfully submitted for review. Please allow us sufficient time to review the listing and approve it for public display in our directory.', 'ldd-directory-lite') . '</p>',
                ),
            )
        ),

        'appearance' => apply_filters('lddlite_settings_appearance',
            array(
                'disable_bootstrap' => array(
                    'id' => 'disable_bootstrap',
                    'name' => __( 'Disable Bootstrap', 'ldd-directory-lite'),
                    'desc' => __( 'You can disable the Bootstrap CSS library if your theme already loads a copy, or if you want to use entirely custom CSS.', 'ldd-directory-lite'),
                    'type' => 'checkbox',
                ),
                'appearance_display_featured' => array(
                    'id' => 'appearance_display_featured',
                    'name' => __( 'Enable Featured Listings', 'ldd-directory-lite'),
                    'desc' => __('If checked, listings tagged with <code>featured</code> will be shown on your directory home page.', 'ldd-directory-lite'),
                    'type' => 'checkbox',
                    'std' => 1,
                ),
                'appearance_primary_normal' => array(
                    'id' => 'appearance_primary_normal',
                    'name' => __( 'Primary Normal', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std' => '#3bafda',
                ),
                'appearance_primary_hover' => array(
                    'id' => 'appearance_primary_hover',
                    'name' => __( 'Primary Hover', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std' => '#3071a9',
                ),
                'appearance_primary_foreground' => array(
                    'id' => 'appearance_primary_foreground',
                    'name' => __( 'Primary Foreground', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std' => '#ffffff',
                ),
            )
        ),
    );

    return $settings;
}


/**
 * Register the default settings and sections
 *
 * @since 0.8
 */
function ldl_register_settings() {

	foreach(ldl_get_registered_settings() as $tab => $settings) {

		add_settings_section(
			'lddlite_settings_' . $tab,
			__return_null(),
			'__return_false',
			'lddlite_settings_' . $tab
		);

		foreach ($settings as $option) {

			$name = isset($option['name']) ? $option['name'] : '';

			add_settings_field(
				'lddlite_settings[' . $option['id'] . ']',
				$name,
				function_exists( 'ldl_' . $option['type'] . '_callback' ) ? 'ldl_' . $option['type'] . '_callback' : 'ldl_missing_callback',
				'lddlite_settings_' . $tab,
				'lddlite_settings_' . $tab,
                array(
                    'id'      => isset($option['id']) ? $option['id'] : null,
                    'desc'    => !empty($option['desc']) ? $option['desc'] : '',
                    'name'    => isset($option['name']) ? $option['name'] : null,
                    'section' => $tab,
                    'size'    => isset($option['size']) ? $option['size'] : null,
                    'options' => isset($option['options']) ? $option['options'] : '',
                    'std'     => isset($option['std']) ? $option['std'] : ''
                )
			);
		}

	}

	// Creates our settings in the options table
	register_setting( 'lddlite_settings', 'lddlite_settings', 'ldl_settings_sanitize' );

}
add_action('admin_init', 'ldl_register_settings');


/**
 * Settings Sanitization
 *
 * @since 0.8
 * @param array $input The value inputted in the field
 * @return string $input
 */
function ldl_settings_sanitize( $input = array() ) {

	if ( empty( $_POST['_wp_http_referer'] ) ) {
		return $input;
	}

	parse_str( $_POST['_wp_http_referer'], $referrer );

	$settings = ldl_get_default_settings();
	$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

	$input = $input ? $input : array();
	$input = apply_filters( 'lddlite_settings_' . $tab . '_sanitize', $input );

	// Loop through each setting being saved and pass it through a sanitization filter
	foreach ( $input as $key => $value ) {

		// Get the setting type (checkbox, select, etc)
		$type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;

		if ( $type ) {
			// Field type specific filter
			$input[$key] = apply_filters( 'lddlite_settings_sanitize_' . $type, $value, $key );
		}

		// General filter
		$input[$key] = apply_filters( 'lddlite_settings_sanitize', $value, $key );
	}

    $ldl_settings = get_option('lddlite_settings');

    if ( ! empty( $settings[$tab] ) ) {
        foreach ( $settings[$tab] as $key => $value ) {

            if ( empty( $input[$key] ) ) {
                unset( $ldl_settings[$key] );
            }

        }
    }

	// Merge our new settings with the existing
	$output = array_merge( $ldl_settings, $input );

	add_settings_error( 'lddlite-notices', '', __( 'Settings updated.', 'ldd-directory-lite'), 'updated' );

	return $output;
}


function ldl_sanitize_text_field( $input ) {
	return trim( $input );
}
add_filter( 'ldl_settings_sanitize_text', 'ldl_sanitize_text_field' );


function ldl_get_settings_tabs() {

	$settings = ldl_get_registered_settings();

	$tabs             = array();
	$tabs['general']  = __( 'General', 'ldd-directory-lite');
    $tabs['emails']   = __( 'Emails', 'ldd-directory-lite');
    $tabs['submit']   = __( 'Submit', 'ldd-directory-lite');
    $tabs['appearance']   = __( 'Appearance', 'ldd-directory-lite');

	return apply_filters( 'ldl_settings_tabs', $tabs );
}


function ldl_get_pages( $force = false ) {

	$pages_options = array( 0 => '' ); // Blank option

	if( ( ! isset( $_GET['page'] ) || 'lddlite-settings' != $_GET['page'] ) && ! $force ) {
		return $pages_options;
	}

	$pages = get_pages();
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}

	return $pages_options;
}


function ldl_header_callback( $args ) {
	echo '<hr/>';
}


function ldl_checkbox_callback( $args ) {

	$checked = isset( $ldl_options[ $args[ 'id' ] ] ) ? checked( 1, $ldl_options[ $args[ 'id' ] ], false ) : '';
	$html = '<input type="checkbox" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_multicheck_callback( $args ) {

	if ( ! empty( $args['options'] ) ) {
		foreach( $args['options'] as $key => $option ):
			if( isset( $ldl_options[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
			echo '<input name="lddlite_settings[' . $args['id'] . '][' . $key . ']" id="lddlite_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
			echo '<label for="lddlite_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
		endforeach;
		echo '<p class="description">' . $args['desc'] . '</p>';
	}
}


function ldl_radio_callback( $args ) {

	foreach ( $args['options'] as $key => $option ) :
		$checked = false;

		if ( isset( $ldl_options[ $args['id'] ] ) && $ldl_options[ $args['id'] ] == $key )
			$checked = true;
		elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $ldl_options[ $args['id'] ] ) )
			$checked = true;

		echo '<input name="lddlite_settings[' . $args['id'] . ']"" id="lddlite_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
		echo '<label for="lddlite_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	endforeach;

	echo '<p class="description">' . $args['desc'] . '</p>';
}


function ldl_text_callback( $args ) {

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $size . '-text" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_number_callback( $args ) {

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$max  = isset( $args['max'] ) ? $args['max'] : 999999;
	$min  = isset( $args['min'] ) ? $args['min'] : 0;
	$step = isset( $args['step'] ) ? $args['step'] : 1;

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_textarea_callback( $args ) {

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<textarea class="large-text" cols="50" rows="5" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_password_callback( $args ) {

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="password" class="' . $size . '-text" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'ldd-directory-lite'), $args['id'] );
}


function ldl_select_callback($args) {

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$html = '<select id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $name ) :
		$selected = selected( $option, $value, false );
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_rich_editor_callback( $args ) {
	global $wp_version;

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

    ob_start();
    wp_editor( stripslashes( $value ), 'ldl_settings_' . $args['id'], array( 'textarea_name' => 'lddlite_settings[' . $args['id'] . ']', 'textarea_rows' => 8 ) );
    $html = ob_get_clean();

	$html .= '<br/><label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_upload_callback( $args ) {

	if (ldl()->has_option($args['id']))
		$value = $ldl_options[$args['id']];
	else
		$value = isset($args['std']) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $size . '-text ldl_upload_field" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<span>&nbsp;<input type="button" class="ldl_settings_upload_button button-secondary" value="' . __( 'Upload File', 'ldd-directory-lite') . '"/></span>';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_color_callback( $args ) {

	if (ldl()->has_option($args['id']))
		$value = ldl()->get_option($args['id']);
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$default = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="lddlite-color-picker" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />';
	$html .= '<label for="lddlite_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


function ldl_hook_callback( $args ) {
	do_action( 'ldl_' . $args['id'] );
}
