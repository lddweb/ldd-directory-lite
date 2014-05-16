<section class="business-directory directory-submit cf">

    <header class="directory-header">
        {$header}

        <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em;">
            <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active"><span>Submit a Listing</span></li>
        </ol>
    </header>

    <div id="search-directory-results"></div>

    <section class="directory-content">


    <style>
        .submit-form-wrap .row {
            margin-bottom: 1em;
        }
        .submit-form-wrap .row.bump {
            margin-bottom: 2.5em;
        }
    </style>

    <form id="submit-listing" name="submit-listing" action="{$form_action}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="__T__action" value="submit_form">
        {$nonce}

        <div id="submit-items"></div>

        <div class="submit-form-wrap">
            <ul id="submit-panels">
                <li>

                    <fieldset>
                        <legend>General Information</legend>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="">Title</label>
                                    <input id="title" class="form-control" name="ld_s_title" type="text" {if="!empty($data.title)"}value="{$data.title}" {/if} tabindex="1" required>
                                    {if="!empty($errors.title)"}{$errors.title}{/if}
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="">Category</label>
                                    {$category_dropdown}
                                    {if="!empty($errors.category)"}{$errors.category}{/if}
                                </div>
                            </div>
                        </div>
                        <div class="row bump">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Description</label>
                                    <textarea id="description" class="form-control" name="ld_s_description" tabindex="3" required>{if="!empty($data.description)"}{$data.description}{/if}</textarea>
                                    {if="!empty($errors.description)"}{$errors.description}{/if}
                                    <span class="description">The following HTML tags and attributes are allowed in your description:<br> {$allowed_tags} </span>
                                </div>
                            </div>
                        </div>
                        <div class="row hpt">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Summary</label>
                                    <input id="summary" class="form-control" name="ld_s_summary" placeholder="Add a summary for your listing" type="text" {if="!empty($data.summary)"}value="{$data.summary}" {/if} tabindex="4">
                                    {if="!empty($errors.summary)"}{$errors.summary}{/if}
                                </div>
                            </div>
                        </div>
                        <div class="row bump">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Logo</label>
                                    <input id="logo" class="form-control" name="ld_s_logo" type="file" tabindex="5">
                                    {if="!empty($errors.logo)"}{$errors.logo}{/if}
                                    <span class="description">Maximum file size is 2mb. This will be displayed on your profile page, and search results.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="">Contact Email</label>
                                    <input id="contact_email" class="form-control" name="ld_s_contact_email" type="text" {if="!empty($data.contact_email)"}value="{$data.contact_email}" {/if}tabindex="6" required>
                                    {if="!empty($errors.contact_email)"}{$errors.contact_email}{/if}
                                    <span class="description">This is not displayed publicly, however if you choose to provide an email address, visitors will be able to contact you via an online form</span>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="">Contact Phone</label>
                                    <input id="contact_phone" class="form-control" name="ld_s_contact_phone" type="text" {if="!empty($data.contact_phone)"}value="{$data.contact_phone}" {/if}tabindex="7" required>
                                    {if="!empty($errors.contact_phone)"}{$errors.contact_phone}{/if}
                                    <span class="description">This will be displayed publicly on your listings profile page</span>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </li>


                <li>
                    <fieldset>
                        <legend>Geographical Info</legend>

                        {if="!$use_locale"}<div class="row bump">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Country</label>
                                    {$country_dropdown}
                                    {if="!empty($errors.country)"}{$errors.country}{/if}
                                </div>
                            </div>
                        </div>{/if}
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Street</label>
                                    <input id="address_one" class="form-control" name="ld_s_address_one" type="text" {if="!empty($data.address_one)"}value="{$data.address_one}" {/if}tabindex="8" required>
                                    {if="!empty($errors.address_one)"}{$errors.address_one}{/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label class="control-label" for="city">City/Town</label>
                                    <input id="city" class="form-control" name="ld_s_city" type="text" {if="!empty($data.city)"}value="{$data.city}" {/if}tabindex="9" required>
                                    {if="!empty($errors.city)"}{$errors.city}{/if}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label id="subdivision_label" class="control-label" for="subdivision">State</label>
                                    <span id="subdivision_control">{$subdivision_dropdown}</span>
                                    {if="!empty($errors.subdivision)"}{$errors.subdivision}{/if}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label id="post_code_label" class="control-label" for="post_code">Zip/Postal Code</label>
                                    <input id="post_code" class="form-control" name="ld_s_post_code" type="text" {if="!empty($data.post_code)"}value="{$data.post_code}" {/if}tabindex="11" required>
                                    {if="!empty($errors.post_code)"}{$errors.post_code}{/if}
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </li>


                <li>
                    <fieldset>
                        <legend>Web & Social</legend>

                        <p>None of the following information is required, however it can play a very important role in distinguishing your listing from others in our directory. The best way to ensure the validity of these URLs is to copy them directly from the location bar in your browser.</p>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Website URL</label>
                                    <input id="url" class="form-control" name="ld_s_url[website]" type="text" {if="!empty($url.website)"}value="{$url.website}" {/if}tabindex="12">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Facebook Url</label>
                                    <input id="facebook" class="form-control" name="ld_s_url[facebook]" type="text" {if="!empty($url.facebook)"}value="{$url.facebook}" {/if}tabindex="13">
                                    <span class="description">Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Twitter</label>
                                    <input id="twitter" class="form-control" name="ld_s_url[twitter]" type="text" {if="!empty($url.twitter)"}value="{$url.twitter}" {/if}tabindex="14">
                                    <span class="description">This will always be 'https://twitter.com/<strong>username</strong>'</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="">Linkedin</label>
                                    <input id="linkedin" class="form-control" name="ld_s_url[linkedin]" type="text" {if="!empty($url.linkedin)"}value="{$url.linkedin}" {/if}tabindex="15">
                                    <span class="description">Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a></span>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </li>


                <li>
                    <fieldset>
                        <legend>Account Information</legend>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="">Username</label>
                                    <input id="username" class="form-control" name="ld_s_username" placeholder="Choose a username" type="text" {if="!empty($data.username)"}value="{$data.username}" {/if}tabindex="16" required>
                                    {if="!empty($errors.username)"}{$errors.username}{/if}
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label" for="">Your Email</label>
                                    <input id="email" class="form-control" name="ld_s_email" placeholder="Please enter your email address" type="email" {if="!empty($data.email)"}value="{$data.email}" {/if}tabindex="17" required>
                                    {if="!empty($errors.email)"}{$errors.email}{/if}
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </li>
            </ul>
        </div>

        <div class="submit-form-wrap submit-confirm" style="display: none;">
            <fieldset>
                <legend>Confirm</legend>
                <p>Please verify all information on this form before submitting. Your listing will not appear immediately as we review all submissions for accuracy and content, to ensure that listings fall within our terms of service.</p>
                <p>By submitting, you agree your listing abides by <a href="#" data-toggle="modal" data-target="#tos-modal">our terms of service</a>.</p>
                <button type="submit" id="submit-form-submit" class="btn btn-success"><i class="fa fa-cog"></i> Submit for Review</button>
            </fieldset>
        </div>

    </form>


    </section>


</section>


<div id="tos-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="tos-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Contact</h4>
            </div>
            <div class="modal-body">
                <p>Feel free to contact us for any issues you might have with our products.</p>
                <div class="row">
                    <div class="col-xs-6">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-xs-6">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label>Message</label>
                        <textarea class="form-control" rows="3">Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Send</button>
            </div>
        </div>
    </div>
</div>


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

        var countryDrop = $("#country")
        var subControl = $("#subdivision_control")

        countryDrop.change(function() {
            var newSubdivision = this.value
            var request = $.ajax({
                url: "{$ajaxurl}",
                type: "POST",
                data: {
                    action: "dropdown_change",
                    subdivision: newSubdivision,
                },
            })
            request.done(function( msg ) {
                var response = $.parseJSON( msg )
                console.log( response.subdivision )
                subControl.html( response.input )
                $("#subdivision_label").text( response.sub )
                $("#post_code_label").text( response.code )
            })
        })




        function getNavItem( currentItem ) {
            var tab_number = parseInt( currentItem.split("_s").pop() ) + 1
            var tab_class = currentItem.substring( 0, currentItem.length - 1 ) + tab_number
            return tab_class;
        }

        $('.row.hpt').hide()

        $('span.submit-error').closest('div').addClass('has-error has-feedback')

        // Append "submit-error" class to tabs
        $('li').has('span.submit-error').each(function( index ) {
            var tab_id = $(this).attr("id")
            var tab_number = parseInt( tab_id.split("_s").pop() ) + 1
            var tab_class = tab_id.substring( 0, tab_id.length - 1 ) + tab_number
            $( "." + tab_class).addClass("submit-error")
        });

        $('.form-control').focus(function(){
            if ( $(this).closest('div').hasClass('has-error') ) {

                var tab_id = $(this).closest('li').attr('id')
                var activeTab = getNavItem( tab_id )

                $("a." + activeTab).removeClass('submit-error')

                $(this).next('span').remove()
                $(this).closest('div').removeClass('has-error has-feedback')
                $(this).next('span').animate({height: 0, opacity: 0}, 'slow', function() {
                    $(this).remove();
                });

            }
        });

        $('a.submit-error').click(function() {
            $(this).removeClass('submit-error');
        });


        //
        var first_tab = jQuery("#ldd-submit-listing1_s0");
        var last_tab  = jQuery("#ldd-submit-listing1_s3");
        var prev_button = jQuery(".ldd-submit-listing_nav.prev");
        var next_button = jQuery(".ldd-submit-listing_nav.next");

        prev_button.hide();
        jQuery('.ldd-submit-listing_nav').add('#submit-items a').click(function() {
/*            var the_top = jQuery('section.directory-content').offset().top - 40;
            jQuery('html, body').animate({
                scrollTop: the_top
            }, 500);*/


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

        var submitButton = jQuery('#submit-form-submit');

        submitButton.click(function () {
            jQuery('#submit-form-submit > i').addClass('fa-spin');
        });

    });
</script>