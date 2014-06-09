<section class="directory-lite directory-category">

    <header class="directory-header">
        {$header}
    </header>


    <div class="row">
        <div class="col-md-12">
            <ol class="l-breadcrumb">
                <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="{$term_link}">{$term_name}</a></li>
                <li class="active"><span>View Listing</span></li>
            </ol>
        </div>
    </div>

    <div id="search-directory-results"></div>


    <article id="listing-{$id}" class="directory-content listing-{$id} listing type-listing listing-full">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <span class="h2">{$title}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">


                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="h4 panel-title">Description</span>
                        </div>
                        <div class="panel-body">
                            {$description}
                        </div>
                    </div>

                    {if="$google_maps"}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <span class="h4 panel-title">Location</span>
                                    </div>
                                    <div class="panel-body">
                                        <div id="map_wrapper">
                                            <div id="map_canvas"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}

                </div>
                <div class="col-md-4" style="padding-left: 0;">
                    {$thumbnail}
                    <span class="social-meta">
                        {$social}
                    </span>

                    <div class="panel panel-default listing-meta" >
                        <div class="panel-heading">
                            <span class="h4 panel-title">Information</span>
                        </div>
                        <div class="panel-body">
                            <div id="map_wrapper">
                                <!--                        {if="!empty($website)"}<span class="website">{$website}</span>{/if} -->
                                {if="!empty($website)"}<p class="website">{$website}</p>{/if}
                                {if="!empty($address)"}<p class="address">{$address}</p>{/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>



    </article>


</section>

{if="$google_maps"}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbaw0hFglsihePOsFpMnLQwJZtOChIoDg&sensor=false"></script>
<script>

    function initialize() {
        var mapLatLng = new google.maps.LatLng({$geo.lat}, {$geo.lng})
        var mapOptions = {
            center: mapLatLng,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl: false,
        }
        var map = new google.maps.Map( document.getElementById("map_canvas"), mapOptions )

        var marker = new google.maps.Marker({
            position: mapLatLng,
            map: map,
            animation: google.maps.Animation.DROP,
        })

    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
{/if}
