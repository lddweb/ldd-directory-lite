    <div id="listing-<?php echo get_the_ID(); ?>" class="col-xs-12 col-sm-6 col-md-4 type-grid grid-item">
        <div class="thumbnail">
            <?php
            $thumbnail_src = ldl_get_thumbnail( get_the_ID() );
            if($thumbnail_src) {
                echo $thumbnail_src." <hr /> ";
            }
            ?>
            <div class="caption text-left">
                <h3 class="listing-title grid-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                <div class="listing-meta meta-column">
                    <ul class="listing-meta fa-ul">
                        <?php if (ldl_has_meta('contact_phone')): ?><li><i class="fa fa-phone fa-li"></i> <?php echo ldl_get_meta( 'contact_phone' ); ?></li><?php endif; ?>
                        <?php if (ldl_has_meta('contact_fax')): ?><li><i class="fa fa-fax fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_fax'); ?></li><?php endif; ?>
                        <?php if (ldl_has_meta('contact_skype')): ?><li><i class="fa fa-skype fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_skype'); ?></li><?php endif; ?>
                        <?php if (ldl_get_address()): ?><li><i class="fa fa-globe fa-li"></i> <?php echo ldl_get_address(); ?></li><?php endif; ?>
                        <li class="grid_socials"><?php echo ldl_get_social( get_the_ID() ); ?></li>
                    </ul>
                    <?php
                    if(class_exists("LDDReviewscore")){
                       LDDReviewscore::show_ratings(get_the_ID());
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>