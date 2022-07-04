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
if($listing_view = ldl()->get_option( 'home_page_listing') =="map"){
    $listing = "map";
}

if(ldl()->get_option( 'view_controls' )=="yes"){?>
    <div class="row view_controls">
        <div class="col-md-4"><a class="" href="?ldd_view=grid">Grid</a> | <a class="<?php echo $listing;?>" href="?ldd_view=compact">Compact</a></div>
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
            endif;   //end feature listing 
			
			//for categoy home page
			 if (ldl()->get_option('home_page_listing') =='category'):?>
        <h2> Categories</h2>

        <?php   if ( $grid == "grid" ) { 
            $per_page   = ldl()->get_option( 'listings_category_number', 10 );
                                if ( get_query_var( 'paged' ) )
                                $paged = get_query_var('paged');
                                else if ( get_query_var( 'page' ) )
                                $paged = get_query_var( 'page' );
                                else
                                $paged = 1;
                                //$per_page    = 2;
                                $number_of_series = count( get_terms( LDDLITE_TAX_CAT,array('hide_empty'=>'0') ) );
                                $offset      = $per_page * ( $paged -1) ;
            
                        //echo "<div class='grid js-isotope' data-isotope-options='{ \"itemSelector\": \".grid-item\", \"layoutMode\": \"fitRows\" }'>";
                        echo "<div class='grid js-isotope2 masonry-cols3'>";
                    
                        
                            ldl_get_template_part( 'loop/category', $grid ); 
                           
                        
                echo "</div>";
                echo "<nav class='ldd_listing_pagination clearfix'>";
                $big = 999999;
                echo paginate_links(apply_filters( 'ldd_pagination_args', array(
                'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'  => '?paged=%#%',
                'current' => $paged,
                'prev_text'    => '&larr;',
                        'next_text'    => '&rarr;',
                        'type'         => 'list',
                'total'   => ceil( $number_of_series / $per_page ) // 3 items per page
                ) ));
                echo "</nav>";
				wp_enqueue_script( 'isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js' );
			?>

                    
                    <?php ldl_get_template_part( 'loop/pagination' ); do_action( 'ldd_after_directory_loop' );
                } else {
                            ?>

                        <div class="col-md-12">
                            <div class="list-group">
                            <?php
                        
                        ?>
                                <?php //echo ldl_get_parent_categories($pass_attr); 
                                
                            ldd_show_all_cat();

                                ?>
                            </div>
                            
                        </div>
                <?php }
                 endif; 
				 //End categoy home page
				 
				 // Directory Listing
   if (ldl()->get_option('home_page_listing') =='listing'):
   
   //pagination
 
//pagination
   
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
                    <?php  echo ldd_pagination($dir_list->max_num_pages); ldl_get_template_part( 'loop/pagination' ); 
					do_action( 'ldd_after_directory_loop' );
                endif;
               
                wp_reset_postdata();
				
				
   endif;
   
   
  
    //End listing home page
	
	
	//Map home page
	 if (ldl()->get_option('home_page_listing') =='map'){?>
            <div class="col-md-12">
                <div id="map_wrapper">
                   <div id="map_canvas1" style="width: 100%; height: 400px;"></div>
                </div>
                
            </div>
            

            
            <?php
                if(class_exists("LDD_MAP_Public")){
            LDD_MAP_Public::ldd_get_addresses();
        }
                //wp_reset_postdata();
            ?>
            <div class="col-md-12" style="margin-top:30px">
            <?php
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
               
                wp_reset_postdata();?>
        </div>
           
            <?php 
			echo ldd_pagination($dir_list->max_num_pages);
			}
			
			//End map view
			
			
			// featured image bottom
			 if (ldl()->get_option('appearance_display_featured') &&  ldl()->get_option('feature_lisitng_position')=="bottom"):
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

            
            
