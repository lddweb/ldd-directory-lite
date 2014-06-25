<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Twelve already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

    <section id="primary" class="site-content">
        <div id="content" role="main">

            <?php if ( have_posts() ) : ?>
            <section class="directory-lite directory-category">

                <header class="directory-header">
                    <?php echo ldl_get_header(); ?>
                </header>

                <section class="directory-content">
                <?php
                /* Start the Loop */
                while ( have_posts() ) : the_post();

                    ldl_get_template_part( 'listing', 'compact' );

                endwhile;

                ?>

                </section>

            </section>
            <?php else : ?>

            <?php endif; ?>

        </div><!-- #content -->
    </section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

