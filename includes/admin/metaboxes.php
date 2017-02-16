<?php
/**
 * Initialize the custom post type metaboxes
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * It's quicker if we just restructure the excerpt box a little, then change all the wording via gettext
 *
 * @param $post The WP_Post object
 */
function ldl_excerpt_meta_box($post)
{
    echo '<label class="screen-reader-text" for="excerpt">' . __('Summary', 'ldd-directory-lite') . '</label><textarea rows="1" cols="40" name="excerpt" id="excerpt">' . $post->post_excerpt . '</textarea>';
}


/**
 * Relocate some of the built in meta boxes. Heck, we can even rename them at the same time.
 *
 * @since 0.5.0
 */
function ldl_metaboxes__swap()
{
    if (LDDLITE_POST_TYPE == get_post_type()) {
        remove_meta_box('postimagediv', LDDLITE_POST_TYPE, 'side');
        remove_meta_box('authordiv', LDDLITE_POST_TYPE, 'side');
        remove_meta_box('postexcerpt', LDDLITE_POST_TYPE, 'normal');
        add_meta_box('postimagediv', __('Logo', 'ldd-directory-lite'), 'post_thumbnail_meta_box', null, 'side', 'high');
        add_meta_box('authordiv', __('Owner', 'ldd-directory-lite'), 'post_author_meta_box', null, 'side', 'high');
        add_meta_box('postexcerpt', __('Summary', 'ldd-directory-lite'), 'ldl_excerpt_meta_box', null, 'normal', 'high');
    }
}

add_action('add_meta_boxes', 'ldl_metaboxes__swap', 5);

/**
 * Setup an array of of meta boxes to be used by the directory_listings custom post type
 *
 * @see https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Basic-Usage Basic Usage
 *
 * @param array $meta_boxes Array of currently registered metaboxes
 *
 * @return array Updated array with our new metaboxes attached
 */
function ldl_metaboxes_setup_cmb()
{

    // Set up a custom meta box to display a google map for visually defining a listings geographical location
    $listings_geo = new_cmb2_box(array(
        'id'           => 'listings_geo',
        'title'        => __('Location', 'ldd-directory-lite'),
        'object_types' => array(LDDLITE_POST_TYPE),
        'context'      => 'normal',
        'priority'     => 'core',
        'show_names'   => TRUE,
    ));
    $listings_geo->add_field(array(
        'name'  => __('Address One', 'ldd-directory-lite'),
        'id'    => ldl_pfx('address_one'),
        'type'  => 'text',
        'class' => 'geo_address_1',
    ));
    $listings_geo->add_field(array(
        'name'  => __('Address Two', 'ldd-directory-lite'),
        'id'    => ldl_pfx('address_two'),
        'type'  => 'text',
        'class' => 'geo_address_2',
    ));
    $listings_geo->add_field(array(
        'name'  => __('City', 'ldd-directory-lite'),
        'id'    => ldl_pfx('city'),
        'type'  => 'text_medium',
        'class' => 'geo_city',
    ));
    $listings_geo->add_field(array(
        'name'  => __('Zip / Postal Code', 'ldd-directory-lite'),
        'id'    => ldl_pfx('postal_code'),
        'type'  => 'text_medium',
        'class' => 'geo_zip',
    ));
    $listings_geo->add_field(array(
        'name'  => __('Country', 'ldd-directory-lite'),
        'id'    => ldl_pfx('country'),
        'type'  => 'text_medium',
        'class' => 'geo_country',
    ));
    $listings_geo->add_field(array(
        'name'  => __('State / Province', 'ldd-directory-lite'),
        'id'    => ldl_pfx('state'),
        'type'  => 'text_medium',
        'class' => 'geo_state',
    ));
    $listings_geo->add_field(array(
        'name'       => __('Map Location', 'ldd-directory-lite'),
        'desc'       => __('Please provide the address above for adding map.', 'ldd-directory-lite'),
        'id'         => ldl_pfx('geo'),
        'type'       => 'geo_location',
        'show_on_cb' => 'geo_location_field_condition'
    ));

    // Set up a custom meta box to encapsulate all URLs related to this listing
    $listings_web = new_cmb2_box(array(
        'id'           => 'listings_web',
        'title'        => __('Web Addresses', 'ldd-directory-lite'),
        'object_types' => array(LDDLITE_POST_TYPE),
        'context'      => 'normal',
        'priority'     => 'core',
        'show_names'   => TRUE,
    ));
    $listings_web->add_field(array(
        'name' => __('Website', 'ldd-directory-lite'),
        'desc' => __('Valid examples include; <code>mywebsite.net</code>, <code>www.business.com</code>, or <code>www.hosting.com/mysite/mypage.html</code>', 'ldd-directory-lite'),
        'id'   => ldl_pfx('url_website'),
        'type' => 'text_url',
    ));
    $listings_web->add_field(array(
        'name' => __('Facebook', 'ldd-directory-lite'),
        'desc' => __('This should always start with <code>facebook.com/</code> or <code>www.facebook.com</code>.', 'ldd-directory-lite'),
        'id'   => ldl_pfx('url_facebook'),
        'type' => 'text_url',
    ));
    $listings_web->add_field(array(
        'name' => __('Twitter', 'ldd-directory-lite'),
        'desc' => __('Enter the entire url (<code>www.twitter.com/username</code>) or just the username.', 'ldd-directory-lite'),
        'id'   => ldl_pfx('url_twitter'),
        'type' => 'text_url',
    ));
    $listings_web->add_field(array(
        'name' => __('LinkedIn', 'ldd-directory-lite'),
        'desc' => __('This should start with <code>www.linkedin.com</code>', 'ldd-directory-lite'),
        'id'   => ldl_pfx('url_linkedin'),
        'type' => 'text_url',
    ));

    // Groups together all contact information for a listing
    $listings_contact = new_cmb2_box(array(
        'id'           => 'listings_contact',
        'title'        => __('Contact Information', 'ldd-directory-lite'),
        'object_types' => array(LDDLITE_POST_TYPE),
        'context'      => 'side',
        'priority'     => 'core',
        'show_names'   => TRUE,
    ));
    $listings_contact->add_field(array(
        'name' => __('Contact Name', 'ldd-directory-lite'),
        'id'   => ldl_pfx('contact_name'),
        'type' => 'text_small',
    ));
    $listings_contact->add_field(array(
        'name' => __('Email', 'ldd-directory-lite'),
        'id'   => ldl_pfx('contact_email'),
        'type' => 'text_email',
    ));
    $listings_contact->add_field(array(
        'name' => __('Phone', 'ldd-directory-lite'),
        'id'   => ldl_pfx('contact_phone'),
        'type' => 'text_small',
    ));
    $listings_contact->add_field(array(
        'name' => __('Fax', 'ldd-directory-lite'),
        'id'   => ldl_pfx('contact_fax'),
        'type' => 'text_small',
    ));
    $listings_contact->add_field(array(
        'name' => __('Skype', 'ldd-directory-lite'),
        'id'   => ldl_pfx('contact_skype'),
        'type' => 'text_small',
    ));

}

