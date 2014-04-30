<style>
    .display-business .listing {
        padding: .5em 0 2.5em;
        margin-bottom: 1em;
    }

    .display-business .listing-image {
        float: left;
        position: relative;
        width: 26%;
        margin: 0 2% 0 0;
    }
    .display-business .listing-content {
        width: 66%;
        float: left;
    }

    .display-business .listing .featured-image {
        display: inline-block;
        vertical-align: top;
    }
    .display-business .listing .featured-image img {
        max-width: 100%;
        height: auto;
    }
    .display-business .listing-title {
        font-size: 140%;
        font-weight: normal;
        line-height: 130%;
        margin: 0 0 5px;
    }
    .display-business .listing-title a {
        text-decoration: none;
    }
    .display-business .listing-title a:hover {
        text-decoration: none;
    }

    .display-business .listing-meta p {
        font-size: 90%;
        line-height: 130%;
        color: #777;
        margin: 0 0 8px;
    }
    .display-business .listing-meta a {
        text-decoration: none;
        font-weight: 700;
        font-size: 110%;
    }
    .display-business .listing-meta a:hover {
        text-decoration: none;
    }




    .listing-description {
        clear: both;
        width: 100%;
    }
    .business-directory .listing-title {

    }
    .listing-social {
        padding: .5em 0;
        margin-bottom: .5em;
        background: #fff;
        border: 1px solid rgba(32, 69, 108, 0.12);
        border-radius: 2px;
        text-align: center;
    }

    .listing-header {
        padding-top: 1em;
        text-align: center;
    }

    .listing-header .post-thumbnail img {
        max-width: 100px;
    }

    .listing-header .listing-title {
        margin: .2em 0;
        text-align: center;
        font-size: 3em;
    }
    .listing-social a {
        margin: 0 2px;
        padding: 3px;
        display: inline-block;
        width: 48px;
        height: 48px;
    }
    .listing-social a:hover {
        background: #eee;
        border-radius: 3px;
    }
    .listing-social img
</style>


<section class="business-directory cf">

    <nav class="lite-nav above-header center cf">
        <ul>
            <li><a href="{{url}}" id="show-tree">Directory Home</a></li>
            <li><a href="{{url}}?show=submit&t=listing">Submit Listing</a></li>

        </ul>
    </nav>

    <section class="directory-content">

        <header class="listing-header">
            <a href="" class="post-thumbnail">{{logo}}</a>
            <h2 class="listing-title">{{title}}</h2>
        </header>

        <div class="listing-social">
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/browser.png" width="48" height="48" alt="" /></a>
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/ebay.png" width="48" height="48" alt="" /></a>
            <a rel="leanModal" href="#panels"><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/email.png" width="48" height="48" alt="" /></a>
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/fb.png" width="48" height="48" alt="" /></a>
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/google+.png" width="48" height="48" alt="" /></a>
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/linkedin.png" width="48" height="48" alt="" /></a>
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/social/twitter.png" width="48" height="48" alt="" /></a>
        </div>

        <article id="listing-1" class="listing-1 listing type-listing full-listing status-approved featured cf">

            <div class="location-wrapper pure-g-r">

                <div id="map_wrapper" class="pure-u-2-3">
                    <div id="map_canvas" class="mapping"></div>
                </div>
                <div class="pure-u-1-24"></div>

                <div class="entry-meta pure-u-7-24" style="font-size: .75em;">
                    <p class="website"><a href="">lddconsulting.com</a></p>
                    <p class="phone" style="font-size: 100%; display: none;">(505) 455-8749</p>
                    <p class="address" style="font-size: 100%;">2420 Midtown Pl NE,<br>Albuquerque, NM 87107</p>
                    <div class="ratings-stars">
                        <img src="/wp-content/plugins/ldd-directory-lite/public/icons/stars.png">
                        <p class="rating"><a href="">write a review</a> | <a href="">read reviews (3)</a></p>
                    </div>
                </div><!-- .entry-meta -->

            </div>

            <div class="listing-description">
                <p>Cutting me a green measuring-rod, I once more dived within the skeleton. From their arrow-slit in the skull, the priests perceived me taking the altitude of the final rib, "How now!" they shouted; "Dar'st thou measure this our god! That's for us." "Aye, priests&mdash;well, how long do ye make him, then?" But hereupon a fierce contest rose among them, concerning feet and inches; they cracked each other's sconces with their yard-sticks&mdash;the great skull echoed&mdash;and seizing that lucky chance, I quickly concluded my own admeasurements.</p>
                <p>These admeasurements I now propose to set before you. But first, be it recorded, that, in this matter, I am not free to utter any fancied measurement I please. Because there are skeleton authorities you can refer to, to test my accuracy. There is a Leviathanic Museum, they tell me, in Hull, England, one of the whaling ports of that country, where they have some fine specimens of fin-backs and other whales. Likewise, I have heard that in the museum of Manchester, in New Hampshire, they have what the proprietors call "the only perfect specimen of a Greenland or River Whale in the United States." Moreover, at a place in Yorkshire, England, Burton Constable by name, a certain Sir Clifford Constable has in his possession the skeleton of a Sperm Whale, but of moderate size, by no means of the full-grown magnitude of my friend King Tranquo's.</p>
            </div>

        </article>

    </section>


</section>


