<article id="listing-<?php get_the_ID(); ?>" class="listing-<?php get_the_ID(); ?> type-listing compact">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <?php echo ldl_get_thumbnail( get_the_ID() ); ?>
            </div>
            <div class="col-sm-10">
                <div class="listing-header row">
                    <div class="col-sm-8">
                        <h2 class="listing-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                        <p class="website"><?php echo ldl_get_meta( 'url_website' ); ?></p>
                        <div class="listing-summary">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                    <div class="col-sm-4 meta-column">
                        <span class="social-meta">
                            <?php echo ldl_get_social( $id, 'default', false ); ?>
                        </span>
                        <ul class="listing-meta fa-ul">
                            <li><i class="fa fa-phone fa-li"></i> <?php echo ldl_get_meta( 'contact_phone' ); ?></li>
                            <li><i class="fa fa-globe fa-li"></i> <?php echo ldl_get_address(); ?></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

</article>
