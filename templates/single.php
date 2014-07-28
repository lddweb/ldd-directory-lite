<?php
global $geo;
// ldl_use_google_maps() will always return false if this isn't an array containing our lat and lng
$geo = ldl_get_meta('geo');

get_header(); ?>

<div id="primary" class="site-content directory-lite">
    <div id="content" role="main">

        <?php while (have_posts()) : the_post(); ?>

        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header><!-- .entry-header -->

        <?php ldl_get_header(); ?>

        <article id="listing-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div><!-- .entry-content -->

                        <div class="listing-meta">
                            <ul class="fa-ul">
                                <?php if (ldl_has_meta('contact_phone')): ?><li><i class="fa fa-phone fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_phone'); ?></li><?php endif; ?>
                                <?php if (ldl_get_address()): ?><li><i class="fa fa-globe fa-fw fa-li"></i> <?php echo ldl_get_address(); ?></li><?php endif; ?>
                            </ul>
                            <ul class="fa-ul">
                                <?php if (ldl_has_meta('url_website')): ?><li><i class="fa fa-link fa-lg fa-li"></i> <a href="<?php echo ldl_get_meta( 'url_website' ); ?>"><?php echo ldl_get_meta( 'url_website' ); ?></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_facebook')): ?><li><i class="fa fa-facebook fa-lg fa-li"></i> <a href="<?php echo ldl_get_meta( 'url_facebook' ); ?>"><?php echo ldl_get_meta( 'url_facebook' ); ?></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_twitter')): ?><li><i class="fa fa-twitter fa-lg fa-li"></i> <a href="<?php echo ldl_get_meta( 'url_twitter' ); ?>"><?php echo ldl_get_meta( 'url_twitter' ); ?></a></li><?php endif; ?>
                                <?php if (ldl_has_meta('url_linkedin')): ?><li><i class="fa fa-linkedin fa-lg fa-li"></i> <a href="<?php echo ldl_get_meta( 'url_linkedin' ); ?>"><?php echo ldl_get_meta( 'url_linkedin' ); ?></a></li><?php endif; ?>
                            </ul>
                        </div>

                        <?php if ( ldl_use_google_maps() ): ?>
                            <div id="map_wrapper">
                                <div id="map_canvas"></div>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="col-md-4">
                        <?php echo ldl_get_thumbnail( $post->ID ); ?>

                        <?php ldl_get_contact_form(); ?>
                    </div>
                </div>

            </div>

        </article>
            
        <?php if ( ldl_use_google_maps() ): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbaw0hFglsihePOsFpMnLQwJZtOChIoDg&sensor=false"></script>
        <script>
            function initialize() {
                var mapLatLng = new google.maps.LatLng(<?php echo $geo['lat']; ?>, <?php echo $geo['lng']; ?>)
                var mapOptions = {
                    center: mapLatLng,
                    zoom: 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    panControl: false
                }
                var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions)

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

<?php get_sidebar(); ?>
<?php get_footer(); ?>


