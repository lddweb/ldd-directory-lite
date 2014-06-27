<?php
/**
 * General functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Settings Functions
 * =====================================================================================================================
 */


/**
 * Returns an array of default settings for initial use by the plugin. Also allows for the addition of
 * new settings without running any additional upgrade methods.
 *
 * @since yore
 * @return array The default settings
 */
function ldl_get_default_settings() {

    $email = array();
	$site_title = get_bloginfo( 'name' );

    $signature = <<<SIG
*****************************************
This is an automated message from {$site_title}
Please do not respond directly to this email
SIG;

	$email['to_admin']   = <<<EM
<p><strong>A new listing is pending review!</strong></p>

<p>This submission is awaiting approval. Please visit the link to view and approve the new listing:</p>

<p>{approve_link}</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
	$email['on_submit']  = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing is pending approval.</p>
<p>Please review the following information for accuracy, as this is what will appear on our web site. If you see any errors, please contact us immediately at {directory_email}.</p>

<ul>
    <li>Listing Name: <strong>{title}</strong></li>
    <li>Listing Description: <strong>{description}</strong></li>
</ul>
EM;
	$email['on_approve'] = <<<EM
<p><strong>Thank you for submitting a listing to {site_title}!</strong></p>

<p>Your listing has been approved! You can now view it online:</p>
<p>{link}</p>
EM;

	foreach ( $email as $key => $msg ) {
        $email[ $key ] = $msg . $signature;
    }

	$defaults = apply_filters( 'lddlite_default_settings_array', array(
		'directory_label'             => get_bloginfo( 'name' ),
		'directory_description'       => '',
		'directory_page'              => '',
		'disable_bootstrap'           => 0,
		'public_or_private'           => 1,
		'google_maps'                 => 1,
        'email_from_name'             => get_bloginfo( 'name' ),
        'email_from_address'          => get_bloginfo( 'admin_email' ),
        'email_notification_address'  => get_bloginfo( 'admin_email' ),
		'email_toadmin_subject'       => 'A new listing has been submitted for review!',
		'email_toadmin_body'          => $email['to_admin'],
		'email_onsubmit_subject'      => 'Your listing on ' . $site_title . ' is pending review!',
		'email_onsubmit_body'         => $email['on_submit'],
		'email_onapprove_subject'     => 'Your listing on ' . $site_title . ' was approved!',
		'email_onapprove_body'        => $email['on_approve'],
		'submit_use_tos'              => 0,
		'submit_tos'                  => '',
		'submit_use_locale'           => 0,
		'submit_locale'               => 'US',
		'submit_require_address'      => 1,
		'allow_tracking_popup_done'   => 0,
		'allow_tracking'              => 0,
        'appearance_display_new'      => 1,
        'appearance_panel_background' => '#3bafda',
        'appearance_panel_foreground' => '#fff',
	) );

	return $defaults;
}


/**
 * An alias for the LDL_Directory_Lite get_setting() method which handles loading the singleton and also
 * allows for escaping the value if necessary.
 *
 * @since 0.5.3
 * @param string $key The configuration setting
 * @param bool $esc Whether or not to escape the output
 * @return mixed Returns empty if the setting doesn't exist, or the value if it does
 */
function ldl_get_setting( $key, $esc = false ) {

	$ldl = ldl_get_instance();
	$value = $ldl->get_setting( $key );

	if ( $esc )
		$value = esc_attr( $value );

	return $value;
}


/**
 * An alias for the LDL_Directory_Lite update_setting() method that also handles loading the singleton. This
 * function automatically saves the settings after update, requiring only one function call to handle the entire
 * process.
 *
 * @since 0.5.3
 * @param string $key The configuration setting we're updating
 * @param string $new_val The new value, leave empty to initialize
 */
function ldl_update_setting( $key, $new_val = '' ) {

	$ldl = ldl_get_instance();
	$old_val = $ldl->get_setting( $key );

	if ( $new_val == $old_val )
		return;

	$ldl->update_setting( $key, $new_val );
	$ldl->save_settings();

}





/**
 * This scans post content for the existence of our shortcode. If discovered, it updates the `directory_page`
 * setting so that any filters or actions returning a link to the directories home page has a post_id to work with.
 *
 * @param int $post_ID Post ID.
 * @param WP_Post $post Post object.
 */
function ldl_haz_shortcode( $post_id, $post ) {
    global $shortcode_tags;

    // Run as little as possible
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( wp_is_post_revision( $post_id ) )
        return;

    if ( empty( $post->post_content ) )
        return;

    // Store this, we don't want to permanently change it
    $old_shortcode_tags = $shortcode_tags;

    // Remove everything but our shortcode
    $shortcode_tags = array_intersect_key( $shortcode_tags, array(
        'directory' => '',
        'directory_lite' => '',
        'business_directory' => '',
    ) );

    $pattern = get_shortcode_regex();
    if ( preg_match( "/$pattern/s", $post->post_content ) )
        ldl_update_setting( 'directory_page', $post_id );

    // Reset the global array
    $shortcode_tags = $old_shortcode_tags;

}
add_action( 'save_post', 'ldl_haz_shortcode', 10, 2 );









