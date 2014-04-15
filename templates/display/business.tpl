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


    .display-business .listing-social {
        clear: both;
        width: 100%;
        margin: 1em 0;
        padding: 1em;
        text-align: center;
        border-top: 2px solid rgba(51, 51, 51, 0.10);
        border-bottom: 2px solid rgba(51, 51, 51, 0.10);
    }
    .display-business .listing-social img {
        margin: .1em;
    }

    .listing-description {
        clear: both;
        width: 100%;
    }
</style>

{{search}}

<div class="section-wrapper cf">

    <div class="directory-nav cf">
        <a href="{{url}}?submit=true" class="button">Categories</a>
        <a rel="leanModal" href="#panels" class="button right">Contact</a>
    </div>

    <div class="display-business">

        <div class="listing cf">
            <div class="listing-image cf" style="background: #eee; border-radius: 2px;">
                <a href="" class="featured-image"><img src="http://www.aaas.org/sites/default/files/migrate/uploads/0707green_building_0242.jpg" /></a>
            </div>

            <div class="listing-content">

                <h2 class="entry-title listing-title"><a  href="">Example Listing Business</a></h2>
                <div class="listing-meta">
                    <p class="website"><a href="">lddconsulting.com</a></p>

                    <p class="phone">(505) 455-8749</p>
                    <p class="address">2420 Midtown Pl NE,<br />Albuquerque, NM 87107</p>
                </div>

            </div>
        </div>

        <div class="listing-social">
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/24/twitter.png" width="24" height="24" alt="" /></a>
            <a href=""><img src="/wp-content/plugins/ldd-directory-lite/public/icons/24/pinterest.png" width="24" height="24" alt="" /></a>
        </div>

        <div class="listing-description">
            <p>Secondly: The ship Union, also of Nantucket, was in the year 1807 totally lost off the Azores by a similar onset, but the authentic particulars of this catastrophe I have never chanced to encounter... <a class="moretag" href="">Read More</a></p>
        </div>


    </div>

</div>


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

