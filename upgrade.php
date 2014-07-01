<?php

global $upgrades;

/**
 * The switch handles the various upgrades, while the trigger is used to determine if we need
 * to fire that upgrade.
 */
foreach ($upgrades as $version => $trigger) {

    if (!$trigger)
        continue;

    switch ($version) {

        case '0.5.5-beta':
            global $wpdb;

            $IDs = ldl_upgrade__get_IDs();

            if ($IDs) {
                ldl_upgrade__promo($IDs);
                ldl_upgrade__geolocation($IDs);
            }

    }

}


/**
 * Pull an array of directory post IDs for use in updating all the information.
 */
function ldl_upgrade__get_IDs() {
    global $wpdb;

    $query = sprintf("
					SELECT ID, post_status
					FROM `%s`
					WHERE post_type = '%s'
						AND post_status IN ( 'publish', 'pending' )
				", $wpdb->posts, LDDLITE_POST_TYPE);

    return $wpdb->get_col($query);
}

/**
 * A lot of people are asking where the promo information went. Let's bring it back and see if we can
 * use it in place of the post_excerpt.
 *
 * @param array $IDs Array of Post IDs
 */
function ldl_upgrade__promo($IDs) {
    global $wpdb;

    if (!is_array($IDs) || empty($IDs))
        return;

    foreach ($IDs as $post_id) {

        // These were stored in meta during the upgrade from the old plugin
        $promo = get_post_meta($post_id, LDDLITE_PFX . '_promotion', true);

        // But that doesn't mean they upgraded from the old plugin
        if ($promo) {
            $wpdb->update($wpdb->posts, array('post_excerpt' => $promo), array('ID' => $post_id), array('%s'), array('%d'));
        }

        // Make sure there's no clutter left behind
        delete_post_meta($post_id, LDDLITE_PFX . '_promotion');
    }

}


/**
 * Collects the address post_meta and combines it to retrieve geocoded location information
 * from Google. Instead of writing a huge class to handles i18n addresses, it seems likely it
 * would never quite be on par with theirs...
 *
 * @param array $IDs Array of Post IDs
 */
function ldl_upgrade__geolocation($IDs) {

    if (!is_array($IDs) || empty($IDs))
        return;

    foreach ($IDs as $post_id) {

        // Gather the existing meta data
        $address = get_post_meta($post_id, '_lddlite_address_one', 1);
        $city = get_post_meta($post_id, '_lddlite_city', 1);
        $subdivision = get_post_meta($post_id, '_lddlite_subdivision', 1);
        $post_code = get_post_meta($post_id, '_lddlite_post_code', 1);
        $country = get_post_meta($post_id, '_lddlite_country', 1);

        // Put it together
        $address = $address . ', ' . $city . ', ' . $subdivision . ' ' . $post_code . ', ' . $country;

        // If there's no data, we still want to initialize the new meta information
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