<div id="panels">
    <div id="panels-ct">

        <form class="lddlite" action="{{form_action}}" method="post">
            <input type="hidden" name="action" value="contact_form" />
            <input type="hidden" name="nonce" value="{{nonce}}" />
            <input type="hidden" name="business_id" value="{{id}}" />
        <fieldset class="panel">
            <legend>Contact</legend>
            <p>
                <label for="name">Your Name</label>
                <input id="name" type="text" name="name" required />
                <label for="name" id="label-name" class="info"></label>
            </p>
            <p>
                <label for="username">Your Email</label>
                <input id="email" name="email" type="email" required AUTOCOMPLETE=OFF />
                <label for="email" id="label-email" class="info"></label>
            </p>
            <p>
                <label for="subject">Subject</label>
                <input id="subject" type="text" name="subject" required />
                <label for="subject" id="label-subject" class="info"></label>
            </p>
            <p>
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
                <label for="message" id="label-message" class="info"></label>
            </p>
            <p>
                <label for="math">What is 4 + 7?</label>
                <input id="math" type="text" name="math" class="small" required />
                <label for="math" id="label-math" class="info"></label>
            </p>
            <p class="submit">
                <button class="button modal_close">Cancel</button>
                <button class="button submit" type="submit">Send</button>
            </p>
            <p class="unjabi"></p>
        </fieldset>
        </form>
    </div>
</div>



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
            ['Herman Melville, Albuquerque', 35.1297672,-106.6171098]
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
            this.setZoom(14);
            google.maps.event.removeListener(boundsListener);
        });

    }
</script>




<script src="/wp-content/plugins/ldd-directory-lite/public/js/jquery.leanModal.min.js"></script>
<script src="/wp-content/plugins/ldd-directory-lite/public/js/jquery.form.min.js"></script>
<script>
    jQuery(document).ready(function() {
        var options = {
            target:         'p.unjabi',   // target element(s) to be updated with server response
            beforeSubmit:   showRequest,  // pre-submit callback
            success:        showResponse,  // post-submit callback
            //dataType:       'json',

        };


        function showRequest(formData, jqForm, options)
        {
            jQuery('label.info').fadeOut();
            jQuery('button.submit').text('Sending...');
            return true;
        }


        function showResponse(responseText, statusText, xhr, $form)
        {
            var $response = JSON && JSON.parse(responseText) || jQuery.parseJSON(responseText);


            if ( false == $response.success )
            {
                for ( var key in $response.errors )
                {
                    var field = jQuery( '#label-' + key );
                    field.text( $response.errors[key] );
                    field.addClass( 'error' );
                    field.fadeIn();
                }
                jQuery('button.submit').text('Send');
                jQuery('button.submit').blur();
                return false;
            }
            else if ( true == $response.success )
            {
                jQuery("#lean_overlay").fadeOut(200);
                jQuery("#panels").css({"display":"none"});
            }

        }

        jQuery('form.lddlite').ajaxForm(options);
    });




    jQuery(function() {
        jQuery('a[rel*=leanModal]').leanModal({ top : {{top}}, closeButton: ".modal_close" });
        jQuery('label.info').hide();
    });

</script>
<style>
    #lean_overlay {
        position: fixed;
        z-index: 10000;
        top: 0px;
        left: 0px;
        height:100%;
        width:100%;
        background: #000;
        display: none;
    }

    #panels {
        width: 600px;
        display:none;
        background: #fff;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        -webkit-box-shadow: 0 0 4px rgba(0,0,0,0.7);
        -moz-box-shadow: 0 0px 4px rgba(0,0,0,0.7);
        box-shadow: 0px 0px 8px rgba(0,0,0,0.7);
    }

    form.lddlite p.final {
        background:none;
        border:none;
        text-align: center;
    }

    form.lddlite fieldset {
        margin: 0;
        padding: 0;
        border: 0;
    }

    form.lddlite legend {
        text-align: center;
        background: #dbe1e8 url('/wp-content/plugins/ldd-directory-lite/public/icons/bg.png') repeat;
        color: #fff;
        font-size: 2em;
        font-weight: 700;
        width: 100%;
        padding: 5px 0 5px 10px;
        margin: 2px 0 .3em;
        text-shadow: 0 0 1px rgba( 0, 0, 0, 0.2 );
    }

    form.lddlite p {
        float: left;
        clear: both;
        margin: .1em 0 .1em 62px;
        background-color: #f1f3f5;
        width: 480px;
        padding: 10px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
    }

    form.lddlite p label{
        width: 140px;
        float: left;
        text-align: right;
        margin-right: 15px;
        line-height: 26px;
        color: #333;
        font-weight: 700;
    }
    form.lddlite label.info {
        display: inline-block;
        float: none;
        clear: both;
        margin: 0 0 0 160px;
        padding-top: .1em;
        text-align: left;
        color: #a5a5a5;
        font-weight: 500;
        width: 100%;
    }
    form.lddlite label.error {
        color: #D00;
        font-size: .9em;
    }
    form.lddlite input:not([type=checkbox]),
    form.lddlite textarea,
    form.lddlite select{
        background: #ffffff;
        border: 2px solid #ddd;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        outline: none;
        padding: .5em;
        font-size: 1.2em;
        width: 305px;
        float:left;
    }
    form.lddlite input.small {
        width: 105px;
    }
    form.lddlite input:not([type=radio]).error,
    form.lddlite textarea.error,
    form.lddlite select.error {

        border-color: #D00;
    }
    form.lddlite textarea {
        min-height: 120px;
    }
    form.lddlite input:focus,
    form.lddlite textarea:focus {
        -moz-box-shadow:0px 0px 1px #aaa;
        -webkit-box-shadow:0px 0px 1px #aaa;
        box-shadow:0px 0px 1px #aaa;
        background-color:#FFFEEF;
    }

</style>

