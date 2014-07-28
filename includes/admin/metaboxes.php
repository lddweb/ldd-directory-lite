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
function ldl_excerpt_meta_box($post) {
    echo '<label class="screen-reader-text" for="excerpt">' . __('Summary', 'ldd-directory-lite') . '</label><textarea rows="1" cols="40" name="excerpt" id="excerpt">' . $post->post_excerpt . '</textarea>';
}


/**
 * Relocate some of the built in meta boxes. Heck, we can even rename them at the same time.
 *
 * @since 0.5.0
 */
function ldl_metaboxes__swap() {
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
 * Make sure another plugin hasn't already done this, then initialize the CMB library.
 *
 * @see https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress Custom Metaboxes awesomeness
 */
function ldl_metaboxes_init_cmb() {
    if (!class_exists('cmb_Meta_Box'))
        require_once(LDDLITE_PATH . '/includes/cmb/init.php');
}

add_action('init', 'ldl_metaboxes_init_cmb');


/**
 * Setup an array of of meta boxes to be used by the directory_listings custom post type
 *
 * @see https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress/wiki/Basic-Usage Basic Usage
 *
 * @param array $meta_boxes Array of currently registered metaboxes
 *
 * @return array Updated array with our new metaboxes attached
 */
function ldl_metaboxes_setup_cmb(array $meta_boxes) {


    // Set up a custom meta box to display a google map for visually defining a listings geographical location
    $meta_boxes['listings_geo'] = array(
        'id'         => 'listings_geo',
        'title'      => __('Location', 'ldd-directory-lite'),
        'pages'      => array(LDDLITE_POST_TYPE),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => 'Address One',
                'id'   => ldl_pfx('address_one'),
                'type' => 'text',
            ),
            array(
                'name' => 'Address Two',
                'id'   => ldl_pfx('address_two'),
                'type' => 'text',
            ),
            array(
                'name' => 'Zip / Postal Code',
                'id'   => ldl_pfx('postal_code'),
                'type' => 'text_medium',
            ),
            array(
                'name' => 'Country',
                'id'   => ldl_pfx('country'),
                'type' => 'text_medium',
            ),
            array(
                'name' => 'Set Map Marker',
                'desc' => __('Use the map above to set the location for this listing. The text field will attempt to autocomplete any address you enter, or you can drag the marker directly on the map to set the location.', 'ldd-directory-lite'),
                'id'   => ldl_pfx('geo'),
                'type' => 'geo_location',
            ),
        ),
    );

    // Set up a custom meta box to encapsulate all URLs related to this listing
    $meta_boxes['listings_web'] = array(
        'id'         => 'listings_web',
        'title'      => __('Web Addresses', 'ldd-directory-lite'),
        'pages'      => array(LDDLITE_POST_TYPE),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __('Website', 'ldd-directory-lite'),
                'desc' => __('Valid examples include; <code>mywebsite.net</code>, <code>www.business.com</code>, or <code>www.hosting.com/mysite/mypage.html</code>', 'ldd-directory-lite'),
                'id'   => ldl_pfx('url_website'),
                'type' => 'text',
            ),
            array(
                'name' => __('Facebook', 'ldd-directory-lite'),
                'desc' => __('This should always start with <code>facebook.com/</code> or <code>www.facebook.com</code>.', 'ldd-directory-lite'),
                'id'   => ldl_pfx('url_facebook'),
                'type' => 'text',
            ),
            array(
                'name' => __('Twitter', 'ldd-directory-lite'),
                'desc' => __('Enter the entire url (<code>www.twitter.com/username</code>) or just the username.', 'ldd-directory-lite'),
                'id'   => ldl_pfx('url_twitter'),
                'type' => 'text',
            ),
            array(
                'name' => __('LinkedIn', 'ldd-directory-lite'),
                'desc' => __('This should start with <code>www.linkedin.com</code>', 'ldd-directory-lite'),
                'id'   => ldl_pfx('url_linkedin'),
                'type' => 'text',
            ),
        ),
    );

    // Groups together all contact information for a listing
    $meta_boxes['listings_contact'] = array(
        'id'         => 'listings_contact',
        'title'      => __('Contact Information', 'ldd-directory-lite'),
        'pages'      => array(LDDLITE_POST_TYPE),
        'context'    => 'side',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __('Email', 'ldd-directory-lite'),
                'id'   => ldl_pfx('contact_email'),
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Phone', 'ldd-directory-lite'),
                'id'   => ldl_pfx('contact_phone'),
                'type' => 'text_small',
            ),
            array(
                'name' => __('Fax', 'ldd-directory-lite'),
                'id'   => ldl_pfx('contact_fax'),
                'type' => 'text_small',
            ),
        ),
    );

    return $meta_boxes;
}
add_filter('cmb_meta_boxes', 'ldl_metaboxes_setup_cmb');


/**
 * Create a google map custom field that allows users to visually select a geographical location for the
 * current listing. Stores an array with the formatted address (as provided by the Google Maps Autocomplete &
 * Geocoder API), latitude and longitude.
 *
 * @param array $field Information pertaining to the field
 * @param array $meta Saved values for this field
 */
function ldl_render_geo_location_field($field, $meta) {

    wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');
    wp_enqueue_script('lddlite-admin');

    wp_enqueue_style('lddlite-admin');

    echo '<input type="text" class="autocomplete" id="' . $field['id'] . '">';
    echo '<input type="hidden" class="lat" name="' . $field['id'] . '[lat]" value="' . (isset($meta['lat']) ? $meta['lat'] : '') . '">';
    echo '<input type="hidden" class="lng" name="' . $field['id'] . '[lng]" value="' . (isset($meta['lng']) ? $meta['lng'] : '') . '">';
    echo '<div class="map-canvas"></div>';

    if (!empty($field['desc']))
        echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';

}
add_action('cmb_render_geo_location', 'ldl_render_geo_location_field', 10, 2);


/**
 * Runs through the `gettext` filter to change labels on our custom post type add/edit screen
 *
 * @param string $translations Translated text.
 * @param string $text         Text to translate.
 * @param string $domain       Text domain. Unique identifier for retrieving translated strings.
 *
 * @return mixed
 */
function ldl_relabel($translation, $text, $domain) {

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
