<section class="directory-lite directory-category">

    <header class="directory-header">
        {$header}

        <div class="row">
            <div class="col-md-12">
                <ol class="l-breadcrumb">
                    <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
                    <li class="active"><span>{$category_title}</span></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 view-types" style="text-align: right">
                <div class="btn-group">
                    <a href="{$list_link}" class="btn btn-default"><i class="fa fa-list"></i></a>
                    <a href="{$grid_link}" class="btn btn-default"><i class="fa fa-th"></i></a>
                </div>
            </div>
        </div>
    </header>


    <div id="search-loading">{$loading}</div>
    <div id="search-directory-results"></div>

    <section class="directory-content">
        {$listings}
    </section>

</section>

