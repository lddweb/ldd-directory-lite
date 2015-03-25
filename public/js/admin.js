jQuery(document).ready(function ($) {
    $('.lddlite-color-picker').wpColorPicker();

    var uninstallCheck = $("input[id=lite-debug_uninstall]");
    var warningStack = $("p.warning");
    uninstallCheck.change(function () {
        var closestTD = $(this).closest('td');
        if (uninstallCheck.is(":checked")) {
            closestTD.css('background-color', '#DA4453')
            warningStack.css({
                'color': '#fff',
                'font-weight': '700'
            })
        } else {
            closestTD.css('background-color', 'transparent')
            warningStack.css({
                'color': 'inherit',
                'font-weight': 'inherit'
            })
        }
    })
});

(function($) {

    function setLatLng(position) {
        window.latLng = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        window.latLng = 'something?';
        console.log(window.latLng);
    }

    $('.cmb-type-geo_location').each(function () {
        var latLng;
        var zoom = 16;

        var searchInput = $('.autocomplete', this)[ 0 ];
        var mapCanvas = $('.map-canvas', this)[ 0 ];
        var $lat = $('.lat', this);
        var $lng = $('.lng', this);

        var geocoder = new google.maps.Geocoder();

        if ($lat.val().length > 0 && $lng.val().length > 0) {
            latLng = new google.maps.LatLng($lat.val(), $lng.val())
        } else {
            latLng = new google.maps.LatLng(39.97712028761926, -102.70019568750001)
            zoom = 4
        }

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

        if ($lat.val().length > 0 && $lng.val().length > 0) {
            marker.setPosition(latLng)
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $(searchInput).val(results[0].formatted_address);
                    }
                }
            });
        }

        google.maps.event.addListener(marker, 'drag', function () {
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $(searchInput).val(results[0].formatted_address);
                    }
                }
            });
            $lat.val(marker.getPosition().lat())
            $lng.val(marker.getPosition().lng())
        })

        var autocomplete = new google.maps.places.Autocomplete(searchInput)
        autocomplete.bindTo('bounds', map)

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
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

        $(searchInput).keypress(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault()
            }
        })
    })

}(jQuery))