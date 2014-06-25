<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header><!-- .entry-header -->

        <section class="directory-lite directory-listing">

            <header class="directory-header">
                <?php ldl_get_header(); ?>
            </header>

            <article id="listing-{$id}" class="directory-content listing-{$id} listing type-listing listing-full">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">

                            <?php
                            // Let's take a page from some other plugins and turn some of these into do_actions?
                            if ( ldl_use_google_maps() ): ?>
                                <div id="map_wrapper">
                                    <div id="map_canvas"></div>
                                </div>
                            <?php endif; ?>

                            <ul class="listing-meta fa-ul">
                                <li><i class="fa fa-link fa-lg fa-li"></i> http://www.google.com/<?php echo ldl_get_meta( 'url_website' ); ?></li>
                                <li><i class="fa fa-phone fa-lg fa-li"></i> <?php echo ldl_get_meta( 'contact_phone' ); ?></li>
                                <li><i class="fa fa-globe fa-lg fa-li"></i> <?php echo ldl_get_address(); ?></li>
                            </ul>

                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div><!-- .entry-content -->

                            <span class="social-meta">
                                <?php echo ldl_get_social( $post->ID, '' ); ?>
                            </span>

                        </div>
                        <div class="col-md-4">
                            <?php echo ldl_get_thumbnail( $post->ID ); ?>

                            <?php ldl_get_template_part( 'contact', 'sidebar' ); ?>
                        </div>
                    </div>



                </div>



            </article>


        </section>

        <?php if ( ldl_use_google_maps() ): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbaw0hFglsihePOsFpMnLQwJZtOChIoDg&sensor=false"></script>
        <script>

            function initialize() {
                var mapLatLng = new google.maps.LatLng(<?php echo ldl_get_address( 'lat' ); ?>, <?php echo ldl_get_address( 'lng' ); ?>)
            var mapOptions = {
                center: mapLatLng,
                zoom: 16,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: false,
            }
            var map = new google.maps.Map( document.getElementById("map_canvas"), mapOptions )

            var marker = new google.maps.Marker({
                position: mapLatLng,
                map: map,
                animation: google.maps.Animation.DROP,
            })

            }

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <?php endif; ?>

        <?php comments_template( '', true ); ?>

        <?php endwhile; // end of the loop. ?>

    </div><!-- #content -->
</div><!-- #primary -->



<style>
    .listing-meta {
        margin: 0 0 1.5em;
        font-size: 14px;
    }
    .listing-meta li {
        margin: 0 0 10px;
        line-height: 17px;
    }
    .listing-meta li > i {
        color: #999;
    }
    #map_wrapper {
        display: block;
        height: 200px;
        margin-bottom: 1.5em;
    }
    #map_canvas {
        width: 100%;
        height: 100%;
        border-radius: 8px;
    }
    #map_wrapper img,
    #map_canvas img {
        max-width: none;
    }
    .type-listing.listing-full .img-rounded {
        margin: 0 0 1.5em;
        height: auto;
    }

</style>


<?php get_sidebar(); ?>
<?php get_footer(); ?>


