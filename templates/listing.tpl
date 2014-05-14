<section class="business-directory directory-category cf">

    <header class="directory-header">
        {$header}
    </header>


    <div class="row">
        <div class="col-md-8">
            <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em;">
                <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="{$term_link}">{$term_name}</a></li>
                <li class="active"><span>View Listing</span></li>
            </ol>

        </div>

        <div class="col-md-4 view-types" style="text-align: center;">
            <div class="btn-group">
                <a href="" class="btn btn-success" data-toggle="modal" data-target="#contact-listing-owner"><i class="fa fa-envelope"></i></a>
                <a href="" class="btn btn-success"><i class="fa fa-facebook-square"></i></a>
                <a href="" class="btn btn-success"><i class="fa fa-twitter"></i></a>
                <a href="" class="btn btn-success"><i class="fa fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    <div id="search-directory-results"></div>


    <article id="listing-{$id}" class="directory-content listing-{$id} listing type-listing listing-full cf">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="listing-title panel-title">{$title}</h3>
            </div>
            <div class="panel-body">

                <div class="row">
                    {if="!empty($website)"}<p class="website">{$website}</p>{/if}
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-md-8">



                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Description</h3>
                    </div>
                    <div class="panel-body">
                        {$description}
                    </div>
                </div>


            </div>
            <div class="col-md-4">

                <div class="panel panel-default">

                    <div class="panel-body">
                        {$thumbnail}

                        <div class="listing-meta">
                            {if="!empty($meta.website)"}<p class="website"><i class="fa fa-phone"></i> {$meta.website}</p>{/if}
                            {if="!empty($address)"}<p class="address"><i class="fa fa-globe"></i> {$address}</p>{/if}
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {if="$google_maps"}
        <div class="panel panel-default" >
            <div class="panel-heading">
                <h4 class="panel-title">Location</h3>
            </div>
            <div class="panel-body">
                <div id="map_wrapper">
                    <div id="map_canvas" class="mapping"></div>
                </div>
            </div>
        </div>
        {/if}


    </article>


</section>


{if="$google_maps"}
<script>
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
{/if}


<script>

    jQuery(document).ready(function($) {

        var modalTitle = $("#contact-modal-title")
        modalTitle.text( "Contact {$title}" )



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

</script>

<style>
    #map_wrapper {
        margin: 0 -15px;
    }
    .listing-full .panel:last-child {
        margin-bottom: 0;
    }
    .listing-full .panel {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    .listing-full .panel-heading {
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .panel .listing-meta {
        margin: .1em 0;
        color: rgba(119, 119, 119, 0.8);
    }

    .panel-body {
        position: relative;
    }
    .panel-title {
        margin: 0 !important;
        letter-spacing: normal !important;
    }
    .panel-primary .panel-title {
        color: #fff !important;
    }
    .type-listing.listing-full {
        padding: 1em;
        position: relative;
    }
    .panel p.website {
        margin-bottom: 0;
        margin-left: 1em;
        font-size: 1.2em;
    }
    .business-directory .type-listing.listing-full .listing-header .listing-meta p {
        font-size: 1.2em;
        line-height: normal;
        color: rgba(119, 119, 119, 0.8);
        margin: 0 0 8px;
    }
    .listing-full .listing-meta p .fa {
        top: 4px;
        left: 2px;
    }
/*    .listing-thumbnail {
        position: absolute;
        top: 0;
        right: 0;
    }*/
    .type-listing.listing-full .img-rounded {
        margin: 0;
        margin-top: -15px;
        height: auto;
    }

    .listing-full h2.listing-title {
        font-size: 2.4em !important;
    }

    .listing-description {
        clear: both;
        width: 100%;
    }


    .listing-header {
        position: relative;
        padding-right: 210px;
        min-height: 210px;
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
        font-size: 1.4em;
        color: green;
    }
    .listing-social {
        padding: 0;
        margin-top: 1.5em;
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
    .listing-description {
        border-top: 1px solid rgba(119, 119, 119, 0.3);
    }
</style>
