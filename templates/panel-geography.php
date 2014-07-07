<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <p class="section">Providing an address for your listing is optional, however if you would like for people to know where you are located we suggest including it.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="title">Address</label>
                <input type="text" id="title" class="form-control" name="ld_s_address_one" value="<?php echo ldl_get_value( 'address_one' ); ?>" required>
                <input type="text" id="title" class="form-control bump-down" name="ld_s_address_two" value="<?php echo ldl_get_value( 'address_two' ); ?>" required>
                <?php echo ldl_get_error( 'address_one' ); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="category">Postal Code</label>
                <input type="text" id="title" class="form-control" name="ld_s_address_one" value="<?php echo ldl_get_value( 'address_one' ); ?>" required>
                <?php echo ldl_get_error( 'category' ); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="category">Country</label>
                <input type="text" id="title" class="form-control" name="ld_s_address_one" value="<?php echo ldl_get_value( 'address_one' ); ?>" required>
                <?php echo ldl_get_error( 'category' ); ?>
            </div>
        </div>
    </div>
    <div class="row bump-down">
		<div class="col-md-12">
			<p>If you would like to include a Google map with your listing, you can set one here. Type in part of your address to use the autocomplete feature, or drag the marker on the map to your location.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 submit_form_geo_wrapper">
			<label class="control-label" for="">Set Marker</label>
			<input type="text" id="geo" class="form-control autocomplete-control">
			<div id="map-canvas"></div>
			<input type="hidden" id="lat" name="ld_s_geo[lat]" >
			<input type="hidden" id="lng" name="ld_s_geo[lng]" >
		</div>
	</div>
</div>

<script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?sensor=false&#038;libraries=places&#038;ver=3.9.1'></script>
<script>

		jQuery(document).ready(function($) {
			var searchInput = $('#geo')
			var $lat = $('#lat')
			var $lng = $('#lng')

			var latLng = new google.maps.LatLng(39.97712028761926, -102.70019568750001)
			var zoom = 4
			var geocoder = new google.maps.Geocoder();

            var mapCanvas   = $('#map-canvas')
            var mapOptions = {
				center: latLng,
				zoom: zoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map(mapCanvas, mapOptions)

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchInput);
			var markerOptions = {
				position: latLng,
				map: map,
				draggable: true,
			}
			var marker = new google.maps.Marker(markerOptions)

			google.maps.event.addListener(marker, 'drag', function() {
				$lat.val( marker.getPosition().lat() )
				$lng.val( marker.getPosition().lng() )
			})

			var autocomplete = new google.maps.places.Autocomplete(searchInput)
			autocomplete.bindTo('bounds', map)

			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace()
				if (place.geometry.viewport) {
					map.fitBounds(place.geometry.viewport)
				} else {
					map.setCenter(place.geometry.location)
					map.setZoom(16)
				}

				marker.setPosition(place.geometry.location)

				$lat.val(place.geometry.location.lat())
				$lng.val(place.geometry.location.lng())
			})

			$(searchInput).keypress( function(e) {
				if(e.keyCode == 13) {
					e.preventDefault()
				}
			})
		})

</script>