/**
 * Wrapper to collect post meta for a listing
 *
 * @param $id
 * @param $field
 */
function ldl_get_meta( $key ) {
    $post_id = get_the_ID();

    if ( !is_int( $post_id ) )
        return false;

    return get_metadata( 'post', $post_id, '_lddlite_' . $key, true );
}


function ldl_get_listing_meta( $id ) {

    if ( !is_int( $id ) || LDDLITE_POST_TYPE != get_post_type( $id ) )
        return false;

    $defaults = array(
        'address_one' => '',
        'address_two' => '',
        'city' => '',
        'subdivision' => '',
        'post_code' => '',
        'address' => '',
        'geocode' => '',
        'website' => '',
        'email' => '',
        'phone' => '',
    );

    $meta['address_one'] = get_post_meta( $id, '_lddlite_address_one', 1 );
    $meta['address_two'] = get_post_meta( $id, '_lddlite_address_two', 1 );
    $meta['city']        = get_post_meta( $id, '_lddlite_city', 1 );
    $meta['subdivision'] = get_post_meta( $id, '_lddlite_subdivision', 1 );
    $meta['post_code']   = get_post_meta( $id, '_lddlite_post_code', 1 );

    $address = '';
    $geocode = '';

    foreach ( $meta as $key => $value ) {
        if ( 'address_two' != $key && empty( $value ) ) {
            $address = false;
            break;
        }
    }

    if ( false !== $address ) {

        $address = '<i class="fa fa-map-marker"></i>  ' . $meta['address_one'];
        if ( !empty( $meta['address_two'] ) )
            $address .= '<br>' . $meta['address_two'];
        $address .= ',<br>' . $meta['city'] . ', ' . $meta['subdivision'] . ' ' . $meta['post_code'];

        $geocode = urlencode( str_replace( '<br>', ' ', $address ) );

    } else {
        $address = '';
    }

    $meta['address'] = $address;
    $meta['geocode'] = $geocode;

    $website = get_post_meta( $id, '_lddlite_url_website', 1 );
    if ( $website )
        $meta['website'] = apply_filters( 'lddlite_listing_website', sprintf( '<a href="%1$s"><i class="fa fa-link"></i>  %1$s</a>', esc_url( $website ) ) );

    $meta['email'] = get_post_meta( $id, '_lddlite_contact_email', 1 );

    $phone = get_post_meta( $id, '_lddlite_contact_phone', 1 );
    if ( $phone )
        $meta['phone'] = ldl_format_phone( $phone );

    $meta = wp_parse_args( $meta, $defaults );

    return $meta;

}


/**
 * @deprecated Ripping this out and heading to Geocoder country.
 */
function ldl_dropdown_subdivision( $subdivision, $data, $tabindex = 0 ) {
    return '<input id="subdivision" class="form-control" name="ld_s_subdivision" type="text" value="' . $selected . '" ' . $tabindex . ' required>';
}


/**
 * @deprecated Ripping this out and heading to Geocoder country.
 */
function ldl_dropdown_country( $name, $data = '', $tabindex = 0 ) {
    return '<input id="country" class="form-control" name="' . $name . '" type="text" ' . $tabindex . ' required>';
}




function ldl_sanitize_twitter( $url ) {

	if ( empty( $url ) )
		return;

    $url = preg_replace( '/[^A-Za-z0-9\/:.]/', '', $url );

    if ( strpos( $url, '/' ) !== false )
        $url = substr( $url, strrpos( $url, '/' ) + 1 );

    $url = 'https://twitter.com/' . $url;

    return $url;
}






/**
 * Alias for wp_mail that sets headers for us.
 *
 * @since 1.3.13
 * @param string $to Email address this message is going to
 * @param string $subject Email subject
 * @param string $message Email contents
 * @param string $headers Optional, default is managed internally.
 */
function ldl_mail($to, $subject, $message, $headers = '' ) {

    // If we're not passing any headers, default to our internal from address
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= sprintf( 'From: %s <%s>', ldl_get_setting( 'email_from_name' ), ldl_get_setting( 'email_from_address' ) ) . "\r\n";
    }

    ob_start();
    wp_mail($to, $subject, $message, $headers);
    ob_end_clean();

}

/**
 * Output Functions
 * =====================================================================================================================
 */

/**
 * Replaces the protocol with HTTPS
 *
 * @since 0.5.3
 * @param string $url The URL
 * @return string The modified URL
 */
function ldl_force_https( $url ) {

	if ( strpos( $url, 'http') !== 0 )
		$url = esc_url_raw( $url );

    return set_url_scheme( $url, 'https' );
}


