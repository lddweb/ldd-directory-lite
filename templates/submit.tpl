<section class="directory-lite directory-submit">

    <header class="directory-header">
        {$header}

        <div class="row">
            <div class="col-md-12">
                <ol class="l-breadcrumb" style="margin-bottom: 1em;">
                    <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
                    <li class="active"><span>Submit a Listing</span></li>
                </ol>
            </div>
        </div>
    </header>


    <div id="search-loading">{$loading}</div>
    <div id="search-directory-results"></div>

    <section class="directory-content">

        {if="!empty($errors)"}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Oops!</strong> Panels marked in red have errors that need to be fixed before submitting.
            </div>
        {/if}

        {if="$success"}
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Thank you for your submission!</h4>
                    <p>Your listing is awaiting approval. Please allow time for us to review the accuracy of your content before it appears publicly in our directory.</p>
                    <p><a class="btn btn-success" href="{$home}">View Other Listings</a></p>
                </div>

        {/if}

        <form id="submit-listing" name="submit-listing" action="{$form_action}" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="__T__action" value="submit_form">
            {$nonce}

            <div id="submit-items"></div>

            <div class="submit-form-wrap">
                <ul id="submit-panels">
                    <li>{$panel_general}</li>
                    <li>{$panel_geography}</li>
                    <li>{$panel_urls}</li>
                    {if="isset($panel_account)"}<li>{$panel_account}</li>{/if}
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

<style>
    .directory-lite #submit-items ul li.ldd-submit-listing_here a.submit-error,
    .directory-lite #submit-items ul li a.submit-error:hover {
        background-color: #da4453;
        color: #fff;
    }
</style>