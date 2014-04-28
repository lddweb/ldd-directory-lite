<section class="business-directory  cf">

    <nav class="lite-nav above-header center cf">
        <ul><li><a href="{{url}}">Directory Home</a></li></ul>
    </nav>

    <header class="global-header submit-listing">
        <form class="directory-search cf">
            <input type="text" placeholder="Search the directory..." required />
            <button type="submit">Search</button>
        </form>
    </header>


    <section class="directory-content">




    <form id="submit-listing" name="submit-listing" action="" method="post">
        <input type="hidden" name="action" value="submit_form" />
        <input type="hidden" name="nonce" value="{{nonce}}" />
        <input type="hidden" name="business_id" value="{{id}}" />

        <div id="navigation"></div>

        <div class="submit-form-wrap">
            <ul id="submit-panels">
                <li>
                    <fieldset>
                        <legend>General Information</legend>
                        <div class="panel">
                            <label for="name">
                                <span>Name:</span>
                                <input id="name" name="name" placeholder="Please enter your full name" type="text" tabindex="1" required autofocus>
                            </label>
                            <label for="description">
                                <span>Description:</span>
                                <textarea id="description" name="description" placeholder="Enter a description for your business" tabindex="2" required></textarea>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-11-24">
                                <label for="username">
                                    <span>Username:</span>
                                    <input id="username" placeholder="Choose a username" type="text" tabindex="3" required>
                                </label>
                            </div><div class="pure-u-2-24"></div>
                            <div class="pure-u-11-24">
                                <label for="email">
                                    <span>Email:</span>
                                    <input id="email" name="email" placeholder="Please enter your email address" type="email" tabindex="4" required>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-11-24">
                                <label for="phone">
                                    <span>Contact Phone</span>
                                    <input id="phone" name="phone" type="text" tabindex="12">
                                </label>
                            </div><div class="pure-u-2-24"></div>
                            <div class="pure-u-11-24">
                                <label for="fax">
                                    <span>Contact Fax</span>
                                    <input id="fax" name="fax" type="text" tabindex="13">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </li>
                <li>
                    <fieldset>
                        <legend>Business Location</legend>
                        <div class="panel">
                            <label for="country">
                                <span>Country</span>
                                {{country_dropdown}}
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-24-24">
                                <label for="street">
                                    <span>Street</span>
                                    <input id="street" name="street" type="text" tabindex="7" required>
                                </label>
                            </div>
                            <div class="pure-u-7-24">
                                <label for="city">
                                    <span>City / Town:</span>
                                    <input id="city" name="city" type="text" tabindex="8" required>
                                </label>
                            </div><div class="pure-u-1-24"></div>
                            <div class="pure-u-8-24">
                                <label for="subdivision">
                                    <span>State:</span>
                                    <select id="subdivision" name="subdivision" tabindex="9" required>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">District Of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select>
                                </label>
                            </div><div class="pure-u-1-24"></div>
                            <div class="pure-u-7-24">
                                <label for="zip">
                                    <span>Zip/Postal:</span>
                                    <input id="zip" name="zip" type="text" tabindex="10" required>
                                </label>
                            </div>
                        </div>
                        <div class="show-ajax"></div>
                    </fieldset>
                </li>
                <li>
                    <fieldset>
                        <legend>Web & Social</legend>
                        <div class="panel">
                            <label for="url">
                                <span>Website</span>
                                <input id="url" name="url" type="text" tabindex="14">
                            </label>
                            <label for="facebook">
                                <span>Facebook Page</span>
                                <input id="facebook" name="facebook" type="text" tabindex="15">
                            </label>
                            <label for="twitter">
                                <span>Twitter Handle</span>
                                <input id="twitter" name="twitter" type="text" tabindex="16">
                            </label>
                            <label for="linkedin">
                                <span>Linked In Profile</span>
                                <input id="linkedin" name="linkedin" type="text" tabindex="17">
                            </label>
                        </div>
                    </fieldset>
                </li>
                <li>
                    <fieldset>
                        <legend>Business Logo</legend>
                        <div class="panel">
                            <label for="logo">
                                <span>Logo Image</span>
                                <input id="logo" name="logo" type="file">
                            </label>
                        </div>
                    </fieldset>
                </li>
            </ul>
        </div>

        <div class="submit-form-wrap">
            <fieldset style="clear: both;">
                <legend>Confirm</legend>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta.</p>
                <button type="submit" id="submit-form-submit">Submit Listing</button>
            </fieldset>
        </div>

    </form>


    </section>


</section>


<script>
    jQuery(document).ready(function() {
        var options = {
            target:         '.show-ajax',
            beforeSubmit:   showRequest,
            success:        showResponse,
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

            }

        }

        jQuery('form#submit-listing').ajaxForm(options);
    });



    jQuery(function () {

        jQuery("#submit-panels").responsiveSlides({
            auto:           false,
            pager:          true,
            nav:            true,
            navContainer:   '#navigation',
            speed:          300,
            namespace:      'ldd-submit-listing',
        });

    });


    jQuery(document).ready(function() {
        var first_tab = jQuery("#ldd-submit-listing1_s0");
        var last_tab  = jQuery("#ldd-submit-listing1_s3");
        var prev_button = jQuery(".ldd-submit-listing_nav.prev");
        var next_button = jQuery(".ldd-submit-listing_nav.next");

        prev_button.hide();
        jQuery('.ldd-submit-listing_nav').add('#navigation a').click(function() {
            var the_top = jQuery('section.directory-content').offset().top - 40;
            jQuery('html, body').animate({
                scrollTop: the_top
            }, 500);
            if ( first_tab.hasClass('ldd-submit-listing1_on') ) {
                prev_button.hide();
                next_button.show();
            } else if ( last_tab.hasClass('ldd-submit-listing1_on') ) {
                prev_button.show();
                next_button.hide();
            } else {
                prev_button.show();
                next_button.show();
            }
        });

        var submit_button = jQuery('#submit-form-submit');

        submit_button.click(function () {
            jQuery(this).val('Submitting');
        });

    });
</script>