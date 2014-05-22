<section class="directory-lite directory-submit">

    <header class="directory-header">
        {$header}

        <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em;">
            <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active"><span>Submit a Listing</span></li>
        </ol>
    </header>

    <div id="search-directory-results"></div>

    <section class="directory-content submit-success">

        <header class="listing-header">
            <a href="" class="post-thumbnail">{{logo}}</a>
            <h2 class="listing-title">Congratulations</h2>
        </header>

        <div class="listing-content">

            <p>Your listing is awaiting review.</p>

            <ul class="submit-review">
                <li><span>Business Name:</span> {{listing.name}}</li>
                <li><span>Website:</span> <a href="{{listing.url}}">{{listing.url}}</a></li>
                <li><span>Your Username:</span> {{listing.username}}</li>
            </ul>

            <p>Please allow up to five business days for your listing to be reviewed and successfully published to our directory.</p>

        </div>


    </section>

</section>
