<section class="directory-lite directory-submit">

    <header class="directory-header">
        {$header}

        <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em;">
            <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active"><span>Submit a Listing</span></li>
        </ol>
    </header>

    <div id="search-directory-results"></div>

    <section class="directory-content">

        <form id="submit-listing" name="submit-listing" action="{$form_action}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="__T__action" value="submit_form">
            {$nonce}

            <div id="submit-items"></div>

            <div class="submit-form-wrap">
                <ul id="submit-panels">
                    <li>{$panel_general}</li>
                    <li>{$panel_geography}</li>
                    <li>{$panel_urls}</li>
                    <li>{$panel_account}</li>
                </ul>
            </div>

            <div class="submit-form-wrap submit-confirm" style="display: none;">
                <fieldset>
                    <legend>Confirm</legend>
                    <p>Please verify all information on this form before submitting. Your listing will not appear immediately as we review all submissions for accuracy and content, to ensure that listings fall within our terms of service.</p>
                    {if="$use_tos"}<p>By submitting, you agree your listing abides by <a href="#" data-toggle="modal" data-target="#tos-modal">our terms of service</a>.</p>{/if}
                    <button type="submit" id="submit-form-submit" class="btn btn-success"><i class="fa fa-cog"></i> Submit for Review</button>
                </fieldset>
            </div>
        </form>

    </section>

</section>


<script>
    var topOffset = 10{if="$is_logged_in"} + 32{/if};
</script>
