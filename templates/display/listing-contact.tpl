<style>

    .listing-contact-form {
        margin-bottom: .5em;
        background: #fff;
        border: 1px solid rgba(32, 69, 108, 0.12);
        border-radius: 2px;
        padding:  1em;
    }

    .listing-contact-form form * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
    }


    .contact-navigation {
        margin: 1em 0 0;
        text-align: right;
    }
</style>



<div class="listing-contact-form" style="display: none;">

    <form  action="{$form_action}" method="post">
        <input type="hidden" name="action" value="contact_form" />
        <input type="hidden" name="nonce" value="{$nonce}" />
        <input type="hidden" name="business_id" value="{$id}" />
        <fieldset>
            <legend>Contact</legend>

            <div class="pure-g-r">
                <div class="pure-u-11-24">
                    <label for="from">
                        <span>your name:</span>
                        <input id="from" name="from" type="text" {if="!empty($data.from)"}value="{$data.from}" {/if}tabindex="1" required>
                        {if="!empty($errors.from)"}{$errors.from}{/if}
                    </label>
                </div><div class="pure-u-2-24"></div>
                <div class="pure-u-11-24">
                    <label for="email">
                        <span>your email:</span>
                        <input id="email" name="email" type="email" {if="!empty($data.email)"}value="{$data.email}" {/if}tabindex="2" required>
                        {if="!empty($errors.email)"}{$errors.email}{/if}
                    </label>
                </div>
            </div>

            <div class="pure-g-r" style="margin-bottom: 1em;">
                <div class="pure-u-17-24">
                    <label for="subject">
                        <span>subject</span>
                        <input id="subject" name="subject" type="text" class="small" tabindex="3" required>
                        {if="!empty($errors.subject)"}{$errors.subject}{/if}
                    </label>
                </div><div class="pure-u-2-24"></div>
                <div class="pure-u-5-24">
                    <label for="math">
                        <span>&#102;our &#112;&#108;us se&#118;en</span>
                        <input id="math" name="math" type="text" class="small" tabindex="4" required>
                        {if="!empty($errors.math)"}{$errors.math}{/if}
                    </label>
                </div>
            </div>

            <label for="message">
                <span>message</span>
                <textarea id="message" name="message" tabindex="5" required>{if="!empty($data.message)"}{$data.message}{/if}</textarea>
                {if="!empty($errors.message)"}{$errors.message}{/if}
            </label>


            <div class="contact-navigation clearfix">
                <a href="#" class="contact-buttons cancel">Cancel</a>
                <a href="#" class="contact-buttons">Submit</a>
            </div>

            <p class="unjabi"></p>
        </fieldset>
    </form>

</div>













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


</script>
