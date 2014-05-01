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


    <form id="submit-listing" name="submit-listing" action="{{action}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="__T__action" value="submit_form" />
        {{nonce}}

        <div id="submit-items"></div>

        <div class="submit-form-wrap">
            <ul id="submit-panels">
                <li>
                    <fieldset>
                        <legend>General Information</legend>
                        <div class="panel">
                            <label for="name">
                                <span>Name:</span>
                                <input id="name" name="ld_s_name" placeholder="Please enter your full name" type="text" value="Mark's Hot Taco's" tabindex="1" required autofocus>
                                {{errors.name}}
                            </label>
                            <label for="category">
                                <span>Listing Category:</span>
                                {{category_dropdown}}
                                {{errors.category}}
                            </label>
                            <label for="description">
                                <span>Description:</span>
                                <textarea id="description" name="ld_s_description" placeholder="Enter a description for your business" tabindex="3" required>{{data.description}}</textarea>
                                {{errors.description}}
                            </label>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="panel">
                            <label for="logo">
                                <span>Logo</span>
                                <input id="logo" name="ld_s_logo" type="file" tabindex="4">
                                {{errors.logo}}
                            </label>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-11-24">
                                <label for="phone">
                                    <span>Contact Email</span>
                                    <input id="contact_email" name="ld_s_contact_email" type="text" value="{{data.contact_email}}" tabindex="5" required>
                                    {{errors.phone}}
                                </label>
                            </div><div class="pure-u-2-24"></div>
                            <div class="pure-u-11-24">
                                <label for="fax">
                                    <span>Contact Phone</span>
                                    <input id="contact_phone" name="ld_s_contact_phone" type="text" value="{{data.contact_phone}}" tabindex="6" required>
                                    {{errors.fax}}
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </li>
                <li>
                    <fieldset>
                        <legend>Geographical Info</legend>
                        <div class="panel">
                            <label for="country">
                                <span>Country</span>
                                {{country_dropdown}}
                                {{errors.country}}
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-24-24">
                                <label for="street">
                                    <span>Street</span>
                                    <input id="street" name="ld_s_address_one" type="text" tabindex="8" value="450 Michelle Cir" required>
                                    {{errors.street}}
                                </label>
                            </div>
                            <div class="pure-u-7-24">
                                <label for="city">
                                    <span>City / Town:</span>
                                    <input id="city" name="ld_s_city" type="text" tabindex="9" value="Bernalillo" required>
                                    {{errors.city}}
                                </label>
                            </div><div class="pure-u-1-24"></div>
                            <div class="pure-u-8-24">
                                <label for="subdivision">
                                    <span>State:</span>
                                    <select id="subdivision" name="ld_s_subdivision" tabindex="10" required>
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
                                        <option value="NM" selected>New Mexico</option>
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
                                    {{errors.subdivision}}
                                </label>
                            </div><div class="pure-u-1-24"></div>
                            <div class="pure-u-7-24">
                                <label for="post_code">
                                    <span>Zip/Postal:</span>
                                    <input id="post_code" name="ld_s_post_code" type="text" tabindex="11" value="87004" required>
                                    {{errors.post_code}}
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
                                <input id="url" name="ld_s_url[website]" type="text" tabindex="12">
                            </label>
                            <label for="facebook">
                                <span>Facebook Page</span>
                                <input id="facebook" name="ld_s_url[facebook]" type="text" tabindex="13">
                            </label>
                            <label for="twitter">
                                <span>Twitter Handle</span>
                                <input id="twitter" name="ld_s_url[twitter]" type="text" tabindex="14">
                            </label>
                            <label for="linkedin">
                                <span>Linked In Profile</span>
                                <input id="linkedin" name="ld_s_url[linkedin]" type="text" tabindex="15">
                            </label>
                        </div>
                    </fieldset>
                </li>
                <li>
                    <fieldset>
                        <legend>Account Information</legend>

                        <div class="panel pure-g-r">
                            <div class="pure-u-11-24">
                                <label for="username">
                                    <span>Username:</span>
                                    <input id="username" name="ld_s_username" placeholder="Choose a username" type="text" tabindex="16" value="hot-taco" required>
                                    {{errors.username}}
                                </label>
                            </div><div class="pure-u-2-24"></div>
                            <div class="pure-u-11-24">
                                <label for="email">
                                    <span>Your Email:</span>
                                    <input id="email" name="ld_s_email" placeholder="Please enter your email address" type="email" tabindex="17" value="mark@watero.us" required>
                                    {{errors.email}}
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </li>
            </ul>
        </div>

        <div class="submit-form-wrap submit-confirm" style="display: none;">
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



    jQuery(function () {

        jQuery("#submit-panels").responsiveSlides({
            auto:           false,
            pager:          true,
            nav:            true,
            navContainer:   '#submit-items',
            speed:          300,
            namespace:      'ldd-submit-listing',
        });

    });


    jQuery(document).ready(function($) {

        $('span.submit-error').prev().addClass('submit-error');
        console.log( $('li').has('span.submit-error') );
        $('li').has('span.submit-error').each(function( index ) {
            var tab_id = $(this).attr('id');
            var tab_number = parseInt( tab_id.split('_s').pop() ) + 1;
            var tab_class = tab_id.substring( 0, tab_id.length - 1 ) + tab_number;
            $('.'+tab_class).addClass('submit-error');
        });

        $('.submit-error').focus(function(){
            $(this).removeClass('submit-error');
            $(this).next('span').animate({height: 0, opacity: 0}, 'slow', function() {
                $(this).remove();
            });
        });

        $('a.submit-error').click(function() {
            $(this).removeClass('submit-error');
        });

            $("#submit-items li").hasClass('submit-error');

        var first_tab = jQuery("#ldd-submit-listing1_s0");
        var last_tab  = jQuery("#ldd-submit-listing1_s3");
        var prev_button = jQuery(".ldd-submit-listing_nav.prev");
        var next_button = jQuery(".ldd-submit-listing_nav.next");

        prev_button.hide();
        jQuery('.ldd-submit-listing_nav').add('#submit-items a').click(function() {
            var the_top = jQuery('section.directory-content').offset().top - 40;
            jQuery('html, body').animate({
                scrollTop: the_top
            }, 500);
            if ( first_tab.hasClass('ldd-submit-listing1_on') ) {
                prev_button.hide();
                next_button.show();
            } else if ( last_tab.hasClass('ldd-submit-listing1_on') ) {
                $('.submit-confirm:hidden').slideDown();
                prev_button.show();
                next_button.hide();
            } else {
                prev_button.show();
                next_button.show();
            }
        });

        if ( $("#submit-items li").hasClass('submit-error') ) {
            $('.submit-confirm:hidden').show();
        }

        var submit_button = jQuery('#submit-form-submit');

        submit_button.click(function () {
            jQuery(this).val('Submitting');
        });

    });
</script>