<?php
/*
* File version: 2
*/
?>
<div class="directory-lite ldd-directory-home bootstrap-wrapper">

    <?php
    echo ldl_get_header();
 $version = 2;
    ?>
<?php 

if($listing_view = ldl()->get_option( 'directory_view_type') =="compact"){
    $compact = "compact";
}
if($listing_view = ldl()->get_option( 'directory_view_type') =="grid"){
    $grid = "grid";
}
if($listing_view = ldl()->get_option( 'home_page_listing') =="category"){
    $category = "category";
}
if($listing_view = ldl()->get_option( 'home_page_listing') =="listing"){
    $listing = "listing";
}

if(ldl()->get_option( 'view_controls' )=="yes"){?>
    <div class="row view_controls">
        <div class="col-md-4"><a class="<?php echo $category;?>" href="?ldd_view=category">Category View</a> | <a class="<?php echo $listing;?>" href="?ldd_view=listing">Listing View</a></div>
       
        <div class="col-md-4">&nbsp;</div>
        
        
        <div class="col-md-4 text-right"><a class="<?php echo $grid;?>" href="?ldd_view=grid">Grid View</a> | <a class="<?php echo $compact;?>" href="?ldd_view=compact">Compact View</a></div>
     </div>
<?php } ?>

    <div class="container-fluid">
        
        <div class="row">


        <?php if (ldl()->get_option('appearance_display_featured') &&  ldl()->get_option('feature_lisitng_position')=="top"):
                $featured = ldl_get_featured_posts(array(), $pass_attr);
                $listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
                if ($featured->have_posts()):
                    ?>
                    <div class="col-md-12 ldd-featured-listings-container">

                        <h2 class="ldd-featured-listings-title"><?php _e(ldl()->get_option('featured_listings_text','Featured Listings'), 'ldd-directory-lite'); ?></h2>
                    </div>
                        <div class="col-md-12 ldd-featured-listings-container">
                        <?php if ( $listing_view == "grid" ) { 
                        //echo "<div class='grid js-isotope' data-isotope-options='{ \"itemSelector\": \".grid-item\", \"layoutMode\": \"fitRows\" }'>";
                        echo "<div class='grid js-isotope2 masonry-cols3'>";
                    } ?>
                        <?php while ($featured->have_posts()): $featured->the_post(); ?>
                            <?php ldl_get_template_part( 'loop/listing', $listing_view ); ?>
                        <?php endwhile; ?>
                        <?php if ( $listing_view == "grid" ) {
				echo "</div>";
				wp_enqueue_script( 'isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js' );
			}?>

                    </div>
                    <?php
                endif;
                wp_reset_postdata();
            endif;?>


        <?php if (ldl()->get_option('home_page_listing') !='listing'):?>
            <div class="col-md-12">
                <div class="list-group">
                <?php
               
               ?>
                     <?php //echo ldl_get_parent_categories($pass_attr); 
                    
                  show_all_cat();

                    ?>
                </div>
                
            </div>
        <?php endif; 
           
            
                
// Directory Listing
   if (ldl()->get_option('home_page_listing') =='listing'):

   
   
                $dir_list = ldl_get_directory_listing();
                if ($dir_list->have_posts()):
                    $listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
                    
                    ?>
                    <div class="col-md-12 ldd-featured-listings-container">

                        <h2 class="ldd-featured-listings-title"><?php _e('Directory Listings', 'ldd-directory-lite'); ?></h2>
                    </div>
                        <div class="col-md-12 ldd-featured-listings-container">
                        <?php if ( $listing_view == "grid" ) { 
                        //echo "<div class='grid js-isotope' data-isotope-options='{ \"itemSelector\": \".grid-item\", \"layoutMode\": \"fitRows\" }'>";
                        echo "<div class='grid js-isotope2 masonry-cols3'>";
                    } ?>
                        <?php while ($dir_list->have_posts()): $dir_list->the_post(); ?>
                            <?php ldl_get_template_part( 'loop/listing', $listing_view ); ?>
                           
                        <?php endwhile;  ?>
                       <?php if ( $listing_view == "grid" ) {
				echo "</div>";
				wp_enqueue_script( 'isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js' );
			}?>

                    </div>
                    <?php ldl_get_template_part( 'loop/pagination' ); do_action( 'ldd_after_directory_loop' );
                endif;
               
                wp_reset_postdata();
                
           endif;
           if(ldl()->get_option( 'listings_display_number') >0){
            the_posts_pagination( array(
      'mid_size' => 2,
      'prev_text' => __( 'Previous', 'textdomain' ),
      'next_text' => __( 'Next', 'textdomain' ),
  ) ); 
           }
            ?>

<?php if (ldl()->get_option('appearance_display_featured') &&  ldl()->get_option('feature_lisitng_position')=="bottom"):
                $featured = ldl_get_featured_posts(array(), $pass_attr);
                $listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
                if ($featured->have_posts()):
                    ?>
                    <div class="col-md-12 ldd-featured-listings-container">

                        <h2 class="ldd-featured-listings-title"><?php _e('Featured Listings', 'ldd-directory-lite'); ?></h2>
                    </div>
                        <div class="col-md-12 ldd-featured-listings-container">
                        <?php if ( $listing_view == "grid" ) { 
                        //echo "<div class='grid js-isotope' data-isotope-options='{ \"itemSelector\": \".grid-item\", \"layoutMode\": \"fitRows\" }'>";
                        echo "<div class='grid js-isotope2'>";
                    } ?>
                        <?php while ($featured->have_posts()): $featured->the_post(); ?>
                            <?php ldl_get_template_part( 'loop/listing', $listing_view ); ?>
                        <?php endwhile; ?>
                        <?php if ( $listing_view == "grid" ) {
				echo "</div>";
				wp_enqueue_script( 'isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js' );
			}?>

                    </div>
                    <?php
                endif;
                wp_reset_postdata();
            endif;
            ?>

        </div>

    </div>

</div>
