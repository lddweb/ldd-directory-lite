
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
                {if="$public"}<a class="navbar-brand" href="{$submit_link}">Submit Listing <i class="fa fa-cogs fa-sm"></i></a>{/if}
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <form action="{$form_action}" class="navbar-form navbar-right" role="search">
                    <input type="hidden" name="action" value="search_directory">
                    <input type="hidden" name="search-form-nonce" value="{$nonce}">
                    <div class="form-search search-only">
                        <i class="search-icon fa fa-search"></i>
                        <input type="text" id="directory-search" class="form-control search-query">
                    </div>
                </form>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <script>
        jQuery(document).ready(function($) {
            $('#directory-search').searchbox({
                url: '{$ajaxurl}',
                dom_id: '#search-directory-results',
                delay: 250,
                loading_css: '#search-loading'
            });
        });
    </script>