<?php

global $upgrades;

/**
 * The switch handles the various upgrades, while the trigger is used to determine if we need
 * to fire that upgrade.
 */
foreach ( $upgrades as $version => $trigger ) {

	if ( !$trigger )
		continue;

	switch ( $version ) {

		case '0.5.5-beta':
			global $wpdb;

			$query = sprintf("
					SELECT ID, post_status
					FROM `%s`
					WHERE post_type = 'directory_listings'
						AND post_status IN ( 'publish', 'pending' )
				", $wpdb->posts, LDDLITE_POST_TYPE );
			$ids = $wpdb->get_col( $query );

			ldl_upgrade__promo( $ids );
			ldl_upgrade__geolocation( $ids );

	}

}



/**
 * A lot of people are asking where the promo information went. Let's bring it back and see if we can
 * use it in place of the post_excerpt.
 *
 * @param array $ids Array of Post IDs
 */
function ldl_upgrade__promo( $ids ) {
	global $wpdb;

	if ( !is_array( $ids ) || empty( $ids ) )
		return;

	foreach ( $ids as $post_id ) {
		$promo = get_post_meta( $post_id, LDDLITE_PFX . '_promotion', true );

		if ( $promo ) {
			$wpdb->update(
				$wpdb->posts,
				array( 'post_excerpt' => $promo ),
				array( 'ID' => $post_id ),
				array( '%s' ),
				array( '%d' )
			);
		}

		delete_post_meta( $post_id, LDDLITE_PFX . '_promotion' );
	}

}


/**
 * Collects the address post_meta and combines it to retrieve geocoded location information
 * from Google. Instead of writing a huge class to handles i18n addresses, it seems likely it
 * would never quite be on par with theirs...
 *
 * @param array $ids Array of Post IDs
 */
function ldl_upgrade__geolocation( $ids ) {

	if ( !is_array( $ids ) || empty( $ids ) )
		return;

	foreach ( $ids as $post_id ) {

		$address     = get_post_meta( $post_id, '_lddlite_address_one', 1 );
		$city        = get_post_meta( $post_id, '_lddlite_city', 1 );
		$subdivision = get_post_meta( $post_id, '_lddlite_subdivision', 1 );
		$post_code   = get_post_meta( $post_id, '_lddlite_post_code', 1 );
		$country     = get_post_meta( $post_id, '_lddlite_country', 1 );

		$address = $address . ', ' . $city . ', ' . $subdivision . ' ' . $post_code . ', ' . $country;

		$geo = array(
			'formatted' => '',
			'lat' => '',
			'lng' => '',
		);

		if ( '' != trim( $address, ' ,' ) ) {

			$get_address = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode( $address ) . '&sensor=false';
			$data = wp_remote_get( $get_address );

			if ( '200' == $data['response']['code'] ) {
				$body = json_decode( $data['body'] );
				$geo['formatted'] = $body->results[0]->formatted_address;
				$geo['lat'] = $body->results[0]->geometry->location->lat;
				$geo['lng'] = $body->results[0]->geometry->location->lng;
			} else {
				$geo['formatted'] = $address;
			}

		}

		update_post_meta( $post_id, LDDLITE_PFX . '_geo', $geo );

		delete_post_meta( $post_id, '_lddlite_address_one' );
		delete_post_meta( $post_id, '_lddlite_address_two' );
		delete_post_meta( $post_id, '_lddlite_city' );
		delete_post_meta( $post_id, '_lddlite_subdivision' );
		delete_post_meta( $post_id, '_lddlite_post_code' );
		delete_post_meta( $post_id, '_lddlite_country' );

	}

}