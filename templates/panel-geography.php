<div class="container-fluid">
	<div class="row bump-down">
		<div class="col-md-12">
			<p>Tell us where your organization is located, and we'll include a map on your listing page. As before, if you don't wish to include this information, leave the field blank.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 submit_form_geo_wrapper">
			<label class="control-label" for="">Location</label>
			<input type="text" id="geo" class="map_search form-control bump-up">
			<div class="map_wrapper"></div>
			<input type="hidden" class="formatted" name="ld_s_geo[formatted]" >
			<input type="hidden" class="lat" name="ld_s_geo[lat]" >
			<input type="hidden" class="lng" name="ld_s_geo[lng]" >
		</div>
	</div>
</div>


<style>
	.map_wrapper {
		width: 100%;
		height: 200px;
		border: 1px solid #ccc;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
		/* box-shadow: inset 0 1px 1px rgba(0,0,0,0.075); */
		-webkit-transition: bord
	}
	.map_wrapper img {
		max-width: none;
	}
</style>

<script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?sensor=false&#038;libraries=places&#038;ver=3.9.1'></script>
<script>

	(function ($) {

		$('.submit_form_geo_wrapper').ready(function() {
			var searchInput = $('.map_search', this).get(0)
			var mapCanvas   = $('.map_wrapper', this).get(0)
			var $lat = $('.lat', this)
			var $lng = $('.lng', this)
			var $formatted = $('.formatted', this)

			var latLng = new google.maps.LatLng( 39.97712028761926, -102.70019568750001 )
			var zoom = 6
			var geocoder = new google.maps.Geocoder();

			if ( $lat.val().length > 0 && $lng.val().length > 0 ) {
				latLng = new google.maps.LatLng( $lat.val(), $lng.val() )
				zoom = 16
			}

			var mapOptions = {
				center: latLng,
				zoom: zoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map( mapCanvas, mapOptions )

			var markerOptions = {
				position: latLng,
				map: map,
				draggable: true,
			}
			var marker = new google.maps.Marker( markerOptions )

			if( $lat.val().length > 0 && $lng.val().length > 0 ) {
				marker.setPosition( latLng )
			}

			google.maps.event.addListener( marker, 'drag', function() {
				geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$(searchInput).val( results[0].formatted_address );
							$formatted.val( results[0].formatted_address );
						}
					}
				});
				$lat.val( marker.getPosition().lat() )
				$lng.val( marker.getPosition().lng() )
			})

			var autocomplete = new google.maps.places.Autocomplete( searchInput )
			autocomplete.bindTo( 'bounds', map )

			google.maps.event.addListener( autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace()
				if ( place.geometry.viewport ) {
					map.fitBounds( place.geometry.viewport )
				} else {
					map.setCenter( place.geometry.location )
					map.setZoom( 16 )
				}

				marker.setPosition( place.geometry.location )

				$formatted.val( place.formatted_address )
				$lat.val( place.geometry.location.lat() )
				$lng.val( place.geometry.location.lng() )
			})

			$(searchInput).keypress( function(e) {
				if(e.keyCode == 13) {
					e.preventDefault()
				}
			})
		})

	}(jQuery))
</script>