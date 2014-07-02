<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-directory">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo ldl_get_submit_form_link(); ?>">Submit Listing</a>
        </div>

        <div id="navbar-directory" class="collapse navbar-collapse">
	        <form role="search" method="get" action="<?php echo ldl_get_home_url(); ?>" class="navbar-form navbar-right">
                <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
	            <div class="form-group">
		            <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="Search">
	            </div>
	            <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</nav>


<script>
    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>"
</script>