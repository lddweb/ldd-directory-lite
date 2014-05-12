<section class="business-directory directory-category cf">

    <header class="directory-header">
        {$header}
    </header>

    <div class="row">
        <div class="col-xs-14 col-md-10">
            <ol class="l-breadcrumb l-breadcrumb-arrow" style="margin-bottom: 1em;">
                <li><a href="{$home}"><i class="fa fa-home"></i> Home</a></li>
                <li class="active"><span>{$category_title}</span></li>
            </ol>
        </div>

        <div class="col-xs-4 col-md-2 view-types" style="text-align: right;">
            <div class="btn-group">
                <a href="{$list_link}" class="btn btn-success"><i class="fa fa-list"></i></a>
                <a href="{$grid_link}" class="btn btn-success"><i class="fa fa-th"></i></a>
            </div>
        </div>
    </div>


    <section class="directory-content">
        {$listings}
    </section>

</section>


<style>
    h2.listing-title {
        font-size: 1.8em !important;
        margin: 5px 0 0 !important;
    }
    .type-grid h2.listing-title {
        font-size: 1.4em !important;
        margin: 0 0 15px !important;
    }

    .type-listing {
        padding: 0;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 4px;
        border: 1px solid rgba(128, 128, 128, 0.10);
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
        box-shadow: 0 1px 2px rgba(0,0,0,.2);
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .type-listing .img-rounded {
        margin: 15px auto;
    }
    .type-listing .listing-summary {
        margin: 0 0 15px;
        padding: 0;
        width: 100%;
        font-size: .9em;
    }
    .type-grid .listing-meta p {
        text-align: left;
        font-size: .8em;
        color: rgba(119, 119, 119, 0.5);
    }
    .listing-meta p .fa {
        position: absolute;
        top: 3px;
        left: 0px;
    }
    .listing-meta {
        margin-top: 15px;
    }
    .listing-meta p {
        padding-left: 20px;
        position: relative;
    }
    p.website {
         font-weight: 400;
         margin-bottom: 1em;

    p.website a {
        color: rgba(0, 128, 0, 0.90);
    }
    p.website a:hover {
        color: rgba(0, 170, 0, 0.90);
    }

</style>