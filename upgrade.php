<?php
/**
 * Boosts, enhancements, increasements, advancements, betterments, progressments, and functions that make strides.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

global $upgrades;


/**
 * Collects the address post_meta and combines it to retrieve geocoded location information
 * from Google. This and handling other meta information such as phone numbers could easily get
 * ridiculously cumbersome. Let's attempt to keep it as simple as possible, as much as possible.
 *
 * @param array $post_ids Array of Post IDs
 */
function ldl_060_address_to_geo($post_ids) {

    if (!is_array($post_ids) || empty($post_ids)) { return; }

    foreach ($post_ids as $post_id) {

        // Gather the existing meta data
        $address = get_post_meta($post_id, '_lddlite_address_one', 1);
        $city = get_post_meta($post_id, '_lddlite_city', 1);
        $subdivision = get_post_meta($post_id, '_lddlite_subdivision', 1);
        $post_code = get_post_meta($post_id, '_lddlite_post_code', 1);
        $country = get_post_meta($post_id, '_lddlite_country', 1);

        // Put it together
        $address = $address . ', ' . $city . ', ' . $subdivision . ' ' . $post_code . ', ' . $country;

        $geo = array(
            'formatted' => '',
            'lat'       => '',
            'lng'       => '',
        );

        // Make sure we have something to work with
        if ('' != trim($address, ' ,')) {

            $get_address = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false';
            $data = wp_remote_get($get_address);

            // Use the data from Google's geocoder API to display addresses and set map coordinates
            if ('200' == $data['response']['code']) {
                $body = json_decode($data['body']);
                $geo['formatted'] = $body->results[0]->formatted_address;
                $geo['lat'] = $body->results[0]->geometry->location->lat;
                $geo['lng'] = $body->results[0]->geometry->location->lng;
            } else {
                // If the geocoder fails, this should kickstart the dashboard autocomplete
                $geo['formatted'] = $address;
            }

        }

        update_post_meta($post_id, LDDLITE_PFX . '_geo', $geo);

        // Don't delete until we hear silence, or hear back that everyone is happy with the new paradigm

        /*		delete_post_meta( $post_id, '_lddlite_address_one' );
                delete_post_meta( $post_id, '_lddlite_address_two' );
                delete_post_meta( $post_id, '_lddlite_city' );
                delete_post_meta( $post_id, '_lddlite_subdivision' );
                delete_post_meta( $post_id, '_lddlite_post_code' );
                delete_post_meta( $post_id, '_lddlite_country' );*/

    }

}


$post_ids = ldl_get_all_IDs();

if ($post_ids) {

    /**
     * The switch handles the various upgrades, while the trigger is used to determine if we need
     * to fire that upgrade.
     */
    foreach ($upgrades as $version => $trigger) {

        if (!$trigger) { continue; }

            switch ($version) {

                case '0.6.0-beta':
                    ldl_060_address_to_geo($post_ids);
                    break;

            }

        }

    }
}