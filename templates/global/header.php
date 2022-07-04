<?php
/*
* File version: 2
*/
?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo ldl()->get_option('googlemap_api_key');?>"></script>
  
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

            <div class="navbar-toggle ldd_search des1"> 
                <i class="fa fa-search-plus show_search"></i>
                </div>
        </div>
        
        <div id="navbar-directory" class="collapse navbar-collapse">
        
            <ul class="nav navbar-nav">
            <li class="ldd-home-link"><a href="<?php echo ldl_get_directory_link(); ?>"><?php _e(ldl_get_directory_title(), 'ldd-directory-lite'); ?></a></li>
                	<li class="dropdown ldd-categories-dropdown">
                    	<a href="#" class="ldd-dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<?php _e('Categories', 'ldd-directory-lite'); ?>
                            <span class="caret"></span>
                        </a>
                    <ul class="dropdown-menu">
                      <?php echo ldl_get_categories_li(0); ?>
                    </ul>
                    
                <?php if (ldl()->get_option('directory_submit_page') && ldl()->get_option('general_allow_public_submissions','yes') === 'yes'): ?>
                	<li class="ldd-submit-listings"><a href="<?php echo ldl_get_submit_link(); ?>"><?php _e('Submit', 'ldd-directory-lite'); ?></a></li>
				<?php endif; ?>
                <?php if (ldl()->get_option('directory_manage_page') && ldl()->get_option('general_allow_public_submissions','yes') === 'yes'): ?>
                	<li class="ldd-manage-directory"><a href="<?php echo ldl_get_manage_link(); ?>"><?php _e('Manage', 'ldd-directory-lite'); ?></a></li>
				<?php endif; ?>
                  </li>
            </ul>
            <!--<form role="search" method="get" style="display: none;" action="<?php echo site_url(); ?>" class="navbar-form ldd_right_search_form navbar-right ldd-search-form des1">
                    <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
                    <div class="input-group">
                        <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search listings...', 'ldd-directory-lite'); ?>">
                        <span class="input-group-btn">
                            <button type="submit" class="btn ldd-search-btn ldd-btn-fix btn-primary"><?php _e('Search', 'ldd-directory-lite'); ?></button>
                        </span>
                    </div>
                </form>-->
                <?php if(ldl()->get_option( 'view_controls' )=="yes" && !is_single()){?>
                <div class=" ldd_search   des2"> 
                    <a class="ldd_header_view grid " title="Category View" href="?ldd_view=category"><i class="fa fa-th"></i></a>
                    <a class="<?php ?>" title="Listing View" href="?ldd_view=listing"><i class="fa fa-list"></i></a>
                    <?php  
                   
                        if(class_exists("LDD_MAP_Public")){
                        ?>
                        <a class="<?php echo $grid;?>" title="Map View" href="?ldd_view=map"><i class="fa fa-map"></i></a>
                        <?php }
                        ?>
                </div>
                <?php } ?>
        </div>
    </div>
   
    <div class="ldd-search-box ldd_main_search_box">
        <form role="search" method="get" style="" action="<?php echo site_url(); ?>" class="ldd-search-form des1">
                    <input type="hidden" name="post_type" value="<?php echo LDDLITE_POST_TYPE; ?>">
                    <div class="input-group">
                        <input id="directory-search" class="form-control" name="s" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search listings...', 'ldd-directory-lite'); ?>">
                        <span class="input-group-btn">
                            <button type="submit" class="btn ldd-search-btn ldd-btn-fix btn-primary"><?php _e('Search', 'ldd-directory-lite'); ?></button>
                        </span>
                    </div>
         </form>
    </div>
</nav>
<?php wp_enqueue_script('lddlite-masonry');?>
<?php wp_enqueue_script('lddlite-main');?>
