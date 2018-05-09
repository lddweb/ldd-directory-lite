<?php
global $geo;
// ldl_use_google_maps() will always return false if this isn't an array containing our lat and lng
$geo = ldl_get_meta('geo');
//get_header();
?>
<div class=" bootstrap-wrapper">
    <?php
        /**
         * ldd_before_main_content hook.
         *
         * @hooked ldd_output_content_wrapper - 10 (outputs opening divs for the content)
         */
        //do_action( 'ldd_before_main_content' );
    ?>
      

        <header class="entry-header">
           <!-- <h1 class="entry-title"><?php the_title(); ?></h1>-->
        </header><!-- .entry-header -->

        <?php ldl_get_header(); ?>
        <ol class="breadcrumb bc-ldd">
          <li><a href="<?php echo ldl_get_directory_link(); ?>">Home</a></li>
          <li><a href="#"><?php echo get_the_term_list(get_the_id(),LDDLITE_TAX_CAT,"",", "); ?></a></li>
          <li class="active"><?php the_title(); ?></li>
        </ol>

        <article>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">

                        <div class="listing-content ab"><!--entry-content-->
                            <?php echo  do_shortcode(get_the_content()); ?>
                            <p class="tags"><?php the_tags();  echo ldd_custom_taxonomies_terms_links();
                            ?> </p>
 
   
                        </div><!-- .entry-content -->
                        <div class="listing-meta">
                            <ul class="fa-ul">
                                <?php if (ldl_has_meta('contact_name')): ?><li><i class="fa fa-user fa-li"></i> <?php echo ldl_get_meta( 'contact_name' ); ?></li><?php endif; ?>
                                <?php if (ldl_has_meta('contact_phone')): ?><li><i class="fa fa-phone fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_phone'); ?></li><?php endif; ?>
                                <?php if (ldl_has_meta('contact_fax')): ?><li><i class="fa fa-fax fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_fax'); ?></li><?php endif; ?>
                                <?php if (ldl_has_meta('contact_skype')): ?><li><i class="fa fa-skype fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_skype'); ?></li><?php endif; ?>
                                <?php if (ldl_get_address()): ?><li><i class="fa fa-globe fa-fw fa-li"></i> <?php echo ldl_get_address(); ?></li><?php endif; ?>
                            </ul>
                            <ul class="fa-ul fa-ul-social">
                                <?php if (ldl_has_meta('url_website')): ?><li> <a target="_blank" href="<?php echo ldl_get_meta( 'url_website' ); ?>" title="Website"><i class="fa fa-home fa-lg "></i></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_facebook')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_facebook' ); ?>" title="Facebook"><i class="fa fa-facebook fa-lg "></i> </a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_twitter')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_twitter' ); ?>" title="Twitter"><i class="fa fa-twitter fa-lg "> </i></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_linkedin')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_linkedin' ); ?>" title="LinkedIn"><i class="fa fa-linkedin fa-lg "></i> </a></li><?php endif; ?>
                                 <?php if (ldl_has_meta('url_googleplus')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_googleplus' ); ?>" title="Google +"><i class="fa fa-google-plus fa-lg "></i> </a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_instagram')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_instagram' ); ?>" title="Instagram"><i class="fa fa-instagram fa-lg "></i> </a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_youtube')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_youtube' ); ?>" title="Youtube"><i class="fa fa-youtube fa-lg "></i> </a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_custom')): ?><li><a target="_blank" href="<?php echo ldl_get_meta( 'url_custom' ); ?>" title=""><i class="fa fa-link fa-lg "></i> </a></li><?php endif; ?>
                                
                            </ul>
                        </div>

                        <?php if ( ldl_use_google_maps() ): ?>
                            <div id="map_wrapper">
                                <div id="map_canvas"></div>
                            </div>
                        <?php endif; ?>

                        <!--START-->
                        <?php
                           if(class_exists("LDDReviewscore")){
                                 LDDReviewscore::show_ratings_single_page_content(get_the_ID());
                           }
                        ?>
                        <!--END-->

                    </div>
                    <div class="col-md-4">
                        <?php
                        echo ldl_get_thumbnail( $post->ID );

                        $sidebar_shortcode = ldl()->get_option('appearance_sidebar_shortcode', '');
                        if(isset($sidebar_shortcode) and !empty($sidebar_shortcode)) {
                            
                            echo do_shortcode($sidebar_shortcode);
                        }else {
                            echo ldl_get_contact_form();
                            
                            
                        }
                        ?>
                    </div>
                </div>

            </div>

        </article>
           
        <?php if ( ldl_use_google_maps() ): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo LDDLITE_GOOGLE_SCRIPT; ?>"></script>
        
        <script>
            function initialize() {
                var mapLatLng = new google.maps.LatLng(<?php echo $geo['lat']; ?>, <?php echo $geo['lng']; ?>);
                var mapOptions = {
                    center: mapLatLng,
                    zoom: 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    panControl: false
                };
                var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

                var marker = new google.maps.Marker({
                    position: mapLatLng,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <?php endif; ?>
            <?php comments_template( '', true ); ?>
       
    <?php
        /**
         * ldd_after_main_content hook.
         *
         * @hooked ldd_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        //do_action( 'ldd_after_main_content' );
    ?>

<?php //get_sidebar(); ?>
</div>
<?php //get_footer(); ?>