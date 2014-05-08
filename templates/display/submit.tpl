<section class="business-directory  cf">

    <nav class="lite-nav above-header center cf">
        <ul><li><a href="{$url}">Directory Home</a></li></ul>
    </nav>

    <header class="global-header submit-listing">
        <form class="directory-search cf">
            <input type="text" placeholder="Search the directory..." required />
            <button type="submit">Search</button>
        </form>
    </header>


    <section class="directory-content">


    <form id="submit-listing" name="submit-listing" action="{$form_action}" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="__T__action" value="submit_form" />
        {$nonce}

        <div id="submit-items"></div>

        <div class="submit-form-wrap">
            <ul id="submit-panels">
                <li>
                    <fieldset>
                        <legend>General Information</legend>
                        <div class="panel">
                            <label for="name">
                                <span>Name:</span>
                                <input id="name" name="ld_s_name" placeholder="Please enter your full name" type="text" {if="!empty($data.name)"}value="{$data.name}" {/if}tabindex="1" required autofocus>
                                {if="!empty($errors.name)"}{$errors.name}{/if}
                            </label>
                            <label for="category">
                                <span>Listing Category:</span>
                                {$category_dropdown}
                                {if="!empty($errors.category)"}{$errors.category}{/if}
                            </label>
                            <label for="description">
                                <span>Description:</span>
                                <textarea id="description" name="ld_s_description" placeholder="Enter a description for your business" tabindex="3" required>{if="!empty($data.description)"}{$data.description}{/if}</textarea>
                                {if="!empty($errors.description)"}{$errors.description}{/if}
                            </label>
                        </div>
                    </fieldset>

                    <fieldset id="submit-pot">
                        <div class="panel">
                            <label for="summary">
                                <span>summary</span>
                                <input id="summary" name="ld_s_summary" placeholder="Add a summary for your listing" type="text" {if="!empty($data.summary)"}value="{$data.summary}" {/if} tabindex="4">
                                {if="!empty($errors.summary)"}{$errors.summary}{/if}
                            </label>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="panel">
                            <label for="logo">
                                <span>Logo</span>
                                <input id="logo" name="ld_s_logo" type="file" tabindex="5">
                                {if="!empty($errors.logo)"}{$errors.logo}{/if}
                            </label>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-11-24">
                                <label for="phone">
                                    <span>Contact Email</span>
                                    <input id="contact_email" name="ld_s_contact_email" type="text" {if="!empty($data.contact_email)"}value="{$data.contact_email}" {/if}tabindex="6" required>
                                    {if="!empty($errors.contact_email)"}{$errors.contact_email}{/if}
                                </label>
                            </div><div class="pure-u-2-24"></div>
                            <div class="pure-u-11-24">
                                <label for="fax">
                                    <span>Contact Phone</span>
                                    <input id="contact_phone" name="ld_s_contact_phone" type="text" {if="!empty($data.contact_phone)"}value="{$data.contact_phone}" {/if}tabindex="7" required>
                                    {if="!empty($errors.contact_phone)"}{$errors.contact_phone}{/if}
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
                                {$country_dropdown}
                                {if="!empty($errors.country)"}{$errors.country}{/if}
                            </label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="panel pure-g-r">
                            <div class="pure-u-24-24">
                                <label for="street">
                                    <span>Street</span>
                                    <input id="street" name="ld_s_address_one" type="text" {if="!empty($data.street)"}value="{$data.street}" {/if}tabindex="8" required>
                                    {if="!empty($errors.street)"}{$errors.street}{/if}
                                </label>
                            </div>
                            <div class="pure-u-7-24">
                                <label for="city">
                                    <span>City / Town:</span>
                                    <input id="city" name="ld_s_city" type="text" {if="!empty($data.city)"}value="{$data.city}" {/if}tabindex="9" required>
                                    {if="!empty($errors.city)"}{$errors.city}{/if}
                                </label>
                            </div><div class="pure-u-1-24"></div>
                            <div class="pure-u-8-24">
                                <label for="subdivision">
                                    <span>State:</span>
                                    {$subdivision_dropdown}
                                    {if="!empty($errors.subdivision)"}{$errors.subdivision}{/if}
                                </label>
                            </div><div class="pure-u-1-24"></div>
                            <div class="pure-u-7-24">
                                <label for="post_code">
                                    <span>Zip/Postal:</span>
                                    <input id="post_code" name="ld_s_post_code" type="text" {if="!empty($data.post_code)"}value="{$data.post_code}" {/if}tabindex="11" required>
                                    {if="!empty($errors.post_code)"}{$errors.post_code}{/if}
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
                                    <input id="username" name="ld_s_username" placeholder="Choose a username" type="text" {if="!empty($data.username)"}value="{$data.username}" {/if}tabindex="16" required>
                                    {if="!empty($errors.username)"}{$errors.username}{/if}
                                </label>
                            </div><div class="pure-u-2-24"></div>
                            <div class="pure-u-11-24">
                                <label for="email">
                                    <span>Your Email:</span>
                                    <input id="email" name="ld_s_email" placeholder="Please enter your email address" type="email" {if="!empty($data.email)"}value="{$data.email}" {/if}tabindex="17" required>
                                    {if="!empty($errors.email)"}{$errors.email}{/if}
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
                <p>Please verify all information on this form before submitting. Your listing will not appear immediately as we review all submissions for accuracy and content, to ensure that listings fall within our terms of service.</p>
                <button type="submit" id="submit-form-submit" class="submit">Submit Listing</button>
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