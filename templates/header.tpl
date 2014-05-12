
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
                <div class="btn-group">
                    <a href="#" class="btn btn navbar-btn" data-toggle="tooltip" data-placement="top" title="User Control Panel"><i class="fa fa-user"></i></a>
                    <a href="{$submit_link}" class="btn btn navbar-btn" data-toggle="tooltip" data-placement="top" title="Submit a Listing"><i class="fa fa-plus"></i><span>Submit Listing</span></a>
                </div>

                <form class="navbar-form navbar-right" role="search">
                    <div class="form-search search-only">
                        <i class="directory-search-icon fa fa-search"></i>
                        <input type="text" class="form-control search-query">
                    </div>
                </form>
            </div>
        </div>
    </nav>
