
    {if="$show_label==1"}
    <div class="panel panel-primary">
        <div class="panel-heading">{$directory_label}</div>
        <div class="panel-body">
            {$directory_description}
        </div>
    </div>
    {/if}

    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{$submit_link}">Submit Listing</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div id="navbar-directory" class="collapse navbar-collapse">

                <form class="navbar-form navbar-right" role="search" {if="$nosearch"}style="display: none;"{/if}>
                    <input type="hidden" name="show" value="search">
                    <div class="form-search search-only">
                        <i class="search-icon fa fa-search"></i>
                        <input id="directory-search" name="t" type="text" value="{$terms}" class="form-control search-query">
                    </div>
                </form>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <script>
        var topOffset = 10{if="$is_logged_in"} + 32{/if};

        if ( typeof ajaxurl === "undefined" )
            var ajaxurl = "{$ajaxurl}"

/*
        jQuery(document).ready(function($) {
            $("input[id=directory-search]").searchbox({
                url: ajaxurl,
                dom_id: '#search-directory-results',
                delay: 250,
                loading_css: '#search-loading'
            })
        })
*/
    </script>