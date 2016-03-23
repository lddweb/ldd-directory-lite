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
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

    /*  var markerOptions = {
     position: latLng,
     map: map,
     draggable: true
     };
     var marker = new google.maps.Marker(markerOptions);*/

    if (geocoder && address !== 0) {

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
    }
}

function update_user_address() {
    var address = "";

    if(jQuery("#f_address_one").val().length > 0) {
        address = jQuery("#f_address_one").val();
    }
    if(jQuery("#f_address_two").val().length > 0) {
        if(jQuery("#f_address_one").val().length > 0) {
            address = address + ", " + jQuery("#f_address_two").val();
        }else{
            address = address + jQuery("#f_address_two").val();
        }
    }
    if(jQuery("#f_city").val().length > 0) {
        address = address +", "+ jQuery("#f_city").val();
    }
    /* if(jQuery("#f_postal_code").val().length > 0) {
     address = address +", "+ jQuery("#f_postal_code").val();
     }*/
    if(jQuery("#f_state").val().length > 0) {
        address = address +", "+ jQuery("#f_state").val();
    }
    if(jQuery("#f_country").val().length > 0) {
        address = address +", "+ jQuery("#f_country").val();
    }
    if(address) {
        jQuery(".full_address_geo").val(address);
        jQuery(".full_address_i").html("<strong><u>Address:</u></strong> "+address);
        initialize(address);
    }
}

(function($) {

    var countries = ["Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegowina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, the Democratic Republic of the","Cook Islands","Costa Rica","Cote d'Ivoire","Croatia (Hrvatska)","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","France Metropolitan","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Heard and Mc Donald Islands","Holy See (Vatican City State)","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran (Islamic Republic of)","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, Democratic People's Republic of","Korea, Republic of","Kuwait","Kyrgyzstan","Lao, People's Democratic Republic","Latvia","Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia, The Former Yugoslav Republic of","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia, Federated States of","Moldova, Republic of","Monaco","Mongolia","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russian Federation","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Seychelles","Sierra Leone","Singapore","Slovakia (Slovak Republic)","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and the South Sandwich Islands","Spain","Sri Lanka","St. Helena","St. Pierre and Miquelon","Sudan","Suriname","Svalbard and Jan Mayen Islands","Swaziland","Sweden","Switzerland","Syrian Arab Republic","Taiwan, Province of China","Tajikistan","Tanzania, United Republic of","Thailand","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","Virgin Islands (British)","Virgin Islands (U.S.)","Wallis and Futuna Islands","Western Sahara","Yemen","Yugoslavia","Zambia","Zimbabwe"];
    $("#f_country").autocomplete({source: countries});

    $("#f_address_one,#f_address_two,#f_city,#f_postal_code,#f_country,#f_state").on("blur",function(e){
        update_user_address();
    });
    initialize(0);
    update_user_address();

}(jQuery));