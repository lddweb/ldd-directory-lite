<section class="directory-lite directory-home">

    <header class="directory-header">
        {$header}
    </header>

    <div id="search-loading">{$loading}</div>
    <div id="search-directory-results"></div>

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

</section>
