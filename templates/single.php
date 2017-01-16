<?php
global $geo;
// ldl_use_google_maps() will always return false if this isn't an array containing our lat and lng
$geo = ldl_get_meta('geo');
get_header();
?>
    <?php
        /**
         * ldd_before_main_content hook.
         *
         * @hooked ldd_output_content_wrapper - 10 (outputs opening divs for the content)
         */
        do_action( 'ldd_before_main_content' );
    ?>
        <?php while (have_posts()) : the_post(); ?>

        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header><!-- .entry-header -->

        <?php ldl_get_header(); ?>
        <ol class="breadcrumb bc-ldd">
          <li><a href="<?php echo ldl_get_directory_link(); ?>">Home</a></li>
          <li><a href="#"><?php echo get_the_term_list(get_the_id(),LDDLITE_TAX_CAT,"",", "); ?></a></li>
          <li class="active"><?php the_title(); ?></li>
        </ol>

        <article id="listing-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div><!-- .entry-content -->
                        <div class="listing-meta">
                            <ul class="fa-ul">
                                <?php if (ldl_has_meta('contact_name')): ?><li><i class="fa fa-user fa-li"></i> <?php echo ldl_get_meta( 'contact_name' ); ?></li><?php endif; ?>
                                <?php if (ldl_has_meta('contact_phone')): ?><li><i class="fa fa-phone fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_phone'); ?></li><?php endif; ?>
                                <?php if (ldl_has_meta('contact_fax')): ?><li><i class="fa fa-fax fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_fax'); ?></li><?php endif; ?>
                                <?php if (ldl_has_meta('contact_skype')): ?><li><i class="fa fa-skype fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_skype'); ?></li><?php endif; ?>
                                <?php if (ldl_get_address()): ?><li><i class="fa fa-globe fa-fw fa-li"></i> <?php echo ldl_get_address(); ?></li><?php endif; ?>
                            </ul>
                            <ul class="fa-ul">
                                <?php if (ldl_has_meta('url_website')): ?><li><i class="fa fa-link fa-lg fa-li"></i> <a target="_blank" href="<?php echo ldl_get_meta( 'url_website' ); ?>"><?php echo ldl_get_meta( 'url_website' ); ?></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_facebook')): ?><li><i class="fa fa-facebook fa-lg fa-li"></i> <a target="_blank" href="<?php echo ldl_get_meta( 'url_facebook' ); ?>"><?php echo ldl_get_meta( 'url_facebook' ); ?></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_twitter')): ?><li><i class="fa fa-twitter fa-lg fa-li"></i> <a target="_blank" href="<?php echo ldl_get_meta( 'url_twitter' ); ?>"><?php echo ldl_get_meta( 'url_twitter' ); ?></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_linkedin')): ?><li><i class="fa fa-linkedin fa-lg fa-li"></i> <a target="_blank" href="<?php echo ldl_get_meta( 'url_linkedin' ); ?>"><?php echo ldl_get_meta( 'url_linkedin' ); ?></a></li><?php endif; ?>
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
                            ldl_get_contact_form();
                        }
                        ?>
                    </div>
                </div>

            </div>

        </article>
            
        <?php if ( ldl_use_google_maps() ): ?>
        <script src="<?php echo $google_api_src;?>"></script>
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
        <?php endwhile; // end of the loop. ?>
    <?php
        /**
         * ldd_after_main_content hook.
         *
         * @hooked ldd_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action( 'ldd_after_main_content' );
    ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>