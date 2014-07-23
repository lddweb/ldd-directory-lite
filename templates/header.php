<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-directory">
                <span class="sr-only"><?php _e('Toggle navigation', 'lddlite'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php if (ldl_get_setting('directory_submit_page')): ?><a class="navbar-brand" href="<?php echo ldl_get_submit_link(); ?>"><?php _e('Submit Listing', 'lddlite'); ?></a><?php endif; ?>
        </div>

        <div id="navbar-directory" class="collapse navbar-collapse">
	        <form role="search" method="get" action="<?php echo site_url(); ?>" class="navbar-form navbar-right">
                <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
	            <div class="form-group">
		            <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search', 'lddlite'); ?>">
	            </div>
	            <button type="submit" class="btn btn-primary"><?php _e('Search', 'lddlite'); ?></button>
            </form>
        </div>
    </div>
</nav>
