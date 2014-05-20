
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
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
                <div class="btn-group" style="float:left;">
                    <!-- <a href="#" class="btn btn navbar-btn" data-toggle="modal" data-target="#login-form-modal"><i class="fa fa-user"></i></a> -->
                    {if="$public"}<a href="{$submit_link}" class="btn btn navbar-btn" data-toggle="tooltip" data-placement="top" title="Submit a Listing"><i class="fa fa-plus"></i> Submit Listing</a>{/if}
                </div>

                <form action="{$form_action}" class="navbar-form navbar-right" role="search">
                    <input type="hidden" name="action" value="search_directory">
                    <input type="hidden" name="search-form-nonce" value="{$nonce}">

                    <div class="form-search search-only">
                        <i class="directory-search-icon fa fa-search"></i>
                        <input type="text" id="search-directory-input" class="form-control search-query">
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <script>
        jQuery(document).ready(function($) {
            $('input#search-directory-input').searchbox({
                url: '{$ajaxurl}',
                dom_id: '#search-directory-results',
                delay: 250,
                loading_css: '#search-loading'
            });
            $()
        });
    </script>