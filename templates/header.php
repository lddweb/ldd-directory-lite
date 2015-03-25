<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-directory">
                <span class="sr-only"><?php _e('Toggle navigation', 'ldd-directory-lite'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div id="navbar-directory" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if (ldl()->get_option('directory_submit_page')): ?><li><a href="<?php echo ldl_get_submit_link(); ?>"><?php _e('Submit Listing', 'ldd-directory-lite'); ?></a></li><?php endif; ?>
                <?php if (ldl()->get_option('directory_manage_page')): ?><li><a href="<?php echo ldl_get_manage_link(); ?>"><?php _e('Manage Listings', 'ldd-directory-lite'); ?></a></li><?php endif; ?>
            </ul>
	        <form role="search" method="get" action="<?php echo site_url(); ?>" class="navbar-form navbar-right">
                <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
	            <div class="form-group">
		            <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search', 'ldd-directory-lite'); ?>">
	            </div>
	            <button type="submit" class="btn btn-primary"><?php _e('Search', 'ldd-directory-lite'); ?></button>
            </form>
        </div>
    </div>
</nav>
