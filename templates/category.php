<?php get_header(); ?>

    <section id="primary" class="site-content directory-lite">
        <div id="content" role="main">

            <?php echo ldl_get_header(); ?>

            <div class="col-md-12">
                <div class="list-group">
                    <?php echo ldl_get_categories( get_queried_object()->term_id ); ?>
                </div>
            </div>

            <?php if (have_posts()) : ?>


                <?php
                while (have_posts()) {
                    the_post();
                    ldl_get_template_part('listing', 'compact');
                }
                ?>

            <?php else : ?>

            <?php endif; ?>

        </div>
    </section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

