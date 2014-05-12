<section class="business-directory directory-home cf">

    <header class="directory-header">
        {$header}
    </header>

    <section class="directory-content">

        <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em; display:none;">
            <li><a href="#"><i class="glyphicon glyphicon-home"></i> Home</a></li>
            <li class="active"><span>You Are Here</span></li>
        </ol>

        <div class="row">
            {$featured}
        </div>

        <div class="panel panel-default">
            <div class="panel-heading panel-primary">Categories</div>
            <div class="list-group">
                {$categories}
            </div>
        </div>

        <div class="row" style="display: none;">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-primary">New Listings</div>
                    <div class="list-group">
                        <a href="#" class="list-group-item">Arts & Entertainment</a>
                        <a href="#" class="list-group-item">Computers & Electronics</a>
                        <a href="#" class="list-group-item">Food & Dining</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-primary">Featured Listings</div>
                    <div class="list-group">
                        <a href="#" class="list-group-item">Arts & Entertainment</a>
                        <a href="#" class="list-group-item">Food & Dining</a>
                        <a href="#" class="list-group-item">General</a>
                        <a href="#" class="list-group-item">Home & Garden</a>
                    </div>
                </div>
            </div>
        </div>




        </section>


        {$featured_listings_open}
        {$featured_listings}
        {$featured_listings_close}



</section>



<script>
    // This is all part of the mobile menu... that we may not even use.
    var lite_breakpoint = 640;
    var shrunk = false;

    jQuery(document).ready(function() {
        if ( jQuery(window).width() < lite_breakpoint  && jQuery(".lite-nav.below-header .current").length == 0 ) {
            jQuery('.lite-nav.below-header li:first-child').addClass('current');
        }
    });

    jQuery(window).resize(function() {
        if ( jQuery(window).width() < lite_breakpoint  && jQuery(".lite-nav.below-header .current").length == 0 ) {
            shrunk = true;
            jQuery('.lite-nav.below-header li:first-child').addClass('current');
        }
        if ( jQuery(window).width() > lite_breakpoint && shrunk ) {
            shrunk = false;
            jQuery('.lite-nav.below-header li').removeClass('current');
        }
    });
</script>
