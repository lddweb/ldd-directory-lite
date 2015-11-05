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

function initialize(address) {
    var latLng;
    var zoom = 16;

    var $lat = jQuery('.lat');
    var $lng = jQuery('.lng');
    var has_points = false;

    geocoder = new google.maps.Geocoder();

    if ($lat.val().length > 0 && $lng.val().length > 0) {
        latLng = new google.maps.LatLng($lat.val(), $lng.val());
        has_points = true;
    } else {
        latLng = new google.maps.LatLng(39.97712028761926, -102.70019568750001);
        zoom = 4;
    }

    var mapOptions = {
        center: latLng,
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

    var markerOptions = {
        position: latLng,
        map: map,
        draggable: true
    };
    var marker = new google.maps.Marker(markerOptions);

    if (geocoder && address != 0) {
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                    map.setCenter(results[0].geometry.location);

                    var infowindow = new google.maps.InfoWindow({
                        content: '<b>' + address + '</b>',
                        size: new google.maps.Size(150, 50)
                    });

                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                        title: address
                    });

                    $lat.val(marker.getPosition().lat());
                    $lng.val(marker.getPosition().lng());

                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(map, marker);
                    });

                } else {
                    alert("No results Found.");
                }
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });

/*        google.maps.event.addListener(marker, 'drag', function () {
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        jQuery(".full_address_geo").val(results[0].formatted_address);
                        jQuery(".full_address_i").html("<strong><u>Address:</u></strong> "+results[0].formatted_address);
                    }
                }
            });
            $lat.val(marker.getPosition().lat());
            $lng.val(marker.getPosition().lng());
        });*/
    }
}

function update_user_address() {
    var address = "";

    if(jQuery("#_lddlite_address_one").val().length > 0) {
        address = jQuery("#_lddlite_address_one").val();
    }
    if(jQuery("#_lddlite_address_two").val().length > 0) {
        if(jQuery("#_lddlite_address_one").val().length > 0) {
            address = address + ", " + jQuery("#_lddlite_address_two").val();
        }else{
            address = address + jQuery("#_lddlite_address_two").val();
        }
    }
    if(jQuery("#_lddlite_city").val().length > 0) {
        address = address +", "+ jQuery("#_lddlite_city").val();
    }
    /* if(jQuery("#_lddlite_postal_code").val().length > 0) {
     address = address +", "+ jQuery("#_lddlite_postal_code").val();
     }*/
    if(jQuery("#_lddlite_state").val().length > 0) {
        address = address +", "+ jQuery("#_lddlite_state").val();
    }
    if(jQuery("#_lddlite_country").val().length > 0) {
        address = address +", "+ jQuery("#_lddlite_country").val();
    }
    if(address) {
        jQuery(".full_address_geo").val(address);
        jQuery(".full_address_i").html("<strong><u>Address:</u></strong> "+address);
        initialize(address);
    }
}

(function($) {
    $("#_lddlite_address_one,#_lddlite_address_two,#_lddlite_city,#_lddlite_postal_code,#_lddlite_country,#_lddlite_state").on("blur",function(e){
        update_user_address();
    });
    initialize(0);
    update_user_address();
}(jQuery));