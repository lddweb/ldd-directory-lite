<nav class="navbar navbar-inverse ldd-directory-navbar" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle ldd-btn-fix" data-toggle="collapse" data-target="#navbar-directory">
                <span class="sr-only"><?php _e('Toggle navigation', 'ldd-directory-lite'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand visible-xs" href="#">MENU</a>
        </div>

        <div id="navbar-directory" class="collapse navbar-collapse">
            <div class="ldd_top_search_form col-md-12 clearfix" style="display: none;">
                <form role="search" method="get" action="<?php echo site_url(); ?>" class="ldd-sm-search-form">
                    <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
                    <div class="input-group">
                        <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search listings...', 'ldd-directory-lite'); ?>">
                        <span class="input-group-btn">
                            <button type="submit" class="btn ldd-search-btn ldd-btn-fix btn-primary"><?php _e('Search', 'ldd-directory-lite'); ?></button>
                        </span>
                    </div>
                </form>
            </div>
            <ul class="nav navbar-nav">
                	<li class="ldd-home-link"><a href="<?php echo ldl_get_directory_link(); ?>"><?php _e('Home', 'ldd-directory-lite'); ?></a></li>
                <?php if (ldl()->get_option('directory_submit_page') && ldl()->get_option('general_allow_public_submissions','yes') === 'yes'): ?>
                	<li class="ldd-submit-listings"><a href="<?php echo ldl_get_submit_link(); ?>"><?php _e('Submit Listing', 'ldd-directory-lite'); ?></a></li>
				<?php endif; ?>
                <?php if (ldl()->get_option('directory_manage_page') && ldl()->get_option('general_allow_public_submissions','yes') === 'yes'): ?>
                	<li class="ldd-manage-directory"><a href="<?php echo ldl_get_manage_link(); ?>"><?php _e('Manage Listings', 'ldd-directory-lite'); ?></a></li>
				<?php endif; ?>
                	<li class="dropdown ldd-categories-dropdown">
                    	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<?php _e('Categories', 'ldd-directory-lite'); ?>
                            <span class="caret"></span>
                        </a>
                    <ul class="dropdown-menu">
                      <?php echo ldl_get_categories_li(0); ?>
                    </ul>
                  </li>
            </ul>
            <form role="search" method="get" style="display: none;" action="<?php echo site_url(); ?>" class="navbar-form ldd_right_search_form navbar-right ldd-search-form">
                    <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
                    <div class="input-group">
                        <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search listings...', 'ldd-directory-lite'); ?>">
                        <span class="input-group-btn">
                            <button type="submit" class="btn ldd-search-btn ldd-btn-fix btn-primary"><?php _e('Search', 'ldd-directory-lite'); ?></button>
                        </span>
                    </div>
                </form>
        </div>
    </div>
</nav>