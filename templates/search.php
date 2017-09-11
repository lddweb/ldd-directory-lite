<?php get_header(); ?>
<div class=" bootstrap-wrapper ">
    <?php
        /**
         * ldd_before_main_content hook.
         *
         * @hooked ldd_output_content_wrapper - 10 (outputs opening divs for the content)
         */
        do_action( 'ldd_before_main_content' );
    ?>

        <?php echo ldl_get_header(); 
    ?>

    

        <?php if (have_posts()) : ?>
				
            <?php

            
            while (have_posts()) {
                the_post();
                ldl_get_template_part('loop/listing', 'compact');
            }
            ?>

        <?php else : ?>
            <?php ldl_get_template_part( 'loop/no-listings-found.php' ); ?>
        <?php endif; ?>

    <?php
        /**
         * ldd_after_main_content hook.
         *
         * @hooked ldd_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action( 'ldd_after_main_content' );
    ?>
    </div>
<?php get_footer(); ?>