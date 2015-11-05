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
function ldl_060_address_format($post_ids) {

    if (!is_array($post_ids) || empty($post_ids)) { return; }

    foreach ($post_ids as $post_id) {

        // Gather the existing meta data
        $address = get_post_meta($post_id, ldl_pfx('address_one'), 1);
        $city = get_post_meta($post_id, ldl_pfx('city'), 1);
        $subdivision = get_post_meta($post_id, ldl_pfx('subdivision'), 1);
        $post_code = get_post_meta($post_id, ldl_pfx('post_code'), 1);
        $country = get_post_meta($post_id, ldl_pfx('country'), 1);

        $post_meta = array(
            'country'     => $country,
            'post_code'   => $post_code,
            'address_one' => $address,
            'address_two' => $city . (empty($subdivision) ? '' : ' ' . $subdivision),
            'geo'         => array(
                'lat'       => '',
                'lng'       => '',
            ),
        );

        foreach ($post_meta as $key => $value) {
            add_post_meta($post_id, ldl_pfx($key), $value);
        }

        // Delete leftovers
        delete_post_meta( $post_id, ldl_pfx('city') );
        delete_post_meta( $post_id, ldl_pfx('subdivision') );

    }

}


$post_ids = ldl_get_all_IDs();

if ($post_ids) {

    /**
     * The switch handles the various upgrades, while the trigger is used to determine if we need
     * to fire that upgrade.
     */
    foreach ($upgrades as $version => $trigger) {

        if (!$trigger)
            continue;

        switch ($version) {

            case '0.6.0-beta':
                ldl_060_address_format($post_ids);
                break;

        }

    }

}