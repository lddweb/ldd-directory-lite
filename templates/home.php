<div class="directory-lite">

    <?php echo ldl_get_header(); ?>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="list-group">
                    <?php echo ldl_get_parent_categories(); ?>
                </div>
            </div>
            <?php if (ldl_get_setting('appearance_display_featured')): ?>
            <div class="col-md-12">

                <h2><?php _e('Featured Listings', 'lddlite'); ?></h2>
                <?php
                $args = array(
                    'post_type'      => LDDLITE_POST_TYPE,
                    'tax_query'      => array(
                        'taxonomy' => LDDLITE_TAX_TAG,
                        'field'    => 'slug',
                        'terms'    => 'featured',
                    ),
                    'orderby'        => 'rand',
                    'posts_per_page' => '3'
                );
                $featured = new WP_Query($args); ?>

                <?php if ($featured->have_posts()): while ($featured->have_posts()): $featured->the_post(); ?>
                    <?php ldl_get_template_part('listing', 'compact'); ?>
                <?php endwhile; endif; ?>

            </div>
            <?php endif; ?>
        </div>

    </div>

</div>
