<section class="directory-lite directory-home">

    <header class="directory-header">
        <?php echo ldl_get_header(); ?>
    </header>

    <section class="directory-content">

        <!--
        <div class="row">
            {$featured}
        </div>

        <div class="row">
            {$new}
        </div>
        -->

        <div class="list-group">
            <?php echo ldl_get_parent_categories(); ?>
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
