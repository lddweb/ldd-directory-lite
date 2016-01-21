<?php define( 'WP_USE_THEMES', false ); get_header(); ?>

<section id="primary" class="site-content directory-lite">
    <div id="content" role="main">

        <?php echo ldl_get_header(); ?>

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

<?php get_footer(); ?>