add_action('cmb2_admin_init', 'ldl_metaboxes_setup_cmb');


/**
 * Create a google map custom field that allows users to visually select a geographical location for the
 * current listing. Stores an array with the formatted address (as provided by the Google Maps Autocomplete &
 * Geocoder API), latitude and longitude.
 *
 */
function ldl_render_geo_location_field($field, $escaped_value, $object_id, $object_type, $field_type_object)
{
    global $google_api_src;

    wp_enqueue_script('google-maps', $google_api_src);
    wp_enqueue_script('lddlite-admin');
    wp_enqueue_style('lddlite-admin');

    echo '<i class="full_address_i"></i>';
    echo '<input type="text" style="display:none;" class="autocomplete full_address_geo" id="' . $field->args["id"] . '">';
    echo '<input type="hidden" class="lat" name="' . $field->args["id"] . '[lat]" value="' . (isset($escaped_value['lat']) ? $escaped_value['lat'] : '') . '">';
    echo '<input type="hidden" class="lng" name="' . $field->args["id"] . '[lng]" value="' . (isset($escaped_value['lng']) ? $escaped_value['lng'] : '') . '">';
    echo '<div class="map-canvas" id="map_canvas"></div>';

    if (!empty($field->args["desc"]))
        echo '<p class="cmb_metabox_description">' . $field->args["desc"] . '</p>';

}
add_action('cmb2_render_geo_location', 'ldl_render_geo_location_field', 10, 5);

/**
 * Return true or false based on toggles to display of Google Maps for listings that have an address set.
 *
 * @return bool
 */
function geo_location_field_condition() {
    if ( ldl_use_google_maps() ){
        return true;
    }
    return false;
}

/**
 * Runs through the `gettext` filter to change labels on our custom post type add/edit screen
 *
 * @param string $translations Translated text.
 * @param string $text Text to translate.
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 *
 * @return mixed
 */
function ldl_relabel($translation, $text, $domain)
{

    if (LDDLITE_POST_TYPE != get_post_type()) {
        return $translation;
    }

    switch ($translation) {
        case 'Publish':
            $translation = __('Approve', 'ldd-directory-lite');
            break;
        case 'Published':
            $translation = __('Approved', 'ldd-directory-lite');
            break;
        case 'Published on: <b>%1$s</b>':
            $translation = __('Approved on: <b>%1$s</b>', 'ldd-directory-lite');
            break;
        case 'Publish <b>immediately</b>':
            $translation = __('Approve <b>immediately</b>', 'ldd-directory-lite');
            break;
        case 'Publish on: <b>%1$s</b>':
            $translation = __('Approve on: <b>%1$s</b>', 'ldd-directory-lite');
            break;
    }

    return $translation;
}

add_filter('gettext', 'ldl_relabel', 10, 3);
