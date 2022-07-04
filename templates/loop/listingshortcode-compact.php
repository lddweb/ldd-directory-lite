<?php /*
* File version: 2
*/

if ( $query1->have_posts() ) {
    
        
     
     while ($query1->have_posts()) { $query1->the_post();
?>
<article id="listing-<?php echo get_the_ID(); ?>" class="ldd-listing listing-<?php echo get_the_ID(); ?> type-listing compact clearfix">
    <div class="container-fluid">
        <div class="row">
        <?php
        $featured = ldl_get_thumbnail(get_the_ID());
        if(ldl()->get_option("general_display_img_placeholder")!="no" ) { ?>
            <div class="col-sm-2 ldd-thumbnail-left">
                <?php echo ldl_get_thumbnail( get_the_ID() ); ?>
        </div> <?php } ?>
            <div class="col-sm-10">
                <div class="listing-header row">
                    <div class="col-sm-8">
                        <h2 class="listing-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                        <span class="website"><a href="<?php echo ldl_get_meta( 'url_website' ); ?>"><?php echo ldl_get_meta( 'url_website' ); ?></a></span>
                        <div class="listing-summary">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                    <div class="col-sm-4 meta-column">
                        <ul class="listing-meta fa-ul">
                            <?php if (ldl_has_meta('contact_name')): ?><li><i class="fa fa-user fa-li"></i> <?php echo ldl_get_meta( 'contact_name' ); ?></li><?php endif; ?>
                            <?php if (ldl_has_meta('contact_phone')): ?><li><i class="fa fa-phone fa-li"></i> <?php echo ldl_get_meta( 'contact_phone' ); ?></li><?php endif; ?>
                            <?php if (ldl_has_meta('contact_fax')): ?><li><i class="fa fa-fax fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_fax'); ?></li><?php endif; ?>
                            <?php if (ldl_has_meta('contact_skype')): ?><li><i class="fa fa-skype fa-fw fa-li"></i> <?php echo ldl_get_meta('contact_skype'); ?></li><?php endif; ?>
                            <?php if (ldl_get_address()): ?><li><i class="fa fa-globe fa-li"></i> <?php echo ldl_get_address(); ?></li><?php endif; ?>
                        </ul>
                        <?php
                        if(class_exists("LDDReviewscore")){
                            LDDReviewscore::show_ratings(get_the_ID());
                        }
                        ?>

                        <span class="social-meta clearfix">
                            <?php echo ldl_get_social( get_the_ID() ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
                    <?php } 
                    echo "<div class='clearfix'></div>";
                    wp_reset_postdata();
                }?>