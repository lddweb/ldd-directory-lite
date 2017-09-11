<div class="directory-lite ldd-directory-home bootstrap-wrapper">

    <?php
    echo ldl_get_header();
    $pass_attr = array();
    if (isset($atts) and !empty($atts)):
        $pass_attr = array_filter($atts);
    endif;
    ?>

    <div class="container-fluid">

        <div class="row">
        <?php if (ldl()->get_option('home_page_listing') !='listing'):?>
            <div class="col-md-12">
                <div class="list-group">
                    <?php echo ldl_get_parent_categories($pass_attr); ?>
                </div>
            </div>
        <?php endif; ?>
            <?php if (ldl()->get_option('appearance_display_featured')):
                $featured = ldl_get_featured_posts(array(), $pass_attr);
                if ($featured->have_posts()):
                    ?>
                    <div class="col-md-12 ldd-featured-listings-container">

                        <h2 class="ldd-featured-listings-title"><?php _e('Featured Listings', 'ldd-directory-lite'); ?></h2>

                        <?php while ($featured->have_posts()): $featured->the_post(); ?>
                            <?php ldl_get_template_part('loop/listing', 'compact'); ?>
                        <?php endwhile; ?>

                    </div>
                    <?php
                endif;
                wp_reset_postdata();
            endif;
            
                
// Directory Listing
   if (ldl()->get_option('home_page_listing') =='listing'):
                $dir_list = ldl_get_directory_listing();
                if ($dir_list->have_posts()):
                    ?>
                    <div class="col-md-12 ldd-featured-listings-container">

                        <h2 class="ldd-featured-listings-title"><?php _e('Directory Listings', 'ldd-directory-lite'); ?></h2>

                        <?php while ($dir_list->have_posts()): $dir_list->the_post(); ?>
                            <?php ldl_get_template_part('loop/listing', 'compact'); ?>
                           
                        <?php endwhile;  ?>

                    </div>
                    <?php
                endif;
                wp_reset_postdata();
           endif;
            ?>

            

        </div>

    </div>

</div>
