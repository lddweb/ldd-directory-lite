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
	        <form action="<?php echo ldl_get_home_url(); ?>" class="navbar-form navbar-right" role="search">
                <input type="hidden" name="show" value="search">
	            <div class="form-group">
		            <input type="text" class="form-control" placeholder="Search">
	            </div>
	            <button type="submit" class="btn btn-warning">Submit</button>
            </form>
        </div>
    </div>
</nav>


<script>
    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>"
</script>