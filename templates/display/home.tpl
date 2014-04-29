<section class="business-directory directory-home cf">

    <nav class="lite-nav above-header center cf">
        <ul>
            <li><a href="{{url}}?show=submit&t=listing">Submit Listing</a></li>
        </ul>
    </nav>

    <header class="global-header">
        {{search_form}}
    </header>

    <nav class="lite-nav below-header center cf" style="display:none;">
        <ul class="top-level">
            <li class="cat-item cat-item-2"><a href="http://local.wordpress.dev/dir/?show=category&t=arts-entertainment" title="View all posts filed under Arts &amp; Entertainment">Arts &amp; Entertainment</a></li>
            <li class="cat-item cat-item-5"><a href="http://local.wordpress.dev/dir/?show=category&t=computers-electronics" title="View all posts filed under Computers &amp; Electronics">Computers &amp; Electronics</a></li>
            <li class="cat-item cat-item-9"><a href="http://local.wordpress.dev/dir/?show=category&t=food-dining" title="View all posts filed under Food &amp; Dining">Food &amp; Dining</a></li>
            <li class="cat-item cat-item-22"><a href="http://local.wordpress.dev/dir/?show=category&t=general" title="View all posts filed under General">General</a></li>
            <li class="cat-item cat-item-13"><a href="http://local.wordpress.dev/dir/?show=category&t=home-garden" title="View all posts filed under Home &amp; Garden">Home &amp; Garden</a></li>
            <li class="cat-item cat-item-23"><a href="http://local.wordpress.dev/dir/?show=category&t=miscellaneous" title="View all posts filed under Miscellaneous">Miscellaneous</a></li>
            <li class="cat-item cat-item-21"><a href="http://local.wordpress.dev/dir/?show=category&t=test-category" title="View all posts filed under Test Category">Test Category</a></li>
        </ul>
    </nav>


    <section class="directory-content">

        <ul class="category-tree category-home">
        {{categories}}
        </ul>

    </section>


    <section class="directory-content">

        <article id="listing-1" class="listing-1 listing type-listing status-approved featured cf">
            <a href="" class="post-thumbnail"><img src="http://www.aaas.org/sites/default/files/migrate/uploads/0707green_building_0242.jpg"></a>

            <header class="entry-header">
                <h2 class="entry-title listing-title"><a href="{{base_url}}?show=business&term=herman_melville">Example Listing Business</a></h2>
                <div class="entry-meta">
                    <p class="website"><a href="">lddconsulting.com</a></p>
                    <p class="phone" style="font-size: 100%; display: none;">(505) 455-8749</p>
                    <p class="address" style="font-size: 100%;">2420 Midtown Pl NE,<br>Albuquerque, NM 87107</p>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <p>Secondly: The ship Union, also of Nantucket, was in the year 1807 totally lost off the Azores by a similar onset, but the authentic particulars of this catastrophe I have never chanced to encounter... <a class="moretag" href="">Read More</a></p>
            </div><!-- .entry-summary -->
        </article><!-- #listing-## -->


        <article id="listing-2" class="listing-2 listing type-listing status-approved odd cf">
            <a href="" class="post-thumbnail"><img src="http://www.aaas.org/sites/default/files/migrate/uploads/0707green_building_0242.jpg"></a>

            <header class="entry-header">
                <h2 class="entry-title listing-title"><a href="<?php echo $_SERVER['HTTP_HOST']">Example Listing Business</a></h2>
                <div class="entry-meta">
                    <p class="website"><a href="">lddconsulting.com</a></p>
                    <p class="phone" style="font-size: 100%; display: none;">(505) 455-8749</p>
                    <p class="address" style="font-size: 100%;">2420 Midtown Pl NE,<br>Albuquerque, NM 87107</p>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <p>Secondly: The ship Union, also of Nantucket, was in the year 1807 totally lost off the Azores by a similar onset, but the authentic particulars of this catastrophe I have never chanced to encounter... <a class="moretag" href="">Read More</a></p>
            </div><!-- .entry-summary -->
        </article><!-- #listing-## -->


        <article id="listing-3" class="listing-3 listing type-listing status-approved even cf">
            <a href="" class="post-thumbnail"><img src="http://www.aaas.org/sites/default/files/migrate/uploads/0707green_building_0242.jpg"></a>

            <header class="entry-header">
                <h2 class="entry-title listing-title"><a href="">Example Listing Business</a></h2>
                <div class="entry-meta">
                    <p class="website"><a href="">lddconsulting.com</a></p>
                    <p class="phone" style="font-size: 100%; display: none;">(505) 455-8749</p>
                    <p class="address" style="font-size: 100%;">2420 Midtown Pl NE,<br>Albuquerque, NM 87107</p>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <p>Secondly: The ship Union, also of Nantucket, was in the year 1807 totally lost off the Azores by a similar onset, but the authentic particulars of this catastrophe I have never chanced to encounter... <a class="moretag" href="">Read More</a></p>
            </div><!-- .entry-summary -->
        </article><!-- #listing-## -->



    </section>


</section>



<script>
    var lite_breakpoint = 640;
    var shrunk = false;

    jQuery(document).ready(function() {
        if ( jQuery(window).width() < lite_breakpoint  && jQuery(".lite-nav.below-header .current").length == 0 ) {
            jQuery('.lite-nav.below-header li:first-child').addClass('current');
        }
    });

    jQuery(window).resize(function() {
        if ( jQuery(window).width() < lite_breakpoint  && jQuery(".lite-nav.below-header .current").length == 0 ) {
            shrunk = true;
            jQuery('.lite-nav.below-header li:first-child').addClass('current');
        }
        if ( jQuery(window).width() > lite_breakpoint && shrunk ) {
            shrunk = false;
            jQuery('.lite-nav.below-header li').removeClass('current');
        }
    });


    jQuery(document).ready(function() {
        var show_tree_btn = jQuery('#show-tree');
        var tree_div = jQuery('#tree-hide');
        show_tree_btn.click(function(e) {
            e.preventDefault();
            tree_div.slideToggle();
        });
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
            ['London Eye, London', 51.503454,-0.119562],
            ['Palace of Westminster, London', 51.499633,-0.124755]
        ];

        // Info Window Content
        var infoWindowContent = [
            ['<div class="info_content">' +
            '<h3>London Eye</h3>' + '</div>'],
            ['<div class="info_content">' +
            '<h3>Palace of Westminster</h3>' +
            '</div>']
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
            this.setZoom(14);
            google.maps.event.removeListener(boundsListener);
        });

    }
</script>
