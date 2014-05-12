<section class="business-directory directory-category cf">

    <header class="directory-header">
        {$header}
    </header>

    <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em;">
        <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#">Arts &amp; Entertainment</li>
        <li class="active"><span>{$title}</span></li>
    </ol>


    <article id="listing-1" class="listing-1 listing type-listing full-listing status-approved light cf">

        <header class="listing-header">
            <div class="listing-logo">{$logo}</div>

            <h2 class="listing-title">{$title}</h2>
            <div class="listing-meta">
                <p class="website">{$meta.website}</p>
                {if="!empty($address)"}<p class="address">{$address}</p>{/if}
            </div>
            <div class="listing-social">
                {$social}
            </div>
        </header>

        <div id="listing-contact-form">
            {$contact_form}
        </div>



            <div class="listing-description">
                {$description}
            </div>
            <div id="map_wrapper">
                <div id="map_canvas" class="mapping"></div>
            </div>
        </article>




</section>



<script>

    jQuery(document).ready(function($) {

        var contactdiv = $(".listing-contact-form");

        $("a[rel=contact]").click(function() {
            if ( contactdiv.is(":visible") ) {
                contactdiv.fadeOut( 'slow' );
            } else {
                contactdiv.show( 'slow', 'swing' )
            }
        })

        $("a.cancel").click(function() {
            contactdiv.hide( 'slow' );
        })

    });



    jQuery(function() {
        // Asynchronously Load the map API
        var script = document.createElement('script');
        script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
        document.body.appendChild(script);
    });

    function initialize() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap'
        };

        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        map.setTilt(45);

        // Multiple Markers
        var markers = [
            ['Herman Melville, Albuquerque', {$geo.lat},{$geo.lng}]
        ];

        // Info Window Content
        var infoWindowContent = [
            ['<div class="info_content"><h3>Business Information!</h3></div>']
        ];

        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;

        // Loop through our array of markers & place each one on the map
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0]
            });

            var contentString = '<div id="map-window-' + i + '">Sample Content</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }

        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(16);
            google.maps.event.removeListener(boundsListener);
        });

    }
</script>





<style>
    .listing-description {
        clear: both;
        width: 100%;
    }
    .listing-header .listing-logo img {
        width: 200px;
        height: 200px;
    }


    .listing-header {
        position: relative;
        padding-right: 210px;
        min-height: 210px;
    }
    .listing-header .listing-logo {
        position: absolute;
        top: 0;
        right: 0;
    }
    .listing-header h2.listing-title {
        margin: 10px 0 0;
        padding: 0;
        text-align: left;
        font-size: 2.5em;
        font-weight: 300;
        letter-spacing: -1px;
        font-family: helvetica, arial;
    }
    .listing-header .listing-meta p.website {
        margin: 0;
        padding: 0;
    }
    .listing-header .listing-meta p.website a {
        font-size: 1.1em;
        color: green;
    }
    .listing-social {
        padding: 0;
        margin-bottom: .5em;
        text-align: left;
    }
    .listing-social a {
        margin: 0 2px;
        padding: 0;
        display: inline-block;
        width: 48px;
        height: 48px;
    }
    .listing-social a {
        background: rgba(221, 221, 221, 0.5);
    }
    .listing-social a:hover {
        background: #2f67a1;
    }
    .listing-social a.red:hover {
        background: #d31800;
    }
    .listing-social a.orange:hover {
        background: #f95400;
    }
    .listing-social a.yellow:hover {
        background: #ffbf00;
    }
    .listing-social a.green:hover {
        background: #7fb500;
    }
    .listing-social a.blue:hover {
        background: #2968a2;
    }
    .listing-description
</style>
